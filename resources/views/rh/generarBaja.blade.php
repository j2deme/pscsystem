<x-app-layout>
    <x-navbar></x-navbar>
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="container mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Baja de Usuario</h2>
                @livewire('filtrousuarios')
                <center><a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 ml-2 mr-2">
                    Regresar
                </a></center>
            </div>
        </div>
    </div>
</x-app-layout>
