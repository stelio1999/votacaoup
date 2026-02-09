<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'cor',
        'icone',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    // Relacionamentos
    public function users()
    {
        return $this->hasMany(User::class, 'categoria', 'nome');
    }

    public function cargos()
    {
        return $this->hasMany(Cargo::class, 'categoria', 'nome');
    }

    // Escopos
    public function scopeAtivas($query)
    {
        return $query->where('ativo', true);
    }

    public function scopePorNome($query, $nome)
    {
        return $query->where('nome', $nome);
    }

    // Métodos
    public function getTotalUsuariosAttribute()
    {
        return User::where('categoria', $this->nome)->count();
    }

    public function getTotalCargosAttribute()
    {
        return Cargo::where('categoria', $this->nome)->count();
    }

    public function getEleicoesAtivasAttribute()
    {
        return Eleicao::whereHas('cargo', function($query) {
            $query->where('categoria', $this->nome);
        })->ativas()->count();
    }

    // Atributos calculados
    public function getNomeFormatadoAttribute()
    {
        return match($this->nome) {
            'estudante' => 'Estudante',
            'docente' => 'Docente',
            'tecnico_administrativo' => 'Técnico Administrativo',
            default => ucfirst($this->nome),
        };
    }

    public function getCorPadraoAttribute()
    {
        if ($this->cor) {
            return $this->cor;
        }

        return match($this->nome) {
            'estudante' => '#28a745', // verde
            'docente' => '#007bff', // azul
            'tecnico_administrativo' => '#6c757d', // cinza
            default => '#6c757d',
        };
    }

    public function getIconePadraoAttribute()
    {
        if ($this->icone) {
            return $this->icone;
        }

        return match($this->nome) {
            'estudante' => 'fas fa-user-graduate',
            'docente' => 'fas fa-chalkboard-teacher',
            'tecnico_administrativo' => 'fas fa-user-tie',
            default => 'fas fa-user',
        };
    }
}