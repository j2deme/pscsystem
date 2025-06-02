<div>
    <select wire:model.live.debounce.500ms="filtro" class="form-select mb-4">
        <option value="todos">Todos los meses</option>
        @foreach(['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'] as $mes)
            <option value="{{ strtolower($mes) }}">{{ $mes }}</option>
        @endforeach
    </select>

    <center><div class="chart-container" style="position: relative; height:400px; width:75%">
        <canvas id="nominaChart" wire:ignore></canvas>
    </div></center>

    <div class="mt-3 text-lg font-bold">Total: ${{ number_format(array_sum($periodo1) + array_sum($periodo2), 2) }}</div>
</div>

@script
<script>
document.addEventListener('livewire:initialized', () => {
    const ctx = document.getElementById('nominaChart').getContext('2d');
    let chart = null;

    function renderChart(labels, periodo1, periodo2, filtro) {
        if (chart) {
            chart.destroy();
        }

        chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Periodo 1 (26-10)',
                        data: periodo1,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Periodo 2 (11-25)',
                        data: periodo2,
                        backgroundColor: 'rgba(255, 99, 132, 0.7)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
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

    renderChart(
        @js($labels),
        @js($periodo1),
        @js($periodo2),
        @js($filtro)
    );

    Livewire.on('chart-updated', ({labels, periodo1, periodo2, filtro}) => {
        renderChart(labels, periodo1, periodo2, filtro);
    });
});
</script>
@endscript
