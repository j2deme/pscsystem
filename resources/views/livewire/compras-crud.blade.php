<div class="py-6 mx-auto">
  <x-navbar />

  <x-livewire.monitoreo-layout :breadcrumb-items="$breadcrumbItems" :title-main="$titleMain" :help-text="$helpText">

    <div class="container mx-auto">

      {{-- Mensajes de éxito --}}
      @if (session()->has('message'))
      <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
        class="relative px-4 py-3 mb-4 text-green-900 bg-green-100 border-t-4 border-green-500 rounded-b shadow-md"
        role="alert" @keydown.escape.window="show = false">
        <div class="flex items-center gap-2">
          <i class="text-lg ti ti-circle-check text-green-600"></i>
          <p class="text-sm">{{ session('message') }}</p>
        </div>
        <button type="button" @click="show = false"
          class="absolute text-xl leading-none top-2 right-2 focus:outline-none">&times;</button>
      </div>
      @endif

      {{-- Formulario de Creación/Edición --}}
      @if($showForm)
      <div class="flex items-center gap-2 mb-4">
        <span class="inline-flex items-center justify-center w-8 h-8 text-xl rounded-full" @if($editId)
          style="background-color: #FEF3C7; color: #CA8A04;" @else style="background-color: #DBEAFE; color: #2563EB;"
          @endif>
          <i class="ti {{ $editId ? 'ti-edit' : 'ti-plus' }}"></i>
        </span>
        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
          {{ $editId ? 'Editar Compra/Servicio' : 'Registrar Compra/Servicio' }}
        </h3>
      </div>

      <form wire:submit.prevent="save" class="grid grid-cols-1 gap-4 mb-6">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
          <div>
            <label class="block mb-1 text-gray-700 dark:text-gray-200" for="unidad_id">Unidad</label>
            <select id="unidad_id" wire:model.defer="form.unidad_id"
              class="w-full h-10 px-3 bg-white border border-gray-300 rounded-lg form-input focus:border-blue-600 focus:ring-2 focus:ring-blue-100 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
              <option value="">Sin unidad asociada...</option>
              @foreach($unidadesDisponibles as $unidad)
              <option value="{{ $unidad['id'] }}">
                {{ $unidad['placas'] }}: {{ $unidad['marca'] }} {{ $unidad['modelo'] }}
              </option>
              @endforeach
            </select>
            @error('form.unidad_id')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
          </div>

          <div>
            <label class="block mb-1 text-gray-700 dark:text-gray-200" for="fecha_hora">Fecha y Hora *</label>
            <input type="datetime-local" id="fecha_hora" wire:model.defer="form.fecha_hora"
              class="w-full h-10 px-3 bg-white border border-gray-300 rounded-lg form-input focus:border-blue-600 focus:ring-2 focus:ring-blue-100 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200"
              required>
            @error('form.fecha_hora')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
          </div>

          <div>
            <label class="block mb-1 text-gray-700 dark:text-gray-200" for="tipo">Tipo *</label>
            <select id="tipo" wire:model.defer="form.tipo"
              class="w-full h-10 px-3 bg-white border border-gray-300 rounded-lg form-select focus:border-blue-600 focus:ring-2 focus:ring-blue-100 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200"
              required>
              <option value="">Selecciona un tipo...</option>
              @foreach($tiposDisponibles as $tipo)
              <option value="{{ $tipo }}">{{ $tipo }}</option>
              @endforeach
            </select>
            @error('form.tipo')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
          </div>

          <div>
            <label class="block mb-1 text-gray-700 dark:text-gray-200" for="proveedor">Proveedor</label>
            <input type="text" id="proveedor" wire:model.defer="form.proveedor" maxlength="255"
              class="w-full h-10 px-3 bg-white border border-gray-300 rounded-lg form-input focus:border-blue-600 focus:ring-2 focus:ring-blue-100 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200"
              placeholder="Nombre del proveedor o responsable">
            @error('form.proveedor')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
          </div>

          <div>
            <label class="block mb-1 text-gray-700 dark:text-gray-200" for="costo">Costo</label>
            <div class="relative">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                <i class="ti ti-currency-dollar"></i>
              </span>
              <input type="number" id="costo" step="0.01" min="0" wire:model.defer="form.costo"
                class="w-full h-10 pl-10 pr-3 bg-white border border-gray-300 rounded-lg form-input focus:border-blue-600 focus:ring-2 focus:ring-blue-100 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200"
                placeholder="0.00">
            </div>
            @error('form.costo')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
          </div>

          <div>
            <label class="block mb-1 text-gray-700 dark:text-gray-200" for="kilometraje">Kilometraje</label>
            <input type="number" id="kilometraje" wire:model.defer="form.kilometraje" min="0"
              class="w-full h-10 px-3 bg-white border border-gray-300 rounded-lg form-input focus:border-blue-600 focus:ring-2 focus:ring-blue-100 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200"
              placeholder="Kilometraje del vehículo">
            @error('form.kilometraje')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
          </div>
        </div>

        <div class="grid grid-cols-1 gap-4">
          <div>
            <label class="block mb-1 text-gray-700 dark:text-gray-200" for="descripcion">Descripción *</label>
            <textarea id="descripcion" wire:model.defer="form.descripcion" rows="3"
              class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg form-input focus:border-blue-600 focus:ring-2 focus:ring-blue-100 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200"
              placeholder="Descripción detallada del servicio o refacción..." required></textarea>
            @error('form.descripcion')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
          </div>

          <div>
            <label class="block mb-1 text-gray-700 dark:text-gray-200" for="notas">Notas</label>
            <textarea id="notas" wire:model.defer="form.notas" rows="2" maxlength="1000"
              class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg form-input focus:border-blue-600 focus:ring-2 focus:ring-blue-100 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200"
              placeholder="Información adicional, comentarios, etc."></textarea>
            @error('form.notas')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
          </div>

          <div class="flex items-center">
            <input type="checkbox" id="garantia" wire:model.defer="form.garantia"
              class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label for="garantia" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
              ¿Este servicio/refacción se realizó bajo garantía?
            </label>
          </div>
        </div>

        <div class="flex gap-2 mt-2">
          <div class="flex justify-end w-full gap-2">
            <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
              <i class="ti {{ $editId ? 'ti-edit' : 'ti-plus' }} mr-2"></i>
              {{ $editId ? 'Actualizar' : 'Registrar' }}
            </button>
            <button type="button" wire:click="cancelarForm"
              class="px-4 py-2 text-gray-500 transition bg-transparent border border-gray-300 rounded hover:bg-gray-100 hover:text-gray-700">
              Cancelar
            </button>
          </div>
        </div>
      </form>
      @endif

      {{-- Listado y Filtros --}}
      @if(!$showForm)
      <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
        {{-- Filtros Izquierda --}}
        <div class="flex flex-wrap items-center w-full gap-4">
          <div class="flex w-full gap-4">
            <div>
              <label for="perPage" class="mr-2 text-gray-700 dark:text-gray-200">Mostrar:</label>
              <select wire:model.live="perPage" id="perPage"
                class="px-2 py-1 rounded form-select focus:border-blue-600 focus:ring-2 focus:ring-blue-100 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select>
            </div>
            <div class="flex-grow"></div>
            <div class="flex items-center justify-end">
              <button wire:click="showCreateForm"
                class="flex items-center gap-2 px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700"
                type="button">
                <i class="ti ti-plus"></i>
                Registrar Compra/Servicio
              </button>
            </div>
          </div>
          <div class="flex flex-wrap w-full gap-4 mt-1">
            <div>
              <label for="filtro_fecha_inicio" class="mr-2 text-gray-700 dark:text-gray-200">Fecha inicio:</label>
              <input type="date" wire:model.live="filtro_fecha_inicio" id="filtro_fecha_inicio"
                class="px-2 py-1 rounded form-input focus:border-blue-600 focus:ring-2 focus:ring-blue-100 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
            </div>
            <div>
              <label for="filtro_fecha_fin" class="mr-2 text-gray-700 dark:text-gray-200">Fecha fin:</label>
              <input type="date" wire:model.live="filtro_fecha_fin" id="filtro_fecha_fin"
                class="px-2 py-1 rounded form-input focus:border-blue-600 focus:ring-2 focus:ring-blue-100 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
            </div>
            <div>
              <label for="filtro_unidad" class="mr-2 text-gray-700 dark:text-gray-200">Unidad:</label>
              <select wire:model.live="filtro_unidad" id="filtro_unidad"
                class="px-2 py-1 rounded form-select focus:border-blue-600 focus:ring-2 focus:ring-blue-100 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
                <option value="">Todas</option>
                @foreach($unidadesDisponibles as $unidad)
                <option value="{{ $unidad['id'] }}">
                  {{ $unidad['placas'] }}: {{ $unidad['marca'] }} {{ $unidad['modelo'] }}
                </option>
                @endforeach
              </select>
            </div>
            <div>
              <label for="filtro_tipo" class="mr-2 text-gray-700 dark:text-gray-200">Tipo:</label>
              <select wire:model.live="filtro_tipo" id="filtro_tipo"
                class="px-2 py-1 rounded form-select focus:border-blue-600 focus:ring-2 focus:ring-blue-100 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
                <option value="">Todos</option>
                @foreach($tiposDisponibles as $tipo)
                <option value="{{ $tipo }}">{{ $tipo }}</option>
                @endforeach
              </select>
            </div>
            <div>
              <label for="filtro_proveedor" class="mr-2 text-gray-700 dark:text-gray-200">Proveedor:</label>
              <input type="text" wire:model.live="filtro_proveedor" id="filtro_proveedor" placeholder="Buscar..."
                class="px-2 py-1 rounded form-input focus:border-blue-600 focus:ring-2 focus:ring-blue-100 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
            </div>
            <div>
              <label for="filtro_garantia" class="mr-2 text-gray-700 dark:text-gray-200">Garantía:</label>
              <select wire:model.live="filtro_garantia" id="filtro_garantia"
                class="px-2 py-1 rounded form-select focus:border-blue-600 focus:ring-2 focus:ring-blue-100 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
                <option value="">Todas</option>
                <option value="1">Con Garantía</option>
                <option value="0">Sin Garantía</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded shadow table-auto dark:bg-gray-800" style="table-layout: fixed;">
          <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
              <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-200 w-32">Fecha/Hora</th>
              <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-200 w-48">Unidad</th>
              <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-200 w-32">Tipo</th>
              <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-200 flex-1">Descripción</th>
              <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-200 w-40">Proveedor</th>
              <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-200 w-32">Costo</th>
              <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-200 w-24">Garantía</th>
              <th class="px-4 py-2 text-center text-gray-700 dark:text-gray-200 w-24">Acciones</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse ($compras as $compra)
            <tr wire:key='compra-{{ $compra->id }}' class="hover:bg-gray-50 dark:hover:bg-gray-750">
              <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200 whitespace-nowrap">
                {{ $compra->fecha_hora->format('d/m/Y H:i') }}
                @if($compra->kilometraje)
                <br><span class="text-xs text-gray-500">Km: {{ number_format($compra->kilometraje, 0) }}</span>
                @endif
              </td>
              <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200">
                @if($compra->unidad)
                <div class="font-medium">{{ $compra->unidad->placas }}</div>
                <div class="text-xs text-gray-500">{{ $compra->unidad->marca }} {{ $compra->unidad->modelo }}</div>
                @else
                <span class="text-gray-400">Sin unidad</span>
                @endif
              </td>
              <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200">
                @php
                $tipo = $compra->tipo;
                $icono = match($tipo) {
                'Refacción' => 'ti-bolt',
                'Insumo' => 'ti-box',
                'Servicio Menor' => 'ti-asset',
                'Servicio Mayor' => 'ti-engine',
                'Compra Directa' => 'ti-shopping-cart',
                'Mantenimiento' => 'ti-gauge',
                'Reparación' => 'ti-tool',
                'Verificación' => 'ti-checklist',
                'Afinación' => 'ti-adjustments',
                'Cambio de Llantas' => 'ti-car-4wd-filled',
                'Hojalatería y Pintura' => 'ti-spray',
                'Siniestro' => 'ti-car-crash',
                default => 'ti-basket-plus'
                };

                // Asignación de colores por categoría semántica
                [$bgColor, $textColor] = match($tipo) {
                'Mantenimiento', 'Afinación', 'Servicio Menor', 'Servicio Mayor' => ['bg-blue-100', 'text-blue-800'],
                'Reparación', 'Hojalatería y Pintura' => ['bg-green-100', 'text-green-800'],
                'Siniestro' => ['bg-red-100', 'text-red-800'],
                'Refacción', 'Insumo', 'Compra Directa', 'Cambio de Llantas' => ['bg-yellow-100', 'text-yellow-800'],
                'Verificación' => ['bg-purple-100', 'text-purple-800'],
                default => ['bg-gray-100', 'text-gray-800']
                };
                @endphp
                <span
                  class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full {{ $bgColor }} {{ $textColor }} dark:bg-opacity-20 dark:text-opacity-90">
                  <i class="mr-1 text-sm ti {{ $icono }}"></i>
                  {{ $tipo }}
                </span>
              </td>
              <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200 max-w-xs">
                <button type="button"
                  class="block w-full max-w-xs p-0 text-sm text-left text-gray-700 truncate bg-transparent border-none cursor-pointer hover:underline"
                  title="{{ $compra->descripcion }}" onclick="showDescModal(this)"
                  data-desc="{{ $compra->descripcion }}">
                  {{ Str::limit($compra->descripcion, 50) }}
                </button>
                @if($compra->notas)
                <div class="mt-1 text-xs text-gray-500 italic">
                  <i class="ti ti-notes"></i> {{ Str::limit($compra->notas, 30) }}
                </div>
                @endif
              </td>
              <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200">
                {{ $compra->proveedor ?? 'N/A' }}
              </td>
              <td class="px-4 py-2 text-sm font-medium text-gray-900 dark:text-white text-right">
                @if($compra->costo !== null)
                ${{ number_format($compra->costo, 2) }}
                @else
                <span class="text-gray-400">N/A</span>
                @endif
              </td>
              <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200 text-center items-center">
                @if($compra->garantia)
                <span
                  class="inline-flex items-center px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full dark:bg-green-900 dark:text-green-100">
                  <i class="ti ti-shield-check mr-1"></i> Sí
                </span>
                @else
                <span class="text-xs text-gray-400">No</span>
                @endif
              </td>
              <td class="flex justify-center gap-2 px-4 py-2">
                <a href="{{ route('compras.detalle', $compra->id) }}"
                  class="flex items-center justify-center p-2 text-gray-700 bg-gray-100 border border-gray-300 rounded hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600"
                  title="Ver detalle">
                  <i class="ti ti-eye"></i>
                </a>
                <button wire:click="editarCompra({{ $compra->id }})"
                  class="flex items-center justify-center p-2 text-white bg-blue-400 rounded hover:bg-blue-500"
                  title="Editar">
                  <i class="ti ti-edit"></i>
                </button>
                <button wire:click="eliminarCompra({{ $compra->id }})"
                  class="flex items-center justify-center p-2 text-white bg-red-600 rounded hover:bg-red-700"
                  onclick="return confirm('¿Seguro que deseas eliminar este registro?')" title="Eliminar">
                  <i class="ti ti-trash"></i>
                </button>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="8" class="px-4 py-8 text-center">
                <div class="flex flex-col items-center justify-center gap-4 py-8">
                  <span
                    class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full dark:bg-gray-700">
                    <i class="text-4xl text-gray-400 ti ti-shopping-cart-off"></i>
                  </span>
                  <span class="text-lg font-semibold text-gray-700 dark:text-gray-200">No hay registros de
                    compras/servicios</span>
                  <span class="text-sm text-gray-500 dark:text-gray-400">Agrega un nuevo registro para comenzar a
                    gestionar
                    las compras y servicios de las unidades.</span>
                  <button wire:click="showCreateForm" type="button"
                    class="flex items-center gap-2 px-5 py-2 mt-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                    <i class="ti ti-plus"></i>
                    Registrar Compra/Servicio
                  </button>
                </div>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
        <div class="mt-4">
          @if($compras->hasPages())
          {{ $compras->links('vendor.pagination.tailwind') }}
          @endif
        </div>
      </div>
      @endif

      {{-- Modal para descripción larga --}}
      <div id="modalDesc" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-40">
        <div
          class="relative w-full max-w-md p-6 bg-white border border-gray-200 shadow-lg dark:bg-gray-900 rounded-xl dark:border-gray-700">
          <button type="button" id="closeDescModalBtn"
            class="absolute text-gray-500 top-3 right-3 hover:text-gray-700 dark:hover:text-gray-300" title="Cerrar">
            <i class="ti ti-x"></i>
          </button>
          <div class="flex items-center gap-2 mb-4 text-lg font-bold text-blue-700 dark:text-blue-300">
            <i class="text-blue-600 ti ti-file-description dark:text-blue-300"></i> Descripción completa
          </div>
          <div class="text-base text-gray-800 whitespace-pre-line dark:text-gray-200" id="modalDescText">
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
                document.getElementById('closeDescModalBtn').addEventListener('click', function () {
                    document.getElementById('modalDesc').classList.add('hidden');
                });
                document.getElementById('modalDesc').addEventListener('click', function (e) {
                    if (e.target === this) {
                        this.classList.add('hidden');
                    }
                });
      </script>
    </div>
  </x-livewire.monitoreo-layout>
</div>