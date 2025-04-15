<x-app-layout>
    <x-navbar></x-navbar>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8 space-y-8">

        <div class="bg-white shadow rounded-lg p-6">
            @if(session('success'))
                <div class="bg-green-100 border-t-4 border-green-500 rounded-b text-green-900 px-4 py-3 shadow-md" role="alert">
                    <div class="flex">
                        <div>
                            <p class="text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif
            <p class="text-gray-900 text-2xl dark:text-gray-100 text-2xl">
                Información del Solicitante
            </p><br>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div><strong>Nombre:</strong> {{ $solicitud->nombre }} {{ $solicitud->apellido_paterno }} {{ $solicitud->apellido_materno }}</div>
                <div><strong>CURP:</strong> {{ $solicitud->curp }}</div>
                <div><strong>NSS:</strong> {{ $solicitud->nss }}</div>
                <div><strong>RFC:</strong> {{ $solicitud->rfc }}</div>
                <div><strong>Email:</strong> {{ $solicitud->email }}</div>
                <div><strong>Teléfono:</strong> {{ $solicitud->telefono }}</div>
                <div><strong>Dirección:</strong> {{ $solicitud->domicilio_calle }} #{{ $solicitud->domicilio_numero }}, {{ $solicitud->domicilio_colonia }}, {{ $solicitud->domicilio_ciudad }}, {{ $solicitud->domicilio_estado }}</div>
                <div><strong>Estado Civil:</strong> {{ $solicitud->estado_civil }}</div>
                <div><strong>Rol:</strong> {{ $solicitud->rol }}</div>
                <div><strong>Empresa:</strong> {{ $solicitud->empresa }}</div>
                <div><strong>Punto:</strong> {{ $solicitud->punto }}</div>
                <div><strong>Fecha de Nacimiento:</strong> {{ $solicitud->fecha_nacimiento }}</div>
                <div><strong>Estado de la solicitud:</strong>
                    @if($solicitud->status == 'En Proceso')
                        <span class="inline-flex items-center justify-center px-2 py-1 text-sm leading-none text-gray-900 bg-yellow-300 rounded-full">
                            {{ $solicitud->status }}
                        </span>
                    @elseif($solicitud->status == 'Aceptada')
                        <span class="inline-flex items-center justify-center px-2 py-1 text-sm leading-none text-gray-800 bg-green-300 rounded-full">
                            {{ $solicitud->status }}
                        </span>
                    @elseif($solicitud->status == 'Rechazada')
                        <span class="inline-flex items-center justify-center px-2 py-1 text-sm leading-none text-gray-100 bg-red-300 rounded-full">
                            {{ $solicitud->status }}
                        </span>
                    @endif
                </div>
                <div><strong>Observaciones:</strong>
                    @if($solicitud->status == 'En Proceso')
                        <span class="inline-flex items-center justify-center px-2 py-1 text-sm leading-none text-gray-900 bg-yellow-300 rounded-full">
                            {{ $solicitud->observaciones }}
                        </span>
                    @elseif($solicitud->status == 'Aceptada')
                        <span class="inline-flex items-center justify-center px-2 py-1 text-sm leading-none text-gray-800 bg-green-300 rounded-full">
                            {{ $solicitud->observaciones }}
                            </span>
                    @elseif($solicitud->status == 'Rechazada')
                        <span class="inline-flex items-center justify-center px-2 py-1 text-sm leading-none text-gray-100 bg-red-300 rounded-full">
                            {{ $solicitud->observaciones }}
                        </span>
                    @endif
                </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Documentación</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                @foreach([
                    'arch_acta_nacimiento' => 'Acta de Nacimiento',
                    'arch_curp' => 'CURP',
                    'arch_ine' => 'INE',
                    'arch_comprobante_domicilio' => 'Comprobante de Domicilio',
                    'arch_rfc' => 'RFC',
                    'arch_comprobante_estudios' => 'Comprobante de Estudios',
                    'arch_carta_rec_laboral' => 'Carta Recomendación Laboral',
                    'arch_carta_rec_personal' => 'Carta Recomendación Personal',
                    'arch_cartilla_militar' => 'Cartilla Militar',
                    'arch_infonavit' => 'Infonavit',
                    'arch_fonacot' => 'Fonacot',
                    'arch_licencia_conducir' => 'Licencia de Conducir',
                    'arch_carta_no_penales' => 'Carta Antecedentes no Penales',
                    'arch_foto' => 'Fotografía',
                    'visa' => 'Visa',
                    'pasaporte' => 'Pasaporte'
                ] as $campo => $label)
                    @if($documentacion->$campo)
                        <div class="flex items-center justify-between border rounded p-2 bg-gray-50">
                            <span>{{ $label }}</span>
                            <a href="{{ asset($documentacion->$campo) }}" target="_blank" class="text-blue-600 hover:underline text-sm">
                                Ver Archivo
                            </a>
                        </div>
                    @else
                        <div class="flex items-center justify-between border rounded p-2 bg-red-50 text-red-700">
                            <span>{{ $label }}</span>
                            <span class="text-xs">No disponible</span>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <div>
            <center><br>
                @if($solicitud->status == 'En Proceso')
                <a href="{{route('sup.editarSolicitudForm', $solicitud->id)}}" class="inline-block bg-green-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 mr-2">
                    Editar
                </a>
                @else
                @endif
                <a href="{{ route('sup.historial') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 mr-2">
                    Regresar
                </a>
            </center>
        </div>

    </div>
</x-app-layout>
