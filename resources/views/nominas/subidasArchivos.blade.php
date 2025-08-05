@php
    $documentosObligatorios = [];
        $documentosObligatorios = [
            ['label' => 'Nóminas', 'name' => 'arch_nominas'],
            ['label' => 'Destajos', 'name' => 'arch_destajos'],
        ];

@endphp

<x-app-layout>
    <x-navbar />

            <div class="py-8 px-6">
                <div class="max-w-5xl mx-auto">
                    @if(session('success'))
            <div class="mt-4 text-green-600 font-semibold">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
                <div class="mt-4 text-red-600 font-semibold">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 space-y-6">
                <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Archivos de Nóminas/Destajos</h2>

                <form action="{{route('nominas.guardarArchivos')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-6">
                        <label for="periodo" class="block text-sm font-semibold text-gray-600">Periodo</label>
                        <select id="periodo" name="periodo" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" >
                            <option value="" disabled selected>Selecciona una opción</option>
                            <option value="1° Enero">1° Enero</option>
                            <option value="2° Enero">2° Enero</option>
                            <option value="1° Febrero">1° Febrero</option>
                            <option value="2° Febrero">2° Febrero</option>
                            <option value="1° Marzo">1° Marzo</option>
                            <option value="2° Marzo">2° Marzo</option>
                            <option value="1° Abril">1° Abril</option>
                            <option value="2° Abril">2° Abril</option>
                            <option value="1° Mayo">1° Mayo</option>
                            <option value="2° Mayo">2° Mayo</option>
                            <option value="1° Junio">1° Junio</option>
                            <option value="2° Junio">2° Junio</option>
                            <option value="1° Julio">1° Julio</option>
                            <option value="2° Julio">2° Julio</option>
                            <option value="1° Agosto">1° Agosto</option>
                            <option value="2° Agosto">2° Agosto</option>
                            <option value="1° Septiembre">1° Septiembre</option>
                            <option value="2° Septiembre">2° Septiembre</option>
                            <option value="1° Octubre">1° Octubre</option>
                            <option value="2° Octubre">2° Octubre</option>
                            <option value="1° Noviembre">1° Noviembre</option>
                            <option value="2° Noviembre">2° Noviembre</option>
                            <option value="1° Diciembre">1° Diciembre</option>
                            <option value="2° Diciembre">2° Diciembre</option>
                        </select>
                    </div>

                    <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mb-2">Documentos</h3>
                    <div class="grid grid-cols-2 gap-6">
                        @foreach ($documentosObligatorios as $doc)
                            <div x-data="dropFile('{{ $doc['name'] }}')" x-on:dragover.prevent x-on:drop.prevent="handleDrop($event)" class="border-2 border-dashed rounded-lg p-4 text-center hover:border-blue-400 transition">
                                <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">
                                    {{ $doc['label'] }} <span class="text-red-500">*</span>
                                </label>
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
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-md transition">
                            Subir Documentos
                        </button>
                        <a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-5 rounded-md hover:bg-gray-400 transition">
                            Regresar
                        </a>
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
