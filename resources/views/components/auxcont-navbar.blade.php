<div class="col-span-full">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @php
        $tipoSeleccionado='oficina';
            $cards = [
                [
                    'titulo' => 'Finiquitos',
                    'ruta' => route('auxcont.finiquitos'),
                    'icono' => 'file-text',
                    'color' => 'bg-red-100 dark:bg-red-700',
                ],
                [
                    'titulo' => 'Solicitar Vacaciones',
                    'ruta' => route('user.solicitarVacacionesForm'),
                    'icono' => 'confetti',
                    'color' => 'bg-blue-100 dark:bg-blue-700'
                ],
                [
                    'titulo' => 'Mi Historial de Vacaciones',
                    'ruta' => route('user.historialVacaciones'),
                    'icono' => 'calendar',
                    'color' => 'bg-yellow-100 dark:bg-yellow-700'
                ],
                [
                    'titulo' => 'Buzón de Quejas y Sugerencias',
                    'ruta' => route('user.buzon'),
                    'icono' => 'message',
                    'color' => 'bg-purple-100 dark:bg-purple-700',
                ],
                [
                    'titulo' => 'Ficha Técnica',
                    'ruta' => route('user.verFicha', auth()->user()->id),
                    'icono' => 'file-description',
                    'color' => 'bg-yellow-100 dark:bg-yellow-700',
                ],
                [
                    'titulo' => 'Mensajes',
                    'ruta' => route('mensajes.index'),
                    'icono' => 'message',
                    'color' => 'bg-purple-100 dark:bg-purple-700',
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
                        <div class="flex items-center justify-center mb-1 rounded-full shadow w-14 h-14 bg-white/80">
                            <i class="ti ti-{{ $card['icono'] }} text-3xl {{
                                Str::contains($card['color'], 'blue') ? 'text-blue-700' :
                                (Str::contains($card['color'], 'yellow') ? 'text-yellow-700' :
                                (Str::contains($card['color'], 'red') ? 'text-red-700' :
                                (Str::contains($card['color'], 'green') ? 'text-green-700' :
                                (Str::contains($card['color'], 'purple') ? 'text-purple-700' :
                                (Str::contains($card['color'], 'gray') ? 'text-gray-700' : 'text-gray-800')))))
                            }}"></i>
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
