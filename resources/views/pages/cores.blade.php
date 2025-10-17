@extends('layouts.site')
@section('title','Studio Latitude — cores')

@section('content')
<section class="max-w-6xl mx-auto px-4 py-10">
  {{-- link para o catálogo no SketchUp --}}
  <div class="mt-6 text-left">
    <a href="https://3dwarehouse.sketchup.com/model/539982a9-6fdf-4eff-a67c-a3042f4c40c9/CATALOGO-DE-COR-STUDIO-LATITUDE-LADRILHO-SKETCH-UP" 
       target="_blank" 
       rel="noopener noreferrer"
       class="text-sky-600 font-semibold underline tracking-wide hover:text-sky-800">
      CLIQUE AQUI PARA ABRIR NOSSO CATÁLOGO DE CORES DIRETO NO SKETCH UP
    </a>
  </div>
  {{-- Apenas a imagem da tabela de cores --}}
  <div class="mt-8">
    <img src="{{ asset('img/cores/tabela-cores.jpg') }}" 
         alt="Tabela de cores Studio Latitude"
         class="max-w-3xl w-full h-auto">
  </div>
</section>
@endsection
