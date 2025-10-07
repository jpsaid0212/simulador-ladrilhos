{{-- resources/views/partials/subnav.blade.php --}}
@php

  use Illuminate\Support\Facades\Route;

  $homeUrl     = Route::has('home')     ? route('home')     : url('/');
  $blocos3dUrl = Route::has('blocos3d') ? route('blocos3d') : url('/projetos-blocos-3d');
  
  $tabs = [
    ['label' => 'nossos ladrilhos', 'url' => route('home') ?? url('/')],
    ['label' => 'exclusivos',       'url' => url('/exclusivos')],
    ['label' => 'clássicos',        'url' => url('/classicos')],
    ['label' => 'geométricos',      'url' => url('/geometricos')],
    ['label' => 'cores',            'url' => url('/cores')],
    ['label' => 'sobre',            'url' => url('/sobre')],
    ['label' => 'crie seu ladrilho','url' => url('/crie-seu-ladrilho')],
    ['label' => 'projetos / blocos 3D', 'url' => $blocos3dUrl],
    ['label' => 'contato',          'url' => url('/contato')],
  ];

  $currentUrl = rtrim(url()->current(), '/');
  $activeHex = '#c29a2b'; // ativo (mostarda)
  $hoverHex  = '#a87f21'; // hover (um pouco mais escuro)
  $textGray  = '#6e6e6e'; // cinza padrão do site
@endphp

<style>
  .subnav-link { border-radius: .2rem; }
  .subnav-link:hover {
    background: {{ $hoverHex }} !important;
    color: #fff !important;
  }
  .subnav-link.is-active {
    background: {{ $activeHex }} !important;
    color: #fff !important;
  }
</style>

<div class="sticky top-0 z-30 bg-white/90 backdrop-blur">
  <nav class="max-w-6xl mx-auto px-4">
    <ul class="flex flex-wrap justify-center items-center gap-6 py-4 text-[13px] md:text-[13px] font-bold tracking-wide relative">
      @foreach ($tabs as $tab)
        @php
          $isActive = rtrim($tab['url'], '/') === $currentUrl;
        @endphp
        <li class="relative">
          <a href="{{ $tab['url'] }}"
             class="subnav-link px-3 py-2 transition
               {{ $isActive ? 'is-active' : '' }}"
             style="color: {{ $isActive ? '#fff' : $textGray }};">
            {{ $tab['label'] }}
          </a>

          @if($isActive)
            {{-- seta maior e proporcional --}}
            <span class="absolute left-1/2 -bottom-[12px] -translate-x-1/2 w-0 h-0"
                  style="border-left:11px solid transparent;
                         border-right:11px solid transparent;
                         border-top:12px solid {{ $activeHex }};">
            </span>
          @endif
        </li>
      @endforeach

      {{-- linha cinza abaixo --}}
      <span class="pointer-events-none absolute left-0 right-0 -bottom-[1px] border-b border-neutral-300/80"></span>
    </ul>
  </nav>
</div>
