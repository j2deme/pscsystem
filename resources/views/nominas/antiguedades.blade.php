<x-app-layout>
    <x-navbar></x-navbar>
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h1 class="text-xl font-semibold text-gray-800 dark:text-white">Antigüedades</h1>
                @livewire('antiguedades')
                <center>
                    <a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 mt-2 rounded-md hover:bg-gray-400 mr-2 ml-4">
                        Regresar
                    </a>
                </center>
            </div>
        </div>
    </div>
</x-app-layout>
