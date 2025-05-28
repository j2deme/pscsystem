<x-app-layout>
    <x-navbar></x-navbar>
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="container mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h1 class="text-2xl mb-4">Nóminas</h1>
                <form method="GET" action="{{ route('nominas.calculos') }}" class="mt-4 flex flex-col sm:flex-row sm:items-end gap-4 justify-center">
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
                @if(isset($usuarios) && $usuarios->count())
                    <div class="overflow-x-auto mt-6">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No.</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nombre</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Punto</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha de ingreso</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Rol</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Detalle</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($usuarios as $user)
                                    <tr class="border-t border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-4 py-2">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-2">{{ $user->name }}</td>
                                        <td class="px-4 py-2">{{ $user->punto }}</td>
                                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($user->fecha_ingreso)->format('d/m/Y') }}</td>
                                        <td class="px-4 py-2">{{ $user->rol }}</td>
                                        <td class="px-4 py-2">
                                            <button type="button"
                                                onclick="abrirModalNomina({{ $user->id }}, '{{ $user->name }}')"
                                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                Ver
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @elseif(request()->has('punto'))
                    <div class="mt-6 text-center text-gray-600 dark:text-gray-300">
                        No se encontraron usuarios activos para el punto seleccionado.
                    </div>
                @endif

                <center>
                    <a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-6 mt-4 rounded-md hover:bg-gray-400 transition-colors">
                        Regresar
                    </a>
                </center>
            </div>
        </div>
    </div>
</x-app-layout>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function abrirModalNomina(userId, userName) {
        Swal.fire({
            title: 'Detalle de nómina de ' + userName,
            html: `
                <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
                    <div>
                        <h3 style="font-weight: bold; margin-bottom: 10px;">PERCEPCIONES</h3>
                        <table border="1" cellpadding="8" style="border-collapse: collapse;">
                            <thead style="background-color: #f0f0f0;">
                                <tr>
                                    <th>Concepto</th>
                                    <th>Dias</th>
                                    <th></th>
                                    <th>Importe</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Sueldo</td>
                                    <td>--</td>
                                    <td>$</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Premio Asistencia</td>
                                    <td></td>
                                    <td>$</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Premio de Puntualidad</td>
                                    <td></td>
                                    <td>$</td>
                                    <td></td>
                                </tr>
                                <tr><td colspan="4" style="height: 20px;"></td></tr>
                                <tr><td colspan="4" style="height: 20px;"></td></tr>
                                <tr>
                                    <td>Total Percepciones</td>
                                    <td></td>
                                    <td>$</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div>
                        <h3 style="font-weight: bold; margin-bottom: 10px;">DEDUCCIONES</h3>
                        <table border="1" cellpadding="8" style="border-collapse: collapse;">
                            <thead style="background-color: #f0f0f0;">
                                <tr>
                                    <th>Concepto</th>
                                    <th></th>
                                    <th>Importe</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>I.S.R. (mes)</td>
                                    <td>$</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Ajuste al neto</td>
                                    <td>$</td>
                                    <td></td>
                                </tr>
                                <tr><td colspan="4" style="height: 20px;"></td></tr>
                                <tr><td colspan="4" style="height: 20px;"></td></tr>
                                <tr><td colspan="4" style="height: 20px;"></td></tr>
                                <tr><td colspan="4" style="height: 20px;"></td></tr>
                                <tr>
                                    <td>Total Deducciones</td>
                                    <td>$</td>
                                    <td></td>
                                </tr>
                                <tr><td colspan="4" style="height: 20px;"></td></tr>
                                <tr>
                                    <td>Neto a pagar</td>
                                    <td>$</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            `,
            width: '80%',
            showCloseButton: true,
            confirmButtonText: 'Cerrar'
        });
    }
</script>
