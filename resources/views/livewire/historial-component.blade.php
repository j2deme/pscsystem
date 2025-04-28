<div>
        <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">
            @if ($mostrarCoberturas)
                Historial de Turnos Cubiertos
            @else
                Historial de Tiempos Extras
            @endif
        </h2>
        <a href="#" wire:click="cambiarVista" class="text-blue-500 hover:underline">
            @if ($mostrarCoberturas)
                Mostrar historial de tiempos extras
            @else
                Mostrar historial de turnos cubiertos
            @endif
        </a>

    @if ($mostrarCoberturas)
        @livewire('supcoberturaturno')
    @else
        @livewire('suptiempoextra')
    @endif

    <center class="mt-6">
        <a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400">
            Regresar
        </a>
    </center>
</div>

