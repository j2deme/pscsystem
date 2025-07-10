<div>
    @foreach ($conversaciones as $conv)
        @php
            $otro = $conv->users->where('id', '!=', auth()->id())->first();
            $foto = $otro?->documentacionAltas?->arch_foto;
            $foto_url = $foto ? asset($foto) : asset('images/default-user.jpg');
        @endphp

        <div wire:click="seleccionarConversacion({{ $conv->id }})"
            class="flex items-center gap-3 cursor-pointer p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700 mb-1">
            <img src="{{ $foto_url }}" alt="Foto" class="w-10 h-10 rounded-full object-cover">

            <div class="flex-1">
                <strong class="block text-gray-900 dark:text-white">
                    {{ $conv->is_group ? $conv->title ?? 'Grupo sin nombre' : $otro?->name }}
                </strong>
                <span class="text-sm text-gray-600 dark:text-gray-400">
                    {{ $conv->latestMessage?->body ?? 'Sin mensajes' }}
                </span>
            </div>
        </div>
    @endforeach

</div>
