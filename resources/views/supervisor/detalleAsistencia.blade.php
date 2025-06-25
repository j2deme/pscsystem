<x-app-layout>
    <x-navbar></x-navbar>
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="container mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Registro de Asistencias</h2>
                            <div class="mb-6 p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                                    Asistencia del {{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }} a las {{ $asistencia->hora_asistencia }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Supervisor: {{ $asistencia->usuario->name }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Observaciones: {{ $asistencia->observaciones }}</p>

                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-4">
                                    <div>
                                        <h4 class="text-md font-semibold text-green-600 dark:text-green-400 mb-2">Asistieron:</h4>
                                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                            @foreach ($asistencia->usuarios_enlistados as $usuario)
                                                <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded-lg text-center shadow">
                                                    <img src="{{ $usuario->solicitudAlta?->documentacion?->arch_foto ? asset($usuario->solicitudAlta->documentacion->arch_foto) . '?v=' . now()->timestamp : asset('images/default-user.jpg') }}" class="w-20 h-20 rounded-full mx-auto object-cover mb-2 border border-gray-300 dark:border-gray-600">
                                                    <p class="text-gray-800 dark:text-white font-medium">{{ $usuario->name }}</p>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $usuario->punto }} - {{ $usuario->empresa }}</p>
                                                    @if (isset($asistencia->fotos_asistentes[$usuario->id]))
                                                        <div class="mt-2">
                                                            <span class="text-xs text-gray-500 dark:text-gray-400">Evidencia</span>
                                                            <img src="{{ $asistencia->fotos_asistentes[$usuario->id] }}" class="w-24 h-16 object-cover mx-auto rounded-lg cursor-pointer border border-gray-300 dark:border-gray-600" alt="Foto de evidencia" data-img="{{ $asistencia->fotos_asistentes[$usuario->id] }}" onclick="openImageModal(this)">
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div>
                                        @if ($asistencia->usuarios_descansos->isEmpty())
                                            <h4 class="text-md font-semibold text-blue-600 dark:text-blue-400">No se registraron descansos.</h4>
                                        @else
                                            <h4 class="text-md font-semibold text-blue-600 dark:text-blue-400 mb-2">Descansaron:</h4>
                                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                                @foreach ($asistencia->usuarios_descansos as $usuario)
                                                    <div class="bg-blue-50 dark:bg-gray-700 p-3 rounded-lg text-center shadow">
                                                        <img src="{{ $usuario->solicitudAlta?->documentacion?->arch_foto ? asset($usuario->solicitudAlta->documentacion->arch_foto) . '?v=' . now()->timestamp : asset('images/default-user.jpg') }}" class="w-20 h-20 rounded-full mx-auto object-cover mb-2 border border-gray-300 dark:border-gray-600">
                                                        <p class="text-gray-800 dark:text-white font-medium">{{ $usuario->name }}</p>
                                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $usuario->punto }} - {{ $usuario->empresa }}</p>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    <div>
                                        @if ($asistencia->usuarios_faltantes->isEmpty())
                                            <h4 class="text-md font-semibold text-green-600 dark:text-green-400">No se registraron inasistencias.</h4>
                                        @else
                                        <h4 class="text-md font-semibold text-red-600 dark:text-red-400 mb-2">Faltaron:</h4>
                                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                                @foreach ($asistencia->usuarios_faltantes as $usuario)
                                                    <div class="bg-red-50 dark:bg-gray-700 p-3 rounded-lg text-center shadow">
                                                        <img src="{{ $usuario->solicitudAlta?->documentacion?->arch_foto ? asset($usuario->solicitudAlta->documentacion->arch_foto) . '?v=' . now()->timestamp : asset('images/default-user.jpg') }}" class="w-20 h-20 rounded-full mx-auto object-cover mb-2 border border-gray-300 dark:border-gray-600">
                                                        <p class="text-gray-800 dark:text-white font-medium">{{ $usuario->name }}</p>
                                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $usuario->punto }} - {{ $usuario->empresa }}</p>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        @if ($asistencia->usuarios_coberturas->isEmpty())
                                            <h4 class="text-md font-semibold text-yellow-600 dark:text-yellow-400">No se registraron coberturas.</h4>
                                        @else
                                            <h4 class="text-md font-semibold text-yellow-600 dark:text-yellow-400 mb-2">Hicieron cobertura:</h4>
                                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                                @foreach ($asistencia->usuarios_coberturas as $usuario)
                                                    <div class="bg-yellow-50 dark:bg-gray-700 p-3 rounded-lg text-center shadow">
                                                        <img src="{{ $usuario->solicitudAlta?->documentacion?->arch_foto ? asset($usuario->solicitudAlta->documentacion->arch_foto) . '?v=' . now()->timestamp : asset('images/default-user.jpg') }}" class="w-20 h-20 rounded-full mx-auto object-cover mb-2 border border-gray-300 dark:border-gray-600">
                                                        <p class="text-gray-800 dark:text-white font-medium">{{ $usuario->name }}</p>
                                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $usuario->punto }} - {{ $usuario->empresa }}</p>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div id="imageModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center hidden z-50">
                                <span class="absolute top-4 right-4 text-white text-2xl cursor-pointer" onclick="closeImageModal()">×</span>
                                <img id="modalImage" src="" class="rounded-lg shadow-lg object-contain" style="max-width: 90vw; max-height: 80vh;">
                            </div>


                <center><br>
                    <a href="{{ route('sup.verAsistencias', Auth()->user()->id) }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 mr-2 mb-2">
                        Regresar
                    </a>
                </center>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    function openImageModal(imgElement) {
        const imageSrc = imgElement.getAttribute('data-img');
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');

        modalImage.src = imageSrc;
        modal.classList.remove('hidden');
    }

    function closeImageModal() {
        const modal = document.getElementById('imageModal');
        modal.classList.add('hidden');
    }
</script>
