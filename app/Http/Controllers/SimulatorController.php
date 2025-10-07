<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SimulatorController extends Controller
{
    public function index()
    {
        // Paleta inicial simples e alguns exemplos de texturas (adicione seus arquivos em /public/simulator/patterns)
        $swatches = ['#ffffff','#f5f5f5','#e5e7eb','#d1d5db','#94a3b8','#0f172a','#b45309','#c2410c','#047857','#0ea5e9','#f43f5e'];
        $examples = [
            asset('simulator/patterns/azulejo1.jpg'),
            asset('simulator/patterns/azulejo2.jpg'),
            asset('simulator/patterns/azulejo3.jpg'),
        ];

        return view('simulator.index', compact('swatches', 'examples'));
    }

    public function ambiente()
    {
        return view('simulator.ambiente');
    }
}
