<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voto extends Model
{
    use HasFactory;

    protected $fillable = [
        'eleicao_id',
        'candidato_id',
        'user_id',
        'hash_voto',
        'ip_address',
        'user_agent',
        'valido',
    ];

    protected $casts = [
        'valido' => 'boolean',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Escopos
    public function scopeValidos($query)
    {
        return $query->where('valido', true);
    }

    public function scopeNulos($query)
    {
        return $query->where('valido', false);
    }

    public function scopePorEleicao($query, $eleicao_id)
    {
        return $query->where('eleicao_id', $eleicao_id);
    }

    public function scopePorPeriodo($query, $inicio, $fim)
    {
        return $query->whereBetween('created_at', [$inicio, $fim]);
    }

    // Métodos
    public function gerarHash()
    {
        // Gerar hash único baseado em dados do voto
        $dados = $this->eleicao_id . $this->candidato_id . $this->user_id . microtime(true) . random_bytes(16);
        return hash('sha256', $dados);
    }

    public function verificarValidade()
    {
        // Verificar se o voto ainda é válido
        // (Implementar lógica de validação específica)
        return $this->valido;
    }

    // Atributos calculados
    public function getDataFormatadaAttribute()
    {
        return $this->created_at->format('d/m/Y H:i:s');
    }

    public function getHoraAttribute()
    {
        return $this->created_at->format('H:i');
    }

    public function getDiaAttribute()
    {
        return $this->created_at->format('d/m/Y');
    }

    // Eventos
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($voto) {
            if (empty($voto->hash_voto)) {
                $voto->hash_voto = $voto->gerarHash();
            }
        });

        static::created(function ($voto) {
            // Atualizar estatísticas da eleição
            $voto->eleicao->atualizarEstatisticas();
        });

        static::deleted(function ($voto) {
            // Atualizar estatísticas da eleição
            $voto->eleicao->atualizarEstatisticas();
        });
    }
}