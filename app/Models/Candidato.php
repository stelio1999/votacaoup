<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Candidato extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'user_id',
        'eleicao_id',
        'cargo_id',
        'numero_candidato',
        'proposta',
        'foto',
        'aprovado',
        'motivo_reprovacao',
    ];

    protected $casts = [
        'aprovado' => 'boolean',
    ];

    protected $appends = ['foto_url'];

    // Relacionamentos
    public function user()
    {
        return $this->belongsTo(User::class);
    }

public function resultado()
{
    return $this->hasOne(Resultado::class);
}

    public function eleicao()
    {
        return $this->belongsTo(Eleicao::class);
    }

    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
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
    public function scopeAprovados($query)
    {
        return $query->where('aprovado', true);
    }

    public function scopePendentes($query)
    {
        return $query->where('aprovado', false);
    }

    public function scopePorEleicao($query, $eleicao_id)
    {
        return $query->where('eleicao_id', $eleicao_id);
    }

    public function scopePorCategoria($query, $categoria)
    {
        return $query->whereHas('user', function($q) use ($categoria) {
            $q->where('categoria', $categoria);
        });
    }

    // Métodos
    public function getTotalVotosAttribute()
    {
        return $this->votos()->count();
    }

    public function getPercentualVotos($totalVotosValidos)
    {
        if ($totalVotosValidos > 0) {
            return round(($this->total_votos / $totalVotosValidos) * 100, 2);
        }
        return 0;
    }

    public function podeSerEditado()
    {
        return $this->eleicao->status === 'agendada';
    }

    public function podeSerExcluido()
    {
        return $this->eleicao->status === 'agendada' && $this->votos()->count() === 0;
    }

    // Atributos calculados
    public function getFotoUrlAttribute()
    {
        if ($this->foto) {
            return \Storage::url($this->foto);
        }
        return null;
    }

    public function getIniciaisAttribute()
    {
        $nomes = explode(' ', $this->user->name);
        $iniciais = '';
        
        foreach ($nomes as $nome) {
            if (!empty($nome)) {
                $iniciais .= strtoupper($nome[0]);
                if (strlen($iniciais) >= 2) break;
            }
        }
        
        return $iniciais;
    }

    public function getStatusFormatadoAttribute()
    {
        if ($this->aprovado) {
            return ['text' => 'Aprovado', 'class' => 'success'];
        } else {
            if ($this->motivo_reprovacao) {
                return ['text' => 'Reprovado', 'class' => 'danger'];
            } else {
                return ['text' => 'Pendente', 'class' => 'warning'];
            }
        }
    }
}