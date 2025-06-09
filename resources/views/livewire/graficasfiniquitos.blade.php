
    <div>
        <div class="flex justify-center">
            <h2 class="text-xl font-semibold mb-2">Cálculo de finiquitos por periodo</h2>
        </div>
        @if (!$readyToLoad)
            <div class="flex justify-center">
                <button wire:click="cargarGrafica" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Generar Gráfico
                </button>
            </div>
            <center><p class="mt-2 text-sm text-gray-600">Nota: Favor de esperar un momento después de hacer clic en el botón.</p></center>
        @endif

        @if ($readyToLoad)
            <div wire:loading.remove wire:target="actualizarDatos">
                <select wire:model.live="filtro" class="rounded-md border-gray-300 shadow-sm mb-4" wire:loading.attr="disabled">
                    <option value="hoy">Hoy</option>
                    <option value="anio">Este Año</option>
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

                <div class="relative h-96 w-full">
                    <canvas id="finiquitosChart" wire:ignore></canvas>
                </div>
            </div>
        @endif
    </div>


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('livewire:init', function() {
    let chartInstance = null;

    function initChart(data) {
        const ctx = document.getElementById('finiquitosChart');
        if (!ctx) {
            setTimeout(() => initChart(data), 100);
            return;
        }

        if (chartInstance) {
            chartInstance.destroy();
        }

        ctx.style.width = '100%';
            ctx.style.height = '100%';
            ctx.width = ctx.offsetWidth;
            ctx.height = ctx.offsetHeight;

        chartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.labels || ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                datasets: [
                    {
                        label: 'Periodo 1 (1-10)',
                        backgroundColor: '#4caf50',
                        borderColor: '#4caf50',
                        borderWidth: 1,
                        borderRadius: 4,
                        data: data.periodo1 || Array(12).fill(0)
                    },
                    {
                        label: 'Periodo 2 (11-25)',
                        backgroundColor: '#2196f3',
                        borderColor: '#2196f3',
                        borderWidth: 1,
                        borderRadius: 4,
                        data: data.periodo2 || Array(12).fill(0)
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '$' + context.raw.toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString('es-MX');
                            }
                        }
                    }
                }
            }
        });
    }

    Livewire.on('chart-finiquitos-updated', (data) => {
        console.log('Datos recibidos para gráfico:', data);

        const chartData = Array.isArray(data) ? data[0] : data;
        initChart(chartData);
    });

    initChart({
        labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        periodo1: Array(12).fill(0),
        periodo2: Array(12).fill(0)
    });
});
</script>
@endpush
