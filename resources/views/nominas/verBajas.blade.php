<x-app-layout>
    <x-navbar></x-navbar>
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h1 class="text-xl font-semibold text-gray-800 dark:text-white">Bajas Recientes</h1>
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No.</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nombre</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Empresa</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Por</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Detalles</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha de Baja</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($bajas as $solicitud)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2 px-4 whitespace-nowrap">{{$loop->iteration }}</td>
                                    <td class="py-2 px-4 whitespace-nowrap">{{ $solicitud->user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $solicitud->user->empresa }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $solicitud->por }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $solicitud->motivo }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ \Carbon\Carbon::parse($solicitud->fecha_baja)->format('d/m/Y') }}</td>
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
                                        <a href="{{ route('rh.detalleSolicitudBaja', $solicitud->id) }}"
                                        class="inline-flex items-center justify-center p-2 rounded-full bg-blue-500 text-white hover:bg-blue-600 transition duration-200 shadow-sm"
                                        title="Ver Detalles">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                            </svg>
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
        </div>
    </div>
</x-app-layout>
