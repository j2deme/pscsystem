@php
    use App\Models\SolicitudAlta;
    use App\Models\SolicitudBajas;

    $altasEnProceso = SolicitudAlta::where('status', 'En Proceso')
                    ->where('observaciones', '!=', 'Solicitud enviada a Administrador.')
                    ->count();
    $bajasEnProceso = SolicitudBajas::where('estatus', 'En Proceso')
                    ->where('por', 'Renuncia')
                    ->count();
@endphp

<div class="col-span-full">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @php
            $cards = [
                [
                    'titulo' => 'Solicitudes de Altas',
                    'ruta' => route('rh.solicitudesAltas'),
                    'icono' => '📈',
                    'color' => 'bg-blue-100 dark:bg-blue-700',
                    'notificaciones' => $altasEnProceso,
                ],
                [
                    'titulo' => 'Solicitudes de Bajas',
                    'ruta' => route('rh.solicitudesBajas'),
                    'icono' => '📉',
                    'color' => 'bg-red-100 dark:bg-red-700',
                    'notificaciones' => $bajasEnProceso,
                ],
                [
                    'titulo' => 'Archivos',
                    'ruta' => route('rh.archivos'),
                    'icono' => '📁',
                    'color' => 'bg-yellow-100 dark:bg-yellow-700'
                ],
                [
                    'titulo' => 'Historial de Altas',
                    'ruta' => route('rh.historialSolicitudesAltas'),
                    'icono' => '🗂️',
                    'color' => 'bg-indigo-100 dark:bg-indigo-700'
                ],
                [
                    'titulo' => 'Historial de Bajas',
                    'ruta' => route('rh.historialSolicitudesBajas'),
                    'icono' => '📜',
                    'color' => 'bg-pink-100 dark:bg-pink-700'
                ],
                [
                    'titulo' => 'Vacaciones',
                    'ruta' => route('rh.vistaVacaciones'),
                    'icono' => '🎉',
                    'color' => 'bg-yellow-100 dark:bg-yellow-700',
                ],
                [
                    'titulo' => 'Generar Nueva Alta',
                    'ruta' => route('rh.generarNuevaAltaForm'),
                    'icono' => '📈',
                    'color' => 'bg-blue-100 dark:bg-blue-700',
                    'disabled' => Auth::user()->rol=='admin'
                ],
                [
                    'titulo' => 'Generar Nueva Baja',
                    'ruta' => route('rh.generarNuevaBajaForm'),
                    'icono' => '📉',
                    'color' => 'bg-red-100 dark:bg-red-700',
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
    @if(Auth::user()->rol=='admin')
        <center><br><a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 mr-2 mb-2">
            Regresar
        </a></center>
    @endif
</div>
