{{-- resources/views/livewire/auxadmin-riesgos-trabajo.blade.php --}}

@php
    $currentPage = $users->currentPage();
    $lastPage = $users->lastPage();
@endphp

<div>
    <div class="mb-6">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por nombre..."
            class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
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
                        Nombre
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Punto
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Rol
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Estatus
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($users as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                            {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-200">
                                {{ $user->name }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                            {{ $user->solicitudAlta?->punto ?? 'No Disponible' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                            @if ($user->rol == 'admin')
                                Administrador
                            @else
                                {{ $user->rol }}
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                            @if ($user->estatus == 'Activo')
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ $user->estatus }}
                                </span>
                            @else
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    {{ $user->estatus }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
    <a href="{{ route('aux.generarRiesgoForm', $user->id) }}"
       class="inline-flex items-center justify-center p-2 rounded-lg bg-blue-50 dark:bg-blue-900/50 text-blue-600 dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-800 transition duration-200"
       title="Generar Riesgo de Trabajo">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V8z" clip-rule="evenodd" />
        </svg>
    </a>
</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Paginación --}}
        <ul class="flex justify-center space-x-2 mt-4">
            @if ($users->onFirstPage())
                <li class="px-3 py-1 text-gray-500" aria-disabled="true">&laquo;</li>
            @else
                <li>
                    <button wire:click="previousPage"
                        class="px-3 py-1 text-blue-600 hover:text-blue-800">&laquo;</button>
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
                        <button wire:click="gotoPage({{ $i }})"
                            class="px-3 py-1 text-blue-600 hover:text-blue-800">{{ $i }}</button>
                    @endif
                </li>
            @endfor

            @if ($currentPage < $lastPage - 1)
                @if ($currentPage < $lastPage - 2)
                    <li class="px-3 py-1 text-gray-500">...</li>
                @endif
                <li>
                    <button wire:click="gotoPage({{ $lastPage }})"
                        class="px-3 py-1 text-blue-600 hover:text-blue-800">{{ $lastPage }}</button>
                </li>
            @endif

            @if ($users->hasMorePages())
                <li>
                    <button wire:click="nextPage" class="px-3 py-1 text-blue-600 hover:text-blue-800">&raquo;</button>
                </li>
            @else
                <li class="px-3 py-1 text-gray-500" aria-disabled="true">&raquo;</li>
            @endif
        </ul>
    </div><br>
    <center>
        <a href="{{ route('dashboard') }}"
            class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 mr-2">
            Regresar
        </a>
    </center>
</div>
