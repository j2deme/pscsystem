<div class="p-4 bg-white rounded-lg shadow">
    <div class="flex flex-col items-center mb-4">
        <h2 class="text-xl font-semibold mb-2">Inasistencias</h2>
        <select wire:model.live.debounce.500ms="filtro" class="rounded-md border-gray-300 shadow-sm">
            <option value="hoy">Hoy</option>
            <option value="semana">Esta Semana</option>
            <option value="mes">Este Mes</option>
            <option value="anio">Este Año</option>
        </select>
    </div>

    <div class="relative h-96 w-3/4 mx-auto">
        <canvas id="inasistenciasChart"></canvas>
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 transition">
            Regresar
        </a>
    </div>

    <script>
    document.addEventListener('livewire:init', function () {
        let chartInstance = null;

        function renderChart(data) {
            const ctx = document.getElementById('inasistenciasChart');
            if (!ctx) return;

            if (chartInstance) {
                chartInstance.destroy();
            }

            chartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: 'Inasistencias',
                        data: data,
                        backgroundColor: '#f44336',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                precision: 0
                            }
                        }
                    }
                }
            });

            ctx.chart = chartInstance;
        }

        renderChart(@json($data));

        Livewire.on('chart-inasistencias-updated', ({data}) => {
            renderChart(data);
        });
    });
    </script>
</div>
