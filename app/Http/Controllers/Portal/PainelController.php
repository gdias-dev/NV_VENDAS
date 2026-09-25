<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Otica;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PainelController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        /** @var Otica|null $otica */
        $otica = Auth::guard('otica')->user();

        if (! $otica) {
            return redirect()->route('area-oticas');
        }

        if ($otica->isBloqueada()) {
            Auth::guard('otica')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('area-oticas')
                ->with('erro', 'Este acesso está bloqueado. Fale com o laboratório.');
        }

        return view('portal.painel', ['otica' => $otica->fresh('tabelaPreco')]);
    }
}
