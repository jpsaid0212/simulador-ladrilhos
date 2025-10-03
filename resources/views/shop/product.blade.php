{{-- resources/views/shop/product.blade.php --}}
@extends('layouts.site')
@section('title', 'Studio Latitude — ' . $produto['name'])

@section('content')
    <section class="max-w-6xl mx-auto px-4 py-10">
        {{-- BARRA DE NAVEGAÇÃO: breadcrumb + anterior/próximo --}}
        @php
            // categoria do produto (precisa existir no $produto)
            $categoriaSlug = $produto['categoria'] ?? 'exclusivos';
            $categoriaNome = $produto['categoria_nome'] ?? ucfirst($categoriaSlug);

            // rotas das categorias (ajuste se seus names forem outros)
            $categoriaRoute = match ($categoriaSlug) {
                'exclusivos' => route('cat.exclusivos'),
                'classicos' => route('cat.classicos'),
                'geometricos' => route('cat.geometricos'),
                default => url('/'),
            };

            // fallbacks: se não vierem $prev/$next do controller
            $prevHref = $prev['href'] ?? null ?: $categoriaRoute;
            $nextHref = $next['href'] ?? null ?: $categoriaRoute;
        @endphp

        <div class="mb-6 flex items-center justify-between text-[12px] text-neutral-500">
            {{-- breadcrumb --}}
            <nav aria-label="breadcrumb" class="space-x-1">
                <a href="{{ route('home') }}" class="hover:underline">Início</a>
                <span>/</span>
                <a href="{{ $categoriaRoute }}" class="hover:underline">{{ $categoriaNome }}</a>
                <span>/</span>
                <span class="text-neutral-400">{{ $produto['name'] }}</span>
            </nav>

            {{-- anterior | próximo --}}
            <nav aria-label="navegação entre produtos" class="flex items-center gap-4">
                <a href="{{ $prevHref }}" class="flex items-center gap-1 hover:underline">
                    <span class="text-neutral-400">&lsaquo;</span><span>Anterior</span>
                </a>
                <span class="text-neutral-300">|</span>
                <a href="{{ $nextHref }}" class="flex items-center gap-1 hover:underline">
                    <span>Próximo</span><span class="text-neutral-400">&rsaquo;</span>
                </a>
            </nav>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-7">
                <div class="grid grid-cols-2 grid-rows-2 aspect-square bg-white border border-neutral-300 overflow-hidden">
                    @for ($i = 0; $i < 4; $i++)
                        <div class="relative">
                            <img id="preview-img-{{ $i }}" src="{{ $produto['images'][0] }}"
                                class="w-full h-full object-cover">
                            <span class="pointer-events-none absolute inset-x-0 top-1/2 h-px bg-neutral-300/90"></span>
                            <span class="pointer-events-none absolute inset-y-0 left-1/2 w-px bg-neutral-300/90"></span>
                        </div>
                    @endfor
                </div>
            </div>
            {{-- thumbs + infos (direita) --}}
            <div class="lg:col-span-5">
                <h1 class="text-2xl md:text-3xl font-semibold text-sky-700">{{ $produto['name'] }}</h1>

                {{-- thumbs verticais --}}
                <div class="mt-4 flex gap-3">
                    <div class="flex flex-col gap-2">
                        @foreach ($produto['images'] as $idx => $thumb)
                            <button class="w-12 h-12 border border-neutral-300 hover:border-neutral-500 overflow-hidden"
                                onclick="setPreview('{{ $thumb }}')">
                                <img src="{{ $thumb }}" alt="thumb {{ $idx }}"
                                    class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>

                    {{-- especificações --}}
                    <div class="flex-1">
                        <h2 class="text-sky-700 font-semibold">Especificações</h2>
                        <ul class="mt-2 text-[13px] text-neutral-600 leading-6">
                            @foreach ($produto['specs'] as $k => $v)
                                <li><span class="text-neutral-500">{{ $k }}:</span> {{ $v }}</li>
                            @endforeach
                        </ul>

                        <div class="mt-6 text-[13px] text-neutral-600">
                            <p>Descrição breve do produto. Ajustaremos isso quando os dados reais estiverem prontos.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            function setPreview(src) {
                // troca a imagem dos 4 quadrantes para simular o conjunto
                for (let i = 0; i < 4; i++) {
                    const el = document.getElementById('preview-img-' + i);
                    if (el) el.src = src;
                }
            }
        </script>
    @endpush
@endsection
