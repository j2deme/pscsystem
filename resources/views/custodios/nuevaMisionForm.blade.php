<x-app-layout>
    <x-navbar></x-navbar>
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">

                <h1 class="text-xl font-semibold text-gray-800 dark:text-white">Nueva Misión</h1>
                <form action="{{ route('misiones.store') }}" method="POST">
                    @csrf
                    <div class="mb-4 mt-4">
                        <label for="fecha_inicio" class="block font-semibold mb-1">Fecha de Inicio</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio" class="w-full p-2 border rounded-lg"
                            onchange="actualizarAgentes()" required>

                        <label for="fecha_fin" class="block font-semibold mb-1">Fecha de Fin</label>
                        <input type="date" name="fecha_fin" id="fecha_fin" class="w-full p-2 border rounded-lg"
                            onchange="actualizarAgentes()" required>

                        <label for="agentes_id" class="block font-semibold mb-1">Agentes Asignados</label>
                        <select id="agentes" name="agentes_id[]" class="border p-2 w-full" multiple>
                            <option disabled>Selecciona fechas para ver agentes disponibles</option>
                        </select>
                        <small class="text-gray-500">Usa Ctrl + Clic para seleccionar varios</small>
                    </div>

                    <div class="mb-4">
                        <label for="nivel_amenaza" class="block font-semibold mb-1">Nivel de Amenaza</label>
                        <select name="nivel_amenaza" id="nivel_amenaza" class="w-full p-2 border rounded-lg" required>
                            <option value="" disabled selected>Selecciona un nivel</option>
                            <option value="bajo">Bajo</option>
                            <option value="medio">Medio</option>
                            <option value="alto">Alto</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="armados" class="block font-semibold mb-1">Tipo de Agentes</label>
                        <select name="armados" id="armados" class="w-full p-2 border rounded-lg" required>
                            <option value="" disabled selected>Selecciona tipo</option>
                            <option value="armado">Armados</option>
                            <option value="desarmado">Desarmados</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="tipo_servicio" class="block font-semibold mb-1">Tipo de Servicio</label>
                        <input type="text" name="tipo_servicio" id="tipo_servicio"
                            class="w-full p-2 border rounded-lg" required>
                    </div>

                    <div id="ubicaciones-container" class="mb-6 space-y-4">
    <div class="ubicacion-item border p-4 rounded bg-gray-50">
        <label class="block font-semibold mb-1">Dirección</label>
        <input type="text" name="ubicaciones[0][direccion]" class="w-full p-2 border rounded-lg mb-2"
            placeholder="Ej. Calle X, Ciudad, Estado">

        <small class="text-gray-500">O ingresa latitud y longitud directamente:</small>

        <div class="grid grid-cols-2 gap-4 mt-2">
            <div>
                <label class="block font-semibold mb-1">Latitud</label>
                <input type="text" name="ubicaciones[0][latitud]" class="w-full p-2 border rounded-lg">
            </div>
            <div>
                <label class="block font-semibold mb-1">Longitud</label>
                <input type="text" name="ubicaciones[0][longitud]" class="w-full p-2 border rounded-lg">
            </div>
        </div>
    </div>
</div>

<button type="button" onclick="agregarUbicacion()"
    class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">
    + Agregar otra ubicación
</button>

                    <div class="mb-4 mt-4">
                        <label for="nombre_clave" class="block font-semibold mb-1">Nombre Clave</label>
                        <input type="text" name="nombre_clave" id="nombre_clave" class="w-full p-2 border rounded-lg"
                            required>
                    </div>

                    <div class="mb-4">
                        <label for="cliente" class="block font-semibold mb-1">Cliente</label>
                        <input type="text" name="cliente" id="cliente" class="w-full p-2 border rounded-lg">
                    </div>

                    <div class="mb-4">
                        <label for="pasajeros" class="block font-semibold mb-1">Pasajeros</label>
                        <input type="text" name="pasajeros" id="pasajeros" class="w-full p-2 border rounded-lg">
                    </div>

                    <div class="mb-4">
                        <label for="tipo_operacion" class="block font-semibold mb-1">Tipo de Operación
                            (opcional)</label>
                        <input type="text" name="tipo_operacion" id="tipo_operacion"
                            class="w-full p-2 border rounded-lg">
                    </div>

                    <div class="mb-4">
                        <label for="num_vehiculos" class="block font-semibold mb-1">Número de Vehículos</label>
                        <input type="number" name="num_vehiculos" id="num_vehiculos"
                            class="w-full p-2 border rounded-lg" min="0">
                    </div>

                    <div class="mb-4">
                        <label for="tipo_vehiculos" class="block font-semibold mb-1">Tipo de Vehículos</label>
                        <select name="tipo_vehiculos[]" id="tipo_vehiculos" multiple
                            class="w-full p-2 border rounded-lg">
                            <option value="Sedán">Sedán</option>
                            <option value="SUV">SUV</option>
                            <option value="Pick-up">Pick-up</option>
                            <option value="Blindado">Blindado</option>
                            <option value="Otro">Otro</option>
                        </select>
                        <small class="text-gray-500">Selecciona uno o varios</small>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="border rounded-lg p-4 bg-gray-100">
                            <div class="flex justify-center">
                                <h3 class="font-semibold text-lg mb-2">Datos del Hotel</h3>
                            </div>
                            <label class="block font-semibold mb-1">Nombre</label>
                            <input type="text" name="hotel[nombre]" class="w-full p-2 border rounded-lg mb-2">
                            <label class="block font-semibold mb-1">Dirección</label>
                            <input type="text" name="hotel[direccion]" class="w-full p-2 border rounded-lg mb-2">
                            <label class="block font-semibold mb-1">Teléfono</label>
                            <input type="text" name="hotel[telefono]" class="w-full p-2 border rounded-lg">
                        </div>

                        <div class="border rounded-lg p-4 bg-gray-100">
                            <div class="flex justify-center">
                                <h3 class="font-semibold text-lg mb-2">Datos del Hospital</h3>
                            </div>
                            <label class="block font-semibold mb-1">Nombre</label>
                            <input type="text" name="hospital[nombre]" class="w-full p-2 border rounded-lg mb-2">
                            <label class="block font-semibold mb-1">Dirección</label>
                            <input type="text" name="hospital[direccion]"
                                class="w-full p-2 border rounded-lg mb-2">
                            <label class="block font-semibold mb-1">Teléfono</label>
                            <input type="text" name="hospital[telefono]" class="w-full p-2 border rounded-lg">
                        </div>

                        <div class="border rounded-lg p-4 bg-gray-100">
                            <div class="flex justify-center">
                                <h3 class="font-semibold text-lg mb-2">Datos de la Embajada</h3>
                            </div>
                            <label class="block font-semibold mb-1">Nombre</label>
                            <input type="text" name="embajada[nombre]" class="w-full p-2 border rounded-lg mb-2">
                            <label class="block font-semibold mb-1">Dirección</label>
                            <input type="text" name="embajada[direccion]"
                                class="w-full p-2 border rounded-lg mb-2">
                            <label class="block font-semibold mb-1">Teléfono</label>
                            <input type="text" name="embajada[telefono]" class="w-full p-2 border rounded-lg">
                        </div>
                        <div class="border rounded-lg p-4 bg-gray-100">
                            <div class="flex justify-center">
                                <h3 class="font-semibold text-lg mb-2">Datos de Aeropuerto</h3>
                            </div>
                            <label class="block font-semibold mb-1">Nombre</label>
                            <input type="text" name="aeropuerto[nombre]"
                                class="w-full p-2 border rounded-lg mb-2">
                            <label class="block font-semibold mb-1">Dirección</label>
                            <input type="text" name="aeropuerto[direccion]"
                                class="w-full p-2 border rounded-lg mb-2">
                            <label class="block font-semibold mb-1">Teléfono</label>
                            <input type="text" name="aeropuerto[telefono]" class="w-full p-2 border rounded-lg">
                        </div>

                        <div class="md:col-span-2 border rounded-lg p-4 bg-gray-100">
                            <div class="flex justify-center">
                                <h3 class="font-semibold text-lg mb-2">Datos del Vuelo</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-semibold mb-1">Fecha</label>
                                    <input type="date" name="vuelo[fecha]" class="w-full p-2 border rounded-lg">
                                </div>

                                <div>
                                    <label class="block font-semibold mb-1">Flight</label>
                                    <input type="text" name="vuelo[flight]" class="w-full p-2 border rounded-lg">
                                </div>

                                <div>
                                    <label class="block font-semibold mb-1">Hora</label>
                                    <input type="time" name="vuelo[hora]" class="w-full p-2 border rounded-lg">
                                </div>

                                <div>
                                    <label class="block font-semibold mb-1">Pax</label>
                                    <input type="text" name="vuelo[pax]" class="w-full p-2 border rounded-lg">
                                </div>

                                <div>
                                    <label class="block font-semibold mb-1">Evento</label>
                                    <input type="text" name="vuelo[evento]" class="w-full p-2 border rounded-lg">
                                </div>

                                <div>
                                    <label class="block font-semibold mb-1">Aeropuerto</label>
                                    <input type="text" name="vuelo[aeropuerto]"
                                        class="w-full p-2 border rounded-lg">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-center">
                        <button type="submit"
                            class="bg-blue-600 inline-block text-white py-2 px-4 rounded-md hover:bg-blue-800 mr-2 mb-2">
                            Registrar Misión
                        </button>
                        <a href="{{ route('dashboard') }}"
                            class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 mr-2 mb-2">
                            Regresar
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <script>
        function actualizarAgentes() {
            const inicio = document.getElementById('fecha_inicio').value;
            const fin = document.getElementById('fecha_fin').value;
            const select = document.getElementById('agentes');

            if (!inicio || !fin) return;

            fetch('/agentes-disponibles', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        fecha_inicio: inicio,
                        fecha_fin: fin
                    })
                })
                .then(res => res.json())
                .then(agentes => {
                    select.innerHTML = '';

                    agentes.forEach(agente => {
                        const option = document.createElement('option');
                        option.value = agente.id;
                        option.textContent = agente.name;

                        if (agente.ocupado) {
                            option.disabled = true;
                            option.textContent += ' (Ocupado)';
                        }

                        select.appendChild(option);
                    });
                });
        }
    </script>
    <script>
    let ubicacionIndex = 1;

    function agregarUbicacion() {
        const container = document.getElementById('ubicaciones-container');
        const nuevaUbicacion = document.createElement('div');
        nuevaUbicacion.className = 'ubicacion-item border p-4 rounded bg-gray-50';

        nuevaUbicacion.innerHTML = `
            <label class="block font-semibold mb-1">Dirección</label>
            <input type="text" name="ubicaciones[${ubicacionIndex}][direccion]" class="w-full p-2 border rounded-lg mb-2"
                placeholder="Ej. Calle X, Ciudad, Estado">

            <small class="text-gray-500">O ingresa latitud y longitud directamente:</small>

            <div class="grid grid-cols-2 gap-4 mt-2">
                <div>
                    <label class="block font-semibold mb-1">Latitud</label>
                    <input type="text" name="ubicaciones[${ubicacionIndex}][latitud]" class="w-full p-2 border rounded-lg">
                </div>
                <div>
                    <label class="block font-semibold mb-1">Longitud</label>
                    <input type="text" name="ubicaciones[${ubicacionIndex}][longitud]" class="w-full p-2 border rounded-lg">
                </div>
            </div>
        `;
        container.appendChild(nuevaUbicacion);
        ubicacionIndex++;
    }
</script>

</x-app-layout>
