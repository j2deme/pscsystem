<x-app-layout>
    <x-navbar></x-navbar>

    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="container mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <h2 class="text-2xl mb-4">Formulario de Solicitud de Baja</h2>
                <div class="overflow-x-auto bg-white rounded-lg shadow">

                    <form action="#" method="POST" class="space-y-6">
                        @csrf

                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">Datos Generales</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="fecha_hoy" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha</label>
                                    <input type="date" name="fecha_hoy" id="fecha_hoy" value="{{$solicitudBaja->fecha_solicitud}}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500" readonly>
                                </div>

                                <div>
                                    <label for="nss" class="block text-sm font-medium text-gray-700 dark:text-gray-300">NSS</label>
                                    <p class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        {{$solicitudAlta->nss}}
                                    </p>
                                </div>

                                <div>
                                    <label for="fecha_ingreso" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha de Ingreso</label>
                                    <p class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        {{ optional(\Carbon\Carbon::parse($user->fecha_ingreso))->format('d/m/Y') }}
                                    </p>
                                </div>

                                <div>
                                    <label for="incapacidad" class="block text-sm font-medium text-gray-700 dark:text-gray-300">¿Incapacidad?</label>
                                    <input type="text" name="incapacidad" id="incapacidad" value="{{$solicitudBaja->incapacidad}}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">Datos de Baja</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
                                    <p  value="{{$user->name}}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        {{$user->name}}
                                    </p>
                                </div>

                                <div>
                                    <label for="empresa" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Empresa</label>
                                    <p class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        {{$user->empresa}}
                                    </p>
                                </div>

                                <div>
                                    <label for="punto" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Punto</label>
                                    <p  class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        {{$user->punto}}
                                    </p>
                                </div>

                                <div>
                                    <label for="por" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Por</label>
                                    <p  class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        {{$solicitudBaja->por}}
                                    </p>
                                </div>
                                <div>
                                    <label for="ultima_asistencia" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Última Asistencia</label>
                                    <input type="date" name="ultima_asistencia" id="ultima_asistencia" value="{{$solicitudBaja->ultima_asistencia}}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label for="motivo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Motivo (opcional)</label>
                                    <p  class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        {{$solicitudBaja->motivo}}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <center><div class="flex justify-center items-center text-right">
                            <a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 mr-2 mb-2 ml-2 mt-1">
                                Regresar
                            </a>
                        </div></center>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
