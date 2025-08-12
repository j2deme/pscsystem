@php
    use App\Models\User;
    use Illuminate\Support\Facades\Auth;
    use App\Models\SolicitudAlta;
    use App\Models\SolicitudBajas;
    use App\Models\SolicitudVacaciones;
    use App\Models\Asistencia;
    use Carbon\Carbon;

    $vacacionesAdmin = SolicitudVacaciones::where('estatus', 'En Proceso')
        ->where('observaciones', '!=', 'Solicitud aceptada, falta subir archivo de solicitud.')
        ->whereHas('user', function ($query) {
            $query->where('empresa', 'Montana');
        })
        ->count();
    $conteoBajasJuridico = SolicitudBajas::where('estatus', 'En Proceso')
        ->where('por', '!=', 'Renuncia')
        ->where('fecha_baja', '>=', Carbon::now()->subDays(7))
        ->count();

    $activos = User::where('estatus', 'Activo')->count();
    $activosMesActual = User::where('estatus', 'Activo')
        ->whereDate('created_at', '>=', Carbon::now()->startOfMonth())
        ->count();
    $activosMesPasado = $activos - $activosMesActual;

    $inicioMesActual = Carbon::now()->startOfMonth();
    $inicioMesPasado = Carbon::now()->subMonth()->startOfMonth();
    $finMesPasado = Carbon::now()->subMonth()->endOfMonth();

    $conteoAltasAdmin = SolicitudAlta::where('status', 'Aceptada')
        ->whereDate('fecha_ingreso', '>=', $inicioMesActual)
        ->count();

    $altasMesPasado = SolicitudAlta::where('status', 'Aceptada')
        ->whereBetween('fecha_ingreso', [$inicioMesPasado, $finMesPasado])
        ->count();

    $conteoBajasAdmin = SolicitudBajas::where('estatus', 'Aceptada')
        ->whereDate('fecha_baja', '>=', $inicioMesActual)
        ->count();

    $bajasMesPasado = SolicitudBajas::where('estatus', 'Aceptada')
        ->whereBetween('fecha_baja', [$inicioMesPasado, $finMesPasado])
        ->count();

    function calcularVariacion($actual, $anterior)
    {
        if ($anterior == 0) {
            return $actual > 0 ? 100 : 0;
        }
        return round((($actual - $anterior) / $anterior) * 100);
    }

    $variacionActivos = calcularVariacion($activos, $activosMesPasado);
    $variacionAltas = calcularVariacion($conteoAltasAdmin, $altasMesPasado);
    $variacionBajas = calcularVariacion($conteoBajasAdmin, $bajasMesPasado);

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
    $rhSolicitudesBajas = SolicitudBajas::where('estatus', 'En Proceso')->where('por', 'Renuncia')->count();
    $rhnotificaciones = $rhSolicitudesAltas + $rhSolicitudesBajas;

    $solicitudesVacaciones = SolicitudVacaciones::where('estatus', 'En Proceso')->count();
    $totalAsistenciasHoy = 0;

    foreach ($supervisores as $supervisor) {
        $asistenciasHoy = Asistencia::where('user_id', $supervisor->id)->whereDate('fecha', Carbon::today())->count();
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
        Auth::user()->email == 'gino@spyt.com.mx'
            ? [
                'titulo' => 'Nuevas Altas',
                'ruta' => route('admi.verSolicitudesAltas'),
                'icono' => 'trending-up',
                'color' => 'bg-green-200 dark:bg-green-700',
                'notificaciones' => $solicitudesAdmin,
            ]
            : null,
        [
            'titulo' => 'Mensajes',
            'ruta' => route('mensajes.index'),
            'icono' => 'message',
            'color' => 'bg-purple-300 dark:bg-purple-700',
        ],
        [
            'titulo' => 'Nóminas',
            'ruta' => route('admin.nominasDashboard'),
            'icono' => 'currency-dollar',
            'color' => 'bg-yellow-200 dark:bg-yellow-700',
            'notificaciones' => $conteoNominas,
        ],
        [
            'titulo' => 'IMSS',
            'ruta' => route('admin.imssDashboard'),
            'icono' => 'pill',
            'color' => 'bg-blue-200 dark:bg-blue-700',
            'notificaciones' => $conteoAltasAux,
        ],
        [
            'titulo' => 'Jurídico',
            'ruta' => route('admin.juridicoDashboard'),
            'icono' => 'scale',
            'color' => 'bg-red-300 dark:bg-red-700',
            'notificacions' => $conteoBajasJuridico,
        ],
        [
            'titulo' => 'Custodios',
            'ruta' => route('admin.custodiosDashboard'),
            'icono' => 'user-shield',
            'color' => 'bg-blue-300 dark:bg-blue-700',
        ],
        [
            'titulo' => 'RRHH',
            'ruta' => route('admin.rhDashboard'),
            'icono' => 'users-group',
            'color' => 'bg-pink-300 dark:bg-pink-700',
            'notificaciones' => $rhnotificaciones,
        ],
        [
            'titulo' => 'Monitoreo',
            'ruta' => route('admin.monitoreoDashboard'),
            'icono' => 'trending-up',
            'color' => 'bg-indigo-300 dark:bg-indigo-700',
        ],
        [
            'titulo' => 'Supervisores',
            'ruta' => route('admin.verTableroSupervisores'),
            'icono' => 'users',
            'color' => 'bg-green-300 dark:bg-green-700',
            'notificaciones' => $supNotificaciones,
        ],
        [
            'titulo' => 'Vacaciones',
            'ruta' => route('admin.solicitudesVacaciones'),
            'icono' => 'tent',
            'color' => 'bg-yellow-300 dark:bg-yellow-700',
            'notificaciones' => $vacacionesAdmin,
        ],
        [
            'titulo' => 'Gestión de Usuarios',
            'ruta' => route('admin.verUsuarios'),
            'icono' => 'user-code',
            'color' => 'bg-indigo-300 dark:bg-indigo-700',
        ],
        [
            'titulo' => 'Buzón de Quejas y Sugerencias',
            'ruta' => route('admin.verBuzon'),
            'icono' => 'message',
            'color' => 'bg-purple-300 dark:bg-purple-700',
        ],
        /*[
            'titulo' => 'Depurar datos',
            'form' => true, // Marcador para saber que es un formulario
            'action' => route('admin.import.unify-duplicates'),
            'icono' => 'message',
            'color' => 'bg-purple-300 dark:bg-purple-700',
            'confirm' => '¿Estás seguro de unificar los usuarios duplicados? Esta acción no se puede deshacer.',
        ],
        [
            'titulo' => 'Importar Datos',
            'ruta' => route('importar.excel'),
            'icono' => 'folder-open',
            'color' => 'bg-gray-300 dark:bg-gray-700',
            'disabled' => true,
        ],
        [
            'titulo' => 'Registrar Datos',
            'ruta' => route('registrarNominas'),
            'icono' => 'file-text',
            'color' => 'bg-orange-300 dark:bg-orange-700',
            'disabled' => false,
        ],
        [
            'titulo' => 'Registrar Finiquitos',
            'ruta' => route('registrarFiniquitos'),
            'icono' => 'file-text',
            'color' => 'bg-red-300 dark:bg-red-700',
            'disabled' => true,
        ],*/
    ]);
@endphp

<div class="flex h-full">
    <div class="w-64 px-4 py-6 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700">
        <div class="space-y-2">
            @foreach ($cards as $card)
                @php
                    $isDisabled = $card['disabled'] ?? false;
                    $isActive = request()->routeIs(Str::after($card['ruta'] ?? '', url('/')));
                @endphp

                @if ($isDisabled)
                    <div class="p-3 rounded-lg {{ $card['color'] }} opacity-50 cursor-not-allowed">
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center justify-center mb-1 rounded-full shadow w-14 h-14 bg-white/80">
                                <i
                                    class="ti ti-{{ $card['icono'] }} text-3xl {{ Str::contains($card['color'], 'blue')
                                        ? 'text-blue-700'
                                        : (Str::contains($card['color'], 'yellow')
                                            ? 'text-yellow-700'
                                            : (Str::contains($card['color'], 'indigo')
                                                ? 'text-indigo-700'
                                                : (Str::contains($card['color'], 'orange')
                                                    ? 'text-orange-700'
                                                    : (Str::contains($card['color'], 'red')
                                                        ? 'text-red-700'
                                                        : (Str::contains($card['color'], 'green')
                                                            ? 'text-green-700'
                                                            : (Str::contains($card['color'], 'purple')
                                                                ? 'text-purple-700'
                                                                : (Str::contains($card['color'], 'gray')
                                                                    ? 'text-gray-700'
                                                                    : 'text-gray-800'))))))) }}"></i>
                            </div>
                            <span class="font-medium">{{ $card['titulo'] }}</span>
                        </div>
                        @if (isset($card['notificaciones']) && $card['notificaciones'] > 0)
                            <span class="absolute top-2 right-2 bg-red-600 text-white text-xs rounded-full px-2 py-0.5">
                                {{ $card['notificaciones'] }}
                            </span>
                        @endif
                    </div>
                @elseif($card['titulo'] === 'Importar Datos')
                    <form action="{{ route('importar.excel') }}" method="POST" enctype="multipart/form-data"
                        class="p-3 rounded-lg {{ $card['color'] }}">
                        @csrf
                        <div class="flex items-center space-x-3">
                            <div
                                class="flex items-center justify-center mb-1 rounded-full shadow w-14 h-14 bg-white/80">
                                <i
                                    class="ti ti-{{ $card['icono'] }} text-3xl {{ Str::contains($card['color'], 'blue')
                                        ? 'text-blue-700'
                                        : (Str::contains($card['color'], 'yellow')
                                            ? 'text-yellow-700'
                                            : (Str::contains($card['color'], 'red')
                                                ? 'text-red-700'
                                                : (Str::contains($card['color'], 'green')
                                                    ? 'text-green-700'
                                                    : (Str::contains($card['color'], 'purple')
                                                        ? 'text-purple-700'
                                                        : (Str::contains($card['color'], 'gray')
                                                            ? 'text-gray-700'
                                                            : 'text-gray-800'))))) }}"></i>
                            </div>
                            <span class="font-medium">{{ $card['titulo'] }}</span>
                        </div>
                        <input type="file" name="excel" accept=".xlsx,.xls, .csv"
                            class="mt-2 w-full text-sm text-gray-500 file:mr-4 file:py-1 file:px-2 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                            required>
                        <button type="submit"
                            class="mt-2 w-full bg-blue-600 text-white py-1 px-2 rounded hover:bg-blue-700 transition text-sm">
                            Importar
                        </button>
                    </form>
                @elseif (isset($card['form']) && $card['form'])
                    <form action="{{ $card['action'] }}" method="POST"
                        class="p-3 rounded-lg {{ $card['color'] }} hover:bg-opacity-70 transition relative block"
                        onsubmit="return confirm('{{ $card['confirm'] ?? '¿Estás seguro?' }}')">
                        @csrf
                        <button type="submit" class="w-full h-full flex items-center space-x-3 text-left">
                            <div
                                class="flex items-center justify-center mb-1 rounded-full shadow w-14 h-14 bg-white/80">
                                <i
                                    class="ti ti-{{ $card['icono'] }} text-3xl {{ Str::contains($card['color'], 'blue')
                                        ? 'text-blue-700'
                                        : (Str::contains($card['color'], 'yellow')
                                            ? 'text-yellow-700'
                                            : (Str::contains($card['color'], 'red')
                                                ? 'text-red-700'
                                                : (Str::contains($card['color'], 'green')
                                                    ? 'text-green-700'
                                                    : (Str::contains($card['color'], 'purple')
                                                        ? 'text-purple-700'
                                                        : (Str::contains($card['color'], 'gray')
                                                            ? 'text-gray-700'
                                                            : 'text-gray-800'))))) }}"></i>
                            </div>
                            <span class="font-medium">{{ $card['titulo'] }}</span>
                        </button>
                    </form>
                @else
                    <a href="{{ $card['ruta'] ?? '#' }}"
                        @if (in_array($card['titulo'], ['RRHH', 'Nóminas', 'IMSS'])) @click.prevent="$dispatch('cambiar-menu', { menu: '{{ strtolower(str_replace(' ', '_', $card['titulo'])) }}' })" @endif
                        class="block p-3 rounded-lg {{ $card['color'] }} {{ $isActive ? 'ring-2 ring-blue-500' : '' }} hover:bg-opacity-70 transition relative">
                        <div class="flex items-center space-x-3">
                            <div
                                class="flex items-center justify-center mb-1 rounded-full shadow w-14 h-14 bg-white/80">
                                <i
                                    class="ti ti-{{ $card['icono'] }} text-3xl {{ Str::contains($card['color'], 'blue')
                                        ? 'text-blue-700'
                                        : (Str::contains($card['color'], 'yellow')
                                            ? 'text-yellow-700'
                                            : (Str::contains($card['color'], 'red')
                                                ? 'text-red-700'
                                                : (Str::contains($card['color'], 'green')
                                                    ? 'text-green-700'
                                                    : (Str::contains($card['color'], 'purple')
                                                        ? 'text-purple-700'
                                                        : (Str::contains($card['color'], 'gray')
                                                            ? 'text-gray-700'
                                                            : 'text-gray-800'))))) }}"></i>
                            </div>
                            <span class="font-medium">{{ $card['titulo'] }}</span>
                        </div>
                        @if (isset($card['notificaciones']) && $card['notificaciones'] > 0)
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
        <div x-data="{ slide: 1 }" class="relative">
            <div class="overflow-hidden">
                <div :class="`flex transition-transform duration-500 ease-in-out transform ${slide === 1 ? 'translate-x-0' : 'translate-x-full md:translate-x-[-50%]'}`"
                    style="display: flex; width: 200%;">
                    <div class="flex w-full">
                        <div class="grid gap-4 p-4 w-full" style="grid-template-columns: repeat(3, 1fr);">
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-blue-500">
                                <div class="h-full flex flex-col min-w-0">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Elementos Activos
                                    </h3>
                                    <div class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                                        {{ $activos }}</div>
                                    <div
                                        class="text-sm mt-1 flex items-center gap-1 {{ $variacionActivos >= 0 ? 'text-green-600' : 'text-red-500' }}">
                                        @if ($variacionActivos > 0)
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 shrink-0 fill-current" viewBox="0 0 20 20">
                                                <path d="M5 10l5-5 5 5H5z" />
                                            </svg>
                                        @elseif($variacionActivos < 0)
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 shrink-0 fill-current" viewBox="0 0 20 20">
                                                <path d="M5 10l5 5 5-5H5z" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 shrink-0 fill-current" viewBox="0 0 20 20">
                                                <path d="M4 9h12v2H4z" />
                                            </svg>
                                        @endif
                                        <span class="whitespace-nowrap">
                                            {{ $variacionActivos >= 0 ? '+' : '' }}{{ $variacionActivos }}% vs mes
                                            pasado
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-green-500">
                                <div class="h-full flex flex-col min-w-0">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Altas Nuevas</h3>
                                    <div class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                                        {{ $conteoAltasAdmin }}</div>
                                    <div
                                        class="text-sm mt-1 flex items-center gap-1 {{ $variacionAltas >= 0 ? 'text-green-600' : 'text-red-500' }}">
                                        @if ($variacionAltas > 0)
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 shrink-0 fill-current" viewBox="0 0 20 20">
                                                <path d="M5 10l5-5 5 5H5z" />
                                            </svg>
                                        @elseif($variacionAltas < 0)
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 shrink-0 fill-current" viewBox="0 0 20 20">
                                                <path d="M5 10l5 5 5-5H5z" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 shrink-0 fill-current" viewBox="0 0 20 20">
                                                <path d="M4 9h12v2H4z" />
                                            </svg>
                                        @endif
                                        <span class="whitespace-nowrap">
                                            {{ $variacionAltas >= 0 ? '+' : '' }}{{ $variacionAltas }}% vs mes pasado
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-yellow-500">
                                <div class="h-full flex flex-col min-w-0">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Bajas Recientes
                                    </h3>
                                    <div class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                                        {{ $conteoBajasAdmin }}</div>
                                    <div
                                        class="text-sm mt-1 flex items-center gap-1 {{ $variacionBajas >= 0 ? 'text-red-600' : 'text-green-500' }}">
                                        @if ($variacionBajas > 0)
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 shrink-0 fill-current" viewBox="0 0 20 20">
                                                <path d="M5 10l5-5 5 5H5z" />
                                            </svg>
                                        @elseif($variacionBajas < 0)
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 shrink-0 fill-current" viewBox="0 0 20 20">
                                                <path d="M5 10l5 5 5-5H5z" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 shrink-0 fill-current" viewBox="0 0 20 20">
                                                <path d="M4 9h12v2H4z" />
                                            </svg>
                                        @endif
                                        <span class="whitespace-nowrap">
                                            {{ $variacionBajas >= 0 ? '+' : '' }}{{ $variacionBajas }}% vs mes pasado
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex w-full">
                        <div class="grid gap-4 p-4 w-full" style="grid-template-columns: repeat(3, 1fr);">
                            @livewire('nominamensual')
                            @livewire('finiquitomensual')
                            @livewire('destajosmensuales')
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de navegación
            <div class="flex justify-center space-x-2 mt-4">
                <button type="button" @click="slide = 1"
                    :class="{ 'bg-blue-500 text-white': slide ===
                        1, 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300': slide !== 1 }"
                    class="w-3 h-3 rounded-full focus:outline-none" aria-label="Slide 1"></button>
                <button type="button" @click="slide = 2"
                    :class="{ 'bg-blue-500 text-white': slide ===
                        2, 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300': slide !== 2 }"
                    class="w-3 h-3 rounded-full focus:outline-none" aria-label="Slide 2"></button>
            </div>-->

            <!-- Flechas opcionales (puedes quitarlas si no las quieres) -->
            <button type="button" @click="slide = 1"
                class="absolute top-1/2 left-2 transform -translate-y-1/2 bg-white dark:bg-gray-800 rounded-full p-1 shadow-md opacity-70 hover:opacity-100 focus:outline-none"
                :disabled="slide === 1" :class="{ 'cursor-not-allowed opacity-40': slide === 1 }">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700 dark:text-gray-300"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button type="button" @click="slide = 2"
                class="absolute top-1/2 right-2 transform -translate-y-1/2 bg-white dark:bg-gray-800 rounded-full p-1 shadow-md opacity-70 hover:opacity-100 focus:outline-none"
                :disabled="slide === 2" :class="{ 'cursor-not-allowed opacity-40': slide === 2 }">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700 dark:text-gray-300"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>


        <div class="relative p-6">
            <div class="relative overflow-hidden rounded-lg">
                <div id="carouselSlides" class="flex transition-transform duration-300 ease-in-out">
                    <div class="w-full flex-shrink-0 px-4">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 h-full">
                            @livewire('nominastotales')
                        </div>
                    </div>
                    <div class="w-full flex-shrink-0 px-4">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 h-full">
                            @livewire('graficasfiniquitos')
                        </div>
                    </div>
                    <div class="w-full flex-shrink-0">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-4">
                            @livewire('graficas-altas')
                        </div>
                    </div>
                </div>
            </div>

            <button onclick="prevSlide()" class="carousel-arrow left">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <button onclick="nextSlide()" class="carousel-arrow right">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <div class="flex justify-center mt-4 space-x-2">
                @for ($i = 0; $i < 3; $i++)
                    <button onclick="goToSlide({{ $i }})"
                        class="w-3 h-3 rounded-full bg-gray-400 hover:bg-blue-500 transition indicator-dot"></button>
                @endfor
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        let currentSlide = 0;
        const totalSlides = 3;
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
            //setInterval(nextSlide, 10000);
        });
    </script>
@endpush
