<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<x-app-layout>
    <x-navbar />
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="space-y-4">
                    <p class="text-gray-900 text-2xl dark:text-gray-100 text-2xl">
                        Tablero de Opciones
                    </p>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        @if(Auth::user()->rol == 'admin')
                            <x-admin-navbar></x-admin-navbar>
                        @elseif (Auth::user()->rol == 'Supervisor')
                            <x-supervisor-navbar></x-supervisor-navbar>
                        @elseif (Auth::user()->rol == 'Recursos Humanos')
                            <x-rh-navbar></x-rh-navbar>
                        @else
                        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                            <h3 class="font-medium text-gray-800 dark:text-gray-200 text-sm sm:text-base">Acciones a realizar</h3>
                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 mt-1"></p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<style>
button, [type="button"], [type="submit"] {
    touch-action: manipulation;
    min-height: 44px;
    min-width: 44px;
}

@media (prefers-color-scheme: dark) {
    .bg-gray-50 {
        background-color: #1a202c;
    }
}
</style>
