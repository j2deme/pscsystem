<div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        <div class="border-b border-gray-200 dark:border-gray-700 pb-6 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center">
                        @if ($mostrarCoberturas)
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 mr-3 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Historial de Turnos Cubiertos
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 mr-3 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Historial de Tiempos Extras
                        @endif
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        @if ($mostrarCoberturas)
                            Consulte y gestione los turnos que han sido cubiertos por otros empleados
                        @else
                            Consulte y gestione los tiempos extras registrados por los empleados
                        @endif
                    </p>
                </div>

                <div class="flex items-center">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 mr-3">
                        @if ($mostrarCoberturas)
                            Ver Tiempos Extras
                        @else
                            Ver Turnos Cubiertos
                        @endif
                    </span>
                    <button wire:click="cambiarVista"
                            class="relative inline-flex h-6 w-11 items-center rounded-full bg-gray-300 dark:bg-gray-600 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                        <span class="sr-only">Cambiar vista</span>
                        <span class="{{ $mostrarCoberturas ? 'translate-x-6 bg-green-500' : 'translate-x-1 bg-purple-500' }} inline-block h-4 w-4 transform rounded-full bg-white transition-transform" />
                    </button>
                </div>
            </div>
        </div>

        <div class="transition-all duration-300 ease-in-out">
            @if ($mostrarCoberturas)
                <div class="animate-fadeIn">
                    @livewire('supcoberturaturno')
                </div>
            @else
                <div class="animate-fadeIn">
                    @livewire('suptiempoextra')
                </div>
            @endif
        </div>

        <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
            <div class="flex items-start">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 dark:text-blue-400 mt-0.5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200">Información</h4>
                    <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">
                        @if ($mostrarCoberturas)
                            Utilice esta sección para revisar todos los turnos que han sido cubiertos por otros empleados cuando el personal original no pudo asistir.
                        @else
                            Utilice esta sección para revisar todos los tiempos extras registrados por los empleados fuera de su horario regular.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out forwards;
        }
    </style>
</div>
