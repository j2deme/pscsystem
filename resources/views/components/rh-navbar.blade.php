@php
    use App\Models\SolicitudAlta;
    use App\Models\SolicitudBajas;

    $altasEnProceso = SolicitudAlta::where('status', 'En Proceso')->count();
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
                    'titulo' => 'Archivos',
                    'ruta' => route('rh.archivos'),
                    'icono' => '📁',
                    'color' => 'bg-green-100 dark:bg-green-700'
                ],
                [
                    'titulo' => 'Generar Nueva Alta',
                    'ruta' => route('rh.generarNuevaAltaForm'),
                    'icono' => '📈',
                    'color' => 'bg-green-100 dark:bg-green-700'
                ],
                [
                    'titulo' => 'Generar Nueva Baja',
                    'ruta' => route('rh.generarNuevaBajaForm'),
                    'icono' => '📉',
                    'color' => 'bg-red-100 dark:bg-red-700'
                ],
            ];
        @endphp

        @foreach($cards as $card)
            <a href="{{ $card['ruta'] }}" class="transition-transform transform hover:scale-105">
                <div class="p-4 rounded-xl shadow-md {{ $card['color'] }} hover:shadow-lg h-full flex flex-col justify-between">
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
