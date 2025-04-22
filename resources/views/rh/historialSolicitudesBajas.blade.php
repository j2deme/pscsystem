<x-app-layout>
    <x-navbar/>
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="container mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                @if(session('success'))
                    <div class="bg-green-100 border-t-4 border-green-500 rounded-b text-green-900 px-4 py-3 shadow-md" role="alert">
                        <div class="flex">
                            <div>
                                <p class="text-sm">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            <h2 class="text-2xl mb-4">Historial de Solicitudes de Bajas</h2>
            @livewire('rhfiltrobajas')
            </div>
        </div>
    </div>
</x-app-layout>
