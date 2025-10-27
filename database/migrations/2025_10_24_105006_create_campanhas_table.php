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
        Schema::create('campanhas', function (Blueprint $table) {
            $table->id();
            $table->string("nome")->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->datetime("data_inicio")->nullable();
            $table->datetime("data_fim")->nullable();
            $table->string("status")->nullable();
            $table->integer("total_contatos")->nullable();
            $table->string("canal")->nullable();
            $table->longText("mensagem")->nullable();

            $table->unsignedBigInteger("estado_id")->nullable();
            $table->unsignedBigInteger("cidade_id")->nullable();
            $table->string("opcao_mei")->nullable();
            $table->datetime("data_abertura_inicio")->nullable();
            $table->datetime("data_abertura_fim")->nullable();

            $table->string("produto")->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('estado_id')->references('id')->on('estados')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('cidade_id')->references('id')->on('cidades')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campanhas');
    }
};
