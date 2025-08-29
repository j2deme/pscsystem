<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Compra;
use App\Models\Unidades;

class CompraDetalle extends Component
{
    public $compraId;
    public $compra;
    public $unidad;
    // Propiedades para métricas
    public $frecuenciaTipoEnUnidad;
    public $gastoAcumuladoUnidad;
    public $historialServiciosUnidad;

    protected $listeners = ['verDetalleCompra' => 'cargarDetalle'];

    public function mount($id)
    {
        $this->cargarDetalle($id);
    }

    public function cargarDetalle($id)
    {
        // Cargar la compra con su relación a unidad
        $compra = Compra::with(['unidad'])->findOrFail($id);

        $this->compra   = $compra;
        $this->compraId = $id;
        $this->unidad   = $compra->unidad;

        // Calcular métricas si hay unidad asociada
        if ($this->unidad) {
            $this->calcularMetricas();
        }
    }

    private function calcularMetricas()
    {
        $unidadId = $this->unidad->id;

        // 1. Frecuencia de servicios similares en esta unidad
        $this->frecuenciaTipoEnUnidad = Compra::where('unidad_id', $unidadId)
            ->where('tipo', $this->compra->tipo)
            ->count();

        // 2. Gasto acumulado en esta unidad (últimos 12 meses)
        $this->gastoAcumuladoUnidad = Compra::where('unidad_id', $unidadId)
            ->where('fecha_hora', '>=', now()->subYear())
            ->whereNotNull('costo')
            ->sum('costo');

        // 3. Historial de últimos 5 servicios de la unidad (excluyendo el actual)
        $this->historialServiciosUnidad = Compra::where('unidad_id', $unidadId)
            ->where('id', '!=', $this->compraId)
            ->orderByDesc('fecha_hora')
            ->limit(5)
            ->get();
    }

    // Método para obtener el icono del tipo de compra
    public function getIconoTipo($tipo)
    {
        return match ($tipo) {
            'Refacción' => 'ti-bolt',
            'Insumo' => 'ti-box',
            'Servicio Menor' => 'ti-asset',
            'Servicio Mayor' => 'ti-engine',
            'Compra Directa' => 'ti-shopping-cart',
            'Mantenimiento' => 'ti-gauge',
            'Reparación' => 'ti-tool',
            'Verificación' => 'ti-checklist',
            'Afinación' => 'ti-adjustments',
            'Cambio de Llantas' => 'ti-car-4wd-filled',
            'Hojalatería y Pintura' => 'ti-spray',
            'Siniestro' => 'ti-car-crash',
            default => 'ti-basket-plus'
        };
    }

    // Método para obtener los colores del badge del tipo de compra
    public function getBadgeColores($tipo)
    {
        return match ($tipo) {
            'Mantenimiento', 'Afinación', 'Servicio Menor', 'Servicio Mayor' => ['bg-blue-100', 'text-blue-800'],
            'Reparación', 'Hojalatería y Pintura' => ['bg-green-100', 'text-green-800'],
            'Siniestro' => ['bg-red-100', 'text-red-800'],
            'Refacción', 'Insumo', 'Compra Directa', 'Cambio de Llantas' => ['bg-yellow-100', 'text-yellow-800'],
            'Verificación' => ['bg-purple-100', 'text-purple-800'],
            default => ['bg-gray-100', 'text-gray-800']
        };
    }

    public function render()
    {
        $data = [
            'compra' => $this->compra,
            'unidad' => $this->unidad,
            'frecuenciaTipoEnUnidad' => $this->frecuenciaTipoEnUnidad,
            'gastoAcumuladoUnidad' => $this->gastoAcumuladoUnidad,
            'historialServiciosUnidad' => $this->historialServiciosUnidad,
            'breadcrumbItems' => [
                ['icon' => 'ti-home', 'url' => route('dashboard')],
                ['icon' => 'ti-shopping-cart', 'url' => route('gastos.index'), 'label' => 'Compras'], // Ajusta la ruta si es diferente
                ['icon' => 'ti-eye', 'label' => 'Detalle de Compra']
            ],
            'titleMain' => 'Detalle de Compra/Servicio',
            'helpText' => 'Información detallada del registro de compra o servicio.'
        ];

        return view('livewire.compra-detalle', $data)
            ->layout('layouts.app'); // Ajusta el layout si es necesario
    }
}