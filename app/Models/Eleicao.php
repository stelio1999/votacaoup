<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Eleicao extends Model
{
    use HasFactory, SoftDeletes;
 protected $table = 'eleicoes';
    protected $fillable = [
        'titulo',
        'descricao',
        'cargo_id',
        'data_inicio',
        'data_fim',
        'status',
        'total_eleitores',
        'votos_registrados',
        'percentual_conclusao',
        'observacoes',
    ];

    protected $casts = [
        'data_inicio' => 'datetime',
        'data_fim' => 'datetime',
    ];

    // Relacionamentos
    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }

    public function candidatos()
    {
        return $this->hasMany(Candidato::class);
    }

    public function votos()
    {
        return $this->hasMany(Voto::class);
    }

    public function resultados()
    {
        return $this->hasMany(Resultado::class);
    }

    // Escopos
    public function scopeAtivas($query)
    {
        return $query->where('status', 'ativa')
                    ->where('data_inicio', '<=', now())
                    ->where('data_fim', '>=', now());
    }

    public function scopeConcluidas($query)
    {
        return $query->where('status', 'concluida');
    }

    public function scopeAgendadas($query)
    {
        return $query->where('status', 'agendada')
                    ->where('data_inicio', '>', now());
    }

    // Métodos
    public function atualizarEstatisticas()
    {
        $this->votos_registrados = $this->votos()->count();
        $this->percentual_conclusao = $this->total_eleitores > 0 
            ? round(($this->votos_registrados / $this->total_eleitores) * 100, 2)
            : 0;
        $this->save();
    }

    public function verificarEncerramento()
    {
        if ($this->status === 'ativa' && $this->data_fim->isPast()) {
            $this->status = 'concluida';
            $this->save();
            $this->calcularResultados();
        }
    }

    public function calcularResultados()
    {
         if ($this->resultados()->exists()) {
        return;
    }
        // Calcular votos por candidato
        $candidatos = $this->candidatos()->withCount(['votos as total_votos'])->get();
        $totalVotosValidos = $this->votos()->where('valido', true)->count();
        
        foreach ($candidatos as $candidato) {
            $percentual = $totalVotosValidos > 0 
                ? round(($candidato->total_votos / $totalVotosValidos) * 100, 2)
                : 0;

            Resultado::updateOrCreate(
                [
                    'eleicao_id' => $this->id,
                    'candidato_id' => $candidato->id,
                ],
                [
                    'total_votos' => $candidato->total_votos,
                    'percentual' => $percentual,
                    'votos_validos' => $totalVotosValidos,
                    'votos_nulos' => $this->votos()->where('valido', false)->count(),
                    'votos_brancos' => 0, // Implementar se necessário
                    'eleito' => false, // Implementar lógica de eleição
                ]
            );
        }

        // Determinar vencedor se houver
        $vencedor = $candidatos->sortByDesc('total_votos')->first();
        if ($vencedor && $vencedor->total_votos > 0) {
            Resultado::where('eleicao_id', $this->id)
                     ->where('candidato_id', $vencedor->id)
                     ->update(['eleito' => true]);
        }
    }

    // Atributos calculados
    public function getTempoRestanteAttribute()
    {
        if ($this->status !== 'ativa') {
            return null;
        }

        return $this->data_fim->diffForHumans();
    }

    public function getEstaAtivaAttribute()
    {
        return $this->status === 'ativa' && 
               $this->data_inicio <= now() && 
               $this->data_fim >= now();
    }
   protected static function booted()
{
    static::creating(function ($eleicao) {
        $eleicao->slug = Str::slug($eleicao->titulo) . '-' . time();
    });

    static::deleting(function ($eleicao) {

        // se usar SoftDelete e quiser apagar filhos também
        $eleicao->votos()->delete();
        $eleicao->candidatos()->delete();
        $eleicao->resultados()->delete();

    });
}


}