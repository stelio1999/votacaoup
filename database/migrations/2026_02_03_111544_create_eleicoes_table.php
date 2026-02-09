<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('eleicoes', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('slug')->unique();
            $table->text('descricao')->nullable();
            $table->foreignId('cargo_id')->constrained()->onDelete('cascade');
            $table->dateTime('data_inicio');
            $table->dateTime('data_fim');
            $table->enum('status', ['agendada', 'ativa', 'concluida', 'cancelada', 'suspensa'])->default('agendada');
            $table->integer('total_eleitores')->default(0);
            $table->integer('votos_registrados')->default(0);
            $table->integer('votos_validos')->default(0);
            $table->integer('votos_nulos')->default(0);
            $table->integer('votos_brancos')->default(0);
            $table->decimal('percentual_conclusao', 5, 2)->default(0);
            $table->decimal('percentual_participacao', 5, 2)->default(0);
            $table->text('observacoes')->nullable();
            $table->text('regras')->nullable();
            $table->boolean('resultado_publico')->default(true);
            $table->boolean('voto_anonimo')->default(true);
            $table->boolean('permite_voto_branco')->default(true);
            $table->boolean('permite_voto_nulo')->default(true);
            $table->integer('duracao_votacao_horas')->nullable();
            $table->integer('limite_tentativas')->default(3);
            $table->json('configuracoes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index('slug');
            $table->index('cargo_id');
            $table->index('status');
            $table->index('data_inicio');
            $table->index('data_fim');
            $table->index('resultado_publico');
            
            // Índice para garantir unicidade de eleição ativa por cargo
            $table->unique(['cargo_id', 'status'])->where('status', 'ativa');
        });
    }

    public function down()
    {
        Schema::dropIfExists('eleicoes');
    }
};