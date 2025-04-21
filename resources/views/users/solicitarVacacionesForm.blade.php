<x-app-layout>
    <x-navbar></x-navbar>
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="container mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="space-y-4">
                    <p class="text-gray-900 text-2xl dark:text-gray-100 text-2xl">
                        Ficha Técnica
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><strong>Nombre:</strong> {{ $solicitud->nombre }} {{ $solicitud->apellido_paterno }} {{ $solicitud->apellido_materno }}</div>
                            <div><strong>CURP:</strong> {{ $solicitud->curp }}</div>
                            <div><strong>NSS:</strong> {{ $solicitud->nss }}</div>
                            <div><strong>RFC:</strong> {{ $solicitud->rfc }}</div>
                            <div><strong>Email:</strong> {{ $solicitud->email }}</div>
                            <div><strong>Teléfono:</strong> {{ $solicitud->telefono }}</div>
                            <div><strong>Dirección:</strong> {{ $solicitud->domicilio_calle }} #{{ $solicitud->domicilio_numero }}, {{ $solicitud->domicilio_colonia }}, {{ $solicitud->domicilio_ciudad }}, {{ $solicitud->domicilio_estado }}</div>
                            <div><strong>Estado Civil:</strong> {{ $solicitud->estado_civil }}</div>
                            <div><strong>Puesto Solicitado:</strong> {{ $solicitud->rol }}</div>
                            <div><strong>Empresa:</strong> {{ $solicitud->empresa }}</div>
                            <div><strong>Punto:</strong> {{ $solicitud->punto }}</div>
                            <div><strong>Fecha de Nacimiento:</strong> {{ $solicitud->fecha_nacimiento }}</div>
                        </div>
                        <div class="flex flex-col items-center justify-start text-center space-y-2">
                            @if ($documentacion && $documentacion->arch_foto)
                                <p class="font-semibold">Foto del solicitante:</p>
                                <img src="{{ asset($documentacion->arch_foto) }}" alt="Foto del usuario" class="w-40 h-40 object-cover rounded-full shadow">
                                <a href="{{ asset($documentacion->arch_foto) }}" target="_blank" class="text-blue-500 underline text-sm">Ver completa</a>
                            @else
                                <p class="text-sm text-gray-500">No hay foto cargada.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mt-2">
                <div class="space-y-4">
                    <p class="text-gray-900 text-2xl dark:text-gray-100 text-2xl">
                        Datos y Solicitud de Vacaciones
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><strong>Antigüedad:</strong> {{$antiguedad}} años</div>
                            <div><strong>Días correspondientes por Ley:</strong> {{$dias}} días</div>
                            <div><strong>Días ya utilizados:</strong> {{$diasUtilizados}} días</div>
                            <div><strong>Días disponibles:</strong> {{$diasDisponibles}} días</div>

                            @if($diasDisponibles > 0)
                            <div class="col-span-full md:col-span-1">
                                <form action="{{route('user.solicitarVacaciones', $user->id)}}" method="POST" class="w-full bg-white dark:bg-gray-700 p-6 rounded-lg shadow space-y-4">
                                    @csrf

                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Solicitar Vacaciones</h3>

                                    <div>
                                        <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Fecha de Inicio</label>
                                        <input type="date" name="fecha_inicio" id="fecha_inicio" class="mt-1 w-full rounded border-gray-300 shadow-sm" required>
                                    </div>

                                    <div>
                                        <label for="fecha_fin" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Fecha de Fin</label>
                                        <input type="date" name="fecha_fin" id="fecha_fin" class="mt-1 w-full rounded border-gray-300 shadow-sm" required>
                                    </div>

                                    <div>
                                        <label for="dias_solicitados" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Días Solicitados</label>
                                        <input type="number" name="dias_solicitados" id="dias_solicitados" class="mt-1 w-full rounded border-gray-300 shadow-sm bg-gray-200 cursor-not-allowed" readonly>
                                    </div>
                                    <input type="hidden" name="dias_disponibles" value="{{ $diasDisponibles }}">
                                    <input type="hidden" name="dias_utilizados" value="{{ $diasUtilizados }}">
                                    <input type="hidden" name="dias_por_derecho" value="{{ $dias }}">

                                    <button type="submit" id="btn_solicitar"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded transition-colors duration-200 disabled:opacity-60 disabled:cursor-not-allowed disabled:bg-red-400">
                                        Solicitar Vacaciones
                                    </button>
                                </form>
                            </div>
                            @else
                                <p class="text-sm text-gray-500">No hay días disponibles para solicitar vacaciones.</p>
                            @endif
                            <p><strong>Nota:</strong> En caso de visualizar un botón rojo puede ser por algunas razones: <br>
                                                    - Ya no cuenta con días disponibles. <br>
                                                    - La fecha seleccionada excede los días disponibles. <br>
                                                    - La fecha inicial no es mayor que la fecha de hoy. <br>
                                                    - La fecha de fin es menor que la fecha inicial.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const inicio = document.getElementById('fecha_inicio');
        const fin = document.getElementById('fecha_fin');
        const diasInput = document.getElementById('dias_solicitados');
        const btnSubmit = document.getElementById('btn_solicitar');

        const diasDisponibles = {{ $diasDisponibles }};

        function calcularDias() {
            if (inicio.value && fin.value) {
                const fechaInicio = new Date(inicio.value);
                const fechaFin = new Date(fin.value);const hoy = new Date();

            hoy.setHours(0, 0, 0, 0);

            if (fechaInicio < hoy) {
                diasInput.value = '';
                btnSubmit.disabled = true;
                btnSubmit.classList.add('bg-red-400', 'cursor-not-allowed');
                btnSubmit.classList.remove('hover:bg-blue-700', 'bg-blue-600');
                return;
            }
                if (fechaFin >= fechaInicio) {
                    const diffTime = fechaFin.getTime() - fechaInicio.getTime();
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                    diasInput.value = diffDays;

                    if (diffDays > diasDisponibles) {
                        btnSubmit.disabled = true;
                        btnSubmit.classList.add('bg-red-400', 'cursor-not-allowed');
                        btnSubmit.classList.remove('hover:bg-blue-700', 'bg-blue-600');
                    } else {
                        btnSubmit.disabled = false;
                        btnSubmit.classList.remove('bg-red-400', 'cursor-not-allowed');
                        btnSubmit.classList.add('bg-blue-600', 'hover:bg-blue-700');
                    }
                } else {
                    diasInput.value = '';
                    btnSubmit.disabled = true;
                }
            }
        }

        inicio.addEventListener('change', calcularDias);
        fin.addEventListener('change', calcularDias);
    });
</script>
