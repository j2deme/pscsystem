<div class="py-6 mx-auto">
  <x-navbar />
  <x-livewire.monitoreo-layout :breadcrumb-items="$breadcrumbItems" :title-main="$titleMain" :help-text="$helpText">
    <div class="max-w-5xl mx-auto">
      <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
        <!-- Card: Información del Gasto -->
        <div
          class="flex flex-col min-w-0 gap-4 p-6 bg-white shadow-md rounded-xl dark:bg-gray-800 border-2 border-blue-200 dark:border-blue-700">
          <div class="flex items-center gap-3 mb-2">
            @php
            $tipo = strtolower($gasto->Tipo ?? '');
            $icono = match($tipo) {
            'gasolina', 'carga gasolina' => 'ti-gas-station',
            'viaticos', 'viáticos' => 'ti-wallet',
            default => 'ti-receipt-2'
            };
            $colorIcono = match($tipo) {
            'gasolina', 'carga gasolina' => 'text-blue-600 bg-blue-100',
            'viaticos', 'viáticos' => 'text-purple-600 bg-purple-100',
            default => 'text-gray-600 bg-gray-100'
            };
            @endphp
            <span class="inline-flex items-center justify-center w-12 h-12 text-2xl {{ $colorIcono }} rounded-full">
              <i class="ti {{ $icono }}"></i>
            </span>
            <div>
              <div class="text-lg font-bold text-gray-800 dark:text-gray-100">
                Gasto de {{ ucfirst($gasto->Tipo ?? 'N/A') }}
              </div>
              <div class="text-base font-bold text-blue-600 dark:text-blue-400">
                <i class="mr-1 ti ti-calendar-event"></i>{{ $gasto->Fecha->format('d/m/Y') }}
                <span class="ml-2 text-gray-500 dark:text-gray-400">{{ $gasto->Hora }}</span>
              </div>
            </div>
          </div>

          <div class="mt-2">
            <div class="text-3xl font-bold text-gray-900 dark:text-white">
              ${{ number_format($gasto->Monto, 2) }}
            </div>
            <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
              Monto total del gasto
            </div>
          </div>

          @if($gasto->Evidencia)
          <div class="mt-3">
            <a href="{{ Storage::disk('public')->url($gasto->Evidencia) }}" target="_blank"
              class="inline-flex items-center gap-2 px-4 py-2 text-sm text-blue-700 bg-blue-100 rounded-lg hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-100 dark:hover:bg-blue-800">
              <i class="ti ti-photo"></i> Ver evidencia
            </a>
          </div>
          @endif
        </div>

        <!-- Card: Usuario -->
        <div class="flex flex-col min-w-0 gap-4 p-6 bg-white shadow-md rounded-xl dark:bg-gray-800">
          <div class="flex items-center gap-2 mb-2 text-base font-bold text-blue-700 dark:text-blue-300">
            <i class="ti ti-user"></i> Usuario
          </div>
          @if($usuario)
          <div class="flex flex-col items-center justify-center gap-2 py-4">
            <span
              class="inline-flex items-center justify-center w-16 h-16 text-4xl text-blue-700 bg-blue-100 rounded-full shadow dark:bg-blue-900 dark:text-blue-100">
              <i class="ti ti-user"></i>
            </span>
            <div class="mt-2 text-lg font-bold text-gray-800 dark:text-gray-100">{{ $usuario->name }}</div>
            @if($usuario->rol)
            <span
              class="inline-flex items-center px-3 py-1 text-sm font-semibold text-blue-700 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-100">
              {{ \Illuminate\Support\Str::title(strtolower($usuario->rol)) }}
            </span>
            @endif
            @if($usuario->punto || ($usuario->solicitudAlta && $usuario->solicitudAlta->punto))
            <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
              Punto: {{ $usuario->punto ?? $usuario->solicitudAlta->punto }}
            </div>
            @endif
          </div>
          @else
          <div
            class="flex flex-col items-center justify-center h-[170px] bg-blue-50 dark:bg-gray-700 rounded-lg border-2 border-dashed border-blue-200 p-4 dark:border-gray-600">
            <span
              class="inline-flex items-center justify-center w-16 h-16 mb-2 text-4xl text-blue-300 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300">
              <i class="ti ti-user-off"></i>
            </span>
            <div class="text-lg font-semibold text-blue-400 dark:text-blue-300">Usuario no disponible</div>
          </div>
          @endif
        </div>

        <!-- Card: Detalles Específicos -->
        <div class="flex flex-col min-w-0 gap-4 p-6 bg-white shadow-md rounded-xl dark:bg-gray-800">
          <div class="flex items-center gap-2 mb-2 text-base font-bold text-blue-700 dark:text-blue-300">
            <i class="ti ti-list-details"></i> Detalles
          </div>
          @if($gasto->Tipo === 'Gasolina')
          <div class="space-y-4">
            @if($gasto->Km !== null)
            <div class="flex items-center">
              <i class="ti ti-road-sign text-blue-500 mr-2"></i>
              <span class="font-medium text-gray-700 dark:text-gray-300">Kilometraje:</span>
              <span class="ml-2 font-mono text-gray-900 dark:text-gray-100">
                {{ number_format($gasto->Km, 0, '.', ',') }} Km
              </span>
            </div>
            @endif

            @php
            $antes = $gasto->Gasolina_antes_carga !== null ? (int)round($gasto->Gasolina_antes_carga) : null;
            $despues = $gasto->Gasolina_despues_carga !== null ? (int)round($gasto->Gasolina_despues_carga) : null;

            // Determinar el máximo para la escala
            $maxValue = max(
            $antes ?? 0,
            $despues ?? 0,
            1
            );

            // Limitar a un máximo razonable para la visualización
            // Determinar el tope dinámicamente según el valor más alto registrado
            $nivelesPosibles = [4, 8, 10, 16];
            $maxRegistrado = max($antes ?? 0, $despues ?? 0, 1);

            // Buscar el nivel posible más cercano hacia arriba
            $scaleMax = collect($nivelesPosibles)
            ->filter(fn($n) => $n >= $maxRegistrado)
            ->sort()
            ->first() ?? max($nivelesPosibles);
            @endphp

            @if($antes !== null || $despues !== null)
            <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
              <div class="flex items-center mb-3">
                <i class="ti ti-gas-station text-blue-700 mr-2"></i>
                <span class="font-medium text-gray-700 dark:text-gray-300">Nivel de Gasolina:</span>
              </div>

              <!-- Escala visual -->
              <div class="mb-4">
                <div class="flex justify-between mb-1 text-xs text-gray-500 dark:text-gray-400">
                  <span>0</span>
                  <span>{{ $scaleMax }}</span>
                </div>

                <!-- Barra de escala -->
                <div class="relative h-6 bg-gray-200 rounded-full dark:bg-gray-700 overflow-hidden">
                  <!-- Fondo de la barra -->
                  <div class="absolute inset-0 flex">
                    @for ($i = 0; $i < $scaleMax; $i++) <div
                      class="flex-1 border-r border-gray-300 dark:border-gray-600">
                  </div>
                  @endfor
                </div>

                <!-- Nivel después de carga (encima) -->
                @if($despues !== null)
                <div class="absolute inset-y-0 left-0 bg-green-500 dark:bg-green-600 rounded-l-full"
                  style="width: {{ $despues > 0 ? (($despues / $scaleMax) * 100) : 0 }}%"></div>
                @endif

                <!-- Nivel antes de carga (fondo) -->
                @if($antes !== null)
                <div class="absolute inset-y-0 left-0 bg-amber-200 dark:bg-amber-900 rounded-l-full"
                  style="width: {{ $antes > 0 ? (($antes / $scaleMax) * 100) : 0 }}%"></div>
                @endif


                <!-- Marcadores de valores -->
                @if($antes !== null)
                <div class="absolute top-0 h-full w-0.5 bg-amber-600 dark:bg-amber-400"
                  style="left: {{ (($antes / $scaleMax) * 100) }}%"></div>
                @endif

                @if($despues !== null && $despues !== $antes)
                <div class="absolute top-0 h-full w-0.5 bg-green-700 dark:bg-green-400"
                  style="left: {{ (($despues / $scaleMax) * 100) }}%"></div>
                @endif
              </div>

              <!-- Etiquetas de valores -->
              {{-- <div class="flex justify-between mt-1 text-xs">
                @if($antes !== null)
                <div class="flex items-center" style="margin-left: {{ max(0, (($antes / $scaleMax) * 100) - 5) }}%">
                  <i class="ti ti-arrow-down-left text-amber-600 dark:text-amber-400 mr-1"></i>
                  <span class="font-medium text-amber-700 dark:text-amber-300">{{ $antes }}</span>
                </div>
                @endif

                @if($despues !== null && $despues !== $antes)
                <div class="flex items-center"
                  style="margin-left: {{ max(0, (($despues / $scaleMax) - ($antes  / $scaleMax)) * 100 - 5) }}%">
                  <i class="ti ti-arrow-up-right text-green-600 dark:text-green-400 mr-1"></i>
                  <span class="font-medium text-green-700 dark:text-green-300">{{ $despues }}</span>
                </div>
                @endif
              </div> --}}
            </div>

            <!-- Valores numéricos -->
            <div class="grid grid-cols-2 gap-2 text-sm">
              @if($antes !== null)
              <div class="flex items-center p-2 bg-amber-50 rounded-lg dark:bg-amber-900/30">
                <i class="ti ti-arrow-down-left-circle text-amber-600 dark:text-amber-400 mr-2"></i>
                <div>
                  <div class="text-xs text-amber-700 dark:text-amber-300">Antes</div>
                  <div class="font-medium text-amber-900 dark:text-amber-100">{{ $antes }} rayas</div>
                </div>
              </div>
              @endif

              @if($despues !== null)
              <div class="flex items-center p-2 bg-green-50 rounded-lg dark:bg-green-900/30">
                <i class="ti ti-arrow-up-right-circle text-green-600 dark:text-green-400 mr-2"></i>
                <div>
                  <div class="text-xs text-green-700 dark:text-green-300">Después</div>
                  <div class="font-medium text-green-900 dark:text-green-100">{{ $despues }} rayas</div>
                </div>
              </div>
              @endif
            </div>
          </div>
          @endif
        </div>
        @else
        <div class="flex flex-col items-center justify-center h-[120px] text-gray-500 dark:text-gray-400">
          <i class="text-3xl ti ti-info-circle"></i>
          <span class="mt-2 text-sm">Sin detalles adicionales</span>
        </div>
        @endif
      </div>
    </div>

    <!-- Sección mejorada de información relevante -->
    <div class="grid grid-cols-1 gap-6 mt-8">
      <!-- Card: Información de Unidad y Ubicación -->
      <div class="p-6 bg-white shadow-md rounded-xl dark:bg-gray-800">
        <div class="flex items-center gap-2 mb-4 font-bold text-blue-700 dark:text-blue-300">
          <i class="text-lg ti ti-car"></i>
          Información Adicional
        </div>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
          <!-- Información del Punto -->
          <div class="p-4 bg-gray-50 rounded-lg dark:bg-gray-700">
            <div class="flex items-center mb-2">
              <i class="ti ti-map-pin text-blue-500 mr-2"></i>
              <span class="font-medium text-gray-700 dark:text-gray-300">Ubicación</span>
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400">
              @if($usuario && ($usuario->punto || ($usuario->solicitudAlta && $usuario->solicitudAlta->punto)))
              <div class="mb-1">
                <span class="font-medium">Punto:</span>
                <span class="ml-1">{{ $usuario->punto ?? $usuario->solicitudAlta->punto }}</span>
              </div>
              @else
              <span class="text-gray-400">Punto no disponible</span>
              @endif

              @if($usuario && $usuario->subpunto)
              <div>
                <span class="font-medium">Subpunto:</span>
                <span class="ml-1">{{ $usuario->subpunto->nombre ?? 'N/A' }}</span>
              </div>
              @endif
            </div>
          </div>

          <!-- Información de Rendimiento (para gasolina) -->
          @if($gasto->Tipo === 'Gasolina')
          <div class="p-4 bg-gray-50 rounded-lg dark:bg-gray-700">
            <div class="flex items-center mb-2">
              <i class="ti ti-chart-bar text-green-500 mr-2"></i>
              <span class="font-medium text-gray-700 dark:text-gray-300">Rendimiento</span>
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400">
              @if($gasto->Km !== null)
              <div class="mb-2">
                <span class="font-medium">Kilometraje Actual:</span>
                <span class="ml-1 font-mono">{{ number_format($gasto->Km, 0) }} Km</span>
              </div>

              @if($kilometrosRecorridos !== null)
              <div class="mb-2">
                <span class="font-medium">Km Recorridos:</span>
                <span class="ml-1 font-mono">{{ number_format($kilometrosRecorridos, 0) }} Km</span>
              </div>
              <div class="mb-2">
                <span class="font-medium">Costo por Km:</span>
                <span class="ml-1 font-mono">${{ number_format($costoPorKm, 2) }}</span>
              </div>
              <div class="text-xs text-gray-500 dark:text-gray-400">
                <i class="ti ti-info-circle mr-1"></i>
                Basado en el registro anterior del {{ $gastoPrevio->Fecha->format('d/m/Y') }}
              </div>
              @elseif($gastoPrevio)
              <div class="text-xs text-amber-600 dark:text-amber-400">
                <i class="ti ti-alert-triangle mr-1"></i>
                No se puede calcular rendimiento (Km no incremental)
              </div>
              @else
              <div class="text-xs text-gray-500 dark:text-gray-400">
                <i class="ti ti-info-circle mr-1"></i>
                Primer registro de kilometraje
              </div>
              @endif
              @else
              <span class="text-gray-400">Kilometraje no registrado</span>
              @endif
            </div>
          </div>
          @endif

          <!-- Información de Registro -->
          <div class="p-4 bg-gray-50 rounded-lg dark:bg-gray-700">
            <div class="flex items-center mb-2">
              <i class="ti ti-clock-hour-4 text-amber-500 mr-2"></i>
              <span class="font-medium text-gray-700 dark:text-gray-300">Registro</span>
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400">
              <div class="mb-1">
                <span class="font-medium">Fecha:</span>
                <span class="ml-1">{{ $gasto->Fecha->format('d/m/Y') }}</span>
              </div>
              <div class="mb-1">
                <span class="font-medium">Hora:</span>
                <span class="ml-1">{{ $gasto->Hora }}</span>
              </div>
              <div>
                <span class="font-medium">ID:</span>
                <span class="ml-1 font-mono text-xs">{{ $gasto->id }}</span>
              </div>
            </div>
          </div>

          <!-- Información del Usuario -->
          <div class="p-4 bg-gray-50 rounded-lg dark:bg-gray-700">
            <div class="flex items-center mb-2">
              <i class="ti ti-user-circle text-purple-500 mr-2"></i>
              <span class="font-medium text-gray-700 dark:text-gray-300">Usuario</span>
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400">
              @if($usuario)
              <div class="mb-1">
                <span class="font-medium">Nombre:</span>
                <span class="ml-1">{{ $usuario->name }}</span>
              </div>
              @if($usuario->rol)
              <div class="mb-1">
                <span class="font-medium">Rol:</span>
                <span class="ml-1">{{ \Illuminate\Support\Str::title(strtolower($usuario->rol)) }}</span>
              </div>
              @endif
              @if($gasto->user_name && $gasto->user_name !== $usuario->name)
              <div class="text-xs text-gray-500 dark:text-gray-400">
                <i class="ti ti-info-circle mr-1"></i>
                Registrado como: {{ $gasto->user_name }}
              </div>
              @endif
              @else
              <span class="text-gray-400">Usuario no disponible</span>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="flex justify-end max-w-4xl gap-4 mx-auto mt-8">
      <a href="{{ route('gastos.index') }}"
        class="flex items-center gap-2 px-5 py-2 text-gray-700 bg-gray-100 border border-gray-300 rounded-lg shadow hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 dark:hover:bg-gray-600"
        title="Regresar">
        <i class="ti ti-arrow-left"></i> Regresar
      </a>
    </div>
</div>
</x-livewire.monitoreo-layout>
</div>