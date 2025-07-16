<div class="px-4 py-6 mx-auto max-w-7xl">
    <div class="p-6 bg-white rounded-lg shadow dark:bg-gray-800">
        <h2 class="mb-4 text-2xl font-bold text-gray-900 dark:text-gray-100">Control de Vehículos</h2>

        @if (session()->has('success'))
        <div class="px-4 py-3 mb-4 text-green-900 bg-green-100 border-t-4 border-green-500 rounded-b shadow-md"
            role="alert">
            <p class="text-sm">{{ session('success') }}</p>
        </div>
        @endif

        @if($mostrarFormulario)
        <form wire:submit.prevent="{{ $modo == 'crear' ? 'guardarUnidad' : 'actualizarUnidad' }}"
            class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-2">
            <div>
                <label class="block text-gray-700 dark:text-gray-200">Propietario</label>
                <input type="text" wire:model.defer="nombre_propietario" class="w-full form-input" required>
                @error('nombre_propietario')
                <span class="text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label class="block text-gray-700 dark:text-gray-200">Zona</label>
                <input type="text" wire:model.defer="zona" class="w-full form-input" required>
                @error('zona')
                <span class="text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label class="block text-gray-700 dark:text-gray-200">Marca</label>
                <input type="text" wire:model.defer="marca" class="w-full form-input" required>
                @error('marca')
                <span class="text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label class="block text-gray-700 dark:text-gray-200">Modelo</label>
                <input type="text" wire:model.defer="modelo" class="w-full form-input" required>
                @error('modelo')
                <span class="text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label class="block text-gray-700 dark:text-gray-200">Placas</label>
                <input type="text" wire:model.defer="placas" class="w-full form-input" required>
                @error('placas')
                <span class="text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label class="block text-gray-700 dark:text-gray-200">Kilometraje</label>
                <input type="number" wire:model.defer="kms" class="w-full form-input" required>
                @error('kms')
                <span class="text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label class="block text-gray-700 dark:text-gray-200">Asignación Punto</label>
                <input type="text" wire:model.defer="asignacion_punto" class="w-full form-input">
                @error('asignacion_punto')
                <span class="text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label class="block text-gray-700 dark:text-gray-200">Estado del Vehículo</label>
                <input type="text" wire:model.defer="estado_vehiculo" class="w-full form-input" required>
                @error('estado_vehiculo')
                <span class="text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div class="md:col-span-2">
                <label class="block text-gray-700 dark:text-gray-200">Observaciones</label>
                <textarea wire:model.defer="observaciones" class="w-full form-input"></textarea>
                @error('observaciones')
                <span class="text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div class="flex gap-2 mt-2 md:col-span-2">
                <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">{{ $modo ==
                    'crear' ? 'Agregar' : 'Actualizar' }}</button>
                <button type="button" wire:click="resetCampos"
                    class="px-4 py-2 text-white bg-gray-400 rounded hover:bg-gray-500">Cancelar</button>
            </div>
        </form>
        @else
        <div class="flex items-center justify-between mb-4">
            <div>
                <label for="perPage" class="mr-2 text-gray-700 dark:text-gray-200">Mostrar:</label>
                <select wire:model="perPage" id="perPage" class="px-2 py-1 rounded form-select" wire:change="$refresh">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="1000">Todos</option>
                </select>
            </div>
            <button wire:click="mostrarFormularioCrear"
                class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700" type="button">
                Agregar Vehículo
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded shadow dark:bg-gray-800">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Propietario</th>
                        <th class="px-4 py-2">Zona</th>
                        <th class="px-4 py-2">Marca</th>
                        <th class="px-4 py-2 text-center">Modelo</th>
                        <th class="px-4 py-2 text-center">Placas</th>
                        <th class="px-4 py-2 text-center">Kms</th>
                        <th class="px-4 py-2">Asignación</th>
                        <th class="px-4 py-2">Estado</th>

                        <th class="px-4 py-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($unidades as $unidad)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $unidad->nombre_propietario }}</td>
                        <td class="px-4 py-2">{{ $unidad->zona }}</td>
                        <td class="px-4 py-2">{{ $unidad->marca }}</td>
                        <td class="px-4 py-2 text-center">{{ $unidad->modelo }}</td>
                        <td class="px-4 py-2 text-center">
                            <span
                                class="inline-block px-3 py-1 font-mono text-xs font-bold tracking-widest text-gray-800 bg-white border border-gray-700 rounded shadow select-none">
                                {{ $unidad->placas }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-center">{{ is_numeric($unidad->kms) ? number_format($unidad->kms) :
                            $unidad->kms
                            }}</td>
                        <td class="px-4 py-2 text-center">{{ $unidad->asignacion_punto }}</td>
                        <td class="px-4 py-2 text-center">
                            @if($unidad->is_activo)
                            <span
                                class="inline-flex items-center px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded">Activo</span>
                            @else
                            <span
                                class="inline-flex items-center px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded">Inactivo</span>
                            @endif
                        </td>
                        <td class="flex gap-2 px-4 py-2">
                            <button wire:click="editarUnidad({{ $unidad->id }})"
                                class="px-2 py-1 text-white bg-yellow-500 rounded hover:bg-yellow-600">Editar</button>
                            <button wire:click="eliminarUnidad({{ $unidad->id }})"
                                class="px-2 py-1 text-white bg-red-600 rounded hover:bg-red-700"
                                onclick="return confirm('¿Seguro que deseas eliminar esta unidad?')">Eliminar</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-4 py-2 text-center text-gray-500">No hay unidades registradas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">
                @if($unidades->hasPages())
                {{ $unidades->links() }}
                @endif
            </div>
        </div>
        @endif
    </div>
</div>