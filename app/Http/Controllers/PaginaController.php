<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class PaginaController extends Controller
{
    public function home(): View
    {
        return view('home');
    }

    public function lentes(): View
    {
        return view('lentes');
    }

    public function comoPedir(): View
    {
        return view('como-pedir');
    }

    public function sobre(): View
    {
        return view('sobre');
    }
}
