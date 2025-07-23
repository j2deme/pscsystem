<div class="py-6 mx-auto">
  <x-navbar />
  <x-livewire.monitoreo-layout :breadcrumb-items="[
    ['icon' => 'ti-home', 'url' => route('dashboard')],
    ['icon' => 'ti-car-crash', 'url' => route('siniestros.index'), 'label' => 'Siniestros'],
    ['icon' => 'ti-eye', 'label' => 'Detalle del Siniestro']
]" title-main="Detalle del Siniestro" help-text="Información completa y estado del siniestro seleccionado">
    <div class="max-w-5xl mx-auto">
      <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
        <div
          class="flex flex-col min-w-0 gap-4 p-6 bg-white shadow-md rounded-xl dark:bg-gray-800 border-2 {{ $badgeGravedadInfo['border'] }}">
          <div class="flex items-center gap-3 mb-2">
            <span
              class="inline-flex items-center justify-center w-12 h-12 text-2xl text-blue-700 bg-blue-100 rounded-full">
              <i class="ti {{ $siniestro->tipo_siniestro === 'vehiculo' ? 'ti-car-crash' : 'ti-user' }}"></i>
            </span>
            <div>
              <div class="text-lg font-bold text-gray-800">
                Siniestro de {{ ucfirst($siniestro->tipo_siniestro) }}
              </div>
              <div class="text-base font-bold text-blue-600">
                <i class="mr-1 ti ti-calendar-event"></i>{{ $siniestro->fecha->format('d/m/Y') }}
              </div>
            </div>
          </div>
          @if($tipoInfo)
          <div class="mb-1 text-lg font-semibold text-gray-800">{{ $tipoInfo['label'] ?? $siniestro->tipo }}</div>
          <div class="mb-2 text-sm text-gray-600">{{ $tipoInfo['descripcion'] ?? '' }}</div>
          @else
          <span class="text-gray-400">-</span>
          @endif
          <div class="flex items-center gap-2 mt-2">
            <span
              class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-semibold border {{ $badgeGravedadInfo['border'] }} {{ $badgeGravedadInfo['badgeBg'] }} {{ $badgeGravedadInfo['textColor'] }}">
              <i class="ti {{ $badgeGravedadInfo['icon'] }} {{ $badgeGravedadInfo['textColor'] }}"></i>
              {{ ucfirst($gravedad) }}
            </span>
          </div>
        </div>
        <!-- Card: Unidad -->
        <div class="flex flex-col min-w-0 gap-4 p-6 bg-white shadow-md rounded-xl dark:bg-gray-800">
          <div class="flex items-center gap-2 mb-2 text-base font-bold text-blue-700">
            <i class="ti ti-car"></i> Unidad
          </div>
          @if($unidad)
          <dl class="space-y-1 text-gray-700 dark:text-gray-200">
            <div>
              <dt class="inline font-semibold">Placas:</dt>
              <dd class="inline ml-1">{{ $unidad->placas }}</dd>
            </div>
            <div>
              <dt class="inline font-semibold">Marca:</dt>
              <dd class="inline ml-1">{{ $unidad->marca }}</dd>
            </div>
            <div>
              <dt class="inline font-semibold">Modelo:</dt>
              <dd class="inline ml-1">{{ $unidad->modelo }}</dd>
            </div>
          </dl>
          @else
          <span class="text-gray-400">Sin unidad</span>
          @endif
        </div>
        <!-- Card: Elementos involucrados -->
        <div class="relative flex flex-col min-w-0 gap-4 p-6 bg-white shadow-md rounded-xl dark:bg-gray-800">
          <div class="flex items-center gap-2 mb-2 text-base font-bold text-blue-700">
            <i class="ti ti-users"></i> Elementos involucrados
            @if($usuarios && count($usuarios) > 1)
            <span
              class="inline-flex items-center justify-center px-2 py-1 ml-auto text-sm font-bold leading-none text-white bg-blue-500 rounded-full shadow">
              {{ count($usuarios) }}
            </span>
            @endif
          </div>
          @if($usuarios && count($usuarios) > 0)
          @if(count($usuarios) === 1)
          @php $usuario = $usuarios[0]; @endphp
          <div class="flex flex-col items-center justify-center gap-2 py-4">
            <span
              class="inline-flex items-center justify-center w-16 h-16 text-4xl text-blue-700 bg-blue-100 rounded-full shadow">
              <i class="ti ti-user"></i>
            </span>
            <div class="mt-2 text-lg font-bold text-gray-800">{{ $usuario->name }}</div>
            <div class="text-sm font-medium text-gray-500">{{ $usuario->rol ?? 'Elemento' }}</div>
          </div>
          @else
          <ul
            class="overflow-hidden overflow-y-auto border border-gray-100 divide-y divide-gray-200 rounded-lg max-h-56 dark:divide-gray-700 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
            @foreach($usuarios as $usuario)
            <li class="flex items-center gap-3 px-4 py-3">
              <span
                class="inline-flex items-center justify-center w-10 h-10 text-2xl text-blue-700 bg-blue-100 rounded-full">
                <i class="ti ti-user"></i>
              </span>
              <div class="flex flex-col min-w-0">
                <span class="font-semibold text-gray-800 truncate">{{ $usuario->name }}</span>
                <span class="text-xs text-gray-500 truncate">{{ $usuario->rol ?? 'Elemento' }}</span>
              </div>
            </li>
            @endforeach
          </ul>
          @endif
          @else
          <span class="text-gray-400">Sin personal</span>
          @endif
        </div>
      </div>
      <div class="grid grid-cols-1 gap-6 mt-8 md:grid-cols-2">
        <!-- Card: Descripción -->
        @php
        $hasSeguimiento = !empty(trim($siniestro->seguimiento ?? ''));
        $hasCosto = !empty($siniestro->costo) && $siniestro->costo > 0;
        @endphp
        <div
          class="p-6 shadow-md bg-gray-50 rounded-xl dark:bg-gray-900 @if(!$hasSeguimiento && !$hasCosto) md:col-span-2 @endif">
          <div class="flex items-center gap-2 mb-2 font-bold text-blue-700 dark:text-blue-300">
            <i class="text-lg ti ti-file-description"></i>
            Descripción
          </div>
          <div
            class="p-3 overflow-y-auto text-gray-800 bg-white border border-gray-200 rounded-lg dark:text-gray-200 max-h-32 dark:bg-gray-800 dark:border-gray-700">
            {!! nl2br(e($siniestro->descripcion)) !!}
          </div>
        </div>

        <!-- Card: Seguimiento y costo (solo para vehículo) -->
        @if($siniestro->tipo_siniestro === 'vehiculo' && ($hasSeguimiento || $hasCosto))
        @if($hasSeguimiento && $hasCosto)
        <div class="p-6 shadow-md bg-gray-50 rounded-xl dark:bg-gray-900">
          <div class="flex items-center gap-2 mb-2 font-bold text-blue-700 dark:text-blue-300">
            <i class="text-lg ti ti-clipboard-check"></i>
            Seguimiento
          </div>
          <div
            class="p-3 mb-4 overflow-y-auto text-gray-800 bg-white border border-gray-200 rounded-lg dark:text-gray-200 max-h-32 dark:bg-gray-800 dark:border-gray-700">
            {!! nl2br(e($siniestro->seguimiento)) !!}
          </div>
          @if ($hasCosto)
          <div class="flex items-center gap-2 mt-2">
            <i class="text-green-600 ti ti-currency-dollar"></i>
            <span class="font-semibold text-gray-800">${{ number_format($siniestro->costo, 2) }}</span>
          </div>
          @endif
        </div>
        @elseif($hasCosto && !$hasSeguimiento)
        <div class="p-6 shadow-md bg-gray-50 rounded-xl dark:bg-gray-900">
          <div class="flex items-center gap-2 mb-2 font-bold text-blue-700 dark:text-blue-300">
            <i class="text-lg ti ti-currency-dollar"></i>
            Costo
          </div>
          <div class="flex items-center justify-center w-full h-24 mb-2">
            <span class="text-5xl font-bold text-gray-800">${{
              number_format($siniestro->costo, 2)
              }}</span>
          </div>
        </div>
        @endif
        @endif
      </div>
      <div class="flex justify-end max-w-4xl gap-4 mx-auto mt-8">
        <a href="{{ route('siniestros.index', ['editar' => $siniestro->id, 'return' => 'detalle']) }}"
          class="flex items-center gap-2 px-5 py-2 text-white bg-blue-400 rounded-lg shadow hover:bg-blue-500"
          title="Editar">
          <i class="ti ti-edit"></i> Editar
        </a>
        <a href="{{ route('siniestros.index') }}"
          class="flex items-center gap-2 px-5 py-2 text-gray-700 bg-gray-100 border border-gray-300 rounded-lg shadow hover:bg-gray-200"
          title="Regresar">
          <i class="ti ti-arrow-left"></i> Regresar
        </a>
      </div>
    </div>
  </x-livewire.monitoreo-layout>
</div>