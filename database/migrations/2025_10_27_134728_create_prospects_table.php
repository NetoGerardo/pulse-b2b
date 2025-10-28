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
        Schema::create('prospects', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('campanha_id')->nullable();
            $table->unsignedBigInteger('canal_id')->nullable();
            $table->string('canal')->nullable();
            $table->string('telefone')->nullable();
            $table->longText('dados')->nullable();

            $table->string('status_ligacao')->nullable();
            $table->string('status_whatsapp')->nullable();

            $table->timestamps();

            $table->foreign('campanha_id')->references('id')->on('campanhas')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('canal_id')->references('id')->on('canais')
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
        Schema::dropIfExists('prospects');
    }
};
