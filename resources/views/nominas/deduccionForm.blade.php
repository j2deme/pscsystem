<x-app-layout>
    <x-navbar></x-navbar>
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="container mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Nueva deducción</h2>
                <form action="{{ route('guardarDeduccion') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label>Concepto</label>
                        <input type="text" name="concepto" class="w-full border rounded p-2" required>
                    </div>

                    <div>
                        <label>Usuario</label>
                        @livewire('nuevadeduccion')

                        <input type="hidden" name="user_id" value="{{ old('user_id') }}" id="formUserId">
                    </div>

                    <div>
                        <label>Monto</label>
                        <input type="number" name="monto" step="0.01" class="w-full border rounded p-2" required>
                    </div>

                    <div>
                        <label>Fecha Inicio</label>
                        <input type="date" name="fecha_inicio" class="w-full border rounded p-2" required>
                    </div>

                    <div>
                        <label>Núm. Quincenas</label>
                        <input type="number" name="num_quincenas" class="w-full border rounded p-2" required>
                    </div>

                    <div class="flex justify-center">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Guardar</button>
                        <a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 ml-2 mr-2">
                            Regresar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        Livewire.on('inputActualizado', ({ usuarioId, nombre }) => {
            const hiddenInput = document.getElementById('formUserId');
            if (hiddenInput) {
                hiddenInput.value = usuarioId;
            }

            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.value = nombre;
            }
        });
    });
</script>
@endpush


