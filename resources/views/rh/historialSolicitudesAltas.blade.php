<x-app-layout>
    <x-navbar></x-navbar>
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="container mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <h2 class="text-2xl mb-4">Historial de Solicitudes de Alta</h2>
            @if($solicitudes->isEmpty())
                <p class="mt-4">No hay registros de solicitudes.</p>
            @else
                <div class="overflow-x-auto bg-white rounded-lg shadow">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100 text-gray-700 text-left text-sm uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3">No.</th>
                                <th class="px-6 py-3">Nombre</th>
                                <th class="px-6 py-3">CURP</th>
                                <th class="px-6 py-3">RFC</th>
                                <th class="px-6 py-3">Fecha de solicitud</th>
                                <th class="px-6 py-3">Estado</th>
                                <th class="px-6 py-3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 text-sm font-medium text-gray-700">
                            @foreach($solicitudes as $solicitud)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $solicitud->nombre }} {{ $solicitud->apellido_paterno }} {{ $solicitud->apellido_materno }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $solicitud->curp }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $solicitud->rfc }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $solicitud->created_at->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $solicitud->status == 'En Proceso' ? 'bg-yellow-100 text-yellow-800' :
                                                ($solicitud->status == 'Aceptada' ? 'bg-green-100 text-green-800' :
                                                'bg-red-100 text-red-800') }}">
                                            {{ $solicitud->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{route('rh.detalleSolicitud', $solicitud->id)}}" class="text-blue-600 hover:text-blue-900">Ver Más</a>
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
