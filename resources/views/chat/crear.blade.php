<x-app-layout>
    <x-navbar></x-navbar>

    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="mx-auto max-w-4xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h1 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Iniciar nueva conversación</h1>

                <form method="POST" action="{{ route('mensajes.nueva') }}">
                    @csrf

                    <label class="block text-sm text-gray-700 dark:text-gray-300 mb-2">Selecciona un usuario:</label>

                    <select name="user_id" class="w-full border border-gray-300 dark:border-gray-600 rounded p-2 dark:bg-gray-900 dark:text-white" required>
                        <option value="" disabled selected>-- Seleccionar --</option>
                        @foreach ($usuarios as $usuario)
                            <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                        @endforeach
                    </select>

                    <button type="submit"
                        class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Iniciar chat
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
