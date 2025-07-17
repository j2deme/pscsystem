{{-- resources/views/livewire/monitoreo-layout.blade.php --}}
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
  <div class="container px-4 py-6 mx-auto">
    <x-breadcrumb :items="$breadcrumbItems" />
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
        <i class="ti {{ $icon ?? 'ti-car' }} text-xl"></i>
        {{ $titleMain }}
      </h1>
      @if(!empty($helpText))
      <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $helpText }}</p>
      @endif
    </div>
    <div>
      {{ $slot }}
    </div>
  </div>
</div>