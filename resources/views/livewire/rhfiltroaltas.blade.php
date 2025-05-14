@php
    $currentPage = $solicitudes->currentPage();
    $lastPage = $solicitudes->lastPage();
@endphp
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
        @if($solicitudes->isEmpty())
            <p class="mt-4">No hay registros de solicitudes.</p>
        @else
            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No.</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nombre</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">CURP</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">RFC</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha de solicitud</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($solicitudes as $solicitud)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">{{ ($solicitudes->currentPage() - 1) * $solicitudes->perPage() + $loop->iteration }}</td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ trim(($solicitud->nombre ?? '') . ' ' . ($solicitud->apellido_paterno ?? '') . ' ' . ($solicitud->apellido_materno ?? '')) ?: 'N/D' }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $solicitud->curp ?? 'N/D' }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $solicitud->rfc ?? 'N/D' }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ optional($solicitud->created_at)->format('d/m/Y') ?? 'Sin fecha' }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $status = $solicitud->status ?? 'Desconocido';
                                        $statusClasses = match($status) {
                                            'En Proceso' => 'bg-yellow-100 text-yellow-800',
                                            'Aceptada'   => 'bg-green-100 text-green-800',
                                            'Rechazada'  => 'bg-red-100 text-red-800',
                                            default      => 'bg-gray-200 text-gray-800',
                                        };
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses }}">
                                        {{ $status }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('rh.detalleSolicitud', $solicitud->id) }}" class="text-blue-600 hover:text-blue-900">Ver Más</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <ul class="flex justify-center space-x-2">
                    @if ($solicitudes->onFirstPage())
                        <li class="px-3 py-1 text-gray-500" aria-disabled="true">&laquo;</li>
                    @else
                        <li>
                            <button wire:click="previousPage" class="px-3 py-1 text-blue-600 hover:text-blue-800">&laquo;</button>
                        </li>
                    @endif

                    @if ($currentPage > 2)
                        <li>
                            <button wire:click="gotoPage(1)" class="px-3 py-1 text-blue-600 hover:text-blue-800">1</button>
                        </li>
                        @if ($currentPage > 3)
                            <li class="px-3 py-1 text-gray-500">...</li>
                        @endif
                    @endif

                    @for ($i = max(1, $currentPage - 1); $i <= min($lastPage, $currentPage + 1); $i++)
                        <li>
                            @if ($i == $currentPage)
                                <span class="px-3 py-1 bg-blue-500 text-white rounded">{{ $i }}</span>
                            @else
                                <button wire:click="gotoPage({{ $i }})" class="px-3 py-1 text-blue-600 hover:text-blue-800">{{ $i }}</button>
                            @endif
                        </li>
                    @endfor

                    @if ($currentPage < $lastPage - 1)
                        @if ($currentPage < $lastPage - 2)
                            <li class="px-3 py-1 text-gray-500">...</li>
                        @endif
                        <li>
                            <button wire:click="gotoPage({{ $lastPage }})" class="px-3 py-1 text-blue-600 hover:text-blue-800">{{ $lastPage }}</button>
                        </li>
                    @endif

                    @if ($solicitudes->hasMorePages())
                        <li>
                            <button wire:click="nextPage" class="px-3 py-1 text-blue-600 hover:text-blue-800">&raquo;</button>
                        </li>
                    @else
                        <li class="px-3 py-1 text-gray-500" aria-disabled="true">&raquo;</li>
                    @endif
                </ul>
            </div>
            @endif
            <center><br><a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 mr-2 mb-2">
                Regresar
            </a></center>
</div>

