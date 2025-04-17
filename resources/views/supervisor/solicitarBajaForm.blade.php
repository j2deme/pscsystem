<x-app-layout>
    <x-navbar></x-navbar>

    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="container mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <h2 class="text-2xl mb-4">Formulario de Solicitud de Baja</h2>
            <p>Este formulario es para solicitar la baja de un empleado del sistema. Favor de seleccionar al elemento que desea dar de baja.</p>
                <div class="overflow-x-auto bg-white rounded-lg shadow">
                    <div class="overflow-x-auto mt-6">
                        <table class="min-w-full divide-y divide-gray-200 rounded-lg shadow-md bg-white dark:bg-gray-800">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        Nombre
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        Empresa
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        Punto
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                                @foreach ($elementos as $elemento)
                                    @if($elemento->estatus == 'Inactivo')
                                    @else
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $elemento->name ?? 'Sin usuario' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $elemento->empresa ?? 'No disponible' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $elemento->punto ?? 'No disponible' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                            <a href="{{route('sup.validarSolicitudBaja', $elemento->id)}}" class="inline-block mb-2 bg-red-500 hover:bg-red-600 text-white font-semibold py-1.5 px-3 rounded-md shadow-md transition w-40">
                                                Solicitar Baja
                                            </a><br>
                                            <a href="#" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-semibold py-1.5 px-3 rounded-md shadow-md transition w-40">
                                                Ver Ficha Técnica
                                            </a>
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <center><div class="flex justify-center items-center text-right">
                        <a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 mr-2 mb-2 ml-2 mt-1">
                            Regresar
                        </a>
                    </div></center>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
