<div class="rounded-lg bg-gray-100 dark:bg-gray-900">
    <nav class="bg-white dark:bg-gray-800 shadow-md rounded-t-lg">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex flex-wrap items-center justify-between">
                <div class="flex flex-wrap items-center gap-4">

                    <a href="{{ route('dashboard') }}">
                        <button class="flex items-center gap-2 px-4 py-2 text-sm sm:text-base font-medium text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-md transition-all duration-200">
                            <svg class="w-5 h-5 text-blue-500 dark:text-blue-300" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 1.293a1 1 0 00-1.414 0l-7 7A1 1 0 003 9h1v7a2 2 0 002 2h2a1 1 0 001-1v-4h2v4a1 1 0 001 1h2a2 2 0 002-2V9h1a1 1 0 00.707-1.707l-7-7z" />
                            </svg>
                            Inicio
                        </button>
                    </a>

                    <a href="{{ route('profile.edit') }}">
                        <button class="flex items-center gap-2 px-4 py-2 text-sm sm:text-base font-medium text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 hover:text-purple-600 dark:hover:text-purple-400 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-md transition-all duration-200">
                            <svg class="w-5 h-5 text-purple-500 dark:text-purple-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 14.25V16a2 2 0 01-2 2H4a2 2 0 01-2-2v-1.75A6.25 6.25 0 018.25 8h3.5A6.25 6.25 0 0118 14.25zM10 7A3 3 0 1010 1a3 3 0 000 6z" clip-rule="evenodd" />
                            </svg>
                            Mi Perfil
                        </button>
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 px-4 py-2 mt-auto text-sm sm:text-base font-medium text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 hover:text-red-600 dark:hover:text-red-400 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-md transition-all duration-200">
                            <svg class="w-5 h-5 text-red-500 dark:text-red-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h6a1 1 0 110 2H5v10h5a1 1 0 110 2H4a1 1 0 01-1-1V4zm11.293 1.293a1 1 0 011.414 1.414L14.414 9H17a1 1 0 110 2h-2.586l1.293 1.293a1 1 0 01-1.414 1.414L11 10l3.293-3.293z" clip-rule="evenodd" />
                            </svg>
                            Cerrar Sesión
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </nav>


    <div class="p-4">
        {{ $slot }}
    </div>
</div>

