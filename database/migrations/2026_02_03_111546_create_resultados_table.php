<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('resultados', function (Blueprint $table) {
            $table->id();
$table->foreignId('eleicao_id')->constrained('eleicoes')->onDelete('cascade');
            $table->foreignId('candidato_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('tipo_resultado')->default('candidato'); // candidato, nulo, branco
            $table->integer('total_votos')->default(0);
            $table->decimal('percentual', 5, 2)->default(0);
            $table->integer('votos_validos')->default(0);
            $table->integer('votos_nulos')->default(0);
            $table->integer('votos_brancos')->default(0);
            $table->boolean('eleito')->default(false);
            $table->integer('posicao')->nullable();
            $table->json('estatisticas')->nullable();
            $table->json('distribuicao_temporal')->nullable();
            $table->json('distribuicao_geografica')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index('eleicao_id');
            $table->index('candidato_id');
            $table->index('tipo_resultado');
            $table->index('eleito');
            $table->index('posicao');
            $table->index('percentual');
            
            // Índice único para evitar duplicidade
            $table->unique(['eleicao_id', 'candidato_id'])->where('tipo_resultado', 'candidato');
            $table->unique(['eleicao_id', 'tipo_resultado'])->whereIn('tipo_resultado', ['nulo', 'branco']);
            
            // Índice para ordenação
            $table->index(['eleicao_id', 'posicao']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('resultados');
    }
};