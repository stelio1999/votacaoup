<?php

namespace App\Http\Controllers;

use App\Models\Eleicao;
use App\Models\Candidato;
use App\Models\Voto;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class VotacaoController extends Controller
{
    public function index()
    {
        // Obter eleições ativas para o usuário atual
        $eleicoes = Eleicao::ativas()
                          ->with('cargo')
                          ->whereHas('cargo', function($query) {
                              $query->where('categoria', auth()->user()->categoria);
                          })
                          ->whereDoesntHave('votos', function($query) {
                              $query->where('user_id', auth()->id());
                          })
                          ->get();

        // Eleições em que já votou
        $eleicoesVotadas = Eleicao::whereHas('votos', function($query) {
                                    $query->where('user_id', auth()->id());
                                })
                                ->with('cargo')
                                ->get();

        // Próximas eleições agendadas
        $eleicoesAgendadas = Eleicao::agendadas()
                                   ->with('cargo')
                                   ->whereHas('cargo', function($query) {
                                       $query->where('categoria', auth()->user()->categoria);
                                   })
                                   ->get();

        return view('votacao.index', compact('eleicoes', 'eleicoesVotadas', 'eleicoesAgendadas'));
    }

    public function show(Eleicao $eleicao)
    {
        // Verificar se o usuário pode votar nesta eleição
        if (!$this->podeVotar($eleicao)) {
            return redirect()->route('votacao.index')
                           ->with('error', 'Não está autorizado a votar nesta eleição.');
        }

        $candidatos = $eleicao->candidatos()
                             ->with('user')
                             ->where('aprovado', true)
                             ->get();

        return view('votacao.show', compact('eleicao', 'candidatos'));
    }

    public function votar(Request $request, Eleicao $eleicao)
    {
        // Verificar se o usuário pode votar
        if (!$this->podeVotar($eleicao)) {
            return redirect()->route('votacao.index')
                           ->with('error', 'Não está autorizado a votar nesta eleição.');
        }

        // Validar o voto
        $validated = $request->validate([
            'candidato_id' => 'required|exists:candidatos,id',
            'confirmacao' => 'required|accepted',
        ]);

        // Verificar se o candidato pertence à eleição
        $candidato = Candidato::find($validated['candidato_id']);
        if ($candidato->eleicao_id !== $eleicao->id) {
            return back()->with('error', 'Candidato inválido para esta eleição.');
        }

        // Gerar hash único para o voto
        $hashVoto = $this->gerarHashVoto();

        // Registrar o voto
        $voto = Voto::create([
            'eleicao_id' => $eleicao->id,
            'candidato_id' => $candidato->id,
            'user_id' => auth()->id(),
            'hash_voto' => $hashVoto,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Atualizar estatísticas da eleição
        $eleicao->atualizarEstatisticas();

        // Registrar log
        Log::create([
            'user_id' => auth()->id(),
            'acao' => 'registrar_voto',
            'descricao' => "Registrou voto na eleição: {$eleicao->titulo}",
            'ip_address' => $request->ip(),
            'dados_alterados' => [
                'eleicao_id' => $eleicao->id,
                'candidato_id' => $candidato->id,
                'hash_voto' => $hashVoto,
            ],
        ]);

        // Enviar email de confirmação (opcional)
        // $this->enviarEmailConfirmacao($voto);

        return redirect()->route('votacao.comprovante', $voto)
                       ->with('success', 'Voto registrado com sucesso!');
    }

    public function comprovante(Voto $voto)
    {
        // Verificar se o voto pertence ao usuário atual
        if ($voto->user_id !== auth()->id()) {
            abort(403, 'Acesso não autorizado.');
        }

        return view('votacao.comprovante', compact('voto'));
    }

    public function historico()
    {
        $votos = auth()->user()->votos()
                              ->with(['eleicao.cargo', 'candidato.user'])
                              ->latest()
                              ->paginate(10);

        return view('votacao.historico', compact('votos'));
    }

    private function podeVotar(Eleicao $eleicao)
    {
        $user = auth()->user();

        // Verificar se o usuário está ativo
        if (!$user->ativo) {
            return false;
        }

        // Verificar se a categoria do usuário corresponde à eleição
        if ($eleicao->cargo->categoria !== $user->categoria) {
            return false;
        }

        // Verificar se já votou nesta eleição
        if ($user->jaVotou($eleicao->id)) {
            return false;
        }

        // Verificar se a eleição está ativa
        if (!$eleicao->estaAtiva) {
            return false;
        }

        // Verificar se há candidatos aprovados
        if ($eleicao->candidatos()->where('aprovado', true)->count() < 2) {
            return false;
        }

        return true;
    }

    private function gerarHashVoto()
    {
        return hash('sha256', 
            auth()->id() . 
            time() . 
            Str::random(40) . 
            microtime(true)
        );
    }

    private function enviarEmailConfirmacao($voto)
    {
        // Implementação de envio de email
        // Mail::to($voto->user->email)->send(new VotoConfirmado($voto));
    }
}