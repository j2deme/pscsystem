<x-app-layout>
    <x-navbar />

    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="mx-auto max-w-7xl h-[80vh]">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 h-full">
                <div class="grid grid-cols-12 gap-4 h-full">
                    <div class="col-span-4 bg-white dark:bg-gray-800 rounded-lg shadow overflow-y-auto p-4">
                        @livewire('mensajes-lista')
                    </div>
                    <div class="col-span-8 bg-white dark:bg-gray-900 rounded-lg shadow overflow-y-auto p-4">
                        @livewire('mensajes-chat')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
