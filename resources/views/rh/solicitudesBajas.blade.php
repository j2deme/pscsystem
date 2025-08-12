<x-app-layout>
    <x-navbar />
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="container mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Solicitudes Pendientes</h2>
                </div>

                @if(session('success'))
                    <div class="bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 p-4 mx-6 my-4 rounded-r-lg">
                        <p class="text-green-700 dark:text-green-300 text-sm">{{ session('success') }}</p>
                    </div>
                @endif

                @if($solicitudes->isEmpty())
                    <div class="text-center py-10">
                        <p class="text-gray-500 dark:text-gray-400 text-lg">No hay solicitudes pendientes de respuesta.</p>
                        <div class="mt-6">
                            <a href="{{ route('dashboard') }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Regresar
                            </a>
                        </div>
                    </div>
                @else
                    <!-- Tabla -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900/50 text-left text-sm font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-4">No.</th>
                                    <th class="px-6 py-4">Nombre</th>
                                    <th class="px-6 py-4">Empresa</th>
                                    <th class="px-6 py-4">Por</th>
                                    <th class="px-6 py-4">Observaciones</th>
                                    <th class="px-6 py-4">Fecha</th>
                                    <th class="px-6 py-4">Estado</th>
                                    <th class="px-6 py-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                @foreach($solicitudes as $solicitud)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition duration-150 ease-in-out">
                                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4 font-medium text-gray-800 dark:text-white">{{ $solicitud->user->name }}</td>
                                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $solicitud->user->solicitudAlta->empresa ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $solicitud->por }}</td>
                                        <td class="px-6 py-4 text-gray-600 dark:text-gray-400 max-w-xs truncate" title="{{ $solicitud->observaciones }}">
                                            {{ $solicitud->observaciones }}
                                        </td>
                                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($solicitud->fecha_solicitud)->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4">
                                            @if($solicitud->estatus == 'En Proceso')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">
                                                    {{ $solicitud->estatus }}
                                                </span>
                                            @elseif($solicitud->estatus == 'Aceptada')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                                    {{ $solicitud->estatus }}
                                                </span>
                                            @elseif($solicitud->estatus == 'Rechazada')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
                                                    {{ $solicitud->estatus }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 flex items-center justify-center">
                                            @if($solicitud->observaciones == 'Finiquito enviado a RH.')
                                                <a href="{{ asset('storage/' . $solicitud->calculo_finiquito) }}" target="_blank"
                                                   class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300"
                                                   title="Ver Cálculo de Finiquito">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Ver Finiquito
                                                </a>
                                            @else
                                                <a href="{{ route('rh.detalleSolicitudBaja', $solicitud->id) }}"
                                                   class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                                                   title="Ver Detalles de la Solicitud">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                    </svg>
                                                    Ver Solicitud
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-6 text-center border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('dashboard') }}"
                           class="inline-flex items-center px-5 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 font-medium transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Regresar
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
