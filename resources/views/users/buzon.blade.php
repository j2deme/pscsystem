<x-app-layout>
    <x-navbar></x-navbar>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 sm:p-8">
                <h1 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6 text-center">
                    Buzón de mensajes
                </h1>

                @if(session('success'))
                    <div class="bg-green-100 border-t-4 border-green-500 rounded-b text-green-900 px-4 py-3 shadow-md" role="alert">
                        <div class="flex">
                            <div>
                                <p class="text-sm">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                    @else
                @endif

                <form method="POST" action="{{ route('user.enviarSugerencia', Auth::user()->id) }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">
                            Fecha
                        </label>
                        <input type="text" name="fecha"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-100 dark:bg-gray-700 dark:text-white"
                            value="{{ date('d-m-Y') }}" readonly>
                    </div>

                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">
                            Asunto <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="asunto"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white"
                            required>
                    </div>

                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">
                            Mensaje <span class="text-red-500">*</span>
                        </label>
                        <textarea name="mensaje" rows="5"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white"
                            required></textarea>
                    </div>

                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                        Nota: La queja o sugerencia se hará llegar de manera anónima a los administradores del sistema para su revisión. Su mensaje será tomado en cuenta.
                    </p>

                    <div class="flex flex-col sm:flex-row sm:justify-center sm:items-center gap-4 mt-6">
                        <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition">
                            Enviar
                        </button>
                        <a href="{{ route('dashboard') }}"
                            class="bg-gray-300 text-gray-800 px-6 py-2 rounded-md hover:bg-gray-400 transition text-center">
                            Regresar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
