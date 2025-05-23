<x-app-layout>
    <x-navbar></x-navbar>
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="container mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h1 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Listado de Usuarios</h1>
                    @livewire('auxadminusuarios')
            </div>
        </div>
    </div>
</x-app-layout>
