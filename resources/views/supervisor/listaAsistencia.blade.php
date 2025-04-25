<x-app-layout>
    <x-navbar />

    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="container mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                @if(Auth::user()->rol == 'Supervisor')
                    @if(!$asistencia_hoy->isEmpty())
                    <p>El día de hoy ya se registró la asistencia de todos los usuarios. Favor de volver mañana.</p>
                    <center><br>
                        <a href="{{ route('sup.verAsistencias') }}" class="inline-block bg-blue-300 text-gray-800 py-2 px-4 rounded-md hover:bg-blue-400 mr-2 mb-2">
                            Ver Registros de Asistencia
                        </a>
                        <a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 mr-2 mb-2">
                            Regresar
                        </a>
                    </center>
                    @else
                    <h1 class="text-2xl mb-6 text-gray-800 dark:text-white">Usuarios Activos</h1>

                    <form action="{{route('sup.guardarAsistencias')}}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($elementos as $elemento)
                                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-5 border border-gray-200 dark:border-gray-700 flex flex-col justify-between">
                                    <div class="flex items-center space-x-4 mb-4">
                                        <img src="{{ $elemento->solicitudAlta?->documentacion?->arch_foto ?: url('images/default-user.jpg') }}" alt="Foto de {{ $elemento->name }}" class="w-16 h-16 rounded-full object-cover border border-gray-300 dark:border-gray-600">
                                        <div>
                                            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">{{ $elemento->name }}</h2>
                                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $elemento->empresa }} - {{ $elemento->punto }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $elemento->rol }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between mt-4">
                                        <label for="asistencia_{{ $elemento->id }}" class="text-sm text-gray-700 dark:text-gray-300">
                                            Asistió
                                        </label>
                                        <input type="checkbox" name="asistencias[]" value="{{ $elemento->id }}" id="asistencia_{{ $elemento->id }}" class="h-5 w-5 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-6">
                            <label for="observaciones" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Observaciones</label>
                            <textarea name="observaciones" id="observaciones" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Escribe tus observaciones aquí..."></textarea>
                        </div>

                        <div class="mt-8 text-center">
                            <button type="submit"
                                    class="inline-block bg-green-500 text-white px-6 py-2 rounded-md hover:bg-green-600 transition duration-200">
                                Guardar Asistencias
                            </button>
                            <a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 mr-2 mb-2">
                                Regresar
                            </a>
                        </div>
                    </form>
                    @endif
                @else
                <h2 class="text-2xl mb-6 text-gray-800 dark:text-white">Control de Supervisores para Asistencias Enviadas Hoy</h2>
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                No.
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Supervisor
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Punto
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Asistencia Enviada
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($supervisores as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-200">
                                    {{ $user->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ $user->punto }}
                            </td>
                            <td class="px-4 py-2 font-semibold {{ $user->envio_asistencia === 'Sí' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $user->envio_asistencia }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{route('admin.editarUsuarioForm', $user->id)}}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-500 mr-3">Editar</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <center><br>
                    <a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 mr-2 mb-2">
                        Regresar
                    </a>
                </center>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
