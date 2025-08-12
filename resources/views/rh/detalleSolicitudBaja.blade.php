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
    <x-navbar />
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="container mx-auto max-w-7xl">
            <!-- Datos Generales -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-transparent dark:from-gray-700 dark:to-transparent">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white">Datos Generales</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Fecha de Baja</p>
                            <p class="font-medium text-gray-900 dark:text-white mt-1">
                                {{ \Carbon\Carbon::parse($solicitud->fecha_baja)->format('d/m/Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">NSS</p>
                            <p class="font-medium text-gray-900 dark:text-white mt-1">
                                {{ $solicitudAlta->nss }}
                            </p>
                        </div>
                        <div class="flex justify-center md:justify-end">
                            <img src="{{ asset($documentacion->arch_foto) }}"
                                 alt="Foto del solicitante"
                                 class="rounded-xl w-24 h-24 object-cover border-2 border-gray-300 dark:border-gray-600 shadow-md">
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Fecha de Ingreso</p>
                            <p class="font-medium text-gray-900 dark:text-white mt-1">
                                {{ \Carbon\Carbon::parse($user->fecha_ingreso)->format('d/m/Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">¿Incapacidad?</p>
                            <p class="font-medium text-gray-900 dark:text-white mt-1">
                                {{ $solicitud->incapacidad ?? 'No especificado' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Datos de Baja -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden mt-6">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-transparent dark:from-gray-700/50 dark:to-transparent">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white">Datos de Baja</h2>
                </div>
                <div class="p-6 space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Nombre</p>
                            <p class="font-medium text-gray-900 dark:text-white mt-1">{{ $user->name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Empresa</p>
                            <p class="font-medium text-gray-900 dark:text-white mt-1">{{ $user->empresa }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Punto</p>
                            <p class="font-medium text-gray-900 dark:text-white mt-1">{{ $user->punto }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Motivo de Baja</p>
                            <p class="font-medium text-gray-900 dark:text-white mt-1">
                                {{ $solicitud->por ?? 'No especificado' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Última Asistencia</p>
                            <p class="font-medium text-gray-900 dark:text-white mt-1">
                                {{ \Carbon\Carbon::parse($solicitud->ultima_asistencia)->format('d/m/Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Estado</p>
                            <div class="mt-1">
                                @if($solicitud->estatus == 'En Proceso')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">
                                        ⚙️ {{ $solicitud->estatus }}
                                    </span>
                                @elseif($solicitud->estatus == 'Aceptada')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                        ✅ {{ $solicitud->estatus }}
                                    </span>
                                @elseif($solicitud->estatus == 'Rechazada')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
                                        ❌ {{ $solicitud->estatus }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Descuento por equipo/material no devuelto</p>
                            <p class="font-medium text-gray-900 dark:text-white mt-1">
                                {{ $solicitud->descuento ?? 'No se ha aplicado descuento' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Archivo de Baja</p>
                            @if($solicitud->archivo_baja)
                                <a href="{{ asset('storage/' . $solicitud->archivo_baja) }}" target="_blank"
                                   class="inline-flex items-center mt-1 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Ver documento
                                </a>
                            @else
                                <p class="text-sm text-red-500 mt-1">No disponible</p>
                            @endif
                        </div>

                        @if($solicitud->por == 'Renuncia')
                            <div>
                                <p class="text-gray-500 dark:text-gray-400">Archivo de Renuncia Firmada</p>
                                @if($solicitud->arch_renuncia)
                                    <a href="{{ asset('storage/' . $solicitud->arch_renuncia) }}" target="_blank"
                                       class="inline-flex items-center mt-1 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Ver documento
                                    </a>
                                @else
                                    <p class="text-sm text-red-500 mt-1">No disponible</p>
                                @endif
                            </div>
                        @endif

                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Entrega de Equipo</p>
                            @if($solicitud->arch_equipo_entregado)
                                <a href="{{ asset('storage/' . $solicitud->arch_equipo_entregado) }}" target="_blank"
                                   class="inline-flex items-center mt-1 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Ver documento
                                </a>
                            @else
                                <p class="text-sm text-red-500 mt-1">No disponible</p>
                            @endif
                        </div>

                        <div class="md:col-span-2">
                            <p class="text-gray-500 dark:text-gray-400">Motivo (opcional)</p>
                            <p class="font-medium text-gray-900 dark:text-white mt-1 whitespace-pre-line">
                                {{ $solicitud->motivo ?? 'Sin detalles adicionales' }}
                            </p>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex flex-col sm:flex-row gap-3 justify-center mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        @if(Auth::user()->rol == 'admin' || in_array(Auth::user()->rol, ['AUXILIAR NOMINAS', 'Auxiliar Nominas']) || (isset(Auth::user()->solicitudAlta) && in_array(Auth::user()->solicitudAlta->rol, ['AUXILIAR NOMINAS', 'Auxiliar Nominas'])))
                            <button onclick="mostrarFiniquito({{ $solicitud->id }})"
                                    class="px-5 py-2.5 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg transition flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Finiquito
                            </button>
                        @endif

                        @if(($solicitud->estatus == 'En Proceso' && $solicitud->por == 'Renuncia') || ($solicitud->estatus == 'En Proceso' && $solicitud->por == 'Separación Voluntaria' && Auth::user()->rol == 'admin'))
                            <a href="{{ route('rh.aceptarBaja', $solicitud->id) }}"
                               class="px-5 py-2.5 bg-green-500 hover:bg-green-600 text-white font-medium rounded-lg transition flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Aceptar
                            </a>
                            <a href="{{ route('rh.rechazarBaja', $solicitud->id) }}"
                               class="px-5 py-2.5 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg transition flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Rechazar
                            </a>
                        @endif

                        <a href="{{ route('dashboard') }}"
                           class="px-5 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-medium rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Regresar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout><script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script>
    const tieneRenuncia = {{ $solicitud->arch_renuncia ? 'true' : 'false' }};

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
                ${!tieneRenuncia ? '<p class="text-red-600 font-semibold mb-2">No se puede enviar el finiquito porque falta el archivo de renuncia firmada.</p>' : ''}
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Guardar cálculo',
        cancelButtonText: 'Cerrar',
        confirmButtonColor: tieneRenuncia ? '#3085d6' : '#aaa',
        didOpen: () => {
            if (!tieneRenuncia) {
                const confirmBtn = Swal.getConfirmButton();
                confirmBtn.disabled = true;
            }
        },
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


