<x-app-layout>
    <x-navbar></x-navbar>

    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="container mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Registro de Asistencias</h2>
                <div class="mb-6 p-4 bg-white dark:bg-gray-800 rounded-lg shadow flex flex-col md:flex-row md:items-center md:justify-between">
                    <form action="{{ route('sup.verFechaAsistencias') }}" method="GET" class="flex items-center gap-4">
                        <label for="fecha" class="text-gray-700 dark:text-gray-300 font-semibold">Seleccionar Fecha:</label>
                        <input type="date" id="fecha" name="fecha" value="{{ $fechaSeleccionada ?? '' }}" class="rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">
                            Buscar
                        </button>
                    </form>
                </div>
                @if(count($asistenciasElementos) == 0)
                    <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
                        <p class="text-gray-600 dark:text-gray-400">No se han registrado asistencias para la fecha seleccionada.</p>
                    </div>
                @else
                @foreach ($asistenciasElementos as $asistencia)
                    <div class="mb-6 p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                            Asistencia del {{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }} a las {{ $asistencia->hora_asistencia }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Observaciones: {{ $asistencia->observaciones }}</p>

                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-3">
                            @foreach ($asistencia->usuarios_enlistados as $usuario)
                                <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded-lg text-center shadow">
                                    <img src="{{ $usuario->solicitudAlta?->documentacion?->arch_foto ? asset($usuario->solicitudAlta->documentacion->arch_foto) . '?v=' . now()->timestamp : asset('images/default-user.jpg') }}" class="w-16 h-16 rounded-full mx-auto object-cover mb-2 border border-gray-300 dark:border-gray-600">
                                    <p class="text-gray-800 dark:text-white font-medium">{{ $usuario->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $usuario->punto }} - {{ $usuario->empresa }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
                @endif
            </div>
            <center><br>
                <a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 mr-2 mb-2">
                    Regresar
                </a>
            </center>
        </div>
    </div>
</x-app-layout>
