<div class="p-4 bg-white rounded-lg shadow">
    <div class="flex flex-col items-center mb-4">
        <h2 class="text-xl font-semibold mb-2">Total de Nómina por Mes</h2>
        <select wire:model.live.debounce.500ms="filtro" class="rounded-md border-gray-300 shadow-sm mb-4">
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

    function renderChart(labels, values) {
        const ctx = document.getElementById('nominasChart');
        if (!ctx) return;

        if (chartInstance) {
            chartInstance.destroy();
        }

        requestAnimationFrame(() => {
            chartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Nómina ($)',
                        data: values,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            ctx.chart = chartInstance;
        });
    }

    renderChart(@json($labels ?? []), @json($values ?? []));

    Livewire.on('chart-nominas-updated', ({ labels, values }) => {
        renderChart(labels, values);
    });
});

</script>

</div>
