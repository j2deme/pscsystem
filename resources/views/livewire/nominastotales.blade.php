<div class="p-4 bg-white rounded-lg shadow">
    <div class="flex flex-col items-center mb-4">
        <h2 class="text-xl font-semibold mb-2">Total de Nómina por Mes</h2>
        <select
            wire:model.live.debounce.500ms="filtro"
            id="filtroMes"
            name="filtroMes"
            class="rounded-md border-gray-300 shadow-sm mb-4"
        >
            <option value="todos">Todos</option>
            <option value="enero">Enero</option>
            <option value="febrero">Febrero</option>
            <option value="marzo">Marzo</option>
            <option value="abril">Abril</option>
            <option value="mayo">Mayo</option>
            <option value="junio">Junio</option>
            <option value="julio">Julio</option>
            <option value="agosto">Agosto</option>
            <option value="septiembre">Septiembre</option>
            <option value="octubre">Octubre</option>
            <option value="noviembre">Noviembre</option>
            <option value="diciembre">Diciembre</option>
        </select>
        <div wire:loading class="text-sm text-gray-500 mt-1">
            Cargando...
        </div>
    </div>
    <div class="text-center text-lg font-bold mb-4">
        Total: ${{ number_format($values[0] ?? 0, 2) }}
    </div>

    <div class="relative h-96 w-3/4 mx-auto">
        <canvas id="nominasChart" wire:ignore></canvas>
    </div>

    <script>
    document.addEventListener('livewire:init', function () {
        let chartInstance = null;

        function initChart(labels, values) {
            const ctx = document.getElementById('nominasChart');
            if (!ctx) {
                console.error('Canvas element not found');
                return;
            }

            if (!Array.isArray(labels) || !Array.isArray(values)) {
                console.error('Invalid chart data', {labels, values});
                return;
            }

            if (chartInstance) {
                chartInstance.destroy();
                chartInstance = null;
            }

            chartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Nómina ($)',
                        data: values,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        fill: true,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        }

        initChart(@json($labels ?? []), @json($values ?? []));

        Livewire.on('chart-nominas-updated', (data) => {
            console.log('Datos recibidos:', data);

            if (!data || !data.labels || !data.values) {
                console.error('Datos incompletos recibidos', data);
                return;
            }

            if (chartInstance) {
                chartInstance.data.labels = data.labels;
                chartInstance.data.datasets[0].data = data.values;
                chartInstance.update();
            } else {
                initChart(data.labels, data.values);
            }
        });
    });
    </script>
</div>
