@php
    use App\Models\User;
    use App\Models\SolicitudBajas;
    use Carbon\Carbon;

    $conteoBajas = SolicitudBajas::where('estatus', 'En Proceso')
        ->where('por', '!=', 'Renuncia')
        ->where('fecha_baja', '>=', Carbon::now()->subDays(7))
        ->count();

    $cards = [
        [
            'titulo' => 'Nuevas Bajas',
            'ruta' => '#',
            //'ruta' => route('juridico.nuevasBajas'),
            'icono' => 'trending-down',
            'color' => 'bg-green-100 dark:bg-green-700',
            'notificaciones' => $conteoBajas,
        ],
        [
            'titulo' => 'Solicitar Vacaciones',
            'ruta' => route('user.solicitarVacacionesForm'),
            'icono' => 'confetti',
            'color' => 'bg-blue-100 dark:bg-blue-700',
            'disabled' => Auth::user()->rol == 'admin'
        ],
        [
            'titulo' => 'Mi Historial de Vacaciones',
            'ruta' => route('user.historialVacaciones'),
            'icono' => 'calendar',
            'color' => 'bg-green-100 dark:bg-green-700',
            'disabled' => Auth::user()->rol == 'admin'
        ],
        [
            'titulo' => 'Ficha Técnica',
            'ruta' => route('user.verFicha', auth()->user()->id),
            'icono' => 'file-text',
            'color' => 'bg-yellow-100 dark:bg-yellow-700',
            'disabled' => Auth::user()->rol == 'admin'
        ],
        [
            'titulo' => 'Buzón de Quejas y Sugerencias',
            'ruta' => route('user.buzon'),
            'icono' => 'message',
            'color' => 'bg-purple-100 dark:bg-purple-700',
            'disabled' => Auth::user()->rol == 'admin'
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
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Opción no disponible</p>
                    </div>
                </div>
            @else
                <a href="{{ $card['ruta'] }}" class="transition-transform transform hover:scale-105">
                    <div class="p-4 rounded-xl shadow-md {{ $card['color'] }} hover:shadow-lg h-full flex flex-col justify-between">
                        <div class="flex items-center space-x-3">
                            @if (!empty($card['notificaciones']) && $card['notificaciones'] > 0)
                                <span class="absolute top-2 right-2 bg-red-600 text-white text-xs rounded-full px-2 py-0.5">
                                    {{ $card['notificaciones'] }}
                                </span>
                            @endif
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
            @endif
        @endforeach
    </div>
</div>
