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
    @if($tiemposExtras->isEmpty())
                <p>No hay tiempos extras registrados.</p>
            @else
                <div class="overflow-x-auto bg-white rounded-lg shadow">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No.</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nombre</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Hora de Inicio</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Hora de Fin</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tiempo Extra (H-m-s)</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Autorizado por</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($tiemposExtras as $tiempoExtra)
                                <tr class="border-t dark:border-gray-700">
                                    <td class="px-4 py-2 text-center">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-2 text-center">
                                        {{ $tiempoExtra->user->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ \Carbon\Carbon::parse($tiempoExtra->fecha)->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $tiempoExtra->hora_inicio }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $tiempoExtra->hora_fin }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300"><center>{{ $tiempoExtra->total_horas }}</center></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $tiempoExtra->autorizado_por }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($tiemposExtras->hasPages())
                        <div class="mt-4">
                            <nav role="navigation">
                                <ul class="flex justify-center space-x-2">
                                    @if($tiemposExtras->onFirstPage())
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

                                    @foreach(range(1, $tiemposExtras->lastPage()) as $page)
                                        <li>
                                            @if($page == $tiemposExtras->currentPage())
                                                <span class="px-3 py-1 bg-blue-500 text-white rounded">{{ $page }}</span>
                                            @else
                                                <button wire:click="gotoPage({{ $page }})" class="px-3 py-1 text-blue-600 hover:text-blue-800">
                                                    {{ $page }}
                                                </button>
                                            @endif
                                        </li>
                                    @endforeach

                                    @if($tiemposExtras->hasMorePages())
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
            @endif
            <center><br><a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 mr-2 mb-2">
                Regresar
            </a></center>
</div>
