{{-- Componente de breadcrumb dinámico --}}
@props(['items' => []])
<nav class="mb-6" aria-label="Breadcrumb">
  <ol class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
    @foreach($items as $index => $item)
    @if($index > 0)
    <li>
      <i class="text-xs ti ti-chevron-right"></i>
    </li>
    @endif
    <li @class([ 'flex items-center gap-1' , 'font-semibold text-gray-700 dark:text-gray-200'=> $loop->last,
      ])>
      @if(isset($item['url']) && !$loop->last)
      <a href="{{ $item['url'] }}"
        class="flex items-center gap-1 text-blue-600 transition-colors duration-150 dark:text-blue-400 hover:text-blue-800 focus:text-blue-800"
        @if(isset($item['title'])) title="{{ $item['title'] }}" @endif>@if(isset($item['icon']))
        <i class="text-base ti {{ $item['icon'] }}"></i>
        @endif
        @if(isset($item['label']))<span>{{ $item['label'] }}</span>@endif
      </a>
      @else
      @if(isset($item['icon']))
      <i class="text-base ti {{ $item['icon'] }}"></i>
      @endif
      <span>{{ $item['label'] ?? '' }}</span>
      @endif
    </li>
    @endforeach
  </ol>
</nav>