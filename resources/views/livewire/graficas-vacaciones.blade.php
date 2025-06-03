<div class="p-4 bg-white rounded-lg shadow">
    <div class="flex flex-col items-center mb-4">
        <h2 class="text-xl font-semibold mb-2">Vacaciones</h2>
        <select wire:model.live.debounce.500ms="filtro" class="rounded-md border-gray-300 shadow-sm">
            <option value="hoy">Hoy</option>
            <option value="semana">Esta Semana</option>
            <option value="mes">Este Mes</option>
            <option value="anio">Este Año</option>
        </select>
    </div>

    <div class="relative h-96 w-3/4 mx-auto">
        <canvas id="vacacionesChart"></canvas>
    </div>

    <script>
    document.addEventListener('livewire:init', function () {
        let chartInstance = null;

        function renderChart(data) {
            const ctx = document.getElementById('vacacionesChart');
            if (!ctx) return;

            if (chartInstance) {
                chartInstance.destroy();
            }

            chartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: 'Vacaciones Aceptadas',
                        data: data,
                        backgroundColor: '#2196f3',
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

        Livewire.on('chart-vacaciones-updated', ({data}) => {
            renderChart(data);
        });
    });
    </script>
</div>
