<div>
    <div class="mb-6">
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Buscar por nombre..."
            class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
        >
        <div wire:loading class="text-sm text-gray-500 mt-1">
            Buscando...
        </div>
    </div>
    @if($tiemposExtras->isEmpty())
                <p>No hay tiempos extras registrados.</p>
            @else
                <div class="overflow-x-auto bg-white rounded-lg shadow">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100 text-gray-700 text-left text-sm tracking-wider">
                            <tr>
                                <th class="px-4 py-2 text-center font-normal">No.</th>
                                <th class="px-4 py-2 text-center font-normal">Nombre</th>
                                <th class="px-4 py-2 text-center font-normal">Fecha</th>
                                <th class="px-4 py-2 text-center font-normal">Hora de Inicio</th>
                                <th class="px-4 py-2 text-center font-normal">Hora de Fin</th>
                                <th class="px-4 py-2 text-center font-normal">Tiempo Extra (H-m-s)</th>
                                <th class="px-4 py-2 text-center font-normal">Autorizado por</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 text-sm font-medium text-gray-700">
                            @foreach($tiemposExtras as $tiempoExtra)
                                <tr class="border-t dark:border-gray-700">
                                    <td class="px-4 py-2 text-center">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-2 text-center">
                                        {{ $tiempoExtra->user->name }}
                                    </td>
                                    <td class="px-4 py-2 text-center">{{ \Carbon\Carbon::parse($tiempoExtra->fecha)->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2 text-center">{{ $tiempoExtra->hora_inicio }}</td>
                                    <td class="px-4 py-2 text-center">{{ $tiempoExtra->hora_fin }}</td>
                                    <td class="px-4 py-2 text-center"><center>{{ $tiempoExtra->total_horas }}</center></td>
                                    <td class="px-4 py-2 text-center">{{ $tiempoExtra->autorizado_por }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <center><br><a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 mr-2 mb-2">
                        Regresar
                    </a></center>
                </div>
            @endif
</div>
