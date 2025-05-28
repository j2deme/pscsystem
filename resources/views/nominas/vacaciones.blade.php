<x-app-layout>
    <x-navbar></x-navbar>
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h1 class="text-xl font-semibold text-gray-800 dark:text-white">Vacaciones</h1>
                <form method="GET" action="{{ route('nominas.vacaciones') }}" class="mt-4 flex flex-col sm:flex-row sm:items-end gap-4 justify-center">
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
                        <label for="tipo" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Tipo</label>
                        <select name="tipo" id="tipo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todas</option>
                            <option value="Disfrutadas">Disfrutadas</option>
                            <option value="Pagadas">Pagadas</option>
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
                @if(isset($vacaciones) && $vacaciones->count())
                    <div class="mt-6 overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-700 text-sm rounded-lg shadow">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left">Empleado</th>
                                    <th class="px-4 py-2 text-left">Punto</th>
                                    <th class="px-4 py-2 text-left">Fecha Inicio</th>
                                    <th class="px-4 py-2 text-left">Fecha Fin</th>
                                    <th class="px-4 py-2 text-left">Tipo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vacaciones as $v)
                                    <tr class="border-t border-gray-200 dark:border-gray-600">
                                        <td class="px-4 py-2">{{ $v->user->name }}</td>
                                        <td class="px-4 py-2">{{ $v->user->punto }}</td>
                                        <td class="px-4 py-2">{{ $v->fecha_inicio }}</td>
                                        <td class="px-4 py-2">{{ $v->fecha_fin }}</td>
                                        <td class="px-4 py-2">{{ $v->tipo }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    @if(request()->all())
                        <div class="mt-6 text-center text-gray-500 dark:text-gray-300">
                            No se encontraron registros con los filtros seleccionados.
                        </div>
                    @endif
                @endif
                <center>
                    <a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 mr-2 mb-2 ml-2 mt-2">
                        Regresar
                    </a>
                </center>
            </div>
        </div>
    </div>
</x-app-layout>
