{{-- <header class="border-b border-neutral-200">
  <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
    <a href="{{ route('home') }}" class="flex items-center gap-3">
      <img src="{{ asset('img/logo.png') }}" alt="Studio Latitude" class="h-9 w-auto">
      <span class="sr-only">Studio Latitude</span>
    </a>

    <button class="md:hidden inline-flex items-center justify-center w-10 h-10" x-data @click="$dispatch('toggle-menu')">
      <span class="sr-only">Abrir menu</span>
      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
      </svg>
    </button>
  </div>

  {{-- mobile fallback (links) --}}
  {{-- <div x-data="{open:false}" x-on:toggle-menu.window="open=!open" x-show="open" x-transition class="md:hidden border-t border-neutral-200">
    <nav class="max-w-6xl mx-auto px-4 py-3 grid gap-3 text-[15px]">
      <a href="{{ route('home') }}">nossos ladrilhos</a>
      <a href="{{ route('cat.exclusivos') }}">exclusivos</a>
      <a href="{{ route('cat.classicos') }}">clássicos</a>
      <a href="{{ route('cat.geometricos') }}">geométricos</a>
      <a href="{{ route('cores') }}">cores</a>
      <a href="{{ route('sobre') }}">sobre</a>
      <a href="{{ route('crie') }}">crie seu ladrilho</a>
      <a href="{{ route('blocos3d') }}">projetos / blocos 3D</a>
      <a href="{{ route('contato') }}">contato</a>
      <span>+</span>
    </nav>
  </div>
</header>  --}}
