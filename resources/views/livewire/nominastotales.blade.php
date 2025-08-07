<div>
    <div class="flex justify-center">
        <h2 class="text-xl font-semibold mb-2">Gráfico de Nómina</h2>
    </div>
    <div class="flex justify-between items-center mb-4">
        <a href="{{ route('nominas.registros') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm">
            Ver Registros
        </a>
    </div>
    @if (!$readyToLoad)
        <div class="flex justify-center">
            <button wire:click="cargarGrafica" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Generar Gráfico
            </button><br>
        </div>
        <center><p class="mt-2 text-sm text-gray-600">Nota: Favor de esperar un momento después de hacer clic en el botón.</p></center>
    @endif

    @if ($readyToLoad)
        <div wire:loading.remove wire:target="actualizarGrafica">
            <select wire:model.live="filtro" class="form-select mb-4">
                <option value="todos">Todos los meses</option>
                @foreach(['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'] as $mes)
                    <option value="{{ $mes }}">{{ ucfirst($mes) }}</option>
                @endforeach
            </select>

            <div class="relative w-full min-h-[400px]">
                <canvas id="chartNominas" wire:ignore></canvas>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('livewire:init', () => {
        let chartNominas = null;
        let pendingData = null;

        function initializeChart() {
            const ctx = document.getElementById('chartNominas');
            if (!ctx) {
                console.log('Canvas no disponible aún, reintentando...');
                setTimeout(initializeChart, 100);
                return;
            }

            if (chartNominas) {
                chartNominas.destroy();
            }

            // Usar datos pendientes si existen
            const data = pendingData || {
                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                periodo1: Array(12).fill(0),
                periodo2: Array(12).fill(0),
                total: 0
            };

            chartNominas = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [
                        {
                            label: 'Periodo 1 (26-10)',
                            backgroundColor: 'rgba(54, 162, 235, 0.7)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1,
                            data: data.periodo1,
                        },
                        {
                            label: 'Periodo 2 (11-25)',
                            backgroundColor: 'rgba(255, 99, 132, 0.7)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1,
                            data: data.periodo2,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: `Nómina Total: $${data.total.toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`
                        }
                    },
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

            // Limpiar datos pendientes
            pendingData = null;
        }

        // Inicializar el gráfico cuando el componente esté listo
        Livewire.hook('morph.updated', (el) => {
            if (el.component && el.component.name === 'nominastotales' && el.component.initialized) {
                initializeChart();
            }
        });

        Livewire.on('chart-nominas-updated', (data) => {
            console.log('Datos recibidos:', data);

            // Manejar si los datos vienen como array
            const chartData = Array.isArray(data) ? data[0] : data;

            if (!document.getElementById('chartNominas')) {
                pendingData = chartData;
                initializeChart();
            } else if (chartNominas) {
                chartNominas.data.labels = chartData.labels;
                chartNominas.data.datasets[0].data = chartData.periodo1;
                chartNominas.data.datasets[1].data = chartData.periodo2;
                chartNominas.options.plugins.title.text = `Nómina Total: $${chartData.total.toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
                chartNominas.update();
            } else {
                pendingData = chartData;
                initializeChart();
            }
        });
    });
</script>
@endpush
