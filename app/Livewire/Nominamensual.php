<?php
/*
namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Nomina;
use Carbon\Carbon;

class Nominamensual extends Component
{
    public float $totalMesActual = 0;
    public float $totalMesAnterior = 0;
    public float $variacion = 0;

    public function calcularResumen()
    {
        $hoy = now();
        $mesActual = $hoy->format('m');
        $anioActual = $hoy->format('Y');

        $this->totalMesActual = Nomina::whereYear('created_at', $anioActual)
            ->whereMonth('created_at', $mesActual)
            ->sum('monto');

        $mesAnterior = $hoy->copy()->subMonth();
        $this->totalMesAnterior = Nomina::whereYear('created_at', $mesAnterior->year)
            ->whereMonth('created_at', $mesAnterior->month)
            ->sum('monto');

        if ($this->totalMesAnterior > 0) {
            $this->variacion = round((($this->totalMesActual - $this->totalMesAnterior) / $this->totalMesAnterior) * 100, 2);
        } else {
            $this->variacion = 0;
        }
    }

    public function mount()
    {
        $this->calcularResumen();
    }

    public function render()
    {
        return view('livewire.nominamensual');
    }
}<?php*/

namespace App\Livewire;

use Livewire\Component;
use App\Models\Archivonomina;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Nominamensual extends Component
{
    public $totalMesActual = 0;
    public $variacion = 0;

    public function mount()
    {
        \Log::info('Iniciando carga de componente Nominamensual');
        $this->calcularTotalNomina();
        $this->calcularVariacion();
    }

    private function calcularTotalNomina()
    {
        \Log::info('Buscando registro más reciente de nómina');

        // Obtener el registro más reciente
        $registroMasReciente = Archivonomina::latest('created_at')->first();

        if ($registroMasReciente) {
            \Log::info('Registro encontrado', [
                'id' => $registroMasReciente->id,
                'periodo' => $registroMasReciente->periodo,
                'arch_nomina' => $registroMasReciente->arch_nomina,
                'created_at' => $registroMasReciente->created_at
            ]);

            if ($registroMasReciente->arch_nomina) {
                $this->totalMesActual = $this->calcularTotalDesdeExcel($registroMasReciente->arch_nomina);
                \Log::info('Total calculado', ['total' => $this->totalMesActual]);
            } else {
                \Log::warning('No hay archivo de nómina en el registro');
            }
        } else {
            \Log::warning('No se encontraron registros de nómina');
        }
    }

    private function calcularTotalDesdeExcel($rutaArchivo)
{
    try {
        \Log::info('Intentando cargar archivo Excel', ['ruta_db' => $rutaArchivo]);

        // Construir la ruta completa correctamente
        $rutaCompleta = storage_path('app/public/' . $rutaArchivo);
        \Log::info('Ruta completa construida', ['ruta_completa' => $rutaCompleta]);

        // Verificar si el archivo existe
        if (!file_exists($rutaCompleta)) {
            \Log::error('Archivo no encontrado', ['ruta_completa' => $rutaCompleta]);
            return 0;
        }

        \Log::info('Archivo encontrado, cargando Excel...');
        $spreadsheet = IOFactory::load($rutaCompleta);

        // Verificar cuántas hojas tiene el archivo
        $numeroHojas = $spreadsheet->getSheetCount();
        $nombresHojas = $spreadsheet->getSheetNames();
        \Log::info('Archivo cargado exitosamente', [
            'numero_hojas' => $numeroHojas,
            'nombres_hojas' => $nombresHojas
        ]);

        $totalGeneral = 0;

        // Iterar por todas las hojas
        for ($hojaIndex = 0; $hojaIndex < $numeroHojas; $hojaIndex++) {
            $worksheet = $spreadsheet->getSheet($hojaIndex);
            $nombreHoja = $nombresHojas[$hojaIndex];
            \Log::info('Procesando hoja', [
                'indice' => $hojaIndex,
                'nombre' => $nombreHoja
            ]);

            $totalHoja = 0;
            $fila = 5;
            $espaciosBlancoSeguidos = 0;
            $maxEspaciosBlanco = 3;

            \Log::info('Iniciando iteración desde fila 5 en hoja: ' . $nombreHoja);

            // Iterar hasta encontrar 3 espacios en blanco seguidos
            $filasProcesadas = 0;
            while ($espaciosBlancoSeguidos < $maxEspaciosBlanco && $fila < 1000) {
                $nombreEmpleado = $worksheet->getCell('B' . $fila)->getValue();

                // OBTENER EL VALOR CALCULADO en lugar de la fórmula
                $celdaP = $worksheet->getCell('P' . $fila);
                $valorP = $celdaP->getCalculatedValue();

                \Log::debug('Procesando fila en hoja ' . $nombreHoja, [
                    'fila' => $fila,
                    'nombre_empleado' => $nombreEmpleado,
                    'formula_p' => $celdaP->getValue(),
                    'valor_calculado_p' => $valorP,
                    'espacios_blancos' => $espaciosBlancoSeguidos
                ]);

                // Verificar si la celda del nombre está vacía
                if (empty($nombreEmpleado) || trim($nombreEmpleado) === '') {
                    $espaciosBlancoSeguidos++;
                    \Log::debug('Fila vacía encontrada en hoja ' . $nombreHoja, [
                        'fila' => $fila,
                        'espacios_consecutivos' => $espaciosBlancoSeguidos
                    ]);
                } else {
                    $espaciosBlancoSeguidos = 0;

                    // Obtener el valor total de la columna P (ya calculado)
                    if (is_numeric($valorP)) {
                        $totalHoja += $valorP;
                        $totalGeneral += $valorP;
                        \Log::debug('Valor sumado en hoja ' . $nombreHoja, [
                            'fila' => $fila,
                            'valor' => $valorP,
                            'total_hoja' => $totalHoja,
                            'total_general' => $totalGeneral
                        ]);
                    } else {
                        \Log::debug('Valor no numérico en columna P en hoja ' . $nombreHoja, [
                            'fila' => $fila,
                            'valor' => $valorP
                        ]);
                    }
                }

                $fila++;
                $filasProcesadas++;

                // Seguridad para evitar bucles infinitos
                if ($filasProcesadas > 500) {
                    \Log::warning('Límite de filas procesadas alcanzado en hoja ' . $nombreHoja, [
                        'filas' => $filasProcesadas
                    ]);
                    break;
                }
            }

            \Log::info('Hoja procesada completamente', [
                'nombre_hoja' => $nombreHoja,
                'total_hoja' => $totalHoja,
                'filas_procesadas' => $filasProcesadas
            ]);
        }

        \Log::info('Cálculo completado para todas las hojas', [
            'total_general' => $totalGeneral,
            'numero_hojas_procesadas' => $numeroHojas
        ]);

        return $totalGeneral;

    } catch (\Exception $e) {
        \Log::error('Error al calcular total de nómina: ' . $e->getMessage(), [
            'exception' => $e,
            'ruta_archivo' => $rutaArchivo ?? 'no definida'
        ]);
        return 0;
    }
}
    private function calcularVariacion()
    {
        \Log::info('Calculando variación porcentual');

        $registros = Archivonomina::latest('created_at')->limit(2)->get();

        if ($registros->count() >= 2) {
            $registroActual = $registros[0];
            $registroAnterior = $registros[1];

            \Log::info('Registros encontrados para variación', [
                'actual_id' => $registroActual->id,
                'anterior_id' => $registroAnterior->id
            ]);

            if ($registroActual->arch_nomina && $registroAnterior->arch_nomina) {
                $totalActual = $this->calcularTotalDesdeExcel($registroActual->arch_nomina);
                $totalAnterior = $this->calcularTotalDesdeExcel($registroAnterior->arch_nomina);

                \Log::info('Totales para variación', [
                    'actual' => $totalActual,
                    'anterior' => $totalAnterior
                ]);

                if ($totalAnterior > 0) {
                    $this->variacion = round((($totalActual - $totalAnterior) / $totalAnterior) * 100, 2);
                    \Log::info('Variación calculada', ['porcentaje' => $this->variacion]);
                }
            }
        } else {
            \Log::warning('No hay suficientes registros para calcular variación', ['cantidad' => $registros->count()]);
        }
    }

    public function render()
    {
        return view('livewire.nominamensual');
    }
}
