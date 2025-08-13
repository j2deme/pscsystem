<div
    class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-t-4 border-orange-500 hover:shadow-xl transform hover:-translate-y-1 transition">
    <div class="flex items-center gap-4">
        <div class="p-3 bg-orange-100 dark:bg-orange-900 rounded-full">
            <!-- Icono de billetes -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-orange-600 dark:text-orange-300" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <rect x="2" y="6" width="20" height="12" rx="2" ry="2" stroke-width="2" stroke="currentColor" fill="none" />
                <circle cx="12" cy="12" r="3" stroke-width="2" stroke="currentColor" fill="none" />
            </svg>
        </div>
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Suma de pagos de Destajos</h3>
            <p class="text-3xl font-bold text-gray-800 dark:text-gray-200">
                ${{ number_format($totalMesActual, 2) }}
            </p>
            <div
                class="text-sm mt-1 flex items-center gap-1 {{ $variacion >= 0 ? 'text-red-600' : 'text-green-500' }}">
                @if ($variacion > 0)
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5 10l5-5 5 5H5z" />
                    </svg>
                @elseif ($variacion < 0)
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5 10l5 5 5-5H5z" />
                    </svg>
                @else
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 9h12v2H4z" />
                    </svg>
                @endif
                <span>{{ $variacion >= 0 ? '+' : '' }}{{ $variacion }}% vs periodo pasado</span>
            </div>
        </div>
    </div>
</div>
