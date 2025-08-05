<x-app-layout>
    <x-navbar />


    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="container mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <h1 class="text-3xl font-semibold text-gray-800 dark:text-white text-center md:text-left mb-6 transition-colors duration-300">
            Gráficas
        </h1>

        <form method="GET" action="{{ route('auxadmin.index') }}"
            class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg mb-8 transition-all duration-300 transform hover:shadow-xl">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Filtrar Datos</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <div>
                    <label for="punto" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Punto
                    </label>
                    <select name="punto" id="punto"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="">Todos los puntos</option>
                        @foreach (['Monterrey', 'Guanajuato', 'Nuevo Laredo', 'México', 'SLP', 'Xalapa', 'Michoacán', 'Puebla', 'Toluca', 'Querétaro', 'Saltillo', 'Drones', 'Kansas'] as $punto)
                            <option value="{{ $punto }}" {{ request('punto') == $punto ? 'selected' : '' }}>
                                {{ $punto }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Fecha Inicio
                    </label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio"
                        value="{{ request('fecha_inicio') }}"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                </div>

                <div>
                    <label for="fecha_fin" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Fecha Fin
                    </label>
                    <input type="date" name="fecha_fin" id="fecha_fin"
                        value="{{ request('fecha_fin') }}"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                </div>
            </div>

            <div class="mt-6 text-center">
                <button type="submit"
                    class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-lg shadow hover:from-blue-700 hover:to-blue-800 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 transition-all duration-200 transform hover:scale-105 focus:scale-100 active:scale-95">
                    🔍 Filtrar
                </button>
            </div>
        </form>

        <div id="carousel"
            class="relative w-full max-w-5xl mx-auto bg-gray-50 dark:bg-gray-900/60 backdrop-blur-sm rounded-2xl shadow-xl overflow-hidden border border-gray-200 dark:border-gray-700 transition-colors duration-300">

            <div class="overflow-hidden rounded-t-2xl">
                <div id="carousel-inner" class="flex transition-transform duration-700 ease-in-out">
                    <div class="min-w-full p-4 sm:p-6 flex flex-col items-center">
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4">📈 Altas Mensuales</h3>
                        <div class="w-full max-w-4xl h-64 sm:h-72 md:h-80">
                            <canvas id="chartAltas"></canvas>
                        </div>
                    </div>

                    <div class="min-w-full p-4 sm:p-6 flex flex-col items-center">
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4">📉 Bajas Mensuales</h3>
                        <div class="w-full max-w-4xl h-64 sm:h-72 md:h-80">
                            <canvas id="chartBajas"></canvas>
                        </div>
                    </div>

                    <div class="min-w-full p-4 sm:p-6 flex flex-col items-center">
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4">🤒 Incapacidades</h3>
                        <div class="w-full max-w-4xl h-64 sm:h-72 md:h-80">
                            <canvas id="chartIncapacidades"></canvas>
                        </div>
                    </div>

                    <div class="min-w-full p-4 sm:p-6 flex flex-col items-center">
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4">⚠️ Riesgos de Trabajo</h3>
                        <div class="w-full max-w-4xl h-64 sm:h-72 md:h-80">
                            <canvas id="chartRiesgos"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between p-4 gap-4 bg-gray-100 dark:bg-gray-800/70 transition-colors duration-300">
                <button onclick="prevSlide()"
                    class="flex items-center gap-2 px-5 py-2.5 bg-gray-700 hover:bg-gray-600 text-white font-medium rounded-lg shadow transition transform hover:scale-105 active:scale-95 w-full sm:w-auto justify-center">
                    ← Anterior
                </button>

                <div class="flex space-x-2">
                    <button onclick="showSlide(0)"
                        class="w-3 h-3 rounded-full bg-gray-400 hover:bg-blue-400 transition duration-200 dark:bg-gray-500" aria-label="Ir a gráfica 1"></button>
                    <button onclick="showSlide(1)"
                        class="w-3 h-3 rounded-full bg-gray-400 hover:bg-blue-400 transition duration-200 dark:bg-gray-500" aria-label="Ir a gráfica 2"></button>
                    <button onclick="showSlide(2)"
                        class="w-3 h-3 rounded-full bg-gray-400 hover:bg-blue-400 transition duration-200 dark:bg-gray-500" aria-label="Ir a gráfica 3"></button>
                    <button onclick="showSlide(3)"
                        class="w-3 h-3 rounded-full bg-gray-400 hover:bg-blue-400 transition duration-200 dark:bg-gray-500" aria-label="Ir a gráfica 4"></button>
                </div>

                <button onclick="nextSlide()"
                    class="flex items-center gap-2 px-5 py-2.5 bg-gray-700 hover:bg-gray-600 text-white font-medium rounded-lg shadow transition transform hover:scale-105 active:scale-95 w-full sm:w-auto justify-center">
                    Siguiente →
                </button>
            </div>
        </div>
    </div>
        </div>
        </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
            if (e.matches) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        });

        const options = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#e5e7eb' : '#1f2937',
                        font: { size: 12 }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(30, 41, 59, 0.9)',
                    titleColor: '#fff',
                    bodyColor: '#d1d5db',
                    borderColor: '#4b5563',
                    borderWidth: 1
                }
            },
            scales: {
                x: {
                    ticks: {
                        color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#e5e7eb' : '#4b5563',
                        font: { size: 10 }
                    },
                    grid: {
                        color: window.matchMedia('(prefers-color-scheme: dark)').matches ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.05)'
                    },
                    border: {
                        color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#4b5563' : '#d1d5db'
                    }
                },
                y: {
                    ticks: {
                        color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#e5e7eb' : '#4b5563',
                        font: { size: 10 },
                        callback: function(value) {
                            return Number.isInteger(value) ? value : null;
                        }
                    },
                    grid: {
                        color: window.matchMedia('(prefers-color-scheme: dark)').matches ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.05)'
                    },
                    border: {
                        color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#4b5563' : '#d1d5db'
                    },
                    beginAtZero: true
                }
            }
        };

        const createChart = (id, label, data, color) => {
            new Chart(document.getElementById(id), {
                type: 'bar',
                data: {
                    labels: @json($meses),
                    datasets: [{
                        label,
                        data,
                        backgroundColor: color.bg,
                        borderColor: color.border,
                        borderWidth: 1,
                        borderRadius: 6,
                        borderSkipped: false
                    }]
                },
                options
            });
        };

        createChart('chartAltas', 'Altas', @json($altas), {
            bg: 'rgba(59, 130, 246, 0.7)',
            border: 'rgba(59, 130, 246, 1)'
        });

        createChart('chartBajas', 'Bajas', @json($bajas), {
            bg: 'rgba(239, 68, 68, 0.7)',
            border: 'rgba(239, 68, 68, 1)'
        });

        createChart('chartIncapacidades', 'Incapacidades', @json($incapacidades), {
            bg: 'rgba(245, 158, 11, 0.7)',
            border: 'rgba(245, 158, 11, 1)'
        });

        createChart('chartRiesgos', 'Riesgos de Trabajo', @json($riesgos), {
            bg: 'rgba(168, 85, 247, 0.7)',
            border: 'rgba(168, 85, 247, 1)'
        });

        let slide = 0;
        const totalSlides = 4;

        function showSlide(index) {
            slide = index;
            const carousel = document.getElementById('carousel-inner');
            carousel.style.transform = `translateX(-${slide * 100}%)`;

            document.querySelectorAll('#carousel button[onclick^="showSlide"]').forEach((btn, i) => {
                if (i === slide) {
                    btn.classList.add('bg-blue-500');
                    btn.classList.remove('bg-gray-400', 'dark:bg-gray-500');
                } else {
                    btn.classList.remove('bg-blue-500');
                    btn.classList.add('bg-gray-400', 'dark:bg-gray-500');
                }
            });
        }

        function nextSlide() {
            showSlide((slide + 1) % totalSlides);
        }

        function prevSlide() {
            showSlide((slide - 1 + totalSlides) % totalSlides);
        }

        showSlide(0);
    </script>
</x-app-layout>
