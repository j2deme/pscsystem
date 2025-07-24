<x-app-layout>
    <x-navbar />

    <div class="py-8 px-6 max-w-3xl mx-auto">
        @if(session('success'))
            <div class="bg-green-100 border-t-4 border-green-500 rounded-b text-green-900 px-4 py-3 shadow-md mb-6">
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        @elseif(session('error'))
            <div class="bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md mb-6">
                <p class="text-sm">{{ session('error') }}</p>
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
            <h2 class="text-2xl font-semibold text-gray-700 dark:text-white mb-6">Subir Archivos SIPARE</h2>

            <form action="{{ route('aux.sipareUpload') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @php
                    $archivos = [
                        ['name' => 'pdf_spyt', 'label' => 'PDF SPyT'],
                        ['name' => 'pdf_psc', 'label' => 'PDF PSC'],
                        ['name' => 'pdf_montana', 'label' => 'PDF Montana'],
                    ];
                @endphp

                <div class="grid grid-cols-1 gap-6">
                    @foreach ($archivos as $archivo)
                        <div x-data="dropFile('{{ $archivo['name'] }}')" x-on:dragover.prevent x-on:drop.prevent="handleDrop($event)"
                            class="border-2 border-dashed rounded-lg p-4 text-center hover:border-blue-400 transition">
                            <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">
                                {{ $archivo['label'] }}
                            </label>
                            <input type="file" name="{{ $archivo['name'] }}" x-ref="fileInput"
                                   accept="application/pdf" class="hidden" @change="handleFileInput" required>
                            <button type="button" @click="$refs.fileInput.click()"
                                class="mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                Seleccionar archivo
                            </button>
                            <template x-if="fileName">
                                <p class="mt-2 text-sm text-green-600">Archivo: <strong x-text="fileName"></strong></p>
                            </template>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 flex justify-center space-x-4">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-md transition">
                        Subir Archivos
                    </button>
                    <a href="{{ url()->previous() }}"
                        class="inline-block border border-blue-600 text-blue-600 py-2 px-6 rounded-md hover:bg-blue-50 transition">
                        Cancelar
                    </a>
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
