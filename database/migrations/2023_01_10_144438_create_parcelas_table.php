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
        Schema::create('parcelas', function (Blueprint $table) {
            $table->id();
            $table->string('valor');
            $table->date('data_vencimento');
            $table->string('status');
            $table->string('numero_parcela');
            $table->date('data_pagamento')->nullable();
            $table->date('data_pagamento_corretora')->nullable();
            $table->foreignId('proposta_id')->nullable();

            $table->foreign('proposta_id')->references('id')->on('propostas')
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
        Schema::dropIfExists('parcelas');
    }
};
