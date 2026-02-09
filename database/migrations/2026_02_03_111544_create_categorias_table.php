<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();
            $table->string('slug')->unique();
            $table->text('descricao')->nullable();
            $table->string('cor')->default('#6c757d');
            $table->string('icone')->default('fas fa-user');
            $table->integer('ordem')->default(0);
            $table->boolean('ativo')->default(true);
            $table->json('configuracoes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index('slug');
            $table->index('ativo');
            $table->index('ordem');
        });
        
        // Inserir categorias padrão
        $this->seedCategorias();
    }

    public function down()
    {
        Schema::dropIfExists('categorias');
    }
    
    private function seedCategorias()
    {
        // Este método será executado após a criação da tabela
        DB::table('categorias')->insert([
            [
                'nome' => 'estudante',
                'slug' => 'estudante',
                'descricao' => 'Estudantes da Universidade Pedagógica',
                'cor' => '#28a745',
                'icone' => 'fas fa-user-graduate',
                'ordem' => 1,
                'ativo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'docente',
                'slug' => 'docente',
                'descricao' => 'Corpo docente da Universidade Pedagógica',
                'cor' => '#007bff',
                'icone' => 'fas fa-chalkboard-teacher',
                'ordem' => 2,
                'ativo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'tecnico_administrativo',
                'slug' => 'tecnico-administrativo',
                'descricao' => 'Técnicos e funcionários administrativos',
                'cor' => '#6c757d',
                'icone' => 'fas fa-user-tie',
                'ordem' => 3,
                'ativo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
};