<div
    class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-t-4 border-purple-500 hover:shadow-xl transform hover:-translate-y-1 transition">
    <div class="flex items-center gap-4">
        <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-full">
            <!-- Icono de dinero con documento -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600 dark:text-purple-300" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <!-- Documento -->
                <rect x="3" y="3" width="14" height="18" rx="2" ry="2" stroke-width="2" stroke="currentColor" fill="none" />
                <line x1="3" y1="7" x2="17" y2="7" stroke-width="2" stroke="currentColor" />
                <line x1="7" y1="11" x2="13" y2="11" stroke-width="2" stroke="currentColor" />
                <!-- Billete/dinero -->
                <circle cx="20" cy="14" r="3" stroke-width="2" stroke="currentColor" fill="none" />
            </svg>
        </div>
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Suma de Finiquitos</h3>
            <p class="text-3xl font-bold text-gray-800 dark:text-gray-200">
                ${{ number_format($finiquitosMesActual, 2) }}
            </p>
            <div
                class="text-sm mt-1 flex items-center gap-1 {{ $variacionFiniquitos >= 0 ? 'text-red-600' : 'text-green-500' }}">
                @if ($variacionFiniquitos > 0)
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5 10l5-5 5 5H5z" />
                    </svg>
                @elseif ($variacionFiniquitos < 0)
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5 10l5 5 5-5H5z" />
                    </svg>
                @else
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 9h12v2H4z" />
                    </svg>
                @endif
                <span>{{ $variacionFiniquitos >= 0 ? '+' : '' }}{{ $variacionFiniquitos }}% vs mes pasado</span>
            </div>
        </div>
    </div>
</div>
