<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cargo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nome',
        'descricao',
        'categoria',
        'mandato_meses',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'mandato_meses' => 'integer',
    ];

    // Relacionamentos
    public function eleicoes()
    {
        return $this->hasMany(Eleicao::class);
    }

    public function candidatos()
    {
        return $this->hasManyThrough(Candidato::class, Eleicao::class);
    }

    // Escopos
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    public function scopePorCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    // Métodos
    public function getEleicoesAtivasAttribute()
    {
        return $this->eleicoes()->ativas()->count();
    }

    public function getTotalCandidatosAttribute()
    {
        return $this->candidatos()->count();
    }

    public function podeSerExcluido()
    {
        return $this->eleicoes()->count() === 0;
    }

    // Atributos calculados
    public function getNomeFormatadoAttribute()
    {
        return ucfirst(strtolower($this->nome));
    }

    public function getCategoriaFormatadaAttribute()
    {
        return match($this->categoria) {
            'estudante' => 'Estudante',
            'docente' => 'Docente',
            'tecnico_administrativo' => 'Técnico Administrativo',
            default => $this->categoria,
        };
    }
}