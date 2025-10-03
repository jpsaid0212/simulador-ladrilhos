<?php
namespace App\Http\Controllers;

// app/Http/Controllers/CatalogoController.php

use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    public function exclusivos()
    {
        // 12 itens mock usando a mesma imagem (você troca depois)
        $items = collect(range(1, 12))->map(function ($i) {
            $slug = 'modelo-'.$i;
            return [
                'name' => "Modelo {$i}",
                'slug' => $slug,
                'href' => route('produto.show', $slug),
                'img'  => asset('simulator/patterns/azulejo1.avif'),
            ];
        });

        return view('shop.category', [
            'title' => 'exclusivos',
            'items' => $items,
        ]);
    }

        public function category(Request $request, string $slug = 'exclusivos')
        {   
            $title = $slug;

            $items = collect(range(1, 12))->map(function ($i) use ($slug) {
                $itemSlug = "{$slug}-{$i}";
                return [
                    'name' => ucfirst($slug)." {$i}",
                    'slug' => $itemSlug,
                    'href' => route('produto.show', $itemSlug), // <- link pronto
                    'img'  => asset('simulator/patterns/azulejo1.avif'),
                ];
            })->all();

            return view('shop.category', compact('title', 'items'));
        }


 public function produto(string $slug)
{
    // derive a categoria se seu slug vier com prefixo (ex.: classicos-24)
    $categoria = 'exclusivos';
    if (str_starts_with($slug, 'classicos-'))    $categoria = 'classicos';
    if (str_starts_with($slug, 'geometricos-'))  $categoria = 'geometricos';

    $produto = [
        'slug'   => $slug,
        'name'   => ucwords(str_replace('-', ' ', $slug)),
        'images' => [
            asset('simulator/patterns/azulejo1.avif'),
            asset('simulator/patterns/azulejo1.avif'),
            asset('simulator/patterns/azulejo1.avif'),
        ],
        'specs'  => [
            'Dimensão'   => '20cm x 20cm / 200mm x 200mm',
            'Espessura'  => '1,5cm a 2cm',
            'Rendimento' => '1 m² = 25 peças',
        ],
        'categoria'      => $categoria,          // usado no breadcrumb
        'categoria_nome' => ucfirst($categoria), // texto do breadcrumb
    ];

    // mocks simples de anterior / próximo
    $prevSlug = $categoria.'-anterior';
    $nextSlug = $categoria.'-proximo';
    $prev = ['href' => route('produto.show', $prevSlug)];
    $next = ['href' => route('produto.show', $nextSlug)];

    return view('shop.product', compact('produto', 'prev', 'next'));
}

}
