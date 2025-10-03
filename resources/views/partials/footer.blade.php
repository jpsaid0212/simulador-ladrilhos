<footer class="relative mt-16 overflow-hidden">
  {{-- BG com imagem + véu branco translúcido --}}
  <div class="absolute inset-0 -z-10">
    <img src="{{ asset('imgs/footer-bg.jpg') }}" alt="" class="w-full h-full object-cover">
    <div class="absolute inset-0 bg-white/70"></div>
  </div>

  <div class="max-w-6xl mx-auto px-4 md:px-6 lg:px-8 py-10">
    <div class="grid md:grid-cols-2 gap-10 items-start">
      {{-- ESQ: instagram --}}
      <div>
        <p class="text-slate-500 text-sm mb-1">siga nosso instagram:</p>
        <div class="flex items-center gap-2">
          <a href="https://www.instagram.com/studio.latitude.ladrilho" target="_blank" rel="noopener"
             class="text-sky-600 underline underline-offset-4">@studio.latitude.ladrilho</a>

          {{-- ícone IG (usa Font Awesome; se não tiver, troque por um PNG/SVG) --}}
          <img src="{{ asset('imgs/ig-badge.svg') }}" class="h-7 w-7">
            <i class="fa-brands fa-instagram text-slate-600"></i>
          </span>
        </div>
      </div>

      {{-- DIR: orçamento / e-mail / razão / endereço --}}
      <div class="md:text-right space-y-1">
        <p class="text-slate-500">
          <a href="https://wa.me/5511934563752" target="_blank" rel="noopener"
             class="text-sky-600 underline underline-offset-4">orçamento</a>
          direto via whatsapp chat
          <a href="https://wa.me/5511934563752" target="_blank" rel="noopener"
             class="ml-1 font-semibold text-slate-900 underline underline-offset-4">+55 11 9.3456-3752</a>
        </p>

        <p class="text-slate-500">
          ou e-mail —
          <a href="mailto:ladrilho@studiolatitude.page" class="text-sky-600 underline underline-offset-4">
            ladrilho@studiolatitude.page
          </a>
        </p>

        <p class="text-slate-500 mt-3">
          STUDIO LATITUDE LADRILHO (CEMENT TILE) CNPJ 35.708.275/0001-69
        </p>

        <p>
          <a
            href="https://www.google.com/maps?q=-23.54733,-46.64267"
            target="_blank" rel="noopener"
            class="text-sky-600 underline underline-offset-4">
            Avenida São Luis, 192 - República / São Paulo - BRASIL / 01046-000
          </a>
        </p>
      </div>
    </div>
  </div>
</footer>
