<x-app-layout>
    <x-navbar />

    <div class="py-8 px-6 max-w-4xl mx-auto">
        @if (session('success'))
            <div class="bg-green-100 text-green-900 p-4 rounded">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="bg-red-100 text-red-900 p-4 rounded">{{ session('error') }}</div>
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 space-y-8">
            <h2 class="text-2xl font-bold text-gray-700 dark:text-white">Subir Archivos de Cédulas</h2>

            <!-- EMA (Mensual) -->
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">EMA (Mensual)</h3>

            @if ($emaSubida)
                <div class="bg-yellow-100 text-yellow-800 p-4 rounded mb-4">
                    Ya se han subido los archivos EMA para este mes.
                </div>
            @else
                <form action="{{ route('aux.cedulasupload', 'ema') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-4">
                    @csrf
                    @foreach (['ema_spyt' => 'PDF SPyT', 'ema_psc' => 'PDF PSC', 'ema_montana' => 'PDF Montana'] as $name => $label)
                        <div x-data="dropFile('{{ $name }}')" x-on:dragover.prevent x-on:drop.prevent="handleDrop($event)"
                            class="border-2 border-dashed rounded-lg p-4 text-center hover:border-blue-400 transition">
                            <label
                                class="block text-gray-700 dark:text-gray-300 font-medium mb-1">{{ $label }}</label>
                            <input type="file" name="{{ $name }}" x-ref="fileInput" accept="application/pdf"
                                class="hidden" @change="handleFileInput" required>
                            <button type="button" @click="$refs.fileInput.click()"
                                class="mt-2 px-3 py-1 bg-blue-600 text-white rounded">
                                Seleccionar archivo
                            </button>
                            <template x-if="fileName">
                                <p class="mt-2 text-sm text-green-600">Archivo: <strong x-text="fileName"></strong></p>
                            </template>
                        </div>
                    @endforeach
                    <div class="flex justify-end space-x-3">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">Subir
                            EMA</button>
                        <a href="{{ url()->previous() }}"
                            class="bg-white border border-blue-600 text-blue-600 px-6 py-2 rounded hover:bg-blue-50">Cancelar</a>
                    </div>
                </form>
            @endif


            <!-- EVA (Bimestral) -->
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">EVA (Bimestral)</h3>

@if($evaSubida)
    <div class="bg-yellow-100 text-yellow-800 p-4 rounded mb-4">
        Ya se han subido los archivos EVA para este bimestre.
    </div>
@else
    <form action="{{ route('aux.cedulasupload', 'eva') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @foreach(['eva_spyt' => 'PDF SPyT', 'eva_psc' => 'PDF PSC', 'eva_montana' => 'PDF Montana'] as $name => $label)
            <div x-data="dropFile('{{ $name }}')" x-on:dragover.prevent x-on:drop.prevent="handleDrop($event)" class="border-2 border-dashed rounded-lg p-4 text-center hover:border-blue-400 transition">
                <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">{{ $label }}</label>
                <input type="file" name="{{ $name }}" x-ref="fileInput" accept="application/pdf" class="hidden" @change="handleFileInput" required>
                <button type="button" @click="$refs.fileInput.click()" class="mt-2 px-3 py-1 bg-blue-600 text-white rounded">
                    Seleccionar archivo
                </button>
                <template x-if="fileName">
                    <p class="mt-2 text-sm text-green-600">Archivo: <strong x-text="fileName"></strong></p>
                </template>
            </div>
        @endforeach
        <div class="flex justify-end space-x-3">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">Subir EVA</button>
            <a href="{{ url()->previous() }}" class="bg-white border border-blue-600 text-blue-600 px-6 py-2 rounded hover:bg-blue-50">Cancelar</a>
        </div>
    </form>
@endif

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
