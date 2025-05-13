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
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Hora Inicio</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Hora Fin</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Punto</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cobertura</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Observaciones</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($coberturas as $cobertura)
                    <tr class="border-t dark:border-gray-700">
                        <td class="px-4 py-2 whitespace-nowrap">{{$loop->iteration}}</td>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $cobertura->user->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ \Carbon\Carbon::parse($cobertura->fecha)->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ \Carbon\Carbon::parse($cobertura->hora_inicio)->format('H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ \Carbon\Carbon::parse($cobertura->hora_fin)->format('H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $cobertura->punto_procedencia }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $cobertura->punto_cobertura }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $cobertura->observaciones }}</td>
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
        @if($coberturas->hasPages())
                        <div class="mt-4">
                            <nav role="navigation">
                                <ul class="flex justify-center space-x-2">
                                    @if($coberturas->onFirstPage())
                                        <li class="px-3 py-1 text-gray-500" aria-disabled="true">
                                            <span>&laquo;</span>
                                        </li>
                                    @else
                                        <li>
                                            <button wire:click="previousPage" class="px-3 py-1 text-blue-600 hover:text-blue-800" rel="prev">
                                                &laquo;
                                            </button>
                                        </li>
                                    @endif

                                    @foreach(range(1, $coberturas->lastPage()) as $page)
                                        <li>
                                            @if($page == $coberturas->currentPage())
                                                <span class="px-3 py-1 bg-blue-500 text-white rounded">{{ $page }}</span>
                                            @else
                                                <button wire:click="gotoPage({{ $page }})" class="px-3 py-1 text-blue-600 hover:text-blue-800">
                                                    {{ $page }}
                                                </button>
                                            @endif
                                        </li>
                                    @endforeach

                                    @if($coberturas->hasMorePages())
                                        <li>
                                            <button wire:click="nextPage" class="px-3 py-1 text-blue-600 hover:text-blue-800" rel="next">
                                                &raquo;
                                            </button>
                                        </li>
                                    @else
                                        <li class="px-3 py-1 text-gray-500" aria-disabled="true">
                                            <span>&raquo;</span>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                    @endif
    </div>
        <div class="flex justify-center mt-6">
            <a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 mr-2 mb-2">
                Regresar
            </a>
        </div>
</div>
