<style>
    [x-cloak] { display: none !important; }
</style>

<x-app-layout>
    <x-navbar></x-navbar>
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                @if(session('success'))
                    <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="space-y-4">
                    <p class="text-gray-900 text-2xl dark:text-gray-100 text-2xl">
                        Información del Solicitante
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div><strong>Nombre:</strong> {{ $solicitud->nombre }} {{ $solicitud->apellido_paterno }} {{ $solicitud->apellido_materno }}</div>
                        <div><strong>CURP:</strong> {{ $solicitud->curp }}</div>
                        <div><strong>NSS:</strong> {{ $solicitud->nss }}</div>
                        <div><strong>RFC:</strong> {{ $solicitud->rfc }}</div>
                        <div><strong>Email:</strong> {{ $solicitud->email }}</div>
                        <div><strong>Teléfono:</strong> {{ $solicitud->telefono }}</div>
                        <div><strong>Dirección:</strong> {{ $solicitud->domicilio_calle }} #{{ $solicitud->domicilio_numero }}, {{ $solicitud->domicilio_colonia }}, {{ $solicitud->domicilio_ciudad }}, {{ $solicitud->domicilio_estado }}</div>
                        <div><strong>Estado Civil:</strong> {{ $solicitud->estado_civil }}</div>
                        <div><strong>Puesto Solicitado:</strong> {{ $solicitud->rol }}</div>
                        <div><strong>Empresa:</strong> {{ $solicitud->empresa }}</div>
                        <div><strong>Punto:</strong> {{ $solicitud->punto }}</div>
                        <div><strong>Fecha de Nacimiento:</strong> {{ $solicitud->fecha_nacimiento }}</div>
                        <div><strong>Estado de la Solicitud:</strong>
                            @if($solicitud->status == 'En Proceso')
                                <span class="inline-flex items-center justify-center px-2 py-1 text-sm leading-none text-gray-800 bg-yellow-300 rounded-full">
                                    {{ $solicitud->status }}
                                </span>
                            @elseif($solicitud->status == 'Aceptada')
                                <span class="inline-flex items-center justify-center px-2 py-1 text-sm leading-none text-gray-800 bg-green-300 rounded-full">
                                    {{ $solicitud->status }}
                                </span>
                            @else
                                <span class="inline-flex items-center justify-center px-2 py-1 text-sm leading-none text-gray-800 bg-gray-300 rounded-full">
                                    {{ $solicitud->status }}
                                </span>
                            @endif
                        </div>
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
            <div class="flex flex-wrap justify-center gap-4 mt-4">
                <a href="{{ route('rh.aceptarSolicitud', $solicitud->id) }}"
                    class="inline-block bg-green-300 text-gray-800 py-2 px-4 rounded-md hover:bg-green-400 transition">
                    Aceptar
                </a>

                <a href="#"
                    class="inline-block bg-red-300 text-gray-800 py-2 px-4 rounded-md hover:bg-red-400 transition">
                    Rechazar
                </a>

                <div x-data="{ open: false }" class="relative">
                    <a href="#" role="button" @click.prevent="open = true"
                        class="inline-block bg-yellow-300 text-gray-800 py-2 px-4 rounded-md hover:bg-yellow-400 transition">
                        Observaciones
                    </a>

                    <div x-show="open" x-cloak
                            class="absolute z-10 mt-2 w-80 bg-white border border-gray-300 rounded p-4 shadow">
                        <form action="{{ route('rh.observacion_solicitud', $solicitud->id) }}" method="POST">
                            @csrf

                            <label for="observacion" class="block text-sm font-medium text-gray-700 mb-1">
                                Escribe una observación:
                            </label>
                            <textarea name="observacion" id="observacion" rows="4" class="w-full border border-gray-300 rounded p-2 mb-3 focus:ring focus:ring-blue-200"required></textarea>

                            <div class="flex justify-end gap-2">
                                <button type="submit"
                                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                    Enviar
                                </button>
                                <a href="#" @click.prevent="open = false"
                                    class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
                                    Cancelar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 transition">
                    Regresar
                </a>
            </div>
        </div>
    </div>
</x-app-layout>

