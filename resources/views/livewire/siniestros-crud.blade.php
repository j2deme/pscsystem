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
          <i class="ti {{ $isDelete ? 'ti-circle-x text-red-600' : 'ti-circle-check text-green-600' }} text-lg"></i>
          <p class="text-sm">{{ $msg }}</p>
        </div>
        <button type="button" @click="show = false"
          class="absolute text-xl leading-none top-2 right-2 focus:outline-none">&times;</button>
      </div>
      @endif

      @if($showForm)
      <div class="flex items-center gap-2 mb-4">
        <span class="inline-flex items-center justify-center w-8 h-8 text-xl rounded-full" @if($editId)
          style="background-color: #FEF3C7; color: #CA8A04;" @else style="background-color: #DBEAFE; color: #2563EB;"
          @endif>
          <i class="ti {{ $editId ? 'ti-edit' : 'ti-plus' }}"></i>
        </span>
        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
          {{ $editId ? 'Editar siniestro' : 'Agregar siniestro' }}
        </h3>
      </div>

      <form wire:submit.prevent="save" class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-2">
        <div class="grid grid-cols-1 gap-4 md:col-span-2 md:grid-cols-3">
          <div>
            <label class="block text-gray-700 dark:text-gray-200" for="fecha">Fecha</label>
            <input type="date" id="fecha" wire:model.defer="form.fecha"
              class="w-full h-10 px-3 bg-white border border-gray-300 rounded-lg form-input focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
              required>
            @error('form.fecha')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
          </div>
          <div>
            <label class="block text-gray-700 dark:text-gray-200" for="tipo_siniestro">Reporte para...</label>
            <div class="flex gap-4">
              <div class="inline-flex overflow-hidden border border-blue-500 rounded-lg">
                <button type="button"
                  class="flex items-center gap-2 px-4 py-2 focus:outline-none transition-all border-r border-blue-500 {{ $form['tipo_siniestro'] === 'vehiculo' ? 'bg-blue-600 text-white' : 'bg-white text-blue-600' }}"
                  style="border-top-left-radius:0.5rem; border-bottom-left-radius:0.5rem;"
                  wire:click="$set('form.tipo_siniestro', 'vehiculo')">
                  <i class="text-xl ti ti-car-crash"></i>
                  Vehículo
                </button>
                <button type="button"
                  class="flex items-center gap-2 px-4 py-2 focus:outline-none transition-all {{ $form['tipo_siniestro'] === 'personal' ? 'bg-blue-600 text-white' : 'bg-white text-blue-600' }}"
                  style="border-top-right-radius:0.5rem; border-bottom-right-radius:0.5rem;"
                  wire:click="$set('form.tipo_siniestro', 'personal')">
                  <i class="text-xl ti ti-user"></i>
                  Personal
                </button>
              </div>
            </div>
            @error('form.tipo_siniestro')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
          </div>
          @if($form['tipo_siniestro'] === 'vehiculo')
          <div>
            <label class="block text-gray-700 dark:text-gray-200" for="unidad_id">Unidad</label>
            <select id="unidad_id" wire:model.defer="form.unidad_id"
              class="w-full h-10 px-3 bg-white border border-gray-300 rounded-lg form-input focus:border-blue-600 focus:ring-2 focus:ring-blue-100">
              <option value="">Sin unidad...</option>
              @foreach($placasDisponibles as $placa)
              <option value="{{ $placa['unidad_id'] }}">{{ $placa['numero'] }}: {{ $placa['marca'] }} ({{
                $placa['modelo'] }})</option>
              @endforeach
            </select>
            @error('form.unidad_id')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
          </div>
          @endif
        </div>

        <div class="grid grid-cols-1 gap-4 md:col-span-2 md:grid-cols-3">
          <div class="flex-1">
            <label class="block mb-1 text-gray-700 dark:text-gray-200" for="tipo">Tipo</label>
            @php
            $tipos = $form['tipo_siniestro'] === 'vehiculo' ? $tiposVehiculo : ($form['tipo_siniestro'] === 'personal'
            ? $tiposPersonal : []);
            @endphp
            <select id="tipo" wire:model.live="form.tipo"
              class="w-full h-10 px-3 bg-white border border-gray-300 rounded-lg form-input focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
              required>
              <option value="">Selecciona...</option>
              @foreach($tipos as $clave => $info)
              <option value="{{ $clave }}">{{ $info['label'] ?? $clave }}</option>
              @endforeach
            </select>
            @error('form.tipo')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
          </div>
          <div class="flex items-center flex-1">
            @php
            $hasTipo = $form['tipo'] && isset($tipos[$form['tipo']]);
            $gravedad = $hasTipo ? strtolower($tipos[$form['tipo']]['gravedad'] ?? '') : '';
            $badgeColor = match($gravedad) {
            'alta' => 'bg-red-100 text-red-700 border-red-300',
            'media' => 'bg-yellow-100 text-yellow-700 border-yellow-300',
            'baja' => 'bg-green-100 text-green-700 border-green-300',
            default => 'bg-gray-100 text-gray-700 border-gray-300',
            };
            $borderColor = match($gravedad) {
            'alta' => 'border-red-300',
            'media' => 'border-yellow-300',
            'baja' => 'border-green-300',
            default => 'border-gray-300',
            };
            @endphp
            <div
              class="w-full bg-white dark:bg-gray-800 border {{ $borderColor }} rounded-lg shadow p-4 flex flex-col gap-2 min-h-[80px]">
              @if($hasTipo)
              <div class="flex items-center gap-2">
                <span class="flex-1 text-base font-semibold">{{ $tipos[$form['tipo']]['label'] ?? $form['tipo']
                  }}</span>
                <span class="inline-block px-2 py-1 rounded border text-xs font-semibold {{ $badgeColor }} ml-auto">
                  {{ ucfirst($tipos[$form['tipo']]['gravedad'] ?? 'No especificada') }}
                </span>
              </div>
              <div class="text-sm text-gray-600 dark:text-gray-300">
                {{ $tipos[$form['tipo']]['descripcion'] ?? '' }}
              </div>
              @else
              <div class="flex flex-col gap-2 animate-pulse">
                <span class="w-1/2 h-5 bg-gray-200 rounded"></span>
                <span class="w-16 h-4 bg-gray-100 rounded"></span>
                <span class="w-3/4 h-4 bg-gray-100 rounded"></span>
              </div>
              @endif
            </div>
          </div>
          <div class="flex-1">
            <label class="block text-gray-700 dark:text-gray-200" for="zona">Zona</label>
            <input type="text" id="zona" wire:model.defer="form.zona"
              class="w-full h-10 px-3 bg-white border border-gray-300 rounded-lg form-input focus:border-blue-600 focus:ring-2 focus:ring-blue-100">
            @error('form.zona')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
          </div>
        </div>
        <div class="grid grid-cols-1 gap-4 md:col-span-2 md:grid-cols-3">
        </div>

        <div class="grid grid-cols-1 gap-4 md:col-span-2 md:grid-cols-2">
          <div>
            <label class="block text-gray-700 dark:text-gray-200" for="descripcion">Descripción</label>
            <textarea id="descripcion" wire:model.defer="form.descripcion"
              class="w-full h-32 px-3 py-2 bg-white border border-gray-300 rounded-lg form-input focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
              required></textarea>
            @error('form.descripcion')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
          </div>
          <div>
            <label class="block text-gray-700 dark:text-gray-200" for="usuarios">Elementos involucrados</label>
            <select id="usuarios" wire:model.defer="form.usuarios" multiple
              class="w-full h-32 px-3 bg-white border border-gray-300 rounded-lg form-input focus:border-blue-600 focus:ring-2 focus:ring-blue-100">
              @foreach($usuariosDisponibles as $usuario)
              <option value="{{ $usuario['id'] }}">{{ $usuario['name'] }} ({{ $usuario['rol'] }})</option>
              @endforeach
            </select>
            @error('form.usuarios')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
          </div>
        </div>

        @if($form['tipo_siniestro'] === 'vehiculo')
        <div class="mt-8 md:col-span-2">
          <div class="mb-2 text-base font-semibold text-gray-700">Información de cierre</div>
          <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
              <label class="block text-gray-700 dark:text-gray-200" for="seguimiento">Seguimiento</label>
              <textarea id="seguimiento" wire:model.defer="form.seguimiento"
                class="w-full h-32 px-3 py-2 bg-white border border-gray-300 rounded-lg form-input focus:border-blue-600 focus:ring-2 focus:ring-blue-100"></textarea>
              @error('form.seguimiento')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
            </div>
            <div>
              <label class="block text-gray-700 dark:text-gray-200" for="costo">Costo</label>
              <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                  <i class="ti ti-currency-dollar"></i>
                </span>
                <input type="number" id="costo" step="0.01" min="0" wire:model.defer="form.costo"
                  class="w-full h-10 pr-3 bg-white border border-gray-300 rounded-lg pl-9 form-input focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
                  placeholder="0.00">
              </div>
              @error('form.costo')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
            </div>
          </div>
        </div>
        @endif
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
      <div class="flex items-center justify-between mb-4">
        <div class="flex flex-wrap items-center w-full gap-4">
          <div class="flex w-full gap-4">
            <div>
              <label for="perPage" class="mr-2 text-gray-700 dark:text-gray-200">Mostrar:</label>
              <select wire:model.live="perPage" id="perPage"
                class="px-2 py-1 rounded form-select focus:border-blue-600 focus:ring-2 focus:ring-blue-100">
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
                Agregar siniestro
              </button>
            </div>
          </div>
          <div class="flex w-full gap-4 mt-1">
            <div>
              <label for="filtro_unidad" class="mr-2 text-gray-700 dark:text-gray-200">Filtrar por unidad:</label>
              <select wire:model.live="filtro_unidad" id="filtro_unidad"
                class="px-2 py-1 rounded form-select focus:border-blue-600 focus:ring-2 focus:ring-blue-100">
                <option value="">Todas</option>
                @foreach($placasDisponibles as $placa)
                <option value="{{ $placa['unidad_id'] }}">{{ $placa['numero'] }}: {{ $placa['marca'] }} ({{
                  $placa['modelo'] }})</option>
                @endforeach
              </select>
            </div>
            <div>
              <label for="filtro_tipo" class="mr-2 text-gray-700 dark:text-gray-200">Filtrar por tipo:</label>
              <select wire:model.live="filtro_tipo" id="filtro_tipo"
                class="px-2 py-1 rounded form-select focus:border-blue-600 focus:ring-2 focus:ring-blue-100">
                <option value="">Todos</option>
                <option value="vehiculo">Vehículo</option>
                <option value="personal">Personal</option>
              </select>
            </div>
            <div>
              <label for="filtro_fecha_inicio" class="mr-2 text-gray-700 dark:text-gray-200">Fecha inicio:</label>
              <input type="date" wire:model.live="filtro_fecha_inicio" id="filtro_fecha_inicio"
                class="px-2 py-1 rounded form-input focus:border-blue-600 focus:ring-2 focus:ring-blue-100">
            </div>
            <div>
              <label for="filtro_fecha_fin" class="mr-2 text-gray-700 dark:text-gray-200">Fecha fin:</label>
              <input type="date" wire:model.live="filtro_fecha_fin" id="filtro_fecha_fin"
                class="px-2 py-1 rounded form-input focus:border-blue-600 focus:ring-2 focus:ring-blue-100">
            </div>
          </div>
        </div>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded shadow table-fixed dark:bg-gray-800">
          <thead>
            <tr>
              <th class="px-4 py-2">Fecha</th>
              <th class="px-4 py-2">Descripción</th>
              <th class="px-4 py-2">Tipo</th>
              <th class="px-4 py-2">Gravedad</th>
              <th class="px-4 py-2">Unidad / Elementos</th>
              <th class="px-4 py-2">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($siniestros as $siniestro)
            <tr class="border-t" wire:key='siniestro-{{ $siniestro->id }}'>
              <td class="px-4 py-2 text-sm text-center">
                {{ $siniestro->fecha->format('d-m-Y') }}
              </td>
              <td class="px-4 py-2">
                <button type="button"
                  class="block w-full max-w-xs p-0 text-sm text-left text-gray-700 truncate bg-transparent border-none cursor-pointer hover:underline"
                  title="{{ $siniestro->descripcion }}" onclick="showDescModal(this)"
                  data-desc="{{ $siniestro->descripcion }}">
                  {{ $siniestro->descripcion }}
                </button>
              </td>
              <td class="px-4 py-2 text-center">
                @php
                $tipo = strtolower($siniestro->tipo_siniestro);
                $icono = $tipo === 'vehiculo' ? 'ti-car-crash' : 'ti-user';
                $label = $tipo === 'vehiculo' ? 'Vehículo' : 'Personal';
                $badgeColor = $tipo === 'vehiculo'
                ? 'bg-blue-100 text-blue-800 border border-blue-300'
                : 'bg-purple-100 text-purple-800 border border-purple-300';
                // Obtener gravedad
                $tipos = $tipo === 'vehiculo' ? ($tiposVehiculo ?? []) : ($tipo === 'personal' ? ($tiposPersonal ?? []):
                []);
                $infoTipo = $siniestro->tipo && isset($tipos[$siniestro->tipo]) ? $tipos[$siniestro->tipo] : null;
                $gravedad = $siniestro->gravedad ?? null;
                $badgeGravedadInfo = $siniestro->badgeGravedadInfo ?? null;
                @endphp
                <span
                  class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs font-medium {{ $badgeColor }}">
                  <i class="ti {{ $icono }} text-sm"></i>
                  {{ $label }}
                </span>
              </td>

              <td class="px-4 py-2 text-center">
                @if($gravedad)
                <span
                  class="inline-flex items-center gap-1 px-2 py-1 rounded border text-xs font-semibold {{ $badgeGravedadInfo['badgeBg'] ?? '' }} {{ $badgeGravedadInfo['border'] ?? '' }} {{ $badgeGravedadInfo['textColor'] ?? '' }}">
                  <i class="ti {{ $badgeGravedadInfo['icon'] ?? '' }} {{ $badgeGravedadInfo['textColor'] ?? '' }}"></i>
                  {{ ucfirst($gravedad) }}
                </span>
                @else
                <span class="text-xs text-gray-400">-</span>
                @endif
              </td>

              <td class="px-4 py-2 text-center">
                <div class="flex flex-col items-center justify-center gap-1 min-h-[40px]">
                  @php
                  $tipo = strtolower($siniestro->tipo_siniestro);
                  $unidad = collect($placasDisponibles)->firstWhere('unidad_id', $siniestro->unidad_id);
                  $totalUsuarios = $siniestro->usuarios ? count($siniestro->usuarios) : 0;
                  $hasCosto = !empty($siniestro->costo) && $siniestro->costo > 0;
                  @endphp

                  <div class="flex flex-wrap items-center justify-center gap-2">
                    @if(!$unidad && $totalUsuarios === 0)
                    {{-- Caso 1: Sin unidad ni personal --}}
                    <span class="text-gray-400">-</span>
                    @elseif($unidad && $totalUsuarios === 0)
                    {{-- Caso 2: Solo unidad --}}
                    <span class="flex items-center gap-2">
                      <i class="text-blue-500 ti ti-car"></i>
                      <span class="text-sm font-medium text-gray-700">
                        {{ $unidad['numero'] }}: {{ $unidad['marca'] }} {{ $unidad['modelo'] }}
                      </span>
                    </span>
                    @elseif(!$unidad && $totalUsuarios > 0)
                    {{-- Caso 3: Solo personal --}}
                    @if($totalUsuarios == 1)
                    <span
                      class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 border border-gray-200 rounded-full">
                      <i class="text-gray-500 ti ti-user"></i> {{ $siniestro->usuarios[0]->name }}
                    </span>
                    @else
                    <span
                      class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 border border-gray-200 rounded-full">
                      <i class="text-gray-500 ti ti-users"></i> {{ $totalUsuarios }} elementos
                    </span>
                    @endif
                    @elseif($unidad && $totalUsuarios > 0)
                    {{-- Caso 4: Unidad y personal (EN MISMA LÍNEA) --}}
                    <span class="flex items-center gap-1">
                      <i class="text-blue-500 ti ti-car"></i>
                      <span class="text-sm text-gray-700">
                        {{ $unidad['numero'] }}
                      </span>
                    </span>
                    <span class="text-gray-300">|</span>
                    @if($totalUsuarios == 1)
                    <span
                      class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 border border-gray-200 rounded-full">
                      <i class="text-gray-500 ti ti-user"></i> {{ $siniestro->usuarios[0]->name }}
                    </span>
                    @else
                    <span
                      class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 border border-gray-200 rounded-full">
                      <i class="text-gray-500 ti ti-users"></i> {{ $totalUsuarios }}
                    </span>
                    @endif
                    @endif
                    @if($hasCosto)
                    <span class="text-gray-300">|</span>
                    <span
                      class="inline-flex items-center gap-1 px-2 py-1 ml-1 text-xs font-semibold text-green-700 bg-green-100 border border-green-300 rounded-full"
                      title="${{ number_format($siniestro->costo, 2) }}">
                      <i class="ti ti-currency-dollar"></i>
                    </span>
                    @endif
                  </div>
                </div>
              </td>
              <!-- Acciones -->
              <td class="flex justify-center gap-2 px-4 py-2">
                <a href="{{ route('siniestros.detalle', $siniestro->id) }}"
                  class="flex items-center justify-center p-2 text-gray-700 bg-gray-100 border border-gray-300 rounded hover:bg-gray-200"
                  title="Ver detalle">
                  <i class="ti ti-eye"></i>
                </a>
                <button wire:click="editarSiniestro({{ $siniestro->id }})"
                  class="flex items-center justify-center p-2 text-white bg-blue-400 rounded hover:bg-blue-500"
                  title="Editar">
                  <i class="ti ti-edit"></i>
                </button>
                <button wire:click="eliminarSiniestro({{ $siniestro->id }})"
                  class="flex items-center justify-center p-2 text-white bg-red-600 rounded hover:bg-red-700"
                  onclick="return confirm('¿Seguro que deseas eliminar este siniestro?')" title="Eliminar">
                  <i class="ti ti-trash"></i>
                </button>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="6" class="px-4 py-8 text-center">
                <div class="flex flex-col items-center justify-center gap-4 py-8">
                  <span class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full">
                    <i class="text-4xl text-gray-400 ti ti-car-crash"></i>
                  </span>
                  <span class="text-lg font-semibold text-gray-700">No hay siniestros registrados</span>
                  <span class="text-sm text-gray-500">Agrega un nuevo siniestro para comenzar a gestionar el historial
                    de incidentes y accidentes.</span>
                  <button wire:click="showCreateForm" type="button"
                    class="flex items-center gap-2 px-5 py-2 mt-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                    <i class="ti ti-plus"></i>
                    Agregar siniestro
                  </button>
                </div>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
        <div class="mt-4" wire:key="paginacion-{{ md5($siniestros->toJson()) }}" wire:ignore>
          @if($siniestros->hasPages())
          {{ $siniestros->links('vendor.pagination.tailwind') }}
          @endif
        </div>
      </div>
      @endif

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