@extends('layouts.site')

@section('title', 'Simulador 2')

@section('content')
<section class="max-w-6xl mx-auto px-4 py-12">
    <div class="text-center">
        <h1 class="text-3xl font-bold mb-6 text-gray-700">Simulador 2</h1>
        <div class="bg-gray-100 rounded-lg p-12 shadow-inner">
            <p class="text-xl text-gray-600 mb-4">Esta página está em desenvolvimento</p>
            <p class="text-gray-500">O novo simulador estará disponível em breve!</p>
            <div class="mt-8">
                <a href="{{ url('/') }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded transition">
                    Voltar ao Simulador Principal
                </a>
            </div>
        </div>
    </div>
</section>
@endsection