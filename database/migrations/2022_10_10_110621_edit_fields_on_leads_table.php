<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->boolean("possui_cnpj")->default(0)->change();
            $table->string("status")->nullable()->change();
            $table->string("ocupacao")->nullable()->change();
            $table->string("bairro")->nullable()->change();
            $table->boolean("possui_plano")->default(0)->change();
            $table->string("qtd_vidas")->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
