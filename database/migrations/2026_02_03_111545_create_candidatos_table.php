<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('candidatos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('eleicao_id')->constrained('eleicoes')->onDelete('cascade'); // corrigido
            $table->foreignId('cargo_id')->constrained()->onDelete('cascade');
            $table->string('numero_candidato')->unique();
            $table->text('proposta')->nullable();
            $table->text('curriculo')->nullable();
            $table->string('foto')->nullable();
            $table->string('video_url')->nullable();
            $table->string('website')->nullable();
            $table->boolean('aprovado')->default(false);
            $table->text('motivo_reprovacao')->nullable();
            $table->integer('votos_recebidos')->default(0);
            $table->decimal('percentual_votos', 5, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index('numero_candidato');
            $table->index('aprovado');
            $table->index('votos_recebidos');
            
            // Índice único para evitar duplicidade de candidatura
            $table->unique(['user_id', 'eleicao_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('candidatos');
    }
};