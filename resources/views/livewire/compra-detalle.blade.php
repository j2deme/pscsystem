<div class="py-6 mx-auto">
    <x-navbar />

    <x-livewire.monitoreo-layout :breadcrumb-items="$breadcrumbItems" :title-main="$titleMain" :help-text="$helpText">

        <div class="max-w-5xl mx-auto">
            <!-- Sección: Información Principal del Registro -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <!-- Card: Información del Servicio/Compra -->
                <div
                    class="flex flex-col min-w-0 gap-4 p-6 bg-white shadow-md rounded-xl dark:bg-gray-800 border-2 border-blue-200 dark:border-blue-700">
                    <div class="flex items-center gap-3 mb-2">
                        @php
                        $tipo = $compra->tipo ?? '';
                        $icono = $this->getIconoTipo($tipo);
                        [$bgColor, $textColor] = $this->getBadgeColores($tipo);
                        @endphp
                        <span
                            class="inline-flex items-center justify-center w-12 h-12 text-2xl {{ $bgColor }} rounded-full">
                            <i class="ti {{ $icono }} {{ $textColor }}"></i>
                        </span>
                        <div>
                            <div class="text-lg font-bold text-gray-800 dark:text-gray-100">
                                {{ $tipo ?: 'N/A' }}
                            </div>
                            <div class="text-base font-bold text-blue-600 dark:text-blue-400">
                                <i class="mr-1 ti ti-calendar-event"></i>{{ $compra->fecha_hora->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>

                    <div class="mt-2">
                        <div class="text-3xl font-bold text-gray-900 dark:text-white">
                            @if($compra->costo !== null)
                            ${{ number_format($compra->costo, 2) }}
                            @else
                            <span class="text-gray-400">N/A</span>
                            @endif
                        </div>
                        <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Costo del servicio/compra
                        </div>
                    </div>

                    @if($compra->garantia)
                    <div
                        class="px-3 py-1 mt-3 text-sm font-semibold text-center text-green-700 bg-green-100 rounded-full dark:bg-green-900 dark:text-green-100">
                        <i class="ti ti-shield-check mr-1"></i> Realizado bajo garantía
                    </div>
                    @endif
                </div>

                <!-- Card: Unidad Asociada -->
                <div class="flex flex-col min-w-0 gap-4 p-6 bg-white shadow-md rounded-xl dark:bg-gray-800">
                    <div class="flex items-center gap-2 mb-2 text-base font-bold text-blue-700 dark:text-blue-300">
                        <i class="ti ti-car"></i> Unidad
                    </div>
                    @if($unidad)
                    <div class="flex flex-col justify-between h-full min-h-[170px]">
                        <div class="flex flex-col items-center gap-2 pt-2">
                            <div class="inline-block px-6 py-2 font-mono text-3xl font-extrabold tracking-widest text-blue-900 bg-white border-2 border-blue-400 rounded-md shadow-md"
                                style="letter-spacing:2.5px;min-width:140px;">
                                {{ $unidad->placas }}
                            </div>
                            <div class="mt-2 text-base font-medium text-gray-700 dark:text-gray-200">
                                {{ $unidad->marca }} <span class="mx-1 text-gray-400">|</span> {{ $unidad->modelo }}
                            </div>
                            @if($compra->kilometraje)
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                <i class="ti ti-road-sign mr-1"></i>
                                {{ number_format($compra->kilometraje, 0) }} Km
                            </div>
                            @endif
                        </div>
                        <div class="flex items-center justify-end w-full mt-4">
                            <a href="{{ route('vehiculos.detalle', ['id' => $unidad->id]) }}"
                                class="inline-flex items-center gap-2 px-5 py-2 text-base text-white bg-blue-400 rounded-lg shadow-lg hover:bg-blue-500"
                                title="Ver detalle del vehículo">
                                <i class="ti ti-car"></i> Ver vehículo
                            </a>
                        </div>

                    </div>
                    @else
                    <div
                        class="flex flex-col items-center justify-center h-[170px] bg-blue-50 dark:bg-gray-700 rounded-lg border-2 border-dashed border-blue-200 p-4">
                        <span
                            class="inline-flex items-center justify-center w-16 h-16 mb-2 text-4xl text-blue-300 bg-blue-100 rounded-full">
                            <i class="ti ti-car-off"></i>
                        </span>
                        <div class="text-lg font-semibold text-blue-400">Sin unidad asociada</div>
                    </div>
                    @endif
                </div>

                <!-- Card: Proveedor/Responsable -->
                <div class="flex flex-col min-w-0 gap-4 p-6 bg-white shadow-md rounded-xl dark:bg-gray-800">
                    <div class="flex items-center gap-2 mb-2 text-base font-bold text-blue-700 dark:text-blue-300">
                        <i class="ti ti-user-circle"></i> Proveedor/Responsable
                    </div>
                    @if($compra->proveedor)
                    <div class="flex flex-col items-center justify-center h-full min-h-[170px]">
                        <div class="text-lg font-bold text-gray-800 dark:text-gray-100 text-center">
                            {{ $compra->proveedor }}
                        </div>
                    </div>
                    @else
                    <div
                        class="flex flex-col items-center justify-center h-[170px] bg-blue-50 dark:bg-gray-700 rounded-lg border-2 border-dashed border-blue-200 p-4">
                        <span
                            class="inline-flex items-center justify-center w-16 h-16 mb-2 text-4xl text-blue-300 bg-blue-100 rounded-full">
                            <i class="ti ti-building"></i>
                        </span>
                        <div class="text-lg font-semibold text-blue-400">Sin proveedor registrado</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Sección: Detalles del Servicio -->
            <div class="grid grid-cols-1 gap-6 mt-8">
                <div class="p-6 bg-white shadow-md rounded-xl dark:bg-gray-800">
                    <div class="flex items-center gap-2 mb-4 font-bold text-blue-700 dark:text-blue-300">
                        <i class="text-lg ti ti-file-description"></i>
                        Detalles del Servicio/Compra
                    </div>
                    <div class="space-y-4">
                        <!-- Descripción y Notas en fila -->
                        <div class="flex flex-col md:flex-row gap-4">
                            <!-- Descripción (ocupa todo el ancho si no hay notas) -->
                            <div class="flex-1">
                                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Descripción:
                                </h3>
                                <div
                                    class="p-3 text-gray-800 bg-gray-50 border border-gray-200 rounded-lg dark:text-gray-200 dark:bg-gray-700 dark:border-gray-600">
                                    {{ $compra->descripcion ?? 'N/A' }}
                                </div>
                            </div>

                            <!-- Notas (solo si existen) -->
                            @if($compra->notas)
                            <div class="w-full md:w-1/2">
                                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Notas:</h3>
                                <div
                                    class="p-3 text-gray-800 bg-gray-50 border border-gray-200 rounded-lg dark:text-gray-200 dark:bg-gray-700 dark:border-gray-600">
                                    {{ $compra->notas }}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección: Métricas y Análisis (si hay unidad) -->
            @if($unidad)
            <div class="grid grid-cols-1 gap-6 mt-8 md:grid-cols-2">
                <!-- Card: Frecuencia del Tipo de Servicio -->
                <div class="p-6 bg-white shadow-md rounded-xl dark:bg-gray-800">
                    <div class="flex items-center gap-2 mb-4 font-bold text-blue-700 dark:text-blue-300">
                        <i class="text-lg ti ti-repeat"></i>
                        Frecuencia del Servicio
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-gray-900 dark:text-white">{{ $frecuenciaTipoEnUnidad }}
                        </div>
                        <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ ($frecuenciaTipoEnUnidad > 1) ? 'veces': 'vez' }} que se ha realizado este tipo de
                            servicio en esta unidad
                        </div>
                    </div>
                </div>

                <!-- Card: Gasto Acumulado en la Unidad -->
                <div class="p-6 bg-white shadow-md rounded-xl dark:bg-gray-800">
                    <div class="flex items-center gap-2 mb-4 font-bold text-blue-700 dark:text-blue-300">
                        <i class="text-lg ti ti-currency-dollar"></i>
                        Gasto Acumulado (últimos 12 meses)
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-gray-900 dark:text-white">
                            ${{ number_format($gastoAcumuladoUnidad, 2) }}
                        </div>
                        <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            en todos los servicios de esta unidad
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Sección: Historial Relacionado (si hay unidad) -->
            @if($unidad && $historialServiciosUnidad && $historialServiciosUnidad->isNotEmpty())
            <div class="grid grid-cols-1 gap-6 mt-8">
                <div class="p-6 bg-white shadow-md rounded-xl dark:bg-gray-800">
                    <div class="flex items-center gap-2 mb-4 font-bold text-blue-700 dark:text-blue-300">
                        <i class="text-lg ti ti-history"></i>
                        Historial Reciente de Servicios ({{ $unidad->placas }})
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-200">Fecha</th>
                                    <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-200">Tipo</th>
                                    <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-200">Descripción</th>
                                    <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-200">Costo</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($historialServiciosUnidad as $servicio)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-750">
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        {{ $servicio->fecha_hora->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-4 py-2">
                                        @php
                                        $tipoHist = $servicio->tipo;
                                        $iconoHist = $this->getIconoTipo($tipoHist);
                                        [$bgColorHist, $textColorHist] = $this->getBadgeColores($tipoHist);
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full {{ $bgColorHist }} {{ $textColorHist }}">
                                            <i class="mr-1 text-xs ti {{ $iconoHist }}"></i>
                                            {{ Str::limit($tipoHist, 15) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 max-w-xs">
                                        <span class="truncate block" title="{{ $servicio->descripcion }}">
                                            {{ Str::limit($servicio->descripcion, 30) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 font-medium">
                                        @if($servicio->costo !== null)
                                        ${{ number_format($servicio->costo, 2) }}
                                        @else
                                        <span class="text-gray-400">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Sección: Acciones -->
            <div class="flex justify-end max-w-4xl gap-4 mx-auto mt-8">
                <a href="{{ route('compras.index') }}" {{-- Ajusta la ruta --}}
                    class="flex items-center gap-2 px-5 py-2 text-gray-700 bg-gray-100 border border-gray-300 rounded-lg shadow hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 dark:hover:bg-gray-600"
                    title="Regresar">
                    <i class="ti ti-arrow-left"></i> Regresar
                </a>
                <a href="{{ route('compras.index', ['editar' => $compra->id]) }}"
                    class="flex items-center gap-2 px-5 py-2 text-white bg-blue-400 rounded-lg shadow hover:bg-blue-500"
                    title="Editar">
                    <i class="ti ti-edit"></i> Editar
                </a>
            </div>
        </div>
    </x-livewire.monitoreo-layout>
</div>