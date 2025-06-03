@php
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\SolicitudAlta;
use App\Models\SolicitudBajas;
use App\Models\SolicitudVacaciones;
use App\Models\Asistencia;
use Carbon\Carbon;

$activos= User::where('estatus', 'Activo')->count();
$user = Auth::user();
$asistenciasHoy = 0;
$solicitudesAdmin = SolicitudAlta::where('status', 'Aceptada')
    ->whereDate('updated_at', Carbon::today('America/Mexico_City'))
    ->count();

$supervisores = User::where('rol', 'Supervisor')->where('estatus', 'Activo')->get();
$supervisoresCount = $supervisores->count();

$rhSolicitudesAltas = SolicitudAlta::where('status', 'En Proceso')
    ->where('observaciones', '!=', 'Solicitud enviada a Administrador.')
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

$conteoAltasAux = SolicitudAlta::where('status', 'Aceptada')
    ->whereDate('fecha_ingreso', '>=', Carbon::today('America/Mexico_City')->subDays(5))
    ->whereHas('documentacion', function ($q) {
        $q->whereNull('arch_acuse_imss');
    })
    ->count();

$conteoAltasNominas = SolicitudAlta::where('status', 'Aceptada')
    ->whereDate('fecha_ingreso', '>=', Carbon::today('America/Mexico_City')->subDays(5))
    ->count();
$conteoBajasNominas = SolicitudBajas::where('estatus', 'Aceptada')
    ->whereDate('fecha_baja', '>=', Carbon::today('America/Mexico_City')->subDays(5))
    ->count();
$conteoNominas = $conteoAltasNominas + $conteoBajasNominas;

$cards = array_filter([
    Auth::user()->email == 'gino@spyt.com.mx' ? [
        'titulo' => 'Nuevas Altas',
        'ruta' => route('admi.verSolicitudesAltas'),
        'icono' => '👨‍🌾',
        'color' => 'bg-gray-300 dark:bg-gray-700',
        'notificaciones' => $solicitudesAdmin,
    ] : null,
    [
        'titulo' => 'Nóminas',
        'ruta' => "#",
        'icono' => '💵',
        'color' => 'bg-gray-300 dark:bg-gray-700',
        'notificaciones' => $conteoNominas,
    ],
    [
        'titulo' => 'IMSS',
        'ruta' => "#",
        'icono' => '💊',
        'color' => 'bg-gray-300 dark:bg-gray-700',
        'notificaciones' => $conteoAltasAux,
    ],
    [
        'titulo' => 'Recursos Humanos',
        'ruta' => '#',
        'icono' => '👨‍👩‍👧‍👦',
        'color' => 'bg-gray-300 dark:bg-gray-700',
        'notificaciones' => $rhnotificaciones
    ],
    [
        'titulo' => 'Monitoreo',
        'ruta' => '#',
        'icono' => '📈',
        'color' => 'bg-gray-300 dark:bg-gray-700',
        'disabled' => true
    ],
    [
        'titulo' => 'Supervisores',
        'ruta' => route('admin.verTableroSupervisores'),
        'icono' => '👨‍💻',
        'color' => 'bg-gray-300 dark:bg-gray-700',
        'notificaciones' => $supNotificaciones
    ],
    [
        'titulo' => 'Gestión de Usuarios',
        'ruta' => route('admin.verUsuarios'),
        'icono' => '👨‍💻',
        'color' => 'bg-gray-300 dark:bg-gray-700'
    ],
    [
        'titulo' => 'Buzón de Quejas y Sugerencias',
        'ruta' => route('admin.verBuzon'),
        'icono' => '💬',
        'color' => 'bg-gray-300 dark:bg-gray-700'
    ],
    [
        'titulo' => 'Importar Datos',
        'ruta' => route('importar.excel'),
        'icono' => '📂',
        'color' => 'bg-gray-300 dark:bg-gray-700',
        'disabled' => true,
    ],
]);
@endphp

<div class="flex h-full">
    <div class="w-64 px-4 py-6 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700">
        <div class="space-y-2">
            @foreach($cards as $card)
                @php
                    $isDisabled = $card['disabled'] ?? false;
                    $isActive = request()->routeIs(Str::after($card['ruta'] ?? '', url('/')));
                @endphp

                @if($isDisabled)
                    <div class="p-3 rounded-lg {{ $card['color'] }} opacity-50 cursor-not-allowed">
                        <div class="flex items-center space-x-3">
                            <div class="text-2xl">{{ $card['icono'] }}</div>
                            <span class="font-medium">{{ $card['titulo'] }}</span>
                        </div>
                        @if(isset($card['notificaciones']) && $card['notificaciones'] > 0)
                            <span class="absolute top-2 right-2 bg-red-600 text-white text-xs rounded-full px-2 py-0.5">
                                {{ $card['notificaciones'] }}
                            </span>
                        @endif
                    </div>
                @elseif($card['titulo'] === 'Importar Datos')
                    <form action="{{ route('importar.excel') }}" method="POST" enctype="multipart/form-data" class="p-3 rounded-lg {{ $card['color'] }}">
                        @csrf
                        <div class="flex items-center space-x-3">
                            <div class="text-2xl">{{ $card['icono'] }}</div>
                            <span class="font-medium">{{ $card['titulo'] }}</span>
                        </div>
                        <input type="file" name="excel" accept=".xlsx,.xls" class="mt-2 w-full text-sm text-gray-500 file:mr-4 file:py-1 file:px-2 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                        <button type="submit" class="mt-2 w-full bg-blue-600 text-white py-1 px-2 rounded hover:bg-blue-700 transition text-sm">
                            Importar
                        </button>
                    </form>
                @else
                    <a href="{{ $card['ruta'] ?? '#' }}"
                        @if(in_array($card['titulo'], ['Recursos Humanos', 'Nóminas', 'IMSS']))
                            @click.prevent="$dispatch('cambiar-menu', { menu: '{{ strtolower(str_replace(' ', '_', $card['titulo'])) }}' })"
                        @endif
                        class="block p-3 rounded-lg {{ $card['color'] }} {{ $isActive ? 'ring-2 ring-blue-500' : '' }} hover:bg-opacity-70 transition relative">
                        <div class="flex items-center space-x-3">
                            <div class="text-2xl">{{ $card['icono'] }}</div>
                            <span class="font-medium">{{ $card['titulo'] }}</span>
                        </div>
                        @if(isset($card['notificaciones']) && $card['notificaciones'] > 0)
                            <span class="absolute top-2 right-2 bg-red-600 text-white text-xs rounded-full px-2 py-0.5">
                                {{ $card['notificaciones'] }}
                            </span>
                        @endif
                    </a>
                @endif
            @endforeach
        </div>
    </div>

    <div class="flex-1 overflow-y-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 p-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-blue-500">
                <div class="h-full flex flex-col">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Elementos Activos</h3>
                    <div class="mt-auto text-2xl font-bold text-gray-800 dark:text-gray-200">{{ $activos }}</div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-green-500">
                <div class="h-full flex flex-col">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Altas Nuevas</h3>
                    <div class="mt-auto text-2xl font-bold text-gray-800 dark:text-gray-200">--</div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-yellow-500">
                <div class="h-full flex flex-col">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Bajas Recientes</h3>
                    <div class="mt-auto text-2xl font-bold text-gray-800 dark:text-gray-200">--</div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-purple-500">
                <div class="h-full flex flex-col">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Movimientos de Nóminas</h3>
                    <div class="mt-auto text-2xl font-bold text-gray-800 dark:text-gray-200">--</div>
                </div>
            </div>
        </div>

        <div class="relative p-6">
        <div class="relative overflow-hidden rounded-lg">
            <div id="carouselSlides" class="flex transition-transform duration-300 ease-in-out">
                <div class="w-full flex-shrink-0 px-4">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 h-full">
                        @livewire('nominastotales')
                    </div>
                </div>
                <div class="w-full flex-shrink-0">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        @livewire('graficas-altas')
                    </div>
                </div>

                <div class="w-full flex-shrink-0">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        @livewire('graficasnuevasaltas')
                    </div>
                </div>

                <div class="w-full flex-shrink-0">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        @livewire('graficas-bajas')
                    </div>
                </div>

                <div class="w-full flex-shrink-0">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        @livewire('graficas-inasistencias')
                    </div>
                </div>

                <div class="w-full flex-shrink-0">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        @livewire('graficas-vacaciones')
                    </div>
                </div>
            </div>
        </div>

        <button onclick="prevSlide()" class="absolute left-6 top-1/2 -translate-y-1/2 bg-white dark:bg-gray-700 text-gray-800 dark:text-white shadow hover:bg-blue-500 hover:text-white transition duration-300 rounded-full w-10 h-10 flex items-center justify-center z-10">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
    </button>

    <button onclick="nextSlide()" class="absolute right-6 top-1/2 -translate-y-1/2 bg-white dark:bg-gray-700 text-gray-800 dark:text-white shadow hover:bg-blue-500 hover:text-white transition duration-300 rounded-full w-10 h-10 flex items-center justify-center z-10">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
    </button>

    <div class="flex justify-center mt-4 space-x-2">
        @for ($i = 0; $i < 6; $i++)
            <button onclick="goToSlide({{ $i }})" class="w-3 h-3 rounded-full bg-gray-400 hover:bg-blue-500 transition indicator-dot"></button>
        @endfor
    </div>
    </div>
    </div>
</div>
@push('scripts')
<script>
    let currentSlide = 0;
    const totalSlides = 6;
    const carousel = document.getElementById('carouselSlides');
    const indicators = document.querySelectorAll('.indicator-dot');

    function updateCarousel() {
        carousel.style.transform = `translateX(-${currentSlide * 100}%)`;

        indicators.forEach((dot, index) => {
            dot.classList.toggle('bg-blue-500', index === currentSlide);
            dot.classList.toggle('bg-gray-400', index !== currentSlide);
        });

        if (window.nominaChart) {
            setTimeout(() => {
                window.nominaChart.update();
            }, 300);
        }
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % totalSlides;
        updateCarousel();
    }

    function prevSlide() {
        currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
        updateCarousel();
    }

    function goToSlide(index) {
        currentSlide = index;
        updateCarousel();
    }

    document.addEventListener('DOMContentLoaded', () => {
        updateCarousel();
        setInterval(nextSlide, 10000);
    });
</script>
@endpush
