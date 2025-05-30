<div class="p-4 bg-white rounded-lg shadow" wire:ignore>
    <h2 class="text-xl font-semibold mb-4">Usuarios Activos: {{ $activeUsersCount }}</h2>

    <div class="relative h-64">
        <canvas id="activeUsersChart"></canvas>
    </div>

    <script>
    document.addEventListener('livewire:init', function() {
        // Almacenamos la instancia del gráfico en el contexto del canvas
        let chartInstance = null;

        function initChart() {
            const ctx = document.getElementById('activeUsersChart');
            if (!ctx) {
                console.error('No se encontró el elemento canvas');
                return;
            }

            // Destruir gráfico anterior si existe
            if (chartInstance) {
                chartInstance.destroy();
                chartInstance = null;
            }

            // Crear nuevo gráfico
            chartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Usuarios Activos'],
                    datasets: [{
                        label: 'Total',
                        data: [@json($activeUsersCount)],
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
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
                    }
                }
            });

            // Guardar referencia en el canvas
            ctx.chart = chartInstance;
        }

        // Inicializar al cargar
        initChart();

        // Actualizar cuando Livewire actualice el componente
        Livewire.hook('morph.updated', function() {
            initChart();
        });
    });
    </script>
</div>
