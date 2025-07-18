<x-app-layout>
  <x-navbar />
  <div class="px-4 py-6 mx-auto max-w-7xl">
    @livewire('vehiculo-detalle', ['id' => $id])
  </div>
</x-app-layout>