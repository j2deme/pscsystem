<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-purple-500">
    <div class="h-full flex flex-col min-w-0">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Suma de Finiquitos</h3>
        <div class="text-2xl font-bold text-gray-800 dark:text-gray-200">
            ${{ number_format($finiquitosMesActual, 2) }}
        </div>
        <div class="text-sm mt-1 flex items-center gap-1 {{ $variacionFiniquitos >= 0 ? 'text-red-600' : 'text-green-500' }}">
            @if($variacionFiniquitos > 0)
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 fill-current" viewBox="0 0 20 20">
                    <path d="M5 10l5-5 5 5H5z" />
                </svg>
            @elseif($variacionFiniquitos < 0)
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 fill-current" viewBox="0 0 20 20">
                    <path d="M5 10l5 5 5-5H5z" />
                </svg>
            @else
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 fill-current" viewBox="0 0 20 20">
                    <path d="M4 9h12v2H4z" />
                </svg>
            @endif
            <span class="whitespace-nowrap">
                {{ $variacionFiniquitos >= 0 ? '+' : '' }}{{ $variacionFiniquitos }}% vs mes pasado
            </span>
        </div>
    </div>
</div>
