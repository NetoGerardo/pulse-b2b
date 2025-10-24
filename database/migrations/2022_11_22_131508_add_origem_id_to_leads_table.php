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

            $table->string('avaliacao_sistema')->nullable();
            $table->foreignId('origem_id')->nullable();

            $table->foreign('origem_id')->references('id')->on('origens')
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
            $table->dropForeign('leads_origem_id_foreign');
            $table->dropColumn('origem_id');
            $table->dropColumn('avaliacao_sistema');
        });
    }
};
