<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'acao',
            'modulo', // <- adicionado

        'descricao',
        'ip_address',
        'user_agent',
        'dados_alterados',
    ];

    protected $casts = [
        'dados_alterados' => 'array',
    ];

    // Relacionamentos
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Escopos
    public function scopePorUsuario($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }

    public function scopePorAcao($query, $acao)
    {
        return $query->where('acao', $acao);
    }

    public function scopePorPeriodo($query, $inicio, $fim)
    {
        return $query->whereBetween('created_at', [$inicio, $fim]);
    }

    public function scopeRecentes($query, $limite = 100)
    {
        return $query->latest()->limit($limite);
    }

    // Métodos
    public static function registrar($acao, $descricao, $user_id = null, $dados = null)
    {
        $request = request();
        
        return self::create([
            'user_id' => $user_id ?? auth()->id(),
            'acao' => $acao,
                    'modulo' => $modulo, // <- valor padrão

            'descricao' => $descricao,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'dados_alterados' => $dados,
        ]);
    }

    public function getAcaoFormatadaAttribute()
    {
        $acoes = [
            'login' => ['icon' => 'sign-in-alt', 'color' => 'success'],
            'logout' => ['icon' => 'sign-out-alt', 'color' => 'secondary'],
            'criar_usuario' => ['icon' => 'user-plus', 'color' => 'primary'],
            'atualizar_usuario' => ['icon' => 'user-edit', 'color' => 'warning'],
            'excluir_usuario' => ['icon' => 'user-times', 'color' => 'danger'],
            'criar_eleicao' => ['icon' => 'vote-yea', 'color' => 'primary'],
            'atualizar_eleicao' => ['icon' => 'edit', 'color' => 'warning'],
            'excluir_eleicao' => ['icon' => 'trash', 'color' => 'danger'],
            'registrar_voto' => ['icon' => 'check-circle', 'color' => 'success'],
            'aprovar_candidato' => ['icon' => 'check', 'color' => 'success'],
            'reprovar_candidato' => ['icon' => 'times', 'color' => 'danger'],
        ];

        return $acoes[$this->acao] ?? ['icon' => 'info-circle', 'color' => 'info'];
    }

    public function getDataFormatadaAttribute()
    {
        return $this->created_at->format('d/m/Y H:i:s');
    }

    public function getDiferencaTempoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    // Eventos
    protected static function boot()
    {
        parent::boot();

        // Limpar logs antigos automaticamente (mantém apenas 90 dias)
        static::created(function ($log) {
            self::where('created_at', '<', now()->subDays(90))->delete();
        });
    }
}