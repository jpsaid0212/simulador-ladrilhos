{{-- resources/views/crie-seu-ladrilho.blade.php --}}
@extends('layouts.site')
@section('title','Studio Latitude — crie seu ladrilho')

@push('head')
<style>
  /* === Futura LT W01 Medium (igual ao site original) === */
  @font-face {
    font-family: 'Futura LT W01 Medium';
    src: url('{{ asset('fonts/Futura LT W01 Medium.woff') }}') format('woff');
    font-weight: 400;
    font-style: normal;
    font-display: swap;
  }

  .futura-text {
    font-family: 'Futura LT W01 Medium', system-ui, sans-serif !important;
    font-weight: 400;
  }
</style>
@endpush

@section('content')
<section class="max-w-6xl mx-auto px-4 py-12 futura-text">

  {{-- Topo: texto à esquerda / imagens à direita --}}
  <div class="grid md:grid-cols-2 gap-10 items-start">
    {{-- Coluna de texto (esquerda) --}}
    <article class="space-y-5 text-[15px] leading-relaxed text-slate-700">
      <h1 class="text-xl md:text-2xl font-semibold text-[#caa13b]">Serviços adicionais</h1>

      <p>
        Além dos ladrilhos exclusivos encontrados em nosso catálogo, também oferecemos
        serviço de design sob medida onde você nos envia o <span class="font-semibold">design</span> desejado
        para o ladrilho e nós confeccionaremos o molde (este usado para confecção do ladrilho).
        O custo do projeto já cobre o serviço de customização/criação do desenho em software 3D
        bem como a impressão de molde em 3D.
      </p>

      <p>
        Nos envie um e-mail ou whatsapp com o design desejado para
        análisarmos a possibilidade de criar o molde com o seu design.
      </p>

      <p class="text-sky-700/90">
        <span class="font-semibold">Nota:</span> Os desenhos se tornam propriedade do Studio Latitude,
        podendo ser replicados em outros projetos.
      </p>

      <div class="pt-2">
        <p class="font-medium">É possível criar o design sob medida nos seguintes formatos:</p>
        <ul class="list-disc ml-5 mt-2">
          <li class="font-semibold">10x10 &ndash; 10x20 &ndash; 15x15 &ndash; 20x20</li>
        </ul>
      </div>

      {{-- Linha pontilhada + preço --}}
      <div class="pt-4">
        <p class="mb-2">Outros serviços adicionais:</p>
        <div class="flex items-baseline">
          <span>Paginação digital na planta</span>
          <span class="mx-3 grow border-b border-dotted border-slate-400"></span>
          <span class="whitespace-nowrap font-medium">R$ 900,00</span>
        </div>
      </div>
    </article>

    {{-- Coluna de imagens (direita): duas imagens empilhadas e limitadas de altura --}}
    <aside class="w-full md:max-w-[420px] md:ml-auto space-y-6">
      <div class="w-full h-[220px] sm:h-[260px] md:h-[280px] lg:h-[300px]">
        <img
          src="{{ asset('img/crie-seu-ladrilho/crie-seu-ladrilho1.png') }}"
          alt="Design autoral & customizável"
          class="w-full h-full object-contain"
          loading="lazy"
        />
      </div>
      <div class="w-full h-[180px] sm:h-[220px] md:h-[240px] lg:h-[260px]">
        <img
          src="{{ asset('img/crie-seu-ladrilho/crie-seu-ladrilho2.png') }}"
          alt="Exemplo ilustrativo"
          class="w-full h-full object-contain"
          loading="lazy"
        />
      </div>
    </aside>
  </div>

  {{-- Faixa inferior: duas imagens lado a lado (mesma altura e alinhadas) --}}
<div class="mt-10 grid md:grid-cols-2 gap-6 items-start">
  <figure class="w-full text-center">
    <div class="w-full h-[320px] sm:h-[380px] md:h-[420px] lg:h-[460px]">
      <img
        src="{{ asset('img/crie-seu-ladrilho/paginacao-natalia2.jpg') }}"
        alt="Paginação em planta-baixa"
        class="w-full h-full object-contain"
        loading="lazy"
      />
    </div>
    <figcaption class="mt-2 text-[14px] text-slate-500 italic text-left">
      Projeto de paginação do modelo "Janelas" em planta-baixa do cliente
    </figcaption>
  </figure>

  <figure class="w-full text-center">
    <div class="w-full h-[320px] sm:h-[380px] md:h-[420px] lg:h-[460px]">
      <img
        src="{{ asset('img/crie-seu-ladrilho/crie-seu-ladrilho3.jpg') }}"
        alt="Ladrilho final"
        class="w-full h-full object-contain"
        loading="lazy"
      />
    </div>
    {{-- Se não quiser legenda do lado direito, pode manter vazio ou remover o figcaption --}}
    <figcaption class="sr-only">Imagem de ladrilho</figcaption>
  </figure>
</div>

</section>
@endsection
