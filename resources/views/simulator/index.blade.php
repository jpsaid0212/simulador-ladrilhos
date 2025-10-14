@extends('layouts.site')

@section('title', 'Studio Latitude — Simulador de Ladrilhos')

@section('content')
<section class="max-w-6xl mx-auto px-4 py-12">
<div x-data="simuladorDP()" x-init="init()">

  <!-- aviso / instruções -->
  <div class="bg-orange-50 border border-orange-100 rounded-xl px-4 py-3 text-sm text-slate-700">
    <p class="font-semibold mb-1">crie seu ladrilho personalizado:</p>
      <ol class="list-decimal ml-5 space-y-1">
        <li>escolha o modelo na lista abaixo</li>
        <li>escolha as cores na nossa paleta de cores</li>
        <li>clique primeiro na cor e em seguida na parte do ladrilho que deseja pintar</li>
        <li>clique no botão salvar pdf</li>
        <li>nos envie o arquivo via whatsapp para orçamento e prazo de fabricação</li>
      </ol>
  </div>

  <!-- SELECTOR -->
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

  <div class="mt-4">
    <p class="text-center text-slate-600 text-sm">2 — Depois de escolher o modelo, selecione as cores ao lado e clique nas áreas do ladrilho.</p>
  </div>

  <!-- GRID: esquerda preview / direita paleta -->
  <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- ESQUERDA: preview -->
    <div class="flex flex-col items-center">
      <div class="relative bg-white border rounded-xl p-3">
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
              @click="baixarLadrilhoPDF()"
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

    <!-- DIREITA: paleta com nomes -->
    <div>
      <p class="text-slate-600 mb-2 text-sm">3 - Selecione as cores</p>

      <div class="rounded-xl border border-slate-200 bg-white p-4">
        <div class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-5 gap-x-6 gap-y-6">
          <template x-for="cor in coresLadrilar" :key="cor.nome">
            <button class="group flex flex-col items-center text-center"
                    @click="corSelecionada = cor.hex"
                    :title="cor.nome">
              <span class="block h-12 w-12 rounded-md border border-slate-300 shadow-sm"
                    :class="corSelecionada===cor.hex ? 'ring-2 ring-slate-900' : ''"
                    :style="`background:${cor.hex}`"></span>
              <span class="mt-1.5 text-[11px] text-slate-600 group-hover:text-slate-800 truncate w-20" x-text="cor.nome"></span>
            </button>
          </template>
        </div>
      </div>
    </div>
  </div>

  <!-- TAPETE -->
  <div class="mt-12 lg:grid lg:grid-cols-[minmax(0,1fr)_340px] lg:gap-10">
    <div class="flex justify-center">
      <div class="border-2 border-slate-300 p-2 rounded-sm bg-white shadow-sm">
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
        <div class="grid grid-cols-2 gap-4">
          <label class="text-sm text-slate-700 flex items-center gap-2">
            <span>Colunas:</span>
            <input type="number" class="w-16 border rounded-md px-2 py-1.5 bg-slate-50 text-slate-700 cursor-default" :value="rows" readonly aria-readonly="true" onwheel="this.blur()" />
          </label>
          <label class="text-sm text-slate-700 flex items-center gap-2">
            <span>Linhas:</span>
            <input type="number" class="w-16 border rounded-md px-2 py-1.5 bg-slate-50 text-slate-700 cursor-default" :value="cols" readonly aria-readonly="true" onwheel="this.blur()" />
          </label>
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
       class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-3"
       @keydown.escape.window="sim.open=false"
       @click.self="sim.open=false">

    <div class="relative bg-white rounded-md shadow-xl p-2 md:p-4 w-[88vw] max-w-[960px]">
      <button @click="sim.open=false"
              class="absolute -top-3 -right-3 bg-white/90 rounded-full border px-2 py-1 text-[12px] shadow">
        ✕
      </button>

      <div class="w-full flex justify-center">
        <!-- wrapper -->
        <div id="simWrap" class="relative inline-block max-w-full">
          <!-- piso (div com padrão + efeito 3D) -->
          <div id="simFloor"
               class="absolute inset-0 z-0 will-change-transform"
               :style="floorStyle()"></div>

          <!-- imagem por cima (PNG com piso transparente) -->
          <img id="sim3dBase"
               :src="sim.photo"
               alt=""
               class="block max-w-full w-auto max-h-[70vh] h-auto select-none relative z-10">
        </div>
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
      { id:'geo1',  type:'svg', nome:'Geométrico 01', categoria:'Geométricos', coresDefault:{ bg:'#e6e6e6', p1:'#6b7280', p2:'#94a3b8', miolo:'#1f2937', borda:'#374151' } },
      { id:'flor1', type:'svg', nome:'Flor 01',       categoria:'Florões',    coresDefault:{ bg:'#e6e6e6', p1:'#8ab79b', p2:'#5a8d75', miolo:'#2f5d50', borda:'#2f3e46' } },
    ],

    tile: {},
    cores: {},
    corSelecionada: '#2b2b2b',

    groutColor: '#cfd8e3',
    rows: 6,
    cols: 5,

    tapeteTileSize: 120,

    // === PALETA OFICIAL (Studio Latitude) ===
    coresLadrilar: [
      // linha 1
      { nome:'BRANCO',        hex:'#F7F7F4' },
      { nome:'CINZA CLARO',   hex:'#DDDCD2' },
      { nome:'CINZA',         hex:'#B4B1AC' },
      { nome:'PRATA',         hex:'#9FA7AD' },
      { nome:'PRETO',         hex:'#2F2F2F' },

      // linha 2
      { nome:'OFFWHITE',      hex:'#F4F2EA' },
      { nome:'CRAFT CLARO',   hex:'#EFE0CF' },
      { nome:'CRAFT',         hex:'#CDA87B' },
      { nome:'CRAFT ESC',     hex:'#B08F64' },
      { nome:'AMÊNDOA',       hex:'#CDA06D' },
      { nome:'MARROM',        hex:'#6F4D4D' },

      // linha 3
      { nome:'BEGE',          hex:'#EEDFC4' },
      { nome:'AMARELO CL',    hex:'#F8DE78' },
      { nome:'AMARELO',       hex:'#F4C232' },
      { nome:'MOSTARDA',      hex:'#E4AE54' },
      { nome:'VERDE CANA',    hex:'#B9AA54' },

      // linha 4
      { nome:'TERRACOTA CL',  hex:'#EDA382' },
      { nome:'TERRACOTA',     hex:'#DF825F' },
      { nome:'TERRACOTA ESC', hex:'#CF735A' },
      { nome:'VERM VIVO',     hex:'#A6423C' },
      { nome:'VERM ESC',      hex:'#8F332C' },

      // linha 5
      { nome:'ROSA CL',       hex:'#F1C7BF' },
      { nome:'ROSA',          hex:'#F3B6B9' },
      { nome:'GOIABA',        hex:'#E06A5E' },

      // linha 6
      { nome:'VERDE CL',      hex:'#B4CBB6' },
      { nome:'VERDE',         hex:'#6A834F' },
      { nome:'JAGUAR',        hex:'#2F7F5E' },
      { nome:'VERDE ESC',     hex:'#3F5242' },

      // linha 7
      { nome:'AZ GRIZZO',     hex:'#D8DADD' },
      { nome:'AZ GRIZZO CL',  hex:'#CFD7D9' },
      { nome:'TIFFANY CL',    hex:'#AEEBEA' },
      { nome:'TIFFANY',       hex:'#75E0E6' },
      { nome:'OCEAN BLUE',    hex:'#167C8A' },

      // linha 8
      { nome:'AZUL CL',       hex:'#A9C1DA' },
      { nome:'COBALTO',       hex:'#3F92D2' },
      { nome:'PORTO',         hex:'#6A85B3' },
      { nome:'AZUL ULTRAMAR', hex:'#3C5AA6' },
      { nome:'AZUL ESC',      hex:'#4B5F7F' },
      { nome:'AZUL ESC 2',    hex:'#3B4C63' },
    ],


    // editor raster
    editor: { canvas: null, ctx: null, img: null, original: null, scaleX:1, scaleY:1, tol: 28 },

    tileURL: '',
    usedColorsRaster: [],

    /* --------- SIMULAÇÃO 3D (CSS 3D) --------- */
    rooms: [
      { id:'sala',     nome:'Sala',            photo:"{{ asset('simulator/rooms/sala_overlay.png') }}",     tileSize:90, angle:52, persp:900, offsetY:0 },
      { id:'banheiro', nome:'Banheiro',        photo:"{{ asset('simulator/rooms/banheiro_overlay.png') }}", tileSize:88, angle:57, persp:900, offsetY:-4 },
      { id:'cozinha',  nome:'Cozinha',         photo:"{{ asset('simulator/rooms/cozinha_overlay.png') }}",  tileSize:92, angle:54, persp:900, offsetY:-6 },
      { id:'quarto',   nome:'Parede / Quarto', photo:"{{ asset('simulator/rooms/quarto_overlay.png') }}",   tileSize:86, angle:-55, persp:900, offsetY:0 },
    ],
    sim: { open:false, photo:'', tileSize:90, angle:55, persp:900, offsetY:0 },

    // NOVO: textura (dataURL) com ladrilho + rejunte para o 3D
    simTextureURL: '',

    /* --------- init --------- */
    init(){
      this.selecionarTemplate(this.templates[0]);
      this.atualizarTapete();

      // ---> TRAVA DURA: torna rows/cols constantes (ignoram x-model)
      Object.defineProperty(this, 'rows', {
        configurable: true,
        get(){ return 5 },
        set(_){ /* ignorado */ }
      });
      Object.defineProperty(this, 'cols', {
        configurable: true,
        get(){ return 6 },
        set(_){ /* ignorado */ }
      });

      // fixa grade
      this.rows = 5;
      this.cols = 6;

      // impede alterações pelos inputs (Alpine v3)
      this.$watch('rows', v => { if (v !== 5) this.rows = 5; });
      this.$watch('cols', v => { if (v !== 6) this.cols = 6; });

      window.addEventListener('resize', () => {});
    },

    // NOVO: espessura do rejunte (px CSS) proporcional ao tamanho do ladrilho do ambiente
    groutPx3D(){
      const t = this.sim.tileSize || 90;
      const hairline = t * 0.004;
      return Math.max(0.5, hairline);
    },

    // NOVO: gera textura (tile + borda de rejunte) e guarda em simTextureURL
    async gerarSimTexture(){
      if(!this.tileURL) this.gerarTileURL();

      const baseTilePx = 256; // tamanho do tile dentro da textura
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
        this.gerarTileURL();
      };
      img.src = src;
    },

    resetRaster(){
      if (!this.editor.original) return;
      this.editor.ctx.putImageData(this.editor.original, 0, 0);
      this.usedColorsRaster = [];
      this.gerarTileURL();
    },

    onCanvasClick(ev){
      if (this.tile.type !== 'raster') return;
      const rect = ev.target.getBoundingClientRect();
      const x = Math.floor((ev.clientX - rect.left) * (this.editor.canvas.width / rect.width));
      const y = Math.floor((ev.clientY - rect.top) * (this.editor.canvas.height / rect.height));
      this.floodFill(x, y, this.hexToRgba(this.corSelecionada));
      this.addUsedColor(this.corSelecionada);
      this.gerarTileURL();
      if(this.sim.open){ this.gerarSimTexture() } // reflete no 3D se aberto
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
      const gap = 2;
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
        borderRadius: '2px'
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
      return m ? m.nome : h;
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

      this.sim.photo    = r.photo;
      this.sim.tileSize = r.tileSize;
      this.sim.angle    = r.angle ?? 55;
      this.sim.persp    = r.persp ?? 900;
      this.sim.offsetY  = r.offsetY ?? 0;

      this.simTextureURL = '';
      try { await this.gerarSimTexture(); } catch(e){ /* fallback silencioso */ }

      this.sim.open = true;
    },

    // estilo do plano do piso (div) — PROFUNDIDADE AQUI
    // usa a textura com rejunte e ajusta backgroundSize (tile + 2*g)
    floorStyle(){
      const size = this.sim.tileSize || 90;
      const g    = this.groutPx3D();
      const tex  = this.simTextureURL || this.tileURL;

      return {
        width: '100%',
        height: '100%',
        backgroundImage: `url('${tex}')`,
        backgroundRepeat: 'repeat',
        backgroundSize: `${size + g*2}px ${size + g*2}px`,
        backgroundPosition: 'center bottom',
        transformOrigin: 'bottom center',
        transform: `perspective(${this.sim.persp}px) rotateX(${this.sim.angle}deg) translateY(${this.sim.offsetY}px)`,
        filter: 'brightness(0.98) contrast(1.03)',
      };
    },
  }
}
</script>
</div>
</section>
@endsection
