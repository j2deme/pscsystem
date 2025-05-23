@php
    $factorVacaciones = $diasDisponibles / 365 ;
    $diasVacaciones = $diasTrabajadosAnio * $factorVacaciones;
    $montoVacaciones = $diasVacaciones * $solicitudAlta->sd;
    $primaVacacional = $montoVacaciones * 0.25;
    $diasAguinaldo = (15/365) * $diasTrabajadosAnio;
    $montoAguinaldo = $diasAguinaldo * $solicitudAlta->sd;
    $primaAguinaldo = $montoAguinaldo * 0.25;

@endphp
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
                        {{ \Carbon\Carbon::now()->format('d-m-Y') }}
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
                    <p class="font-medium text-blue-500 dark:text-white">
                        <a href="{{ asset('storage/' . $solicitud->archivo_baja) }}" target="_blank">
                            Ver documento
                        </a>
                    </p>
                </div>
                <div>
                    <p class="text-gray-500">Archivo de Entrega de Equipo:</p>
                    <p class="font-medium text-blue-500 dark:text-white">
                        <a href="{{ asset('storage/' . $solicitud->arch_equipo_entregado) }}" target="_blank">
                            Ver documento
                        </a>
                    </p>
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
                <a href="javascript:void(0);" onclick="mostrarFiniquito()" class="inline-block bg-red-300 text-gray-800 py-2 px-6 rounded-md hover:bg-red-400 transition-colors">
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
<script>
function mostrarFiniquito() {
    Swal.fire({
        title: 'Resumen de Finiquito',
        html: `
        <div class="text-left text-sm font-mono leading-5">
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
                    <tr><td>Días trabajados</td><td>-</td><td>-</td><td>-</td><td>{{ number_format($user->solicitudAlta->sd, 2) }}</td><td>-</td></tr>
                    <tr><td>Extras</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>
                    <tr><td>Festivo</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>
                    <tr><td>Vacaciones 2025-2026</td><td>{{ number_format($factorVacaciones, 10) }}</td><td>{{$diasTrabajadosAnio}}</td><td>{{ number_format($diasVacaciones, 2)}}</td><td>{{ number_format($user->solicitudAlta->sd, 2) }}</td><td>{{ number_format($montoVacaciones, 2) }}</td></tr>
                    <tr><td>Prima vacacional 2025-2026</td><td>-</td><td>-</td><td>-</td><td>25%</td><td>{{ number_format($primaVacacional, 2)}}</td><td>   </td></tr>
                    <tr><td>Aguinaldo 2025</td><td>0.04109589</td><td>{{$diasTrabajadosAnio}}</td><td>{{ number_format($diasAguinaldo, 2)}}</td><td>{{ number_format($user->solicitudAlta->sd, 2) }}</td><td>{{ number_format($montoAguinaldo, 2) }}</td></tr>
                    <tr><td colspan="5"><strong>SUBTOTAL</strong></td><td><strong>{{ number_format($montoVacaciones + $montoAguinaldo + $primaVacacional, 2) }}</strong></td></tr>
                    <tr><td>Días pagados no laborados</td><td>-</td><td>-</td><td>0.00</td><td>{{ number_format($user->solicitudAlta->sd, 2) }}</td><td>-</td></tr>
                    <tr><td>Deducción general</td><td colspan="4">-</td><td>-</td></tr>
                    <tr><td>Anticipo</td><td colspan="4">-</td><td>-</td></tr>
                    <tr><td colspan="5"><strong>TOTAL</strong></td><td><strong>{{ number_format($montoVacaciones + $montoAguinaldo + $primaVacacional, 2) }}</strong></td></tr>
                </tbody>
            </table>
            <p>Nota: Favor de CORROBORAR la cantidad generada antes de continuar con el proceso de finiquito.</p>
        </div>
        `,
        width: '60%',
        confirmButtonText: 'Cerrar'
    });
}
</script>
