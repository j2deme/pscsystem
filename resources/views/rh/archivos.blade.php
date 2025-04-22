<x-app-layout>
    <x-navbar></x-navbar>
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="container mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h1>Archivos</h1>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100 text-gray-700 text-left text-sm uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3">No.</th>
                            <th class="px-6 py-3">Nombre</th>
                            <th class="px-6 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 text-sm font-medium text-gray-700">

                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">1</td>
                                <td class="px-6 py-4 whitespace-nowrap">Archivo Rojo</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('exportar.bajas') }}"
   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded inline-flex items-center"
   wire:loading.attr="disabled">
    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
    </svg>
    <span wire:loading.remove>Exportar Excel</span>
    <span wire:loading>Generando...</span>
</a>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">2</td>
                                <td class="px-6 py-4 whitespace-nowrap">Archivo Rosa</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="#" class="text-blue-600 hover:text-blue-900">Ver Más</a>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">3</td>
                                <td class="px-6 py-4 whitespace-nowrap">Archivo de Vacaciones</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="#" class="text-blue-600 hover:text-blue-900">Ver Más</a>
                                </td>
                            </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
