<x-livewire.monitoreo-layout :breadcrumb-items="[
        ['icon' => 'ti-home', 'url' => route('admin.monitoreoDashboard')],
        ['icon' => 'ti-map', 'label' => 'Mapa de Monitoreo']
    ]" title-main="Mapa de Monitoreo" help-text="Visualización en tiempo real de alertas">
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
        <div class="space-y-3 max-h-[420px] overflow-y-auto overflow-x-hidden relative">
          @if(isset($alertasRecientes) && count($alertasRecientes) > 0)
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
        if (el) el.textContent = mensaje;
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
        if (estadoMapa.inicializado && mapa && mapa._container === document.getElementById('mapaContainer')) {
            console.log('⚠️ Mapa ya inicializado');
            return Promise.resolve();
        }

        if (mapa) {
            try { mapa.remove(); } catch (e) { console.warn('No se pudo remover el mapa anterior:', e); }
            mapa = undefined;
        }
        estadoMapa.inicializado = false;

        return new Promise((resolve) => {
            try {
                const contenedor = document.getElementById('mapaContainer');
                if (!contenedor) { console.error('❌ Contenedor del mapa no encontrado'); resolve(); return; }

                mapa = L.map(contenedor, { center: [25.6866, -100.3161], zoom: 13, zoomControl: true, attributionControl: true });
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors', maxZoom: 18
                }).addTo(mapa);

                grupoMarcadores = L.markerClusterGroup({
                  spiderfyOnMaxZoom: true,
                  showCoverageOnHover: true,
                  zoomToBoundsOnClick: true,
                  removeOutsideVisibleBounds: true,
                  // animate: true, // Puede ser intensivo
                  maxClusterRadius: 85,
                  disableClusteringAtZoom: 17,
                  // Función para colorear clusters
                  iconCreateFunction: function(cluster) {
                    // Obtener todos los marcadores hijos del cluster
                    const childMarkers = cluster.getAllChildMarkers();
                    let maxUrgencyLevel = 0; // 0: Desconocida/Antigua, 1: Baja, 2: Media, 3: Alta, 4: Critica
                    let maxUrgencyClass = 'antigua'; // Clase por defecto
                    
                    childMarkers.forEach(marker => {
                      const minutos = marker.options.minutosTranscurridos || 0;
                      let level = 0;
                      if (minutos <= 10) {
                        level = 4; // Critica
                      } else if (minutos <= 20) {
                        level = 3; // Alta
                      } else if (minutos <= 30) {
                        level = 2; // Media
                      } else if (minutos <= 60) {
                        level = 1; // Baja
                      } // else level=0; // Antigua (ya es 0 por defecto)

                      if (level > maxUrgencyLevel) {
                        maxUrgencyLevel = level;
                        // Asignar clase CSS basada en la gravedad
                        switch(level) {
                        case 4: maxUrgencyClass = 'critica'; break;
                        case 3: maxUrgencyClass = 'alta'; break;
                        case 2: maxUrgencyClass = 'media'; break;
                        case 1: maxUrgencyClass = 'baja'; break;
                        default: maxUrgencyClass = 'antigua'; break;
                        }
                      }
                    });
                        
                    // Crear el icono del cluster con la clase CSS de la máxima gravedad
                    return L.divIcon({
                      html: `<div><span>${childMarkers.length}</span></div>`,
                      className: 'marker-cluster marker-cluster-' + maxUrgencyClass, // Clase CSS personalizada
                      iconSize: L.point(40, 40)
                    });
                  }
                }).addTo(mapa);

                estadoMapa.inicializado = true;
                mapa.invalidateSize();
                console.log('✅ Mapa inicializado');
                actualizarEstadoMapa('Mapa listo');
                resolve();
            } catch (error) {
                console.error('❌ Error al inicializar mapa:', error);
                actualizarEstadoMapa(`Error: ${error.message}`);
                resolve();
            }
        });
    };

    const cargarMarcadores = () => {
        if (!mapa || !estadoMapa.inicializado || estadoMapa.cargando) return;
        estadoMapa.cargando = true;

        grupoMarcadores.clearLayers();

        if (!alertasReales || alertasReales.length === 0) {
            actualizarEstadoMapa('Sin alertas para mostrar');
            estadoMapa.cargando = false;
            return;
        }

        //console.log(`📍 Cargando ${alertasReales.length} marcadores...`);
        const bounds = [];

        alertasReales.forEach((alerta, index) => {
            if (!alerta.latitud || !alerta.longitud || isNaN(alerta.latitud) || isNaN(alerta.longitud)) {
                console.warn(`Alerta ${index} tiene coordenadas inválidas`); return;
            }

            const iconoConfig = obtenerIconoPorEstado(alerta.minutosTranscurridos);
            const alertaId = alerta.id ?? index;
            const minutosTranscurridos = alerta.minutosTranscurridos || 0; // Asegurar que esté disponible

            let zIndexOffset = 0;
            switch (iconoConfig.estadoTexto) {
                case 'CRÍTICA': zIndexOffset = 5000; break;
                case 'ALTA': zIndexOffset = 4000; break;
                case 'MEDIA': zIndexOffset = 3000; break;
                case 'BAJA': zIndexOffset = 2000; break;
                case 'ANTIGUA': zIndexOffset = 1000; break;
            }

            const marcador = L.marker([alerta.latitud, alerta.longitud], {
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
                minutosTranscurridos: minutosTranscurridos, // <-- Pasar minutosTranscurridos
                // zIndexOffset: zIndexOffset
            });

            const c = obtenerUrgenciaYColores(alerta.minutosTranscurridos);

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
                    <div class="popup-detail-row flex items-center text-sm justify-between">
                      <div>
                        <i class='mr-2 ti ti-calendar-event text-gray-500'></i>
                        <span class="font-medium">${alerta.fecha}</span>
                      </div>
                      <div>
                        <i class='mr-2 ti ti-clock text-gray-500'></i>
                        <span class="font-medium">${alerta.tiempo}</span>
                      </div>
                    </div>
                    <div class="popup-detail-row flex items-center text-sm">
                      <i class='mr-2 ti ti-gps text-gray-500'></i>
                      <span class="truncate">${parseFloat(alerta.latitud).toFixed(6)}, ${parseFloat(alerta.longitud).toFixed(6)}</span>
                    </div>
                    <!-- ID de Alerta (Opcional, útil para depuración) -->
                    <!--
                    <div class="popup-detail-row flex items-center text-xs text-gray-500">
                      <i class='mr-2 ti ti-hash'></i>
                      <span>ID: ${alerta.id}</span>
                    </div>
                    -->
                  </div>
                  <div class="popup-footer mt-3 pt-3 border-t border-gray-200 flex justify-between items-center">
                    <span class="popup-indicator inline-block w-3 h-3 rounded-full ${c.indicator} ${c.animate ? 'animate-pulse' : ''}"></span>
                    <button onclick="window.copiarCoordenadas(event, '${alerta.latitud}, ${alerta.longitud}')" class="popup-copy-btn text-xs px-2 py-1 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded flex items-center">
                      <i class='mr-1 ti ti-copy'></i> Copiar coordenadas
                    </button>
                  </div>
                </div>
              </div>
            `);

            grupoMarcadores.addLayer(marcador);
            bounds.push([alerta.latitud, alerta.longitud]);
        });

        if (bounds.length > 0) {
            if (bounds.length === 1) {
                mapa.setView(bounds[0], 15);
            } else {
                mapa.fitBounds(bounds, { padding: [30, 30], maxZoom: 16, animate: true });
            }
        }

        actualizarEstadoMapa(`${grupoMarcadores.getLayers().length} alertas cargadas`);
        estadoMapa.cargando = false;
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
        if (typeof L === 'undefined') { setTimeout(inicializarSistema, 100); return; }
        await inicializarMapa();
        cargarMarcadores();
        inicializarEventosTarjetas();
        actualizarTiemposRelativos();
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
          // Recalcular tiempos en datos
          if (Array.isArray(alertasReales)) {
            alertasReales.forEach(alerta => {
              if (alerta.timestamp_creacion) {
                const fecha = dayjs.unix(parseInt(alerta.timestamp_creacion));
                alerta.minutosTranscurridos = dayjs().diff(fecha, 'minute');
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
          actualizarTiemposRelativos();
          cargarMarcadores(); // Recargar marcadores con nuevos tiempos
          //console.log('🔄 Sistema actualizado automáticamente');
        }, 30000);
      });
    });

    document.addEventListener('livewire:updated', () => {
        setTimeout(() => {
             // Actualizar datos de alertas desde Livewire si es necesario
             // Aquí asumimos que $alertasRecientes se actualiza y el componente se re-renderiza.
             // Si se necesita una actualización más granular, se podría usar un evento Livewire.emitUp/emit
             // y escucharlo aquí para actualizar `alertasReales` específicamente.
             alertasReales = @json($alertasRecientes); // Re-evaluar con datos actualizados de Livewire
             actualizarTiemposRelativos(); // En caso de que se hayan agregado nuevas tarjetas
             cargarMarcadores(); // Cargar nuevos marcadores o actualizar existentes
             inicializarEventosTarjetas(); // Re-asignar eventos a nuevas tarjetas
             console.log('🔄 Sistema actualizado tras cambio de Livewire');
        }, 150); // Pequeño delay para asegurar re-renderizado completo
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