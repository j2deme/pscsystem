<div class="col-span-full">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @php
            $cards = [
                [
                    'titulo' => 'Nóminas',
                    'ruta' => '#',
                    'icono' => '💵',
                    'color' => 'bg-blue-100 dark:bg-blue-700'
                ],
                [
                    'titulo' => 'Recursos Humanos',
                    'ruta' => '#',
                    'icono' => '👨‍👩‍👧‍👦',
                    'color' => 'bg-indigo-100 dark:bg-indigo-700'
                ],
                [
                    'titulo' => 'Monitoreo',
                    'ruta' => '#',
                    'icono' => '📈',
                    'color' => 'bg-red-100 dark:bg-red-700'
                ],
                [
                    'titulo' => 'Gestión de Usuarios',
                    'ruta' => route('admin.verUsuarios'),
                    'icono' => '👨‍💻',
                    'color' => 'bg-yellow-100 dark:bg-yellow-700'
                ],
            ];
        @endphp

        @foreach($cards as $card)
            <a href="{{ $card['ruta'] }}" class="transition-transform transform hover:scale-105">
                <div class="p-4 rounded-xl shadow-md {{ $card['color'] }} hover:shadow-lg h-full flex flex-col justify-between">
                    <div class="flex items-center space-x-3">
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
        @endforeach
    </div>
</div>
