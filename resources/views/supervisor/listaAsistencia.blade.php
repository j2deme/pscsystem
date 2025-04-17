<x-app-layout>
    <x-navbar />

    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="container mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h1 class="text-2xl mb-6 text-gray-800 dark:text-white">Usuarios Activos</h1>

                <form action="{{route('sup.guardarAsistencias')}}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($elementos as $elemento)
                            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-5 border border-gray-200 dark:border-gray-700 flex flex-col justify-between">
                                <div class="flex items-center space-x-4 mb-4">
                                    <img src="{{ $elemento->solicitudAlta?->documentacion?->arch_foto ?? asset('images/default-user.png') }}" alt="Foto de {{ $elemento->name }}" class="w-16 h-16 rounded-full object-cover border border-gray-300 dark:border-gray-600">
                                    <div>
                                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white">{{ $elemento->name }}</h2>
                                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ $elemento->empresa }} - {{ $elemento->punto }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ $elemento->rol }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between mt-4">
                                    <label for="asistencia_{{ $elemento->id }}" class="text-sm text-gray-700 dark:text-gray-300">
                                        Asistió
                                    </label>
                                    <input type="checkbox" name="asistencias[]" value="{{ $elemento->id }}" id="asistencia_{{ $elemento->id }}" class="h-5 w-5 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6">
                        <label for="observaciones" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Observaciones</label>
                        <textarea name="observaciones" id="observaciones" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Escribe tus observaciones aquí..."></textarea>
                    </div>

                    <div class="mt-8 text-center">
                        <button type="submit"
                                class="inline-block bg-green-500 text-white px-6 py-2 rounded-md hover:bg-green-600 transition duration-200">
                            Guardar Asistencias
                        </button>
                        <a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 mr-2 mb-2">
                            Regresar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
