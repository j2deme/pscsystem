<div>
    <div class="mb-6">
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Buscar por nombre..."
            class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
        >
        <div wire:loading class="text-sm text-gray-500 mt-1">
            Buscando...
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100 text-gray-700 text-left text-sm tracking-wider">
                <tr>
                    <th class="px-4 py-2 text-center font-normal">No.</th>
                    <th class="px-4 py-2 text-center font-normal">Nombre</th>
                    <th class="px-4 py-2 text-center font-normal">Fecha</th>
                    <th class="px-4 py-2 text-center font-normal">Hora Inicio</th>
                    <th class="px-4 py-2 text-center font-normal">Hora Fin</th>
                    <th class="px-4 py-2 text-center font-normal">Punto</th>
                    <th class="px-4 py-2 text-center font-normal">Cobertura</th>
                    <th class="px-4 py-2 text-center font-normal">Observaciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-sm font-medium text-gray-700">
                @forelse ($coberturas as $cobertura)
                    <tr class="border-t dark:border-gray-700">
                        <td class="px-4 py-2 text-center">{{$loop->iteration}}</td>
                        <td class="px-4 py-2 text-center">{{ $cobertura->user->name ?? '-' }}</td>
                        <td class="px-4 py-2 text-center">{{ \Carbon\Carbon::parse($cobertura->fecha)->format('d/m/Y') }}</td>
                        <td class="px-4 py-2 text-center">{{ \Carbon\Carbon::parse($cobertura->hora_inicio)->format('H:i') }}</td>
                        <td class="px-4 py-2 text-center">{{ \Carbon\Carbon::parse($cobertura->hora_fin)->format('H:i') }}</td>
                        <td class="px-4 py-2 text-center">{{ $cobertura->punto_procedencia }}</td>
                        <td class="px-4 py-2 text-center">{{ $cobertura->punto_cobertura }}</td>
                        <td class="px-4 py-2 text-center">{{ $cobertura->observaciones }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-2 text-center text-gray-500 dark:text-gray-400">
                            No hay coberturas registradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $coberturas->links() }}
    </div>
</div>
