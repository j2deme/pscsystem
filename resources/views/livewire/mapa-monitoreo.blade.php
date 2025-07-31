<x-livewire.monitoreo-layout :breadcrumb-items="[
        ['icon' => 'ti-home', 'url' => route('admin.monitoreoDashboard')],
        ['icon' => 'ti-map', 'label' => 'Mapa de Monitoreo']
    ]" title-main="Mapa de Monitoreo" help-text="Visualización en tiempo real de alertas">
  <!-- Filtros -->
  <div class="mb-4 p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
    <div class="flex flex-wrap items-center gap-4">
      <!-- Selector de Gravedad como Dropdown -->
      <div>
        <label for="filtro-gravedad-select"
          class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filtrar
          por gravedad</label>
        <div class="relative" x-data="{ open: false }"> {{-- Usando Alpine.js para el dropdown --}}
          <!-- Botón del Dropdown -->
          <button type="button" @click="open = !open" id="filtro-gravedad-select" aria-haspopup="listbox"
            aria-expanded="false"
            class="w-full px-4 py-2 text-left bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm flex items-center justify-between">
            <div class="flex items-center justify-between">
              @if ($filtroGravedad !== 'todas')
              @php
              $colorClasesIndicador = '';
              switch($filtroGravedad) {
              case 'critica': $colorClasesIndicador = 'bg-red-600'; break;
              case 'alta': $colorClasesIndicador = 'bg-orange-500'; break;
              case 'media': $colorClasesIndicador = 'bg-yellow-500'; break;
              case 'baja': $colorClasesIndicador = 'bg-blue-400'; break;
              case 'antigua': $colorClasesIndicador = 'bg-gray-500'; break;
              default: $colorClasesIndicador = 'bg-gray-500'; break;
              }
              @endphp
              <span class="w-3 h-3 rounded-full {{ $colorClasesIndicador }} mr-2"></span>
              @endif
              <span>
                @if ($filtroGravedad === 'todas')
                Todas
                @else
                {{ ucfirst(__($filtroGravedad)) }}
                @endif
              </span>
            </div>
            <!-- Icono del Chevron -->
            <i class="ti ti-chevron-down text-gray-500 dark:text-gray-400 ml-2"></i>
          </button>

          <!-- Lista del Dropdown (Opciones) -->
          <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            class="absolute z-10 mt-1 w-full bg-white dark:bg-gray-800 shadow-lg rounded-md py-1 ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm"
            tabindex="-1" role="listbox">
            <!-- Opción "Todas" -->
            <button type="button" wire:click="$set('filtroGravedad', 'todas'); open = false"
              class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center justify-between"
              role="option">
              <span>Todas</span>
              @if ($filtroGravedad === 'todas')
              <i class="ti ti-check text-blue-500"></i>
              @endif
            </button>

            <!-- Opción "Crítica" -->
            <button type="button" wire:click="$set('filtroGravedad', 'critica'); open = false"
              class="w-full text-left px-4 py-2 text-sm text-red-700 dark:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 flex items-center justify-between"
              role="option">
              <div class="flex items-center">
                <span class="w-2 h-2 rounded-full bg-red-600 mr-2"></span>
                <span>Crítica</span>
              </div>
              @if ($filtroGravedad === 'critica')
              <i class="ti ti-check text-red-500"></i>
              @endif
            </button>

            <!-- Opción "Alta" -->
            <button type="button" wire:click="$set('filtroGravedad', 'alta'); open = false"
              class="w-full text-left px-4 py-2 text-sm text-orange-700 dark:text-orange-300 hover:bg-orange-50 dark:hover:bg-orange-900/20 flex items-center justify-between"
              role="option">
              <div class="flex items-center">
                <span class="w-2 h-2 rounded-full bg-orange-500 mr-2"></span>
                <span>Alta</span>
              </div>
              @if ($filtroGravedad === 'alta')
              <i class="ti ti-check text-orange-500"></i>
              @endif
            </button>

            <!-- Opción "Media" -->
            <button type="button" wire:click="$set('filtroGravedad', 'media'); open = false"
              class="w-full text-left px-4 py-2 text-sm text-yellow-700 dark:text-yellow-300 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 flex items-center justify-between"
              role="option">
              <div class="flex items-center">
                <span class="w-2 h-2 rounded-full bg-yellow-500 mr-2"></span>
                <span>Media</span>
              </div>
              @if ($filtroGravedad === 'media')
              <i class="ti ti-check text-yellow-500"></i>
              @endif
            </button>

            <!-- Opción "Baja" -->
            <button type="button" wire:click="$set('filtroGravedad', 'baja'); open = false"
              class="w-full text-left px-4 py-2 text-sm text-blue-700 dark:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 flex items-center justify-between"
              role="option">
              <div class="flex items-center">
                <span class="w-2 h-2 rounded-full bg-blue-400 mr-2"></span>
                <span>Baja</span>
              </div>
              @if ($filtroGravedad === 'baja')
              <i class="ti ti-check text-blue-500"></i>
              @endif
            </button>

            <!-- Opción "Antigua" -->
            <button type="button" wire:click="$set('filtroGravedad', 'antigua'); open = false"
              class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center justify-between"
              role="option">
              <div class="flex items-center">
                <span class="w-2 h-2 rounded-full bg-gray-500 mr-2"></span>
                <span>Antigua</span>
              </div>
              @if ($filtroGravedad === 'antigua')
              <i class="ti ti-check text-gray-500"></i>
              @endif
            </button>
          </div>
        </div>
      </div>
      <!-- Fin del Selector de Gravedad como Dropdown -->
      <div>
        <label for="filtro-usuario" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar por
          personal</label>
        <div class="relative">
          <input type="text" wire:model.live.debounce.500ms="filtroUsuario" id="filtro-usuario"
            class="block w-full px-3 py-2 pr-10 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-600 dark:border-gray-500 dark:text-white"
            placeholder="Nombre / Apellidos..." autocomplete="off">
          {{-- Botón para limpiar el filtro de usuario --}}
          @if (!empty($filtroUsuario))
          <button wire:click="$set('filtroUsuario', '')" type="button"
            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 focus:outline-none"
            title="Limpiar filtro de usuario">
            <i class="ti ti-x text-lg"></i> {{-- Icono de 'X' --}}
          </button>
          @endif
        </div>
      </div>
    </div>
  </div>
  <!-- Fin de filtros -->
  <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
    <!-- Panel de Alertas Simplificado -->
    <div class="border border-gray-200 rounded-lg md:col-span-1 bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
      <div class="p-4 border-b border-gray-300 dark:border-gray-600">
        <div class="flex items-center justify-between w-full min-h-8">
          <h2 class="text-base font-semibold text-gray-900 dark:text-white">Alertas Recientes</h2>
          <div class="text-sm text-gray-600 dark:text-gray-400">
            <span
              class="inline-block px-3 py-1 text-base font-semibold text-white bg-gray-800 rounded-full shadow dark:bg-white/80 dark:text-gray-900">
              {{ $totalAlertas ?? 0 }}
            </span>
          </div>
        </div>
      </div>
      <div class="p-4">
        <div class="space-y-3 max-h-[420px] overflow-y-auto overflow-x-hidden relative bg-slate-50 dark:bg-gray-800">
          @if($cargando)
          <!-- --- TARJETAS SKELETON --- -->
          @for ($i = 0; $i < 5; $i++) <div
            class="p-3 border-l-4 border-gray-200 bg-gray-50 dark:bg-gray-700 rounded animate-pulse">
            <div class="flex items-start justify-between">
              <div class="flex items-start gap-3">
                <div class="flex-1 space-y-2">
                  <div class="flex items-center gap-2">
                    <!-- Skeleton para el nombre del usuario -->
                    <div class="h-4 bg-gray-200 dark:bg-gray-600 rounded w-3/4"></div>
                    <!-- Skeleton para el badge -->
                    <div class="h-4 bg-gray-200 dark:bg-gray-600 rounded w-12"></div>
                  </div>
                  <!-- Skeleton para la ubicación -->
                  <div class="h-3 bg-gray-200 dark:bg-gray-600 rounded w-5/6"></div>
                  <!-- Skeleton para la fecha/hora -->
                  <div class="h-3 bg-gray-200 dark:bg-gray-600 rounded w-1/2"></div>
                </div>
              </div>
              <div class="flex flex-col items-end space-y-2">
                <!-- Skeleton para el indicador circular -->
                <div class="w-4 h-4 bg-gray-200 dark:bg-gray-600 rounded-full"></div>
                <!-- Skeleton para el texto debajo del indicador -->
                <div class="h-3 bg-gray-200 dark:bg-gray-600 rounded w-10"></div>
              </div>
            </div>
        </div>
        @endfor
        <!-- --- FIN TARJETAS SKELETON --- -->
        @elseif(isset($alertasRecientes) && count($alertasRecientes) > 0)
        @foreach($alertasRecientes as $index => $alerta)
        @php
        $colores = $alerta['colores'] ?? [];
        $estadoCompleto = $alerta['estadoCompleto'] ?? [];
        $urgencia = $alerta['minutosTranscurridos'] ?? 0;
        @endphp
        <!-- Tarjeta de alerta -->
        <div
          class="p-3 transition-all duration-200 border-l-4 {{ $colores['border'] ?? 'border-red-600' }} {{ $colores['bg'] ?? 'bg-red-50' }} rounded cursor-pointer hover:shadow-md hover:scale-[1.02] alerta-card"
          data-alerta-id="{{ $alerta['id'] ?? $index }}">
          <div class="flex items-start justify-between">
            <div class="flex items-start gap-3">
              <div class="flex-1">
                <div class="flex items-center gap-2 mb-1">
                  <p class="font-semibold text-gray-900 dark:text-white card-user">{{ $alerta['usuario'] ?? 'Usuario
                    desconocido' }}</p>
                </div>
                <p class="text-sm {{ $colores['text'] ?? 'text-red-700' }} font-medium">
                  <i class="mr-1 ti ti-map-pin"></i>
                  <span class="card-location">
                    {{ $alerta['ubicacion'] ?? 'Ubicación no disponible' }}
                  </span>
                </p>
                <p class="text-xs text-gray-500">
                  <i class="mr-1 ti ti-calendar"></i>
                  <span class="card-date">{{ $alerta['fecha'] ?? 'Fecha no disponible' }}</span>
                  <i class="mr-1 ti ti-clock"></i>
                  <span class="card-time">{{ $alerta['tiempo'] ?? 'Hora no disponible' }}</span> •
                  <span class="card-timestamp" data-timestamp="{{ $alerta['timestamp_creacion'] ?? '' }}"
                    data-minutos="{{ $urgencia }}">
                    {{-- @if($urgencia < 60) hace {{ $urgencia }} min @else hace {{ floor($urgencia / 60) }} h @endif
                      --}} </span>
                </p>
              </div>
            </div>
            <div class="text-right">
              <span
                class="card-indicator inline-block w-4 h-4 {{ $colores['indicator'] ?? 'bg-gray-600' }} rounded-full {{ ($colores['animate'] ?? false) ? 'animate-pulse' : '' }}"></span>
              <p class="mt-1 text-xs font-bold {{ $colores['text'] ?? 'text-gray-600' }}">{{
                $estadoCompleto['texto'] ?? 'N/A' }}</p>
            </div>
          </div>
        </div>
        <!-- Fin de tarjeta de alerta -->
        @endforeach
        @else
        <div class="flex flex-col items-center justify-center p-5 rounded bg-gray-50 dark:bg-gray-700">
          <div class="mb-3">
            <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-200 dark:bg-gray-600">
              <i class="ti ti-clock-off text-3xl text-gray-500 dark:text-gray-300"></i>
            </span>
          </div>
          <p class="font-semibold text-gray-900 dark:text-white">No hay alertas recientes</p>
          <p class="text-sm text-gray-600 dark:text-gray-400 text-center">
            Las alertas recientes aparecerán aquí.
          </p>
          <p class="text-sm text-gray-600 dark:text-gray-400 text-center mt-2">
            ¡Todo está tranquilo por ahora!
          </p>
        </div>
        @endif
      </div>
    </div>
  </div>

  <!-- Mapa -->
  <div class="border border-gray-200 rounded-lg md:col-span-2 bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
    <div class="relative">
      <div
        class="flex flex-wrap items-center justify-between w-full p-4 border-b border-gray-300 dark:border-gray-600 min-h-8">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white">Vista del Mapa</h2>
        <!-- Escala de Urgencia -->
        <div class="flex flex-wrap items-center gap-4 mt-2 md:mt-0 text-xs">
          <span class="font-medium text-gray-600 dark:text-gray-400">Escala de Urgencia:</span>
          <div class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-1" title="hace menos de 10 minutos">
              <span class="w-2 h-2 bg-red-600 rounded-full"></span>
              <span class="font-medium text-red-600">CRÍTICA</span>
            </div>
            <div class="flex items-center gap-1" title="hace menos de 20 minutos">
              <span class="w-2 h-2 bg-orange-500 rounded-full"></span>
              <span class="font-medium text-orange-600">ALTA</span>
            </div>
            <div class="flex items-center gap-1" title="hace menos de 30 minutos">
              <span class="w-2 h-2 bg-yellow-500 rounded-full"></span>
              <span class="font-medium text-yellow-600">MEDIA</span>
            </div>
            <div class="flex items-center gap-1" title="hace menos de 1 hora">
              <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
              <span class="font-medium text-blue-600">BAJA</span>
            </div>
            <div class="flex items-center gap-1" title="hace más de 2 horas">
              <span class="w-2 h-2 bg-gray-500 rounded-full"></span>
              <span class="font-medium text-gray-600">ANTIGUA</span>
            </div>
          </div>
        </div>
        <button id="btn-centrar-mapa"
          class="p-1 mt-2 ml-0 md:mt-0 md:ml-4 bg-gray-600 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-400"
          title="Ajustar a marcadores">
          <i class="p-2 text-sm text-white ti ti-map-pin-2"></i>
        </button>
      </div>
    </div>
    <div class="p-4">
      <div id="mapaContainer" class="w-full bg-gray-200 rounded h-96 dark:bg-gray-700"></div>
      <div id="mapaEstado" class="mt-2 text-xs text-gray-500">Inicializando mapa...</div>
    </div>
  </div>
  </div>
</x-livewire.monitoreo-layout>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
  integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<!-- Marker Cluster CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" crossorigin="" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css"
  crossorigin="" />
<style>
  #mapaContainer {
    height: 400px;
    min-height: 400px;
    width: 100%;
  }

  .leaflet-container {
    height: 100%;
    width: 100%;
  }

  .custom-marker {
    background: transparent !important;
    border: none !important;
  }

  .custom-marker .animate-ping {
    animation: ping 1s cubic-bezier(0, 0, 0.2, 1) infinite;
  }

  .custom-marker .animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
  }

  @keyframes ping {

    75%,
    100% {
      transform: scale(2);
      opacity: 0;
    }
  }

  @keyframes pulse {

    0%,
    100% {
      opacity: 1;
    }

    50% {
      opacity: .5;
    }
  }

  @keyframes spin {
    to {
      transform: rotate(360deg);
    }
  }

  .animate-spin {
    animation: spin 1s linear infinite;
  }

  /* Estilos personalizados para clusters coloreados según la gravedad máxima */
  .marker-cluster-critica {
    background-color: rgba(220, 38, 38, 0.6);
    /* bg-red-600 con opacidad */
  }

  .marker-cluster-critica div {
    background-color: rgba(220, 38, 38, 0.8);
    color: white;
  }

  .marker-cluster-alta {
    background-color: rgba(249, 115, 22, 0.6);
    /* bg-orange-500 con opacidad */
  }

  .marker-cluster-alta div {
    background-color: rgba(249, 115, 22, 0.8);
    color: white;
  }

  .marker-cluster-media {
    background-color: rgba(234, 179, 8, 0.6);
    /* bg-yellow-500 con opacidad */
  }

  .marker-cluster-media div {
    background-color: rgba(234, 179, 8, 0.8);
    color: white;
  }

  .marker-cluster-baja {
    background-color: rgba(59, 130, 246, 0.6);
    /* bg-blue-500 con opacidad */
  }

  .marker-cluster-baja div {
    background-color: rgba(59, 130, 246, 0.8);
    color: white;
  }

  .marker-cluster-antigua {
    background-color: rgba(107, 114, 128, 0.6);
    /* bg-gray-500 con opacidad */
  }

  .marker-cluster-antigua div {
    background-color: rgba(107, 114, 128, 0.8);
    color: white;
  }

  /* --- ESTILOS PARA EL POPUP DEL MARCADOR --- */
  .popup-alerta {
    font-family: 'Inter', system-ui, sans-serif;
    /* Asegúrate de que Inter esté cargada o usa la predeterminada */
    border-radius: 0.375rem;
    /* rounded-md */
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    /* shadow-lg */
    overflow: hidden;
    background-color: #fff;
    /* bg-white */
    color: #374151;
    /* text-gray-700 */
    border: 1px solid #e5e7eb;
    /* border-gray-200 */
  }

  .dark .popup-alerta {
    background-color: #1f2937;
    /* bg-gray-800 */
    color: #d1d5db;
    /* text-gray-300 */
    border-color: #374151;
    /* border-gray-700 */
  }

  .popup-title h3 {
    line-height: 1.25;
    /* leading-tight */
  }

  .popup-title p {
    line-height: 1.375;
  }

  .popup-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .popup-indicator {
    /* Estilos del indicador circular */
    display: inline-block;
    border-radius: 50%;
  }

  .popup-copy-btn {
    /* Estilos del botón de copiar */
    transition: all 0.2s ease-in-out;
    font-weight: 500;
    /* font-medium */
  }

  .popup-copy-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    /* shadow-sm */
  }

  .dark .popup-copy-btn {
    background-color: #374151;
    /* bg-gray-700 */
    color: #f9fafb;
    /* text-gray-50 */
  }

  .dark .popup-copy-btn:hover {
    background-color: #4b5563;
    /* hover:bg-gray-600 */
  }

  .popup-badge {
    font-weight: 600;
  }

  /* --- FIN ESTILOS POPUP --- */
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
  integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<!-- Marker Cluster JS -->
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js" crossorigin=""></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1.11.10/dayjs.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1.11.10/plugin/relativeTime.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1.11.10/plugin/utc.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1.11.10/plugin/timezone.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1.11.10/plugin/updateLocale.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1.11.10/locale/es.min.js"></script>
<script>
  // --- CONFIGURACIÓN INICIAL ---
    dayjs.extend(dayjs_plugin_relativeTime);
    dayjs.extend(dayjs_plugin_utc);
    dayjs.extend(dayjs_plugin_timezone);
    dayjs.extend(dayjs_plugin_updateLocale);
    dayjs.locale('es');
    dayjs.updateLocale('es', {
        relativeTime: {
            future: 'en %s', past: 'hace %s', s: 'un momento', m: '1 min', mm: '%d min',
            h: '1 h', hh: '%d hrs', d: '1 día', dd: '%d días', M: '1 mes', MM: '%d meses', y: '1 año', yy: '%d años'
        }
    });

    // --- DATOS Y ESTADO ---
    let mapa, grupoMarcadores;
    let alertasReales = @json($alertasRecientes);
    const estadoMapa = { inicializado: false, cargando: false };

    // --- FUNCIONES DE UTILIDAD ---
    const obtenerUrgenciaYColores = (minutos) => {
      if (minutos <= 10) return { texto: 'CRÍTICA', border: 'border-red-600', bg: 'bg-red-50', badge: 'bg-red-600', text: 'text-red-700', indicator: 'bg-red-600', animate: true };
      if (minutos <= 20) return { texto: 'ALTA', border: 'border-orange-500', bg: 'bg-orange-50', badge: 'bg-orange-500', text: 'text-orange-700', indicator: 'bg-orange-500', animate: true };
      if (minutos <= 30) return { texto: 'MEDIA', border: 'border-yellow-500', bg: 'bg-yellow-50', badge: 'bg-yellow-500', text: 'text-yellow-700', indicator: 'bg-yellow-500', animate: false };
      if (minutos <= 60) return { texto: 'BAJA', border: 'border-blue-500', bg: 'bg-blue-50', badge: 'bg-blue-500', text: 'text-blue-700', indicator: 'bg-blue-500', animate: false };
      return { texto: 'ANTIGUA', border: 'border-gray-500', bg: 'bg-gray-50', badge: 'bg-gray-500', text: 'text-gray-700', indicator: 'bg-gray-500', animate: false };
    };

    const obtenerIconoPorEstado = (minutosTranscurridos) => {
      const urgencia = obtenerUrgenciaYColores(minutosTranscurridos);
      let icon = '', animation = '', pulse = false;
      switch (urgencia.texto) {
        case 'CRÍTICA': icon = "<i class='text-lg ti ti-alert-octagon'></i>"; animation = 'animate-pulse'; pulse = true; break;
        case 'ALTA': icon = "<i class='text-lg ti ti-alert-triangle'></i>"; animation = 'animate-pulse'; pulse = true; break;
        case 'MEDIA': icon = "<i class='text-lg ti ti-alert-circle'></i>"; break;
        case 'BAJA': icon = "<i class='text-lg ti ti-clock'></i>"; break;
        case 'ANTIGUA': icon = "<i class='text-lg ti ti-clock-question'></i>"; break;
      }
      return { bgColor: urgencia.badge, textColor: urgencia.text, icon, estadoTexto: urgencia.texto, animation, pulse };
    };

    const actualizarEstadoMapa = (mensaje) => {
      const el = document.getElementById('mapaEstado');
      if (el) {
        // Opcional: Añadir clases CSS basadas en el mensaje
        // Por ejemplo, si el mensaje incluye "Error", añadir una clase roja
        el.textContent = mensaje;
        // Ejemplo simple de cambio de clase basado en contenido
        el.className = 'mt-2 text-xs'; // Resetear clases
        if (mensaje.toLowerCase().includes('error')) {
          el.classList.add('text-red-500', 'font-bold');
        } else if (mensaje.toLowerCase().includes('cargando') || mensaje.toLowerCase().includes('inicializando')) {
          el.classList.add('text-blue-500');
        } else {
          el.classList.add('text-gray-500');
        }
      }
    };

    const mostrarCargandoMapa = (mostrar = true, mensaje = "Cargando mapa...") => {
        const contenedor = document.getElementById('mapaContainer');
        if (!contenedor) return;

        // ID único para el elemento de carga
        const idElementoCarga = 'mapa-cargando-overlay';
        let elementoCarga = document.getElementById(idElementoCarga);

        if (mostrar) {
            // Crear el overlay de carga si no existe
            if (!elementoCarga) {
                elementoCarga = document.createElement('div');
                elementoCarga.id = idElementoCarga;
                // Estilos para cubrir todo el contenedor del mapa
                elementoCarga.style.cssText = `
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(255, 255, 255, 0.7); /* Fondo semitransparente */
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    z-index: 1000; /* Asegurarse de que esté por encima del mapa */
                    border-radius: 0.5rem; /* rounded-lg, igual que el contenedor padre */
                `;
                // Crear el spinner (puedes usar una librería como Spin.js o un SVG)
                // Aquí un spinner simple con CSS
                const spinner = document.createElement('div');
                spinner.innerHTML = `
                    <div class="relative">
                        <div class="w-12 h-12 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                    </div>
                `;
                // Crear el texto
                const texto = document.createElement('p');
                texto.className = 'mt-2 text-sm font-medium text-gray-700 dark:text-gray-300';
                texto.textContent = mensaje;

                elementoCarga.appendChild(spinner);
                elementoCarga.appendChild(texto);
                contenedor.style.position = 'relative'; // Asegurar que el contenedor sea relativo para posicionar el overlay
                contenedor.appendChild(elementoCarga);
                console.log(`🌀 Mostrando indicador de carga en mapa: ${mensaje}`);
            } else {
                // Si ya existe, actualizar el mensaje
                const textoExistente = elementoCarga.querySelector('p');
                if (textoExistente) {
                    textoExistente.textContent = mensaje;
                }
                elementoCarga.style.display = 'flex'; // Asegurar que sea visible
            }
        } else {
            // Ocultar el overlay de carga si existe
            if (elementoCarga) {
                elementoCarga.style.display = 'none';
                console.log("✅ Indicador de carga en mapa ocultado.");
            }
        }
    };

    // --- GESTIÓN DE TIEMPOS RELATIVOS ---
    const actualizarTiemposRelativos = () => {
      document.querySelectorAll('[data-timestamp]').forEach(el => {
        const ts = parseInt(el.getAttribute('data-timestamp'));
        if (!ts || isNaN(ts)) return;
        const fecha = dayjs.unix(ts);
        el.textContent = fecha.fromNow();
        actualizarColoresTarjeta(el, dayjs().diff(fecha, 'minute'));
      });
    };

    const actualizarColoresTarjeta = (elTiempo, minutos) => {
      const tarjeta = elTiempo.closest('[data-alerta-id]');
      if (!tarjeta) return;
    
      const c = obtenerUrgenciaYColores(minutos);
    
      // Función auxiliar para actualizar clases de Tailwind
      const actualizarClase = (elemento, claseBase, nuevaClase) => {
        if (elemento) {
          // Elimina clases antiguas que coincidan con el patrón
          const clases = elemento.className.split(' ').filter(cls => !cls.startsWith(claseBase));
          // Agrega la nueva clase
          clases.push(nuevaClase);
          elemento.className = clases.join(' ');
        }
      };

      const actualizarClaseEspecifica = (elemento, claseBase, nuevaClaseEspecifica, clasesMantener = []) => {
        if (elemento) {
          // Conservar siempre estas clases
          const clasesBase = clasesMantener;
          // Filtrar clases antiguas basadas en el prefijo
          const clasesFiltradas = elemento.className.split(' ').filter(cls =>
            !cls.startsWith(claseBase) && !clasesBase.includes(cls)
          );
          // Combinar: clases base + nueva clase específica
          const nuevasClases = [...clasesBase, nuevaClaseEspecifica, ...clasesFiltradas];
          elemento.className = nuevasClases.join(' ');
        }
      };
    
      // Actualizar borde izquierdo de la tarjeta
      actualizarClase(tarjeta, 'border-', `border-l-4 ${c.border}`);
      // Actualizar fondo de la tarjeta
      actualizarClase(tarjeta, 'bg-', c.bg);
    
      // Actualizar texto de ubicación
      const textoUbicacion = tarjeta.querySelector('.card-location');
      if (textoUbicacion) {
        const contenedorTexto = textoUbicacion.parentElement; // El <p>
        if (contenedorTexto) {
          // Actualiza solo la clase de color (text-*), manteniendo 'text-sm' y 'font-medium'
          actualizarClaseEspecifica(contenedorTexto, 'text-', c.text, ['text-sm', 'font-medium']);
        }
      }
    
      // Actualizar indicador circular
      const indicador = tarjeta.querySelector('.card-indicator');
      if (indicador) {
        actualizarClase(indicador, 'bg-', c.indicator);
        indicador.classList.toggle('animate-pulse', c.animate);
      }
    
      // Actualizar texto debajo del indicador
      const textoUrgencia = tarjeta.querySelector('.text-right p'); // Asumiendo que este es el lugar correcto
      if (textoUrgencia) {
        actualizarClase(textoUrgencia, 'text-', `mt-1 text-xs font-bold ${c.text}`);
        textoUrgencia.textContent = c.texto; // Actualizar el texto
      }
    }; // Fin de actualizarColoresTarjeta

    // --- GESTIÓN DEL MAPA ---
    const inicializarMapa = () => {
      // Resetear banderas de estado
      estadoMapa.inicializado = false;
      estadoMapa.cargando = false;
    
      // 1. Limpiar grupo de marcadores si existe
      if (grupoMarcadores) {
        try {
          console.log("Limpiando grupo de marcadores existente...");
          grupoMarcadores.clearLayers();
          // grupoMarcadores.removeFrom(mapa); // Opcional, si grupoMarcadores está en el mapa
        } catch (e) {
          console.warn("Advertencia al limpiar grupo de marcadores:", e);
        }
        // No destruir grupoMarcadores aún, se puede reutilizar o se creará uno nuevo
      }
    
      // 2. Destruir el mapa Leaflet si existe
      if (mapa) {
        try {
          console.log("Destruyendo instancia de mapa Leaflet existente...");
          // Verificar si el contenedor del mapa aún existe en el DOM antes de remover
          if (mapa._container && document.body.contains(mapa._container)) {
            mapa.remove(); // Método oficial de Leaflet para destruir el mapa
          } else {
            console.log("El contenedor del mapa ya no está en el DOM, omitiendo remove().");
          }
        } catch (e) {
          console.warn('Advertencia al remover el mapa anterior en inicializarMapa:', e);
        }
        mapa = undefined; // Liberar referencia
      }
    
      return new Promise((resolve) => {
        try {
          const contenedor = document.getElementById('mapaContainer');
          if (!contenedor) {
            console.error('❌ Contenedor del mapa (#mapaContainer) no encontrado en inicializarMapa');
            actualizarEstadoMapa('Error: Contenedor del mapa no encontrado.');
            resolve();
            return;
          }

          mostrarCargandoMapa(true, "Inicializando mapa...");
          actualizarEstadoMapa('Inicializando mapa...');
    
          // 3. Limpiar explícitamente el contenedor antes de crear el mapa
          console.log("Limpiando y preparando contenedor del mapa...");
          contenedor.innerHTML = ''; // Vaciar completamente
          // Reafirmar estilos básicos si es necesario
          contenedor.style.height = '400px';
          contenedor.style.minHeight = '400px';
          contenedor.style.width = '100%';
    
          // 4. Crear el nuevo mapa
          console.log("Creando nueva instancia de mapa Leaflet...");
          mapa = L.map(contenedor, {
            center: [25.6866, -100.3161],
            zoom: 13,
            zoomControl: true,
            attributionControl: true
          });
    
          L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 18
          }).addTo(mapa);
    
          // 5. Crear o reutilizar grupoMarcadores
          if (!grupoMarcadores) {
            console.log("Creando nuevo grupo de marcadores (MarkerClusterGroup)...");
            grupoMarcadores = L.markerClusterGroup({
              spiderfyOnMaxZoom: true,
              showCoverageOnHover: true,
              zoomToBoundsOnClick: true,
              removeOutsideVisibleBounds: true,
              maxClusterRadius: 85,
              disableClusteringAtZoom: 17,
              iconCreateFunction: function(cluster) {
                const childMarkers = cluster.getAllChildMarkers();
                let maxUrgencyLevel = 0;
                let maxUrgencyClass = 'antigua';
    
                childMarkers.forEach(marker => {
                  const minutos = marker.options.minutosTranscurridos || 0;
                  let level = 0;
                  if (minutos <= 10)
                    level=4;
                  else if (minutos <=20)
                    level=3;
                  else if (minutos <=30)
                    level=2;
                  else if (minutos <=60)
                    level=1;

                  if (level> maxUrgencyLevel) {
                    maxUrgencyLevel = level;
                    switch(level) {
                      case 4: maxUrgencyClass = 'critica'; break;
                      case 3: maxUrgencyClass = 'alta'; break;
                      case 2: maxUrgencyClass = 'media'; break;
                      case 1: maxUrgencyClass = 'baja'; break;
                      default: maxUrgencyClass = 'antigua'; break;
                    }
                  }
                });
    
                return L.divIcon({
                  html: `<div><span>${childMarkers.length}</span></div>`,
                  className: 'marker-cluster marker-cluster-' + maxUrgencyClass,
                  iconSize: L.point(40, 40)
                });
              },
            });
          } else {
            console.log("Reutilizando grupo de marcadores existente (ya limpiado).");
            // Asegurarse de que el grupo limpio se agregue al nuevo mapa
            // Si ya estaba en el mapa viejo, podría necesitar removeFrom y addTo
            // grupoMarcadores.removeFrom(mapa); // Si estaba en otro mapa
          }
          grupoMarcadores.addTo(mapa);
    
          estadoMapa.inicializado = true;
          // Forzar una actualización del tamaño del mapa
          setTimeout(() => {
            if (mapa) {
              try {
                mapa.invalidateSize();
                //console.log("Tamaño del mapa invalidado después de la inicialización.");
              } catch (e) {
                console.warn("No se pudo invalidar el tamaño del mapa:", e);
              }
            }
          }, 50);
    
          console.log('✅ Mapa inicializado desde cero en inicializarMapa');
          actualizarEstadoMapa('Mapa listo');
          mostrarCargandoMapa(false);
          resolve();
        } catch (error) {
          console.error('❌ Error crítico al inicializar mapa desde cero en inicializarMapa:', error);
          actualizarEstadoMapa(`Error de inicialización: ${error.message}`);
          estadoMapa.inicializado = false;
          mapa = undefined;
          mostrarCargandoMapa(false);
          resolve();
        }
      });
    };

    // - GESTIÓN DEL MAPA (continuación) -
    const cargarMarcadores = () => {
      // Verificaciones iniciales
      if (!mapa) {
        console.warn('⚠️ cargarMarcadores: Mapa no definido.');
        actualizarEstadoMapa('Error: Mapa no disponible para cargar marcadores.');
        // Asegurarse de ocultar cualquier loading anterior
        mostrarCargandoMapa(false);
        return;
      }
      if (!estadoMapa.inicializado) {
        console.warn('⚠️ cargarMarcadores: Mapa no inicializado.');
        actualizarEstadoMapa('Error: Mapa no inicializado.');
        mostrarCargandoMapa(false);
        return;
      }
      if (estadoMapa.cargando) {
        console.log('⚠️ cargarMarcadores: Carga ya en proceso.');
        // Opcional: mostrar loading aquí también si se llama múltiples veces
        // mostrarCargandoMapa(true, "Actualizando marcadores...");
        return;
      }

      estadoMapa.cargando = true;
      actualizarEstadoMapa('Cargando marcadores...');
      mostrarCargandoMapa(true, "Cargando marcadores...");

      try {
        if (grupoMarcadores) {
          grupoMarcadores.clearLayers();
        } else {
          console.error("❌ cargarMarcadores: grupoMarcadores es undefined.");
          estadoMapa.cargando = false;
          actualizarEstadoMapa('Error interno: Grupo de marcadores no encontrado.');
          return;
        }

        if (!alertasReales || alertasReales.length === 0) {
          console.log('ℹ️ No hay alertas para mostrar en el mapa.');
          actualizarEstadoMapa('Sin alertas para mostrar');
          mostrarCargandoMapa(false);
          estadoMapa.cargando = false;
          return;
        }

        console.log(`📍 Cargando ${alertasReales.length} marcadores...`);
        const bounds = [];

        alertasReales.forEach((alerta, index) => {
          if (!alerta.latitud || !alerta.longitud || isNaN(alerta.latitud) || isNaN(alerta.longitud)) {
            console.warn(`Alerta ${index} tiene coordenadas inválidas`, alerta.latitud, alerta.longitud);
            return;
          }

          const lat = parseFloat(alerta.latitud);
          const lng = parseFloat(alerta.longitud);
          const minutosTranscurridos = alerta.minutosTranscurridos || 0;
          const iconoConfig = obtenerIconoPorEstado(minutosTranscurridos);
          const alertaId = String(alerta.id ?? index);

          let zIndexOffset = 0;
          switch (iconoConfig.estadoTexto) {
            case 'CRÍTICA': zIndexOffset = 5000; break;
            case 'ALTA': zIndexOffset = 4000; break;
            case 'MEDIA': zIndexOffset = 3000; break;
            case 'BAJA': zIndexOffset = 2000; break;
            case 'ANTIGUA': zIndexOffset = 1000; break;
            default: zIndexOffset = 0; break;
          }

          const marcador = L.marker([lat, lng], {
            icon: L.divIcon({
              className: 'custom-marker',
              html: `<div class="relative w-8 h-8">
                        ${iconoConfig.pulse ? `<div class="absolute inset-0 w-8 h-8 ${iconoConfig.bgColor} rounded-full animate-ping opacity-30 z-0"></div>` : ''}
                        <div class="relative z-10 w-8 h-8 ${iconoConfig.bgColor} rounded-full border-2 border-white shadow-lg flex items-center justify-center text-white font-bold ${iconoConfig.animation}">
                          ${iconoConfig.icon}
                        </div>
                      </div>`,
              iconSize: [32, 32], iconAnchor: [16, 16]
            }),
            alertaId: alertaId,
            minutosTranscurridos: minutosTranscurridos,
            zIndexOffset: zIndexOffset
          });

          marcador.bindPopup(`
              <div class="popup-alerta min-w-[250px]">
                  <div class="popup-header ${iconoConfig.bgColor} text-white p-3 rounded-t-md flex items-start">
                      <div class="popup-icon text-lg mr-2">${iconoConfig.icon}</div>
                      <div class="popup-title flex-1">
                          <h3 class="font-bold text-base truncate">${alerta.usuario}</h3>
                          <p class="text-xs opacity-90 flex items-center">
                              <i class='mr-1 ti ti-map-pin'></i>
                              <span class="truncate">${alerta.ubicacion ? alerta.ubicacion : 'Ubicación no disponible'}</span>
                          </p>
                      </div>
                      <span class="popup-badge flex-shrink-0 inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-white/20 text-white">
                          ${iconoConfig.estadoTexto}
                      </span>
                  </div>
                  <div class="popup-body p-3">
                      <div class="popup-details space-y-2">
                          <div class="popup-detail-row flex items-center text-sm">
                              <i class='mr-2 ti ti-calendar-event text-gray-500'></i>
                              <span class="font-medium">${alerta.fecha}</span>
                          </div>
                          <div class="popup-detail-row flex items-center text-sm">
                              <i class='mr-2 ti ti-clock text-gray-500'></i>
                              <span>${alerta.tiempo}</span>
                          </div>
                          <div class="popup-detail-row flex items-center text-sm">
                              <i class='mr-2 ti ti-gps text-gray-500'></i>
                              <span class="truncate">${lat.toFixed(6)}, ${lng.toFixed(6)}</span>
                          </div>
                      </div>
                      <div class="popup-footer mt-3 pt-3 border-t border-gray-200 flex justify-between items-center">
                            <span class="popup-indicator inline-block w-3 h-3 rounded-full ${obtenerUrgenciaYColores(minutosTranscurridos).indicator} ${obtenerUrgenciaYColores(minutosTranscurridos).animate ? 'animate-pulse' : ''}"></span>
                            <button onclick="window.copiarCoordenadas(event, '${lat}, ${lng}')" class="popup-copy-btn text-xs px-2 py-1 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded flex items-center">
                                <i class='mr-1 ti ti-copy'></i> Copiar Coordenadas
                            </button>
                      </div>
                  </div>
              </div>
          `);

          grupoMarcadores.addLayer(marcador);
          bounds.push([lat, lng]);
        });

        // Ajustar vista con verificación
        console.log(`Preparando para ajustar vista a ${bounds.length} marcadores/clusters.`);
        if (bounds.length > 0) {
          const ajustarVista = () => {
            try {
              if (!mapa || !mapa._container || !document.body.contains(mapa._container)) {
                console.warn("Mapa no válido o contenedor no en el DOM al intentar ajustar vista.");
                return;
              }
              mapa.invalidateSize();

              if (bounds.length === 1) {
                mapa.setView(bounds[0], 15, { animate: true });
              } else {
                // Usar getBounds del grupo de clusters si está disponible y tiene métodos
                // O calcular bounds manualmente como antes
                const clusterBounds = grupoMarcadores.getBounds();
                if (clusterBounds.isValid()) {
                  mapa.fitBounds(clusterBounds, {
                    padding: [30, 30],
                    maxZoom: 16,
                    animate: true
                  });
                } else {
                  console.warn("Límites del grupo de clusters no son válidos.");
                }
              }
              actualizarEstadoMapa(`${grupoMarcadores.getLayers().length} alertas cargadas`);
            } catch (e) {
              console.error("Error al ajustar la vista del mapa:", e);
              actualizarEstadoMapa(`Alertas cargadas, error al ajustar vista: ${e.message}`);
            }
          };

          // Pequeño delay para asegurar que el grupo de clusters esté listo
          setTimeout(ajustarVista, 100);
        } else {
          console.log("ℹ️ No hay límites válidos para ajustar la vista.");
          actualizarEstadoMapa(`${grupoMarcadores.getLayers().length} alertas cargadas (sin ubicación para ajustar vista)`);
        }
        actualizarEstadoMapa(`${grupoMarcadores.getLayers().length} alertas cargadas`);
        mostrarCargandoMapa(false);
        estadoMapa.cargando = false;

      } catch (errorGeneral) {
        console.error("❌ Error general en cargarMarcadores:", errorGeneral);
        actualizarEstadoMapa(`Error al cargar marcadores: ${errorGeneral.message}`);
        mostrarCargandoMapa(false);
        estadoMapa.cargando = false;
      }
    };

    // - INTERACCIÓN ENTRE TARJETAS Y MAPA (PARA CLUSTERING) -
    const manejarTarjetaAlerta = (event, alertaId, tipo) => {
      const marcadoresIndividuales = grupoMarcadores.getLayers();
      if (!marcadoresIndividuales.length) return;
      
      if (tipo === 'hover' || tipo === 'out') {
        marcadoresIndividuales.forEach(m => {
          const iconDiv = m._icon?.querySelector('.relative.z-10');
          if (!iconDiv) return;
          if (tipo === 'hover' && m.options.alertaId == alertaId) {
            iconDiv.style.opacity = '1';
            iconDiv.style.transform = 'scale(1.3)';
            iconDiv.style.zIndex = '500';
          } else if (tipo === 'out') { // Solo resetear en mouseleave
            iconDiv.style.opacity = '1';
            iconDiv.style.transform = 'scale(1)';
            iconDiv.style.zIndex = '10';
          }
        });
      } else if (tipo === 'click') {
        event.preventDefault();
        event.stopPropagation();
        // Buscar el marcador específico dentro de los marcadores individuales
        const marcador = marcadoresIndividuales.find(m => m.options.alertaId == alertaId);
        if (marcador) {
          // Usar zoomToShowLayer del plugin MarkerCluster
          // Esto desagrupará el cluster si es necesario y luego centra/abre el popup
          if (grupoMarcadores.zoomToShowLayer) {
            grupoMarcadores.zoomToShowLayer(marcador, () => {
              mapa.setView(marcador.getLatLng(), 16); // O ajusta el zoom como prefieras
              if (marcador.openPopup) marcador.openPopup();
            });
          } else {
            // Fallback si zoomToShowLayer no está disponible (menos probable)
            mapa.setView(marcador.getLatLng(), 16, { animate: true });
            if (marcador.openPopup) marcador.openPopup();
          }
        } else {
          console.warn(`Marcador con alertaId ${alertaId} no encontrado.`);
        }
        setTimeout(() => mapa.invalidateSize(), 100);
      }
    };

    const inicializarEventosTarjetas = () => {
      document.querySelectorAll('.alerta-card').forEach(tarjeta => {
        const alertaId = tarjeta.getAttribute('data-alerta-id');
        tarjeta.onmouseenter = e => manejarTarjetaAlerta(e, alertaId, 'hover');
        tarjeta.onmouseleave = e => manejarTarjetaAlerta(e, alertaId, 'out');
        tarjeta.onclick = e => manejarTarjetaAlerta(e, alertaId, 'click');
      });
    };

    // --- INICIALIZACIÓN Y ACTUALIZACIÓN ---
    const inicializarSistema = async () => {
      if (typeof L === 'undefined') {
        console.warn("Leaflet no cargado, reintentando...");
        setTimeout(inicializarSistema, 100);
        return Promise.resolve();
      }
      await inicializarMapa();
      inicializarEventosTarjetas();
      actualizarTiemposRelativos();
      console.log("✅ inicializarSistema completado (mapa e inicializaciones básicas)");
      cargarMarcadores();
    };

    const centrarVistaMapa = async () => {
      // Recalcular tiempos antes de recargar marcadores
      if (Array.isArray(alertasReales)) {
        alertasReales.forEach(alerta => {
          if (alerta.timestamp_creacion) {
            const fecha = dayjs.unix(parseInt(alerta.timestamp_creacion));
            alerta.minutosTranscurridos = dayjs().diff(fecha, 'minute');
          }
        });
        // Actualizar minutosTranscurridos en marcadores existentes (opcional, pero bueno para consistencia)
        grupoMarcadores.eachLayer(layer => {
          if (layer instanceof L.Marker) { // Asegurarse de que es un marcador
            const id = layer.options.alertaId;
            const alertaCorrespondiente = alertasReales.find(a => String(a.id ?? a.index) === String(id));
            if (alertaCorrespondiente) {
              layer.options.minutosTranscurridos = alertaCorrespondiente.minutosTranscurridos;
            }
          }
        });
        grupoMarcadores.refreshClusters(); // Refrescar clusters para aplicar cambios
      }
      cargarMarcadores();
      actualizarTiemposRelativos(); // Actualizar tiempos en tarjetas también
    };

    // --- EVENTOS ---
    document.addEventListener('DOMContentLoaded', () => {
      inicializarSistema().then(() => {
        setInterval(() => {
          let necesitaActualizacionBackend = false;
          // Recalcular tiempos en datos
          if (Array.isArray(alertasReales)) {
            alertasReales.forEach(alerta => {
              if (alerta.timestamp_creacion) {
                const fecha = dayjs.unix(parseInt(alerta.timestamp_creacion));
                alerta.minutosTranscurridos = dayjs().diff(fecha, 'minute');
                if (alerta.minutosTranscurridos > 300) {
                  console.log(`⚠️ Alerta ID ${alerta.id} ha vencido (más de 300 minutos). Se requiere actualización del backend.`);
                  necesitaActualizacionBackend = true;
                }
              }
            });
            // Actualizar minutosTranscurridos en marcadores existentes (opcional)
            grupoMarcadores.eachLayer(layer => {
              if (layer instanceof L.Marker) {
                const id = layer.options.alertaId;
                const alertaCorrespondiente = alertasReales.find(a => String(a.id ?? a.index) === String(id));
                if (alertaCorrespondiente) {
                  layer.options.minutosTranscurridos = alertaCorrespondiente.minutosTranscurridos;
                }
              }
            });
            grupoMarcadores.refreshClusters(); // Refrescar clusters para aplicar cambios
          }

          // --- ACCIÓN BASADA EN LA VERIFICACIÓN ---
          if (necesitaActualizacionBackend) {
            console.log("📡 Solicitando actualización completa de datos al backend...");
            // Disparar un evento que el componente Livewire escuchará
            // Esto es similar a cómo se disparan eventos para filtros
            Livewire.dispatch('solicitarActualizacionCompleta');
          } else {
            // Comportamiento normal si no hay alertas vencidas
            console.log("✅ No se encontraron alertas vencidas. Actualizando tiempos y colores locales.");
            grupoMarcadores.refreshClusters(); // Refrescar clusters para aplicar cambios de color/tiempo
            actualizarTiemposRelativos(); // Actualiza tiempos relativos en las tarjetas
          
            // Opcional: puedes seguir llamando a cargarMarcadores si crees
            // que es necesario reconstruirlos, pero refreshClusters debería ser suficiente
            // para actualizar colores basados en el nuevo minutosTranscurridos.
            // cargarMarcadores();
            
            console.log('🕒 Sistema actualizado automáticamente (tiempos/colores locales).');
            actualizarEstadoMapa(`${grupoMarcadores.getLayers().length} alertas cargadas`);
          }
          // --- FIN ACCIÓN ---

          //actualizarTiemposRelativos();
          //cargarMarcadores(); // Recargar marcadores con nuevos tiempos
          //console.log('🔄 Sistema actualizado automáticamente');
        }, 30000);
      });
    });

    // - EVENTOS LIVEWIRE -
    window.addEventListener('alertasActualizadas', (event) => {
      const nuevasAlertas = event.detail && event.detail.alertas ? event.detail.alertas : [];
  
      console.log("🔔 Evento personalizado 'alertasActualizadas' recibido CON DATOS.");
      console.log(`📥 Datos recibidos directamente del evento: ${Array.isArray(nuevasAlertas) ? nuevasAlertas.length : 'N/A'} alertas.`);
  
      // Validar que se recibieron datos
      if (!Array.isArray(nuevasAlertas)) {
        console.error("❌ Los datos recibidos en 'alertasActualizadas' no son un array válido:", nuevasAlertas);
        actualizarEstadoMapa('Error: Datos de alertas recibidos inválidos.');
        return;
      }
  
      setTimeout(() => {
        console.log("--- Iniciando re-sincronización del sistema del mapa ---");

        // 1. --- ACTUALIZAR alertasReales CON LOS DATOS RECIBIDOS DEL EVENTO ---
        alertasReales = nuevasAlertas;
        console.log(`✅ alertasReales actualizado internamente con ${alertasReales.length} alertas.`);

        // 2. Re-inicializar el sistema del mapa (mapa, eventos)
        console.log("🔄 Iniciando re-inicialización del sistema del mapa (mapa, eventos de tarjetas)...");

        // Resetear estado para forzar re-inicialización
        estadoMapa.inicializado = false;
        estadoMapa.cargando = false;

        // Llamar a inicializarSistema y luego cargar marcadores
        inicializarSistema()
            .then(() => {
              console.log("✅ inicializarSistema completado (mapa creado).");
              // Delay adicional para asegurar estabilidad del DOM/mapa
              return new Promise(resolve => setTimeout(resolve, 300));
            })
            .then(() => {
              console.log("📍 Llamando a cargarMarcadores() con los datos filtrados...");
              cargarMarcadores();
            })
            .then(() => {
              console.log("🔁 Re-reforzando inicialización de eventos y tiempos...");
              inicializarEventosTarjetas();
              actualizarTiemposRelativos();
              console.log('🎉 --- Sistema del mapa y feed completamente actualizados tras filtro ---');
            })
            .catch(error => {
              console.error("💥 Error crítico en la cadena de actualización:", error);
              actualizarEstadoMapa(`Error crítico en actualización: ${error.message}`);
            });
      }, 200); // Delay para asegurar re-renderizado completo de Livewire
    });
    
    document.addEventListener('click', function(e) {
        if (e.target.closest('#btn-centrar-mapa')) {
            centrarVistaMapa();
        }
    });

    console.log('📍 Sistema de mapa y tiempos cargado');

    // --- FUNCIONALIDAD PARA COPIAR COORDENADAS DESDE EL POPUP ---
    window.copiarCoordenadas = function(event, texto) {
      navigator.clipboard.writeText(texto).then(() => {
      console.log('Coordenadas copiadas: ' + texto);
      const btn = event.target.closest('.popup-copy-btn');
      if (btn) {
        const originalText = btn.innerHTML;
        btn.innerHTML = `<i class='mr-1 ti ti-check'></i> ¡Copiado!`;
        btn.classList.remove('bg-gray-200', 'dark:bg-gray-700');
        btn.classList.add('bg-green-100', 'dark:bg-green-900', 'text-green-800', 'dark:text-green-200');
        setTimeout(() => {
          btn.innerHTML = originalText;
          btn.classList.remove('bg-green-100', 'dark:bg-green-900', 'text-green-800', 'dark:text-green-200');
          btn.classList.add('bg-gray-200', 'dark:bg-gray-700');
        }, 1500);
      }
    }).catch(err => {
      console.error('Error al copiar texto: ', err);
      const btn = event.target.closest('.popup-copy-btn');
      if (btn) {
        const originalText = btn.innerHTML;
        btn.innerHTML = `<i class='mr-1 ti ti-alert-circle'></i> Error`;
        btn.classList.remove('bg-gray-200', 'dark:bg-gray-700');
        btn.classList.add('bg-red-100', 'dark:bg-red-900', 'text-red-800', 'dark:text-red-200');
        setTimeout(() => {
          btn.innerHTML = originalText;
          btn.classList.remove('bg-red-100', 'dark:bg-red-900', 'text-red-800', 'dark:text-red-200');
          btn.classList.add('bg-gray-200', 'dark:bg-gray-700');
        }, 1500);
      }
    });
  };
  // --- FIN FUNCIONALIDAD COPIAR COORDENADAS ---
</script>
@endpush