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
        Schema::create('solicitacao_lista', function (Blueprint $table) {
            $table->id();
            $table->string('status')->nullable();
            $table->integer('qtd_ultima_lista')->nullable();
            $table->integer('qtd_contatos')->nullable();

            $table->datetime('data_ultima_lista')->nullable();
            $table->datetime('data_envio')->nullable();

            $table->string('justificativa_rejeicao')->nullable();
            $table->foreignId('corretor_id')->nullable();
            $table->foreignId('lista_id')->nullable();

            $table->foreign('lista_id')->references('id')->on('listas')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('corretor_id')->references('id')->on('users')
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
        Schema::dropIfExists('solicitacao_lista');
    }
};
