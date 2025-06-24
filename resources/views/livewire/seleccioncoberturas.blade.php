<div class="relative">
    <input
        type="text"
        wire:model.live.debounce.300ms="search"
        placeholder="Buscar usuario..."
        class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
        autocomplete="off"
    >

    @if($showDropdown)
        <div class="absolute z-10 mt-1 w-full bg-white rounded-md shadow-lg max-h-60 overflow-auto border border-gray-200">
            <ul>
                @forelse($usuarios as $usuario)
                    <li
                        wire:click="seleccionarUsuario({{ $usuario->id }})"
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

    @if(!empty($seleccionados))
        <div class="mt-3">
            <h3 class="font-semibold mb-2 text-gray-700">Usuarios seleccionados:</h3>
            <ul class="space-y-2">
                @foreach($seleccionados as $usuario)
                    <li class="flex items-center justify-between p-2 bg-gray-100 rounded">
                        <span>{{ $usuario['nombre'] }} — <span class="text-sm text-gray-600">{{ $usuario['punto'] }}</span></span>
                        <button type="button" wire:click="eliminarSeleccionado({{ $usuario['id'] }})" class="text-red-600 hover:text-red-800 text-sm ml-4">Eliminar</button>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @foreach($seleccionados as $usuario)
        <input type="hidden" name="coberturas[]" value="{{ $usuario['id'] }}">
    @endforeach
</div>
