<div>
    <select wire:model.live.debounce.500ms="filtro" class="form-select mb-4">
        <option value="todos">Todos los meses</option>
        @foreach(['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'] as $mes)
            <option value="{{ strtolower($mes) }}">{{ $mes }}</option>
        @endforeach
    </select>

    <div class="relative" style="height: 400px;">
        <canvas id="nominaChart-{{ $this->getId() }}" wire:ignore></canvas>
    </div>

    <div class="mt-3 text-lg font-bold">Total: ${{ number_format(array_sum($periodo1) + array_sum($periodo2), 2) }}</div>
</div>

<script>
document.addEventListener('livewire:initialized', () => {
    const canvasId = 'nominaChart-{{ $this->getId() }}';
    const canvas = document.getElementById(canvasId);

    if (!canvas) {
        console.error('Canvas no encontrado:', canvasId);
        return;
    }

    const ctx = canvas.getContext('2d');
    let nominaChart;

    const initChart = (labels, periodo1, periodo2) => {
        if (nominaChart) {
            nominaChart.destroy();
        }

        nominaChart = new Chart(ctx, {
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
    };

    // Inicializar con datos iniciales
    initChart(@js($labels), @js($periodo1), @js($periodo2));

    // Escuchar evento Livewire para actualizar datos
    Livewire.on('chart-nominas-updated', data => {
        initChart(data.labels, data.periodo1, data.periodo2);
    });
});
</script>

