<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Mail\NovoCadastroOtica;
use App\Models\Otica;
use App\Models\TabelaPreco;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Throwable;

class AuthController extends Controller
{
    public function show(): View|RedirectResponse
    {
        if (Auth::guard('otica')->check()) {
            return redirect()->route('portal.painel');
        }

        return view('portal.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credenciais = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $lembrar = $request->boolean('lembrar');

        if (Auth::guard('otica')->attempt($credenciais, $lembrar)) {
            $request->session()->regenerate();

            /** @var Otica $otica */
            $otica = Auth::guard('otica')->user();

            if ($otica->isBloqueada()) {
                Auth::guard('otica')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                throw ValidationException::withMessages([
                    'email' => 'Este acesso está bloqueado. Fale com o laboratório.',
                ]);
            }

            return redirect()->intended(route('portal.painel'));
        }

        // Não é uma ótica cadastrada: tenta como usuário do painel administrativo
        // (admin/produção), pra quem é da equipe do laboratório não precisar
        // lembrar que o login deles fica em /admin.
        if (Auth::guard('web')->attempt($credenciais, $lembrar)) {
            $request->session()->regenerate();

            return redirect()->intended('/admin');
        }

        throw ValidationException::withMessages([
            'email' => 'E-mail ou senha incorretos.',
        ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('otica')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('area-oticas');
    }

    public function showRegister(): View
    {
        return view('portal.cadastro');
    }

    public function register(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'nome_fantasia' => ['required', 'string', 'max:255'],
            'razao_social' => ['nullable', 'string', 'max:255'],
            'cnpj' => ['nullable', 'string', 'max:18', 'unique:oticas,cnpj'],
            'email' => ['required', 'email', 'max:255', 'unique:oticas,email'],
            'telefone' => ['nullable', 'string', 'max:30'],
            'endereco' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $tabelaPadrao = TabelaPreco::where('is_default', true)->first() ?? TabelaPreco::first();

        if (! $tabelaPadrao) {
            return back()
                ->withInput()
                ->with('erro', 'Não foi possível concluir o cadastro agora. Tente novamente mais tarde ou fale com o laboratório.');
        }

        $otica = Otica::create([
            'nome_fantasia' => $dados['nome_fantasia'],
            'razao_social' => $dados['razao_social'] ?? null,
            'cnpj' => $dados['cnpj'] ?? null,
            'email' => $dados['email'],
            'password' => Hash::make($dados['password']),
            'telefone' => $dados['telefone'] ?? null,
            'endereco' => $dados['endereco'] ?? null,
            'tabela_preco_id' => $tabelaPadrao->id,
            'status' => Otica::STATUS_PENDENTE,
        ]);

        $destino = config('nova.email_contato');

        if ($destino) {
            try {
                Mail::to($destino)->send(new NovoCadastroOtica($otica));
            } catch (Throwable $e) {
                Log::error('Falha ao avisar o laboratório sobre novo cadastro de ótica: '.$e->getMessage(), [
                    'otica_id' => $otica->id,
                ]);
            }
        }

        return redirect()->route('area-oticas')->with('cadastro_enviado', true);
    }
}
