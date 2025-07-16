<div class="max-w-6xl px-4 py-6 mx-auto">
  <nav class="mb-4" aria-label="Breadcrumb">
    <ol class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
      <li>
        <a href="{{ route('admin.monitoreoDashboard') }}" class="hover:text-blue-600 dark:hover:text-blue-400"
          title="Dashboard Monitoreo">
          <svg class="inline-block w-5 h-5 align-middle" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 10V21h16V10" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3l8 7H4l8-7z" />
          </svg>
        </a>
      </li>
      <li>
        <span class="mx-2">/</span>
      </li>
      <li>
        <a href="{{ route('vehiculos.index') }}" class="text-blue-600 hover:underline dark:text-blue-400">Control de
          Vehículos</a>
      </li>
      <li>
        <span class="mx-2">/</span>
      </li>
      <li class="font-semibold text-gray-700 dark:text-gray-200">Detalle: {{ $unidad->placas }}</li>
    </ol>
  </nav>
  <h2 class="mb-6 text-xl font-semibold text-gray-800 dark:text-gray-100">Detalle de Vehículo</h2>
  <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
    <!-- Columna 1: Datos generales -->
    <div class="p-4 bg-white rounded-lg shadow dark:bg-gray-800">
      <div class="mb-2 text-lg font-semibold text-gray-700 dark:text-gray-200">Datos generales</div>
      <div class="space-y-2">
        <div><span class="font-medium">Propietario:</span> {{ $unidad->nombre_propietario }}</div>
        <div><span class="font-medium">Zona:</span> {{ $unidad->zona }}</div>
        <div><span class="font-medium">Asignación Punto:</span> {{ $unidad->asignacion_punto }}</div>
      </div>
    </div>
    <!-- Columna 2: Datos técnicos -->
    <div class="p-4 bg-white rounded-lg shadow dark:bg-gray-800">
      <div class="mb-2 text-lg font-semibold text-gray-700 dark:text-gray-200">Datos técnicos</div>
      <div class="space-y-2">
        <div><span class="font-medium">Marca:</span> {{ $unidad->marca }}</div>
        <div><span class="font-medium">Modelo:</span> {{ $unidad->modelo }}</div>
        <div><span class="font-medium">Kilometraje:</span> {{ is_numeric($unidad->kms) ? number_format($unidad->kms) :
          $unidad->kms }}</div>
      </div>
    </div>
    <!-- Columna 3: Placas y estado -->
    <div class="flex flex-col items-center justify-center p-4 bg-white rounded-lg shadow dark:bg-gray-800">
      <div class="mb-2 text-lg font-semibold text-gray-700 dark:text-gray-200">Placas</div>
      <div class="mb-3">
        <span
          class="inline-block px-3 py-1 font-mono text-lg font-bold tracking-widest text-gray-800 bg-white border border-gray-700 rounded shadow select-none">
          {{ $unidad->placas }}
        </span>
      </div>
      <div>
        @if($unidad->is_activo)
        <span class="inline-flex items-center px-3 py-1 text-sm font-semibold text-green-700 bg-green-100 rounded-full">
          Activo
        </span>
        @else
        <span class="inline-flex items-center px-3 py-1 text-sm font-semibold text-red-700 bg-red-100 rounded-full">
          Inactivo
        </span>
        @endif
      </div>
    </div>
  </div>
  <!-- Sección: Observaciones -->
  <div class="p-4 mt-6 rounded-lg shadow bg-gray-50 dark:bg-gray-900">
    <div class="mb-2">
      <span class="font-semibold text-gray-700 dark:text-gray-200">Observaciones</span>
    </div>
    <div
      class="p-2 overflow-y-auto text-gray-800 bg-white border border-gray-200 rounded dark:text-gray-200 max-h-40 dark:bg-gray-800 dark:border-gray-700">
      {{ $unidad->observaciones }}
    </div>
  </div>
  <!-- Botones de acción -->
  <div class="flex justify-end gap-4 mt-6">
    <a href="{{ route('vehiculos.index', ['editar' => $unidad->id]) }}"
      class="px-4 py-2 text-white bg-yellow-500 rounded hover:bg-yellow-600">Editar</a>
    <a href="{{ route('vehiculos.index') }}"
      class="px-4 py-2 text-white bg-gray-500 rounded hover:bg-gray-600">Regresar</a>
  </div>
</div>