<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Studio Latitude')</title>

  @vite('resources/css/app.css')

  <style>
    @font-face{
      font-family:'Futura';
      src: url('{{ asset('fonts/Futura-Regular.ttf') }}') format('truetype');
      font-weight:400;
      font-style:normal;
      font-display:swap;
    }
    @font-face{
      font-family:'Futura';
      src: url('{{ asset('fonts/Futura-Bold.ttf') }}') format('truetype');
      font-weight:700;
      font-style:normal;
      font-display:swap;
    }
    :root{ --font-futura: 'Futura', system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; }
    html,body{ font-family:var(--font-futura); font-weight:400; font-synthesis:none; }
    h1,h2,h3,h4,h5,h6,strong,b,.fw-bold{ font-weight:700; }
  </style>

  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  @stack('head')
</head>
<body class="antialiased text-neutral-900 bg-white">
  @hasSection('hide_hero')
  @else
    @include('partials.hero')
    @include('partials.subnav')
  @endif

  <main>@yield('content')</main>

  <a href="https://wa.me/5511934563752" target="_blank" rel="noopener"
     class="fixed right-4 bottom-4 inline-flex items-center gap-2 bg-white border border-neutral-200 shadow-lg rounded-full px-4 py-2">
    <span class="text-sm">Contact Us</span>
    <img alt="whatsapp" class="w-6 h-6" src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/whatsapp.svg">
  </a>

  @include('partials.footer')
  @stack('scripts')
</body>
</html>
