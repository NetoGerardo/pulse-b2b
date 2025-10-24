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
        Schema::create('propostas', function (Blueprint $table) {
            $table->id();
            $table->string('nome_titular')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('status')->nullable();
            $table->string('valor_proposta')->nullable();
            $table->string('pdf_proposta')->nullable();
            $table->string('comprovante_residencia')->nullable();
            $table->string('comprovante_vinculo')->nullable();
            $table->date('data_vencimento')->nullable();

            $table->foreignId('plano_id')->nullable();

            $table->foreign('plano_id')->references('id')->on('planos')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->string('cpf')->nullable();
            $table->string('identidade')->nullable();
            $table->integer('parcelas_recebidas')->default(0);
            $table->string('tipo_proposta')->nullable();
            $table->string('tipo_empresa')->nullable();
            $table->string('documento_responsavel')->nullable();
            $table->string('contrato_social')->nullable();
            $table->string('cnpj')->nullable();
            $table->string('identidade_titular')->nullable();
            $table->string('cpf_titular')->nullable();
            $table->string('certidao_casamento')->nullable();
            $table->string('tutela')->nullable();
            $table->string('sobrinhos')->nullable();
            $table->string('cpf_titular_sobrinhos')->nullable();
            $table->text('dependentes')->nullable();
            $table->string('descricao_pendencia')->nullable();
            $table->string('qtd_vidas')->nullable();
            $table->string('qtd_empregados')->nullable();
            $table->text('empregados')->nullable();
            $table->text('vidas')->nullable();
            $table->string('esocial')->nullable();

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
        Schema::dropIfExists('propostas');
    }
};
