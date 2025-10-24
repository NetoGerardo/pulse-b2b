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
        Schema::create('contatos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('telefone');
            $table->boolean('possui_cnpj')->nullable();
            $table->string('status')->nullable();
            $table->string('ocupacao')->nullable();
            $table->string('bairro')->nullable();
            $table->boolean('possui_plano')->nullable();
            $table->string('qtd_vidas')->nullable();
            $table->string('observacao')->nullable();
            $table->text('idades')->nullable();
            $table->text('complemento')->nullable();

            $table->string('observacoes_admin')->nullable();
            $table->string('observacoes_corretor')->nullable();

            $table->foreignId('user_id')->nullable();

            $table->text('origem')->nullable();

            $table->foreignId('origem_id')->nullable();

            $table->foreignId('proposta_id')->nullable();

            $table->foreign('proposta_id')->references('id')->on('propostas')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('origem_id')->references('id')->on('origens')
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
        Schema::dropIfExists('contatos');
    }
};
