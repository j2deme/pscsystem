<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Archivonomina;
use Carbon\Carbon;

class DestajoMensual extends Component
{
    public string $filtro = 'todos';
    public array $labels = [];
    public array $periodo1 = [];
    public array $periodo2 = [];
    public float $total = 0;
    public bool $readyToLoad = false;

    public function render()
    {
        return view('livewire.destajo-mensual');
    }

    public function cargarGrafica()
    {
        $this->readyToLoad = true;
        $this->actualizarGrafica();
    }

    public function updatedFiltro()
    {
        if ($this->readyToLoad) {
            $this->actualizarGrafica();
        }
    }

    public function actualizarGrafica()
    {
        \Log::info('Iniciando actualización de gráfica de destajos', [
            'filtro' => $this->filtro,
            'anio_actual' => now()->year
        ]);

        $anioActual = now()->year;

        // Reiniciar arrays
        if ($this->filtro === 'todos') {
            $this->labels = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
            $this->periodo1 = array_fill(0, 12, 0);
            $this->periodo2 = array_fill(0, 12, 0);
        } else {
            $this->labels = ['Periodo 1', 'Periodo 2'];
            $this->periodo1 = [0, 0]; // Dos elementos para periodo 1 y 2
            $this->periodo2 = [0, 0]; // Dos elementos para periodo 1 y 2
        }

        $this->total = 0;

        // Construir query con filtro por año y mes si aplica
        $query = Archivonomina::whereYear('created_at', $anioActual);

        // Si hay un filtro específico de mes, aplicarlo al periodo
        if ($this->filtro !== 'todos') {
            $query->where('periodo', 'like', '%' . ucfirst($this->filtro) . '%');
        }

        // Solo obtener registros que tengan total_destajos calculado
        $archivosDestajos = $query->whereNotNull('total_destajos')->get();

        \Log::info('Archivos encontrados con total_destajos', [
            'cantidad' => $archivosDestajos->count(),
            'filtro' => $this->filtro
        ]);

        foreach ($archivosDestajos as $archivoDestajo) {
            \Log::info('Procesando archivo con total_destajos', [
                'id' => $archivoDestajo->id,
                'periodo' => $archivoDestajo->periodo,
                'total_destajos' => $archivoDestajo->total_destajos
            ]);

            // Adaptar la expresión regular para el formato "2° Julio" (sin año)
            if (preg_match('/^(1°|2°)\s*(\w+)(?:\s+(\d{4}))?$/u', $archivoDestajo->periodo, $matches)) {
                $quincena = $matches[1];
                $mesTexto = $matches[2];
                $anio = $matches[3] ?? $anioActual; // Si no hay año, usar el año actual

                \Log::info('Datos extraídos del periodo', [
                    'quincena' => $quincena,
                    'mes' => $mesTexto,
                    'anio' => $anio
                ]);

                // Verificar que sea del año actual
                if ((int) $anio !== $anioActual) {
                    \Log::info('Año no coincide, saltando', ['anio_archivo' => $anio, 'anio_actual' => $anioActual]);
                    continue;
                }

                // USAR EL TOTAL_DESTAJOS PRE-CALCULADO EN LUGAR DE SUBTOTAL
                $totalCalculado = $archivoDestajo->total_destajos ?? 0;

                \Log::info('Total obtenido del total_destajos', [
                    'total' => $totalCalculado,
                    'total_destajos_db' => $archivoDestajo->total_destajos
                ]);

                if ($this->filtro === 'todos') {
                    // Modo todos: agrupar por mes
                    $mesIndex = $this->mesTextoANumero($mesTexto) - 1;
                    if ($mesIndex >= 0 && $mesIndex <= 11) {
                        if ($quincena === '1°') {
                            $this->periodo1[$mesIndex] += $totalCalculado;
                            \Log::info('Agregando a periodo 1 todos', [
                                'mes' => $mesTexto,
                                'indice' => $mesIndex,
                                'valor' => $totalCalculado,
                                'total_acumulado' => $this->periodo1[$mesIndex]
                            ]);
                        } elseif ($quincena === '2°') {
                            $this->periodo2[$mesIndex] += $totalCalculado;
                            \Log::info('Agregando a periodo 2 todos', [
                                'mes' => $mesTexto,
                                'indice' => $mesIndex,
                                'valor' => $totalCalculado,
                                'total_acumulado' => $this->periodo2[$mesIndex]
                            ]);
                        }
                    }
                } else {
                    // Modo filtro específico: mostrar solo el mes filtrado
                    if ($this->normalizarTexto($mesTexto) === $this->normalizarTexto($this->filtro)) {
                        // Para el filtro específico, usar índices 0 para periodo 1 y 1 para periodo 2
                        if ($quincena === '1°') {
                            $this->periodo1[0] += $totalCalculado; // Índice 0 para Periodo 1
                            $this->periodo2[0] += 0; // Mantener 0 en periodo 2
                            \Log::info('Agregando a periodo 1 filtro específico', [
                                'mes' => $mesTexto,
                                'valor' => $totalCalculado,
                                'total_acumulado_periodo1' => $this->periodo1[0]
                            ]);
                        } elseif ($quincena === '2°') {
                            $this->periodo1[1] += 0; // Mantener 0 en periodo 1
                            $this->periodo2[1] += $totalCalculado; // Índice 1 para Periodo 2
                            \Log::info('Agregando a periodo 2 filtro específico', [
                                'mes' => $mesTexto,
                                'valor' => $totalCalculado,
                                'total_acumulado_periodo2' => $this->periodo2[1]
                            ]);
                        }
                    }
                }
            } else {
                \Log::warning('No se pudo parsear el periodo', [
                    'periodo' => $archivoDestajo->periodo,
                    'patron_esperado' => '^(1°|2°)\\s*(\\w+)(?:\\s+(\\d{4}))?$'
                ]);
            }
        }

        $this->total = array_sum($this->periodo1) + array_sum($this->periodo2);

        \Log::info('Datos finales para la gráfica de destajos', [
            'labels' => $this->labels,
            'periodo1' => $this->periodo1,
            'periodo2' => $this->periodo2,
            'total' => $this->total
        ]);

        $this->dispatch('chart-destajos-updated', [
            'labels' => $this->labels,
            'periodo1' => $this->periodo1,
            'periodo2' => $this->periodo2,
            'total' => $this->total
        ]);
    }

    // Agregar este método para normalizar texto (sin acentos)
    private function normalizarTexto($texto) {
        if (!$texto) return '';

        $sinAcentos = str_replace(
            ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
            ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'],
            $texto
        );
        return strtolower(trim($sinAcentos));
    }

    private function mesTextoANumero(string $mes): int
    {
        $mesNormalizado = $this->normalizarTexto($mes);

        $meses = [
            'enero' => 1, 'febrero' => 2, 'marzo' => 3, 'abril' => 4,
            'mayo' => 5, 'junio' => 6, 'julio' => 7, 'agosto' => 8,
            'septiembre' => 9, 'octubre' => 10, 'noviembre' => 11, 'diciembre' => 12,
        ];

        return $meses[$mesNormalizado] ?? 0;
    }

    /**
     * MANTENEMOS EL MÉTODO PARA COMPATIBILIDAD CON REGISTROS ANTIGUOS
     * (Solo se usará si algún registro no tiene total_destajos)
     */
    private function calcularTotalDesdeExcel($rutaArchivo)
    {
        \Log::info('Usando fallback calcularTotalDesdeExcel para destajos (no debería ocurrir)');
        return 0;
    }
}
