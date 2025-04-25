<x-app-layout>
    <x-navbar></x-navbar>
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="container mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                @if(session('success'))
                    <div class="bg-green-500 text-white p-2 mb-4 rounded-lg">{{ session('success') }}</div>
                @endif
                <h2 class="text-2xl mb-4">Listado de usuarios</h2>
                @livewire('admigestionusuarios')
            </div>
        </div>
    </div>
</x-app-layout>

