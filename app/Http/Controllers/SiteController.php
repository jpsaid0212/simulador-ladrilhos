<?php
namespace App\Http\Controllers;

// app/Http/Controllers/SiteController.php

use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function home()
    {
        // se quiser, mostre a home normal aqui
        return view('pages.home'); // ajuste para a sua home
    }

    public function category(Request $request, string $slug = 'exclusivos')
    {
        $title = $slug;

        // Por enquanto, gera 12 produtos com a mesma imagem
        $items = collect(range(1, 12))->map(function ($i) use ($slug) {
            return [
                'name' => ucfirst($slug).' '.$i,
                'href' => '#',
                'img'  => asset('simulator/patterns/azulejo1.avif'),
            ];
        })->all();

        return view('shop.category', compact('title', 'items'));
    }

    public function cores()     { return view('pages.cores'); }
    public function sobre()     { return view('pages.sobre'); }
    public function crie()      { return view('pages.crie'); }
    public function projetos()  { return view('pages.projetos'); }
    public function blocos3d()  { return view('pages.blocos3d'); }
    public function contato()   { return view('pages.contato'); }
}
