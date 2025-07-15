<div>
  <div class="p-4">
    <!-- Header Simple -->
    <div class="mb-6">
      <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
        Mapa de Monitoreo
      </h1>
      <p class="mt-2 text-gray-600 dark:text-gray-400">
        Visualización en tiempo real de alertas
      </p>
    </div>

    <!-- Layout con más espacio para el mapa -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
      <!-- Panel de Alertas Simplificado (1/3 del espacio) -->
      <div class="border border-gray-200 rounded-lg md:col-span-1 bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
        <div class="p-4 border-b border-gray-300 dark:border-gray-600">
          <div class="flex items-center justify-between">
            <div class="flex items-center justify-between min-h-8 w-full">
              <h2 class="text-base font-semibold text-gray-900 dark:text-white">
                Alertas Recientes
              </h2>
              <div class="text-sm text-gray-600 dark:text-gray-400">
                <span
                  class="inline-block px-3 py-1 rounded-full bg-gray-800 text-white dark:bg-white/80 dark:text-gray-900 font-semibold text-base shadow">
                  {{ $totalAlertas ?? 0 }}
                </span>
              </div>
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
            <div
              class="p-3 transition-all duration-200 border-l-4 {{ $colores['border'] ?? 'border-red-600' }} {{ $colores['bg'] ?? 'bg-red-50' }} rounded cursor-pointer hover:shadow-md hover:scale-[1.02]"
              data-alerta-id="{{ $alerta['id'] ?? $index }}">
              <div class="flex items-start justify-between">
                <div class="flex items-start gap-3">
                  <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                      <p class="font-semibold text-gray-900 dark:text-white">{{ $alerta['usuario'] ?? 'Usuario
                        desconocido' }}</p>
                      <span
                        class="inline-block px-2 py-1 text-xs font-medium text-white rounded-full {{ $colores['badge'] ?? 'bg-gray-600' }}">
                        {{ $estadoCompleto['texto'] ?? 'N/A' }}
                      </span>
                    </div>
                    <p class="text-sm {{ $colores['text'] ?? 'text-red-700' }} font-medium">📍 {{ $alerta['ubicacion']
                      ?? 'Ubicación no disponible' }}</p>
                    <p class="text-xs text-gray-500">🕐 {{ $alerta['tiempo'] ?? 'Hora no disponible' }} •
                      <span data-timestamp="{{ $alerta['timestamp_creacion'] ?? '' }}" data-minutos="{{ $urgencia }}">
                        @if($urgencia < 60) hace {{ $urgencia }} min @else hace {{ floor($urgencia / 60) }} h @endif
                          </span>
                    </p>
                  </div>
                </div>
                <div class="text-right">
                  <span
                    class="inline-block w-4 h-4 {{ $colores['indicator'] ?? 'bg-gray-600' }} rounded-full {{ ($colores['animate'] ?? false) ? 'animate-pulse' : '' }}"></span>
                  <p class="mt-1 text-xs font-bold {{ $colores['text'] ?? 'text-gray-600' }}">{{
                    $estadoCompleto['texto'] ?? ($colores['texto'] ?? '') }}</p>
                </div>
              </div>
            </div>
            @endforeach
            @else
            <div class="p-3 rounded bg-gray-50 dark:bg-gray-700">
              <p class="font-semibold text-gray-900 dark:text-white">No hay alertas recientes</p>
              <p class="text-sm text-gray-600 dark:text-gray-400">Las nuevas alertas aparecerán aquí</p>
            </div>
            @endif
          </div>
        </div>
      </div>

      <!-- Mapa (2/3 del espacio) -->
      <div class="border border-gray-200 rounded-lg md:col-span-2 bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
        <div class="relative">
          <div
            class="flex items-center justify-between p-4 border-b border-gray-300 dark:border-gray-600 min-h-8 w-full">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">
              Vista del Mapa
            </h2>
            <!-- Escala de Urgencia en la barra del encabezado -->
            <div class="flex items-center gap-4 text-xs ml-4">
              <span class="font-medium text-gray-600 dark:text-gray-400">Escala de Urgencia:</span>
              <div class="flex items-center gap-3">
                <div class="flex items-center gap-1">
                  <span class="w-2 h-2 bg-red-600 rounded-full"></span>
                  <span class="font-medium text-red-600">CRÍTICA</span>
                </div>
                <div class="flex items-center gap-1">
                  <span class="w-2 h-2 bg-orange-500 rounded-full"></span>
                  <span class="font-medium text-orange-600">ALTA</span>
                </div>
                <div class="flex items-center gap-1">
                  <span class="w-2 h-2 bg-yellow-500 rounded-full"></span>
                  <span class="font-medium text-yellow-600">MEDIA</span>
                </div>
                <div class="flex items-center gap-1">
                  <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                  <span class="font-medium text-blue-600">BAJA</span>
                </div>
                <div class="flex items-center gap-1">
                  <span class="w-2 h-2 bg-gray-500 rounded-full"></span>
                  <span class="font-medium text-gray-600">ANTIGUA</span>
                </div>
              </div>
            </div>
            <button onclick="centrarVistaMapa()"
              class="p-2 bg-gray-600 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-400 ml-4"
              title="Centrar Vista">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <polyline points="4,8 4,4 8,4" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"
                  stroke-linejoin="round" />
                <polyline points="16,4 20,4 20,8" stroke="currentColor" stroke-width="2" fill="none"
                  stroke-linecap="round" stroke-linejoin="round" />
                <polyline points="20,16 20,20 16,20" stroke="currentColor" stroke-width="2" fill="none"
                  stroke-linecap="round" stroke-linejoin="round" />
                <polyline points="8,20 4,20 4,16" stroke="currentColor" stroke-width="2" fill="none"
                  stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </button>
          </div>
        </div>
        <div class="p-4">
          <div id="mapaContainer" class="w-full bg-gray-200 rounded h-96 dark:bg-gray-700"
            style="height: 400px; min-height: 400px;">
            <!-- El mapa se cargará aquí -->
          </div>
          <div id="mapaEstado" class="mt-2 text-xs text-gray-500">
            Inicializando mapa...
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@push('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
  integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<style>
  #mapaContainer {
    height: 400px !important;
    min-height: 400px !important;
    width: 100% !important;
  }

  .leaflet-container {
    height: 100% !important;
    width: 100% !important;
  }

  /* Estilos para marcadores con animación */
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

  /* Definir animaciones personalizadas para compatibilidad */
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
</style>
@endpush

@push('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
  integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<!-- Day.js para manejo de fechas y tiempo relativo -->
<script src="https://cdn.jsdelivr.net/npm/dayjs@1.11.10/dayjs.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1.11.10/plugin/relativeTime.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1.11.10/plugin/utc.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1.11.10/plugin/timezone.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1.11.10/plugin/updateLocale.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1.11.10/locale/es.min.js"></script>

<script>
  // Configurar Day.js para tiempo relativo
  dayjs.extend(dayjs_plugin_relativeTime);
  dayjs.extend(dayjs_plugin_utc);
  dayjs.extend(dayjs_plugin_timezone);
  dayjs.extend(dayjs_plugin_updateLocale);
  dayjs.locale('es');
  
  // Configurar mensajes personalizados para formato abreviado
  dayjs.updateLocale('es', {
    relativeTime: {
      future: 'en %s',
      past: 'hace %s',
      s: 'un momento',
      m: '1 min',
      mm: '%d min',
      h: '1 h',
      hh: '%d hrs',
      d: '1 día',
      dd: '%d días',
      M: '1 mes',
      MM: '%d meses',
      y: '1 año',
      yy: '%d años'
    }
  });

  function obtenerUrgenciaYColores(minutos) {
    if (minutos <= 10) return { texto: 'CRÍTICA', border: 'border-red-600', bg: 'bg-red-50', badge: 'bg-red-600', text: 'text-red-700', indicator: 'bg-red-600', animate: true };
    if (minutos <= 20) return { texto: 'ALTA', border: 'border-orange-500', bg: 'bg-orange-50', badge: 'bg-orange-500', text: 'text-orange-700', indicator: 'bg-orange-500', animate: true };
    if (minutos <= 30) return { texto: 'MEDIA', border: 'border-yellow-500', bg: 'bg-yellow-50', badge: 'bg-yellow-500', text: 'text-yellow-700', indicator: 'bg-yellow-500', animate: false };
    if (minutos <= 60) return { texto: 'BAJA', border: 'border-blue-500', bg: 'bg-blue-50', badge: 'bg-blue-500', text: 'text-blue-700', indicator: 'bg-blue-500', animate: false };
    return { texto: 'ANTIGUA', border: 'border-gray-500', bg: 'bg-gray-50', badge: 'bg-gray-500', text: 'text-gray-700', indicator: 'bg-gray-500', animate: false };
  }

  // Actualiza tiempos y colores en tarjetas (sin funciones redundantes)
  function actualizarTiemposRelativos() {
    document.querySelectorAll('[data-timestamp]').forEach(el => {
      const ts = parseInt(el.getAttribute('data-timestamp'));
      if (!ts || isNaN(ts)) return;
      const fecha = dayjs.unix(ts);
      const minutos = dayjs().diff(fecha, 'minute');
      el.textContent = fecha.fromNow();
      actualizarColoresTarjeta(el, minutos);
    });
  }

  // Actualiza colores de la tarjeta según minutos
  function actualizarColoresTarjeta(elTiempo, minutos) {
    const tarjeta = elTiempo.closest('[data-alerta-id]');
    if (!tarjeta) return;
    const c = obtenerUrgenciaYColores(minutos);
    tarjeta.className = tarjeta.className.replace(/border-l-4\s+border-\w+-\d+/, `border-l-4 ${c.border}`);
    tarjeta.className = tarjeta.className.replace(/bg-\w+-\d+/, c.bg);
    const badge = tarjeta.querySelector('.rounded-full');
    if (badge) {
      badge.className = badge.className.replace(/bg-\w+-\d+/, c.badge);
      badge.textContent = c.texto;
    }
    const textoUbicacion = tarjeta.querySelector('p.font-medium');
    if (textoUbicacion) textoUbicacion.className = textoUbicacion.className.replace(/text-\w+-\d+/, `${c.text} font-medium`);
    const indicador = tarjeta.querySelector('.w-4.h-4');
    if (indicador) {
      indicador.className = indicador.className.replace(/bg-\w+-\d+/, c.indicator);
      indicador.classList.toggle('animate-pulse', c.animate);
    }
    const textoUrgencia = tarjeta.querySelector('.text-right p');
    if (textoUrgencia) {
      textoUrgencia.className = textoUrgencia.className.replace(/text-\w+-\d+/, `mt-1 text-xs font-bold ${c.text}`);
      textoUrgencia.textContent = c.texto;
    }
  }


  // Variables globales
  let mapa, marcadores = [], mapaInicializado = false;
  let alertasReales = @json($alertasRecientes);

  // Resalta y/o centra el marcador según evento
  function manejarTarjetaAlerta(event, alertaId, tipo) {
    if (!marcadores || marcadores.length === 0) return;
    if (tipo === 'hover' || tipo === 'out') {
      marcadores.forEach(marcador => {
        const iconDiv = marcador._icon?.querySelector('.relative.z-10');
        if (!iconDiv) return;
        if (tipo === 'hover') {
          if (marcador.options && marcador.options.alertaId == alertaId) {
            iconDiv.style.opacity = '1';
            iconDiv.style.transform = 'scale(1.3)';
            iconDiv.style.zIndex = '50';
          } else {
            iconDiv.style.opacity = '0.3';
            iconDiv.style.transform = 'scale(1)';
            iconDiv.style.zIndex = '10';
            iconDiv.classList.remove('animate-pulse');
          }
        } else {
          iconDiv.style.opacity = '1';
          iconDiv.style.transform = 'scale(1)';
          iconDiv.style.zIndex = '10';
        }
      });
    } else if (tipo === 'click') {
      event.preventDefault();
      event.stopPropagation();
      if (mapa && mapaInicializado) {
        const marcador = marcadores.find(m => m.options && m.options.alertaId == alertaId);
        if (marcador) {
          mapa.setView(marcador.getLatLng(), 16, { animate: true });
          if (marcador.openPopup) marcador.openPopup();
        }
        setTimeout(() => mapa.invalidateSize(), 100);
      }
    }
  }

  // Inicialización principal
  function inicializarSistemaCompleto() {
    if (typeof L === 'undefined') return setTimeout(inicializarSistemaCompleto, 100);
    if (!mapaInicializado) inicializarMapa();
    inicializarTiemposRelativos();
    inicializarEventosTarjetas();
  }

  // Inicializa los eventos de las tarjetas de alerta
  function inicializarEventosTarjetas() {
    document.querySelectorAll('[data-alerta-id]').forEach((tarjeta) => {
      const alertaId = tarjeta.getAttribute('data-alerta-id');
      tarjeta.onmouseenter = e => manejarTarjetaAlerta(e, alertaId, 'hover');
      tarjeta.onmouseleave = e => manejarTarjetaAlerta(e, alertaId, 'out');
      tarjeta.onclick = e => manejarTarjetaAlerta(e, alertaId, 'click');
    });
  }

  // Inicializar cuando DOM esté listo
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', inicializarSistemaCompleto);
  } else {
    setTimeout(inicializarSistemaCompleto, 100);
  }

  function inicializarMapa() {
    if (mapaInicializado && mapa && mapa._container === document.getElementById('mapaContainer')) {
      console.log('⚠️ Mapa ya inicializado y contenedor correcto');
      return;
    }

    // Si el mapa existe pero el contenedor fue reemplazado, destruir la instancia
    if (mapa && mapa._container !== document.getElementById('mapaContainer')) {
      try {
        mapa.remove();
      } catch (e) {
        console.warn('No se pudo remover el mapa anterior:', e);
      }
      mapa = undefined;
      mapaInicializado = false;
    }

    try {
      console.log('🗺️ Inicializando mapa...');
      const contenedor = document.getElementById('mapaContainer');
      if (!contenedor) {
        console.error('❌ Contenedor del mapa no encontrado');
        return;
      }
      // Crear el mapa
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
      mapaInicializado = true;
      setTimeout(() => {
        mapa.invalidateSize();
        cargarMarcadores(false);
        console.log('✅ Mapa inicializado correctamente con todos los marcadores visibles');
        actualizarEstadoMapa('Mapa listo - todos los marcadores visibles');
      }, 200);
    } catch (error) {
      console.error('❌ Error al inicializar mapa:', error);
      actualizarEstadoMapa(`Error: ${error.message}`);
      mapaInicializado = false;
    }
  }
  
  function actualizarEstadoMapa(mensaje) {
    const estadoElement = document.getElementById('mapaEstado');
    if (estadoElement) {
      estadoElement.textContent = mensaje;
    }
  }
  
  function cargarMarcadores(forzarActualizacion = false) {
    if (!mapa || !mapaInicializado) {
      console.warn('⚠️ Mapa no listo para cargar marcadores');
      return;
    }
    marcadores.forEach(marcador => mapa.removeLayer(marcador));
    marcadores = [];
    if (!alertasReales || alertasReales.length === 0) {
      actualizarEstadoMapa('Sin alertas para mostrar');
      return;
    }
    console.log(`📍 Cargando ${alertasReales.length} marcadores...`);
    const coordenadasMarcadores = [];
    alertasReales.forEach((alerta, index) => {
      if (!alerta.latitud || !alerta.longitud || 
          isNaN(alerta.latitud) || isNaN(alerta.longitud)) {
        console.warn(`Alerta ${index} tiene coordenadas inválidas`);
        return;
      }
      const iconoConfig = obtenerIconoPorEstado(alerta.estado, alerta.minutosTranscurridos);
      const alertaId = alerta.id ?? index;
      // Asignar zIndexOffset según gravedad (Leaflet)
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
          iconSize: [32, 32],
          iconAnchor: [16, 16]
        }),
        alertaId: alertaId,
        zIndexOffset: zIndexOffset
      }).addTo(mapa);
      
      marcador.bindPopup(`
        <div class="p-3">
          <h3 class="font-bold">${alerta.usuario}</h3>
          <p class="text-sm">📍 ${alerta.ubicacion}</p>
          <p class="text-xs text-gray-500">🕐 ${alerta.tiempo}</p>
          <span class="inline-block px-2 py-1 text-xs text-white rounded ${iconoConfig.bgColor}">
            ${iconoConfig.estadoTexto}
          </span>
        </div>
      `);
      
      marcadores.push(marcador);
      coordenadasMarcadores.push([alerta.latitud, alerta.longitud]);
    });
    
    // Ajustar vista del mapa para mostrar todos los marcadores
    if (coordenadasMarcadores.length > 0) {
      ajustarVistaParaTodosLosMarcadores(coordenadasMarcadores);
    }
    // Inicializar eventos de tarjetas justo después de cargar marcadores
    inicializarEventosTarjetas();
    actualizarEstadoMapa(`${marcadores.length} alertas cargadas y visibles`);
  }

  // Función para ajustar la vista del mapa para mostrar todos los marcadores
  function ajustarVistaParaTodosLosMarcadores(coordenadas) {
    try {
      if (coordenadas.length === 0) {
        console.warn('No hay coordenadas para ajustar vista');
        return;
      }
      
      if (coordenadas.length === 1) {
        // Si solo hay un marcador, centrar en él con zoom apropiado
        mapa.setView(coordenadas[0], 15);
        console.log('✅ Vista centrada en marcador único');
      } else {
        // Si hay múltiples marcadores, usar fitBounds para mostrar todos
        const grupo = new L.featureGroup(marcadores);
        
        // Calcular bounds con padding adicional
        const bounds = grupo.getBounds();
        
        // Ajustar vista con opciones optimizadas
        mapa.fitBounds(bounds, {
          padding: [30, 30], // Padding generoso para asegurar visibilidad
          maxZoom: 16, // Zoom máximo para no acercarse demasiado
          animate: true, // Animación suave
          duration: 1.0 // Duración de animación
        });
        
        console.log(`✅ Vista ajustada para ${coordenadas.length} marcadores`);
      }
      
      // Invalidar tamaño del mapa después del ajuste
      setTimeout(() => {
        mapa.invalidateSize();
      }, 100);
      
    } catch (error) {
      console.error('Error al ajustar vista del mapa:', error);
      // Fallback: centrar en coordenadas por defecto de Monterrey
      mapa.setView([25.6866, -100.3161], 13);
      console.log('🔄 Aplicado fallback: vista centrada en Monterrey');
    }
  }

  function obtenerIconoPorEstado(estado, minutosTranscurridos) {
    // Calcular estado dinámico basado en tiempo real
    // Usar la función unificada para obtener texto y colores
    const urgencia = obtenerUrgenciaYColores(minutosTranscurridos);
    // Mapear a la estructura esperada por los marcadores
    let icon = '⏱', animation = '', pulse = false;
    switch (urgencia.texto) {
      case 'CRÍTICA': icon = '‼'; animation = 'animate-pulse'; pulse = true; break;
      case 'ALTA': icon = '❗'; animation = 'animate-pulse'; pulse = true; break;
      case 'MEDIA': icon = '⚠'; break;
      case 'BAJA': icon = '⏱'; break;
      case 'ANTIGUA': icon = '?'; break;
    }
    return {
      bgColor: urgencia.badge,
      textColor: urgencia.text,
      icon,
      estadoTexto: urgencia.texto,
      animation,
      pulse
    };
  }

  // Función para centrar la vista del mapa desde el botón, sin Livewire
  function centrarVistaMapa() {
    const contenedor = document.getElementById('mapaContainer');
    if (!contenedor) {
      console.error('❌ Contenedor del mapa no encontrado');
      return;
    }
    // Limpiar el contenedor y destruir el mapa si existe
    if (mapa && mapa._container) {
      try { mapa.remove(); } catch (e) { console.warn('No se pudo remover el mapa anterior:', e); }
      mapa = undefined;
      mapaInicializado = false;
    }
    contenedor.innerHTML = '';
    // Forzar tamaño correcto del contenedor
    contenedor.style.height = '400px';
    contenedor.style.minHeight = '400px';
    contenedor.style.width = '100%';
    // Recalcular minutosTranscurridos en alertasReales antes de reinicializar el mapa y marcadores
    if (Array.isArray(alertasReales)) {
      alertasReales.forEach(alerta => {
        if (alerta.timestamp_creacion) {
          const fecha = dayjs.unix(parseInt(alerta.timestamp_creacion));
          alerta.minutosTranscurridos = dayjs().diff(fecha, 'minute');
        }
      });
    }
    // Inicializar el mapa y ajustar la vista inmediatamente, sin delay innecesario
    mapaInicializado = false;
    inicializarMapa();
    // Esperar a que el mapa esté listo y los marcadores cargados antes de ajustar la vista
    let intentos = 0;
    function esperarMarcadoresYAjustarVista() {
      if (mapa && mapaInicializado && marcadores.length > 0) {
        const grupo = new L.featureGroup(marcadores);
        const bounds = grupo.getBounds();
        mapa.fitBounds(bounds, {
          padding: [30, 30],
          maxZoom: 16,
          animate: true,
          duration: 1.0
        });
        setTimeout(() => mapa.invalidateSize(), 100);
        setTimeout(() => mapa.invalidateSize(), 500);
        actualizarEstadoMapa(`Vista ajustada para ${marcadores.length} alertas`);
      } else if (intentos < 20) {
        intentos++;
        setTimeout(esperarMarcadoresYAjustarVista, 100);
      } else {
        mapa.setView([25.6866, -100.3161], 13);
        setTimeout(() => mapa.invalidateSize(), 100);
        actualizarEstadoMapa('Mapa centrado en ubicación por defecto');
      }
    }
    esperarMarcadoresYAjustarVista();
  }
  
  // Actualizar después de cambios de Livewire
  document.addEventListener('livewire:updated', function () {
    setTimeout(() => {
      actualizarTiemposRelativos();
      if (mapa && mapaInicializado) {
        cargarMarcadores(false);
      }
      
      console.log('🔄 Sistema actualizado tras cambio de Livewire');
    }, 100);
  });

  console.log('📍 Sistema de mapa y tiempos cargado');
  
  // Sistema unificado de tiempos relativos
  let tiemposInicializados = false;
  
  function inicializarTiemposRelativos() {
    if (tiemposInicializados) return;
    
    try {
      actualizarTiemposRelativos();
      tiemposInicializados = true;
      console.log('⏰ Tiempos relativos inicializados');
      
      // Configurar actualización automática
      setInterval(() => {
        actualizarTiemposRelativos();
        // Recalcular minutosTranscurridos en alertasReales antes de actualizar marcadores
        if (Array.isArray(alertasReales)) {
          alertasReales.forEach(alerta => {
            if (alerta.timestamp_creacion) {
              const fecha = dayjs.unix(parseInt(alerta.timestamp_creacion));
              alerta.minutosTranscurridos = dayjs().diff(fecha, 'minute');
            }
          });
        }
        if (mapa && mapaInicializado) {
          cargarMarcadores(false);
        }
        console.log('🔄 Tiempos, colores y marcadores actualizados automáticamente');
      }, 30000);
      
    } catch (error) {
      console.error('❌ Error al inicializar tiempos:', error);
    }
  }
</script>
@endpush