<div class="col-span-full">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @php
        $tipoSeleccionado='oficina';
            $cards = [
                [
                    'titulo' => 'Nueva Misión',
                    'ruta' => route('custodios.nuevaMisionForm'),
                    'icono' => '🚓',
                    'color' => 'bg-blue-100 dark:bg-blue-700',
                ],
                [
                    'titulo' => 'Notificaciones de Misiones',
                    'ruta' => '#',
                    'icono' => '🔔',
                    'color' => 'bg-yellow-100 dark:bg-yellow-700',
                ],
                [
                    'titulo' => 'Misiones Activas',
                    'ruta' => route('custodios.misiones'),
                    'icono' => '📝',
                    'color' => 'bg-green-100 dark:bg-green-700'
                ],
                [
                    'titulo' => 'Solicitudes de  Misiones',
                    'ruta' => '#',
                    'icono' => '📋',
                    'color' => 'bg-blue-100 dark:bg-blue-700',
                ],
                [
                    'titulo' => 'Formularios Post-Misión',
                    'ruta' => route('custodios.misionesTerminadas'),
                    'icono' => '📋',
                    'color' => 'bg-yellow-100 dark:bg-yellow-700'
                ],
                [
                    'titulo' => 'Listado de Elementos',
                    'ruta' => route('custodios.elementos'),
                    'icono' => '📋',
                    'color' => 'bg-green-100 dark:bg-green-700'
                ],
                [
                    'titulo' => 'Solicitar Vacaciones',
                    'ruta' => route('user.solicitarVacacionesForm'),
                    'icono' => '🎉',
                    'color' => 'bg-blue-100 dark:bg-blue-700'
                ],
                [
                    'titulo' => 'Mi Historial de Vacaciones',
                    'ruta' => route('user.historialVacaciones'),
                    'icono' => '📅',
                    'color' => 'bg-yellow-100 dark:bg-yellow-700'
                ],
                [
                    'titulo' => 'Historial de Misiones',
                    'ruta' => route('custodios.historialMisiones'),
                    'icono' => '🗂️',
                    'color' => 'bg-green-100 dark:bg-green-700'
                ],
                [
                    'titulo' => 'Buzón de Quejas y Sugerencias',
                    'ruta' => route('user.buzon'),
                    'icono' => '💬',
                    'color' => 'bg-purple-100 dark:bg-purple-700',
                    'disabled' => Auth::user()->rol=='admin'
                ],
            ];
        @endphp

        @foreach($cards as $card)
        <div class="{{ $card['disabled'] ?? false ? 'pointer-events-none opacity-50' : '' }}" style="{{ ($card['disabled'] ?? false) ? 'opacity: 0.5; pointer-events: none; cursor: default;' : '' }}">
            <a href="{{ $card['ruta'] }}" class="transition-transform transform hover:scale-105 block">
                <div class="p-4 rounded-xl shadow-md {{ $card['color'] }} hover:shadow-lg h-full flex flex-col justify-between relative">
                    @if (!empty($card['notificaciones']) && $card['notificaciones'] > 0)
                        <span class="absolute top-2 right-2 bg-red-600 text-white text-xs rounded-full px-2 py-0.5">
                            {{ $card['notificaciones'] }}
                        </span>
                    @endif
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
        </div>
        @endforeach

    </div>
</div>
