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
        Schema::create('proposta_relatorio', function (Blueprint $table) {
            $table->id();
            $table->string('nome_titular')->nullable();
            $table->string('cpf_titular')->nullable();
            $table->date('data_envio_documentacao')->nullable();
            $table->string('telefone_titular')->nullable();
            $table->integer('qtd_vidas')->nullable();
            $table->string('observacoes')->nullable();

            $table->foreignId('administradora_id')->nullable();
            $table->foreignId('operadora_id')->nullable();
            $table->foreignId('produto_id')->nullable();
            $table->foreignId('status_id')->nullable();
            $table->foreignId('corretor_id')->nullable();
            $table->foreignId('supervisor_id')->nullable();
            $table->foreignId('tipo_produto_id')->nullable();

            $table->foreign('tipo_produto_id')->references('id')->on('tipo_produto')
            ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('produto_id')->references('id')->on('produtos')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('administradora_id')->references('id')->on('administradoras')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('operadora_id')->references('id')->on('operadoras')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('status_id')->references('id')->on('status_proposta')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('corretor_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('supervisor_id')->references('id')->on('users')
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
        Schema::dropIfExists('proposta_relatorio');
    }
};
