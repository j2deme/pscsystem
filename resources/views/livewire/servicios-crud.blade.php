<div class="py-6 mx-auto">
    <x-navbar />
    <x-livewire.monitoreo-layout :breadcrumb-items="$breadcrumbItems" :title-main="$titleMain" :help-text="$helpText">
        <div class="container mx-auto">
            @if (session()->has('success'))
            @php
            $msg = session('success');
            $isDelete = str_contains(strtolower($msg), 'eliminad');
            @endphp
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                class="relative px-4 py-3 mb-4 {{ $isDelete ? 'text-red-900 bg-red-100 border-red-500' : 'text-green-900 bg-green-100 border-green-500' }} border-t-4 rounded-b shadow-md"
                role="alert" @keydown.escape.window="show = false">
                <div class="flex items-center gap-2">
                    <i
                        class="ti {{ $isDelete ? 'ti-circle-x text-red-600' : 'ti-circle-check text-green-600' }} text-lg"></i>
                    <p class="text-sm">{{ $msg }}</p>
                </div>
                <button type="button" @click="show = false"
                    class="absolute text-xl leading-none top-2 right-2 focus:outline-none">&times;</button>
            </div>
            @endif

            @if($showForm)
            <div class="flex items-center gap-2 mb-4">
                <span class="inline-flex items-center justify-center w-8 h-8 text-xl rounded-full" @if($editId)
                    style="background-color: #FEF3C7; color: #CA8A04;" @else
                    style="background-color: #DBEAFE; color: #2563EB;" @endif>
                    <i class="ti {{ $editId ? 'ti-edit' : 'ti-plus' }}"></i>
                </span>
                <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                    {{ $editId ? 'Editar servicio' : 'Agregar servicio' }}
                </h3>
            </div>

            <form wire:submit.prevent="save" class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-2">
                <!-- 1a fila: Placa, Tipo de Servicio y Fecha -->
                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-gray-700 dark:text-gray-200" for="placa-select">Placas de la
                            unidad</label>
                        <select id="placa-select" wire:model.defer="form.unidad_id"
                            class="w-full h-10 px-3 transition-all duration-150 bg-white border border-gray-300 rounded-lg form-input focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
                            required>
                            <option value="">Selecciona una placa...</option>
                            @foreach($placasDisponibles as $placa)
                            <option value="{{ $placa['unidad_id'] }}">{{ $placa['numero'] }}: {{ $placa['marca'] }} ({{
                                $placa['modelo'] }})</option>
                            @endforeach
                        </select>
                        @error('form.unidad_id')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 dark:text-gray-200" for="tipo-select">¿Qué tipo de servicio se
                            realizó?</label>
                        <select id="tipo-select" wire:model.defer="form.tipo"
                            class="w-full h-10 px-3 transition-all duration-150 bg-white border border-gray-300 rounded-lg form-input focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
                            required>
                            <option value="">Selecciona el tipo...</option>
                            <option value="Preventivo">Preventivo</option>
                            <option value="Correctivo">Correctivo</option>
                            <option value="Incidencia">Incidencia</option>
                            <option value="Otros">Otros</option>
                        </select>
                        @error('form.tipo')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 dark:text-gray-200" for="fecha-input">Fecha</label>
                        <input type="date" id="fecha-input" wire:model.defer="form.fecha"
                            class="w-full h-10 px-3 transition-all duration-150 bg-white border border-gray-300 rounded-lg form-input focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
                            required>
                        @error('form.fecha')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
                    </div>
                </div>
                <!-- 2a fila: Costo y Responsable/Taller -->
                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 dark:text-gray-200" for="costo-input">Costo</label>
                        <div class="relative">
                            <span
                                class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                                <i class="ti ti-currency-dollar"></i>
                            </span>
                            <input type="number" id="costo-input" step="0.01" wire:model.defer="form.costo"
                                class="w-full h-10 pl-9 pr-3 transition-all duration-150 bg-white border border-gray-300 rounded-lg form-input focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
                                placeholder="0.00">
                        </div>
                        @error('form.costo')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 dark:text-gray-200" for="responsable-input">Lugar de
                            reparación</label>
                        <input type="text" id="responsable-input" wire:model.defer="form.responsable"
                            list="responsables-list"
                            class="w-full h-10 px-3 transition-all duration-150 bg-white border border-gray-300 rounded-lg form-input focus:border-blue-600 focus:ring-2 focus:ring-blue-100">
                        <datalist id="responsables-list">
                            @foreach($responsablesDisponibles as $resp)
                            <option value="{{ $resp }}">
                                @endforeach
                        </datalist>
                        @error('form.responsable')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
                    </div>
                </div>
                <!-- Descripción y Observaciones en una sola fila -->
                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 dark:text-gray-200" for="descripcion-input">Descripción del
                            servicio</label>
                        <textarea id="descripcion-input" wire:model.defer="form.descripcion"
                            class="w-full h-32 px-3 py-2 transition-all duration-150 bg-white border border-gray-300 rounded-lg form-input focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
                            required></textarea>
                        @error('form.descripcion')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 dark:text-gray-200" for="observaciones-input">Observaciones
                            adicionales</label>
                        <textarea id="observaciones-input" wire:model.defer="form.observaciones"
                            class="w-full h-32 px-3 py-2 transition-all duration-150 bg-white border border-gray-300 rounded-lg form-input focus:border-blue-600 focus:ring-2 focus:ring-blue-100"></textarea>
                        @error('form.observaciones')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="flex gap-2 mt-2 md:col-span-2">
                    <div class="flex justify-end w-full gap-2">
                        <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                            <i class="ti {{ $editId ? 'ti-edit' : 'ti-plus' }} mr-2"></i>
                            {{ $editId ? 'Actualizar' : 'Agregar' }}
                        </button>
                        <button type="button" wire:click="cancelarForm"
                            class="px-4 py-2 text-gray-500 transition bg-transparent border border-gray-300 rounded hover:bg-gray-100 hover:text-gray-700">Cancelar</button>
                    </div>
                </div>
            </form>
            @else
            <div class="flex justify-between items-center mb-4">
                <div class="flex flex-wrap items-center gap-4 w-full">
                    <div class="flex gap-4 w-full">
                        <div>
                            <label for="perPage" class="mr-2 text-gray-700 dark:text-gray-200">Mostrar:</label>
                            <select wire:model.live="perPage" id="perPage" class="px-2 py-1 rounded form-select">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="1000">Todos</option>
                            </select>
                        </div>
                        <div class="flex-grow"></div>
                        <div class="flex items-center justify-end">
                            <button wire:click="showCreateForm"
                                class="flex items-center gap-2 px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700"
                                type="button">
                                <i class="ti ti-plus"></i>
                                Agregar servicio
                            </button>
                        </div>
                    </div>
                    <!-- Filtros de fecha en segunda fila -->
                    <div class="flex gap-4 w-full mt-1">
                        <div>
                            <label for="filtro_unidad" class="mr-2 text-gray-700 dark:text-gray-200">Filtrar por
                                unidad:</label>
                            <select wire:model.live="filtro_unidad" id="filtro_unidad"
                                class="px-2 py-1 rounded form-select">
                                <option value="">Todas</option>
                                @foreach($placasDisponibles as $placa)
                                <option value="{{ $placa['unidad_id'] }}">{{ $placa['numero'] }}: {{ $placa['marca'] }}
                                    ({{ $placa['modelo'] }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="filtro_tipo" class="mr-2 text-gray-700 dark:text-gray-200">Filtrar por
                                tipo:</label>
                            <select wire:model.live="filtro_tipo" id="filtro_tipo"
                                class="px-2 py-1 rounded form-select">
                                <option value="">Todos</option>
                                <option value="Preventivo">Preventivo</option>
                                <option value="Correctivo">Correctivo</option>
                                <option value="Incidencia">Incidencia</option>
                                <option value="Otros">Otros</option>
                            </select>
                        </div>
                        <div>
                            <label for="filtro_fecha_inicio" class="mr-2 text-gray-700 dark:text-gray-200">Fecha
                                inicio:</label>
                            <input type="date" wire:model.live="filtro_fecha_inicio" id="filtro_fecha_inicio"
                                class="px-2 py-1 rounded form-input">
                        </div>
                        <div>
                            <label for="filtro_fecha_fin" class="mr-2 text-gray-700 dark:text-gray-200">Fecha
                                fin:</label>
                            <input type="date" wire:model.live="filtro_fecha_fin" id="filtro_fecha_fin"
                                class="px-2 py-1 rounded form-input">
                        </div>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded shadow dark:bg-gray-800">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Unidad</th>
                            <th class="px-4 py-2">Fecha</th>
                            <th class="px-4 py-2">Descripción</th>
                            <th class="px-4 py-2">Costo</th>
                            <th class="px-4 py-2">Tipo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($servicios as $servicio)
                        <tr class="border-t">
                            <td class="px-4 py-2">
                                @php
                                $unidad = collect($placasDisponibles)->firstWhere('unidad_id', $servicio->unidad_id);
                                @endphp
                                @if($unidad)
                                <a href="{{ route('vehiculos.detalle', ['id' => $servicio->unidad_id]) }}"
                                    class="transition text-gray-800 hover:text-blue-600 hover:underline font-semibold">
                                    {{ $unidad['numero'] }}: {{ $unidad['marca'] }} ({{ $unidad['modelo'] }})
                                </a>
                                @else
                                <a href="{{ route('vehiculos.detalle', ['id' => $servicio->unidad_id]) }}"
                                    class="transition text-gray-800 hover:text-blue-600 hover:underline font-semibold">
                                    Unidad #{{ $servicio->unidad_id }}
                                </a>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-center">{{ $servicio->fecha->format('d-m-Y') }}</td>
                            <td class="px-4 py-2">
                                <button type="button"
                                    class="block max-w-xs truncate cursor-pointer hover:underline text-left w-full bg-transparent border-none p-0"
                                    title="{{ $servicio->descripcion }}" onclick="showDescModal(this)"
                                    data-desc="{{ $servicio->descripcion }}">
                                    {{ $servicio->descripcion }}
                                </button>
                            </td>
                            <td class="px-4 py-2 text-right">
                                @if($servicio->costo == 0)
                                &ndash;
                                @else
                                ${{ number_format($servicio->costo, 2) }}
                                @endif
                            </td>
                            <td class="px-4 py-2 text-center">
                                @php
                                $tipo = $servicio->tipo;
                                $estilo = $estilos[$tipo] ?? $estilos['Otros'];
                                @endphp
                                <span
                                    class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $estilo['badge'] }}">
                                    <i class="ti {{ $estilo['icon'] }} {{ $estilo['iconColor'] }} mr-1"></i>
                                    {{ $tipo }}
                                </span>
                            </td>
                            <td class="flex justify-center gap-2 px-4 py-2">
                                <a href="{{ route('servicio.detalle', ['id' => $servicio->id]) }}"
                                    class="flex items-center justify-center p-2 text-gray-700 bg-gray-100 border border-gray-300 rounded hover:bg-gray-200"
                                    title="Ver detalle">
                                    <i class="ti ti-eye"></i>
                                </a>
                                <button wire:click="editarServicio({{ $servicio->id }})"
                                    class="flex items-center justify-center p-2 text-white bg-blue-400 rounded hover:bg-blue-500"
                                    title="Editar">
                                    <i class="ti ti-edit"></i>
                                </button>
                                <button wire:click="eliminarServicio({{ $servicio->id }})"
                                    class="flex items-center justify-center p-2 text-white bg-red-600 rounded hover:bg-red-700"
                                    onclick="return confirm('¿Seguro que deseas eliminar este servicio?')"
                                    title="Eliminar">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center">
                                <div class="flex flex-col items-center justify-center gap-4 py-8">
                                    <span
                                        class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100">
                                        <i class="ti ti-tool text-4xl text-gray-400"></i>
                                    </span>
                                    <span class="text-lg font-semibold text-gray-700">No hay servicios
                                        registrados</span>
                                    <span class="text-sm text-gray-500">Agrega un nuevo servicio para comenzar a
                                        gestionar el historial de reparaciones y servicios.</span>
                                    <button wire:click="showCreateForm" type="button"
                                        class="mt-2 px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 flex items-center gap-2">
                                        <i class="ti ti-plus"></i>
                                        Agregar servicio
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        @if($servicios->sum('costo') > 0)
                        <tr>
                            <td colspan="3" class="px-4 py-2 text-right font-bold text-gray-700 dark:text-gray-200">
                                TOTAL
                            </td>
                            <td
                                class="px-4 py-2 text-right font-bold text-gray-700 dark:text-gray-200 border-b-blue-500 border-b-2">
                                ${{ number_format($servicios->sum('costo'), 2) }}
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                        @endif
                    </tfoot>
                </table>
                <div class="mt-4">
                    @if($servicios->hasPages())
                    {{ $servicios->links() }}
                    @endif
                </div>
            </div>
            @endif
            <!-- Modal para mostrar descripción completa -->
            <div id="modalDesc"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
                <div
                    class="bg-white dark:bg-gray-900 rounded-xl shadow-lg max-w-md w-full p-6 relative border border-gray-200 dark:border-gray-700">
                    <button type="button" id="closeDescModalBtn"
                        class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300"
                        title="Cerrar">
                        <i class="ti ti-x"></i>
                    </button>
                    <div class="mb-4 text-lg font-bold text-blue-700 dark:text-blue-300 flex items-center gap-2">
                        <i class="ti ti-file-description"></i> Descripción completa
                    </div>
                    <div class="text-gray-800 dark:text-gray-200 whitespace-pre-line text-base" id="modalDescText">
                    </div>
                </div>
            </div>
            <script>
                function showDescModal(btn) {
                            var modal = document.getElementById('modalDesc');
                            var text = document.getElementById('modalDescText');
                            text.textContent = btn.getAttribute('data-desc');
                            modal.classList.remove('hidden');
                        }
                        document.getElementById('closeDescModalBtn').addEventListener('click', function() {
                            document.getElementById('modalDesc').classList.add('hidden');
                        });
                        document.getElementById('modalDesc').addEventListener('click', function(e) {
                            if (e.target === this) {
                                this.classList.add('hidden');
                            }
                        });
            </script>
        </div>
    </x-livewire.monitoreo-layout>
</div>