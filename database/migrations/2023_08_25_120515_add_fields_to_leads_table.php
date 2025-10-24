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
            $table->boolean('encaminhamento_disponivel')->default(1);

            $table->foreignId('lista_id')->nullable();

            $table->foreign('lista_id')->references('id')->on('listas')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('encaminhamento_disponivel');
            $table->dropForeign('leads_lista_id_foreign');
            $table->dropColumn('lista_id');
        });
    }
};
