<x-app-layout>
    <x-navbar></x-navbar>
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="container mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h1 class="flex justify-center text-2xl text-gray-800 dark:text-white">Tiempo Extra de {{ $elemento->name }}</h1>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-6 mt-8">
                                    <div class="flex items-center space-x-4 mb-4">
                                        <img src="{{ $foto ?: url('images/default-user.jpg') }}" alt="Foto de {{ $elemento->name }}" class="w-16 h-16 rounded-full object-cover border border-gray-300 dark:border-gray-600">
                                        <div>
                                            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">{{ $elemento->name }}</h2>
                                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $elemento->empresa }} - {{ $elemento->punto }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $elemento->rol }}</p>
                                        </div>
                                    </div>
                    </div>
                    <form action="{{route('sup.guardarTiempoExtra', $elemento->id)}}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $elemento->id }}">

                        <div>
                            <label for="fecha" class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Fecha</label>
                            <input type="date" name="fecha" id="fecha" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>

                        <div>
                            <label for="hora_inicio" class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Hora de inicio</label>
                            <input type="time" name="hora_inicio" id="hora_inicio" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>

                        <div>
                            <label for="hora_fin" class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Hora de fin</label>
                            <input type="time" name="hora_fin" id="hora_fin" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="observaciones" class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Observaciones</label>
                            <textarea name="observaciones" id="observaciones" rows="4" class="w-full rounded-lg border-gray-800 b-2 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></textarea>
                        </div>

                        <div class="pt-2 flex justify-center space-x-4">
                            <button type="submit" class="inline-block bg-green-300 text-gray-800 py-2 px-4 rounded-md hover:bg-green-400 mr-2 mb-2">
                                Registrar horario
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
