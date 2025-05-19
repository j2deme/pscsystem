@php
    $cards = [
        [
            'titulo' => 'Control de Gasolinas',
            'ruta' => "#",
            'icono' => '🛢',
            'color' => 'bg-green-100 dark:bg-green-700',
        ],
        [
            'titulo' => 'Control de Vehículos',
            'ruta' => "#",
            'icono' => '🚗',
            'color' => 'bg-blue-100 dark:bg-blue-700',
        ],
        [
            'titulo' => 'Importar Datos',
            'ruta' => route('importar.excel'),
            'icono' => '📁',
            'color' => 'bg-gray-100 dark:bg-gray-700',
        ],

    ];
@endphp
<div class="col-span-full">
    @if(session('success'))
                <div class="bg-green-100 border-t-4 border-green-500 rounded-b text-green-900 px-4 py-3 shadow-md" role="alert">
                    <div class="flex">
                        <div>
                            <p class="text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
                @elseif (session('error'))
                    <div class="bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md" role="alert">
                    <div class="flex">
                        <div>
                            <p class="text-sm">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($cards as $card)
            @php
                $isDisabled = $card['disabled'] ?? false;
            @endphp

            @if($isDisabled)
                <div class="transition-transform transform opacity-50 cursor-not-allowed" style="pointer-events: none;">
                    <div class="p-4 rounded-xl shadow-md {{ $card['color'] }} h-full flex flex-col justify-between">
                        <div class="flex items-center space-x-3">
                            @if (!empty($card['notificaciones']) && $card['notificaciones'] > 0)
                                <span class="absolute top-2 right-2 bg-red-600 text-white text-xs rounded-full px-2 py-0.5">
                                    {{ $card['notificaciones'] }}
                                </span>
                            @endif
                            <div class="text-3xl">
                                {{ $card['icono'] }}
                            </div>
                            <h3 class="text-base font-semibold text-gray-800 dark:text-white">
                                {{ $card['titulo'] }}
                            </h3>
                        </div>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Opción no disponible</p>
                    </div>
                </div>
            @elseif($card['titulo'] === 'Importar Datos')
                <form
                    action="{{ route('importar.excel') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="transition-transform transform hover:scale-105"
                >
                    @csrf
                    <div class="p-4 rounded-xl shadow-md {{ $card['color'] }} hover:shadow-lg h-full flex flex-col justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="text-3xl">
                                {{ $card['icono'] }}
                            </div>
                            <h3 class="text-base font-semibold text-gray-800 dark:text-white">
                                {{ $card['titulo'] }}
                            </h3>
                        </div>
                        <div class="mt-4">
                            <input
                                type="file"
                                name="excel"
                                accept=".xlsx,.xls"
                                class="block w-full text-sm text-gray-500 dark:text-gray-300 file:mr-4 file:py-1 file:px-2 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                required
                            >
                            <button
                                type="submit"
                                class="mt-2 w-full bg-blue-600 text-white py-1 px-2 rounded hover:bg-blue-700 transition"
                            >
                                Importar Excel
                            </button>
                        </div>
                    </div>
                </form>
            @else
                <a
                    @if($card['titulo'] === 'Recursos Humanos')
                        href="#"
                        @click.prevent="$dispatch('cambiar-menu', { menu: 'rh' })"
                    @else
                        href="{{ $card['ruta'] }}"
                    @endif
                    class="transition-transform transform hover:scale-105"
                >
                    <div class="p-4 rounded-xl shadow-md {{ $card['color'] }} hover:shadow-lg h-full flex flex-col justify-between">
                        <div class="flex items-center space-x-3">
                            @if (!empty($card['notificaciones']) && $card['notificaciones'] > 0)
                                <span class="absolute top-2 right-2 bg-red-600 text-white text-xs rounded-full px-2 py-0.5">
                                    {{ $card['notificaciones'] }}
                                </span>
                            @endif
                            <div class="text-3xl">
                                {{ $card['icono'] }}
                            </div>
                            <h3 class="text-base font-semibold text-gray-800 dark:text-white">
                                {{ $card['titulo'] }}
                            </h3>
                        </div>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Haz clic para ver más detalles</p>
                    </div>
                </a>
            @endif
        @endforeach
    </div>
</div>
