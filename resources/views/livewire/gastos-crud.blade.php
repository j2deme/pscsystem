<div class="py-6 mx-auto">
  <x-navbar />

  <x-livewire.monitoreo-layout :breadcrumb-items="$breadcrumbItems" :title-main="$titleMain" :help-text="$helpText">

    <div class="container mx-auto">

      {{-- Mensajes de éxito o error --}}
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

      {{-- Sección de Filtros --}}
      <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
        {{-- Filtros Izquierda --}}
        <div class="flex flex-wrap items-center gap-4">
          <div>
            <label for="perPage" class="mr-2 text-gray-700 dark:text-gray-200">Mostrar:</label>
            <select wire:model.live="perPage" id="perPage"
              class="px-2 py-1 text-gray-700 bg-white border border-gray-300 rounded form-select focus:border-blue-600 focus:ring-2 focus:ring-blue-100 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
              <option value="10">10</option>
              <option value="25">25</option>
              <option value="50">50</option>
              <option value="100">100</option>
            </select>
          </div>

          <div>
            <label for="filtro_fecha_inicio" class="mr-2 text-gray-700 dark:text-gray-200">Fecha inicio:</label>
            <input type="date" wire:model.live="filtro_fecha_inicio" id="filtro_fecha_inicio"
              class="px-2 py-1 text-gray-700 bg-white border border-gray-300 rounded form-input focus:border-blue-600 focus:ring-2 focus:ring-blue-100 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
          </div>

          <div>
            <label for="filtro_fecha_fin" class="mr-2 text-gray-700 dark:text-gray-200">Fecha fin:</label>
            <input type="date" wire:model.live="filtro_fecha_fin" id="filtro_fecha_fin"
              class="px-2 py-1 text-gray-700 bg-white border border-gray-300 rounded form-input focus:border-blue-600 focus:ring-2 focus:ring-blue-100 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
          </div>

          <div>
            <label for="filtro_punto" class="mr-2 text-gray-700 dark:text-gray-200">Punto:</label>
            <input type="text" wire:model.live="filtro_punto" id="filtro_punto" placeholder="Buscar punto..."
              class="px-2 py-1 text-gray-700 bg-white border border-gray-300 rounded form-input focus:border-blue-600 focus:ring-2 focus:ring-blue-100 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
          </div>

          <div>
            <label for="filtro_placas" class="mr-2 text-gray-700 dark:text-gray-200">Placas:</label>
            <input type="text" wire:model.live="filtro_placas" id="filtro_placas" placeholder="Buscar placas..."
              class="px-2 py-1 text-gray-700 bg-white border border-gray-300 rounded form-input focus:border-blue-600 focus:ring-2 focus:ring-blue-100 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
          </div>

          <div>
            <label for="filtro_tipo" class="mr-2 text-gray-700 dark:text-gray-200">Tipo:</label>
            <select wire:model.live="filtro_tipo" id="filtro_tipo"
              class="px-2 py-1 text-gray-700 bg-white border border-gray-300 rounded form-select focus:border-blue-600 focus:ring-2 focus:ring-blue-100 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
              <option value="">Todos</option>
              <option value="Gasolina">Gasolina</option>
              <option value="Viaticos">Viáticos</option>
              {{-- Agrega otros tipos si los hay --}}
            </select>
          </div>

          <div>
            <label for="filtro_usuario_rol" class="mr-2 text-gray-700 dark:text-gray-200">Rol:</label>
            <select wire:model.live="filtro_usuario_rol" id="filtro_usuario_rol"
              class="px-2 py-1 text-gray-700 bg-white border border-gray-300 rounded form-select focus:border-blue-600 focus:ring-2 focus:ring-blue-100 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
              <option value="">Todos</option>
              @foreach($rolesDisponibles as $rol)
              <option value="{{ $rol }}">{{ Str::title(strtolower($rol)) }}</option>
              @endforeach
            </select>
          </div>
        </div>

        {{-- Acciones Derecha --}}
        <div class="flex items-center gap-2">
          {{-- Botón para Exportar a Excel (placeholder) --}}
          <button
            class="flex items-center gap-2 px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700 disabled:opacity-50"
            title="Exportar a Excel" disabled>
            <i class="ti ti-file-export"></i>
            Exportar
          </button>
        </div>
      </div>

      {{-- Tabla de Gastos --}}
      <div class="overflow-x-auto">
        <table
          class="min-w-full bg-white border border-gray-200 rounded shadow table-auto dark:bg-gray-800 dark:border-gray-700">
          <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
              <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-200">Fecha/Hora</th>
              <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-200">Usuario</th>
              <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-200">Punto</th>
              <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-200">Tipo</th>
              <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-200">Monto</th>
              <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-200">Detalles</th>
              <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-200">Evidencia</th>
              <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-200">Acciones</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse ($gastos as $gasto)
            <tr wire:key='gasto-{{ $gasto->id }}' class="hover:bg-gray-50 dark:hover:bg-gray-750">
              <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200 whitespace-nowrap">
                {{ $gasto->Fecha->format('d/m/Y') }}<br>
                <span class="text-xs text-gray-500">{{ $gasto->Hora }}</span>
              </td>
              <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200">
                {{ $gasto->user->name ?? $gasto->user_name ?? 'N/A' }}
                @if($gasto->user && $gasto->user->rol)
                <br>
                <span
                  class="text-xs px-1.5 py-0.5 bg-blue-100 text-blue-800 rounded dark:bg-blue-900 dark:text-blue-100">
                  {{ Str::title(strtolower($gasto->user->rol)) }}
                </span>
                @endif
              </td>
              <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200">
                {{ $gasto->user->punto ?? ($gasto->user->solicitudAlta->punto ?? 'N/A') }}
              </td>
              <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200">
                @php
                $tipo = strtolower($gasto->tipo ?? '');
                $badgeInfo = match($tipo) {
                'carga gasolina', 'gasolina' => [
                'text' => 'Gasolina',
                'icon' => 'ti-gas-station',
                'colorClass' => 'text-blue-700 bg-blue-100 dark:bg-blue-900 dark:text-blue-100'
                ],
                'viaticos', 'viáticos' => [
                'text' => 'Viáticos',
                'icon' => 'ti-wallet',
                'colorClass' => 'text-purple-700 bg-purple-100 dark:bg-purple-900 dark:text-purple-100'
                ],
                default => [
                'text' => ucfirst($gasto->Tipo ?? 'N/A'),
                'icon' => 'ti-receipt-2',
                'colorClass' => 'text-gray-700 bg-gray-100 dark:bg-gray-600 dark:text-gray-100'
                ]
                };
                @endphp
                <span
                  class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full {{ $badgeInfo['colorClass'] }}">
                  <i class="mr-1 text-xs ti {{ $badgeInfo['icon'] }}"></i>
                  {{ $badgeInfo['text'] }}
                </span>
              </td>
              <td class="px-4 py-2 text-sm font-medium text-gray-900 dark:text-white">
                ${{ number_format($gasto->Monto, 2) }}
              </td>
              <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200">
                @if($gasto->Tipo === 'Gasolina')
                <div class="text-xs space-y-1">
                  <!-- Kilometraje -->
                  <div class="flex items-center">
                    <i class="ti ti-road-sign text-blue-500 mr-1.5"></i>
                    <span class="ml-1 font-medium">
                      {{ $gasto->Km !== null ? number_format($gasto->Km, 0, '.', ',') . ' Km' : 'N/A' }}
                    </span>
                  </div>

                  @php
                  $antes = $gasto->Gasolina_antes_carga !== null ? (int)round($gasto->Gasolina_antes_carga) : null;
                  $despues = $gasto->Gasolina_despues_carga !== null ? (int)round($gasto->Gasolina_despues_carga) :
                  null;

                  // Determinar el tope dinámicamente según el valor más alto registrado
                  $nivelesPosibles = [4, 8, 10, 16];
                  $maxRegistrado = max($antes ?? 0, $despues ?? 0, 1);

                  // Buscar el nivel posible más cercano hacia arriba
                  $maxValue = collect($nivelesPosibles)
                  ->filter(fn($n) => $n >= $maxRegistrado)
                  ->sort()
                  ->first() ?? max($nivelesPosibles);

                  @endphp

                  <!-- Gasolina -->
                  <div class="flex items-center">
                    <i class="ti ti-gas-station text-blue-700 mr-1.5"></i>
                    <span class="font-medium">Gasolina:</span>
                    @if ($antes !== null and $despues !== null)
                    <span class="ml-1 font-mono">
                      <div class="flex items-center">
                        {{ $antes }} <i class="ti ti-caret-right-filled" aria-hidden="true"></i> {{ $despues
                        }}
                      </div>
                    </span>
                    @else
                    <span class="ml-1 font-mono">N/A</span>
                    @endif
                  </div>
                  <!-- Indicadores de nivel acotados visualmente -->
                  @php
                  $maxDisplay = 10;
                  $showEllipsis = $maxValue > $maxDisplay;

                  // Determinar valores a mostrar
                  if ($showEllipsis && $antes !== null && $despues !== null) {
                  // Mostrar: 1, antes, después, maxValue (con puntos suspensivos)
                  $indices = array_unique([1, $antes, $despues, $maxValue]);
                  sort($indices);

                  // Construir display array con puntos suspensivos
                  $display = [];
                  $last = null;
                  foreach ($indices as $idx) {
                  if ($last !== null && $idx > $last + 1) {
                  $display[] = '‥';
                  }
                  $display[] = $idx;
                  $last = $idx;
                  }
                  } else {
                  // Mostrar todos los números de 1 a maxValue
                  $display = range(1, min($maxValue, $maxDisplay));
                  if ($maxValue > $maxDisplay) {
                  $display[] = '‥';
                  $display[] = $maxValue;
                  }
                  }
                  @endphp
                  <div class="flex items-center">
                    <div class="flex space-x-0.5">
                      @foreach ($display as $val)
                      @if ($val === '...')
                      <span class="pr-2 text-xs font-mono text-gray-400">...</span>
                      @elseif ($val == $antes)
                      <span class="pr-2 text-sm font-bold text-amber-500 font-mono">{{ $val }}</span>
                      @elseif ($val == $despues)
                      <span class="pr-2 text-sm font-bold font-mono text-green-500">{{ $val }}</span>
                      @else
                      <span class="pr-2 text-xs font-mono text-gray-300 dark:text-gray-600">{{ $val }}</span>
                      @endif
                      @endforeach
                    </div>
                  </div>
                </div>
                @else
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                  <i class="ti ti-info-circle mr-1"></i>
                  <span>Sin detalles adicionales</span>
                </div>
                @endif
              </td>
              <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200">
                @php
                $existeEvidencia = Storage::disk('public')->exists($gasto->Evidencia);
                $rutaEvidencia = $existeEvidencia ? asset('storage/' . $gasto->Evidencia) : null;
                @endphp

                @if($gasto->evidencia and $existeEvidencia)
                <a href="{{ $rutaEvidencia }}" target="_blank" class="text-blue-500 hover:underline"
                  title="Ver evidencia">
                  <i class="ti ti-photo"></i> Ver
                </a>
                @elseif(!$existeEvidencia)
                <span
                  class="inline-flex items-center ml-2 px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">
                  <i class="ti ti-photo-off mr-1"></i> No disponible
                </span>
                @else
                <span class="text-xs text-gray-400">Sin evidencia</span>
                @endif
              </td>
              <td class="px-4 py-2 text-center">
                <a href="{{ route('gastos.detalle', $gasto->id) }}"
                  class="inline-flex items-center justify-center w-8 h-8 text-gray-700 bg-gray-100 border border-gray-300 rounded hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600"
                  title="Ver detalle">
                  <i class="ti ti-eye"></i>
                </a>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                <div class="flex flex-col items-center justify-center gap-4 py-8">
                  <span
                    class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full dark:bg-gray-700">
                    <i class="text-4xl text-gray-400 ti ti-receipt-off"></i>
                  </span>
                  <span class="text-lg font-semibold text-gray-700 dark:text-gray-200">No se encontraron gastos</span>
                  <span class="text-sm text-gray-500 dark:text-gray-400">Intenta ajustar los filtros de búsqueda.</span>
                </div>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- Paginación --}}
      <div class="mt-4">
        {{ $gastos->links('vendor.pagination.tailwind') }}
      </div>

    </div>
  </x-livewire.monitoreo-layout>
</div>