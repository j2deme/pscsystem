<x-app-layout>
    <x-navbar></x-navbar>
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="container mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h1 class="text-xl font-semibold text-gray-800 dark:text-white">Asistencias Totales de la Quincena</h1>
                <form method="GET" action="#" class="mt-4 flex flex-col sm:flex-row sm:items-end gap-4 justify-center">
                    <div>
                        <label for="punto" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Punto</label>
                        <select name="punto" id="punto" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todos</option>
                            <option value="DRONES">Drones</option>
                            <option value="KANSAS">Kansas</option>
                            <option value="MONTERREY">Monterrey</option>
                            <option value="GUANAJUATO">Guanajuato</option>
                            <option value="NUEVO LAREDO">Nvo Laredo</option>
                            <option value="MEXICO">Mexico</option>
                            <option value="SLP">SLP</option>
                            <option value="XALAPA">Xalapa</option>
                            <option value="MICHOACAN">Michoacán</option>
                            <option value="PUEBLA">Puebla</option>
                            <option value="TOLUCA">Toluca</option>
                            <option value="QUERETARO">Querétaro</option>
                            <option value="SALTILLO">Saltillo</option>
                        </select>
                    </div>

                    <div>
                        <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Fecha Inicio</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="fecha_fin" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Fecha Fin</label>
                        <input type="date" name="fecha_fin" id="fecha_fin" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mt-6">
                            Filtrar
                        </button>
                    </div>
                </form>
                <br><p><strong>Nota:</strong> Al hacer clic en "Filtrar", se generará un archivo excel con las asistencias totales de del punto y el rango de fechas seleccionada.</p><br><br>
                <center>
                    <a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 mr-2 mb-2 ml-2 mt-2">
                        Regresar
                    </a>
                </center>
            </div>
        </div>
    </div>
</x-app-layout>
