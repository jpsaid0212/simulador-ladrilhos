@extends('layouts.site')
@section('title','Studio Latitude — crie seu ladrilho')

@section('content')
<section class="max-w-6xl mx-auto px-4 py-10 space-y-6">
  <h1 class="text-2xl md:text-3xl font-semibold">crie seu ladrilho</h1>
  <p class="text-slate-700 max-w-3xl">
    Envie um e-mail ou WhatsApp com o design desejado para analisarmos a viabilidade de criar o molde.
    Formatos sob medida: 10x10, 10x20, 15x15, 20x20.
  </p>
  <div class="flex gap-3">
    <a class="px-4 py-2 rounded border" href="mailto:ladrilho@studiolatitude.page">enviar e-mail</a>
    <a class="px-4 py-2 rounded bg-slate-900 text-white" href="https://wa.me/5511934563752" target="_blank">whatsapp</a>
    <a class="px-4 py-2 rounded border" href="{{ route('simulador.index') }}">abrir simulador</a>
  </div>
</section>
@endsection
