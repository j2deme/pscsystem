<x-app-layout>
    <x-navbar></x-navbar>

    <div class="container mx-auto px-4 py-6">
        <div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Solicitud de Alta de Usuario</h2>

            <form action="{{ route('sup.guardarArchivosEditados', $id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <h2 class="text-lg font-semibold mb-4">Documentos Obligatorios</h2>

                @php
                    $docs = [
                        'arch_acta_nacimiento' => 'Acta de Nacimiento',
                        'arch_curp' => 'CURP',
                        'arch_ine' => 'INE',
                        'arch_comprobante_domicilio' => 'Comprobante de Domicilio',
                        'arch_rfc' => 'RFC',
                        'arch_comprobante_estudios' => 'Comprobante de Estudios',
                        'arch_foto' => 'Fotografía (Reciente)',
                    ];

                    $docs_opcionales = [
                        'arch_carta_rec_laboral' => 'Carta de Recomendación Laboral',
                        'arch_carta_rec_personal' => 'Carta de Recomendación Personal',
                        'arch_cartilla_militar' => 'Cartilla Militar',
                        'arch_infonavit' => 'Comprobante INFONAVIT',
                        'arch_fonacot' => 'Comprobante FONACOT',
                        'arch_licencia_conducir' => 'Licencia de Conducir',
                        'arch_carta_no_penales' => 'Carta de Antecedentes No Penales',
                        'visa' => 'Visa',
                        'pasaporte' => 'Pasaporte',
                    ];
                @endphp

                @foreach($docs as $field => $label)
                    <div class="mb-4">
                        <label class="block font-medium">{{ $label }}</label>
                        @if ($documentacion && $documentacion->$field)
                            <div class="mb-2 text-sm text-green-600">
                                Archivo actual:
                                <a href="{{ asset($documentacion->$field) }}" target="_blank" class="underline text-blue-500">
                                    Ver {{ $label }}
                                </a>
                            </div>
                        @endif
                        <input type="file" name="{{ $field }}" class="block mt-1">
                    </div>
                @endforeach

                <h2 class="text-lg font-semibold mt-6 mb-4">Documentos Opcionales</h2>

                @foreach($docs_opcionales as $field => $label)
                    <div class="mb-4">
                        <label class="block font-medium">{{ $label }}</label>
                        @if ($documentacion && $documentacion->$field)
                            <div class="mb-2 text-sm text-green-600">
                                Archivo actual:
                                <a href="{{ asset($documentacion->$field) }}" target="_blank" class="underline text-blue-500">
                                    Ver {{ $label }}
                                </a>
                            </div>
                        @endif
                        <input type="file" name="{{ $field }}" class="block mt-1">
                    </div>
                @endforeach

                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                    Guardar Cambios
                </button>

                <a href="{{ route('sup.solicitud.detalle', $id) }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 mr-2">
                    Regresar
                </a>
            </form>
        </div>
    </div>
</x-app-layout>
