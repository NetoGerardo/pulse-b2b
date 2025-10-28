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
        Schema::create('canal_campanha', function (Blueprint $table) {
            // Chave estrangeira para a tabela 'canais'
            $table->foreignId('canal_id')
                ->constrained('canais') // 'constrained' assume a tabela 'canais' e a coluna 'id'
                ->onUpdate('cascade')
                ->onDelete('cascade'); // Se um canal for deletado, remove a relação

            // Chave estrangeira para a tabela 'campanhas'
            $table->foreignId('campanha_id')
                ->constrained('campanhas') // 'constrained' assume a tabela 'campanhas' e a coluna 'id'
                ->onUpdate('cascade')
                ->onDelete('cascade'); // Se uma campanha for deletada, remove a relação

            // Definir a chave primária composta
            // Isso impede que o mesmo canal seja associado à mesma campanha múltiplas vezes.
            $table->primary(['canal_id', 'campanha_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('canal_campanha');
    }
};
