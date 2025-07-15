<x-app-layout>
  <x-navbar />

  @push('styles')
  <!-- CDN de Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  @endpush

  @push('scripts')
  <!-- CDN de Leaflet JS -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  @endpush

  <div class="py-4 px-2 sm:py-6 sm:px-4">
    <div class="mx-auto max-w-7xl">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <livewire:mapa-monitoreo />
      </div>
    </div>
  </div>
</x-app-layout>