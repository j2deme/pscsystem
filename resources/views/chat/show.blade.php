<x-app-layout>
    <x-navbar></x-navbar>

    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="mx-auto max-w-4xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h1 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Conversación</h1>

                <div class="border border-gray-300 dark:border-gray-600 rounded p-4 h-80 overflow-y-auto mb-4 bg-gray-50 dark:bg-gray-700">
                    @foreach ($conversation->messages as $msg)
                        <div class="mb-3">
                            <strong class="text-gray-800 dark:text-white">{{ $msg->user->name }}</strong>
                            <p class="text-gray-700 dark:text-gray-300">{{ $msg->body }}</p>
                            <small class="text-gray-500 dark:text-gray-400">{{ $msg->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                    @endforeach
                </div>

                <form method="POST" action="{{ route('mensajes.store') }}">
                    @csrf
                    <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
                    <textarea name="body" rows="3" required class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-900 dark:text-white resize-none"></textarea>
                    <button type="submit" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Enviar
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
