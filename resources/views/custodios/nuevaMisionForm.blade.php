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
                        <input type="datetime-local" name="fecha_inicio" id="fecha_inicio"
                            class="w-full p-2 border rounded-lg" onchange="actualizarAgentes()" required>

                            <label for="fecha_fin" class="block font-semibold mb-1">Fecha de Fin</label>
                        <input type="datetime-local" name="fecha_fin" id="fecha_fin"
                            class="w-full p-2 border rounded-lg" onchange="actualizarAgentes()" required>

                            <label for="agentes_id" class="block font-semibold mb-1">Agentes Asignados</label>
                        <select id="agentes" name="agentes_id[]" class="border p-2 w-full" multiple>
                            <option disabled>Selecciona fechas para ver agentes disponibles</option>
                        </select>
                        <small class="text-gray-500">Usa Ctrl + Clic para seleccionar varios</small>
                    </div>

                    <div class="mb-4">
                        <label for="tipo_servicio" class="block font-semibold mb-1">Tipo de Servicio</label>
                        <input type="text" name="tipo_servicio" id="tipo_servicio"
                            class="w-full p-2 border rounded-lg" required>
                    </div>

                    <div class="mb-4">
                        <label for="ubicacion" class="block font-semibold mb-1">Ubicación</label>
                        <input type="text" name="ubicacion" id="ubicacion" class="w-full p-2 border rounded-lg"
                            required>
                    </div>

                    <div class="mb-4">
                        <label for="cliente" class="block font-semibold mb-1">Cliente (opcional)</label>
                        <input type="text" name="cliente" id="cliente" class="w-full p-2 border rounded-lg">
                    </div>

                    <div class="mb-4">
                        <label for="pasajeros" class="block font-semibold mb-1">Pasajeros (opcional)</label>
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

                    <div class="mt-6 flex justify-center">
                        <button type="submit" class="bg-blue-600 inline-block text-white py-2 px-4 rounded-md hover:bg-gray-400 mr-2 mb-2">
                            Registrar Misión
                        </button>
                        <a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 mr-2 mb-2">
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

</x-app-layout>
