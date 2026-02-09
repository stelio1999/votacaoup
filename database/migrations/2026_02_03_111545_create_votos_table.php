<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('votos', function (Blueprint $table) {
            $table->id();
$table->foreignId('eleicao_id')->constrained('eleicoes')->onDelete('cascade');
            $table->foreignId('candidato_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('hash_voto')->unique();
            $table->string('tipo_voto')->default('valido'); // valido, nulo, branco
            $table->string('ip_address');
            $table->string('user_agent')->nullable();
            $table->string('dispositivo')->nullable();
            $table->string('navegador')->nullable();
            $table->string('sistema_operacional')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('cidade')->nullable();
            $table->string('regiao')->nullable();
            $table->string('pais')->nullable();
            $table->boolean('valido')->default(true);
            $table->text('observacoes')->nullable();
            $table->timestamps();
            
            // Índices
            $table->index('hash_voto');
            $table->index('eleicao_id');
            $table->index('candidato_id');
            $table->index('user_id');
            $table->index('tipo_voto');
            $table->index('valido');
            $table->index('created_at');
            
            // Índice único para evitar votação duplicada
            $table->unique(['eleicao_id', 'user_id']);
            
            // Índice composto para consultas frequentes
            $table->index(['eleicao_id', 'valido', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('votos');
    }
};