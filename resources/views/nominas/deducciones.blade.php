@php
    $currentPage = $deducciones->currentPage();
    $lastPage = $deducciones->lastPage();
@endphp
<x-app-layout>
    <x-navbar></x-navbar>
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="container mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                @if(session('success'))
                    <div class="bg-green-100 border-t-4 border-green-500 rounded-b text-green-900 px-4 py-3 shadow-md" role="alert">
                        <div class="flex">
                            <div>
                                <p class="text-sm">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md" role="alert">
                        <div class="flex">
                            <div>
                                <p class="text-sm">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                <h1 class="text-xl font-semibold text-gray-800 dark:text-white">Deducciones</h1>
                @if($deducciones->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400">No hay deducciones registradas al momento.</p>
                @else
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    No.
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Usuario
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Monto
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Concepto
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    No. Quincenas
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Monto Pendiente
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Estado
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                            @foreach ($deducciones as $deduccion)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ ($deducciones->currentPage() - 1) * $deducciones->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $deduccion->user->name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            $ {{ $deduccion->monto }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ $deduccion->concepto }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ $deduccion->num_quincenas }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-bold">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            $ {{ $deduccion->monto_pendiente }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $deduccion->status == 'Pendiente' ? 'bg-yellow-100 text-yellow-800' :
                                                ($deduccion->status == 'Pagada' ? 'bg-green-100 text-green-800' :
                                                'bg-red-100 text-red-800') }}">
                                            {{ $deduccion->status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <ul class="flex justify-center space-x-2 mt-4">
                        @if ($deducciones->onFirstPage())
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

                        @if ($deducciones->hasMorePages())
                            <li>
                                <button wire:click="nextPage" class="px-3 py-1 text-blue-600 hover:text-blue-800">&raquo;</button>
                            </li>
                        @else
                            <li class="px-3 py-1 text-gray-500" aria-disabled="true">&raquo;</li>
                        @endif
                    </ul>
                @endif
                <div class="form-group flex items-center justify-center mt-4">
                    <a href="{{ route('crearDeduccion') }}" class="inline-block bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">Nueva Deducción</a>
                    <a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 ml-2 mr-2">
                        Regresar
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
