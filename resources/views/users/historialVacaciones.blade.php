<x-app-layout>
    <x-navbar></x-navbar>

    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="container mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    Historial de Vacaciones
                </h2>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 uppercase text-xs">
                            <tr>
                                <th class="px-4 py-2">Fecha Solicitud</th>
                                <th class="px-4 py-2">Inicio</th>
                                <th class="px-4 py-2">Fin</th>
                                <th class="px-4 py-2">Días Solicitados</th>
                                <th class="px-4 py-2">Días por Derecho</th>
                                <th class="px-4 py-2">Días Disponibles</th>
                                <th class="px-4 py-2">Estatus</th>
                                <th class="px-4 py-2">Autorizado Por</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($vacaciones as $vacacion)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($vacacion->created_at)->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($vacacion->fecha_inicio)->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($vacacion->fecha_fin)->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2">{{ $vacacion->dias_solicitados }}</td>
                                    <td class="px-4 py-2">{{ $vacacion->dias_por_derecho }}</td>
                                    <td class="px-4 py-2">{{ $vacacion->dias_disponibles }}</td>
                                    <td class="px-4 py-2">
                                        @if($vacacion->estatus === 'Aceptada')
                                            <span class="bg-green-200 text-green-800 px-2 py-1 rounded-full text-xs">Aceptada</span>
                                        @elseif($vacacion->estatus === 'En Proceso')
                                            <span class="bg-yellow-200 text-yellow-800 px-2 py-1 rounded-full text-xs">En Proceso</span>
                                        @else
                                            <span class="bg-red-200 text-red-800 px-2 py-1 rounded-full text-xs">Rechazada</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">{{ $vacacion->autorizado_por }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">
                                        No se han registrado vacaciones.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 flex justify-center">
                    <a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 mr-2 mb-2">
                        Regresar
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
