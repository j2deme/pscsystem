<div>
    <div class="mb-6">
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Buscar por nombre..."
            class="w-1/3 p-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
        >
        <input
            type="date"
            wire:model.live="fecha"
            class="w-1/4 p-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
        />
        <div wire:loading class="text-sm text-gray-500 mt-1">
            Buscando...
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No.</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nombre</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Punto Cubierto</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Supervisor</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($coberturas as $registro)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>
                        <td>{{ $registro['usuario']->name }}</td>
                        <td>{{ $registro['subpunto_nombre'] }}</td>
                        <td>{{ Carbon\Carbon::parse($registro['fecha'])->format('d/m/Y') }}</td>
                        <td>{{ $registro['supervisor'] }}</td>
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
        <div class="mt-4">
    {{ $coberturas->links() }}
</div>
    </div>
        <div class="flex justify-center mt-6">
            <a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 mr-2 mb-2">
                Regresar
            </a>
        </div>
</div>
