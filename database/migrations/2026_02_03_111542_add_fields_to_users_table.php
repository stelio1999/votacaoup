<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Remover colunas padrão que não vamos usar
            if (Schema::hasColumn('users', 'email_verified_at')) {
                $table->dropColumn('email_verified_at');
            }
            
            if (Schema::hasColumn('users', 'remember_token')) {
                $table->dropColumn('remember_token');
            }
            
            // Adicionar novas colunas
            $table->string('role')->default('eleitor')->after('email');
            $table->string('categoria')->nullable()->after('role');
            $table->string('matricula')->nullable()->after('categoria');
            $table->string('curso')->nullable()->after('matricula');
            $table->string('departamento')->nullable()->after('curso');
            $table->string('telefone')->nullable()->after('departamento');
            $table->boolean('ativo')->default(true)->after('telefone');
            $table->timestamp('ultimo_acesso')->nullable()->after('ativo');
            $table->softDeletes();
            
            // Adicionar índices
            $table->index('role');
            $table->index('categoria');
            $table->index('matricula');
            $table->index('ativo');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Remover novas colunas
            $table->dropColumn([
                'role',
                'categoria',
                'matricula',
                'curso',
                'departamento',
                'telefone',
                'ativo',
                'ultimo_acesso',
            ]);
            $table->dropSoftDeletes();
            
            // Restaurar colunas padrão
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            
            // Remover índices
            $table->dropIndex(['role']);
            $table->dropIndex(['categoria']);
            $table->dropIndex(['matricula']);
            $table->dropIndex(['ativo']);
        });
    }
};