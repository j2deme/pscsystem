@php
$servicio = $servicio ?? null;
@endphp
<div class="py-6 mx-auto">
    <x-navbar />
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
                    <dl class="space-y-1">
                        <div>
                            <dt class="font-semibold inline">Placas:</dt>
                            <dd class="inline ml-1">{{ $unidad['placas'] }}</dd>
                        </div>
                        <div>
                            <dt class="font-semibold inline">Marca:</dt>
                            <dd class="inline ml-1">{{ $unidad['marca'] }}</dd>
                        </div>
                        <div>
                            <dt class="font-semibold inline">Modelo:</dt>
                            <dd class="inline ml-1">{{ $unidad['modelo'] }}</dd>
                        </div>
                    </dl>
                    <div class="flex justify-end">
                        <a href="{{ route('vehiculos.detalle', ['id' => $servicio->unidad_id]) }}"
                            class="inline-flex items-center gap-2 px-4 py-2 mt-2 text-white bg-blue-400 rounded-lg shadow hover:bg-blue-500"
                            title="Ver vehículo">
                            <i class="ti ti-car"></i> Ver vehículo
                        </a>
                    </div>
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
                    $tipo = $servicio->tipo;
                    $color = $estilos[$tipo]['badge'] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <dl class="space-y-1">
                        <div>
                            <dt class="font-semibold inline">Tipo:</dt>
                            <dd class="inline ml-1">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $color }}">
                                    <i
                                        class="ti {{ $estilos[$tipo]['icon'] }} {{ $estilos[$tipo]['iconColor'] }} mr-1"></i>
                                    {{ $tipo }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="font-semibold inline">Fecha:</dt>
                            <dd class="inline ml-1">{{ $servicio->fecha->format('d/m/Y') }}</dd>
                        </div>
                        @if(!empty($servicio->responsable))
                        <div>
                            <dt class="font-semibold inline">Lugar de reparación:</dt>
                            <dd class="inline ml-1">{{ $servicio->responsable }}</dd>
                        </div>
                        @endif
                        @if(!empty($servicio->costo) && $servicio->costo > 0)
                        <div>
                            <dt class="font-semibold inline">Costo:</dt>
                            <dd class="inline ml-1">${{ number_format($servicio->costo, 2) }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
                <div class="flex justify-end">
                    <a href="{{ route('servicios.index', ['editar' => $servicio->id, 'return' => 'detalle']) }}"
                        class="flex items-center gap-2 px-5 py-2 text-white bg-blue-400 rounded-lg shadow hover:bg-blue-500"
                        title="Editar">
                        <i class="ti ti-edit"></i> Editar
                    </a>
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
        <!-- Bitácora de servicios de la unidad -->
        <div class="max-w-5xl mx-auto mt-8">
            <div class="mb-4 text-lg font-bold text-blue-700 dark:text-blue-300 flex items-center gap-2">
                <i class="ti ti-timeline"></i> Bitácora de servicios de la unidad
            </div>
            <div class="flex flex-col md:flex-row items-center justify-center gap-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full items-stretch">
                    <!-- Servicio más reciente (siguiente) -->
                    <div class="flex flex-col items-center w-full">
                        <div class="text-xs text-gray-500 mb-1">Servicio siguiente</div>
                        @if($servicioSiguiente)
                        <div
                            class="flex flex-col items-center p-4 rounded-xl border shadow-lg {{ $estilos[$servicioSiguiente->tipo]['border'] }} {{ $estilos[$servicioSiguiente->tipo]['bg'] }} w-full">
                            <div class="flex flex-col items-center mb-2 w-full relative">
                                <!-- Fecha en la esquina superior derecha -->
                                <div
                                    class="absolute top-0 right-0 flex items-center gap-1 text-blue-600 dark:text-blue-300 text-xs font-semibold">
                                    <i class="ti ti-calendar"></i>
                                    {{ $servicioSiguiente->fecha->format('d/m/Y') }}
                                </div>
                                <div
                                    class="w-12 h-12 flex items-center justify-center rounded-full bg-white shadow border-2 {{ $estilos[$servicioSiguiente->tipo]['border'] }} mb-2 mt-5">
                                    <i
                                        class="text-2xl ti {{ $estilos[$servicioSiguiente->tipo]['icon'] }} {{ $estilos[$servicioSiguiente->tipo]['iconColor'] }}"></i>
                                </div>
                            </div>
                            <span
                                class="inline-block px-4 py-1 rounded-full text-sm font-semibold {{ $estilos[$servicioSiguiente->tipo]['badge'] }} mb-2">
                                {{ $servicioSiguiente->tipo }}
                            </span>
                            <div class="mt-1 text-sm text-gray-700 dark:text-gray-200 text-center break-words max-w-full"
                                title="{{ $servicioSiguiente->descripcion }}"
                                style="max-width: 100%; overflow-wrap: break-word;">
                                {{ \Illuminate\Support\Str::limit($servicioSiguiente->descripcion, 60) }}
                            </div>
                            <a href="{{ route('servicio.detalle', ['id' => $servicioSiguiente->id]) }}"
                                class="mt-2 text-xs text-blue-500 hover:underline flex items-center gap-1">
                                <i class="ti ti-arrow-left"></i> Ver detalle
                            </a>
                        </div>
                        @else
                        <div
                            class="flex flex-col items-center p-4 rounded-xl border border-gray-200 bg-gray-50 w-full shadow-sm">
                            <div class="flex flex-col items-center mb-2">
                                <div
                                    class="w-12 h-12 flex items-center justify-center rounded-full bg-white shadow border-2 border-gray-300 mb-2">
                                    <i class="text-2xl ti ti-clock text-gray-400"></i>
                                </div>
                            </div>
                            <span
                                class="inline-block px-4 py-1 rounded-full text-sm font-semibold bg-gray-100 text-gray-500 mb-2">Sin
                                registro</span>
                            <div class="mt-1 text-sm text-gray-400 text-center">No hay servicios más recientes
                                registrados.</div>
                        </div>
                        @endif
                    </div>
                    <!-- Servicio actual -->
                    <div class="flex flex-col items-center w-full">
                        <div
                            class="flex flex-col items-center p-4 rounded-xl border-2 shadow-2xl mx-2 {{ $estilos[$servicio->tipo]['border'] }} {{ $estilos[$servicio->tipo]['bg'] }} w-full">
                            <!-- Fila superior con fecha alineada a la derecha -->
                            <div class="flex w-full justify-end items-center mb-1">
                                <span
                                    class="flex items-center gap-1 text-blue-700 dark:text-blue-300 text-sm font-bold top-0 right-0">
                                    <i class="ti ti-calendar"></i>
                                    {{ $servicio->fecha->format('d/m/Y') }}
                                </span>
                            </div>
                            <div class="flex flex-col items-center w-full">
                                <div
                                    class="w-16 h-16 flex items-center justify-center rounded-full bg-white shadow border-2 {{ $estilos[$servicio->tipo]['border'] }} mb-2 mt-2">
                                    <i
                                        class="text-3xl ti {{ $estilos[$servicio->tipo]['icon'] }} {{ $estilos[$servicio->tipo]['iconColor'] }}"></i>
                                </div>
                            </div>
                            <span
                                class="inline-block px-5 py-2 rounded-full text-base font-bold {{ $estilos[$servicio->tipo]['badge'] }} mb-2">
                                {{ $servicio->tipo }}
                            </span>
                            <div class="mt-1 text-base text-gray-800 dark:text-gray-100 text-center break-words max-w-full"
                                title="{{ $servicio->descripcion }}"
                                style="max-width: 100%; overflow-wrap: break-word;">
                                {{ $servicio->descripcion }}
                            </div>
                        </div>
                    </div>
                    <!-- Servicio más antiguo (previo) -->
                    <div class="flex flex-col items-center w-full">
                        <div class="text-xs text-gray-500 mb-1">Servicio anterior</div>
                        @if($servicioPrevio)
                        <div
                            class="flex flex-col items-center p-4 rounded-xl border shadow-lg {{ $estilos[$servicioPrevio->tipo]['border'] }} {{ $estilos[$servicioPrevio->tipo]['bg'] }} w-full">
                            <div class="flex flex-col items-center mb-2 w-full relative">
                                <div
                                    class="absolute top-0 right-0 flex items-center gap-1 text-blue-600 dark:text-blue-300 text-xs font-semibold">
                                    <i class="ti ti-calendar"></i>
                                    {{ $servicioPrevio->fecha->format('d/m/Y') }}
                                </div>
                                <div
                                    class="w-12 h-12 flex items-center justify-center rounded-full bg-white shadow border-2 {{ $estilos[$servicioPrevio->tipo]['border'] }} mb-2 mt-5">
                                    <i
                                        class="text-2xl ti {{ $estilos[$servicioPrevio->tipo]['icon'] }} {{ $estilos[$servicioPrevio->tipo]['iconColor'] }}"></i>
                                </div>
                            </div>
                            <span
                                class="inline-block px-4 py-1 rounded-full text-sm font-semibold {{ $estilos[$servicioPrevio->tipo]['badge'] }} mb-2">
                                {{ $servicioPrevio->tipo }}
                            </span>
                            <div class="mt-1 text-sm text-gray-700 dark:text-gray-200 text-center break-words max-w-full"
                                title="{{ $servicioPrevio->descripcion }}"
                                style="max-width: 100%; overflow-wrap: break-word;">
                                {{ \Illuminate\Support\Str::limit($servicioPrevio->descripcion, 60) }}
                            </div>
                            <a href="{{ route('servicio.detalle', ['id' => $servicioPrevio->id]) }}"
                                class="mt-2 text-xs text-blue-500 hover:underline flex items-center gap-1">
                                Ver detalle <i class="ti ti-arrow-right"></i>
                            </a>
                        </div>
                        @else
                        <div
                            class="flex flex-col items-center p-4 rounded-xl border border-gray-200 bg-gray-50 w-full shadow-sm">
                            <div class="flex flex-col items-center mb-2">
                                <div
                                    class="w-12 h-12 flex items-center justify-center rounded-full bg-white shadow border-2 border-gray-300 mb-2">
                                    <i class="text-2xl ti ti-clock text-gray-400"></i>
                                </div>
                            </div>
                            <span
                                class="inline-block px-4 py-1 rounded-full text-sm font-semibold bg-gray-100 text-gray-500 mb-2">Sin
                                registro</span>
                            <div class="mt-1 text-sm text-gray-400 text-center">No hay servicios anteriores
                                registrados.
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
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
</div>