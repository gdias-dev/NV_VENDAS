<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Se o middleware "auth:otica" barrar uma requisição sem login,
        // manda para a tela de login do portal (em vez do /login padrão do
        // Laravel, que não existe neste projeto).
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, \Illuminate\Http\Request $request) {
            if (in_array('otica', $e->guards(), true) && ! $request->expectsJson()) {
                return redirect()->route('area-oticas');
            }
        });
    })->create();
