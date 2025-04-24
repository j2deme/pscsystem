<script src="https://unpkg.com/alpinejs" defer></script>
<x-app-layout>
    <x-navbar />
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div x-data="{ menu: 'admin' }" x-on:cambiar-menu.window="menu = $event.detail.menu" class="space-y-4">

                    @if (session('success'))
                        <div class="bg-green-200 text-gray-700 p-2 mb-4 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif
                    <p class="text-gray-900 text-2xl dark:text-gray-100 text-2xl">
                        Tablero de Opciones
                    </p>

                    <div class="">
                        @if(Auth::user()->rol == 'admin')
                            <div x-show="menu === 'admin'" x-cloak>
                                <x-admin-navbar />
                            </div>

                            <div x-show="menu === 'rh'" x-cloak>
                                <x-rh-navbar />
                            </div>
                        @elseif (Auth::user()->rol == 'Supervisor')
                            <x-supervisor-navbar></x-supervisor-navbar>
                        @elseif (Auth::user()->rol == 'Recursos Humanos')
                            <x-rh-navbar></x-rh-navbar>
                        @else
                            <x-user-navbar></x-user-navbar>
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
