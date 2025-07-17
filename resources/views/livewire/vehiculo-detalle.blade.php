<x-livewire.monitoreo-layout :breadcrumb-items="[
        ['icon' => 'ti-home', 'url' => route('dashboard')],
        ['icon' => 'ti-car', 'url' => route('vehiculos.index'), 'label' => 'Control de Vehículos'],
        ['icon' => 'ti-id', 'label' => 'Detalle: ' . $unidad->placas]
    ]" title-main="Detalle de Vehículo" help-text="Información completa y estado actual del vehículo seleccionado">
  <div class="grid max-w-5xl grid-cols-1 gap-6 mx-auto md:grid-cols-3">
    <!-- Columna 1: Datos generales -->
    <div class="p-6 bg-white shadow-md rounded-xl dark:bg-gray-800">
      <div class="flex items-center gap-2 mb-3 text-base font-bold text-blue-700 dark:text-blue-300">
        <i class="text-lg ti ti-user"></i> Datos generales
      </div>
      <div class="space-y-2 text-gray-700 dark:text-gray-200">
        <div><span class="font-semibold">Propietario:</span> {{ $unidad->nombre_propietario }}</div>
        <div><span class="font-semibold">Zona:</span> {{ $unidad->zona }}</div>
        <div><span class="font-semibold">Asignación Punto:</span> {{ $unidad->asignacion_punto }}</div>
      </div>
    </div>
    <!-- Columna 2: Datos técnicos -->
    <div class="p-6 bg-white shadow-md rounded-xl dark:bg-gray-800">
      <div class="flex items-center gap-2 mb-3 text-base font-bold text-blue-700 dark:text-blue-300">
        <i class="text-lg ti ti-car"></i> Datos técnicos
      </div>
      <div class="space-y-2 text-gray-700 dark:text-gray-200">
        <div><span class="font-semibold">Marca:</span> {{ $unidad->marca }}</div>
        <div><span class="font-semibold">Modelo:</span> {{ $unidad->modelo }}</div>
        <div><span class="font-semibold">Kilometraje:</span> {{ is_numeric($unidad->kms) ? number_format($unidad->kms) :
          $unidad->kms }}</div>
      </div>
    </div>
    <!-- Columna 3: Placas y estado -->
    <div class="flex flex-col items-center justify-center p-6 bg-white shadow-md rounded-xl dark:bg-gray-800">
      <div class="flex items-center gap-2 mb-3 text-base font-bold text-blue-700 dark:text-blue-300">
        <i class="text-lg ti ti-id"></i> Placas
      </div>
      <div class="mb-3">
        <span
          class="inline-block px-4 py-2 font-mono text-xl font-bold tracking-widest text-gray-800 bg-white border border-gray-700 rounded shadow select-none">
          {{ $unidad->placas }}
        </span>
      </div>
      <div>
        @if($unidad->is_activo)
        <span
          class="inline-flex items-center px-4 py-2 text-base font-semibold text-green-700 bg-green-100 rounded-full">
          <i class="mr-2 ti ti-circle-check"></i> Activo
        </span>
        @else
        <span class="inline-flex items-center px-4 py-2 text-base font-semibold text-red-700 bg-red-100 rounded-full">
          <i class="mr-2 ti ti-circle-x"></i> Inactivo
        </span>
        @endif
      </div>
    </div>
  </div>
  <!-- Sección: Observaciones -->
  <div class="max-w-5xl p-6 mx-auto mt-8 shadow-md rounded-xl bg-gray-50 dark:bg-gray-900">
    <div class="flex items-center gap-2 mb-2 font-bold text-blue-700 dark:text-blue-300">
      <i class="text-lg ti ti-message-dots"></i>
      Observaciones
    </div>
    <div
      class="p-3 overflow-y-auto text-gray-800 bg-white border border-gray-200 rounded-lg dark:text-gray-200 max-h-40 dark:bg-gray-800 dark:border-gray-700">
      {!! nl2br(e($unidad->observaciones)) !!}
    </div>
  </div>
  <!-- Botones de acción -->
  <div class="flex justify-end max-w-5xl gap-4 mx-auto mt-8">
    <a href="{{ route('vehiculos.index', ['editar' => $unidad->id, 'return' => 'detalle']) }}"
      class="flex items-center gap-2 px-5 py-2 text-white bg-blue-400 rounded-lg shadow hover:bg-blue-500"
      title="Editar">
      <i class="ti ti-edit"></i> Editar
    </a>
    <a href="{{ route('vehiculos.index') }}"
      class="flex items-center gap-2 px-5 py-2 text-gray-700 bg-gray-100 border border-gray-300 rounded-lg shadow hover:bg-gray-200"
      title="Regresar">
      <i class="ti ti-arrow-left"></i> Regresar
    </a>
  </div>
</x-livewire.monitoreo-layout>