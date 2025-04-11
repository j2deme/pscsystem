<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 sm:gap-4">
            <div class="flex items-center gap-2">
                <h2 class="font-semibold text-lg text-gray-800 dark:text-gray-200">
                    {{ Auth::user()->name }}
                </h2>
            </div>
            <div class="flex flex-wrap gap-2 sm:gap-4">
                @if(auth()->user()->name == 'admin')
                <x-admin-layout></x-admin-layout>
                @else
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="space-y-4">
                    <p class="text-gray-900 dark:text-gray-100">
                        ¡Bienvenido!
                    </p>

                    <div class="flex flex-wrap gap-2 sm:hidden">
                        <a href="admin.mostrarUsuarios" class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 rounded">
                            Gestion de Usuarios
                        </a>
                        <a href="#" class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 rounded">
                            Mi Perfil
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 rounded">
                                Cerrar Sesión
                            </button>
                        </form>
                    </div>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-1">
                        @if(auth()->user()->name == 'admin')
                        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                            <h3 class="font-medium text-gray-800 dark:text-gray-200 text-sm sm:text-base">Nóminas</h3>
                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 mt-1"></p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                            <h3 class="font-medium text-gray-800 dark:text-gray-200 text-sm sm:text-base">Recursos Humanos</h3>
                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 mt-1"></p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                            <h3 class="font-medium text-gray-800 dark:text-gray-200 text-sm sm:text-base">Monitoreo</h3>
                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 mt-1"></p>
                        </div>
                        @else
                        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                            <h3 class="font-medium text-gray-800 dark:text-gray-200 text-sm sm:text-base">Módulo de Recursos Humanos</h3>
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
