{{-- resources/views/contato.blade.php --}}
@extends('layouts.site')
@section('title','Studio Latitude — contato')

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
<section class="max-w-6xl mx-auto px-4 md:px-6 lg:px-8 py-10 futura-text">

  {{-- ===== Topo em 3 colunas ===== --}}
  <div class="grid md:grid-cols-3 gap-12">

    {{-- Coluna 1: WhatsApp + E-mail (empilhados) --}}
    <div class="space-y-10">
      <div>
        <h2 class="text-sky-600 text-sm font-semibold uppercase tracking-wider mb-2">whatsapp - chat</h2>
        <a href="https://wa.me/5511934563752" target="_blank" rel="noopener"
           class="text-slate-900 text-xl underline underline-offset-4 hover:no-underline">
          +55 11 9.3456-3752
        </a>
      </div>

      <div>
        <h2 class="text-sky-600 text-sm font-semibold uppercase tracking-wider mb-2">e-mail</h2>
        <a href="mailto:ladrilho@studiolatitude.page"
           class="text-slate-800 underline underline-offset-4 hover:no-underline">
          ladrilho@studiolatitude.page
        </a>
      </div>
    </div>

    {{-- Coluna 2: Endereço --}}
    <div>
      <h2 class="text-sky-600 text-sm font-semibold uppercase tracking-wider mb-2">endereço showroom</h2>
      <p class="text-slate-800 leading-relaxed">
        avenida são luis, 192 - república<br>
        são paulo - brasil / 01046-000
      </p>
    </div>

    {{-- Coluna 3: Horários --}}
    <div>
      <h2 class="text-sky-600 text-sm font-semibold uppercase tracking-wider mb-2">
        showroom&nbsp;&nbsp;horários
      </h2>

      {{-- linhas com travessão e hora alinhados à direita como no site --}}
      <ul class="text-slate-900 space-y-2">
        @php
          $horarios = [
            ['seg','10:00 - 16:00'],
            ['ter','10:00 - 16:00'],
            ['qua','fechado'],
            ['qui','10:00 - 16:00'],
            ['sex','10:00 - 16:00'],
            ['sab','10:00 - 14:00'],
            ['dom','fechado'],
          ];
        @endphp
        @foreach($horarios as [$dia,$hora])
          <li class="grid grid-cols-[auto_auto_1fr_auto] items-baseline gap-2">
            <span class="min-w-[2.5rem]">{{ $dia }}</span>
            <span>—</span>
            <span></span>
            <span class="justify-self-end">{{ $hora }}</span>
          </li>
        @endforeach
      </ul>
    </div>
  </div>

  {{-- ===== Mapa full width ===== --}}
  <div class="mt-10">
    <div id="map" class="w-full h-[460px] rounded-md overflow-hidden border border-slate-200"></div>
    <p class="text-[11px] text-slate-500 mt-1">
      Atalhos do teclado &nbsp;&nbsp; Dados cartográficos ©2025 Google
    </p>
  </div>
</section>

{{-- ===== Scripts Google Maps ===== --}}
@push('scripts')
<script>
  // Coordenadas da Av. São Luís, 192 — República
  const SLAT = -23.54733, SLNG = -46.64267;

  // Estilo azul semelhante ao site
  const BLUE_STYLE = [
    {elementType:"geometry",stylers:[{color:"#f5f9ff"}]},
    {elementType:"labels.icon",stylers:[{visibility:"off"}]},
    {elementType:"labels.text.fill",stylers:[{color:"#5b7ca6"}]},
    {elementType:"labels.text.stroke",stylers:[{color:"#ffffff"}]},
    {featureType:"road",elementType:"geometry",stylers:[{color:"#b9d8ff"},{weight:0.8}]},
    {featureType:"road",elementType:"geometry.stroke",stylers:[{color:"#8bbcf7"}]},
    {featureType:"road.highway",elementType:"geometry",stylers:[{color:"#9fcbff"}]},
    {featureType:"water",elementType:"geometry",stylers:[{color:"#e3f1ff"}]},
    {featureType:"poi",stylers:[{visibility:"off"}]},
    {featureType:"transit",stylers:[{visibility:"off"}]}
  ];

  function initStudioLatitudeMap(){
    const map = new google.maps.Map(document.getElementById('map'),{
      center:{lat:SLAT,lng:SLNG},
      zoom:12,
      mapTypeControl:true,
      streetViewControl:true,
      fullscreenControl:true,
      styles:BLUE_STYLE
    });

    const marker = new google.maps.Marker({
      position:{lat:SLAT,lng:SLNG},
      map,
      // remova o "icon" se não tiver o PNG
      icon:{ url:"{{ asset('imgs/marker-latitude.png') }}", scaledSize:new google.maps.Size(40,40) },
      title:"STUDIO LATITUDE SHOWROOM"
    });

    const info = new google.maps.InfoWindow({
      content: `
        <div style="min-width:220px">
          <strong>STUDIO LATITUDE SHOWROOM</strong><br>
          <a href="https://www.google.com/maps?q=${SLAT},${SLNG}" target="_blank" rel="noopener">Google Maps Link</a><br>
          <a href="https://www.google.com/maps/dir/?api=1&destination=${SLAT},${SLNG}" target="_blank" rel="noopener">Rotas</a>
        </div>
      `
    });
    info.open({ anchor: marker, map });
    marker.addEventListener('click', () => info.open({ anchor: marker, map }));
  }
  window.initStudioLatitudeMap = initStudioLatitudeMap;
</script>

<script async defer
  src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&callback=initStudioLatitudeMap">
</script>
@endpush
@endsection
