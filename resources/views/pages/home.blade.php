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
    }
  </style>
@endpush

@section('content')
@php
  $images = [
    'amazoniavistodecima.avif',
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
        <figure class="border border-neutral-200 bg-white overflow-hidden">
          <a href="{{ request()->fullUrlWithQuery(['img' => $img]) }}" class="block focus:outline-none focus:ring-2 focus:ring-sky-400">
            <img src="{{ asset('nossos_ladrilhos/'.$img) }}" alt="projeto"
                 class="w-full h-[520px] md:h-[720px] object-cover" loading="lazy">
          </a>
        </figure>
      @endforeach
    </div>
  </div>
</section>

{{-- REMOVIDO: bloco "quer um desenho exclusivo?" com links "crie seu ladrilho / whatsapp" --}}

@if($hasMatch)
  <div id="lightbox" class="fixed inset-0 z-[999] bg-white">
    {{-- fundo clicável para fechar (fica atrás dos controles) --}}
    <button class="absolute inset-0 z-0 w-full h-full cursor-zoom-out" onclick="history.back()" aria-label="Fechar"></button>

    <div class="relative z-10 h-full flex flex-col">
      {{-- Top bar --}}
      <div class="flex justify-between items-center pt-6 px-4">
        <div class="flex gap-3">
          {{-- Tela cheia --}}
          <button onclick="toggleFullscreen()"
                  class="btn-chrome w-10 h-10 flex items-center justify-center rounded-full text-xl"
                  aria-label="Tela cheia" title="Tela cheia">⛶</button>
          {{-- Compartilhar --}}
          <button onclick="shareImage('{{ asset('nossos_ladrilhos/'.$current) }}')"
                  class="btn-chrome w-10 h-10 flex items-center justify-center rounded-full text-xl"
                  aria-label="Compartilhar" title="Compartilhar">↗</button>
        </div>

        {{-- Fechar --}}
        <button onclick="history.back()"
                class="btn-chrome w-10 h-10 flex items-center justify-center rounded-full text-xl"
                aria-label="Fechar" title="Fechar">✕</button>
      </div>

      {{-- Imagem + setas --}}
      <div class="relative flex-1 pb-6 flex items-center justify-center">
        <img src="{{ asset('nossos_ladrilhos/'.$current) }}" alt="projeto em destaque"
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
      // ESC fecha
      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') history.back();
      });

      // alterna fullscreen do documento
      function toggleFullscreen() {
        const el = document.documentElement;
        if (!document.fullscreenElement) {
          if (el.requestFullscreen) el.requestFullscreen();
        } else {
          if (document.exitFullscreen) document.exitFullscreen();
        }
      }

      // adiciona/remove classe quando entra/sai do FS
      document.addEventListener('fullscreenchange', () => {
        const lb = document.getElementById('lightbox');
        if (!lb) return;
        if (document.fullscreenElement) {
          lb.classList.add('is-fs');
        } else {
          lb.classList.remove('is-fs');
        }
      });

      // Compartilhar (Web Share API com fallback)
      function shareImage(url) {
        const shareUrl = url;
        if (navigator.share) {
          navigator.share({ title: 'Studio Latitude — ladrilho', url: shareUrl }).catch(()=>{});
        } else {
          navigator.clipboard?.writeText(shareUrl);
          alert('Link copiado: ' + shareUrl);
        }
      }
    </script>
  @endpush
@endif
@endsection
