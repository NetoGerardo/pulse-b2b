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
        Schema::create('tarefas_leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tarefa_id')->nullable();
            $table->foreignId('contato_id')->nullable();
            $table->dateTime('expira_em')->nullable();
            $table->dateTime('concluida_em')->nullable();
            $table->string('anotacoes')->nullable();
            $table->string('motivo_deletar')->nullable();
            $table->tinyInteger('concluida')->nullable();

            $table->foreign('contato_id')->references('id')->on('contatos')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('tarefa_id')->references('id')->on('tarefas')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->timestamps();
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
        Schema::dropIfExists('tarefas_leads');
    }
};
