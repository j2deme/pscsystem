<x-app-layout>
  <x-slot name="header">
    <div class="flex flex-col items-start justify-between gap-2 sm:flex-row sm:items-center sm:gap-4">
      <div class="flex items-center gap-4">
        <img src="https://api.dicebear.com/9.x/initials/svg?seed={{ urlencode(auth()->user()->name) }}" alt="avatar"
          class="w-10 h-10 rounded-full" />
        <div class="flex flex-col">
          <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
            {{ Auth::user()->name }}
          </h2>
          <h3 class="text-sm text-gray-600 dark:text-gray-400">
            @if(Auth::user()->rol == 'admin')
            Administrador
            @else
            {{ Auth::user()->rol }}
            @endif
          </h3>
        </div>
      </div>
      <div class="flex flex-wrap gap-2 sm:gap-4">
        @if(Auth::user()->rol == 'admin')
        <x-admin-layout></x-admin-layout>
        @else
        <x-user-layout></x-user-layout>
        @endif
      </div>
    </div>
  </x-slot>
  <div class="px-4 py-6 mx-auto max-w-7xl">
    @livewire('vehiculos-crud')
  </div>
</x-app-layout>