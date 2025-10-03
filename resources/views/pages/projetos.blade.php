@extends('layouts.site')
@section('title','Studio Latitude — projetos / blocos 3D')

@section('content')
<section class="max-w-6xl mx-auto px-4 py-10 space-y-6">
  <h1 class="text-2xl md:text-3xl font-semibold">projetos / blocos 3D</h1>
  <p class="text-slate-700 max-w-3xl">
    Baixe os blocos 3D para SketchUp com paginações e catálogo.
  </p>
  <a class="inline-block px-4 py-2 rounded bg-slate-900 text-white" href="{{ route('blocos3d') }}">baixe os blocos 3D</a>
</section>
@endsection
