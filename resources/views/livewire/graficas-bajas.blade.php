<div class="p-4 bg-white rounded-lg shadow">
    <div class="flex flex-col items-center mb-4">
        <h2 class="text-xl font-semibold mb-2">Bajas por Mes</h2>
        <select wire:model.live="anio" class="rounded-md border-gray-300 shadow-sm">
            @foreach($aniosDisponibles as $anio)
                <option value="{{ $anio }}">{{ $anio }}</option>
            @endforeach
        </select>
    </div>

    <div class="relative h-96 w-3/4 mx-auto">
        <canvas id="bajasChart"></canvas>
    </div>

    <script>
    document.addEventListener('livewire:init', function () {
        let chartInstance = null;

        function renderChart(data) {
            const ctx = document.getElementById('bajasChart');
            if (!ctx) return;

            if (chartInstance) {
                chartInstance.destroy();
            }

            chartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: 'Bajas Aceptadas',
                        data: data,
                        borderColor: '#f44336',
                        backgroundColor: 'rgba(244, 67, 54, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
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

        Livewire.on('chart-updated', ({data}) => {
            renderChart(data);
        });
    });
    </script>
</div>
