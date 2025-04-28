<x-app-layout>
    <x-navbar></x-navbar>
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="container mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                @if(session('success'))
                    <div class="bg-green-100 border-t-4 border-green-500 rounded-b text-green-900 px-4 py-3 shadow-md" role="alert">
                        <div class="flex">
                            <div>
                                <p class="text-sm">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                    @else
                @endif
                <h1 class="text-2xl text-gray-800 dark:text-white">Usuarios Activos</h1>
                <span class="text-sm text-gray-600 mb-6 dark:text-gray-300">Seleccione un usuario para ingresar una solicitud de tiempo extra o cobertura de turno a otro elemento.</span>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mt-6">
                    @foreach($elementos as $elemento)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-5 border border-gray-200 dark:border-gray-700 flex flex-col justify-between h-full min-h-[220px] transition hover:shadow-lg">
                            <div class="flex items-center space-x-4">
                                <img src="{{ $elemento->solicitudAlta?->documentacion?->arch_foto ?: url('images/default-user.jpg') }}" alt="Foto de {{ $elemento->name }}" class="w-16 h-16 rounded-full object-cover border border-gray-300 dark:border-gray-600 shrink-0">
                                <div class="flex flex-col justify-between h-full">
                                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white leading-snug">{{ $elemento->name }}</h2>
                                    <p class="text-sm text-gray-600 dark:text-gray-300 truncate">{{ $elemento->empresa }} - {{ $elemento->punto }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $elemento->rol }}</p>
                                </div>
                            </div>
                            <div class="flex justify-center">
                                <a href="{{ route('sup.tiemposExtrasForm', $elemento->id) }}" class="inline-block w-1/3 bg-blue-400 text-white py-1 px-4 rounded-md hover:bg-blue-500 ">Tiempo Extra</a>
                                <a href="{{ route('sup.coberturaTurnoForm', $elemento->id) }}" class="inline-block w-1/3 bg-green-400 text-white py-1 px-4 rounded-md hover:bg-green-500 mx-4">Cobertura de Turno</a>
                            </div>
                        </div>

                @endforeach
                </div>

                    <div class="mt-8 text-center">
                        <a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 mr-2 mb-2">
                            Regresar
                        </a>
                    </div>
            </div>
        </div>
    </div>
</x-app-layout>
