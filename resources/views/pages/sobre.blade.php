{{-- resources/views/sobre.blade.php --}}
@extends('layouts.site')
@section('title', 'Studio Latitude — sobre')

@push('head')
<style>
  .sobre-copy{
    font-size:13.5px;
    line-height:1.9;
    color:rgb(79 79 79);
  }
  .sobre-copy p + p{
    margin-top:.9rem;
  }
  .sobre-h2{
    font-size:15px;
    color:#0ea5e9;
    font-weight:600;
  }
  .sobre-em{
    text-decoration:underline;
    text-decoration-thickness:1px;
  }
</style>
@endpush

@section('content')
<section class="max-w-6xl mx-auto px-4 pt-10 pb-16">

  {{-- BLOCO ÚNICO: Texto + 4 imagens --}}
  <div class="grid lg:grid-cols-12 gap-8 items-start">
    <div class="lg:col-span-7 sobre-copy">
      <p>
        O Studio Latitude é uma loja + estúdio de criação de ladrilho hidráulico com o objetivo de
        trazer ao mercado brasileiro e internacional uma alternativa ao design tradicional dos ladrilhos
        fabricados em larga escala — dominante no mercado atual.
      </p>

      <p>
        O Studio Latitude foi fundado em 2019 no bairro da Vila Romana, em São Paulo pela artista
        Luana De Gus, onde ela abriu seu atelier experimental de ladrilhos. Durante dois anos Luana
        realizou experimentações de produção a partir das técnicas clássicas de fabricação de ladrilhos
        até técnicas abstratas e criações de moldes alternativos para fabricação das peças.
      </p>

      <p>
        Foi durante esse tempo de atelier que Luana trabalhou principalmente no aperfeiçoamento para
        confecção dos <span class="sobre-em text-[#d97706] font-medium">moldes impressos em material 3D biodegradável</span>,
        o que é uma alternativa aos moldes tradicionais de ladrilho -
        <em>esses feitos em bronze ou latão.</em>
      </p>

      <p>
        Por conta dessa técnica inovadora e da possibilidade de criar os desenhos totalmente sob medida e
        fiel aos traços - em programas de modelagem 3D,
        <span class="sobre-em text-[#d97706] font-medium">fez-se então possível a criação de linhas autorais</span>,
        colaborações com artistas e arquitetos brasileiros - o que vem até hoje agregando à sua
        gama de ladrilhos uma riqueza extensa de arte & design assinado.
      </p>

      <p>
        Além das linhas exclusivas, os ladrilhos Studio Latitude podem ser feitos sob medida, de acordo
        com a necessidade e visão do cliente. Tornando projetos de arquitetura & design de interior sem
        igual.
      </p>

      <h2 class="sobre-h2 mt-46 mb-6">O que é ladrilho ?</h2>

      <p class="mb-6">
        Os ladrilhos hidráulicos surgiram no século XIX, possivelmente, no sul da Europa e Marrocos, e hoje,
        destacam-se dos outros revestimentos devido ao seu processo de fabricação artesanal.
      </p>

      <p class="mb-6">
        Ecológicos, ladrilhos são livres de cozimento, da utilização de energia elétrica e emissão de gases.
      </p>

      <p class="mb-6">
        Fabricados um a um, a partir da prensagem de camadas de cimento, areia, pigmentos orgânicos e pó de mármore,
        por uma prensa manual com uma força de 15 toneladas, seguidos de uma imersão em água e enfim finalizados em um
        processo de cura e secagem.
      </p>

      <p class="mb-6">
        Os ladrilhos hidráulicos são antiderrapantes, resistentes a impacto, a desgaste, à abrasão e possuem
        durabilidade média de 100 anos +.
      </p>
    </div>

    {{-- 4 imagens estáticas à direita (mesma posição/tamanho) --}}
    <aside class="lg:col-span-5 space-y-3">
      @php
        $imgsSobre = [
          ['src' => asset('img/sobre/sobre1.jpeg'), 'alt' => 'Studio Latitude — paleta de cores 1'],
          ['src' => asset('img/sobre/sobre2.jpeg'), 'alt' => 'Studio Latitude — paleta de cores 2'],
          ['src' => asset('img/sobre/sobre3.png'), 'alt' => 'Studio Latitude — paleta de cores 3'],
          ['src' => asset('img/sobre/sobre4.jpg'), 'alt' => 'Studio Latitude — paleta de cores 4'],
        ];
      @endphp

      @foreach ($imgsSobre as $fig)
        <img
          src="{{ $fig['src'] }}"
          alt="{{ $fig['alt'] }}"
          loading="lazy"
          class="w-full h-[220px] md:h-[240px] object-cover">
      @endforeach
    </aside>
  </div>

  {{-- VÍDEO ABAIXO --}}
  <div class="mt-12">
    <div class="relative pt-[56.25%]"> {{-- 16:9 --}}
      <video
        class="absolute inset-0 w-full h-full object-cover"
        controls
        playsinline
        preload="metadata"
        
        <source src="{{ asset('img/sobre/Studio_horizontal.mp4') }}" type="video/mp4">
      </video>
    </div>
  </div>

</section>
@endsection
