<x-app-layout>
    <x-navbar />

    <div class="container mx-auto">
        <h1 class="text-2xl font-bold mb-4 text-white">Gráficas</h1>

        <form method="GET" action="{{ route('auxadmin.index') }}"
            class="mb-6 bg-white dark:bg-gray-800 p-4 rounded shadow">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Filtro por Punto --}}
                <div>
                    <label for="punto" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Punto</label>
                    <select name="punto" id="punto"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                        <option value="">Todos</option>
                        @foreach (['Monterrey', 'Guanajuato', 'Nuevo Laredo', 'México', 'SLP', 'Xalapa', 'Michoacán', 'Puebla', 'Toluca', 'Querétaro', 'Saltillo', 'Drones', 'Kansas'] as $punto)
                            <option value="{{ $punto }}" {{ request('punto') == $punto ? 'selected' : '' }}>
                                {{ $punto }}</option>
                        @endforeach
                    </select>

                </div>

                {{-- Fecha Inicio --}}
                <div>
                    <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Fecha
                        Inicio</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" value="{{ request('fecha_inicio') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white" />
                </div>

                {{-- Fecha Fin --}}
                <div>
                    <label for="fecha_fin" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Fecha
                        Fin</label>
                    <input type="date" name="fecha_fin" id="fecha_fin" value="{{ request('fecha_fin') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white" />
                </div>
            </div>

            <div class="mt-4">
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Filtrar</button>
            </div>
        </form>

        ---

        {{-- Carrusel --}}
        <div id="carousel" class="relative w-full overflow-hidden max-w-xl mx-auto">
            {{-- Slides --}}
            <div class="flex transition-transform duration-500 ease-in-out" id="carousel-inner">
                <div class="min-w-full p-4 flex justify-center">
                    <canvas id="chartAltas" width="1000" height="150"></canvas>
                </div>

                <div class="min-w-full p-4 flex justify-center">
                    <canvas id="chartBajas" width="1000" height="150"></canvas>
                </div>
                <div class="min-w-full p-4 flex justify-center">
                    <canvas id="chartIncapacidades" width="1000" height="150"></canvas>
                </div>
                <div class="min-w-full p-4 flex justify-center">
                    <canvas id="chartRiesgos" width="1000" height="150"></canvas>
                </div>
            </div>

            {{-- Controles --}}
            <div class="flex justify-between mt-4 text-white px-4">
                <button onclick="prevSlide()">Anterior</button>
                <button onclick="nextSlide()">Siguiente</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const options = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: 'white'
                    }
                }
            },
            scales: {
                x: {
                    ticks: { color: 'white' },
                    grid: { color: '#444' },
                    barThickness: 1,

                },
                y: {
                    ticks: {  callback: function(value) {
        if (Number.isInteger(value)) return value;
    }, color: 'white' },
                    precision: 0 ,
                    grid: { color: '#444' },
                    beginAtZero: true
                }
            }
        };

        new Chart(document.getElementById('chartAltas'), {
            type: 'bar',
            data: {
               labels: {!! json_encode($meses) !!},
        datasets: [{
            label: 'Altas',
            data: {!! json_encode($altas) !!},
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                     barThickness: 100
                }]
            },
            options: options
        });

        new Chart(document.getElementById('chartBajas'), {
            type: 'bar',
            data: {
               labels: {!! json_encode($meses) !!},
        datasets: [{
            label: 'Bajas',
            data: {!! json_encode($bajas) !!},
                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                     barThickness: 100
                }]
            },
            options: options
        });

        new Chart(document.getElementById('chartIncapacidades'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($meses) !!},
        datasets: [{
            label: 'Incapacidades',
            data: {!! json_encode($incapacidades) !!},
                    backgroundColor: 'rgba(255, 206, 86, 0.6)',
                     barThickness: 100
                }]
            },
            options: options
        });

        new Chart(document.getElementById('chartRiesgos'), {
            type: 'bar',
            data: {
               labels: {!! json_encode($meses) !!},
        datasets: [{
            label: 'Riesgos de Trabajo',
            data: {!! json_encode($riesgos) !!},
                    backgroundColor: 'rgba(153, 102, 255, 0.6)',
                     barThickness: 100
                }]
            },
            options: options
        });

        // Carrusel
        let slide = 0;

        function showSlide(index) {
            const carousel = document.getElementById('carousel-inner');
            slide = index;
            carousel.style.transform = `translateX(-${slide * 100}%)`;
        }

        function nextSlide() {
            slide = (slide + 1) % 4;
            showSlide(slide);
        }

        function prevSlide() {
            slide = (slide - 1 + 4) % 4;
            showSlide(slide);
        }

        showSlide(0);
    </script>
</x-app-layout>
