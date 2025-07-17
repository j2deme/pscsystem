<x-livewire.monitoreo-layout :breadcrumb-items="[
        ['icon' => 'ti-home', 'url' => route('dashboard')],
        ['icon' => 'ti-car', 'label' => 'Control de Vehículos']
    ]" title-main="Control de Vehículos"
    help-text="Administra y consulta el listado de vehículos registrados en el sistema.">
    @if (session()->has('success'))
    @php
    $msg = session('success');
    $isDelete = str_contains(strtolower($msg), 'eliminad');
    @endphp
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
        class="relative px-4 py-3 mb-4 {{ $isDelete ? 'text-red-900 bg-red-100 border-red-500' : 'text-green-900 bg-green-100 border-green-500' }} border-t-4 rounded-b shadow-md"
        role="alert" @keydown.escape.window="show = false">
        <div class="flex items-center gap-2">
            <i class="ti {{ $isDelete ? 'ti-circle-x text-red-600' : 'ti-circle-check text-green-600' }} text-lg"></i>
            <p class="text-sm">{{ $msg }}</p>
        </div>
        <button type="button" @click="show = false"
            class="absolute text-xl leading-none top-2 right-2 focus:outline-none">&times;</button>
    </div>
    @endif
    @if($mostrarFormulario)
    <div class="flex items-center gap-2 mb-4">
        <span class="inline-flex items-center justify-center w-8 h-8 text-xl rounded-full"
            :class="$modo == 'crear' ? 'bg-blue-100 text-blue-600' : 'bg-yellow-100 text-yellow-600'">
            <i class="ti" :class="$modo == 'crear' ? 'ti-plus' : 'ti-edit'"></i>
        </span>
        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
            {{ $modo == 'crear' ? 'Agregar Vehículo' : 'Editar Vehículo' }}
        </h3>
    </div>
    <form wire:submit.prevent="{{ $modo == 'crear' ? 'guardarUnidad' : 'actualizarUnidad' }}"
        class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-2">
        <div>
            <label class="block text-gray-700 dark:text-gray-200">Propietario</label>
            <div class="relative">
                <input type="text" wire:model.defer="nombre_propietario" list="propietarios-list"
                    class="w-full h-10 pl-10 pr-3 transition-all duration-150 bg-white border border-gray-300 rounded-lg form-input focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
                    required>
                <span class="absolute text-gray-400 -translate-y-1/2 pointer-events-none left-3 top-1/2">
                    <i class="ti ti-search"></i>
                </span>
                <datalist id="propietarios-list">
                    @foreach($propietarios as $propietario)
                    <option value="{{ $propietario }}">
                        @endforeach
                </datalist>
            </div>
            @error('nombre_propietario')
            <span class="text-xs text-red-500">{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label class="block text-gray-700 dark:text-gray-200">Zona</label>
            <div class="relative">
                <input type="text" wire:model.defer="zona" list="zonas-list"
                    class="w-full h-10 pl-10 pr-3 transition-all duration-150 bg-white border border-gray-300 rounded-lg form-input focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
                    required>
                <span class="absolute text-gray-400 -translate-y-1/2 pointer-events-none left-3 top-1/2">
                    <i class="ti ti-search"></i>
                </span>
                <datalist id="zonas-list">
                    @foreach($zonas as $zona)
                    <option value="{{ $zona }}">
                        @endforeach
                </datalist>
            </div>
            @error('zona')
            <span class="text-xs text-red-500">{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label class="block text-gray-700 dark:text-gray-200">Marca</label>
            <div class="relative">
                <input type="text" wire:model.defer="marca" list="marcas-list"
                    class="w-full h-10 pl-10 pr-3 transition-all duration-150 bg-white border border-gray-300 rounded-lg form-input focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
                    required>
                <span class="absolute text-gray-400 -translate-y-1/2 pointer-events-none left-3 top-1/2">
                    <i class="ti ti-search"></i>
                </span>
                <datalist id="marcas-list">
                    @foreach($marcas as $marca)
                    <option value="{{ $marca }}">
                        @endforeach
                </datalist>
            </div>
            @error('marca')
            <span class="text-xs text-red-500">{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label class="block text-gray-700 dark:text-gray-200">Modelo</label>
            <input type="number" wire:model.defer="modelo" min="2000" max="{{ date('Y') }}"
                class="w-full h-10 px-3 transition-all duration-150 bg-white border border-gray-300 rounded-lg form-input focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
                required>
            @error('modelo')
            <span class="text-xs text-red-500">{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label class="block text-gray-700 dark:text-gray-200">Placas</label>
            <input type="text" wire:model.defer="placas"
                class="w-full h-10 px-3 transition-all duration-150 bg-white border border-gray-300 rounded-lg form-input focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
                required>
            @error('placas')
            <span class="text-xs text-red-500">{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label class="block text-gray-700 dark:text-gray-200">Kilometraje</label>
            <input type="number" wire:model.defer="kms"
                class="w-full h-10 px-3 transition-all duration-150 bg-white border border-gray-300 rounded-lg form-input focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
                required>
            @error('kms')
            <span class="text-xs text-red-500">{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label class="block text-gray-700 dark:text-gray-200">Asignación Punto</label>
            <div class="relative">
                <input type="text" wire:model.defer="asignacion_punto" list="puntos-list"
                    class="w-full h-10 pl-10 pr-3 transition-all duration-150 bg-white border border-gray-300 rounded-lg form-input focus:border-blue-600 focus:ring-2 focus:ring-blue-100">
                <span class="absolute text-gray-400 -translate-y-1/2 pointer-events-none left-3 top-1/2">
                    <i class="ti ti-search"></i>
                </span>
                <datalist id="puntos-list">
                    @foreach($puntos_disponibles as $punto)
                    <option value="{{ $punto }}">
                        @endforeach
                </datalist>
            </div>
            @error('asignacion_punto')
            <span class="text-xs text-red-500">{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label class="block mb-1 text-gray-700 dark:text-gray-200">Estado del Vehículo</label>
            <div class="flex items-center gap-3">
                <span class="text-sm">Inactivo</span>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" wire:model.defer="is_activo" class="sr-only peer">
                    <div
                        class="h-6 transition-all bg-gray-200 rounded-full w-11 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-500 peer peer-checked:bg-blue-600">
                    </div>
                    <div
                        class="absolute w-4 h-4 transition-all bg-white rounded-full shadow left-1 top-1 peer-checked:translate-x-5">
                    </div>
                </label>
                <span class="text-sm">Activo</span>
            </div>
            @error('is_activo')
            <span class="text-xs text-red-500">{{ $message }}</span>
            @enderror
        </div>
        <div class="md:col-span-2">
            <label class="block text-gray-700 dark:text-gray-200">Observaciones</label>
            <textarea wire:model.defer="observaciones"
                class="w-full h-40 px-3 py-2 transition-all duration-150 bg-white border border-gray-300 rounded-lg form-input focus:border-blue-600 focus:ring-2 focus:ring-blue-100"></textarea>
            @error('observaciones')
            <span class="text-xs text-red-500">{{ $message }}</span>
            @enderror
        </div>
        <div class="flex gap-2 mt-2 md:col-span-2">
            <div class="flex justify-end w-full gap-2">
                <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                    <i class="ti {{ $modo == 'crear' ? 'ti-plus' : 'ti-edit' }} mr-2"></i>
                    {{ $modo == 'crear' ? 'Agregar' : 'Actualizar' }}
                </button>
                <button type="button" wire:click="resetCampos"
                    onclick="window.history.replaceState({}, '', window.location.pathname);"
                    class="px-4 py-2 text-gray-500 transition bg-transparent border border-gray-300 rounded hover:bg-gray-100 hover:text-gray-700">Cancelar</button>
            </div>
        </div>
    </form>
    @else
    <div class="flex items-center justify-between mb-4">
        <div class="flex flex-wrap items-center gap-4">
            <div>
                <label for="perPage" class="mr-2 text-gray-700 dark:text-gray-200">Mostrar:</label>
                <select wire:model.live="perPage" id="perPage" class="px-2 py-1 rounded form-select">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="1000">Todos</option>
                </select>
            </div>
            <div>
                <label for="filtro_punto" class="mr-2 text-gray-700 dark:text-gray-200">Filtrar por punto:</label>
                <select wire:model.live="filtro_punto" id="filtro_punto" class="px-2 py-1 rounded form-select">
                    <option value="">Todos</option>
                    @foreach($puntos_disponibles as $punto)
                    <option value="{{ $punto }}">{{ $punto }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="filtro_placas" class="mr-2 text-gray-700 dark:text-gray-200">Filtrar por placas:</label>
                <input type="text" wire:model.live.100ms="filtro_placas" id="filtro_placas"
                    class="px-2 py-1 rounded form-input" placeholder="Buscar placas">
            </div>
        </div>
        <button wire:click="mostrarFormularioCrear"
            class="flex items-center gap-2 px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700" type="button">
            <i class="ti ti-plus"></i>
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
                            class="inline-flex items-center px-2 py-2 text-base font-semibold text-green-700 bg-green-100 rounded-full">
                            <i class="mr-2 ti ti-circle-check"></i> Activo
                        </span>
                        @else
                        <span
                            class="inline-flex items-center px-2 py-2 text-base font-semibold text-red-700 bg-red-100 rounded-full">
                            <i class="mr-2 ti ti-circle-x"></i> Inactivo
                        </span>
                        @endif
                    </td>
                    <td class="flex justify-center gap-2 px-4 py-2">
                        <a href="{{ route('vehiculos.detalle', ['id' => $unidad->id]) }}"
                            class="flex items-center justify-center p-2 text-white bg-blue-500 rounded hover:bg-blue-700"
                            title="Ver Detalle">
                            <i class="ti ti-eye"></i>
                        </a>
                        <button wire:click="editarUnidad({{ $unidad->id }})"
                            class="flex items-center justify-center p-2 text-white bg-blue-400 rounded hover:bg-blue-500"
                            title="Editar">
                            <i class="ti ti-edit"></i>
                        </button>
                        <button wire:click="eliminarUnidad({{ $unidad->id }})"
                            class="flex items-center justify-center p-2 text-white bg-red-600 rounded hover:bg-red-700"
                            onclick="return confirm('¿Seguro que deseas eliminar esta unidad?')" title="Eliminar">
                            <i class="ti ti-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="px-4 py-8 text-center">
                        <div class="flex flex-col items-center justify-center gap-2">
                            <span
                                class="inline-flex items-center justify-center w-16 h-16 text-4xl text-gray-400 bg-gray-100 rounded-full shadow">
                                <i class="ti ti-car-off"></i>
                            </span>
                            <h3 class="mt-2 text-lg font-semibold text-gray-700 dark:text-gray-200">No hay vehículos
                                registrados</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Agrega un nuevo vehículo para comenzar a
                                gestionar la flotilla y su historial.</p>
                            <button wire:click="mostrarFormularioCrear" type="button"
                                class="flex items-center gap-2 px-4 py-2 mt-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                                <i class="ti ti-plus"></i> Agregar Vehículo
                            </button>
                        </div>
                    </td>
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
</x-livewire.monitoreo-layout>