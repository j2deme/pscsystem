<div class="rounded-lg bg-gray-200 dark:bg-gray-800">
    <div>
        <nav class="bg-white bg-gray-200 dark:bg-gray-900 shadow py-4">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-1 sm:space-x-3 md:space-x-6">
                        <a href="{{route('admin.verUsuarios')}}" class="px-3 py-2 text-sm sm:text-base font-medium text-gray-800 dark:text-gray-200 hover:text-gray-600 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md transition-colors duration-200">
                            Gestión de Usuarios
                        </a>

                        <a href="#" class="px-3 py-2 text-sm sm:text-base font-medium text-gray-800 dark:text-gray-200 hover:text-gray-600 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md transition-colors duration-200">
                            Mi Perfil
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="inline mt-3">
                            @csrf
                            <button type="submit" class="px-3 py-3 text-sm sm:text-base font-medium text-gray-800 dark:text-gray-200 hover:text-gray-600 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md transition-colors duration-200">
                                Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    </div>

    {{ $slot }}
</div>
