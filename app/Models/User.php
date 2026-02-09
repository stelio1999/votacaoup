<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'categoria',
        'matricula',
        'curso',
        'departamento',
        'telefone',
        'ativo',
        'ultimo_acesso',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'ultimo_acesso' => 'datetime',
                'preferencias' => 'array', // Adicionar esta linha

    ];

    // Método para verificar o papel do usuário
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    // Relacionamentos
    public function votos()
    {
        return $this->hasMany(Voto::class);
    }

    public function candidaturas()
    {
        return $this->hasMany(Candidato::class);
    }

    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    public function categoriaRelacionada()
    {
        return $this->belongsTo(Categoria::class, 'categoria', 'nome');
    }

    // Método para verificar se o usuário já votou em uma eleição
    public function jaVotou($eleicao_id)
    {
        return $this->votos()->where('eleicao_id', $eleicao_id)->exists();
    }

    // Método para verificar permissões
    public function canVote($eleicao)
    {
        // Verificar se o usuário está ativo
        if (!$this->ativo) {
            return false;
        }

        // Verificar se a categoria do usuário corresponde à eleição
        if ($eleicao->cargo && $eleicao->cargo->categoria !== $this->categoria) {
            return false;
        }

        // Verificar se já votou
        if ($this->jaVotou($eleicao->id)) {
            return false;
        }

        return true;
    }

    // Método para registrar acesso
    public function registrarAcesso()
    {
        $this->update(['ultimo_acesso' => now()]);
    }

    // Atributos calculados
    public function getIniciaisAttribute()
    {
        $nomes = explode(' ', $this->name);
        $iniciais = '';
        
        foreach ($nomes as $nome) {
            if (!empty($nome)) {
                $iniciais .= strtoupper($nome[0]);
                if (strlen($iniciais) >= 2) break;
            }
        }
        
        return $iniciais;
    }

    public function getRoleFormatadoAttribute()
    {
        return match($this->role) {
            'admin' => 'Administrador',
            'comissao' => 'Comissão Eleitoral',
            'eleitor' => 'Eleitor',
            default => ucfirst($this->role),
        };
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

    public function getStatusFormatadoAttribute()
    {
        return $this->ativo ? 'Ativo' : 'Inativo';
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

    public function scopePorRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeComMatricula($query, $matricula)
    {
        return $query->where('matricula', $matricula);
    }
}