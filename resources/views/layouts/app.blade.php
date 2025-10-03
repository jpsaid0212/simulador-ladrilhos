<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title','Simulador')</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  <!-- Alpine para reatividade leve -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 text-slate-900 antialiased">
  @yield('content')
</body>
</html>
