<div>
    <div class="mb-6 justify-between">
        @if(Auth::user()->rol == 'admin')
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Buscar por nombre o fecha"
                class="w-1/3 p-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
            >
        @endif
        <input
            type="date"
            wire:model.live="fecha"
            class="w-1/3 p-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
        />
        <div wire:loading class="text-sm text-gray-500 mt-1">
            Buscando...
        </div>

    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        No.
                    </th>
                    @if(Auth::user()->rol == 'admin')
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Supervisor
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Punto
                        </th>
                    @endif
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Fecha
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Asistencias
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Descansos
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Faltas
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Coberturas
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($asistencias as $asistencia)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                        {{ $loop->iteration }}
                    </td>
                    @if(Auth::user()->rol == 'admin')
                        <td class="px-6 py-4 whitespace-nowrap text-sm dark:text-gray-300">
                                {{ $asistencia->usuario->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ $asistencia->usuario->punto }}
                        </td>
                    @endif
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                            {{ \Carbon\Carbon::parse($asistencia->fecha)->format('d-m-Y') }}
                    </td>
                    @php
                        $asistentes = json_decode($asistencia->elementos_enlistados, true);
                        $asistenciasDia = is_array($asistentes) ? count($asistentes) : 0;

                        $faltantes = json_decode($asistencia->faltas, true);
                        $faltas = is_array($faltantes) ? count($faltantes) : 0;

                        $descansaron = json_decode($asistencia->descansos, true);
                        $descansos = is_array($descansaron) ? count($descansaron) : 0;

                        $coberturas = json_decode($asistencia->coberturas, true);
                        $coberturasDia = is_array($coberturas) ? count($coberturas) : 0;

                    @endphp
                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                        {{ $asistenciasDia }}
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                        {{ $descansos }}
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                        {{ $faltas }}
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                        {{ $coberturasDia }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm ">
                        <a href="{{route('sup.detalleAsistencia', $asistencia->id)}}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-500 mr-3">Ver Detalles</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if($asistencias->hasPages())
            <div class="mt-4">
                <nav role="navigation">
                    <ul class="flex justify-center space-x-2">
                        @if($asistencias->onFirstPage())
                            <li class="px-3 py-1 text-gray-500" aria-disabled="true">
                                <span>&laquo;</span>
                            </li>
                        @else
                            <li>
                                <button wire:click="previousPage" class="px-3 py-1 text-blue-600 hover:text-blue-800" rel="prev">
                                    &laquo;
                                </button>
                            </li>
                        @endif

                        @foreach(range(1, $asistencias->lastPage()) as $page)
                            <li>
                                @if($page == $asistencias->currentPage())
                                    <span class="px-3 py-1 bg-blue-500 text-white rounded">{{ $page }}</span>
                                @else
                                    <button wire:click="gotoPage({{ $page }})" class="px-3 py-1 text-blue-600 hover:text-blue-800">
                                        {{ $page }}
                                    </button>
                                @endif
                            </li>
                        @endforeach

                        @if($asistencias->hasMorePages())
                            <li>
                                <button wire:click="nextPage" class="px-3 py-1 text-blue-600 hover:text-blue-800" rel="next">
                                    &raquo;
                                </button>
                            </li>
                        @else
                            <li class="px-3 py-1 text-gray-500" aria-disabled="true">
                                <span>&raquo;</span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
        @endif
    </div>
</div>
