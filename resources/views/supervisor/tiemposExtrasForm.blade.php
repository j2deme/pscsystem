<x-app-layout>
    <x-navbar></x-navbar>
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="container mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6 mb-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 mr-3 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Tiempo Extra
                            </h1>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Registrar tiempo extra para {{ $elemento->name }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-6 mb-8">
                    <div class="flex items-center space-x-4">
                        @php
                            $foto = $elemento->documentacionAltas->arch_foto;
                        @endphp
                        @if($foto)
                            <img src="{{ asset($foto) }}"
                                 alt="Foto de {{ $elemento->name }}"
                                 class="w-20 h-20 rounded-full object-cover border-2 border-white dark:border-gray-600 shadow-sm">
                        @else
                            <div class="flex-shrink-0 w-20 h-20 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center">
                                <span class="text-white font-medium text-xl">
                                    {{ substr($elemento->name ?? '', 0, 2) }}
                                </span>
                            </div>
                        @endif
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $elemento->name }}</h2>
                            <p class="text-gray-600 dark:text-gray-300">{{ $elemento->empresa }} - {{ $elemento->punto }}</p>
                            <p class="text-gray-500 dark:text-gray-400">{{ $elemento->rol }}</p>
                        </div>
                    </div>
                </div>

                @if($extraHoy == null)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Registrar Tiempo Extra
                        </h3>

                        <form action="{{ route('sup.guardarTiempoExtra', $elemento->id) }}" method="POST" class="space-y-6">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $elemento->id }}">

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="fecha" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Fecha
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <input type="date"
                                               name="fecha"
                                               id="fecha"
                                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white"
                                               value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>

                                <div>
                                    <label for="hora_inicio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Hora de inicio
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <input type="time"
                                               name="hora_inicio"
                                               id="hora_inicio"
                                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white">
                                    </div>
                                </div>

                                <div>
                                    <label for="hora_fin" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Hora de fin
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <input type="time"
                                               name="hora_fin"
                                               id="hora_fin"
                                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="observaciones" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Observaciones
                                </label>
                                <textarea name="observaciones"
                                          id="observaciones"
                                          rows="4"
                                          class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white"
                                          placeholder="Ingrese observaciones adicionales..."></textarea>
                            </div>

                            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-6">
                                <button type="submit"
                                        class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition duration-200 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Registrar Tiempo Extra
                                </button>
                                <a href="{{ route('sup.tiemposExtras') }}"
                                   class="inline-flex items-center px-4 py-3 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition duration-200 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    Regresar
                                </a>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-yellow-500 dark:text-yellow-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tiempo extra ya registrado</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">Ya registraste un tiempo extra para este usuario hoy.</p>

                        <a href="{{ route('sup.tiemposExtras') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition duration-200 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Regresar
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
