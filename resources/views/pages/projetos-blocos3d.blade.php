{{-- resources/views/pages/projetos-blocos3d.blade.php --}}
@extends('layouts.site')
@section('title', 'Studio Latitude — projetos / blocos 3D')

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
<section class="max-w-6xl mx-auto px-4 py-12 space-y-12 futura-text">

  {{-- LINK AZUL SUPERIOR (centralizado) --}}
  <div class="flex justify-center">
    <p class="text-center max-w-4xl">
      <a href="https://3dwarehouse.sketchup.com/user/ed2faa58-3c92-4513-9987-f09f6f2a835c/STUDIO-LATITUDE-LADRILHO"
         target="_blank" rel="noopener noreferrer"
         class="uppercase underline underline-offset-4 tracking-wide text-[#4069a8] font-semibold hover:text-[#204a92] transition">
        clique aqui para abrir o nosso perfil no 3d warehouse & acessar nossos blocos 3d disponíveis
      </a>
    </p>
  </div>

  {{-- INTRO: imagens à esquerda / texto à direita --}}
  <div class="grid md:grid-cols-2 gap-12 items-start">
    <div class="flex flex-col gap-8 justify-center">
      <img src="{{ asset('img/projetos-blocos-3d/projetos-blocos1.jpg') }}"
           alt="Catálogo e paginações 3D"
           class="w-full h-[230px] md:h-[250px] lg:h-[270px] object-cover"
           loading="lazy">
      <img src="{{ asset('img/projetos-blocos-3d/projetos-blocos2.jpg') }}"
           alt="Catálogo e paginações 3D"
           class="w-full h-[230px] md:h-[250px] lg:h-[270px] object-cover"
           loading="lazy">
    </div>

    <div class="text-[15px] leading-relaxed text-slate-700 space-y-5 flex flex-col justify-center">
      <p>Nosso objetivo é promover a experiência mais completa possível à nossos clientes e arquitetos parceiros.</p>
      <p>Entendemos que antes do produto final (ladrilho), existem inúmeras etapas de projeto arquitetônico para aperfeiçoar e pré-visualizar a obra.</p>
      <p>Por isso, disponibilizamos blocos em 3D (Sketch Up), na medida exata das peças e com as linhas fieis aos nossos ladrilhos – fazendo com que a visualização do projeto seja o mais realista possível.</p>
      <p>É possível também modificar as cores de todos os modelos, a partir da nossa extensa paleta de cores cimentícias, estas também disponibilizadas dentro do bloco 3D para extração mais próxima possível do tom real.</p>
      <p>Estamos trabalhando continuamente para tentar manter atualizada a nossa página de blocos 3D do SketchUp porém se você não conseguir encontrar algum modelo nosso que precisa para criar uma paginação, por favor entre em contato com a gente por e-mail ou whatsapp.</p>
    </div>
  </div>

  @php
    $galeria = [
      ['src' => asset('img/projetos-blocos-3d/galeria1.png'),  'title' => 'Marê Arquitetura + Studio Latitude'],
      ['src' => asset('img/projetos-blocos-3d/galeria2.jpeg'), 'title' => ''],
      ['src' => asset('img/projetos-blocos-3d/galeria3.png'),  'title' => 'Marê Arquitetura + Studio Latitude'],
      ['src' => asset('img/projetos-blocos-3d/galeria4.jpg'),  'title' => 'SOW Arquitetos + Studio Latitude'],
      ['src' => asset('img/projetos-blocos-3d/galeria5.jpg'),  'title' => 'Studio Gleeson Paulino + Studio Latitude'],
      ['src' => asset('img/projetos-blocos-3d/galeria6.jpg'),  'title' => 'Tatiana Waileman Arquitetura + Studio Latitude'],
      ['src' => asset('img/projetos-blocos-3d/galeria7.jpg'),  'title' => 'Banheiro BAR 01 - COGUMELOS.jpg'],
      ['src' => asset('img/projetos-blocos-3d/galeria8.jpeg'), 'title' => ''],
      ['src' => asset('img/projetos-blocos-3d/galeria9.jpeg'),  'title' => ''],
      ['src' => asset('img/projetos-blocos-3d/galeria10.jpeg'), 'title' => ''],
      ['src' => asset('img/projetos-blocos-3d/galeria11.jpeg'), 'title' => ''],
      ['src' => asset('img/projetos-blocos-3d/galeria12.jpeg'), 'title' => ''],
      ['src' => asset('img/projetos-blocos-3d/galeria13.jpeg'), 'title' => ''],
      ['src' => asset('img/projetos-blocos-3d/galeria14.jpeg'), 'title' => ''],
      ['src' => asset('img/projetos-blocos-3d/galeria15.jpeg'), 'title' => ''],
    ];
  @endphp

  <div id="galeria-blocos3d" class="space-y-8">
    <div class="grid md:grid-cols-2 gap-8">
      @foreach ([0,1] as $i)
        <button class="group w-full h-[480px] overflow-hidden"
                data-index="{{ $i }}"
                data-src="{{ $galeria[$i]['src'] }}"
                data-title="{{ $galeria[$i]['title'] }}">
          <img src="{{ $galeria[$i]['src'] }}" alt="Projeto 3D {{ $i+1 }}"
               class="w-full h-full object-cover group-hover:opacity-90 transition" loading="lazy">
        </button>
      @endforeach
    </div>

    <div class="grid md:grid-cols-2 gap-8">
      @foreach ([2,3] as $i)
        <button class="group w-full h-[360px] overflow-hidden"
                data-index="{{ $i }}"
                data-src="{{ $galeria[$i]['src'] }}"
                data-title="{{ $galeria[$i]['title'] }}">
          <img src="{{ $galeria[$i]['src'] }}" alt="Projeto 3D {{ $i+1 }}"
               class="w-full h-full object-cover group-hover:opacity-90 transition" loading="lazy">
        </button>
      @endforeach
    </div>

    <div class="grid md:grid-cols-2 gap-8">
      @foreach ([4,5] as $i)
        <button class="group w-full h-[420px] overflow-hidden"
                data-index="{{ $i }}"
                data-src="{{ $galeria[$i]['src'] }}"
                data-title="{{ $galeria[$i]['title'] }}">
          <img src="{{ $galeria[$i]['src'] }}" alt="Projeto 3D {{ $i+1 }}"
               class="w-full h-full object-cover group-hover:opacity-90 transition" loading="lazy">
        </button>
      @endforeach
    </div>

    <div class="grid md:grid-cols-2 gap-8">
      @foreach ([6,7] as $i)
        <button class="group w-full h-[400px] overflow-hidden"
                data-index="{{ $i }}"
                data-src="{{ $galeria[$i]['src'] }}"
                data-title="{{ $galeria[$i]['title'] }}">
          <img src="{{ $galeria[$i]['src'] }}" alt="Projeto 3D {{ $i+1 }}"
               class="w-full h-full object-cover group-hover:opacity-90 transition" loading="lazy">
        </button>
      @endforeach
    </div>

    <div class="grid md:grid-cols-3 gap-8">
      @foreach ([8,9,10] as $i)
        <button class="group w-full h-[420px] overflow-hidden"
                data-index="{{ $i }}"
                data-src="{{ $galeria[$i]['src'] }}"
                data-title="{{ $galeria[$i]['title'] }}">
          <img src="{{ $galeria[$i]['src'] }}" alt="Projeto 3D {{ $i+1 }}"
               class="w-full h-full object-cover group-hover:opacity-90 transition" loading="lazy">
        </button>
      @endforeach
    </div>

    <div class="grid md:grid-cols-3 gap-8">
      @foreach ([11,12,13] as $i)
        <button class="group w-full h-[420px] overflow-hidden"
                data-index="{{ $i }}"
                data-src="{{ $galeria[$i]['src'] }}"
                data-title="{{ $galeria[$i]['title'] }}">
          <img src="{{ $galeria[$i]['src'] }}" alt="Projeto 3D {{ $i+1 }}"
               class="w-full h-full object-cover group-hover:opacity-90 transition" loading="lazy">
        </button>
      @endforeach
    </div>

    <div class="flex justify-center">
      @php $i = 14; @endphp
      <button class="group w-full md:w-[80%] h-[480px] overflow-hidden"
              data-index="{{ $i }}"
              data-src="{{ $galeria[$i]['src'] }}"
              data-title="{{ $galeria[$i]['title'] }}">
        <img src="{{ $galeria[$i]['src'] }}" alt="Projeto 3D {{ $i+1 }}"
             class="w-full h-full object-cover group-hover:opacity-90 transition" loading="lazy">
      </button>
    </div>
  </div>

  {{-- LIGHTBOX --}}
  <div id="lb" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-white"></div>

    {{-- Botões topo esquerdo --}}
    <div class="absolute top-6 left-6 flex items-center gap-4 z-30 text-[#caa13b]">
      <button id="lb-fullscreen" class="text-2xl hover:text-[#a87f21]" title="Ver em tela cheia">&#8599;</button>
      <button id="lb-download" class="text-2xl hover:text-[#a87f21]" title="Baixar imagem">&#8681;</button>
    </div>

    {{-- Fechar --}}
    <button id="lb-close"
            class="absolute top-6 right-6 text-[#caa13b] hover:text-[#a87f21] text-3xl leading-none z-30"
            aria-label="Fechar">&times;</button>

    {{-- Anterior --}}
    <button id="lb-prev"
            class="absolute top-1/2 left-12 -translate-y-1/2 text-[#caa13b] hover:text-[#a87f21] text-4xl z-30"
            aria-label="Anterior">&#8249;</button>

    {{-- Conteúdo: imagem + painel lateral --}}
    <div class="absolute inset-0 flex items-center justify-center px-6 md:px-8 py-12 pointer-events-none z-10">

      {{-- IMPORTANTE: imagem mais à esquerda e painel mais largo para reduzir quebras --}}
      <div class="grid grid-cols-[auto_300px] gap-3 items-center">

        {{-- Imagem principal deslocada à esquerda --}}
        <div class="transform -translate-x-10 md:-translate-x-16">
          <img id="lb-img" src="" alt=""
               class="max-h-[82vh] max-w-[86vw] object-contain shadow pointer-events-auto">
        </div>

        {{-- Painel lateral (título + seta) --}}
        <div class="relative h-full w-[300px] pointer-events-auto select-none">
          {{-- Título com menor margem e largura maior para caber em até 2 linhas --}}
          <div id="lb-title"
               class="absolute top-8 right-3 md:top-10 md:right-5 text-right
                      text-xl md:text-2xl font-bold leading-tight tracking-wide text-slate-800
                      whitespace-normal">
          </div>

          {{-- Próxima --}}
          <button id="lb-next"
                  class="absolute top-1/2 right-0 -translate-y-1/2 text-[#caa13b] hover:text-[#a87f21] text-4xl"
                  aria-label="Próxima">&#8250;</button>
        </div>
      </div>
    </div>
  </div>

  {{-- SCRIPT --}}
  <script>
    (function () {
      const wrapper     = document.getElementById('galeria-blocos3d');
      const lb          = document.getElementById('lb');
      const img         = document.getElementById('lb-img');
      const ttl         = document.getElementById('lb-title');
      const btnClose    = document.getElementById('lb-close');
      const btnPrev     = document.getElementById('lb-prev');
      const btnNext     = document.getElementById('lb-next');
      const btnFull     = document.getElementById('lb-fullscreen');
      const btnDownload = document.getElementById('lb-download');

      let items = [];
      let idx   = 0;

      items = Array.from(wrapper.querySelectorAll('button[data-index]'))
                   .sort((a,b) => parseInt(a.dataset.index) - parseInt(b.dataset.index))
                   .map(b => ({ src: b.dataset.src, title: b.dataset.title }));

      function show(i) {
        if (i < 0) i = items.length - 1;
        if (i >= items.length) i = 0;
        idx = i;
        img.src = items[idx].src;
        ttl.textContent = items[idx].title || '';
        lb.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
      }

      function hide() {
        lb.classList.add('hidden');
        img.src = '';
        document.body.style.overflow = '';
        if (document.fullscreenElement) document.exitFullscreen?.();
      }

      wrapper.addEventListener('click', (e) => {
        const btn = e.target.closest('button[data-index]');
        if (!btn) return;
        show(parseInt(btn.dataset.index, 10));
      });

      [btnNext, btnPrev, btnClose, img, btnFull, btnDownload].forEach(el => {
        el.addEventListener('click', (e) => e.stopPropagation());
      });

      btnNext.addEventListener('click', () => show(idx + 1));
      btnPrev.addEventListener('click', () => show(idx - 1));
      btnClose.addEventListener('click', hide);

      lb.addEventListener('click', (e) => { if (e.target === lb) hide(); });

      window.addEventListener('keydown', (e) => {
        if (lb.classList.contains('hidden')) return;
        if (e.key === 'Escape')     hide();
        if (e.key === 'ArrowRight') show(idx + 1);
        if (e.key === 'ArrowLeft')  show(idx - 1);
      });

      btnFull.addEventListener('click', (e) => {
        e.stopPropagation();
        if (!document.fullscreenElement) {
          img.requestFullscreen?.();
        } else {
          document.exitFullscreen?.();
        }
      });

      btnDownload.addEventListener('click', (e) => {
        e.stopPropagation();
        const a = document.createElement('a');
        a.href = img.src;
        a.download = (img.src.split('/').pop() || 'imagem.jpg').split('?')[0];
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
      });
    })();
  </script>
</section>
@endsection
