@php
    $currentPage = $users->currentPage();
    $lastPage = $users->lastPage();
@endphp

<div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
    <div class="border-b border-gray-200 dark:border-gray-700 pb-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 mr-3 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Gestión de Usuarios
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Busque y gestione los usuarios del sistema
                </p>
            </div>

            <div class="flex items-center space-x-2">
                <div class="bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 px-3 py-1 rounded-full">
                    <span class="text-sm font-medium">{{ $users->total() }}</span>
                    <span class="text-xs">usuarios</span>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-6">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Buscar por nombre..."
                class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
            >
        </div>
        <div wire:loading class="mt-2 flex items-center text-sm text-blue-600 dark:text-blue-400">
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Buscando usuarios...
        </div>
    </div>

    @if($users->isEmpty())
        <div class="text-center py-12">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 dark:text-gray-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No hay usuarios</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-6">No se encontraron usuarios que coincidan con su búsqueda.</p>
        </div>
    @else
        <div class="overflow-hidden rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                #
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Nombre
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Punto
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Rol
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Estatus
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <div class="flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Acciones
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                                                <span class="text-white font-medium text-xs">
                                                    {{ substr($user->name ?? '', 0, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $user->name ?? 'N/D' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    @if($user->solicitudAlta?->punto)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200">
                                            {{ $user->solicitudAlta->punto }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500">N/D</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    @if($user->rol == 'admin')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-200">
                                            Administrador
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                            {{ $user->rol }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $status = $user->estatus ?? 'Desconocido';
                                        $statusConfig = match($status) {
                                            'Activo' => ['bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200', 'check'],
                                            'Inactivo' => ['bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200', 'x'],
                                            default => ['bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200', 'help'],
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusConfig[0] }}">
                                        @if($statusConfig[1] == 'check')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        @elseif($statusConfig[1] == 'x')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        @elseif($statusConfig[1] == 'help')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @endif
                                        {{ $status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <a href="#"
                                        onclick="abrirModalCarga({{ $user->sol_docs_id }}, {
                                            nombre: '{{ addslashes($user->name) }}',
                                            sd: '{{ $user->solicitudAlta->sd ?? '' }}',
                                            sdi: '{{ $user->solicitudAlta->sdi ?? '' }}',
                                            imssNombre: '{{ optional($user->documentacionAltas)->arch_acuse_imss ? basename($user->documentacionAltas->arch_acuse_imss) : '' }}',
                                            infonavitNombre: '{{ optional($user->documentacionAltas)->arch_retencion_infonavit ? basename($user->documentacionAltas->arch_retencion_infonavit) : '' }}',
                                            imssUrl: '{{ optional($user->documentacionAltas)->arch_acuse_imss ? asset($user->documentacionAltas->arch_acuse_imss) : '' }}',
                                            infonavitUrl: '{{ optional($user->documentacionAltas)->arch_retencion_infonavit ? asset($user->documentacionAltas->arch_retencion_infonavit) : '' }}',
                                            modificacionNombre: '{{ optional($user->documentacionAltas)->arch_modificacion_salario ? basename($user->documentacionAltas->arch_modificacion_salario) : '' }}',
                                            modificacionUrl: '{{ optional($user->documentacionAltas)->arch_modificacion_salario ? asset($user->documentacionAltas->arch_modificacion_salario) : '' }}',
                                        })"
                                        class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-200 shadow-sm text-xs">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Editar
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if($users->hasPages())
            <div class="mt-6 flex items-center justify-between">
                <div class="text-sm text-gray-700 dark:text-gray-300">
                    Mostrando
                    <span class="font-medium">{{ $users->firstItem() }}</span>
                    a
                    <span class="font-medium">{{ $users->lastItem() }}</span>
                    de
                    <span class="font-medium">{{ $users->total() }}</span>
                    usuarios
                </div>

                <div class="flex items-center space-x-1">
                    @if ($users->onFirstPage())
                        <span class="px-3 py-1 text-gray-400 dark:text-gray-500 rounded">&laquo;</span>
                    @else
                        <button wire:click="previousPage" class="px-3 py-1 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 rounded hover:bg-gray-100 dark:hover:bg-gray-700">&laquo;</button>
                    @endif

                    @if ($currentPage > 2)
                        <button wire:click="gotoPage(1)" class="px-3 py-1 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 rounded hover:bg-gray-100 dark:hover:bg-gray-700">1</button>
                        @if ($currentPage > 3)
                            <span class="px-2 py-1 text-gray-400 dark:text-gray-500">...</span>
                        @endif
                    @endif

                    @for ($i = max(1, $currentPage - 1); $i <= min($lastPage, $currentPage + 1); $i++)
                        @if ($i == $currentPage)
                            <span class="px-3 py-1 bg-blue-500 text-white rounded">{{ $i }}</span>
                        @else
                            <button wire:click="gotoPage({{ $i }})" class="px-3 py-1 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 rounded hover:bg-gray-100 dark:hover:bg-gray-700">{{ $i }}</button>
                        @endif
                    @endfor

                    @if ($currentPage < $lastPage - 1)
                        @if ($currentPage < $lastPage - 2)
                            <span class="px-2 py-1 text-gray-400 dark:text-gray-500">...</span>
                        @endif
                        <button wire:click="gotoPage({{ $lastPage }})" class="px-3 py-1 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 rounded hover:bg-gray-100 dark:hover:bg-gray-700">{{ $lastPage }}</button>
                    @endif

                    @if ($users->hasMorePages())
                        <button wire:click="nextPage" class="px-3 py-1 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 rounded hover:bg-gray-100 dark:hover:bg-gray-700">&raquo;</button>
                    @else
                        <span class="px-3 py-1 text-gray-400 dark:text-gray-500 rounded">&raquo;</span>
                    @endif
                </div>
            </div>
        @endif
    @endif

    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
        <div class="flex justify-center">
            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition duration-200 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Regresar
            </a>
        </div>
    </div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function abrirModalCarga(solicitudId, datos = {}) {
    Swal.fire({
        title: 'Editar Documentos de ' + (datos.nombre || ''),
        html: `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left">
                <div id="drop-imss" class="border-2 border-dashed border-blue-400 dark:border-blue-600 rounded-lg p-6 bg-blue-50 dark:bg-blue-900/20 transition-colors duration-200">
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m3-3l3 3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Acuse IMSS</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Arrastre o seleccione el archivo</p>
                        <input type="file" id="file-imss" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                        <button type="button"
                                onclick="document.getElementById('file-imss').click()"
                                class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-200 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m3-3l3 3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Seleccionar archivo
                        </button>
                        <p id="file-name-imss" class="mt-3 text-sm text-green-600 dark:text-green-400">
                            ${datos.imssNombre ? `Archivo actual: <a href="${datos.imssUrl}" target="_blank" class="underline text-blue-600 dark:text-blue-400">${datos.imssNombre}</a>` : ''}
                        </p>
                    </div>
                </div>

                <div id="drop-infonavit" class="border-2 border-dashed border-green-400 dark:border-green-600 rounded-lg p-6 bg-green-50 dark:bg-green-900/20 transition-colors duration-200">
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Retención INFONAVIT</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Arrastre o seleccione el archivo (opcional)</p>
                        <input type="file" id="file-infonavit" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                        <button type="button"
                                onclick="document.getElementById('file-infonavit').click()"
                                class="mt-4 inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition duration-200 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Seleccionar archivo
                        </button>
                        <p id="file-name-infonavit" class="mt-3 text-sm text-green-600 dark:text-green-400">
                            ${datos.infonavitNombre ? `Archivo actual: <a href="${datos.infonavitUrl}" target="_blank" class="underline text-green-600 dark:text-green-400">${datos.infonavitNombre}</a>` : ''}
                        </p>
                    </div>
                </div>

                <div id="drop-modificacion" class="border-2 border-dashed border-purple-400 dark:border-purple-600 rounded-lg p-6 bg-purple-50 dark:bg-purple-900/20 transition-colors duration-200 md:col-span-2">
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-purple-100 dark:bg-purple-900/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Modificación de Salario</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Arrastre o seleccione el archivo (opcional)</p>
                        <input type="file" id="file-modificacion" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                        <button type="button"
                                onclick="document.getElementById('file-modificacion').click()"
                                class="mt-4 inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition duration-200 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Seleccionar archivo
                        </button>
                        <p id="file-name-modificacion" class="mt-3 text-sm text-purple-600 dark:text-purple-400">
                            ${datos.modificacionNombre ? `Archivo actual: <a href="${datos.modificacionUrl}" target="_blank" class="underline text-purple-600 dark:text-purple-400">${datos.modificacionNombre}</a>` : ''}
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="input-sd" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">SD</label>
                    <input type="number" id="input-sd" step="0.01" min="0" value="${datos.sd || ''}"
                           class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                </div>

                <div>
                    <label for="input-sdi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">SDI</label>
                    <input type="number" id="input-sdi" step="0.01" min="0" value="${datos.sdi || ''}"
                           class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Actualizar',
        cancelButtonText: 'Cancelar',
        width: '60rem',
        didOpen: () => {
            const zonas = [
                { zona: 'drop-imss', input: 'file-imss', label: 'file-name-imss' },
                { zona: 'drop-infonavit', input: 'file-infonavit', label: 'file-name-infonavit' },
                { zona: 'drop-modificacion', input: 'file-modificacion', label: 'file-name-modificacion' }
            ];

            zonas.forEach(({ zona, input, label }) => {
                const dropZone = document.getElementById(zona);
                const fileInput = document.getElementById(input);
                const fileLabel = document.getElementById(label);

                dropZone.addEventListener('dragover', e => {
                    e.preventDefault();
                    dropZone.classList.add('border-blue-500', 'dark:border-blue-400', 'bg-blue-100', 'dark:bg-blue-900/30');
                });

                dropZone.addEventListener('dragleave', e => {
                    e.preventDefault();
                    dropZone.classList.remove('border-blue-500', 'dark:border-blue-400', 'bg-blue-100', 'dark:bg-blue-900/30');
                });

                dropZone.addEventListener('drop', e => {
                    e.preventDefault();
                    dropZone.classList.remove('border-blue-500', 'dark:border-blue-400', 'bg-blue-100', 'dark:bg-blue-900/30');
                    const file = e.dataTransfer.files[0];
                    if (!file) return;

                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    fileInput.files = dataTransfer.files;

                    fileLabel.innerHTML = 'Archivo: <span class="text-green-600 dark:text-green-400">' + file.name + '</span>';
                });

                fileInput.addEventListener('change', () => {
                    if (fileInput.files[0]) {
                        fileLabel.innerHTML = 'Archivo: <span class="text-green-600 dark:text-green-400">' + fileInput.files[0].name + '</span>';
                    }
                });
            });
        },
        preConfirm: () => {
            const fileImss = document.getElementById('file-imss').files[0];
            const fileInfonavit = document.getElementById('file-infonavit').files[0];
            const fileModificacion = document.getElementById('file-modificacion').files[0];
            const sd = document.getElementById('input-sd').value;
            const sdi = document.getElementById('input-sdi').value;

            if (!sd || sd <= 0) {
                Swal.showValidationMessage('Debes ingresar un valor válido para SD');
                return false;
            }

            if (!sdi || sdi <= 0) {
                Swal.showValidationMessage('Debes ingresar un valor válido para SDI');
                return false;
            }

            const formData = new FormData();
            formData.append('sol_docs_id', solicitudId);
            formData.append('sd', sd);
            formData.append('sdi', sdi);
            if (fileImss) formData.append('arch_acuse_imss', fileImss);
            if (fileInfonavit) formData.append('arch_retencion_infonavit', fileInfonavit);
            if (fileModificacion) formData.append('arch_modificacion_salario', fileModificacion);
            formData.append('_token', '{{ csrf_token() }}');

            return fetch(`/actualizacion_documentacion/${solicitudId}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw new Error(err.message || 'Error al actualizar la información') });
                }
                return response.json();
            })
            .then(data => {
                if (!data.success) {
                    throw new Error(data.message);
                }
                return data;
            })
            .catch(error => {
                Swal.showValidationMessage(`Error: ${error.message}`);
                return false;
            });
        }
    }).then(result => {
        if (result.isConfirmed) {
            Swal.fire({
                title: '¡Éxito!',
                text: result.value.message,
                icon: 'success'
            }).then(() => {
                window.location.reload();
            });
        }
    });
}
</script>
