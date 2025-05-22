@php
    $currentPage = $usuarios->currentPage();
    $lastPage = $usuarios->lastPage();
@endphp
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
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr class="bg-gray-100 dark:bg-gray-700">
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nombre</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Rol</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Punto</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($usuarios as $usuario)
                    <tr class="border-b dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-2 whitespace-nowrap">{{ $usuario->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $usuario->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $usuario->rol }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $usuario->punto }}</td>
                        <td class="px-4 py-2">
                            <a href="{{route('rh.llenarBaja', $usuario->id)}}" class="inline-block rounded ml-2 text-red-500 dark:bg-red-700 px-2">Generar Baja</a>
                        </td>
                    </tr>
                @empty
                    <tr class="border-b dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td colspan="3" class="px-4 py-4 text-center text-gray-500">
                            @if($search)
                                No se encontraron usuarios para "{{ $search }}"
                            @else
                                No hay usuarios activos registrados
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <ul class="flex justify-center space-x-2">
                        @if ($usuarios->onFirstPage())
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

                        @if ($usuarios->hasMorePages())
                            <li>
                                <button wire:click="nextPage" class="px-3 py-1 text-blue-600 hover:text-blue-800">&raquo;</button>
                            </li>
                        @else
                            <li class="px-3 py-1 text-gray-500" aria-disabled="true">&raquo;</li>
                        @endif
                    </ul>
    </div>

</div>
