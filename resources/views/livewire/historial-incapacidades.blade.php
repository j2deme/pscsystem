{{-- resources/views/livewire/historial-incapacidades.blade.php --}}
@php
    $currentPage = $incapacidades->currentPage();
    $lastPage = $incapacidades->lastPage();
@endphp

<div>
    <div class="mb-6">
        <input type="text" wire:model.live.debounce.300ms="search"
            placeholder="Buscar por motivo, tipo, folio o nombre de usuario..."
            class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">


        <div wire:loading class="text-sm text-gray-500 mt-1">
            Buscando...
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        No.
                    </th>

                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Nombre Usuario
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Tipo Incapacidad
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Motivo
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Fecha Inicio
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Folio
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        PDF,JPG,JPEG,PNG
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($incapacidades as $incapacidad)
                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                            {{ ($incapacidades->currentPage() - 1) * $incapacidades->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
                            {{ $incapacidad->user->name ?? 'Usuario Eliminado' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                            {{ $incapacidad->tipo_incapacidad }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                            {{ $incapacidad->motivo }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                            {{ \Carbon\Carbon::parse($incapacidad->fecha_inicio)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                            {{ $incapacidad->folio }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if ($incapacidad->ruta_archivo_pdf)
                                <a href="{{ Storage::url($incapacidad->ruta_archivo_pdf) }}" target="_blank"
                                    class="inline-flex items-center justify-center p-2 rounded-full bg-blue-500 text-white hover:bg-blue-600 transition duration-200 shadow-sm"
                                    title="Ver Archivo">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </a>
                            @else
                                <span class="text-gray-400 dark:text-gray-500">N/A</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6"
                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center dark:text-gray-400">
                            No se encontraron incapacidades.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Paginación --}}
        <ul class="flex justify-center space-x-2 mt-4">
            @if ($incapacidades->onFirstPage())
                <li class="px-3 py-1 text-gray-500 dark:text-gray-400" aria-disabled="true">&laquo;</li>
            @else
                <li>
                    <button wire:click="previousPage"
                        class="px-3 py-1 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">&laquo;</button>
                </li>
            @endif

            @if ($currentPage > 2)
                <li>
                    <button wire:click="gotoPage(1)"
                        class="px-3 py-1 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">1</button>
                </li>
                @if ($currentPage > 3)
                    <li class="px-3 py-1 text-gray-500 dark:text-gray-400">...</li>
                @endif
            @endif

            @for ($i = max(1, $currentPage - 1); $i <= min($lastPage, $currentPage + 1); $i++)
                <li>
                    @if ($i == $currentPage)
                        <span class="px-3 py-1 bg-blue-500 text-white rounded">{{ $i }}</span>
                    @else
                        <button wire:click="gotoPage({{ $i }})"
                            class="px-3 py-1 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">{{ $i }}</button>
                    @endif
                </li>
            @endfor

            @if ($currentPage < $lastPage - 1)
                @if ($currentPage < $lastPage - 2)
                    <li class="px-3 py-1 text-gray-500 dark:text-gray-400">...</li>
                @endif
                <li>
                    <button wire:click="gotoPage({{ $lastPage }})"
                        class="px-3 py-1 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">{{ $lastPage }}</button>
                </li>
            @endif

            @if ($incapacidades->hasMorePages())
                <li>
                    <button wire:click="nextPage"
                        class="px-3 py-1 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">&raquo;</button>
                </li>
            @else
                <li class="px-3 py-1 text-gray-500 dark:text-gray-400" aria-disabled="true">&raquo;</li>
            @endif
        </ul>
    </div>
    <br>
    <center>
        <a href="{{ route('dashboard') }}"
            class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 mr-2 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
            Regresar
        </a>
        {{-- Botón para generar PDF --}}

        <a href="{{ route('reporte.incapacidades.pdf') }}" target="_blank"
            class="ml-4 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 dark:bg-green-700 dark:hover:bg-green-800">
            Generar Reporte PDF
        </a>
    </center>
</div>
