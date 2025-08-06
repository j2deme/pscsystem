{{-- resources/views/auxadmin/riesgosTrabajoForm.blade.php --}}
<x-app-layout>
    <x-navbar></x-navbar>
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="container mx-auto max-w-2xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h1 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mb-6">
                    Generar Riesgo de Trabajo para: {{ $user->name }}
                </h1>

                <!-- Mensajes de éxito o error -->
                @if(session('success'))
                    <div class="bg-green-100 border-t-4 border-green-500 rounded-b text-green-900 px-4 py-3 shadow-md mb-4" role="alert">
                        <p class="text-sm">{{ session('success') }}</p>
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md mb-4" role="alert">
                        <p class="text-sm">{{ session('error') }}</p>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md mb-4" role="alert">
                        <ul class="mt-3 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Formulario -->
                <form action="{{ route('aux.guardarRiesgo', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->id }}">

                    <!-- Tipo de Riesgo -->
                    <div class="mb-4">
                        <label for="tipo_riesgo" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                            Tipo de Riesgo:
                        </label>
                        <select name="tipo_riesgo" id="tipo_riesgo" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600">
                            <option value="">Selecciona una opción</option>
                            <option value="En el trabajo" {{ old('tipo_riesgo') == 'En el trabajo' ? 'selected' : '' }}>En el trabajo</option>
                            <option value="En trayecto" {{ old('tipo_riesgo') == 'En trayecto' ? 'selected' : '' }}>En trayecto</option>
                        </select>
                    </div>

                    <!-- Descripción/Observaciones -->
                    <div class="mb-4">
                        <label for="descripcion_observaciones" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                            Descripción / Observaciones:
                        </label>
                        <textarea name="descripcion_observaciones" id="descripcion_observaciones" rows="5"
                                  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600">{{ old('descripcion_observaciones') }}</textarea>
                    </div>

                    <!-- Archivo PDF -->
                    <div class="mb-6">
                        <label for="archivo_pdf" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                            Subir Hoja de Riesgos (PDF):
                        </label>
                        <input type="file" name="archivo_pdf" id="archivo_pdf" accept=".pdf"
                               class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Solo archivos PDF (máx. 2MB).</p>
                    </div>

                    <!-- Archivo Alta (PDF o Imágenes) -->
                    <div class="mb-6">
                        <label for="arch_alta" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                            Subir Archivo de Alta (PDF o Imágenes):
                        </label>
                        <input type="file" name="arch_alta" id="arch_alta" accept=".pdf,.jpg,.jpeg,.png"
                               class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">PDF o imágenes (máx. 2MB).</p>
                    </div>

                    <!-- Botones -->
                    <div class="flex items-center justify-between">
                        <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Guardar Riesgo
                        </button>
                        <a href="{{ route('aux.riesgosTrabajo') }}"
                           class="inline-block align-baseline font-bold text-sm text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
