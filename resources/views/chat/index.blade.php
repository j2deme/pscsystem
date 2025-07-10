<x-app-layout>
    <x-navbar></x-navbar>

    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h1 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Mis conversaciones</h1>
                <a href="{{ route('mensajes.crearChat') }}"
                    class="inline-block mb-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    + Nuevo chat
                </a>
                <ul class="space-y-2">
                    @forelse ($conversaciones as $conv)
                        <li class="border-b border-gray-300 dark:border-gray-600 pb-2">
                            <a href="{{ route('mensajes.show', $conv->id) }}"
                                class="block text-blue-600 hover:underline dark:text-blue-400">
                                @if ($conv->is_group)
                                    {{ $conv->title ?? 'Grupo sin nombre' }}
                                @else
                                    {{ $conv->users->where('id', '!=', auth()->id())->pluck('name')->implode(', ') }}
                                @endif
                            </a>
                            <small class="text-gray-600 dark:text-gray-400 block mt-1">
                                Último: {{ $conv->latestMessage?->body ?? 'Sin mensajes' }}
                            </small>
                        </li>
                    @empty
                        <li class="text-gray-600 dark:text-gray-300">No tienes conversaciones</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
