{{-- resources/views/partials/subnav.blade.php --}}
@php
  use Illuminate\Support\Facades\Route;

  $homeUrl     = Route::has('home')     ? route('home')     : url('/');
  $blocos3dUrl = Route::has('blocos3d') ? route('blocos3d') : url('/projetos-blocos-3d');

  $tabs = [
    ['label' => 'simulador',   'url' => url('/')],
    ['label' => 'simulador 2', 'url' => url('/simulador2')],
  ];

  $currentUrl = rtrim(url()->current(), '/');
  $activeHex = '#8bbcd9';
  $hoverHex  = '#6a9bb5';
  $textGray  = '#6e6e6e';
@endphp

<style>
  .subnav-link { border-radius: .2rem; }
  .subnav-link:hover { background: {{ $hoverHex }} !important; color:#fff !important; }
  .subnav-link.is-active { background: {{ $activeHex }} !important; color:#fff !important; }

  /* --- força tudo em uma linha --- */
  .subnav-wrap { max-width: 1500px; }          /* aumentei a largura útil */
  @media (min-width: 1536px){ .subnav-wrap{ max-width: 1600px; } } /* telas muito largas */

  .subnav-list{
    flex-wrap: nowrap !important;              /* não quebrar linha */
    white-space: nowrap;                       /* não permitir quebra Entre itens */
    column-gap: 1.25rem;                       /* ~gap-5 para equilibrar */
  }
</style>

<div class="sticky top-0 z-30 bg-white/90 backdrop-blur">
  <nav class="subnav-wrap mx-auto px-4">
    <ul class="subnav-list flex justify-center items-center py-4 text-[13px] md:text-[13px] font-bold tracking-wide relative">
      @foreach ($tabs as $tab)
        @php $isActive = rtrim($tab['url'], '/') === $currentUrl; @endphp
        <li class="relative">
          <a href="{{ $tab['url'] }}"
             class="subnav-link px-3 py-2 transition {{ $isActive ? 'is-active' : '' }}"
             style="color: {{ $isActive ? '#fff' : $textGray }};">
            {{ $tab['label'] }}
          </a>

          @if($isActive)
            <span class="absolute left-1/2 -bottom-[12px] -translate-x-1/2 w-0 h-0"
                  style="border-left:11px solid transparent;
                         border-right:11px solid transparent;
                         border-top:12px solid {{ $activeHex }};">
            </span>
          @endif
        </li>
      @endforeach

      <span class="pointer-events-none absolute left-0 right-0 -bottom-[1px] border-b border-neutral-300/80"></span>
    </ul>
  </nav>
</div>
