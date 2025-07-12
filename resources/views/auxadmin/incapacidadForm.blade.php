{{-- resources/views/auxadmin/incapacidadForm.blade.php --}}
<x-app-layout>
    <x-navbar></x-navbar>
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="container mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">
                    Generar Incapacidad para: {{ $user->name }}
                </h1>

                @if(session('success'))
                    <div class="bg-green-100 border-t-4 border-green-500 rounded-b text-green-900 px-4 py-3 shadow-md mb-4" role="alert">
                        <div class="flex">
                            <div>
                                <p class="text-sm">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md mb-4" role="alert">
                        <div class="flex">
                            <div>
                                <p class="font-bold">¡Atención!</p>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('aux.guardarIncapacidad', $user->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <input type="hidden" name="user_id" value="{{ $user->id }}">

                    <div>
                        <label for="motivo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Motivo de Incapacidad
                        </label>
                        <input type="text" name="motivo" id="motivo"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                               value="{{ old('motivo') }}" required>
                    </div>

                    <div>
                        <label for="tipo_incapacidad" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Tipo de Incapacidad
                        </label>
                         <input type="text" name="tipo_incapacidad" id="tipo_incapacidad"
           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
           value="{{ old('tipo_incapacidad') }}" required>
                    </div>

                    <div>
                        <label for="ramo_seguro" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Ramo de Seguro
                        </label>
                            <input type="text" name="ramo_seguro" id="ramo_seguro"
           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
           value="{{ old('ramo_seguro') }}" required>
                    </div>

                    <div>
                        <label for="dias_incapacidad" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Días de Incapacidad
                        </label>
                        <input type="number" name="dias_incapacidad" id="dias_incapacidad" min="1"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                               value="{{ old('dias_incapacidad') }}" required>
                    </div>

                    <div>
                        <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Fecha de Inicio (a partir del)
                        </label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                               value="{{ old('fecha_inicio') }}" required>
                    </div>

                    <div>
                        <label for="folio" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Folio de Incapacidad
                        </label>
                        <input type="text" name="folio" id="folio"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                               value="{{ old('folio') }}" required>
                    </div>

                    <div>
                        <label for="archivo_pdf" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Hoja de Incapacidad (PDF)
                        </label>
                        <input type="file" name="archivo_pdf" id="archivo_pdf" accept=".pdf"
                               class="mt-1 block w-full text-gray-700 dark:text-gray-300" required>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Solo archivos PDF, máximo 2MB.</p>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('aux.incapacidadesList') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                            Cancelar
                        </a>
                        <button type="submit" class="ml-3 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Guardar Incapacidad
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
