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
                    'icono' => '👨‍👩‍👧‍👦',
                    'color' => 'bg-blue-100 dark:bg-blue-700',
                    'disabled' => Auth::user()->rol == 'admin'
                ],
                [
                    'titulo' => 'Solicitar Baja de Elemento',
                    'ruta' => route('sup.solicitarBajaForm'),
                    'icono' => '⬇️',
                    'color' => 'bg-red-100 dark:bg-red-700',
                    'disabled' => Auth::user()->rol == 'admin'
                ],
                [
                    'titulo' => 'Solicitar Vacaciones de Elemento',
                    'ruta' => route('sup.solicitarVacacionesElemento'),
                    'icono' => '🏖️',
                    'color' => 'bg-yellow-100 dark:bg-yellow-700',
                    'disabled' => Auth::user()->rol == 'admin'
                ],
                [
                    'titulo' => 'Listas de Asistencia',
                    'ruta' => route('sup.listaAsistencia'),
                    'icono' => '📋',
                    'color' => 'bg-green-100 dark:bg-green-700',
                    'notificaciones' => $asistencia
                ],
                [
                    'titulo' => 'Historial de Altas',
                    'ruta' => route('sup.historial'),
                    'icono' => '🗂️',
                    'color' => 'bg-yellow-100 dark:bg-yellow-700'
                ],

                [
                    'titulo' => 'Historial de Bajas',
                    'ruta' => route('sup.historialBajas'),
                    'icono' => '📒',
                    'color' => 'bg-red-100 dark:bg-red-700'
                ],
                [
                    'titulo' => 'Historial de Asistencias',
                    'ruta' => route('sup.verAsistencias', Auth::user()->id),
                    'icono' => '📋',
                    'color' => 'bg-blue-100 dark:bg-blue-700'
                ],
                /*[
                    'titulo' => 'Solicitudes de Vacaciones',
                    'ruta' => route('sup.solicitudesVacaciones'),
                    'icono' => '🏖️',
                    'color' => 'bg-blue-100 dark:bg-blue-700',
                    'notificaciones' => $vacaciones
                ],*/
                [
                    'titulo' => 'Solicitar Vacaciones',
                    'ruta' => route('user.solicitarVacacionesForm'),
                    'icono' => '🎉',
                    'color' => 'bg-green-100 dark:bg-green-700',
                    'disabled' => Auth::user()->rol == 'admin'
                ],
                [
                    'titulo' => 'Mi Historial de Vacaciones',
                    'ruta' => route('user.historialVacaciones'),
                    'icono' => '📅',
                    'color' => 'bg-yellow-100 dark:bg-yellow-700',
                    'disabled' => Auth::user()->rol == 'admin'
                ],
                [
                    'titulo' => 'Tiempos Extras y Cobertura de Turnos',
                    'ruta' => route('sup.tiemposExtras'),
                    'icono' => '🕑',
                    'color' => 'bg-green-100 dark:bg-green-700',
                    'disabled' => Auth::user()->rol == 'admin'
                ],
                [
                    'titulo' => 'Historial de Tiempos Extras y Coberturas',
                    'ruta' => route('sup.historialTiemposExtras'),
                    'icono' => '📅',
                    'color' => 'bg-yellow-100 dark:bg-yellow-700'
                ],
                [
                    'titulo' => 'Gestión de Usuarios',
                    'ruta' => route('sup.gestionUsuarios'),
                    'icono' => '👨‍👩‍👧‍👦',
                    'color' => 'bg-indigo-100 dark:bg-indigo-700',
                    'disabled' => Auth::user()->rol == 'admin'
                ],
                [
                    'titulo' => 'Buzón de Quejas y Sugerencias',
                    'ruta' => route('user.buzon'),
                    'icono' => '💬',
                    'color' => 'bg-purple-100 dark:bg-purple-700',
                    'disabled' => Auth::user()->rol == 'admin'
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
