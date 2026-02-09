<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('acao');
            $table->string('modulo');
            $table->text('descricao');
            $table->string('ip_address');
            $table->string('user_agent')->nullable();
            $table->string('metodo')->nullable();
            $table->string('url')->nullable();
            $table->json('dados_anteriores')->nullable();
            $table->json('dados_novos')->nullable();
            $table->json('dados_alterados')->nullable();
            $table->string('severidade')->default('info'); // info, warning, error, critical
            $table->string('tipo')->default('sistema'); // sistema, auditoria, segurança, aplicacao
            $table->timestamps();
            
            // Índices
            $table->index('user_id');
            $table->index('acao');
            $table->index('modulo');
            $table->index('severidade');
            $table->index('tipo');
            $table->index('created_at');
            
            // Índices para consultas frequentes
            $table->index(['user_id', 'created_at']);
            $table->index(['modulo', 'acao', 'created_at']);
            
            // Índice para logs de segurança
            $table->index(['tipo', 'severidade', 'created_at'])->where('tipo', 'segurança');
        });
    }

    public function down()
    {
        Schema::dropIfExists('logs');
    }
};