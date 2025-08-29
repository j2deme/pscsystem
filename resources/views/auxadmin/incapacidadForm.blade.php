<x-app-layout>
    <x-navbar></x-navbar>
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="container mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6 mb-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 mr-3 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20h18a2 2 0 01-2 2V7a2 2 0 01-2-2H5a2 2 0 01-2 2v12a2 2 0 012 2z" />
                                </svg>
                                Generar Incapacidad
                            </h1>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Complete la información para generar una nueva incapacidad para <span class="font-medium">{{ $user->name }}</span>
                            </p>
                        </div>

                        <div class="flex items-center space-x-2">
                            <div class="bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200 px-3 py-1 rounded-full">
                                <span class="text-sm font-medium">Empleado</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-200 px-4 py-3 rounded-lg mb-6">
                        <div class="flex">
                            <div>
                                <p class="text-sm">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-200 px-4 py-3 rounded-lg mb-6">
                        <div class="flex">
                            <div>
                                <p class="font-bold mb-2">¡Atención!</p>
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li class="text-sm">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('aux.guardarIncapacidad', $user->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <input type="hidden" name="user_id" value="{{ $user->id }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 dark:bg-gray-700/30 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Información del Empleado
                            </h3>
                            <div class="space-y-2">
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Nombre</p>
                                    <p class="text-gray-900 dark:text-white">{{ $user->name }}</p>
                                </div>
                                @if($user->email)
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</p>
                                        <p class="text-gray-900 dark:text-white">{{ $user->email }}</p>
                                    </div>
                                @endif
                                @if($user->solicitudAlta?->punto)
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Punto</p>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200">
                                            {{ $user->solicitudAlta->punto }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label for="motivo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Motivo de Incapacidad
                                </label>
                                <input type="text" name="motivo" id="motivo"
                                       class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                                       value="{{ old('motivo') }}" required>
                            </div>

                            <div>
                                <label for="tipo_incapacidad" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tipo de Incapacidad
                                </label>
                                <select name="tipo_incapacidad" id="tipo_incapacidad"
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                                        required>
                                    <option value="">Seleccione un tipo</option>
                                    <option value="Enfermedad General" {{ old('tipo_incapacidad') == 'Enfermedad General' ? 'selected' : '' }}>Enfermedad General</option>
                                    <option value="Accidente de Trabajo" {{ old('tipo_incapacidad') == 'Accidente de Trabajo' ? 'selected' : '' }}>Accidente de Trabajo</option>
                                    <option value="Accidente de Trayecto" {{ old('tipo_incapacidad') == 'Accidente de Trayecto' ? 'selected' : '' }}>Accidente de Trayecto</option>
                                    <option value="Enfermedad de Riesgo de Trabajo" {{ old('tipo_incapacidad') == 'Enfermedad de Riesgo de Trabajo' ? 'selected' : '' }}>Enfermedad de Riesgo de Trabajo</option>
                                    <option value="Maternidad" {{ old('tipo_incapacidad') == 'Maternidad' ? 'selected' : '' }}>Maternidad</option>
                                    <option value="Otro" {{ old('tipo_incapacidad') == 'Otro' ? 'selected' : '' }}>Otro</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="ramo_seguro" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Ramo de Seguro
                            </label>
                            <input type="text" name="ramo_seguro" id="ramo_seguro"
                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                                   value="{{ old('ramo_seguro') }}" required>
                        </div>

                        <div>
                            <label for="dias_incapacidad" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Días de Incapacidad
                            </label>
                            <input type="number" name="dias_incapacidad" id="dias_incapacidad" min="1"
                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                                   value="{{ old('dias_incapacidad') }}" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Fecha de Inicio (a partir del)
                            </label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio"
                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                                   value="{{ old('fecha_inicio') }}" required>
                        </div>

                        <div>
                            <label for="folio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Folio de Incapacidad
                            </label>
                            <input type="text" name="folio" id="folio"
                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                                   value="{{ old('folio') }}" required>
                        </div>
                    </div>

                    <div>
                        <label for="archivo_pdf" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Hoja de Incapacidad
                        </label>
                        <div class="relative">
                            <input type="file" name="archivo_pdf" id="archivo_pdf" accept=".pdf,.jpg,.jpeg,.png"
                                   class="block w-full text-sm text-gray-700 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 file:cursor-pointer dark:file:bg-red-900/30 dark:file:text-red-200 dark:hover:file:bg-red-800/30">
                        </div>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Solo archivos PDF, JPG, JPEG, PNG, máximo 2MB.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit"
                                class="inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition duration-200 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            Guardar Incapacidad
                        </button>
                        <a href="{{ route('aux.incapacidadesList') }}"
                           class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition duration-200 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
