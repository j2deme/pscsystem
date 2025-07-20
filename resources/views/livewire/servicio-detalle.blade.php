@php
$servicio = $servicio ?? null;
@endphp
<x-livewire.monitoreo-layout :breadcrumb-items="[
    ['icon' => 'ti-home', 'url' => route('dashboard')],
    ['icon' => 'ti-tool', 'url' => route('servicios.index'), 'label' => 'Servicios y Reparaciones'],
    ['icon' => 'ti-eye', 'label' => 'Detalle Servicio']
]" title-main="Detalle de Servicio" help-text="Información completa y estado del servicio seleccionado">
    <div class="grid max-w-5xl grid-cols-1 gap-6 mx-auto md:grid-cols-2">
        <!-- Columna 1: Unidad asociada -->
        <div class="p-6 bg-white shadow-md rounded-xl dark:bg-gray-800">
            <div class="flex items-center gap-2 mb-3 text-base font-bold text-blue-700 dark:text-blue-300">
                <i class="text-lg ti ti-car"></i> Unidad asociada
            </div>
            @if($unidad)
            <div class="space-y-2 text-gray-700 dark:text-gray-200">
                <div><span class="font-semibold">Placas:</span> {{ $unidad['placas'] }}</div>
                <div><span class="font-semibold">Marca:</span> {{ $unidad['marca'] }}</div>
                <div><span class="font-semibold">Modelo:</span> {{ $unidad['modelo'] }}</div>
                <a href="{{ route('vehiculos.detalle', ['id' => $servicio->unidad_id]) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 mt-2 text-white bg-blue-400 rounded-lg shadow hover:bg-blue-500"
                    title="Ver vehículo">
                    <i class="ti ti-car"></i> Ver vehículo
                </a>
            </div>
            @else
            <div class="text-gray-500 dark:text-gray-400">Unidad #{{ $servicio->unidad_id }}</div>
            @endif
        </div>
        <!-- Columna 2: Datos generales del servicio -->
        <div class="p-6 bg-white shadow-md rounded-xl dark:bg-gray-800">
            <div class="flex items-center gap-2 mb-3 text-base font-bold text-blue-700 dark:text-blue-300">
                <i class="text-lg ti ti-tool"></i> Datos del servicio
            </div>
            <div class="space-y-2 text-gray-700 dark:text-gray-200">
                @php
                $badgeColors = [
                'Preventivo' => 'bg-green-100 text-green-800',
                'Correctivo' => 'bg-yellow-100 text-yellow-800',
                'Incidencia' => 'bg-red-100 text-red-800',
                'Otros' => 'bg-gray-100 text-gray-800',
                ];
                $tipo = $servicio->tipo;
                $color = $badgeColors[$tipo] ?? 'bg-gray-100 text-gray-800';
                @endphp
                <div>
                    <span class="font-semibold">Tipo:</span>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $color }} ml-2">
                        {{ $tipo }}
                    </span>
                </div>
                <div><span class="font-semibold">Fecha:</span> {{
                    \Carbon\Carbon::parse($servicio->fecha)->format('d/m/Y') }}</div>
                <div><span class="font-semibold">Responsable / Taller:</span> {{ $servicio->responsable }}</div>
                <div><span class="font-semibold">Costo:</span> ${{ number_format($servicio->costo, 2) }}</div>
            </div>
        </div>
    </div>
    <div class="grid max-w-5xl grid-cols-1 gap-6 mx-auto mt-8 md:grid-cols-2">
        <!-- Columna 1: Descripción -->
        @if(!empty($servicio->observaciones))
        <div class="p-6 bg-gray-50 shadow-md rounded-xl dark:bg-gray-900">
            <div class="flex items-center gap-2 mb-2 font-bold text-blue-700 dark:text-blue-300">
                <i class="text-lg ti ti-file-description"></i>
                Descripción
            </div>
            <div
                class="p-3 overflow-y-auto text-gray-800 bg-white border border-gray-200 rounded-lg dark:text-gray-200 max-h-32 dark:bg-gray-800 dark:border-gray-700">
                {!! nl2br(e($servicio->descripcion)) !!}
            </div>
        </div>
        <!-- Columna 2: Observaciones -->
        <div class="p-6 bg-gray-50 shadow-md rounded-xl dark:bg-gray-900">
            <div class="flex items-center gap-2 mb-2 font-bold text-blue-700 dark:text-blue-300">
                <i class="text-lg ti ti-message-dots"></i>
                Observaciones
            </div>
            <div
                class="p-3 overflow-y-auto text-gray-800 bg-white border border-gray-200 rounded-lg dark:text-gray-200 max-h-24 dark:bg-gray-800 dark:border-gray-700">
                {!! nl2br(e($servicio->observaciones)) !!}
            </div>
        </div>
        @else
        <div class="p-6 bg-gray-50 shadow-md rounded-xl dark:bg-gray-900 md:col-span-2">
            <div class="flex items-center gap-2 mb-2 font-bold text-blue-700 dark:text-blue-300">
                <i class="text-lg ti ti-file-description"></i>
                Descripción
            </div>
            <div
                class="p-3 overflow-y-auto text-gray-800 bg-white border border-gray-200 rounded-lg dark:text-gray-200 max-h-32 dark:bg-gray-800 dark:border-gray-700">
                {!! nl2br(e($servicio->descripcion)) !!}
            </div>
        </div>
        @endif
    </div>
    <!-- Botones de acción -->
    <div class="flex justify-end max-w-4xl gap-4 mx-auto mt-8">
        <a href="{{ route('servicios.index', ['editar' => $servicio->id, 'return' => 'detalle']) }}"
            class="flex items-center gap-2 px-5 py-2 text-white bg-blue-400 rounded-lg shadow hover:bg-blue-500"
            title="Editar">
            <i class="ti ti-edit"></i> Editar
        </a>
        <a href="{{ route('servicios.index') }}"
            class="flex items-center gap-2 px-5 py-2 text-gray-700 bg-gray-100 border border-gray-300 rounded-lg shadow hover:bg-gray-200"
            title="Regresar">
            <i class="ti ti-arrow-left"></i> Regresar
        </a>
    </div>
</x-livewire.monitoreo-layout>