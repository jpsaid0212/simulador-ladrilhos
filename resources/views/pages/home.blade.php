{{-- resources/views/ladrilhos.blade.php --}}
@extends('layouts.site')
@section('title', 'Studio Latitude — nossos ladrilhos')

@push('head')
  <style>
    /* MODO NORMAL (fora do fullscreen) */
    #lightbox:not(.is-fs) .btn-chrome { background: rgba(255,255,255,.90); }
    #lightbox:not(.is-fs) .btn-chrome:hover { background: #fff; }

    /* MODO FULLSCREEN (apenas quando o doc está em FS) */
    #lightbox.is-fs { background: #000; }
    #lightbox.is-fs .btn-chrome { background: rgba(255,255,255,.50); }
    #lightbox.is-fs .btn-chrome:hover { background: rgba(255,255,255,.70); }

    /* imagem cobrindo toda a tela apenas em FS */
    #lightbox.is-fs img.lb-img {
      width: 100vw !important;
      height: 100vh !important;
      object-fit: cover !important;
      object-position: center !important;
    }
  </style>
@endpush

@section('content')
@php
  /* Coloque suas 12 imagens dentro de public/img/nossos_ladrilhos/ */
  $images = [
    'home1.jpg',
    'home2.jpg',
    'home3.jpg',
    'home4.jpg',
    'home5.jpg',
    'home6.jpg',
    'home7.jpg',
    'home8.jpg',
    'home9.jpg',
    'home10.jpg',
    'home11.jpg',
    'home12.jpg',
  ];

  $current = request('img');
  $idx      = $current ? array_search($current, $images, true) : false;
  $hasMatch = $idx !== false;

  if ($hasMatch) {
    $count = count($images);
    $prev  = $images[($idx - 1 + $count) % $count];
    $next  = $images[($idx + 1) % $count];
  }
@endphp

<section>
  <div class="max-w-6xl mx-auto px-4 py-12 md:py-16">
    <div class="space-y-8 md:space-y-12">
      @foreach ($images as $img)
        {{-- figure sem overflow/borda para não dar impressão de "corte" --}}
        <figure class="bg-transparent">
          <a href="{{ request()->fullUrlWithQuery(['img' => $img]) }}"
             class="block focus:outline-none focus:ring-2 focus:ring-sky-400">
            <img
              src="{{ asset('img/nossos_ladrilhos/'.$img) }}"
              alt="ladrilho"
              class="block mx-auto w-full h-[520px] md:h-[720px] object-contain object-center"
              loading="lazy">
          </a>
        </figure>
      @endforeach
    </div>
  </div>
</section>

{{-- Lightbox --}}
@if($hasMatch)
  <div id="lightbox" class="fixed inset-0 z-[999] bg-white">
    <button class="absolute inset-0 z-0 w-full h-full cursor-zoom-out"
            onclick="window.location.href='{{ url('ladrilhos') }}'" aria-label="Fechar"></button>

    <div class="relative z-10 h-full flex flex-col">
      <div class="flex justify-between items-center pt-6 px-4">
        <div class="flex gap-3">
          <button onclick="toggleFullscreen()"
                  class="btn-chrome w-10 h-10 flex items-center justify-center rounded-full text-xl"
                  aria-label="Tela cheia" title="Tela cheia">⛶</button>
          <button onclick="shareImage('{{ asset('nossos_ladrilhos/'.$current) }}')"
                  class="btn-chrome w-10 h-10 flex items-center justify-center rounded-full text-xl"
                  aria-label="Compartilhar" title="Compartilhar">↗</button>
        </div>

        {{-- BOTÃO FECHAR --}}
        <button onclick="window.location.href='{{ url('/') }}'"
                class="btn-chrome w-10 h-10 flex items-center justify-center rounded-full text-xl"
                aria-label="Fechar" title="Fechar">✕</button>
      </div>

      <div class="relative flex-1 pb-6 flex items-center justify-center">
        <img src="{{ asset('img/nossos_ladrilhos/'.$current) }}" alt="projeto em destaque"
             class="lb-img w-[96vw] md:w-[92vw] h-auto max-h-[90vh] object-contain">

        <a href="{{ request()->fullUrlWithQuery(['img' => $prev]) }}"
           class="btn-chrome absolute left-3 md:left-6 top-1/2 -translate-y-1/2 w-12 h-12 flex items-center justify-center rounded-full text-2xl"
           aria-label="Anterior" title="Anterior">‹</a>

        <a href="{{ request()->fullUrlWithQuery(['img' => $next]) }}"
           class="btn-chrome absolute right-3 md:right-6 top-1/2 -translate-y-1/2 w-12 h-12 flex items-center justify-center rounded-full text-2xl"
           aria-label="Próximo" title="Próximo">›</a>
      </div>
    </div>
  </div>

  @push('scripts')
    <script>
      document.addEventListener('keydown', (e) => { if (e.key === 'Escape') history.back(); });

      function toggleFullscreen() {
        const el = document.documentElement;
        if (!document.fullscreenElement) { el.requestFullscreen?.(); }
        else { document.exitFullscreen?.(); }
      }

      document.addEventListener('fullscreenchange', () => {
        const lb = document.getElementById('lightbox');
        if (!lb) return;
        lb.classList.toggle('is-fs', !!document.fullscreenElement);
      });

      function shareImage(url) {
        if (navigator.share) {
          navigator.share({ title: 'Studio Latitude — ladrilho', url }).catch(()=>{});
        } else {
          navigator.clipboard?.writeText(url);
          alert('Link copiado: ' + url);
        }
      }
    </script>
  @endpush
@endif
@endsection
