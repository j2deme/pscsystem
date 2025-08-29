<x-app-layout>
    <x-navbar></x-navbar>
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="container mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6 mb-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 mr-3 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Asistencias Totales de la Quincena
                            </h1>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Genere reportes de asistencias filtrando por punto y rango de fechas
                            </p>
                        </div>
                    </div>
                </div>

                <form method="GET" action="{{ route('exportar.asistencias') }}" class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-6 mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="punto" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Punto
                                </div>
                            </label>
                            <select name="punto" id="punto" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-800 dark:text-white">
                                <option value="">Todos</option>
                                <option value="DRONES" {{ request('punto') == 'DRONES' ? 'selected' : '' }}>Drones</option>
                                <option value="KANSAS" {{ request('punto') == 'KANSAS' ? 'selected' : '' }}>Kansas</option>
                                <option value="MONTERREY" {{ request('punto') == 'MONTERREY' ? 'selected' : '' }}>Monterrey</option>
                                <option value="GUANAJUATO" {{ request('punto') == 'GUANAJUATO' ? 'selected' : '' }}>Guanajuato</option>
                                <option value="NUEVO LAREDO" {{ request('punto') == 'NUEVO LAREDO' ? 'selected' : '' }}>Nvo Laredo</option>
                                <option value="MEXICO" {{ request('punto') == 'MEXICO' ? 'selected' : '' }}>Mexico</option>
                                <option value="SLP" {{ request('punto') == 'SLP' ? 'selected' : '' }}>SLP</option>
                                <option value="XALAPA" {{ request('punto') == 'XALAPA' ? 'selected' : '' }}>Xalapa</option>
                                <option value="MICHOACAN" {{ request('punto') == 'MICHOACAN' ? 'selected' : '' }}>Michoacán</option>
                                <option value="PUEBLA" {{ request('punto') == 'PUEBLA' ? 'selected' : '' }}>Puebla</option>
                                <option value="TOLUCA" {{ request('punto') == 'TOLUCA' ? 'selected' : '' }}>Toluca</option>
                                <option value="QUERETARO" {{ request('punto') == 'QUERETARO' ? 'selected' : '' }}>Querétaro</option>
                                <option value="SALTILLO" {{ request('punto') == 'SALTILLO' ? 'selected' : '' }}>Saltillo</option>
                            </select>
                        </div>

                        <div>
                            <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Fecha Inicio
                                </div>
                            </label>
                            <input type="date"
                                   name="fecha_inicio"
                                   id="fecha_inicio"
                                   value="{{ request('fecha_inicio') }}"
                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-800 dark:text-white">
                        </div>

                        <div>
                            <label for="fecha_fin" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Fecha Fin
                                </div>
                            </label>
                            <input type="date"
                                   name="fecha_fin"
                                   id="fecha_fin"
                                   value="{{ request('fecha_fin') }}"
                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-800 dark:text-white">
                        </div>

                        <div class="flex items-end">
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition duration-200 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Generar Reporte
                            </button>
                        </div>
                    </div>
                </form>

                <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 rounded-r p-4 mb-8">
                    <div class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 dark:text-blue-400 mt-0.5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200">Instrucciones</h4>
                            <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">
                                Al hacer clic en "Generar Reporte", se creará un archivo Excel con las asistencias totales
                                del punto y rango de fechas seleccionados.
                            </p>
                        </div>
                    </div>
                </div>

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
