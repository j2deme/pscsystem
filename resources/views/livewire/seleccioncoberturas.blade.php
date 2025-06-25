<div class="flex flex-col sm:flex-row sm:space-x-4">
    <div class="sm:w-1/3">
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
                            class="px-4 py-2 hover:bg-gray-100 cursor-pointer"
                            wire:key="user-{{ $usuario->id }}"
                        >
                            {{ $usuario->name }}
                        </li>
                    @empty
                        <li class="px-4 py-2 text-gray-500">
                            {{ strlen($search) >= 2 ? 'No se encontraron resultados' : 'Ingrese al menos 2 caracteres' }}
                        </li>
                    @endforelse
                </ul>
            </div>
        @endif
    </div>

    <div class="sm:w-2/3 mt-4 sm:mt-0">
        @if(!empty($seleccionados))
            <ul class="space-y-2">
                @foreach($seleccionados as $index => $usuario)
                    <li class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-2 bg-gray-100 rounded">
                        <div class="flex-1">
                            <span class="font-semibold">{{ $usuario['nombre'] }}</span><br>
                            <span class="text-sm text-gray-600">Punto: {{ $usuario['punto'] ?? 'Sin asignar' }}</span>
                        </div>

                        <div class="mt-2 sm:mt-0 sm:ml-4">
                            <select wire:change="asignarSubpunto({{ $usuario['id'] }}, $event.target.value)"
                                class="p-2 border rounded">
                                <option value="">Selecciona punto</option>
                                @foreach($puntos as $punto)
                                    <optgroup label="{{ $punto->nombre }}">
                                        @foreach($punto->subpuntos as $sub)
                                            <option value="{{ $sub->id }}">
                                                @if(!is_null($sub->codigo))
                                                    ({{ str_pad($sub->codigo, 3, '0', STR_PAD_LEFT) }})
                                                @endif
                                                {{ $sub->nombre }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>

                        <button type="button" wire:click="eliminarSeleccionado({{ $usuario['id'] }})" class="text-red-600 hover:text-red-800 text-sm ml-4">
                            Eliminar
                        </button>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
    @foreach($seleccionados as $usuario)
    <input type="hidden" name="coberturas[]" value='@json(["id" => $usuario["id"], "subpunto_id" => $usuario["subpunto_id"] ?? null])'>
@endforeach
</div>


