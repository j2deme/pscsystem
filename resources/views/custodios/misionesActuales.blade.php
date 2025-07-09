<x-app-layout>
    <x-navbar></x-navbar>
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h1 class="text-xl font-semibold text-gray-800 dark:text-white">Misiones en Curso</h1>
                @if ($misiones->isEmpty())
                    <p class="text-gray-600 dark:text-gray-300">No hay misiones en curso actualmente.</p>
                @else
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    No.
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Tipo de Servicio
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Ubicación
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Fecha Inicio
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Fecha Fin
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Estatus
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($misiones as $mision)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap dark:text-gray-300">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap dark:text-gray-300">
                                        {{ $mision->tipo_servicio }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-normal break-words dark:text-gray-300" style="max-width: 250px;">
                                            @foreach ($mision->ubicacion as $ubicacion)
                                                {{ $ubicacion['direccion'] }}<br>
                                            @endforeach
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ \Carbon\Carbon::parse($mision->fecha_inicio)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ \Carbon\Carbon::parse($mision->fecha_fin)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        <span
                                            class="px-2 py-1 text-xs font-semibold leading-tight text-green-800 bg-green-200 rounded-full">
                                            En Curso
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap dark:text-gray-300">
                                        @if ($mision->arch_mision)
                                            <a href="{{ asset('storage/' . $mision->arch_mision) }}"
                                                class="text-blue-600 hover:text-blue-900" target="_blank">
                                                Ver PDF
                                            </a>
                                        @else
                                            <span class="text-gray-500 italic">Sin PDF</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5"
                                        class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-300">
                                        No hay misiones en curso actualmente.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                @endif
                <div class="flex justify-center">
                    <a href="{{ route('dashboard') }}"
                        class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 mr-2 mb-2">
                        Regresar
                    </a>
                </div>
            </div>
        </div>
</x-app-layout>
