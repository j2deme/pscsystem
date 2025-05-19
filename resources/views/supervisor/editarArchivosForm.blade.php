@php
    use Illuminate\Support\Facades\Auth;

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

    if ($tipo !== 'armado') {
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
    <x-navbar />

    <div class="py-8 px-6">
        <div class="max-w-5xl mx-auto">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 space-y-6">
                <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200 text-center">
                    {{ Auth::user()->rol == 'Supervisor' ? 'Solicitud de Alta de Usuario' : 'Editar Documentos de Usuario' }}
                </h2>

                <form action="{{ route('sup.guardarArchivosEditados', $id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mb-2">Documentos Obligatorios</h3>
                    <div class="grid grid-cols-2 gap-6">
                        @foreach ($documentosObligatorios as $doc)
                            <div x-data="dropFile('{{ $doc['name'] }}')" x-on:dragover.prevent x-on:drop.prevent="handleDrop($event)" class="border-2 border-dashed rounded-lg p-4 text-center hover:border-blue-400 transition">
                                <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">
                                    {{ $doc['label'] }} <span class="text-red-500">*</span>
                                </label>
                                @if ($documentacion && $documentacion->{$doc['name']})
                                    <div class="mb-2 text-sm text-green-600">
                                        Archivo actual:
                                        <a href="{{ asset($documentacion->{$doc['name']}) }}" target="_blank" class="underline text-blue-500">
                                            Ver {{ $doc['label'] }}
                                        </a>
                                    </div>
                                @endif
                                <input type="file" name="{{ $doc['name'] }}" x-ref="fileInput" class="hidden" @change="handleFileInput">
                                <button type="button" @click="$refs.fileInput.click()" class="mt-2 px-3 py-1 bg-blue-500 text-white text-sm rounded">
                                    Seleccionar archivo
                                </button>
                                <template x-if="fileName">
                                    <p class="mt-2 text-sm text-green-600">Archivo: <strong x-text="fileName"></strong></p>
                                </template>
                            </div>
                        @endforeach
                    </div>

                    <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mt-10 mb-2">Documentos Opcionales</h3>
                    <div class="grid grid-cols-2 gap-6">
                        @foreach ($documentosOpcionales as $doc)
                            <div x-data="dropFile('{{ $doc['name'] }}')" x-on:dragover.prevent x-on:drop.prevent="handleDrop($event)" class="border-2 border-dashed rounded-lg p-4 text-center hover:border-blue-400 transition">
                                <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">
                                    {{ $doc['label'] }}
                                </label>
                                @if ($documentacion && $documentacion->{$doc['name']})
                                    <div class="mb-2 text-sm text-green-600">
                                        Archivo actual:
                                        <a href="{{ asset($documentacion->{$doc['name']}) }}" target="_blank" class="underline text-blue-500">
                                            Ver {{ $doc['label'] }}
                                        </a>
                                    </div>
                                @endif
                                <input type="file" name="{{ $doc['name'] }}" x-ref="fileInput" class="hidden" @change="handleFileInput">
                                <button type="button" @click="$refs.fileInput.click()" class="mt-2 px-3 py-1 bg-blue-500 text-white text-sm rounded">
                                    Seleccionar archivo
                                </button>
                                <template x-if="fileName">
                                    <p class="mt-2 text-sm text-green-600">Archivo: <strong x-text="fileName"></strong></p>
                                </template>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8 flex justify-center space-x-4">
                        @if(Auth::user()->rol == 'Supervisor')
                            <a href="{{ route('sup.solicitud.detalle', $id) }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-5 rounded-md hover:bg-gray-400 transition">
                                Regresar
                            </a>
                        @else
                            <a href="{{ route('admin.editarUsuarioForm',$user->id) }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-5 rounded-md hover:bg-gray-400 transition">
                                Regresar
                            </a>
                        @endif
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-md transition">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
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
