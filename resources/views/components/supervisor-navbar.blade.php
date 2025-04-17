<div class="col-span-full">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @php
            $cards = [
                [
                    'titulo' => 'Alta de Usuarios',
                    'ruta' => route('sup.nuevoUsuarioForm'),
                    'icono' => '👨‍👩‍👧‍👦',
                    'color' => 'bg-blue-100 dark:bg-blue-700'
                ],
                [
                    'titulo' => 'Solicitar Baja de Elemento',
                    'ruta' => route('sup.solicitarBajaForm'),
                    'icono' => '⬇️',
                    'color' => 'bg-yellow-100 dark:bg-blue-700'
                ],
                [
                    'titulo' => 'Listas de Asistencia',
                    'ruta' => route('sup.listaAsistencia'),
                    'icono' => '📋',
                    'color' => 'bg-red-100 dark:bg-red-700'
                ],
                [
                    'titulo' => 'Historial de Altas',
                    'ruta' => route('sup.historial'),
                    'icono' => '🗂️',
                    'color' => 'bg-indigo-100 dark:bg-indigo-700'
                ],

                [
                    'titulo' => 'Historial de Bajas',
                    'ruta' => route('sup.historialBajas'),
                    'icono' => '📒',
                    'color' => 'bg-green-100 dark:bg-green-700'
                ],
                [
                    'titulo' => 'Historial de Asistencias',
                    'ruta' => route('sup.verAsistencias'),
                    'icono' => '📋',
                    'color' => 'bg-blue-100 dark:bg-blue-700'
                ],
                [
                    'titulo' => 'Gestión de Usuarios',
                    'ruta' => '#',
                    'icono' => '👨‍👩‍👧‍👦',
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
