@extends('layouts.site')

@section('title', 'Studio Latitude — Simulador de Ladrilhos')

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
<div x-data="simuladorDP()" x-init="init()">

  <!-- aviso / instruções -->
  <div class="border border-slate-300 px-4 py-3 text-sm text-slate-700 font-bold">
    <p class="mb-1">crie seu ladrilho personalizado:</p>
    <ol class="list-decimal ml-5 space-y-1">
      <li>escolha o modelo na lista abaixo</li>
      <li>escolha as cores na nossa paleta de cores</li>
      <li>clique primeiro na cor e em seguida na parte do ladrilho que deseja pintar</li>
      <li>clique no botão salvar pdf</li>
      <li>nos envie o arquivo via whatsapp para orçamento e prazo de fabricação</li>
    </ol>
  </div>

 <!-- SELECTOR (faixa horizontal rolável) -->
  <div class="mt-10 max-w-xl mx-auto">
    <label class="block text-center text-slate-600 mb-3">1 — Selecione um modelo para customizar</label>

    <div class="relative" x-data="{ open:false, q:'', filtrar(list){
        const s = this.q.trim().toLowerCase();
        if(!s) return list;
        return list.filter(t => (t.nome||'').toLowerCase().includes(s) || (t.categoria||'').toLowerCase().includes(s));
      }}">
      <button type="button"
              class="w-full border border-slate-300 bg-white rounded-md px-3 py-2 flex items-center justify-between gap-3 hover:bg-slate-50"
              @click="open = !open">
        <div class="flex items-center gap-3 min-w-0">
          <img :src="tile?.id ? gerarThumb(tile) : (templates[0] ? gerarThumb(templates[0]) : '')"
               alt="" class="h-5 w-5 rounded-sm border border-slate-300 object-cover">
          <span class="truncate text-sm text-slate-800" x-text="tile?.nome ? tile.nome : 'Selecione um modelo'"></span>
        </div>
        <svg class="h-4 w-4 text-slate-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/></svg>
      </button>

      <div x-show="open" x-transition @click.outside="open=false"
           class="absolute z-30 mt-1 w-full rounded-md border border-slate-300 bg-white shadow-md">
        <div class="p-2 border-b border-slate-200 flex items-center gap-2">
          <input type="text" x-model="q" placeholder="Buscar…"
                 class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-slate-400">
          <svg class="h-4 w-4 text-slate-500 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.5 3.5a5 5 0 013.99 8.14l3.18 3.18a.75.75 0 11-1.06 1.06l-3.18-3.18A5 5 0 118.5 3.5zm0 1.5a3.5 3.5 0 100 7 3.5 3.5 0 000-7z" clip-rule="evenodd"/></svg>
        </div>

        <div class="max-h-72 overflow-auto py-1">
          <div class="px-3 py-1 text-[12px] text-slate-500">Selecione um modelo para customizar</div>
          <template x-for="tpl in filtrar(templates)" :key="tpl.id">
            <button type="button"
                    @click="selecionarTemplate(tpl); open=false; q='';"
                    class="w-full px-3 py-2 flex items-center gap-3 text-left hover:bg-slate-50"
                    :class="tile.id===tpl.id ? 'bg-rose-50' : ''">
              <img :src="gerarThumb(tpl)" alt="" class="h-8 w-8 rounded-sm border border-slate-300 object-cover">
              <div class="min-w-0">
                <div class="text-sm text-slate-800 truncate" x-text="tpl.nome"></div>
                <div class="text-[11px] text-slate-500 truncate" x-text="tpl.categoria"></div>
              </div>
            </button>
          </template>
        </div>
      </div>
    </div>
  </div>
<!-- /SELECTOR -->


  <div class="mt-4">
    <p class="text-center text-slate-600 text-sm">2 — Depois de escolher o modelo, selecione as cores ao lado e clique nas áreas do ladrilho.</p>
  </div>

  <!-- GRID: esquerda preview / direita paleta -->
  <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- ESQUERDA: preview -->
    <div class="flex flex-col items-center">
      <div class="relative bg-white border p-3">
        <template x-if="tile.type==='raster'">
          <div class="flex flex-col items-center">
            <canvas id="editorCanvas"
                    class="w-[320px] h-[320px] sm:w-[380px] sm:h-[380px] border border-slate-200 rounded"
                    @click="onCanvasClick($event)"></canvas>
            <div class="text-xs text-slate-500 mt-2">Clique na cor e depois em uma área do ladrilho para pintar</div>
          </div>
        </template>

        <template x-if="tile.type==='svg' && tile.id==='flor1'">
          <svg viewBox="0 0 100 100" class="w-[320px] h-[320px] sm:w-[380px] sm:h-[380px]">
            <rect x="0" y="0" width="100" height="100" :fill="cores.bg" @click="pintar('bg')"></rect>
            <ellipse cx="50" cy="50" rx="34" ry="18" :fill="cores.p1" @click="pintar('p1')"></ellipse>
            <ellipse cx="50" cy="50" rx="34" ry="18" transform="rotate(90,50,50)" :fill="cores.p2" @click="pintar('p2')"></ellipse>
            <circle cx="50" cy="50" r="10" :fill="cores.miolo" @click="pintar('miolo')"></circle>
            <rect x="4" y="4" width="92" height="92" fill="none" stroke-width="2.5" :stroke="cores.borda" @click="pintar('borda')"></rect>
          </svg>
        </template>

        <template x-if="tile.type==='svg' && tile.id==='geo1'">
          <svg viewBox="0 0 100 100" class="w-[320px] h-[320px] sm:w-[380px] sm:h-[380px]">
            <rect x="0" y="0" width="100" height="100" :fill="cores.bg" opacity="0" @click="pintar('bg')"></rect>
            <rect x="20" y="20" width="60" height="60" :fill="cores.p1" @click="pintar('p1')"></rect>
            <rect x="10" y="10" width="80" height="80" transform="rotate(45 50 50)" :fill="cores.p2" @click="pintar('p2')"></rect>
            <rect x="36" y="36" width="28" height="28" :fill="cores.miolo" @click="pintar('miolo')"></rect>
            <rect x="4" y="4" width="92" height="92" fill="none" stroke-width="2.5" :stroke="cores.borda" @click="pintar('borda')"></rect>
          </svg>
        </template>

        <template x-if="tile.type==='svg' && tile.id==='teste-simples'">
          <svg viewBox="0 0 100 100" class="w-[320px] h-[320px] sm:w-[380px] sm:h-[380px]">
            <rect x="0" y="0" width="100" height="100" :fill="cores.bg" opacity="0" @click="pintar('bg')"></rect>
            <rect x="20" y="20" width="60" height="60" :fill="cores.p1" @click="pintar('p1')"></rect>
            <rect x="10" y="10" width="80" height="80" transform="rotate(45 50 50)" :fill="cores.p2" @click="pintar('p2')"></rect>
            <rect x="36" y="36" width="28" height="28" :fill="cores.miolo" @click="pintar('miolo')"></rect>
            <rect x="4" y="4" width="92" height="92" fill="none" stroke-width="2.5" :stroke="cores.borda" @click="pintar('borda')"></rect>
          </svg>
        </template>
        <template x-if="tile.type==='svg' && tile.id==='teste-complexo'">
          <svg viewBox="0 0 100 100" class="w-[320px] h-[320px] sm:w-[380px] sm:h-[380px]">
            <rect x="0" y="0" width="100" height="100" :fill="cores.bg" opacity="0" @click="pintar('bg')"></rect>
            <rect x="20" y="20" width="60" height="60" :fill="cores.p1" @click="pintar('p1')"></rect>
            <rect x="10" y="10" width="80" height="80" transform="rotate(45 50 50)" :fill="cores.p2" @click="pintar('p2')"></rect>
            <rect x="36" y="36" width="28" height="28" :fill="cores.miolo" @click="pintar('miolo')"></rect>
            <rect x="4" y="4" width="92" height="92" fill="none" stroke-width="2.5" :stroke="cores.borda" @click="pintar('borda')"></rect>
          </svg>
        </template>
      </div>

      <div class="flex flex-col items-center">
        <div class="w-[340px] sm:w-[420px] mx-auto">
          <!-- MODELO + CORES USADAS -->
          <div class="mt-6">
            <p class="uppercase tracking-[0.25em] text-[12px] text-slate-700">
              STUDIO LATITUDE - Modelo:
              <span class="normal-case tracking-normal font-medium" x-text="tile?.nome || '—'"></span>
            </p>

            <div class="mt-3 flex items-center gap-4">
              <template x-for="c in coresUsadas()" :key="c">
                <div class="flex flex-col items-center">
                  <span class="h-10 w-10 rounded-sm border border-slate-300" :style="`background:${c}`"></span>
                  <span class="mt-1 text-[11px] text-slate-600" x-text="nomeDaCor(c)"></span>
                </div>
              </template>
            </div>
          </div>

          <!-- BOTÕES -->
          <div class="mt-5 flex items-center justify-between gap-4">
            <button
              @click="baixarLadrilhoPDF()
              "
              type="button"
              class="uppercase tracking-wider text-[11px] font-semibold text-white
                     bg-[#d9c3a3] hover:bg-[#cfb893] active:bg-[#c6ae89]
                     border border-[#d1c2a6] shadow-sm
                     px-6 py-3 rounded-sm flex-1 text-center">
              salvar pdf ou imprimir
            </button>

            <button
              x-show="tile.type==='raster'"
              @click="resetRaster()"
              type="button"
              class="text-[12px] text-slate-600 hover:text-slate-800
                     underline decoration-slate-300 hover:decoration-slate-500 underline-offset-2">
              Resetar ladrilho
            </button>
          </div>
        </div>
      </div>
    </div>

<!-- DIREITA: paleta com nomes (estilo Ladrilar) -->
<div>
  <div class="flex items-center justify-between mb-2">
    <p class="text-slate-600 text-sm">3 - Selecione as cores</p>
    <p class="text-[11px] text-slate-500 italic text-right leading-tight max-w-[220px]">
      As cores podem sofrer variações.
    </p>
  </div>

  <!-- wrapper com largura parecida com a referência -->
  <div class="bg-white px-2 py-3">
    <style>
      /* grade e espaçamentos iguais ao Ladrilar */
      .palette-ladrilar{
        display:grid;
        grid-template-columns: repeat(6, 84px); /* 6 por linha, quadrado + rótulo */
        /* row-gap / col-gap balanceados como no site */
        column-gap: 1px;   /* espaço lateral entre colunas */
        row-gap: 26px;      /* espaço vertical entre linhas */
        justify-content: start;
      }
      .swatch-wrap{ width:84px; }                 /* mesma largura do slot */
      .swatch-box{
        width: 72px; height: 72px;                /* tamanho do quadrado de cor */
        border: 1px solid #e6e6e6;                /* borda fininha e neutra */
        border-radius: 0;                         /* sem arredondar, como no site */
        box-shadow: 0 0 0 0 rgba(0,0,0,0);        /* sem glow; limpo */
      }
      .swatch-name{
        margin-top: 6px;
        font-size: 11px;
        line-height: 1.15rem;
        color: #4b5563;                           /* slate-600 */
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
      }
      .swatch-selected{
        outline: 2px solid #111827;               /* destaque no selecionado (preto) */
        outline-offset: 0;
      }

      /* responsivo próximo ao comportamento do site */
      @media (max-width: 1024px){
        .palette-ladrilar{ grid-template-columns: repeat(4, 84px); column-gap: 24px; }
      }
      @media (max-width: 640px){
        .palette-ladrilar{ grid-template-columns: repeat(3, 84px); column-gap: 20px; }
      }
    </style>

    <div class="palette-ladrilar">
      <template x-for="cor in coresLadrilar" :key="cor.nome">
        <button
          type="button"
          class="swatch-wrap text-left"
          @click="corSelecionada = cor.hex"
          :title="cor.nome"
        >
          <span
            class="swatch-box block"
            :class="corSelecionada===cor.hex ? 'swatch-selected' : ''"
            :style="`background:${cor.hex}`"
          ></span>
          <span class="swatch-name" x-text="cor.nome"></span>
        </button>
      </template>
    </div>
  </div>
</div>

  </div>

  <!-- TAPETE -->
  <div class="mt-12 lg:grid lg:grid-cols-[minmax(0,1fr)_340px] lg:gap-10">
    <div class="flex justify-center">
      <!-- removido rounded-sm daqui -->
      <div class="border-2 border-slate-300 p-2 bg-white shadow-sm">
        <div :style="estiloTapete()" id="tapete">
          <template x-for="i in rows*cols" :key="i">
            <div :style="estiloPeca()"></div>
          </template>
        </div>
      </div>
    </div>

    <aside class="mt-8 lg:mt-0">
  <div class="border-t-2 border-amber-200 mb-4"></div>
  <h3 class="uppercase text-[12px] font-semibold tracking-wider text-slate-700 mb-4">
    Opções de visualização
  </h3>

  <div class="space-y-5">
    <!-- CONTROLES: label ao lado do input -->
    <div class="space-y-3">
  <div class="flex items-center gap-2">
    <label class="text-sm text-slate-700 w-24 shrink-0">Colunas:</label>
    <input
      type="number"
      class="h-9 w-24 border border-[#d1a74a] rounded-none px-3 bg-white text-slate-700 focus:outline-none focus:ring-0 focus:border-[#d1a74a]"
      x-model.number="cols"
      :min="minCols"
      :max="maxCols"
      step="1"
      onwheel="this.blur()"
      @focus="$event.target.select()"
    />
  </div>

  <div class="flex items-center gap-2">
    <label class="text-sm text-slate-700 w-24 shrink-0">Linhas:</label>
    <input
      type="number"
      class="h-9 w-24 border border-[#d1a74a] rounded-none px-3 bg-white text-slate-700 focus:outline-none focus:ring-0 focus:border-[#d1a74a]"
      x-model.number="rows"
      :min="minRows"
      :max="maxRows"
      step="1"
      onwheel="this.blur()"
      @focus="$event.target.select()"
    />
  </div>
</div>


    <div>
      <div class="text-sm text-slate-700 mb-2">Cor do rejunte:</div>
      <div class="grid grid-cols-8 gap-2">
        <template x-for="cor in coresLadrilar" :key="'rej-'+cor.nome">
          <button
            class="h-9 w-9 rounded-sm border border-slate-300"
            :class="groutColor===cor.hex ? 'ring-2 ring-slate-900' : ''"
            :style="`background:${cor.hex}`"
            :title="cor.nome"
            @click="groutColor = cor.hex; if(sim.open){ gerarSimTexture() }">
          </button>
        </template>
      </div>
    </div>

    <button @click="atualizarTapete()"
            class="uppercase tracking-wider text-[11px] font-semibold text-white
                   bg-[#d9c3a3] hover:bg-[#cfb893] active:bg-[#c6ae89]
                   border border-[#d1c2a6] shadow-sm w-full py-3 rounded-sm">
      Visualizar
    </button>
  </div>

  <div class="border-t-2 border-amber-200 mt-6"></div>
</aside>

  </div>

  <!-- SIMULAÇÃO 3D -->
  <div class="mt-14">
    <h3 class="uppercase text-[20px] font-semibold tracking-wider text-slate-800">Simulação 3D</h3>
    <p class="text-slate-600 mb-4 text-sm">Escolha um dos ambientes para visualizar seu ladrilho aplicado.</p>

    <div class="flex flex-wrap gap-3">
      <template x-for="room in rooms" :key="room.id">
        <button
          @click="openSimulacao(room.id)"
          class="px-6 py-3 bg-[#e4d1b7] hover:bg-[#d9c3a3] text-[11px] font-semibold uppercase tracking-wider rounded-sm border border-[#d1c2a6]">
          <span x-text="room.nome"></span>
        </button>
      </template>
    </div>
  </div>

  <!-- MODAL 3D -->
  <div x-show="sim.open"
       x-transition.opacity
       class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4"
       @keydown.escape.window="sim.open=false"
       @click.self="sim.open=false">

    <div class="relative bg-white rounded-lg shadow-2xl p-3 w-auto max-w-[90vw] max-h-[90vh] flex items-center justify-center">
      <button @click="sim.open=false"
              class="absolute -top-3 -right-3 bg-white hover:bg-gray-100 rounded-full border-2 border-gray-300 w-8 h-8 flex items-center justify-center text-lg font-bold shadow-lg z-50 transition-colors">
        ✕
      </button>

     <div id="simWrap" class="relative inline-block overflow-hidden rounded">
      <div id="simFloor" class="absolute inset-0 z-0" :style="floorStyle()"></div>
      <img :src="sim.overlay"
          alt=""
          :style="`max-width: min(85vw, ${sim.maxWidth}px); max-height: min(75vh, ${sim.maxHeight}px); width: auto; height: auto; display: block;`"
          class="select-none relative z-10">
    </div>
    </div>
  </div>

  <!-- canvases ocultos -->
  <canvas id="canvasLadrilho" class="hidden"></canvas>
  <canvas id="canvasTapete" class="hidden"></canvas>

<script>
function simuladorDP(){
  return {
    modo: 'ladrilho',
    categorias: ['Centrais','Geométricos','Florões'],
    categoriaAtiva: 'Centrais',

    templates: [
      { id:'img_az1', type:'raster', nome:'Azulejo 1', categoria:'Centrais', src: "{{ asset('simulator/patterns/azulejo1.avif') }}" },
      // SVG files from public/simulator/patterns — rendered as images so they work with the canvas editor
      { id:'teste-simples-file', type:'raster', nome:'Teste (simples)', categoria:'Centrais', src: "{{ asset('simulator/patterns/teste cor versao simples.svg') }}" },
      { id:'teste-complexo-file', type:'raster', nome:'Teste (complexo)', categoria:'Centrais', src: "{{ asset('simulator/patterns/teste cor versao complexa.svg') }}" },
      { id:'animal-print', type:'raster', nome:'Animal Print', categoria:'Centrais', src: "{{ asset('simulator/patterns/1 ANIMAL PRINT.svg') }}" },
      { id:'andorinha', type:'raster', nome:'Andorinha', categoria:'Centrais', src: "{{ asset('simulator/patterns/1 ANDORINHA.svg') }}" },
      { id:'rita-lee', type:'raster', nome:'Rita Lee', categoria:'Centrais', src: "{{ asset('simulator/patterns/1 RITA LEE.svg') }}" },
      { id:'gama', type:'raster', nome:'Gama', categoria:'Geométricos', src: "{{ asset('simulator/patterns/GAMA.svg') }}" },
      { id:'organic-mix', type:'raster', nome:'Organic Mix', categoria:'Centrais', src: "{{ asset('simulator/patterns/ORGANIC MIX.svg') }}" },
      { id:'amazonia', type:'raster', nome:'Amazônia Vista de Cima', categoria:'Centrais', src: "{{ asset('simulator/patterns/amazonia vista de cima PAG.svg') }}" },
      { id:'geo1',  type:'svg', nome:'Geométrico 01', categoria:'Geométricos', coresDefault:{ bg:'#e6e6e6', p1:'#6b7280', p2:'#94a3b8', miolo:'#1f2937', borda:'#374151' } },
      { id:'flor1', type:'svg', nome:'Flor 01',       categoria:'Florões',    coresDefault:{ bg:'#e6e6e6', p1:'#8ab79b', p2:'#5a8d75', miolo:'#2f5d50', borda:'#2f3e46' } },
    ],

    tile: {},
    cores: {},
    corSelecionada: '#2b2b2b',

    groutColor: '#cfd8e3',
    rows: 4,
    cols: 4,

    /* limites e helpers de linhas/colunas */
    minRows: 4,
    maxRows: 4,
    minCols: 4,
    maxCols: 4,

    tapeteTileSize: 120,

    // === PALETA OFICIAL (Studio Latitude) ===
    coresLadrilar: [
      { nome:'branco',        hex:'#F7F7F4' },
      { nome:'cinza claro',   hex:'#DDDCD2' },
      { nome:'cinza',         hex:'#B4B1AC' },
      { nome:'prata',         hex:'#9FA7AD' },
      { nome:'preto',         hex:'#2F2F2F' },

      // linha 2
      { nome:'offwhite',      hex:'#F4F2EA' },
      { nome:'craft claro',   hex:'#EFE0CF' },
      { nome:'craft',         hex:'#CDA87B' },
      { nome:'craft esc',     hex:'#B08F64' },
      { nome:'amêndoa',       hex:'#CDA06D' },
      { nome:'marrom',        hex:'#6F4D4D' },

      // linha 3
      { nome:'bege',          hex:'#EEDFC4' },
      { nome:'amarelo cl',    hex:'#F8DE78' },
      { nome:'amarelo',       hex:'#F4C232' },
      { nome:'mostarda',      hex:'#E4AE54' },
      { nome:'verde cana',    hex:'#B9AA54' },

      // linha 4
      { nome:'terracota cl',  hex:'#EDA382' },
      { nome:'terracota',     hex:'#DF825F' },
      { nome:'terracota esc', hex:'#CF735A' },
      { nome:'verm vivo',     hex:'#A6423C' },
      { nome:'verm esc',      hex:'#8F332C' },

      // linha 5
      { nome:'rosa cl',       hex:'#F1C7BF' },
      { nome:'rosa',          hex:'#F3B6B9' },
      { nome:'goiaba',        hex:'#E06A5E' },

      // linha 6
      { nome:'verde cl',      hex:'#B4CBB6' },
      { nome:'verde',         hex:'#6A834F' },
      { nome:'jaguar',        hex:'#2F7F5E' },
      { nome:'verde esc',     hex:'#3F5242' },

      // linha 7
      { nome:'az grizzo',     hex:'#D8DADD' },
      { nome:'az grizzo cl',  hex:'#CFD7D9' },
      { nome:'tiffany cl',    hex:'#AEEBEA' },
      { nome:'tiffany',       hex:'#75E0E6' },
      { nome:'ocean blue',    hex:'#167C8A' },

      // linha 8
      { nome:'azul cl',       hex:'#A9C1DA' },
      { nome:'cobalto',       hex:'#3F92D2' },
      { nome:'porto',         hex:'#6A85B3' },
      { nome:'azul ultramar', hex:'#3C5AA6' },
      { nome:'azul esc',      hex:'#4B5F7F' },
      { nome:'azul esc 2',    hex:'#3B4C63' },

    ],


    // editor raster
    editor: { canvas: null, ctx: null, img: null, original: null, scaleX:1, scaleY:1, tol: 28, segments: null },

    tileURL: '',
    usedColorsRaster: [],

    /* --------- SIMULAÇÃO 3D (CSS 3D) --------- */
    rooms: [
    {
      id: 'sala',
      nome: 'piso',
      overlay: "{{ asset('simulator/rooms/piso.png') }}",
      tileSize: 35,
      offsetY: 0,
      perspective: true,
      maxWidth: 800,
      maxHeight: 600
    },
    {
      id: 'banheiro',
      nome: 'Parede Esquerda',
      overlay: "{{ asset('simulator/rooms/paredeesquerdafundo.png') }}",
      tileSize: 60,
      offsetY: 0,
      maxWidth: 800,
      maxHeight: 700
    },
    {
      id: 'cozinha',
      nome: 'Parede De Fundo',
      overlay: "{{ asset('simulator/rooms/parededefundo.png') }}",
      tileSize: 62,
      offsetY: 0,
      maxWidth: 850,
      maxHeight: 650
    },
    {
      id: 'quarto',
      nome: 'Parede Central',
      overlay: "{{ asset('simulator/rooms/paredecentral.png') }}",
      tileSize: 58,
      offsetY: 90,
      maxWidth: 800,
      maxHeight: 500
    },
    {
      id: 'sala-jantar',
      nome: 'Cozinha',
      overlay: "{{ asset('simulator/rooms/cozinha_overlay.png') }}",
      tileSize: 65,
      offsetY: 0,
      maxWidth: 850,
      maxHeight: 650
    }
  ],

    sim: { open:false, tileSize:90, offsetY:0, groutScale:1, overlay:'', floorMask:'', maxWidth:900, maxHeight:700 },

    // NOVO: textura (dataURL) com ladrilho + rejunte para o 3D
    simTextureURL: '',

    /* --------- init --------- */
    init(){
      this.$watch('groutColor', () => { if (this.sim.open) this.gerarSimTexture() });
      this.$watch('sim.groutScale', () => { if (this.sim.open) this.gerarSimTexture() });
      this.$watch('tileURL', () => { if (this.sim.open) this.gerarSimTexture() });

      this.selecionarTemplate(this.templates[0]);
      this.atualizarTapete();

      // watchers reativos para linhas/colunas (clamp + render)
      this.$watch('rows', v => this.setRows(v));
      this.$watch('cols', v => this.setCols(v));

      window.addEventListener('resize', () => {});
    },

    // helpers de linhas/colunas
    clamp(v, min, max){
      v = Number.isFinite(v) ? Math.round(v) : min;
      return Math.min(max, Math.max(min, v));
    },
    setRows(v){
      this.rows = this.clamp(v, this.minRows, this.maxRows);
      this.atualizarTapete();
    },
    setCols(v){
      this.cols = this.clamp(v, this.minCols, this.maxCols);
      this.atualizarTapete();
    },
    incRows(){ this.setRows((this.rows ?? 0) + 1) },
    decRows(){ this.setRows((this.rows ?? 0) - 1) },
    incCols(){ this.setCols((this.cols ?? 0) + 1) },
    decCols(){ this.setCols((this.cols ?? 0) - 1) },

    // NOVO: espessura do rejunte (px CSS) proporcional ao tamanho do ladrilho do ambiente
    groutPx3D(){
      const t = this.sim.tileSize || 90;
      const hairline = t * 0.002;          // base fina
      const scaled = hairline * (this.sim.groutScale || 1); 
      return Math.max(0.25, scaled);       // mínimo bem fino
    },

    // NOVO: gera textura (tile + borda de rejunte) e guarda em simTextureURL
    async gerarSimTexture(){
      if(!this.tileURL) this.gerarTileURL();

      const baseTilePx = 512; // tamanho do tile dentro da textura
      const gCss = this.groutPx3D();
      const gPx = Math.max(1, Math.round(baseTilePx * (gCss / (this.sim.tileSize || 90))));

      const canvas = document.createElement('canvas');
      canvas.width  = baseTilePx + 2*gPx;
      canvas.height = baseTilePx + 2*gPx;

      const ctx = canvas.getContext('2d');
      // fundo = rejunte
      ctx.fillStyle = this.groutColor;
      ctx.fillRect(0, 0, canvas.width, canvas.height);

      // tile no centro
      await new Promise((resolve, reject) => {
        const img = new Image();
        img.crossOrigin = 'anonymous';
        img.onload = () => {
          ctx.drawImage(img, gPx, gPx, baseTilePx, baseTilePx);
          this.simTextureURL = canvas.toDataURL('image/png');
          resolve();
        };
        img.onerror = reject;
        img.src = this.tileURL;
      });
    },

    templatesFiltrados(){ return this.templates.filter(t => t.categoria === this.categoriaAtiva) },

    selecionarTemplate(tpl){
      this.tile = tpl;
      this.usedColorsRaster = [];
      if (tpl.type === 'raster') {
        this.iniciarEditorRaster(tpl.src);
      } else {
        this.cores = { ...tpl.coresDefault };
        this.gerarTileURL();
      }
    },

    gerarThumb(tpl){
      if (tpl.type === 'raster') return tpl.src;
      const c = tpl.coresDefault;
      const svg = tpl.id==='flor1'
        ? `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><rect width="100" height="100" fill="${c.bg}"/><ellipse cx="50" cy="50" rx="34" ry="18" fill="${c.p1}"/><ellipse cx="50" cy="50" rx="34" ry="18" transform="rotate(90 50 50)" fill="${c.p2}"/><circle cx="50" cy="50" r="10" fill="${c.miolo}"/><rect x="4" y="4" width="92" height="92" fill="none" stroke="${c.borda}" stroke-width="2.5"/></svg>`
        : `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><rect width="100" height="100" fill="${c.bg}"/><rect x="20" y="20" width="60" height="60" fill="${c.p1}"/><rect x="10" y="10" width="80" height="80" transform="rotate(45 50 50)" fill="${c.p2}"/><rect x="36" y="36" width="28" height="28" fill="${c.miolo}"/><rect x="4" y="4" width="92" height="92" fill="none" stroke="${c.borda}" stroke-width="2.5"/></svg>`;
      return 'data:image/svg+xml;utf8,' + encodeURIComponent(svg);
    },

    // ===== SVG =====
    pintar(campo){
      this.cores[campo] = this.corSelecionada;
      this.gerarTileURL();
      if(this.sim.open){ this.gerarSimTexture() } // reflete no 3D se aberto
    },

    svgString(){
      if(this.tile.id==='flor1'){
        return `
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
          <rect width="100" height="100" fill="${this.cores.bg}"/>
          <ellipse cx="50" cy="50" rx="34" ry="18" fill="${this.cores.p1}"/>
          <ellipse cx="50" cy="50" rx="34" ry="18" transform="rotate(90 50 50)" fill="${this.cores.p2}"/>
          <circle cx="50" cy="50" r="10" fill="${this.cores.miolo}"/>
          <rect x="4" y="4" width="92" height="92" fill="none" stroke="${this.cores.borda}" stroke-width="2.5"/>
        </svg>`;
      }
      return `
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
        <rect width="100" height="100" fill="${this.cores.bg}"/>
        <rect x="20" y="20" width="60" height="60" fill="${this.cores.p1}"/>
        <rect x="10" y="10" width="80" height="80" transform="rotate(45 50 50)" fill="${this.cores.p2}"/>
        <rect x="36" y="36" width="28" height="28" fill="${this.cores.miolo}"/>
        <rect x="4" y="4" width="92" height="92" fill="none" stroke="${this.cores.borda}" stroke-width="2.5"/>
      </svg>`;
    },

    gerarTileURL(){
      if (this.tile.type === 'svg') {
        const svg = this.svgString();
        this.tileURL = 'data:image/svg+xml;utf8,' + encodeURIComponent(svg);
      } else if (this.tile.type === 'raster' && this.editor.canvas) {
        this.tileURL = this.editor.canvas.toDataURL('image/png');
      }
      // CSS 3D usa this.tileURL direto no style()
    },

    // ===== RASTER =====
    iniciarEditorRaster(src){
      this.editor.canvas = document.getElementById('editorCanvas');
      this.editor.ctx = this.editor.canvas.getContext('2d');

      const targetW = 600, targetH = 600;
      this.editor.canvas.width = targetW;
      this.editor.canvas.height = targetH;

      const img = new Image();
      img.crossOrigin = 'anonymous';
      img.onload = () => {
        this.editor.img = img;
        this.editor.ctx.clearRect(0,0,targetW,targetH);
        this.editor.ctx.drawImage(img, 0, 0, targetW, targetH);
        this.editor.original = this.editor.ctx.getImageData(0,0,targetW,targetH);
        this.usedColorsRaster = [];

        // Criar mapa de segmentos para áreas independentes
        this.criarMapaSegmentos();

        this.gerarTileURL();
      };
      img.src = src;
    },

    // Cria um mapa de segmentos identificando áreas independentes
    criarMapaSegmentos(){
      const { ctx, canvas } = this.editor;
      const imgData = ctx.getImageData(0, 0, canvas.width, canvas.height);
      const data = imgData.data;
      const W = canvas.width, H = canvas.height;

      // Inicializar mapa de segmentos
      this.editor.segments = new Int32Array(W * H);
      let segmentId = 0;

      const idx = (x, y) => y * W + x;
      const pixIdx = (x, y) => (y * W + x) * 4;

      // Função para verificar se dois pixels são similares
      const isSimilar = (idx1, idx2, tolerance = 30) => {
        return Math.abs(data[idx1] - data[idx2]) <= tolerance &&
               Math.abs(data[idx1+1] - data[idx2+1]) <= tolerance &&
               Math.abs(data[idx1+2] - data[idx2+2]) <= tolerance;
      };

      // Flood fill para marcar cada segmento
      const visited = new Uint8Array(W * H);

      for (let y = 0; y < H; y++) {
        for (let x = 0; x < W; x++) {
          const i = idx(x, y);
          if (visited[i]) continue;

          segmentId++;
          const stack = [[x, y]];
          const pIdx = pixIdx(x, y);

          while (stack.length > 0) {
            const [cx, cy] = stack.pop();
            const ci = idx(cx, cy);

            if (visited[ci]) continue;
            if (cx < 0 || cx >= W || cy < 0 || cy >= H) continue;

            const cPixIdx = pixIdx(cx, cy);
            if (!isSimilar(pIdx, cPixIdx)) continue;

            visited[ci] = 1;
            this.editor.segments[ci] = segmentId;

            stack.push([cx-1, cy], [cx+1, cy], [cx, cy-1], [cx, cy+1]);
          }
        }
      }

      console.log(`Mapa de segmentos criado com ${segmentId} áreas independentes`);
    },

    resetRaster(){
      if (!this.editor.original) return;
      this.editor.ctx.putImageData(this.editor.original, 0, 0);
      this.usedColorsRaster = [];
      // Recriar mapa de segmentos após reset
      this.criarMapaSegmentos();
      this.gerarTileURL();
    },

    onCanvasClick(ev){
      if (this.tile.type !== 'raster') return;
      const rect = ev.target.getBoundingClientRect();
      const x = Math.floor((ev.clientX - rect.left) * (this.editor.canvas.width / rect.width));
      const y = Math.floor((ev.clientY - rect.top) * (this.editor.canvas.height / rect.height));

      // Usar novo método que pinta apenas o segmento clicado
      this.pintarSegmento(x, y, this.hexToRgba(this.corSelecionada));
      this.addUsedColor(this.corSelecionada);
      this.gerarTileURL();
      if(this.sim.open){ this.gerarSimTexture() } // reflete no 3D se aberto
    },

    // Pinta apenas o segmento específico clicado
    pintarSegmento(x, y, newColor){
      const { ctx, canvas, segments } = this.editor;
      if (!segments) return;

      const W = canvas.width, H = canvas.height;
      const targetSegment = segments[y * W + x];
      if (targetSegment === 0) return; // Área sem segmento

      const imgData = ctx.getImageData(0, 0, W, H);
      const data = imgData.data;

      // Pintar apenas pixels do mesmo segmento
      for (let i = 0; i < segments.length; i++) {
        if (segments[i] === targetSegment) {
          const pixIdx = i * 4;
          data[pixIdx] = newColor[0];
          data[pixIdx + 1] = newColor[1];
          data[pixIdx + 2] = newColor[2];
          data[pixIdx + 3] = 255;
        }
      }

      ctx.putImageData(imgData, 0, 0);
    },

    floodFill(x, y, newColor){
      const { ctx, canvas, tol } = this.editor;
      const imgData = ctx.getImageData(0,0,canvas.width,canvas.height);
      const data = imgData.data;

      const idx = (x, y) => (y * canvas.width + x) * 4;
      const target = data.slice(idx(x,y), idx(x,y)+4);
      const sameColor = (i) => data[i]===newColor[0] && data[i+1]===newColor[1] && data[i+2]===newColor[2] && data[i+3]===255;
      const withinTol = (i) =>
        Math.abs(data[i]-target[0]) <= tol &&
        Math.abs(data[i+1]-target[1]) <= tol &&
        Math.abs(data[i+2]-target[2]) <= tol &&
        Math.abs(data[i+3]-target[3]) <= 40;

      if (sameColor(idx(x,y))) return;

      const stack = [[x,y]];
      const W = canvas.width, H = canvas.height;
      while (stack.length) {
        const [cx, cy] = stack.pop();
        let i = idx(cx, cy);
        if (!withinTol(i)) continue;

        data[i]   = newColor[0];
        data[i+1] = newColor[1];
        data[i+2] = newColor[2];
        data[i+3] = 255;

        if (cx > 0)       stack.push([cx-1, cy]);
        if (cx < W-1)     stack.push([cx+1, cy]);
        if (cy > 0)       stack.push([cx, cy-1]);
        if (cy < H-1)     stack.push([cx, cy+1]);
      }
      ctx.putImageData(imgData, 0, 0);
    },

    hexToRgba(hex){
      let h = hex.replace('#','');
      if (h.length===3) h = h.split('').map(c=>c+c).join('');
      const r = parseInt(h.substr(0,2),16);
      const g = parseInt(h.substr(2,2),16);
      const b = parseInt(h.substr(4,2),16);
      return [r,g,b,255];
    },

    // ===== TAPETE =====
    estiloTapete(){
      const size = this.tapeteTileSize; // 120
      const gap = 1;
      return {
        display: 'grid',
        gridTemplateColumns: `repeat(${this.cols}, ${size}px)`,
        gridAutoRows: `${size}px`,
        gap: `${gap}px`,
        background: this.groutColor,
        padding: `${gap}px`,
        width: 'fit-content'
      }
    },

    estiloPeca(){
      const size = this.tapeteTileSize;
      return {
        width: `${size}px`,
        height: `${size}px`,
        backgroundImage: `url('${this.tileURL}')`,
        backgroundSize: 'cover',
        backgroundPosition: 'center',
        borderRadius: '0' // sem arredondamento nas peças
      }
    },

    atualizarTapete(){ if(!this.tileURL) this.gerarTileURL() },

    baixarLadrilho(){
      const url = (this.tile.type==='svg')
        ? ('data:image/svg+xml;utf8,' + encodeURIComponent(this.svgString()))
        : this.editor.canvas.toDataURL('image/png');
      const a = document.createElement('a');
      a.href = url;
      a.download = 'ladrilho.png';
      a.click();
    },

    baixarTapete(){
      const size = this.tapeteTileSize; // 120
      const gap = 2;
      const totalW = this.cols*size + (this.cols+1)*gap;
      const totalH = this.rows*size + (this.rows+1)*gap;

      const canvas = document.getElementById('canvasTapete');
      const ctx = canvas.getContext('2d');
      canvas.width = totalW; canvas.height = totalH;

      ctx.fillStyle = this.groutColor;
      ctx.fillRect(0,0,totalW,totalH);

      const img = new Image();
      img.onload = () => {
        for(let r=0;r<this.rows;r++){
          for(let c=0;c<this.cols;c++){
            const x = gap + c*(size+gap);
            const y = gap + r*(size+gap);
            ctx.drawImage(img, x, y, size, size);
          }
        }
        const url = canvas.toDataURL('image/png');
        const a = document.createElement('a');
        a.href = url;
        a.download = 'tapete.png';
        a.click();
      }
      img.src = this.tileURL;
    },


    /* ===== nomes e lista de cores usadas ===== */
    addUsedColor(hex){
      if(!hex) return;
      let n = (hex||'').toLowerCase();
      if(!n.startsWith('#')) n = '#'+n;
      if(n.length===4){ n = '#'+n.slice(1).split('').map(c=>c+c).join(''); }
      if(!this.usedColorsRaster.includes(n)){
        this.usedColorsRaster.push(n);
      }
    },

    nomeDaCor(hex){
      if(!hex) return '';
      const h = (hex || '').toLowerCase();
      const m = (this.coresLadrilar || []).find(c => (c.hex || '').toLowerCase() === h);
      return m ? c.nome : h;
    },

    coresUsadas(){
      if (this.tile?.type === 'svg' && this.cores){
        const ordem = ['p1','p2','miolo','borda','bg'];
        const seq = ordem.map(k => this.cores[k]).filter(Boolean);
        const out = []; const seen = new Set();
        for (const hex of seq){
          const h = (hex||'').toLowerCase();
          if(!seen.has(h)){ seen.add(h); out.push(hex); }
        }
        return out;
      }
      return this.usedColorsRaster.length ? this.usedColorsRaster : [];
    },

    /* ===== PDF ===== */
    async baixarLadrilhoPDF(){
      if(!this.tileURL) this.gerarTileURL();

      const jsPDF = await this._loadJsPDF();
      const pdf = new jsPDF({ unit: 'pt', format: 'a4', compress: true });
      const pw = pdf.internal.pageSize.getWidth();
      const ph = pdf.internal.pageSize.getHeight();
      const margin = 36;

      const now = new Date();
      pdf.setFont('helvetica','normal'); pdf.setTextColor(80);
      pdf.setFontSize(8);
      pdf.text(now.toLocaleDateString() + ', ' + now.toLocaleTimeString(), margin, margin-10);
      pdf.setFontSize(9);
      pdf.text('Simulador | Studio Latitude', pw/2, margin-10, {align:'center'});

      const imgSize = Math.min(pw - margin*2, 420);
      const ix = (pw - imgSize)/2, iy = margin + 8;
      pdf.addImage(this.tileURL, 'PNG', ix, iy, imgSize, imgSize, undefined, 'FAST');

      const tituloY = iy + imgSize + 22;
      pdf.setFontSize(9); pdf.setTextColor(60);
      pdf.text('Studio Latitude - MODELO: ' + (this.tile?.nome || '').toUpperCase(), margin, tituloY);

      const usados = this.coresUsadas();
      const sw = 48, sh = 48, gap = 14;
      let sx = margin, sy = tituloY + 10;

      pdf.setLineWidth(0.5);
      usados.forEach((hex) => {
        const rgb = this._hexToRgb(hex);
        pdf.setDrawColor(200); pdf.setFillColor(rgb.r, rgb.g, rgb.b);
        pdf.rect(sx, sy, sw, sh, 'FD');

        pdf.setTextColor(60); pdf.setFontSize(8);
        const nome = this.nomeDaCor(hex);
        const parts = this._wrapName(nome);
        pdf.text(parts[0], sx, sy + sh + 12);
        if(parts[1]) pdf.text(parts[1], sx, sy + sh + 22);

        sx += sw + gap;
      });

      pdf.setFontSize(7); pdf.setTextColor(110);
      pdf.text(window.location.origin + window.location.pathname, margin, ph - 10);
      pdf.text('1/1', pw - margin, ph - 10, {align:'right'});

      pdf.save('ladrilho.pdf');
    },

    _wrapName(nome){
      const words = String(nome||'').split(' ');
      if(words.length <= 1) return [nome, ''];
      const mid = Math.ceil(words.length/2);
      return [words.slice(0, mid).join(' '), words.slice(mid).join(' ')];
    },

    _hexToRgb(hex){
      let h = (hex||'').replace('#','');
      if(h.length===3) h = h.split('').map(c=>c+c).join('');
      const r = parseInt(h.slice(0,2),16);
      const g = parseInt(h.slice(2,4),16);
      const b = parseInt(h.slice(4,6),16);
      return {r,g,b};
    },

    _loadJsPDF(){
      return new Promise((resolve, reject) => {
        if (window.jspdf && window.jspdf.jsPDF) return resolve(window.jspdf.jsPDF);
        const s = document.createElement('script');
        s.src = 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js';
        s.onload = () => resolve(window.jspdf.jsPDF);
        s.onerror = () => reject(new Error('Falha ao carregar jsPDF'));
        document.head.appendChild(s);
      }).then(() => window.jspdf.jsPDF);
    },

    /* ---- Simulação 3D (CSS) ---- */
    // agora assíncrono: gera a textura com rejunte antes de abrir o modal
    async openSimulacao(id){
      const r = this.rooms.find(x => x.id === id);
      if(!r) return;
      if(!this.tileURL) this.gerarTileURL();

      this.sim.tileSize = r.tileSize ?? 90;
      this.sim.offsetY  = r.offsetY  ?? 0;
      this.sim.overlay  = r.overlay  || '';
      this.sim.floorMask = r.floorMask || '';
      this.sim.perspective = !!r.perspective;
      this.sim.maxWidth = r.maxWidth ?? 900;
      this.sim.maxHeight = r.maxHeight ?? 700;

      this.simTextureURL = '';
      try { await this.gerarSimTexture(); } catch(e){}

      this.sim.open = true;
    },


    // estilo do plano do piso (div) — PROFUNDIDADE AQUI
    // usa a textura com rejunte e ajusta backgroundSize (tile + 2*g)
   floorStyle(){
    const size = this.sim.tileSize || 90;
    const g    = this.groutPx3D();
    const tex  = this.simTextureURL || this.tileURL;
    const mask = this.sim.floorMask || '';

    const style = {
      width: '100%',
      height: '100%',
      backgroundImage: `url('${tex}')`,
      backgroundRepeat: 'repeat',
      backgroundSize: `${size + g*2}px ${size + g*2}px`,
      backgroundPosition: this.sim.perspective ? 'center bottom' : 'center center',
      transform: `translateY(${this.sim.offsetY || 0}px)`,
    };

    // apenas o "piso" tem profundidade
    if (this.sim.perspective) {
      style.transformOrigin = 'center top';
      style.transform = `${style.transform} perspective(1200px) rotateX(48deg)`;
    }

    if (mask) {
      style.maskImage = `url('${mask}')`;
      style.maskRepeat = 'no-repeat';
      style.maskSize = 'contain';
      style.maskPosition = 'center center';
      style['-webkit-mask-image'] = `url('${mask}')`;
      style['-webkit-mask-repeat'] = 'no-repeat';
      style['-webkit-mask-size'] = 'contain';
      style['-webkit-mask-position'] = 'center center';
    }

    return style;
  },
  }
}
</script>
</div>
</section>
@endsection
