<?php

use App\Http\Controllers\Admin\PedidoPdfController;
use App\Http\Controllers\Admin\RelatorioFinanceiroController;
use App\Http\Controllers\ContatoController;
use App\Http\Controllers\PaginaController;
use App\Http\Controllers\Portal\AuthController as PortalAuthController;
use App\Http\Controllers\Portal\PainelController as PortalPainelController;
use App\Http\Controllers\Portal\PedidosController as PortalPedidosController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PaginaController::class, 'home'])->name('home');
Route::get('/lentes-e-tratamentos', [PaginaController::class, 'lentes'])->name('lentes');
Route::get('/como-pedir', [PaginaController::class, 'comoPedir'])->name('como-pedir');
Route::get('/sobre', [PaginaController::class, 'sobre'])->name('sobre');

Route::get('/contato', [ContatoController::class, 'formulario'])->name('contato');
Route::post('/contato', [ContatoController::class, 'enviar'])
    ->middleware('throttle:5,1')
    ->name('contato.enviar');

// ---- Portal das óticas (login próprio, guard "otica") ----------------------
Route::get('/area-das-oticas', [PortalAuthController::class, 'show'])->name('area-oticas');
Route::post('/area-das-oticas/entrar', [PortalAuthController::class, 'login'])
    ->middleware('throttle:10,1')
    ->name('portal.login');
Route::post('/area-das-oticas/sair', [PortalAuthController::class, 'logout'])->name('portal.logout');

Route::get('/area-das-oticas/cadastro', [PortalAuthController::class, 'showRegister'])->name('portal.cadastro');
Route::post('/area-das-oticas/cadastro', [PortalAuthController::class, 'register'])
    ->middleware('throttle:5,1')
    ->name('portal.cadastro.enviar');

Route::get('/area-das-oticas/painel', [PortalPainelController::class, 'index'])->name('portal.painel');

Route::get('/area-das-oticas/pedidos/novo', [PortalPedidosController::class, 'novo'])->name('portal.pedidos.novo');
Route::get('/area-das-oticas/pedidos', [PortalPedidosController::class, 'index'])->name('portal.pedidos.index');
Route::get('/area-das-oticas/pedidos/{pedido}', [PortalPedidosController::class, 'show'])->name('portal.pedidos.show');
Route::get('/area-das-oticas/pedidos/{pedido}/pdf', [PortalPedidosController::class, 'pdf'])->name('portal.pedidos.pdf');

// ---- PDF da ordem de serviço, para quem está logado no painel administrativo ----
Route::middleware('auth')
    ->get('/admin/pedidos/{pedido}/pdf', [PedidoPdfController::class, 'show'])
    ->name('admin.pedidos.pdf');

// ---- Relatório financeiro (CSV), para quem está logado no painel administrativo ----
Route::middleware('auth')
    ->get('/admin/relatorios/csv', [RelatorioFinanceiroController::class, 'csv'])
    ->name('admin.relatorios.csv');
