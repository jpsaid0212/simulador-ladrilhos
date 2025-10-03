{{-- resources/views/shop/category.blade.php --}}
@extends('layouts.site')
@section('title', 'Studio Latitude — '.$title)

@section('content')
<section class="max-w-6xl mx-auto px-4 py-10">
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
    @foreach($items as $it)
      <a href="{{ $it['href'] ?? route('produto.show', $it['slug']) }}" class="block group relative z-10">
        <div class="grid grid-cols-2 grid-rows-2 aspect-square overflow-hidden bg-white border border-neutral-300">
          @for ($i = 0; $i < 4; $i++)
            <div class="relative">
              <img src="{{ $it['img'] ?? asset('simulator/patterns/azulejo1.avif') }}"
                   alt="{{ $it['name'] ?? 'Modelo' }}"
                   class="w-full h-full object-cover transition duration-300 group-hover:scale-105">
              <span class="pointer-events-none absolute inset-x-0 top-1/2 h-px bg-neutral-300/90"></span>
              <span class="pointer-events-none absolute inset-y-0 left-1/2 w-px bg-neutral-300/90"></span>
            </div>
          @endfor
        </div>
        <p class="mt-2 text-[12px] text-neutral-500 text-center">{{ $it['name'] ?? 'Modelo' }}</p>
      </a>
    @endforeach
  </div>
</section>
@endsection
