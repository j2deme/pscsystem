@php
    // Ejemplo: define el tipo. En tu controlador puedes pasar esta variable.
    $tipo = $tipo ?? 'noarmado'; // o 'armado', etc.

    $documentosObligatorios = [];

    if ($tipo === 'armado') {
        $documentosObligatorios = [
            ['label' => 'Solicitud/CV', 'name' => 'arch_solicitud_empleo'],
            ['label' => 'INE', 'name' => 'arch_ine'],
            ['label' => 'NSS', 'name' => 'arch_nss'],
            ['label' => 'CURP', 'name' => 'arch_curp'],
            ['label' => 'RFC', 'name' => 'arch_rfc'],
            ['label' => 'Acta de Nacimiento', 'name' => 'arch_acta_nacimiento'],
            ['label' => 'Comprobante de Estudios', 'name' => 'arch_comprobante_estudios'],
            ['label' => 'Comprobante de Domicilio', 'name' => 'arch_comprobante_domicilio'],
            ['label' => 'Carta de Recomendación Laboral', 'name' => 'arch_carta_rec_laboral'],
            ['label' => 'Carta de Recomendación Personal', 'name' => 'arch_carta_rec_personal'],
            ['label' => 'Cartilla Militar', 'name' => 'arch_cartilla_militar'],
            ['label' => 'Antidoping', 'name' => 'arch_antidoping'],
            ['label' => 'Carta de No Antecedentes Penales', 'name' => 'arch_carta_no_penales'],
            ['label' => 'Contrato', 'name' => 'arch_contrato'],
            ['label' => 'Fotografía (Reciente)', 'name' => 'arch_foto'],
        ];
    } else {
        $documentosObligatorios = [
            ['label' => 'Solicitud/CV', 'name' => 'arch_solicitud_empleo'],
            ['label' => 'INE', 'name' => 'arch_ine'],
            ['label' => 'NSS', 'name' => 'arch_nss'],
            ['label' => 'CURP', 'name' => 'arch_curp'],
            ['label' => 'RFC', 'name' => 'arch_rfc'],
            ['label' => 'Acta de Nacimiento', 'name' => 'arch_acta_nacimiento'],
            ['label' => 'Comprobante de Estudios', 'name' => 'arch_comprobante_estudios'],
            ['label' => 'Comprobante de Domicilio', 'name' => 'arch_comprobante_domicilio'],
            ['label' => 'Carta de Recomendación Laboral', 'name' => 'arch_carta_rec_laboral'],
            ['label' => 'Carta de Recomendación Personal', 'name' => 'arch_carta_rec_personal'],
            ['label' => 'Contrato', 'name' => 'arch_contrato'],
            ['label' => 'Fotografía (Reciente)', 'name' => 'arch_foto'],
        ];
    }

    $documentosOpcionales = [];

    if ($tipo != 'armado') {
        $documentosOpcionales[] = ['label' => 'Cartilla Militar', 'name' => 'arch_cartilla_militar'];
        $documentosOpcionales[] = ['label' => 'Carta de Antecedentes No Penales', 'name' => 'arch_carta_no_penales'];
    }

    $documentosOpcionales = array_merge($documentosOpcionales, [
        ['label' => 'Comprobante INFONAVIT', 'name' => 'arch_infonavit'],
        ['label' => 'Comprobante FONACOT', 'name' => 'arch_fonacot'],
        ['label' => 'Licencia de Conducir', 'name' => 'arch_licencia_conducir'],
        ['label' => 'Visa', 'name' => 'visa'],
        ['label' => 'Pasaporte', 'name' => 'pasaporte'],
    ]);
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center px-4 py-2">
            <h2 class="text-xl font-semibold text-gray-800">{{ Auth::user()->name }}</h2>
        </div>
    </x-slot>

    <div class="container mx-auto px-4 py-6">
        <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg">
            <h1 class="text-2xl font-semibold mb-6 text-center">Archivos Para Alta de Usuario</h1>
            <p class="mb-6 text-center text-gray-600 text-sm">Se pueden dejar documentos obligatorios vacíos, pero luego deberán ser completados.</p>

            <form action="{{ route('sup.guardarArchivos', $solicitud->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                {{-- Documentos Obligatorios --}}
                <section>
                    <h2 class="text-xl font-semibold mb-4">Documentos Obligatorios</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach ($documentosObligatorios as $doc)
                            <div
                                x-data="dropFile('{{ $doc['name'] }}')"
                                x-on:dragover.prevent
                                x-on:drop.prevent="handleDrop($event)"
                                class="border-2 border-dashed rounded-lg p-4 flex flex-col items-center justify-center hover:border-blue-500 transition cursor-pointer"
                            >
                                <label class="block mb-2 font-medium text-gray-700 text-center">{{ $doc['label'] }} <span class="text-red-500">*</span></label>
                                <input
                                    type="file"
                                    name="{{ $doc['name'] }}"
                                    x-ref="fileInput"
                                    class="hidden"
                                    @change="handleFileInput"
                                    accept="application/pdf,image/*"
                                />
                                <button type="button" @click="$refs.fileInput.click()" class="mb-2 px-4 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700 transition">
                                    Seleccionar archivo
                                </button>
                                <template x-if="fileName">
                                    <p class="text-green-600 text-sm">Archivo: <strong x-text="fileName"></strong></p>
                                </template>
                                <template x-if="!fileName">
                                    <p class="text-gray-400 text-xs">Arrastra o suelta un archivo aquí</p>
                                </template>
                            </div>
                        @endforeach
                    </div>
                </section>

                {{-- Documentos Opcionales --}}
                <section>
                    <h2 class="text-xl font-semibold mb-4">Documentos Opcionales</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach ($documentosOpcionales as $doc)
                            <div
                                x-data="dropFile('{{ $doc['name'] }}')"
                                x-on:dragover.prevent
                                x-on:drop.prevent="handleDrop($event)"
                                class="border-2 border-dashed rounded-lg p-4 flex flex-col items-center justify-center hover:border-blue-500 transition cursor-pointer"
                            >
                                <label class="block mb-2 font-medium text-gray-700 text-center">{{ $doc['label'] }}</label>
                                <input
                                    type="file"
                                    name="{{ $doc['name'] }}"
                                    x-ref="fileInput"
                                    class="hidden"
                                    @change="handleFileInput"
                                    accept="application/pdf,image/*"
                                />
                                <button type="button" @click="$refs.fileInput.click()" class="mb-2 px-4 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700 transition">
                                    Seleccionar archivo
                                </button>
                                <template x-if="fileName">
                                    <p class="text-green-600 text-sm">Archivo: <strong x-text="fileName"></strong></p>
                                </template>
                                <template x-if="!fileName">
                                    <p class="text-gray-400 text-xs">Arrastra o suelta un archivo aquí</p>
                                </template>
                            </div>
                        @endforeach
                    </div>
                </section>

                <div class="flex flex-col sm:flex-row justify-center gap-4 mt-8">
                    <a href="{{ route('sup.nuevoUsuarioForm') }}" class="px-6 py-2 rounded bg-gray-300 text-gray-800 hover:bg-gray-400 transition text-center">
                        Regresar
                    </a>
                    <button type="submit" class="px-6 py-2 rounded bg-blue-600 text-white hover:bg-blue-700 transition">
                        Subir Documentos
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function dropFile(inputName) {
            return {
                fileName: null,
                handleDrop(event) {
                    const file = event.dataTransfer.files[0];
                    if (file) {
                        this.$refs.fileInput.files = event.dataTransfer.files;
                        this.fileName = file.name;
                    }
                },
                handleFileInput(event) {
                    const file = event.target.files[0];
                    this.fileName = file ? file.name : null;
                }
            }
        }
    </script>
</x-app-layout>
