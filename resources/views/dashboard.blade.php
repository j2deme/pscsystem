<x-app-layout>
    <x-navbar />
@if (Auth::user()->rol == 'admin')
    <div class="mt-4 ml-4">
        <!-- El x-data es el que corrompe los charts para que no rendericen -->
        <div x-data="{ menu: 'admin' }" x-on:cambiar-menu.window="menu = $event.detail.menu" class="mb-4">
            <div x-show="menu === 'rrhh'" x-cloak><x-rh-navbar /></div>
            <div x-show="menu === 'nóminas'" x-cloak><x-nominas-navbar /></div>
            <div x-show="menu === 'imss'" x-cloak><x-auxadmin-navbar /></div>
        </div>
        <x-admin-navbar></x-admin-navbar> <!--funciona siempre y cuando este fuera de div x-data-->
    </div>
@else
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div x-data="{ menu: 'admin' }" x-on:cambiar-menu.window="menu = $event.detail.menu" class="space-y-4">
                    @if(session('success'))
                        <div class="bg-green-100 border-t-4 border-green-500 rounded-b text-green-900 px-4 py-3 shadow-md" role="alert">
                            <div class="flex">
                                <div>
                                    <p class="text-sm">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                        @else
                    @endif
                    @if (Auth::user()->rol != 'admin')
                        <p class="text-gray-900 text-2xl dark:text-gray-100 text-2xl">
                            Tablero de Opciones
                        </p>
                        <div class="">
                        @if(Auth::user()->rol == 'admin' || Auth::user()->rol == NULL)
                        @elseif (Auth::user()->rol == 'Supervisor')
                            <x-supervisor-navbar></x-supervisor-navbar>
                        @elseif(Auth::user()->rol == 'AUXILIAR NOMINAS' || Auth::user()->solicitudAlta->rol == 'AUXILIAR NOMINAS' || Auth::user()->rol == 'Auxiliar Nominas' || Auth::user()->solicitudAlta->rol == 'Auxiliar Nominas' )
                            <x-nominas-navbar></x-nominas-navbar>
                        @elseif (Auth::user()->solicitudAlta->departamento == 'Recursos Humanos' || Auth::user()->solicitudAlta->rol == 'AUXILIAR RECURSOS HUMANOS' || Auth::user()->solicitudAlta->rol == 'AUXILIAR RH' || Auth::user()->solicitudAlta->rol == 'AUX RH' || Auth::user()->solicitudAlta->rol == 'Auxiliar RH' || Auth::user()->solicitudAlta->rol == 'Auxiliar Recursos Humanos' || Auth::user()->solicitudAlta->rol == 'Aux RH' || Auth::user()->rol == 'AUXILIAR RH' || Auth::user()->rol == 'AUXILIAR RECURSOS HUMANOS' )
                            <x-rh-navbar></x-rh-navbar>
                        @elseif (Auth::user()->rol == 'AUXILIAR MONITORISTA' || Auth::user()->rol == 'MONITORISTA' || Auth::user()->rol == 'Auxiliar Monitorista' || Auth::user()->rol == 'Monitorista' || Auth::user()->solicitudAlta->departamento == 'Monitoreo')
                            <x-monitoreo-navbar></x-monitoreo-navbar>
                        @elseif (Auth::user()->rol == 'Auxiliar Administrativo' || Auth::user()->rol == 'AUXILIAR ADMINISTRATIVO' || Auth::user()->rol == 'Auxiliar administrativo' || Auth::user()->solicitudAlta->rol == 'Auxiliar administrativo')
                            <x-auxadmin-navbar></x-auxadmin-navbar>
                        @elseif(Auth::user()->rol == 'Juridico' || Auth::user()->rol == 'JURIDICO')
                            <x-juridico-navbar></x-juridico-navbar>
                        @else
                            <x-user-navbar></x-user-navbar>
                        @endif
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
    @endif
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
