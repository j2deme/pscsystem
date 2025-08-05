@php
    use App\Models\User;
    use App\Models\Asistencia;
    use App\Models\SolicitudVacaciones;
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Auth;

    $user = User::find(Auth::user()->id);
    $totalAsistenciasHoy = 0;
    $conteoSupervisores = User::where('rol', 'Supervisor')->count();

    if(Auth::user()->rol == 'admin'){
        $supervisores = User::where('rol', 'Supervisor')->get();
        foreach ($supervisores as $supervisor) {
            $asistenciasHoy = Asistencia::where('user_id', $supervisor->id)
                ->whereDate('fecha', Carbon::today())
                ->count();
            $totalAsistenciasHoy += $asistenciasHoy;
        }
    }

    $tieneAsistenciaHoy = Asistencia::where('user_id', $user->id)
        ->whereDate('fecha', Carbon::today())
        ->exists();

    $notificacionAsistencia = $tieneAsistenciaHoy ? 0 : 1;
    $asistenciasTotalesSup = $conteoSupervisores - $totalAsistenciasHoy;

    $vacacionesAdmin = SolicitudVacaciones::where('estatus', 'En Proceso')
        ->where('tipo', 'Disfrutadas')
        ->count();

    $vacacionesSup = SolicitudVacaciones::where('supervisor_id', $user->id)
        ->where('estatus', 'En Proceso')
        ->where('tipo', 'Disfrutadas')
        ->count();


@endphp
<div class="col-span-full">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @php
        $vacaciones = Auth::user()->rol == 'admin' ? $vacacionesAdmin : $vacacionesSup;
        $asistencia = Auth::user()->rol == 'admin' ? $asistenciasTotalesSup : $notificacionAsistencia;
            $cards = [
                [
                    'titulo' => 'Alta de Usuarios',
                    'ruta' => route('sup.nuevoUsuarioForm'),
                    'icono' => 'users-group',
                    'color' => 'bg-blue-100 dark:bg-blue-700',
                ],
                [
                    'titulo' => 'Solicitar Vacaciones de Elemento',
                    'ruta' => route('sup.solicitarVacacionesElemento'),
                    'icono' => 'beach',
                    'color' => 'bg-yellow-100 dark:bg-yellow-700',
                ],
                [
                    'titulo' => 'Solicitar Baja de Elemento',
                    'ruta' => route('sup.solicitarBajaForm'),
                    'icono' => 'arrow-down',
                    'color' => 'bg-red-100 dark:bg-red-700',
                ],
                [
                    'titulo' => 'Historial de Altas',
                    'ruta' => route('sup.historial'),
                    'icono' => 'archive',
                    'color' => 'bg-blue-100 dark:bg-blue-700'
                ],
                [
                    'titulo' => 'Listas de Asistencia',
                    'ruta' => route('sup.listaAsistencia'),
                    'icono' => 'clipboard-list',
                    'color' => 'bg-yellow-100 dark:bg-yellow-700',
                    'notificaciones' => $asistencia
                ],

                [
                    'titulo' => 'Historial de Bajas',
                    'ruta' => route('sup.historialBajas'),
                    'icono' => 'book',
                    'color' => 'bg-red-100 dark:bg-red-700'
                ],
                [
                    'titulo' => 'Solicitudes de Vacaciones',
                    'ruta' => route('sup.solicitudesVacaciones'),
                    'icono' => 'beach',
                    'color' => 'bg-blue-100 dark:bg-blue-700',
                    'notificaciones' => $vacaciones
                ],
                [
                    'titulo' => 'Historial de Asistencias',
                    'ruta' => route('sup.verAsistencias', Auth::user()->id),
                    'icono' => 'clipboard-list',
                    'color' => 'bg-yellow-100 dark:bg-yellow-700'
                ],
                [
                    'titulo' => 'Solicitar Vacaciones',
                    'ruta' => route('user.solicitarVacacionesForm'),
                    'icono' => 'confetti',
                    'color' => 'bg-green-100 dark:bg-green-700',
                ],
                [
                    'titulo' => 'Tiempos Extras',
                    'ruta' => route('sup.tiemposExtras'),
                    'icono' => 'clock-hour-2',
                    'color' => 'bg-blue-100 dark:bg-blue-700',
                ],
                [
                    'titulo' => 'Historial de Tiempos Extras y Coberturas',
                    'ruta' => route('sup.historialTiemposExtras'),
                    'icono' => 'calendar',
                    'color' => 'bg-yellow-100 dark:bg-yellow-700'
                ],
                [
                    'titulo' => 'Mi Historial de Vacaciones',
                    'ruta' => route('user.historialVacaciones'),
                    'icono' => 'calendar',
                    'color' => 'bg-green-100 dark:bg-green-700',
                ],
                [
                    'titulo' => 'Gestión de Usuarios',
                    'ruta' => route('sup.gestionUsuarios'),
                    'icono' => 'users-group',
                    'color' => 'bg-indigo-100 dark:bg-indigo-700',
                ],
                [
                    'titulo' => 'Buzón de Quejas y Sugerencias',
                    'ruta' => route('user.buzon'),
                    'icono' => 'message',
                    'color' => 'bg-purple-100 dark:bg-purple-700',
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
        <div>
            <a {{ ($card['disabled'] ?? false) ? '' : 'href=' . $card['ruta'] }} class="transition-transform transform {{ ($card['disabled'] ?? false) ? '' : 'hover:scale-105' }} block">

                <div class="p-4 rounded-xl shadow-md {{ $card['color'] }} h-full flex flex-col justify-between relative {{ !($card['disabled'] ?? false) ? 'hover:shadow-lg' : '' }}"
                    style="{{ ($card['disabled'] ?? false) ? 'opacity: 0.5; pointer-events: none; cursor: default;' : '' }}">

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
