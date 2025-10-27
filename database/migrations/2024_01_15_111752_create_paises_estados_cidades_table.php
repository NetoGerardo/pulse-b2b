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
        if (!Schema::hasTable('paises')) {
            Schema::create('paises', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('nome', 255);
                $table->enum('status', ['ativo', 'inativo'])->default('ativo');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('estados')) {
            Schema::create('estados', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('pais_id');
                $table->string('nome', 255);
                $table->enum('status', ['ativo', 'inativo'])->default('ativo');
                $table->string('sigla', 255);
                $table->string('iso', 255);
                $table->string('slug', 255);
                $table->string('populacao', 255);

                $table->timestamps();
                $table->softDeletes();

                $table->foreign('pais_id')->references('id')->on('paises')
                    ->onUpdate('cascade')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('cidades')) {
            Schema::create('cidades', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('estado_id');
                $table->string('nome', 255);
                $table->enum('status', ['ativo', 'inativo'])->default('ativo');
                $table->string('slug', 255);
                $table->string('iso', 255);
                $table->string('iso_ddd', 255);
                $table->string('populacao', 255);
                $table->string('lat', 255);
                $table->string('long', 255);
                $table->string('renda_per_capita', 255);
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('estado_id')->references('id')->on('estados')
                    ->onUpdate('cascade')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paises');
        Schema::dropIfExists('cidades');
        Schema::dropIfExists('estados');
    }
};
