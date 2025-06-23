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
                                                onclick="abrirModalNomina(
                                                    {{ $user->id }},
                                                    '{{ $user->name }}',
                                                    {{ $user->asistencias_count }},
                                                    {{ $user->descansos_count }},
                                                    {{ $user->faltas_count }},
                                                    {{ $user->solicitudAlta->sd }},
                                                    {{ $user->solicitudAlta->sdi }},
                                                    {{ $user->monto_deducciones }},
                                                    {{ $user->monto_vacaciones }},
                                                )"
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
    function calcularImss(sdi, asistencias, descansos){
        let diasTrabajados = asistencias + descansos;
        let sueldo = diasTrabajados * sdi;
        let inv = sueldo * 0.00625;
        let ces = sueldo * 0.01125;
        let mat = sdi * 0.05;

        let imss = inv+ces+mat;

        return imss.toFixed(2);
    }
    function calcularISR(sd, asistencias, descansos, faltas) {
        let diasTrabajados = asistencias + descansos;
        let sueldo = sd * diasTrabajados;

        console.log("Días trabajados:", diasTrabajados);
        console.log("Sueldo base:", sueldo.toFixed(2));

        if (faltas === 0) {
            sueldo += sueldo * 0.20;
            console.log("Sueldo con 20% extra (sin faltas):", sueldo.toFixed(2));
        }

        const tablaISR = [
            { limInf: 0.01, limSup: 368.10, cuotaFija: 0.00, porcentaje: 1.92 },
            { limInf: 368.11, limSup: 3124.35, cuotaFija: 7.05, porcentaje: 6.40 },
            { limInf: 3124.36, limSup: 5490.75, cuotaFija: 183.45, porcentaje: 10.88 },
            { limInf: 5490.76, limSup: 6382.80, cuotaFija: 441.00, porcentaje: 16.00 },
            { limInf: 6382.81, limSup: 7641.90, cuotaFija: 583.65, porcentaje: 17.92 },
            { limInf: 7641.91, limSup: 15412.80, cuotaFija: 809.25, porcentaje: 21.36 },
            { limInf: 15412.81, limSup: 24292.65, cuotaFija: 2469.15, porcentaje: 23.52 },
            { limInf: 24292.66, limSup: 46378.50, cuotaFija: 4557.75, porcentaje: 30.00 },
            { limInf: 46378.51, limSup: 61838.10, cuotaFija: 11183.40, porcentaje: 32.00 },
            { limInf: 61838.11, limSup: 185514.30, cuotaFija: 16130.55, porcentaje: 34.00 },
            { limInf: 185514.31, limSup: Infinity, cuotaFija: 58180.35, porcentaje: 35.00 },
        ];

        const rango = tablaISR.find(r => sueldo >= r.limInf && sueldo <= r.limSup);

        if (!rango) {
            console.warn("No se encontró un rango ISR para el sueldo:", sueldo.toFixed(2));
            return 0;
        }

        const excedente = sueldo - rango.limInf;
        const isr = (excedente * (rango.porcentaje / 100)) + rango.cuotaFija;

        console.log("Rango:", rango);
        console.log("Excedente:", excedente.toFixed(2));
        console.log("ISR:", isr.toFixed(2));

        return isr.toFixed(2);
    }

   function abrirModalNomina(userId, userName, asistencias, descansos, faltas, sd, sdi, deducciones, vacaciones) {
    const isr = calcularISR(sd, asistencias, descansos, faltas);
    const imss = calcularImss(sdi, asistencias, descansos);
    const sueldo = ((asistencias + descansos) * sd).toFixed(2);
    const totalPremio = ((asistencias + descansos) * sd * 0.1).toFixed(2);

    let percepcionesNum = parseFloat(
        faltas === 0
            ? (parseFloat(totalPremio) * 2 + (asistencias + descansos) * sd)
            : (asistencias + descansos) * sd
    );

    let filaVacaciones = '';
    if (vacaciones > 0) {
        percepcionesNum += vacaciones;
        filaVacaciones = `
            <tr>
                <td>Vacaciones disfrutadas</td>
                <td></td>
                <td>$</td>
                <td>${vacaciones.toFixed(2)}</td>
            </tr>
        `;
    }

    let deduccionesNum = parseFloat(isr) + parseFloat(imss);

    const netoPagarCalculado = parseFloat((percepcionesNum - deduccionesNum).toFixed(2));

    // Ajuste al neto con base en el ÚLTIMO decimal
    let ajusteAlNeto = 0;
    let ladoPercepciones = false;

    const ultimoDecimal = parseInt(netoPagarCalculado.toFixed(2).slice(-1));

    if (ultimoDecimal < 5) {
        ajusteAlNeto = parseFloat((ultimoDecimal / 100).toFixed(2));
        deduccionesNum += ajusteAlNeto;
        ladoPercepciones = false;
    } else if (ultimoDecimal > 0) {
        ajusteAlNeto = parseFloat(((10 - ultimoDecimal) / 100).toFixed(2));
        percepcionesNum += ajusteAlNeto;
        ladoPercepciones = true;
    }

    const netoPagarFinal = (percepcionesNum - deduccionesNum).toFixed(2);

    const filasPremios = (faltas === 0)
        ? `
            <tr>
                <td>Premio Asistencia</td>
                <td></td>
                <td>$</td>
                <td>${totalPremio}</td>
            </tr>
            <tr>
                <td>Premio de Puntualidad</td>
                <td></td>
                <td>$</td>
                <td>${totalPremio}</td>
            </tr>
        `
        : '';

    const filaAjuste = `
        <tr>
            <td>Ajuste al neto</td>
            ${ladoPercepciones ? '<td></td><td>$</td><td>' + ajusteAlNeto.toFixed(2) + '</td>' : '<td>$</td><td>' + ajusteAlNeto.toFixed(2) + '</td><td></td>'}
        </tr>
    `;

    let filaDeduccionesExtra = '';
    if (deducciones > 0) {
        filaDeduccionesExtra = `
            <tr>
                <td>Deducciones adicionales</td>
                <td>$</td>
                <td>${deducciones.toFixed(2)}</td>
            </tr>
        `;
        deduccionesNum += deducciones;
    }
    Swal.fire({
        title: 'Detalle de nómina de ' + userName,
        html: `
            <p><strong>Asistencias:</strong> ${asistencias + descansos}</p>
            <p><strong>Faltas:</strong> ${faltas}</p>
            <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
                <div>
                    <h3 style="font-weight: bold; margin-bottom: 10px;">PERCEPCIONES</h3>
                    <table border="1" cellpadding="8" style="border-collapse: collapse;">
                        <thead style="background-color: #f0f0f0;">
                            <tr>
                                <th>Concepto</th>
                                <th>Días</th>
                                <th></th>
                                <th>Importe</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Sueldo</td>
                                <td>${asistencias + descansos}</td>
                                <td>$</td>
                                <td>${sueldo}</td>
                            </tr>
                            ${filasPremios}
                            ${filaVacaciones}
                            ${ladoPercepciones ? filaAjuste : ''}
                            <tr><td colspan="4" style="height: 20px;"></td></tr>
                            <tr>
                                <td>Total Percepciones</td>
                                <td></td>
                                <td>$</td>
                                <td>${percepcionesNum.toFixed(2)}</td>
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
                                <td>${parseFloat(isr).toFixed(2)}</td>
                            </tr>
                            <tr>
                                <td>I.M.S.S.</td>
                                <td>$</td>
                                <td>${parseFloat(imss).toFixed(2)}</td>
                            </tr>
                            ${filaDeduccionesExtra}
                            ${!ladoPercepciones ? filaAjuste : ''}
                            <tr><td colspan="3" style="height: 20px;"></td></tr>
                            <tr>
                                <td>Total Deducciones</td>
                                <td>$</td>
                                <td>${deduccionesNum.toFixed(2)}</td>
                            </tr>
                            <tr><td colspan="3" style="height: 20px;"></td></tr>
                            <tr>
                                <td>Neto a pagar</td>
                                <td>$</td>
                                <td>${(netoPagarFinal-deducciones).toFixed(2)}</td>
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
