<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Otica;
use App\Models\Pedido;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class PedidosController extends Controller
{
    /**
     * Garante que há uma ótica logada e aprovada; senão redireciona.
     * Reaproveita o mesmo padrão já usado no PainelController.
     */
    protected function oticaAutenticada(Request $request): Otica|RedirectResponse
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

        if ($otica->isPendente()) {
            return redirect()->route('portal.painel');
        }

        return $otica;
    }

    public function novo(Request $request): View|RedirectResponse
    {
        $otica = $this->oticaAutenticada($request);

        if ($otica instanceof RedirectResponse) {
            return $otica;
        }

        return view('portal.pedidos.novo');
    }

    public function index(Request $request): View|RedirectResponse
    {
        $otica = $this->oticaAutenticada($request);

        if ($otica instanceof RedirectResponse) {
            return $otica;
        }

        $pedidos = $otica->pedidos()->latest()->paginate(15);

        return view('portal.pedidos.index', ['pedidos' => $pedidos]);
    }

    public function show(Request $request, Pedido $pedido): View|RedirectResponse
    {
        $otica = $this->oticaAutenticada($request);

        if ($otica instanceof RedirectResponse) {
            return $otica;
        }

        if ($pedido->otica_id !== $otica->id) {
            abort(404);
        }

        return view('portal.pedidos.show', [
            'pedido' => $pedido->load(['lente', 'tratamentos', 'tabelaPreco']),
        ]);
    }

    public function pdf(Request $request, Pedido $pedido): Response|RedirectResponse
    {
        $otica = $this->oticaAutenticada($request);

        if ($otica instanceof RedirectResponse) {
            return $otica;
        }

        if ($pedido->otica_id !== $otica->id) {
            abort(404);
        }

        $pedido->load(['otica', 'lente', 'tratamentos', 'tabelaPreco']);

        return Pdf::loadView('pdf.ordem-servico', ['pedido' => $pedido])
            ->stream('pedido-'.$pedido->id.'.pdf');
    }
}
