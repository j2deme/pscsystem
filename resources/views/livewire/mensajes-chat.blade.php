<div>
    @if ($conversation)
        @php
            $otro = collect($conversation['users'])
                ->where('id', '!=', auth()->id())
                ->first();
            $foto = $otro['documentacion_altas']['arch_foto'] ?? null;
            $foto_url = $foto ? asset($foto) : asset('images/default-user.jpg');
        @endphp

        <div class="flex items-center gap-3 border-b border-gray-200 dark:border-gray-700 pb-3 mb-3">
            <img src="{{ $foto_url }}" alt="Foto" class="w-10 h-10 rounded-full object-cover">

            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ $conversation['is_group'] ? $conversation['title'] ?? 'Grupo sin nombre' : $otro['name'] ?? 'Usuario' }}
                </h2>
            </div>
        </div>

        <div class="h-[60vh] overflow-y-auto border p-2 rounded bg-gray-50 dark:bg-gray-800 mb-4">
            @foreach ($messages as $msg)
                @php
                    $esMio = $msg['user_id'] === auth()->id();
                @endphp

                <div class="mb-3 flex {{ $esMio ? 'justify-end' : 'justify-start' }}">
                    <div
                        class="max-w-xs px-3 py-2 rounded-lg shadow
                        {{ $esMio
                            ? 'bg-blue-600 text-white rounded-br-none'
                            : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded-bl-none' }}">
                        <p class="text-sm">{{ $msg['body'] }}</p>
                        <small
                            class="block text-xs mt-1 {{ $esMio ? 'text-blue-100' : 'text-gray-500 dark:text-gray-400' }}">
                            {{ \Carbon\Carbon::parse($msg['created_at'])->format('d/m/Y H:i') }}
                        </small>
                    </div>
                </div>
            @endforeach
        </div>

        <form wire:submit.prevent="enviarMensaje" class="flex items-center gap-2">
            <div class="flex-1 relative">
                <textarea wire:model.defer="body" rows="1" placeholder="Escribe un mensaje..."
                    class="w-full resize-none p-3 pr-12 border border-gray-300 dark:border-gray-600 rounded-full bg-gray-100 dark:bg-gray-900 text-sm text-gray-800 dark:text-white focus:outline-none focus:ring focus:ring-blue-300"></textarea>

                <button type="submit"
                    class="absolute right-2 top-1/2 -translate-y-1/2 text-blue-600 hover:text-blue-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </form>
    @else
        <p class="text-gray-500 dark:text-gray-400">Selecciona una conversación</p>
    @endif
</div>
