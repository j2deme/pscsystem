<x-app-layout>
    <x-navbar></x-navbar>
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="container mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 relative overflow-hidden">

                <h2 class="text-xl font-bold text-gray-700 dark:text-gray-200 mb-4 border-b pb-2">Gráficos y Estadísticas</h2>

                <div class="relative w-full overflow-hidden">
                    <div id="carouselSlides" class="flex transition-transform duration-500 ease-in-out" style="width: 100%; transform: translateX(0%)">
                        <div class="w-full flex-shrink-0 px-2" wire:ignore>
                            @livewire('graficas-altas')
                        </div>
                        <div class="w-full flex-shrink-0 px-2" wire:ignore>
                            @livewire('graficasnuevasaltas')
                        </div>
                        <div class="w-full flex-shrink-0 px-2" wire:ignore>
                            @livewire('graficas-bajas')
                        </div>
                        <div class="w-full flex-shrink-0 px-2" wire:ignore>
                            @livewire('graficas-inasistencias')
                        </div>
                        <div class="w-full flex-shrink-0 px-2" wire:ignore>
                            @livewire('graficas-vacaciones')
                        </div>

                        <div class="w-full flex-shrink-0 px-2" wire:ignore>
                            <!--Finiquitos por mes-->
                        </div>
                    </div>

                    <div class="flex justify-center mt-4 space-x-2">
                        @for ($i = 0; $i < 6; $i++)
                            <button onclick="goToSlide({{ $i }})" class="w-3 h-3 rounded-full bg-gray-400 hover:bg-blue-500 transition"></button>
                        @endfor
                    </div>

                    <button onclick="prevSlide()" class="absolute top-1/2 left-2 transform -translate-y-1/2 bg-white text-gray-800 shadow hover:bg-blue-500 hover:text-white transition duration-300 rounded-full w-10 h-10 flex items-center justify-center z-10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <button onclick="nextSlide()" class="absolute top-1/2 right-2 transform -translate-y-1/2 bg-white text-gray-800 shadow hover:bg-blue-500 hover:text-white transition duration-300 rounded-full w-10 h-10 flex items-center justify-center z-10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

<script>
    let currentSlide = 0;
    const totalSlides = 5;

    function goToSlide(index) {
        currentSlide = index;
        const container = document.getElementById('carouselSlides');
        container.style.transform = `translateX(-${index * 100}%)`;
    }

    function nextSlide() {
        if (currentSlide < totalSlides - 1) {
            goToSlide(currentSlide + 1);
        }
    }

    function prevSlide() {
        if (currentSlide > 0) {
            goToSlide(currentSlide - 1);
        }
    }
</script>
