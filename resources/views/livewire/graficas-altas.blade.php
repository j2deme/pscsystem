<div class="p-4 bg-white rounded-lg shadow ml-16" wire:ignore>
    <div class="flex flex-col items-center mb-4">
    <h2 class="text-xl font-semibold mb-2">Estadísticas Generales</h2>

    <select wire:model.live.debounce.300ms="filtro" class="rounded-md border-gray-300 shadow-sm">
        <option value="hoy">Hoy</option>
        <option value="semana">Esta Semana</option>
        <option value="mes">Este Mes</option>
        <option value="anio">Este Año</option>
    </select>
</div>

<center>
    <div class="relative h-64 w-3/4 flex justify-center">
        <canvas id="estadisticasChart"></canvas>
    </div>
</center>
    <script>
    document.addEventListener('livewire:init', function () {
        let chartInstance = null;

        function initChart() {
            const ctx = document.getElementById('estadisticasChart');
            if (!ctx) return;

            if (chartInstance) {
                chartInstance.destroy();
            }

            chartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: 'Totales',
                        data: @json($data),
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.7)',
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(153, 102, 255, 0.7)',
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(153, 102, 255, 1)',
                        ],
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                stepSize: 1
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return `${context.dataset.label}: ${context.raw}`;
                                }
                            }
                        }
                    }
                }
            });

            ctx.chart = chartInstance;
        }

        initChart();

        Livewire.hook('morph.updated', function () {
            initChart();
        });
    });
    </script>
</div>
