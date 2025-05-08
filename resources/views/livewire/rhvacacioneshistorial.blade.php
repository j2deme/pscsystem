<div>
    <div class="mb-6 justify-between">
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Buscar por nombre o fecha"
            class="w-1/3 p-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
        >
        <input
            type="date"
            wire:model.live="fecha"
            class="w-1/3 p-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
        />
        <div wire:loading class="text-sm text-gray-500 mt-1">
            Buscando...
        </div>

        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">No.</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Empleado</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Días Solicitados</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Fecha Inicio</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Fecha Fin</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Estatus</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($solicitudes as $solicitud)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-2">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2">{{ $solicitud->user->name ?? 'N/A' }}</td>
                        <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $solicitud->dias_solicitados }}</td>
                        <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($solicitud->fecha_inicio)->format('d/m/Y') }}</td>
                        <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($solicitud->fecha_fin)->format('d/m/Y') }}</td>
                        <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">
                            @if ($solicitud->estatus == 'Aceptada')
                                <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Aceptada</span>
                            @elseif ($solicitud->estatus == 'Rechazada')
                                <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">Rechazada</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">En Proceso</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4 flex justify-center">
            <a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400">
                Regresar
            </a>
        </div>
    </div>
</div>
