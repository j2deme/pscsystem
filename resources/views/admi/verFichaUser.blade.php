@php
    use Carbon\Carbon;
@endphp
<x-app-layout>
    <x-navbar></x-navbar>
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                @if(session('success'))
                    <div class="bg-green-100 border-t-4 border-green-500 rounded-b text-green-900 px-4 py-3 shadow-md" role="alert">
                        <div class="flex">
                            <div>
                                <p class="text-sm">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                    @else
                @endif
                <div class="space-y-4 rounded dark:bg-white">
                    <p class="text-gray-900 text-2xl dark:text-gray-900 text-2xl">
                        Información del Usuario
                    </p>

                    @if (!$solicitud)
                        <p class="text-red-600 text-sm">No hay información de solicitud disponible para este usuario.</p>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><strong>Nombre:</strong> {{ $solicitud?->nombre }} {{ $solicitud?->apellido_paterno }} {{ $solicitud?->apellido_materno }}</div>
                            <div><strong>CURP:</strong> {{ $solicitud?->curp }}</div>
                            <div><strong>NSS:</strong> {{ $solicitud?->nss }}</div>
                            <div><strong>RFC:</strong> {{ $solicitud?->rfc }}</div>
                            <div><strong>Email:</strong> {{ $solicitud?->email }}</div>
                            <div><strong>Teléfono:</strong> {{ $solicitud?->telefono }}</div>
                            <div><strong>Domicilio (Comprobante):</strong>{{$solicitud?->domicilio_comprobante }}</div>
                            <div><strong>Dirección Fiscal:</strong>
                                {{ $solicitud?->domicilio_calle }}
                                #{{ $solicitud?->domicilio_numero }},
                                {{ $solicitud?->domicilio_colonia }},
                                {{ $solicitud?->domicilio_ciudad }},
                                {{ $solicitud?->domicilio_estado }}
                            </div>
                            <div><strong>Estado Civil:</strong> {{ $solicitud?->estado_civil }}</div>
                            <div><strong>Puesto:</strong> {{ $solicitud?->rol }}</div>
                            <div><strong>Sueldo:</strong> {{ $solicitud?->sueldo_mensual }}</div>
                            <div><strong>Empresa:</strong> {{ $solicitud?->empresa }}</div>
                            <div><strong>Punto:</strong> {{ $solicitud?->punto }}</div>
                            <div><strong>Fecha de Ingreso:</strong> {{ Carbon::parse($solicitud?->fecha_ingreso)->format('d/m/Y') }}</div>
                            <div><strong>Fecha de Nacimiento:</strong> {{ Carbon::parse($solicitud?->fecha_nacimiento)->format('d/m/Y') }}</div>
                            <div><strong>Estatus:</strong>
                                @if($user->estatus == 'Reingreso')
                                    <span class="inline-flex items-center px-2 py-1 text-sm text-gray-800 bg-yellow-300 rounded-full">
                                        {{ $user->estatus }}
                                    </span>
                                @elseif($user->estatus == 'Activo')
                                    <span class="inline-flex items-center px-2 py-1 text-sm text-gray-800 bg-green-300 rounded-full">
                                        {{ $user->estatus }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 text-sm text-gray-800 bg-red-300 rounded-full">
                                        {{ $user->estatus }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-col items-center justify-start text-center space-y-2">
                            @if ($documentacion?->arch_foto)
                                <p class="font-semibold">Foto del solicitante:</p>
                                <img src="{{ asset($documentacion->arch_foto) }}" alt="Foto del usuario" class="w-40 h-40 object-cover rounded-full shadow">
                                <a href="{{ asset($documentacion->arch_foto) }}" target="_blank" class="text-blue-500 underline text-sm">Ver completa</a>
                            @else
                                <p class="text-sm text-gray-500">No hay foto cargada.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-6 mt-4">
                    <h3 class="text-lg font-semibold mb-4">Documentación</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                        @foreach([
                            'arch_acta_nacimiento' => 'Acta de Nacimiento',
                            'arch_curp' => 'CURP',
                            'arch_ine' => 'INE',
                            'arch_comprobante_domicilio' => 'Comprobante de Domicilio',
                            'arch_rfc' => 'RFC',
                            'arch_comprobante_estudios' => 'Comprobante de Estudios',
                            'arch_nss' => 'NSS',
                            'arch_contrato' => 'Contrato',
                            'arch_carta_rec_laboral' => 'Carta Recomendación Laboral',
                            'arch_carta_rec_personal' => 'Carta Recomendación Personal',
                            'arch_cartilla_militar' => 'Cartilla Militar',
                            'arch_infonavit' => 'Infonavit',
                            'arch_fonacot' => 'Fonacot',
                            'arch_licencia_conducir' => 'Licencia de Conducir',
                            'arch_carta_no_penales' => 'Carta No Penales',
                            'arch_foto' => 'Fotografía',
                            'visa' => 'Visa',
                            'pasaporte' => 'Pasaporte'
                        ] as $campo => $label)
                            @if($documentacion?->$campo)
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

                <div class="flex flex-wrap justify-center gap-4 mt-4">
                    @if($user->estatus != 'Inactivo')
                        @if(Auth::user()->rol == 'admin' || Auth::user()->solicitudAlta->departamento == 'Recursos Humanos' || Auth::user()->solicitudAlta->rol == 'AUXILIAR RECURSOS HUMANOS' || Auth::user()->solicitudAlta->rol == 'AUXILIAR RH' || Auth::user()->solicitudAlta->rol == 'AUX RH' || Auth::user()->solicitudAlta->rol == 'Auxiliar RH' || Auth::user()->solicitudAlta->rol == 'Auxiliar Recursos Humanos' || Auth::user()->solicitudAlta->rol == 'Aux RH' || Auth::user()->rol == 'AUXILIAR RECURSOS HUMANOS' || Auth::user()->rol == 'Auxiliar recursos humanos')
                            <a href="{{ route('admin.editarUsuarioForm', $user->id) }}"
                                class="inline-block bg-green-300 text-gray-800 py-2 px-4 rounded-md hover:bg-green-400 transition">
                                Editar
                            </a>

                            <a href="{{ route('rh.descargarFicha', $user->id) }}"
                                class="inline-block bg-blue-300 text-gray-800 py-2 px-4 rounded-md hover:bg-blue-400 transition">
                                Descargar Ficha
                            </a>

                            @if (Auth::user()->rol == 'admin' )
                                <a href="#" class="inline-block bg-red-300 text-gray-800 py-2 px-4 rounded-md hover:bg-red-400 transition"
                                onclick="confirmarBaja({{ $user->id }})">
                                    Dar de Baja
                                </a>
                            @endif
                        @endif
                    @elseif($user->estatus == 'Inactivo')
                        <a href="#" class="inline-block bg-green-300 text-gray-800 py-2 px-4 rounded-md hover:bg-green-400 transition"
                            onclick="confirmarReingreso({{ $user->id }})">
                            Reingreso
                        </a>
                    @endif
                    @if (Auth::user()->rol == 'admin')
                        <a href="{{ route('admin.verUsuarios') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 transition">
                            Regresar
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 transition">
                            Regresar
                        </a>
                    @endif
                </div>
        </div>
    </div>
</x-app-layout>

<script>
    function confirmarBaja(userId) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esto cambiará el estatus del usuario a 'Inactivo'",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, dar de baja',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `/admin/baja_usuario/${userId}`;
            }
        });
    }
    function confirmarReingreso(userId) {
        Swal.fire({
            title: '¿Confirmas generar el reingreso?',
            text: "Esto añadirá un nuevo reingreso para el usuario.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, generar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `/reingreso/${userId}`;
            }
        });
    }
</script>

