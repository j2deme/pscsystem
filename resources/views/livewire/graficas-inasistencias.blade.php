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
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#3b82f6',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1f2937',
                            titleColor: '#fff',
                            bodyColor: '#d1d5db',
                            padding: 10
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: 'rgba(229, 231, 235, 0.3)'
                            },
                            ticks: {
                                color: '#6b7280'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                precision: 0,
                                color: '#6b7280'
                            },
                            grid: {
                                color: 'rgba(229, 231, 235, 0.3)'
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
