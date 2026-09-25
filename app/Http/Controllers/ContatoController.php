<?php

namespace App\Http\Controllers;

use App\Mail\ContatoRecebido;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class ContatoController extends Controller
{
    public function formulario(): View
    {
        return view('contato');
    }

    public function enviar(Request $request): RedirectResponse
    {
        // Campo "website" é uma armadilha para robôs: humanos não o veem nem preenchem.
        if (filled($request->input('website'))) {
            return redirect()->route('contato')->with('sucesso', true);
        }

        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:120'],
            'otica' => ['nullable', 'string', 'max:160'],
            'email' => ['required', 'email', 'max:160'],
            'telefone' => ['nullable', 'string', 'max:30'],
            'assunto' => ['required', 'in:cadastro,duvida,orcamento,outro'],
            'mensagem' => ['required', 'string', 'min:10', 'max:3000'],
        ]);

        $destino = config('nova.email_contato') ?: config('mail.from.address');

        try {
            Mail::to($destino)->send(new ContatoRecebido($dados));
        } catch (Throwable $e) {
            Log::error('Falha ao enviar contato do site: '.$e->getMessage(), ['dados' => $dados]);

            return back()
                ->withInput()
                ->with('erro', 'Não foi possível enviar sua mensagem agora. Tente novamente em instantes ou fale conosco pelo WhatsApp.');
        }

        return redirect()->route('contato')->with('sucesso', true);
    }
}
