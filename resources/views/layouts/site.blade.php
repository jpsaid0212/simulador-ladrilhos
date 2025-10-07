<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Studio Latitude')</title>
  @vite('resources/css/app.css')
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  @stack('head')
</head>
<body class="antialiased text-neutral-900 bg-white">

  {{-- HERO (aparece em todas as páginas que usam este layout) --}}
  @hasSection('hide_hero')
    {{-- se algum blade precisar esconder, declare: @section('hide_hero', true) --}}
  @else
    @include('partials.hero')
    @include('partials.subnav')
  @endif

  {{-- CONTEÚDO DA PÁGINA --}}
  <main>
    @yield('content')
  </main>

  {{-- botão flutuante WhatsApp (opcional) --}}
  <a href="https://wa.me/5511934563752" target="_blank" rel="noopener"
     class="fixed right-4 bottom-4 inline-flex items-center gap-2 bg-white border border-neutral-200 shadow-lg rounded-full px-4 py-2">
    <span class="text-sm">Contact Us</span>
    <img alt="whatsapp" class="w-6 h-6" src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/whatsapp.svg">
  </a>

   @include('partials.footer')

  @stack('scripts')
</body>
</html>
