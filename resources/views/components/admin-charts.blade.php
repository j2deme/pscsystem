<div class="flex w-full">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 p-4">
        <div class="relative p-6">
            <div class="relative overflow-hidden rounded-lg">
                <div id="carouselSlides" class="flex transition-transform duration-300 ease-in-out">
                    <div class="w-full flex-shrink-0 px-4">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 h-full">
                            @livewire('nominastotales')
                        </div>
                    </div>
                    <div class="w-full flex-shrink-0">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            @livewire('graficas-altas')
                        </div>
                    </div>

                    <div class="w-full flex-shrink-0">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            @livewire('graficasnuevasaltas')
                        </div>
                    </div>

                    <div class="w-full flex-shrink-0">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            @livewire('graficas-bajas')
                        </div>
                    </div>

                    <div class="w-full flex-shrink-0">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            @livewire('graficas-inasistencias')
                        </div>
                    </div>

                    <div class="w-full flex-shrink-0">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            @livewire('graficas-vacaciones')
                        </div>
                    </div>
                </div>
            </div>

            <button onclick="prevSlide()" class="absolute left-6 top-1/2 -translate-y-1/2 bg-white dark:bg-gray-700 text-gray-800 dark:text-white shadow hover:bg-blue-500 hover:text-white transition duration-300 rounded-full w-10 h-10 flex items-center justify-center z-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <button onclick="nextSlide()" class="absolute right-6 top-1/2 -translate-y-1/2 bg-white dark:bg-gray-700 text-gray-800 dark:text-white shadow hover:bg-blue-500 hover:text-white transition duration-300 rounded-full w-10 h-10 flex items-center justify-center z-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <div class="flex justify-center mt-4 space-x-2">
                @for ($i = 0; $i < 6; $i++)
                    <button onclick="goToSlide({{ $i }})" class="w-3 h-3 rounded-full bg-gray-400 hover:bg-blue-500 transition indicator-dot"></button>
                @endfor
            </div>

</div>
    @push('scripts')
<script>
    let currentSlide = 0;
    const totalSlides = 6;
    const carousel = document.getElementById('carouselSlides');
    const indicators = document.querySelectorAll('.indicator-dot');

    function updateCarousel() {
        carousel.style.transform = `translateX(-${currentSlide * 100}%)`;

        indicators.forEach((dot, index) => {
            dot.classList.toggle('bg-blue-500', index === currentSlide);
            dot.classList.toggle('bg-gray-400', index !== currentSlide);
        });

        if (window.nominaChart) {
            setTimeout(() => {
                window.nominaChart.update();
            }, 300);
        }
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % totalSlides;
        updateCarousel();
    }

    function prevSlide() {
        currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
        updateCarousel();
    }

    function goToSlide(index) {
        currentSlide = index;
        updateCarousel();
    }

    document.addEventListener('DOMContentLoaded', () => {
        updateCarousel();
        setInterval(nextSlide, 10000);
    });
</script>
@endpush
