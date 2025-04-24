<x-app-layout>
    <x-navbar></x-navbar>
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="container mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                @if(session('success'))
                    <div class="flex items-center bg-green-300 text-white text-sm font-bold px-4 py-3" role="alert">
                        <h1>{{ session('success') }}</h1>
                    </div>
                @endif
                <h1 class="text-xl font-semibold text-gray-800 dark:text-white">Solicitudes de vacaciones</h1>
                @if($solicitudes->isEmpty())
                    <p class="text-gray-500">No hay solicitudes registradas.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                                <tr>
                                    <th class="px-4 py-2 text-left">Empleado</th>
                                    <th class="px-4 py-2 text-left">Punto</th>
                                    <th class="px-4 py-2 text-left">Inicio</th>
                                    <th class="px-4 py-2 text-left">Fin</th>
                                    <th class="px-4 py-2 text-left">Días</th>
                                    <th class="px-4 py-2 text-left">Estatus</th>
                                    <th class="px-4 py-2 text-left">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($solicitudes as $solicitud)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-4 py-2">
                                            {{ $solicitud->user->name }}<br>
                                        </td>
                                        <td class="px-4 py-2">{{ $solicitud->user->punto }}</td>
                                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($solicitud->fecha_inicio)->format('d/m/Y') }}</td>
                                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($solicitud->fecha_fin)->format('d/m/Y') }}</td>
                                        <td class="px-4 py-2 text-center">{{ $solicitud->dias_solicitados }}</td>
                                        <td class="px-4 py-2">
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                                @if($solicitud->estatus === 'Aceptada')
                                                    bg-green-100 text-green-800
                                                @elseif($solicitud->estatus === 'Rechazada')
                                                    bg-red-100 text-red-800
                                                @else
                                                    bg-yellow-100 text-yellow-800
                                                @endif
                                            ">
                                                {{ $solicitud->estatus }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2">
                                            @if($solicitud->estatus != 'En Proceso')
                                            @else
                                                <a href="{{ route('sup.aceptarSolicitudVacaciones', $solicitud->id) }}" class="text-blue-500 hover:text-blue-700">Aceptar</a>
                                                <a href="{{ route('sup.rechazarSolicitudVacaciones', $solicitud->id) }}" class="ml-2 text-red-500 hover:text-red-700">Rechazar</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <center><br><a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 mr-2 mb-2">
                            Regresar
                        </a></center>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
