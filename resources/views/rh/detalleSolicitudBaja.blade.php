<x-app-layout>
    <x-navbar></x-navbar>
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="container mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <h2 class="text-xl font-bold text-gray-700 dark:text-gray-200 mb-4 border-b pb-2">Datos Generales</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                <div>
                    <p class="text-gray-500">Fecha</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                        {{ \Carbon\Carbon::now()->format('Y-m-d') }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-500">NSS</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                        {{ $solicitudAlta->nss }}
                    </p>
                </div>
                <div rowspan="3" class="flex justify-center items-center">
                    <img src="{{ asset($documentacion->arch_foto) }}" alt="Foto del solicitante" class="rounded-xl w-32 h-32 object-cover border-2 border-gray-300 shadow">
                </div>
                <div>
                    <p class="text-gray-500">Fecha de Ingreso</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                        {{ $user->fecha_ingreso }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-500">¿Incapacidad?</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                        {{ $solicitud->incapacidad ?? 'No especificado' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <h2 class="text-xl font-bold text-gray-700 dark:text-gray-200 mb-4 border-b pb-2">Datos de Baja</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                <div>
                    <p class="text-gray-500">Nombre</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $user->name }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Empresa</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $user->empresa }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Punto</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $user->punto }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Por</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                        {{ $solicitud->por ?? 'No especificado' }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-500">Última Asistencia</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                        {{ $solicitud->ultima_asistencia ?? 'No especificado' }}
                    </p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-gray-500">Motivo</p>
                    <p class="font-medium text-gray-900 dark:text-white whitespace-pre-line">
                        {{ $solicitud->motivo ?? 'Sin detalles adicionales' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="text-center mt-6">
            <a href="#" class="inline-block bg-green-300 text-gray-800 py-2 px-6 rounded-md hover:bg-green-400 transition-colors">
                Aceptar
            </a>
            <a href="{{route('rh.rechazarBaja', $solicitud->id)}}" class="inline-block bg-red-300 text-gray-800 py-2 px-6 rounded-md hover:bg-red-400 transition-colors">
                Rechazar
            </a>
            <!--<a href="#" class="inline-block bg-gray-300 text-gray-800 py-2 px-6 rounded-md hover:bg-gray-400 transition-colors">
                Observaciones
            </a>  Opcional este enlace     -->
            <a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-6 rounded-md hover:bg-gray-400 transition-colors">
                Regresar
            </a>
        </div>

    </div>
    </div>

</x-app-layout>
