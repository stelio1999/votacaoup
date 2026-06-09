<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('eleicoes', function (Blueprint $table) {
        $table->dropUnique('eleicoes_cargo_id_status_unique');
    });
}

public function down()
{
    Schema::table('eleicoes', function (Blueprint $table) {
        $table->unique(['cargo_id', 'status']); // opcional, só se quiser restaurar
    });
}

};
