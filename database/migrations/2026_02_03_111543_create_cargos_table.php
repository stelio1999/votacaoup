<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cargos', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();
            $table->text('descricao')->nullable();
            $table->string('categoria'); // estudante, docente, tecnico_administrativo
            $table->integer('mandato_meses')->default(24);
            $table->text('responsabilidades')->nullable();
            $table->text('requisitos')->nullable();
            $table->text('beneficios')->nullable();
            $table->boolean('ativo')->default(true);
            $table->integer('ordem')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index('categoria');
            $table->index('ativo');
            $table->index('ordem');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cargos');
    }
};