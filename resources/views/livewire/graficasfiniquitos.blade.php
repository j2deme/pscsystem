<div class="p-4 bg-white rounded-lg shadow">
    <div class="flex flex-col items-center mb-4">
        <h2 class="text-xl font-semibold mb-2">Cálculos de Finiquitos</h2>
        <select wire:model.live.debounce.500ms="filtro" class="rounded-md border-gray-300 shadow-sm">
            <option value="hoy">Hoy</option>
            <option value="semana">Esta Semana</option>
            <option value="mes">Este Mes</option>
            <option value="anio">Este Año</option>
        </select>
    </div>

    <div class="relative h-96 w-3/4 mx-auto">
        <canvas id="finiquitosChart"></canvas>
    </div>

   <script>
document.addEventListener('livewire:initialized', () => {
    const ctx = document.getElementById('finiquitosChart');
    if (!ctx) {
        console.error('Canvas no encontrado: finiquitosChart');
        return;
    }
    const ctx2d = ctx.getContext('2d');
    let finiquitosChart;

    const renderChart = (periodo1, periodo2) => {
        if (finiquitosChart) {
            finiquitosChart.destroy();
        }
        finiquitosChart = new Chart(ctx2d, {
            type: 'bar',
            data: {
                labels: @js($labels),
                datasets: [
                    {
                        label: 'Periodo 1 (26-10)',
                        data: periodo1,
                        backgroundColor: 'rgba(75, 192, 192, 0.7)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Periodo 2 (11-25)',
                        data: periodo2,
                        backgroundColor: 'rgba(255, 206, 86, 0.7)',
                        borderColor: 'rgba(255, 206, 86, 1)',
                        borderWidth: 1
                    }
                ],
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
    };

    // Inicializar con datos iniciales
    renderChart(@js($dataPeriodo1 ?? []), @js($dataPeriodo2 ?? []));

    Livewire.on('chart-finiquitos-updated', event => {
        renderChart(event.periodo1, event.periodo2);
    });
});
</script>

</div>
