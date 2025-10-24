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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string("nome");
            $table->boolean("possui_cnpj");
            $table->string("telefone");
            $table->string("status");
            $table->string("ocupacao");
            $table->string("bairro");
            $table->boolean("possui_plano");
            $table->string("qtd_vidas");
            $table->text("idades")->nullable();
            $table->text("complemento")->nullable();
            $table->text("origem_lead")->nullable();
            
            $table->datetime('data_envio_corretor')->nullable();
            $table->string('avaliacao')->nullable();
            $table->string("comentario_avaliacao")->nullable();
            $table->datetime('data_avaliacao')->nullable();

            $table->foreignId("user_id")->nullable();
            $table->foreignId("corretor_id")->nullable();

            $table->foreign('corretor_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leads');
    }
};
