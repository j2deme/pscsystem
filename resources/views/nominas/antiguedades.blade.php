<x-app-layout>
    <x-navbar></x-navbar>
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6 mb-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 mr-3 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Control de Antigüedades
                            </h1>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Gestione y consulte las antigüedades de los empleados
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <div class="bg-amber-50 dark:bg-amber-900/20 border-l-4 border-amber-500 rounded-r p-4">
                        <div class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500 dark:text-amber-400 mt-0.5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <h4 class="text-sm font-medium text-amber-800 dark:text-amber-200">Información Importante</h4>
                                <p class="text-sm text-amber-700 dark:text-amber-300 mt-1">
                                    Esta sección muestra el control de antigüedades de los empleados.
                                    Puede filtrar por diferentes criterios para encontrar la información que necesita.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                @livewire('antiguedades')

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
    </div>
</x-app-layout>
