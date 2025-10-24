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
        Schema::table('proposta_relatorio', function (Blueprint $table) {
            $table->decimal('parcela_1', 10, 2)->nullable();
            $table->date('data_pagamento_1')->nullable();
            $table->date('data_repasse_1')->nullable();
            $table->decimal('parcela_2', 10, 2)->nullable();
            $table->date('data_pagamento_2')->nullable();
            $table->date('data_repasse_2')->nullable();
            $table->decimal('parcela_3', 10, 2)->nullable();
            $table->date('data_pagamento_3')->nullable();
            $table->date('data_repasse_3')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('proposta_relatorio', function (Blueprint $table) {
            $table->dropColumn('parcela_1');
            $table->dropColumn('parcela_2');
            $table->dropColumn('parcela_3');
            $table->dropColumn('data_repasse_1');
            $table->dropColumn('data_repasse_2');
            $table->dropColumn('data_repasse_3');
            $table->dropColumn('data_pagamento_1');
            $table->dropColumn('data_pagamento_2');
            $table->dropColumn('data_pagamento_3');
        });
    }
};
