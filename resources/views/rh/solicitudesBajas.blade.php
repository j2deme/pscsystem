<x-app-layout>
    <x-navbar/>
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="container mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <h2 class="text-2xl mb-4">Solicitudes Pendientes</h2>
            @if(session('success'))
                <div class="bg-green-100 border-t-4 border-green-500 rounded-b text-green-900 px-4 py-3 shadow-md" role="alert">
                    <div class="flex">
                        <div>
                            <p class="text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
                @else
            @endif

            @if($solicitudes->isEmpty())
                <p class="mt-4">No hay solicitudes pendientes de respuesta.</p>
                <center><br><a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 mr-2 mb-2">
                    Regresar
                </a></center>
            @else
                <div class="overflow-x-auto bg-white rounded-lg shadow">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100 text-gray-700 text-left text-sm uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3">No.</th>
                                <th class="px-6 py-3">Nombre</th>
                                <th class="px-6 py-3">Empresa</th>
                                <th class="px-6 py-3">Por</th>
                                <th class="px-6 py-3">Detalles</th>
                                <th class="px-6 py-3">Fecha de solicitud</th>
                                <th class="px-6 py-3">Estado</th>
                                <th class="px-6 py-3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 text-sm font-medium text-gray-700">
                            @foreach($solicitudes as $solicitud)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2 px-4">{{ $loop->iteration }}</td>
                                    <td class="py-2 px-4">{{ $solicitud->user->name }}</td>
                                    <td class="py-2 px-4">{{ $solicitud->user->solicitudAlta->empresa }}</td>
                                    <td class="py-2 px-4">{{ $solicitud->por }}</td>
                                    <td class="py-2 px-4">{{ $solicitud->motivo }}</td>
                                    <td class="py-2 px-4">{{ $solicitud->fecha_solicitud }}</td>
                                    <td class="py-2 px-4">
                                        @if($solicitud->estatus == 'En Proceso')
                                            <span class="inline-flex items-center justify-center px-2 py-1 mr-2 text-xs leading-none text-gray-800 bg-yellow-300 rounded-full">
                                                {{ $solicitud->estatus }}
                                            </span>
                                        @elseif($solicitud->estatus == 'Aceptada')
                                            <span class="inline-flex items-center justify-center px-2 py-1 mr-2 text-xs leading-none text-green-100 bg-green-600 rounded-full">
                                                {{ $solicitud->estatus }}
                                            </span>
                                        @elseif($solicitud->estatus == 'Rechazada')
                                            <span class="inline-flex items-center justify-center px-2 py-1 mr-2 text-xs leading-none text-red-100 bg-red-600 rounded-full">
                                                {{ $solicitud->estatus }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-2 px-4">
                                        <a href="{{route('rh.detalleSolicitudBaja', $solicitud->id)}}" class="text-blue-500 py-2 px-4 rounded-md mr-2 mb-2">
                                            Ver Detalles
                                        </a>
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
