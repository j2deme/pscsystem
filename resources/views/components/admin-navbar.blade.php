@php
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\SolicitudAlta;
use App\Models\SolicitudBajas;
use App\Models\SolicitudVacaciones;
use App\Models\Asistencia;
use Carbon\Carbon;

    $user = Auth::user();
    $asistenciasHoy = 0;
    $solicitudesAdmin = 0;
    $solicitudesAdmin = SolicitudAlta::where('status', 'En Proceso')
        ->where('observaciones', 'Solicitud enviada a Administrador.')
        ->count();

    $supervisores = User::where('rol', 'Supervisor')
        ->where('estatus', 'Activo')
        ->get();
    $supervisoresCount = $supervisores->count();

    $rhSolicitudesAltas = SolicitudAlta::where('status', 'En Proceso')
        ->where('observaciones','!=', 'Solicitud enviada a Administrador.')
        ->count();
    $rhSolicitudesBajas = SolicitudBajas::where('estatus', 'En Proceso')
        ->where('por', 'Renuncia')
        ->count();
    $rhnotificaciones = $rhSolicitudesAltas + $rhSolicitudesBajas;

    $solicitudesVacaciones = SolicitudVacaciones::where('estatus', 'En Proceso')->count();
    $totalAsistenciasHoy = 0;

    foreach ($supervisores as $supervisor) {
        $asistenciasHoy = Asistencia::where('user_id', $supervisor->id)
            ->whereDate('fecha', Carbon::today())
            ->count();
        $totalAsistenciasHoy += $asistenciasHoy;
    }
    $asistenciasFaltantes = $supervisoresCount - $totalAsistenciasHoy;
    $supNotificaciones = $asistenciasFaltantes + $solicitudesVacaciones;
@endphp

<div class="col-span-full">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @php
            $cards = [
                [
                    'titulo' => 'Solicitudes de Altas',
                    'ruta' => route('admi.verSolicitudesAltas'),
                    'icono' => '👨‍👩‍👧‍👦',
                    'color' => 'bg-green-100 dark:bg-green-700',
                    'notificaciones' => $solicitudesAdmin
                ],
                [
                    'titulo' => 'Nóminas',
                    'ruta' => "#",
                    'icono' => '💵',
                    'color' => 'bg-blue-100 dark:bg-blue-700',
                    'disabled' =>true
                ],
                [
                    'titulo' => 'Recursos Humanos',
                    'ruta' => '#',
                    'icono' => '👨‍👩‍👧‍👦',
                    'color' => 'bg-indigo-100 dark:bg-indigo-700',
                    'notificaciones' => $rhnotificaciones
                ],
                [
                    'titulo' => 'Monitoreo',
                    'ruta' => '#',
                    'icono' => '📈',
                    'color' => 'bg-red-100 dark:bg-red-700',
                    'disabled' =>true
                ],
                [
                    'titulo' => 'Supervisores',
                    'ruta' => route('admin.verTableroSupervisores'),
                    'icono' => '👨‍💻',
                    'color' => 'bg-green-100 dark:bg-green-700',
                    'notificaciones' => $supNotificaciones
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
            <a
                @if($card['titulo'] === 'Recursos Humanos')
                    href="#"
                    @click.prevent="$dispatch('cambiar-menu', { menu: 'rh' })"
                @else
                    href="{{ $card['ruta'] }}"
                @endif
                class="transition-transform transform hover:scale-105"
            >
                <div class="p-4 rounded-xl shadow-md {{ $card['color'] }} hover:shadow-lg h-full flex flex-col justify-between {{ $card['disabled'] ?? false ? 'pointer-events-none opacity-50' : '' }}">
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
        @endforeach
    </div>
</div>
