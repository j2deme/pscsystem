@php
    use Illuminate\Support\Str;
@endphp
<div class="p-6 space-y-6">
<div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Quincena</label>
        <select wire:model.live="filtroQuincena" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <option value="todas">Todas</option>
            <option value="1">1ª Quincena (1-15)</option>
            <option value="2">2ª Quincena (16-fin)</option>
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Mes</label>
        <select wire:model.live="filtroMes" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <option value="todos">Todos</option>
            @foreach (range(1, 12) as $mes)
                <option value="{{ $mes }}">{{ \Carbon\Carbon::create()->month($mes)->translatedFormat('F') }}</option>
            @endforeach
        </select>
    </div>

    <!--<div>
        <label class="block text-sm font-medium text-gray-700">Año</label>
        <select wire:model.live="filtroAnio" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

        </select>
    </div>-->
</div>

        <table class="min-w-full divide-y divide-gray-300 mt-6">
    <thead class="bg-gray-100">
        <tr>
            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">No.</th>
            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Empresa</th>
            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Nombre</th>
            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Sueldo</th>
            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Fecha Ingreso</th>
            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Antigüedad</th>
            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Días</th>
            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Salario Diario</th>
            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">$ Vacaciones</th>
            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Prima Vacacional</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-200 bg-white">
        @forelse($usuarios as $usuario)
            @php


                $fechaIngreso = \Carbon\Carbon::parse($usuario->fecha_ingreso);
                $antiguedad = $fechaIngreso->diff(now());

                $diasVacaciones = match (true) {
                    $antiguedad->y < 2 => 12,
                    $antiguedad->y === 2 => 14,
                    $antiguedad->y === 3 => 16,
                    $antiguedad->y === 4 => 18,
                    $antiguedad->y === 5 => 20,
                    $antiguedad->y > 5 && $antiguedad->y <= 10 => 22,
                    $antiguedad->y > 10 && $antiguedad->y <= 15 => 24,
                    $antiguedad->y > 15 && $antiguedad->y <= 20 => 26,
                    $antiguedad->y > 20 && $antiguedad->y <= 25 => 28,
                    $antiguedad->y > 25 && $antiguedad->y <= 30 => 30,
                    default => 32,
                };

                $rawSueldo = $usuario->solicitudAlta->sueldo_mensual ?? '0';

                if (preg_match('/\((.*?)\)/', $rawSueldo, $matches)) {
                    $soloNumero = preg_replace('/[^0-9.]/', '', $matches[1]);
                } else {
                    $soloNumero = preg_replace('/[^0-9.]/', '', $rawSueldo);
                }

                $salario = floatval($soloNumero) / 2;
                $salarioDiario = $salario > 0 ? round($salario / 15, 2) : 0;
                $prima = round($salarioDiario * $diasVacaciones * 0.25, 2);
            @endphp

            <tr>
                <td class="px-4 py-2 text-sm text-gray-900">{{ $loop->iteration }}</td>
                <td class="px-4 py-2 text-sm text-gray-600">{{ $usuario->empresa ?? '—' }}</td>
                <td class="px-4 py-2 text-sm text-gray-900">{{ $usuario->name }}</td>
                <td class="px-4 py-2 text-sm text-gray-600">${{ number_format($salario, 2) }}</td>
                <td class="px-4 py-2 text-sm text-gray-600">{{ $usuario->fecha_ingreso ? $fechaIngreso->format('d/m/Y') : '—' }}</td>
                <td class="px-4 py-2 text-sm text-gray-600">
                    @if($antiguedad->y == 1 )
                        {{ $antiguedad->y }} Año
                    @else
                        {{ $antiguedad->y }} Años
                    @endif
                </td>
                <td class="px-4 py-2 text-sm text-gray-600">{{ $diasVacaciones }} días</td>
                <td class="px-4 py-2 text-sm text-gray-600">${{ number_format($salarioDiario, 2) }}</td>
                <td class="px-4 py-2 text-sm text-gray-600">${{ number_format($diasVacaciones * $salarioDiario, 2) }}</td>
                <td class="px-4 py-2 text-sm text-gray-600">${{ number_format($prima, 2) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="px-4 py-4 text-center text-sm text-gray-500 italic">Sin registros</td>
            </tr>
        @endforelse
    </tbody>
</table>
</div>
