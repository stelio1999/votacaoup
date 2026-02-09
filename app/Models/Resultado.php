<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resultado extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'eleicao_id',
        'candidato_id',
        'total_votos',
        'percentual',
        'votos_validos',
        'votos_nulos',
        'votos_brancos',
        'eleito',
    ];

    protected $casts = [
        'eleito' => 'boolean',
        'total_votos' => 'integer',
        'percentual' => 'decimal:2',
        'votos_validos' => 'integer',
        'votos_nulos' => 'integer',
        'votos_brancos' => 'integer',
    ];

    // Relacionamentos
    public function eleicao()
    {
        return $this->belongsTo(Eleicao::class);
    }

    public function candidato()
    {
        return $this->belongsTo(Candidato::class);
    }

    // Escopos
    public function scopeEleitos($query)
    {
        return $query->where('eleito', true);
    }

    public function scopePorEleicao($query, $eleicao_id)
    {
        return $query->where('eleicao_id', $eleicao_id);
    }

    public function scopeOrdenados($query)
    {
        return $query->orderByDesc('total_votos')->orderBy('percentual', 'desc');
    }

    // Métodos
    public function calcularPercentual()
    {
        if ($this->votos_validos > 0) {
            return round(($this->total_votos / $this->votos_validos) * 100, 2);
        }
        return 0;
    }

    public function atualizarResultados($totalVotos, $votosValidos, $votosNulos)
    {
        $this->total_votos = $totalVotos;
        $this->votos_validos = $votosValidos;
        $this->votos_nulos = $votosNulos;
        $this->percentual = $this->calcularPercentual();
        $this->save();
    }

    public function verificarEleicao()
    {
        // Verificar se este candidato foi eleito
        // (Implementar lógica específica de eleição)
        return $this->eleito;
    }

    // Atributos calculados
    public function getPercentualFormatadoAttribute()
    {
        return number_format($this->percentual, 2, ',', '.') . '%';
    }

    public function getDiferencaParaPrimeiroAttribute()
    {
        if (!$this->eleicao) return null;
        
        $primeiro = $this->eleicao->resultados()->orderByDesc('total_votos')->first();
        
        if ($primeiro && $primeiro->id !== $this->id) {
            return $primeiro->total_votos - $this->total_votos;
        }
        
        return 0;
    }

    public function getPosicaoAttribute()
    {
        if (!$this->eleicao) return null;
        
        $resultados = $this->eleicao->resultados()->orderByDesc('total_votos')->get();
        
        foreach ($resultados as $index => $resultado) {
            if ($resultado->id === $this->id) {
                return $index + 1;
            }
        }
        
        return null;
    }

    public function getPosicaoFormatadaAttribute()
    {
        $posicao = $this->posicao;
        
        if ($posicao === 1) return '1º';
        if ($posicao === 2) return '2º';
        if ($posicao === 3) return '3º';
        
        return $posicao . 'º';
    }
}