<div>
    <div class="flex justify-center">
        <h2 class="text-xl font-semibold mb-2">Estadísticas Generales</h2>
    </div>
    @if (!$readyToLoad)
        <div class="flex justify-center">
                <button wire:click="initChart" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Generar Gráfico
                </button>
        </div>
        <center><p class="mt-2 text-sm text-gray-600">Nota: Favor de esperar un momento después de hacer clic en el botón.</p></center>
    @endif

    @if ($readyToLoad)
    <div wire:loading wire:target="actualizarDatos">Cargando...</div>
        <center><div wire:loading.remove wire:target="actualizarDatos" class="relative">
        <select wire:model.live.debounce.500ms="filtro" class="rounded-md border-gray-300 shadow-sm">
            <option value="hoy">Hoy</option>
            <option value="semana">Esta Semana</option>
            <option value="mes">Este Mes</option>
            <option value="anio">Este Año</option>
        </select>
        <canvas id="chartStats"></canvas></center>
    </div>
@endif
</div>

@push('scripts')
<script>
    let chartInstance = null;

    const initChart = () => {
        const ctx = document.getElementById('chartStats');

        if (!ctx) {
            console.warn("⛔ No se encontró el canvas con id 'chartStats'");
            return;
        }

        chartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Altas', 'Bajas', 'Inasistencias', 'Vacaciones'],
                datasets: [{
                    label: 'Estadísticas',
                    data: [0, 0, 0, 0],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(153, 102, 255, 0.6)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        console.log("✅ Gráfico inicializado");
    };

    Livewire.on('chart-altas-updated', (data) => {
    console.log("📊 Recibido evento 'chart-altas-updated' con data:", data);

    const cleanData = Array.isArray(data[0]) ? data[0] : data;
    console.log("📦 Datos limpios:", cleanData);

    if (!chartInstance) {
        console.log("🎯 Inicializando gráfico por primera vez...");
        setTimeout(() => {
            initChart();

            if (Array.isArray(cleanData)) {
                chartInstance.data.datasets[0].data = cleanData;
                chartInstance.update();
                console.log("✅ Gráfico creado y datos cargados.");
            } else {
                console.error("❌ El dato recibido no es un array:", cleanData);
            }
        }, 100);
        return;
    }

    if (chartInstance && chartInstance.data) {
        chartInstance.data.datasets[0].data = cleanData;
        chartInstance.update();
        console.log("🔁 Datos actualizados en el gráfico.");
    } else {
        console.error("❌ Error: chartInstance no existe o no tiene estructura esperada.");
    }
});



</script>
@endpush
