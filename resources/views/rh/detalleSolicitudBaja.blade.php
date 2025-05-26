@php
    use Carbon\Carbon;

    $hoy = Carbon::now('America/Mexico_City');
    $inicioAnio = now()->startOfYear();

    $fechaIngreso = Carbon::parse($user->fecha_ingreso);
    $ultimaAsistencia = Carbon::parse($solicitud->ultima_asistencia);
    $fechaBaja = Carbon::parse($solicitud->fecha_baja ?? now());

    $inicioMes = $fechaBaja->copy()->startOfMonth();
    $quincena = $fechaBaja->copy()->day(15);

    if ($fechaBaja->lessThan($quincena)) {
        $diasQuincena = $inicioMes->diffInDays($fechaBaja);
    } else {
        $diasQuincena = $quincena->diffInDays($fechaBaja);
    }

    $diasTrabajadosAnio = $fechaIngreso->diffInDays($fechaBaja)+1;//-1
    $diasNoLaborados = $ultimaAsistencia->diffInDays($fechaBaja);
    $diasNoPagados = $diasQuincena * $solicitudAlta->sd;

    $descuentoNoLaborados = $diasNoLaborados * $solicitudAlta->sd;

    $factorVacaciones = $diasDisponibles / 365 ;
    $diasVacaciones = $diasTrabajadosAnio * $factorVacaciones;
    $montoVacaciones = $diasVacaciones * $solicitudAlta->sd;
    $primaVacacional = $montoVacaciones * 0.25;

    $factorAguinaldo = (15 / 365);

    $descuentoNoEntregados = $solicitud->descuento;

    if ($fechaIngreso->greaterThanOrEqualTo($inicioAnio)) {
        $diasTrabajAnio = $fechaIngreso->diffInDays($ultimaAsistencia)+1;
    } else {
        $diasTrabajAnio = $inicioAnio->diffInDays($ultimaAsistencia) +1 ;
    }
    $diasAguinaldo = $diasTrabajAnio * $factorAguinaldo;
    $montoAguinaldo = $diasAguinaldo * $solicitudAlta->sd;
    $primaAguinaldo = $montoAguinaldo * 0.25;
@endphp
<style>
    .swal2-popup.custom-modal-width {
        width: 900px !important;
        max-width: 95vw;
    }
    #finiquitoContenido table {
    border-collapse: collapse;
    width: 100%;
    }

#finiquitoContenido th,
    #finiquitoContenido td {
    padding: 6px 8px;
    text-align: left;
    vertical-align: middle;
    margin: 0;
    line-height: 1.2;
    border: 1px solid #ccc;
    font-family: monospace, monospace;
    }

#finiquitoContenido thead th {
    background-color: #f2f2f2;
    vertical-align: middle;
    }

#finiquitoContenido table th:nth-child(1),
    #finiquitoContenido table td:nth-child(1) {
    min-width: 150px;
    }

#finiquitoContenido table th:nth-child(2),
#finiquitoContenido table td:nth-child(2) {
  min-width: 130px;
}
#finiquitoContenido table th:nth-child(3),
#finiquitoContenido table td:nth-child(3) {
  min-width: 130px;
}

#finiquitoContenido table th:nth-child(4),
#finiquitoContenido table td:nth-child(4) {
  min-width: 100px;
}

#finiquitoContenido table th:nth-child(5),
#finiquitoContenido table td:nth-child(5) {
  min-width: 100px;
}
#finiquitoContenido table th:nth-child(6),
#finiquitoContenido table td:nth-child(6) {
  min-width: 80px;
}

</style>
<x-app-layout>
    <x-navbar></x-navbar>
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="container mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <h2 class="text-xl font-bold text-gray-700 dark:text-gray-200 mb-4 border-b pb-2">Datos Generales</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                <div>
                    <p class="text-gray-500">Fecha</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                        {{ \Carbon\Carbon::parse($solicitud->fecha_baja)->format('d-m-Y') }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-500">NSS</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                        {{ $solicitudAlta->nss }}
                    </p>
                </div>
                <div rowspan="3" class="flex justify-center items-center">
                    <img src="{{ asset($documentacion->arch_foto) }}" alt="Foto del solicitante" class="rounded-xl w-24 h-24 object-cover border-2 border-gray-300 shadow">
                </div>
                <div>
                    <p class="text-gray-500">Fecha de Ingreso</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                        {{ \Carbon\Carbon::parse($user->fecha_ingreso)->format('d-m-Y') }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-500">¿Incapacidad?</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                        {{ $solicitud->incapacidad ?? 'No especificado' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow mt-2">
            <h2 class="text-xl font-bold text-gray-700 dark:text-gray-200 mb-4 border-b pb-2">Datos de Baja</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                <div>
                    <p class="text-gray-500">Nombre</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $user->name }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Empresa</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $user->empresa }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Punto</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $user->punto }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Por</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                        {{ $solicitud->por ?? 'No especificado' }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-500">Última Asistencia</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                        {{ \Carbon\Carbon::parse($solicitud->ultima_asistencia)->format('d-m-Y') ?? 'No especificado' }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-500">Estado de la Solicitud</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                        @if($solicitud->estatus == 'En Proceso')
                            <span class="inline-flex items-center justify-center px-2 py-1 mr-2 text-xs leading-none text-gray-800 bg-yellow-300 rounded-full">
                                {{ $solicitud->estatus }}
                            </span>
                        @elseif($solicitud->estatus == 'Aceptada')
                            <span class="inline-flex items-center justify-center px-2 py-1 mr-2 text-xs leading-none text-green-100 bg-green-600 rounded-full">
                                {{ $solicitud->estatus }}
                            </span>
                        @elseif($solicitud->estatus == 'Rechazada')
                            <span class="inline-flex items-center justify-center px-2 py-1 mr-2 text-xs leading-none text-red-100 bg-red-600 rounded-full">
                                {{ $solicitud->estatus }}
                            </span>
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-gray-500">Descuento de no devolución de equipo/material</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                        {{ $solicitud->descuento ?? 'No se ha aplicado descuento' }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-500">Archivo de Baja</p>
                    @if($solicitud->archivo_baja)
                        <p class="font-medium text-blue-500 dark:text-white">
                            <a href="{{ asset('storage/' . $solicitud->archivo_baja) }}" target="_blank">
                                Ver documento
                            </a>
                        </p>
                    @else
                        <p class="text-sm text-red-500">No disponible</p>
                    @endif
                </div>

                @if($solicitud->por == 'Renuncia')
                    <div>
                        <p class="text-gray-500">Archivo de Renuncia</p>
                        @if($solicitud->arch_renuncia)
                            <p class="font-medium text-blue-500 dark:text-white">
                                <a href="{{ asset('storage/' . $solicitud->arch_renuncia) }}" target="_blank">
                                    Ver documento
                                </a>
                            </p>
                        @else
                            <p class="text-sm text-red-500">No disponible</p>
                        @endif
                    </div>
                @endif

                <div>
                    <p class="text-gray-500">Archivo de Entrega de Equipo:</p>
                    @if($solicitud->arch_equipo_entregado)
                        <p class="font-medium text-blue-500 dark:text-white">
                            <a href="{{ asset('storage/' . $solicitud->arch_equipo_entregado) }}" target="_blank">
                                Ver documento
                            </a>
                        </p>
                    @else
                        <p class="text-sm text-red-500">No disponible</p>
                    @endif
                </div>

                <div class="md:col-span-2">
                    <p class="text-gray-500">Motivo</p>
                    <p class="font-medium text-gray-900 dark:text-white whitespace-pre-line">
                        {{ $solicitud->motivo ?? 'Sin detalles adicionales' }}
                    </p>
                </div>

            </div>
            <div class="text-center mt-6">
            @if(Auth::user()->rol == 'admin' || Auth::user()->rol == 'AUXILIAR NOMINAS' || Auth::user()->solicitudAlta->rol == 'AUXILIAR NOMINAS' || Auth::user()->solicitudAlta->rol == 'Auxiliar Nominas' || Auth::user()->rol == 'Auxiliar Nominas')
                <a href="javascript:void(0);" onclick="mostrarFiniquito({{ $solicitud->id }})"
                    class="inline-block bg-red-300 text-gray-800 py-2 px-6 rounded-md hover:bg-red-400 transition-colors">
                    Finiquito
                </a>
            @endif
            @if(($solicitud->estatus == 'En Proceso' && $solicitud->por == 'Renuncia') || ($solicitud->estatus == 'En Proceso' && $solicitud->por == 'Separación Voluntaria' && Auth::user()->rol == 'admin'))
                <a href="{{route('rh.aceptarBaja', $solicitud->id)}}" class="inline-block bg-green-300 text-gray-800 py-2 px-6 rounded-md hover:bg-green-400 transition-colors">
                    Aceptar
                </a>
                <a href="{{route('rh.rechazarBaja', $solicitud->id)}}" class="inline-block bg-red-300 text-gray-800 py-2 px-6 rounded-md hover:bg-red-400 transition-colors">
                    Rechazar
                </a>
                <a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-6 rounded-md hover:bg-gray-400 transition-colors">
                    Regresar
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-6 rounded-md hover:bg-gray-400 transition-colors">
                    Regresar
                </a>
            @endif
        </div>
        </div>

    </div>
    </div>

</x-app-layout>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script>
function mostrarFiniquito(solicitudId) {
    Swal.fire({
        title: 'Resumen de Finiquito',
        customClass: {
            popup: 'custom-modal-width'
        },
        html: `
            <div id="finiquitoContenido" class="text-left text-sm font-mono leading-5" style="width: 750px !important; max-width: 95vw; min-height: 300px; overflow: visible;">
                <p><strong>Nombre:</strong> {{ $user->name }}</p>
                <p><strong>FECHA DE INGRESO:</strong> {{ \Carbon\Carbon::parse($user->fecha_ingreso)->format('d-m-Y') }}</p>
                <p><strong>FECHA DE BAJA:</strong> {{ \Carbon\Carbon::parse($solicitud->fecha_baja)->format('d-m-Y') }}</p>
                <p><strong>SALARIO DIARIO:</strong> ${{ number_format($user->solicitudAlta->sd, 2) }}</p>
                <br>
                <table style="width:100%; border-collapse: collapse;" border="1">
                    <thead style="background-color: #f2f2f2;">
                        <tr>
                            <th>Concepto</th>
                            <th>Factor</th>
                            <th>Días Trab.</th>
                            <th>Días</th>
                            <th>Salario</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>Días trabajados</td><td>-</td><td>-</td><td>{{ number_format($diasQuincena) }}</td><td>{{ number_format($user->solicitudAlta->sd, 2) }}</td><td>{{number_format($diasNoPagados, 2)}}</td></tr>
                        <tr><td>Extras</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>
                        <tr><td>Festivo</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>
                        <tr><td>Vacaciones 2025-2026</td><td>{{ number_format($factorVacaciones, 9) }}</td><td>{{$diasTrabajadosAnio}}</td><td>{{ number_format($diasVacaciones, 2)}}</td><td>{{ number_format($user->solicitudAlta->sd, 2) }}</td><td>{{ number_format($montoVacaciones, 2) }}</td></tr>
                        <tr><td>Prima vacacional 2025-2026</td><td>-</td><td>-</td><td>-</td><td>25%</td><td>{{ number_format($primaVacacional, 2)}}</td></tr>
                        <tr><td>Aguinaldo 2025</td><td>{{ number_format($factorAguinaldo, 8) }}</td><td>{{$diasTrabajAnio}}</td><td>{{ number_format($diasAguinaldo, 2)}}</td><td>{{ number_format($user->solicitudAlta->sd, 2) }}</td><td>{{ number_format($montoAguinaldo, 2) }}</td></tr>
                        <tr><td colspan="5"><strong>SUBTOTAL</strong></td><td><strong>{{ number_format($diasNoPagados + $montoVacaciones + $montoAguinaldo + $primaVacacional, 2) }}</strong></td></tr>
                        <tr><td>Días pagados no laborados</td><td>-</td><td>-</td><td>{{ number_format($diasNoLaborados, 2) }}</td><td>{{ number_format($user->solicitudAlta->sd, 2) }}</td><td>{{ number_format($descuentoNoLaborados, 2) }}</td></tr>
                        <tr><td>Deducción general</td><td colspan="4">-</td><td>{{ number_format($descuentoNoEntregados, 2) }}</td></tr>
                        <tr><td>Adelanto de Nómina</td><td colspan="4">-</td><td>-</td></tr>
                        <tr><td colspan="5"><strong>TOTAL</strong></td><td><strong>{{ number_format($diasNoPagados + $montoVacaciones + $montoAguinaldo + $primaVacacional - $descuentoNoLaborados - $descuentoNoEntregados, 2) }}</strong></td></tr>
                    </tbody>
                </table>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Guardar cálculo',
        cancelButtonText: 'Cerrar',
        preConfirm: () => {
    return new Promise((resolve) => {
        const contenido = document.getElementById('finiquitoContenido');
        contenido.style.overflow = 'visible';
        contenido.style.maxHeight = 'none';
        setTimeout(() => {
            html2canvas(contenido, {
                scrollY: -window.scrollY,
                scale: 2,
                useCORS: true,
                backgroundColor: null
            }).then(canvas => {
                const imagenBase64 = canvas.toDataURL("image/png");

                fetch("{{ route('guardar.calculo.finiquito') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        solicitud_id: solicitudId,
                        imagen: imagenBase64
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Guardado', 'El cálculo de finiquito se guardó correctamente.', 'success');
                    } else {
                        Swal.fire('Error', data.error || 'Hubo un error al guardar.', 'error');
                    }
                    resolve();
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Error al procesar la solicitud.', 'error');
                    resolve();
                });
            });
        }, 1000);
    });
}

    });
}
</script>


