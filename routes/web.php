<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\SimulatorController;
use App\Http\Controllers\CatalogoController;

Route::get('/',                  [SiteController::class, 'home'])->name('home');
Route::get('/cores',             [SiteController::class, 'cores'])->name('cores');
Route::get('/sobre',             [SiteController::class, 'sobre'])->name('sobre');
Route::get('/crie-seu-ladrilho', [SiteController::class, 'crie'])->name('crie');
Route::view('/projetos-blocos-3d', 'pages.projetos-blocos3d')->name('blocos3d');
Route::get('/contato',           [SiteController::class, 'contato'])->name('contato');

Route::get('/simulador', [SimulatorController::class, 'index'])->name('simulador.index');

// CATEGORIAS  -> CatalogoController@category
Route::get('/exclusivos',  [CatalogoController::class, 'category'])->defaults('slug','exclusivos')->name('cat.exclusivos');
Route::get('/classicos',   [CatalogoController::class, 'category'])->defaults('slug','classicos')->name('cat.classicos');
Route::get('/geometricos', [CatalogoController::class, 'category'])->defaults('slug','geometricos')->name('cat.geometricos');

// PRODUCT PAGE
Route::get('/produto/{slug}', [CatalogoController::class, 'produto'])->name('produto.show');

// routes/web.php
Route::post('/contato/enviar', [ContactController::class, 'send'])->name('contact.send');
