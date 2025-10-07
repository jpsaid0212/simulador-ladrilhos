@extends('layouts.site')

@section('title', 'Studio Latitude — Simulador de Ladrilhos')

@section('content')
<section class="max-w-6xl mx-auto px-4 py-12">
<div x-data="simuladorDP()" x-init="init()">

  <div class="bg-orange-50 border border-orange-100 rounded-xl px-4 py-3 text-sm text-slate-700">
    <p class="font-semibold mb-1">Monte seu Ladrilho Personalizado:</p>
    <ol class="list-decimal ml-5 space-y-1">
      <li>Escolha o que deseja simular.</li>
      <li>Clique na peça desejada.</li>
      <li>Clique na paleta de cores e depois vá clicando na peça grande.</li>
      <li>Clique em <b>Atualizar Tapete</b> para ver o tapete montado.</li>
      <li>Para baixar clique em <b>Baixar imagem</b> do ladrilho ou do tapete.</li>
    </ol>
  </div>

  <h2 class="text-2xl font-semibold text-center mt-8 mb-4">O QUE DESEJA SIMULAR?</h2>
  <div class="flex justify-center">
    <select x-model="modo" class="border rounded-md px-3 py-2">
      <option value="ladrilho">Simular só Ladrilho, sem Moldura</option>
    </select>
  </div>

  <div class="mt-10">
    <p class="text-center text-slate-600 mb-3">1 - Clique no Tipo de Ladrilho</p>
    <div class="flex flex-wrap gap-2 justify-center">
      <template x-for="cat in categorias" :key="cat">
        <button @click="categoriaAtiva = cat"
                class="px-3 py-1.5 rounded-md border"
                :class="categoriaAtiva===cat ? 'border-rose-400 text-rose-600 bg-rose-50' : 'border-slate-300 text-slate-700 bg-white' "
                x-text="cat"></button>
      </template>
    </div>
  </div>

  <div class="mt-8">
    <p class="text-center text-slate-600 mb-2">2 - Clique na miniatura, use a rolagem</p>
    <div class="overflow-x-auto">
      <div class="flex gap-3 min-w-max px-2">
        <template x-for="tpl in templatesFiltrados()" :key="tpl.id">
          <button class="border rounded-md overflow-hidden hover:shadow"
                  :class="tile.id===tpl.id ? 'ring-2 ring-rose-500' : 'border-slate-300'"
                  @click="selecionarTemplate(tpl)">
            <img :src="gerarThumb(tpl)" alt="" class="h-20 w-20 object-cover">
          </button>
        </template>
      </div>
    </div>
  </div>

  <div class="mt-6">
    <p class="text-center text-slate-600 mb-3">3 - Clique nas cores e depois nas áreas do ladrilho abaixo</p>

    <div class="mx-auto max-w-5xl">
      <div class="flex flex-wrap gap-2 justify-center border-t pt-4">
        <template x-for="hex in paletaLinha1" :key="'l1'+hex">
          <button class="h-9 w-9 rounded-md border" :style="`background:${hex}`"
                  :class="corSelecionada===hex?'ring-2 ring-slate-900':''"
                  @click="corSelecionada = hex" :title="hex"></button>
        </template>
      </div>
      <div class="flex flex-wrap gap-2 justify-center border-t pt-4 mt-4">
        <template x-for="hex in paletaLinha2" :key="'l2'+hex">
          <button class="h-9 w-9 rounded-md border" :style="`background:${hex}`"
                  :class="corSelecionada===hex?'ring-2 ring-slate-900':''"
                  @click="corSelecionada = hex" :title="hex"></button>
        </template>
      </div>
    </div>
  </div>

  <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div class="flex flex-col items-center">
      <div class="relative bg-white border rounded-xl p-3">

        <!-- NOVO: modo edição por imagem (canvas) -->
        <template x-if="tile.type==='raster'">
          <div class="flex flex-col items-center">
            <canvas id="editorCanvas"
                    class="w-[320px] h-[320px] sm:w-[380px] sm:h-[380px] border border-slate-200 rounded"
                    @click="onCanvasClick($event)"></canvas>
            <div class="text-xs text-slate-500 mt-2">Clique na cor e depois em uma área do ladrilho para pintar</div>
          </div>
        </template>

        <!-- Modelos SVG (você pode remover depois se quiser ficar só com imagem) -->
        <template x-if="tile.type==='svg' && tile.id==='flor1'">
          <svg viewBox="0 0 100 100" class="w-[320px] h-[320px] sm:w-[380px] sm:h-[380px]">
            <rect x="0" y="0" width="100" height="100"
                  :fill="cores.bg"
                  @click="pintar('bg')"></rect>
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

      <div class="mt-3 flex items-center gap-3">
        <button @click="baixarLadrilho()" class="px-3 py-2 border rounded-md">Baixar esta Imagem</button>

        <template x-if="tile.type==='raster'">
          <button @click="resetRaster()" class="px-3 py-2 border rounded-md">Resetar ladrilho</button>
        </template>

        <span class="text-sm text-slate-500">Clique na cor e depois nas áreas do ladrilho</span>
      </div>
    </div>

    <div>
      <div class="mb-4">
        <label class="block text-sm text-slate-600 mb-1">Selecione a Cor do Rejunte</label>
        <select x-model="groutColor" class="border rounded-md px-3 py-2">
          <template x-for="opt in rejunteOpcoes" :key="opt.hex">
            <option :value="opt.hex" x-text="opt.nome"></option>
          </template>
        </select>
      </div>

      <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
          <label class="block text-sm text-slate-600 mb-1">Colunas</label>
          <input type="number" min="3" max="20" x-model.number="cols" class="w-full border rounded-md px-2 py-1.5">
        </div>
        <div>
          <label class="block text-sm text-slate-600 mb-1">Linhas</label>
          <input type="number" min="3" max="20" x-model.number="rows" class="w-full border rounded-md px-2 py-1.5">
        </div>
      </div>

      <button @click="atualizarTapete()"
              class="w-full bg-rose-600 hover:bg-rose-700 text-white font-medium rounded-lg py-3">
        ⟳ Clique - Atualizar Tapete
      </button>
    </div>
  </div>

  <div class="mt-10 flex flex-col items-center">
    <div class="rounded-xl overflow-auto border bg-white p-3">
      <div :style="estiloTapete()" class="mx-auto" id="tapete">
        <template x-for="i in rows*cols" :key="i">
          <div :style="estiloPeca()"></div>
        </template>
      </div>
    </div>

    <button @click="baixarTapete()" class="mt-3 px-4 py-2 border rounded-md">Baixar Tapete (PNG)</button>
  </div>

  <canvas id="canvasLadrilho" class="hidden"></canvas>
  <canvas id="canvasTapete" class="hidden"></canvas>

<script>
function simuladorDP(){
  return {
    modo: 'ladrilho',
    categorias: ['Centrais','Geométricos','Florões'],
    categoriaAtiva: 'Centrais',

    // NOVO: adiciona seu azulejo .avif como template "raster"
    templates: [
      { id:'img_az1', type:'raster', nome:'Azulejo 1', categoria:'Centrais', src: "{{ asset('simulator/patterns/azulejo1.avif') }}" },
      // modelos SVG (exemplos)
      { id:'geo1',  type:'svg', nome:'Geométrico 01', categoria:'Geométricos', coresDefault:{ bg:'#e6e6e6', p1:'#6b7280', p2:'#94a3b8', miolo:'#1f2937', borda:'#374151' } },
      { id:'flor1', type:'svg', nome:'Flor 01',       categoria:'Florões',    coresDefault:{ bg:'#e6e6e6', p1:'#8ab79b', p2:'#5a8d75', miolo:'#2f5d50', borda:'#2f3e46' } },
    ],

    tile: {},
    cores: {},
    corSelecionada: '#2b2b2b',

    groutColor: '#cfd8e3',
    rejunteOpcoes: [
      {nome:'Azul Grizzo Claro', hex:'#cfd8e3'},
      {nome:'Cinza Claro', hex:'#e5e7eb'},
      {nome:'Cinza Médio', hex:'#9ca3af'},
      {nome:'Areia', hex:'#d9c8a8'},
      {nome:'Preto', hex:'#000000'}
    ],

    rows: 8,
    cols: 8,

    paletaLinha1: ['#ffffff','#fbf8ef','#f3e7c9','#f2c94c','#e7a33d','#c05621','#7f1d1d','#5b2a27','#3f6212','#6b8e23','#4b5563','#bfdbfe','#93c5fd','#60a5fa','#3b82f6','#1e40af','#0b2e4f'],
    paletaLinha2: ['#0f172a','#111827','#e2e8f0','#d1d5db','#9ca3af','#6b7280','#f5d0fe','#fca5a5','#f59e0b','#d97706','#c4b5fd','#a7f3d0','#34d399','#14b8a6','#0ea5e9','#0284c7','#0369a1'],

    // estado do editor de imagem (raster)
    editor: { canvas: null, ctx: null, img: null, original: null, scaleX:1, scaleY:1, tol: 28 },

    tileURL: '',

    init(){
      this.selecionarTemplate(this.templates[0]) // começa no seu azulejo
      this.atualizarTapete()
    },

    templatesFiltrados(){
      return this.templates.filter(t => t.categoria === this.categoriaAtiva)
    },

    selecionarTemplate(tpl){
      this.tile = tpl

      if (tpl.type === 'raster') {
        this.iniciarEditorRaster(tpl.src)
      } else {
        this.cores = { ...tpl.coresDefault }
        this.gerarTileURL() // svg
      }
    },

    gerarThumb(tpl){
      if (tpl.type === 'raster') return tpl.src
      const c = tpl.coresDefault
      const svg = tpl.id==='flor1'
        ? `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><rect width="100" height="100" fill="${c.bg}"/><ellipse cx="50" cy="50" rx="34" ry="18" fill="${c.p1}"/><ellipse cx="50" cy="50" rx="34" ry="18" transform="rotate(90 50 50)" fill="${c.p2}"/><circle cx="50" cy="50" r="10" fill="${c.miolo}"/><rect x="4" y="4" width="92" height="92" fill="none" stroke="${c.borda}" stroke-width="2.5"/></svg>`
        : `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><rect width="100" height="100" fill="${c.bg}"/><rect x="20" y="20" width="60" height="60" fill="${c.p1}"/><rect x="10" y="10" width="80" height="80" transform="rotate(45 50 50)" fill="${c.p2}"/><rect x="36" y="36" width="28" height="28" fill="${c.miolo}"/><rect x="4" y="4" width="92" height="92" fill="none" stroke="${c.borda}" stroke-width="2.5"/></svg>`
      return 'data:image/svg+xml;utf8,' + encodeURIComponent(svg)
    },

    // ===== SVG =====
    pintar(campo){
      this.cores[campo] = this.corSelecionada
      this.gerarTileURL()
    },

    svgString(){
      if(this.tile.id==='flor1'){
        return `
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
          <rect x="0" y="0" width="100" height="100" fill="${this.cores.bg}"/>
          <ellipse cx="50" cy="50" rx="34" ry="18" fill="${this.cores.p1}"/>
          <ellipse cx="50" cy="50" rx="34" ry="18" transform="rotate(90 50 50)" fill="${this.cores.p2}"/>
          <circle cx="50" cy="50" r="10" fill="${this.cores.miolo}"/>
          <rect x="4" y="4" width="92" height="92" fill="none" stroke="${this.cores.borda}" stroke-width="2.5"/>
        </svg>`
      }
      return `
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
        <rect x="0" y="0" width="100" height="100" fill="${this.cores.bg}"/>
        <rect x="20" y="20" width="60" height="60" fill="${this.cores.p1}"/>
        <rect x="10" y="10" width="80" height="80" transform="rotate(45 50 50)" fill="${this.cores.p2}"/>
        <rect x="36" y="36" width="28" height="28" fill="${this.cores.miolo}"/>
        <rect x="4" y="4" width="92" height="92" fill="none" stroke="${this.cores.borda}" stroke-width="2.5"/>
      </svg>`
    },

    gerarTileURL(){
      if (this.tile.type === 'svg') {
        const svg = this.svgString()
        this.tileURL = 'data:image/svg+xml;utf8,' + encodeURIComponent(svg)
      } else if (this.tile.type === 'raster' && this.editor.canvas) {
        this.tileURL = this.editor.canvas.toDataURL('image/png')
      }
    },

    // ===== RASTER (edição por clique) =====
    iniciarEditorRaster(src){
      // configura canvas
      this.editor.canvas = document.getElementById('editorCanvas')
      this.editor.ctx = this.editor.canvas.getContext('2d')

      // resolução interna alta para export/qualidade
      const targetW = 600, targetH = 600
      this.editor.canvas.width = targetW
      this.editor.canvas.height = targetH

      // carrega imagem
      const img = new Image()
      img.crossOrigin = 'anonymous'
      img.onload = () => {
        this.editor.img = img
        // desenha centralizado (assume tile quadrado)
        this.editor.ctx.clearRect(0,0,targetW,targetH)
        this.editor.ctx.drawImage(img, 0, 0, targetW, targetH)
        // guarda original para reset
        this.editor.original = this.editor.ctx.getImageData(0,0,targetW,targetH)
        this.gerarTileURL()
      }
      img.src = src
    },

    resetRaster(){
      if (!this.editor.original) return
      this.editor.ctx.putImageData(this.editor.original, 0, 0)
      this.gerarTileURL()
    },

    onCanvasClick(ev){
      if (this.tile.type !== 'raster') return
      const rect = ev.target.getBoundingClientRect()
      // converte coordenada CSS -> pixels reais do canvas (600x600)
      const x = Math.floor((ev.clientX - rect.left) * (this.editor.canvas.width / rect.width))
      const y = Math.floor((ev.clientY - rect.top) * (this.editor.canvas.height / rect.height))
      this.floodFill(x, y, this.hexToRgba(this.corSelecionada))
      this.gerarTileURL()
    },

    floodFill(x, y, newColor){
      const { ctx, canvas, tol } = this.editor
      const imgData = ctx.getImageData(0,0,canvas.width,canvas.height)
      const data = imgData.data

      const idx = (x, y) => (y * canvas.width + x) * 4
      const target = data.slice(idx(x,y), idx(x,y)+4)
      const sameColor = (i) => data[i]===newColor[0] && data[i+1]===newColor[1] && data[i+2]===newColor[2] && data[i+3]===255
      const withinTol = (i) => {
        return Math.abs(data[i]-target[0]) <= tol &&
               Math.abs(data[i+1]-target[1]) <= tol &&
               Math.abs(data[i+2]-target[2]) <= tol &&
               Math.abs(data[i+3]-target[3]) <= 40
      }

      if (sameColor(idx(x,y))) return

      const stack = [[x,y]]
      const W = canvas.width, H = canvas.height
      while (stack.length) {
        const [cx, cy] = stack.pop()
        let i = idx(cx, cy)
        if (!withinTol(i)) continue

        // pinta pixel
        data[i]   = newColor[0]
        data[i+1] = newColor[1]
        data[i+2] = newColor[2]
        data[i+3] = 255

        // vizinhos 4-conexos
        if (cx > 0)       stack.push([cx-1, cy])
        if (cx < W-1)     stack.push([cx+1, cy])
        if (cy > 0)       stack.push([cx, cy-1])
        if (cy < H-1)     stack.push([cx, cy+1])
      }
      ctx.putImageData(imgData, 0, 0)
    },

    hexToRgba(hex){
      let h = hex.replace('#','')
      if (h.length===3) h = h.split('').map(c=>c+c).join('')
      const r = parseInt(h.substr(0,2),16)
      const g = parseInt(h.substr(2,2),16)
      const b = parseInt(h.substr(4,2),16)
      return [r,g,b,255]
    },

    // ===== TAPETE =====
    estiloTapete(){
      const size = 90
      return {
        display: 'grid',
        gridTemplateColumns: `repeat(${this.cols}, ${size}px)`,
        gridAutoRows: `${size}px`,
        gap: '6px',
        background: this.groutColor,
        padding: '6px',
        width: 'fit-content'
      }
    },

    estiloPeca(){
      return {
        width: '90px',
        height: '90px',
        backgroundImage: `url('${this.tileURL}')`,
        backgroundSize: 'cover',
        backgroundPosition: 'center',
        borderRadius: '2px'
      }
    },

    atualizarTapete(){
      if(!this.tileURL) this.gerarTileURL()
    },

    baixarLadrilho(){
      // usa a imagem atual (svg ou canvas)
      const url = (this.tile.type==='svg')
        ? ('data:image/svg+xml;utf8,' + encodeURIComponent(this.svgString()))
        : this.editor.canvas.toDataURL('image/png')

      const a = document.createElement('a')
      a.href = url
      a.download = 'ladrilho.png'
      a.click()
    },

    baixarTapete(){
      const size = 90, gap = 6
      const totalW = this.cols*size + (this.cols+1)*gap
      const totalH = this.rows*size + (this.rows+1)*gap

      const canvas = document.getElementById('canvasTapete')
      const ctx = canvas.getContext('2d')
      canvas.width = totalW
      canvas.height = totalH

      ctx.fillStyle = this.groutColor
      ctx.fillRect(0,0,totalW,totalH)

      const img = new Image()
      img.onload = () => {
        for(let r=0;r<this.rows;r++){
          for(let c=0;c<this.cols;c++){
            const x = gap + c*(size+gap)
            const y = gap + r*(size+gap)
            ctx.drawImage(img, x, y, size, size)
          }
        }
        const url = canvas.toDataURL('image/png')
        const a = document.createElement('a')
        a.href = url
        a.download = 'tapete.png'
        a.click()
      }
      img.src = this.tileURL
    },
  }
}
</script>
</div>
</section>
@endsection
