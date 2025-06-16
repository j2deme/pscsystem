<div class="relative" x-data>
    <input
        type="text"
        wire:model.live.debounce.300ms="search"
        wire:focus="$set('showDropdown', true)"
        placeholder="Buscar usuario..."
        class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
        autocomplete="off"
        id="searchInput"
        x-ref="searchInput"
    >

    <div wire:loading class="text-sm text-gray-500 mt-1">Buscando...</div>

    @if($showDropdown)
        <div class="absolute z-10 mt-1 w-full bg-white rounded-md shadow-lg max-h-60 overflow-auto border border-gray-200">
            <ul>
                @forelse($usuarios as $usuario)
                    <li
                        wire:click="seleccionarUsuario({{ $usuario->id }}, '{{ addslashes($usuario->name) }}')"
                        class="px-4 py-2 hover:bg-gray-100 cursor-pointer flex items-center"
                        wire:key="user-{{ $usuario->id }}"
                    >
                        <span>{{ $usuario->name }}</span>
                    </li>
                @empty
                    <li class="px-4 py-2 text-gray-500">
                        @if(strlen($search) >= 2)
                            No se encontraron resultados
                        @else
                            Ingrese al menos 2 caracteres
                        @endif
                    </li>
                @endforelse
            </ul>
        </div>
    @endif

</div>

@push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('inputActualizado', (event) => {
            const input = document.getElementById('searchInput');
            if (input) {
                input.value = event.nombre;
                input.dispatchEvent(new Event('input'));
            }

            const hiddenInput = document.querySelector('input[name="user_id"]');
            if (hiddenInput) {
                hiddenInput.value = @this.selectedUserId;
            }
        });
    });
</script>
@endpush
