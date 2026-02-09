<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Eleicao;
use Carbon\Carbon;

class VerificarEleicoes extends Command
{
    protected $signature = 'eleicoes:verificar';
    protected $description = 'Verificar e atualizar status das eleições';

    public function handle()
    {
        $this->info('Verificando eleições...');
        
        // Ativar eleições agendadas que iniciaram
        Eleicao::where('status', 'agendada')
               ->where('data_inicio', '<=', Carbon::now())
               ->update(['status' => 'ativa']);
        
        // Encerrar eleições ativas que terminaram
        $eleicoes = Eleicao::where('status', 'ativa')
                          ->where('data_fim', '<=', Carbon::now())
                          ->get();
        
        foreach ($eleicoes as $eleicao) {
            $eleicao->status = 'concluida';
            $eleicao->save();
            $eleicao->calcularResultados();
            $this->info("Eleição '{$eleicao->titulo}' encerrada e resultados calculados.");
        }
        
        $this->info('Verificação concluída.');
        
        return 0;
    }
}