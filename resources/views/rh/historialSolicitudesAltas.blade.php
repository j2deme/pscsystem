<x-app-layout>
    <x-navbar></x-navbar>
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="container mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h2 class="text-2xl mb-4">Historial de Solicitudes de Alta</h2>
                @livewire('rhfiltroaltas')
            </div>
        </div>
    </div>
</x-app-layout>
