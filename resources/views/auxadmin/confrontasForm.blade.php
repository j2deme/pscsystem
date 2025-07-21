<x-app-layout>
    <x-navbar />

    <div class="py-8 px-6">
        <div class="max-w-4xl mx-auto">

            @if(session('success'))
                <div class="text-green-600 font-semibold mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200 mb-6">Subir Archivos de Informes</h2>

                <form action="{{ route('confrontas.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @php
                            $archivos = [
                                ['label' => 'PDF PSC', 'name' => 'inf_psc', 'accept' => 'application/pdf'],
                                ['label' => 'PDF SPyT', 'name' => 'inf_spyt', 'accept' => 'application/pdf'],
                                ['label' => 'PDF Montana', 'name' => 'inf_montana', 'accept' => 'application/pdf'],
                                ['label' => 'Excel PSC', 'name' => 'exc_psc', 'accept' => '.xlsx,.xls'],
                                ['label' => 'Excel SPyT', 'name' => 'exc_spyt', 'accept' => '.xlsx,.xls'],
                                ['label' => 'Excel Montana', 'name' => 'exc_montana', 'accept' => '.xlsx,.xls'],
                            ];
                        @endphp

                        @foreach($archivos as $archivo)
                            <div x-data="dropFile('{{ $archivo['name'] }}')" x-on:dragover.prevent x-on:drop.prevent="handleDrop($event)" class="border-2 border-dashed rounded-lg p-4 text-center hover:border-blue-400 transition">
                                <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">
                                    {{ $archivo['label'] }}
                                </label>
                                <input type="file" name="{{ $archivo['name'] }}" x-ref="fileInput" accept="{{ $archivo['accept'] }}" class="hidden" @change="handleFileInput" required>
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
                            Subir Archivos
                        </button>
                        <a href="{{ url()->previous() }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-5 rounded-md hover:bg-gray-400 transition">
                            Cancelar
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
