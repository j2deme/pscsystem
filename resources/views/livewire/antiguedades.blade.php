<div class="p-6 space-y-6">
<div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
    <!-- Filtro quincena -->
    <div>
        <label class="block text-sm font-medium text-gray-700">Quincena</label>
        <select wire:model.live="filtroQuincena" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <option value="todas">Todas</option>
            <option value="1">1ª Quincena (1-15)</option>
            <option value="2">2ª Quincena (16-fin)</option>
        </select>
    </div>

    <!-- Filtro mes -->
    <div>
        <label class="block text-sm font-medium text-gray-700">Mes</label>
        <select wire:model.live="filtroMes" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <option value="todos">Todos</option>
            @foreach (range(1, 12) as $mes)
                <option value="{{ $mes }}">{{ \Carbon\Carbon::create()->month($mes)->translatedFormat('F') }}</option>
            @endforeach
        </select>
    </div>

    <!-- Filtro año -->
    <div>
        <label class="block text-sm font-medium text-gray-700">Año</label>
        <select wire:model.live="filtroAnio" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <option value="todos">Todos</option>
            @foreach (range(now()->year, now()->year - 10) as $anio)
                <option value="{{ $anio }}">{{ $anio }}</option>
            @endforeach
        </select>
    </div>
</div>



    @foreach ($agrupados as $mes => $subgrupos)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4 border-b pb-2">
                {{ \Carbon\Carbon::createFromFormat('Y-m', $mes)->translatedFormat('F Y') }}
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-blue-600 mb-2">Ingresos de la 1° Quincena</h3>
                    <ul class="space-y-2">
                        @forelse ($subgrupos['del_1_al_15'] as $usuario)
                            <li class="bg-blue-100 dark:bg-blue-900/50 p-3 rounded-md shadow-sm">
                                <span class="font-medium text-gray-800 dark:text-gray-200">{{ $usuario->name }}</span>
                                <br>
                                <span class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($usuario->fecha_ingreso)->format('d/m/Y') }}</span>
                            </li>
                        @empty
                            <li class="text-gray-500 italic">Sin registros</li>
                        @endforelse
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-green-600 mb-2">Ingresos de la 2° Quincena</h3>
                    <ul class="space-y-2">
                        @forelse ($subgrupos['del_16_al_fin'] as $usuario)
                            <li class="bg-green-100 dark:bg-green-900/50 p-3 rounded-md shadow-sm">
                                <span class="font-medium text-gray-800 dark:text-gray-200">{{ $usuario->name }}</span>
                                <br>
                                <span class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($usuario->fecha_ingreso)->format('d/m/Y') }}</span>
                            </li>
                        @empty
                            <li class="text-gray-500 italic">Sin registros</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    @endforeach
</div>
