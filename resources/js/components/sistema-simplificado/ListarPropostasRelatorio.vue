<template>
    <div class="form_wrapper" style="margin-top: -5%">
        <div class="form_container">
            <form @submit.prevent>
                <div class="row clearfix">
                    <div class="col_half">
                        <label>De</label>
                        <div class="input_field">
                            <input id="dataInicio" name="dataInicio" type="date" v-model="dataInicio" />
                        </div>
                    </div>
                    <div class="col_half">
                        <label>Até</label>
                        <div class="input_field">
                            <input id="dataFim" type="date" v-model="dataFim" />
                        </div>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="col_three">
                        <div class="input_field">
                            <button v-on:click="search(1)" :class="period === 1 ? 'button-select-date-focused' : 'button-select-date'
                                " id="dataFim">
                                Hoje
                            </button>
                        </div>
                    </div>
                    <div class="col_three">
                        <div class="input_field">
                            <button v-on:click="search(7)" :class="period === 7 ? 'button-select-date-focused' : 'button-select-date'
                                " id="dataFim">
                                7 dias
                            </button>
                        </div>
                    </div>
                    <div class="col_three">
                        <div class="input_field">
                            <button v-on:click="search(15)" :class="period === 15 ? 'button-select-date-focused' : 'button-select-date'
                                " id="dataFim">
                                15 dias
                            </button>
                        </div>
                    </div>
                </div>
                <button class="button" style="background-color: #22ca80;" type="button"
                    v-on:click="search(0)">Buscar</button>
            </form>
        </div>
    </div>

    <loading v-model:active="isLoading" :can-cancel="false" :is-full-page="true" />

    <div class="row" style="padding: 10px 10px 10px 10px">

        <div class="col-sm-12 col-md-3">
            <div class="form-group">
                <label>Selecione um Supervisor</label>
                <select class="form-control" v-model="supervisor_selecionado" @change="buscarCorretor()">
                    <option value=""></option>
                    <option v-for="supervisor in supervisores" :key="supervisor" :value="supervisor.id">
                        {{ supervisor.name }}
                    </option>
                </select>
            </div>
        </div>

        <div class="col-sm-12 col-md-3" v-if="supervisor_selecionado">
            <div class="form-group">
                <label>Selecione um Corretor</label>
                <select class="form-control" v-model="corretor_selecionado" @change="search()">
                    <option value=""></option>
                    <option v-for="corretor in corretores" :key="corretor" :value="corretor.id">
                        {{ corretor.name }}
                    </option>
                </select>
            </div>
        </div>

        <div class="col-sm-12 col-md-3">
            <div class="form-group">
                <label>Tipo de produto</label>
                <select class="form-control" v-model="tipo_produto" @change="search()">
                    <option value=""></option>
                    <option v-for="tipo_produto in tipo" :key="tipo_produto" :value="tipo_produto.id">
                        {{ tipo_produto.nome }}
                    </option>
                </select>
            </div>
        </div>

        <div class="col-sm-12 col-md-3">
            <div class="form-group">
                <label>Status</label>
                <select class="form-control" v-model="status_selecionado" @change="search()">
                    <option value=""></option>
                    <option v-for="status_produto in status" :key="status_produto" :value="status_produto.id">
                        {{ status_produto.nome }}
                    </option>
                </select>
            </div>
        </div>

        <div class="col-sm-12 col-md-3">
            <div class="form-group">
                <label>Selecione uma operadora</label>
                <select class="form-control" v-model="operadora_selecionada" @change="buscarProdutos()">
                    <option value=""></option>
                    <option v-for="operadora in operadoras" :key="operadora" :value="operadora.id">
                        {{ operadora.nome }}
                    </option>
                </select>
            </div>
        </div>

        <div class="col-sm-12 col-md-3" v-if="operadora_selecionada != ''">
            <div class="form-group">
                <label>Selecione um produto</label>
                <select class="form-control" v-model="produto_selecionado" @change="search()">
                    <option value=""></option>
                    <option v-for="produtos in aux_produtos" :key="produtos" :value="produtos.id">
                        {{ produtos.nome }}
                    </option>
                </select>
            </div>
        </div>

        <div class="col-sm-12 col-md-3">
            <div class="form-group">
                <label>Buscar por Telefone</label>
                <input class="form-control" v-model="telefone" />
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Column -->
        <div class="col-md-6 col-lg-3 col-xlg-3">
            <div class="card card-hover">
                <div class="p-2 bg-success text-center">
                    <h3 class="font-light text-white"> R$ {{ total_recebido }}</h3>
                    <h6 class="text-white">Total Recebido </h6>
                </div>
            </div>
        </div>
    </div>


    <div>
        <user-cadastrar-proposta-relatorio :tipo="tipo" :supervisores="supervisores" :status="status" :produtos="produtos"
            :operadoras="operadoras" :administradoras="administradoras" />
        <div class="card-body p-0">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 25%">Nome</th>
                        <th style="width: 25%">Telefone</th>
                        <th style="width: 25%">Produto</th>
                        <th style="width: 25%">Administradora</th>
                        <th style="width: 12%">Gerenciar</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="proposta in aux_propostas" :key="proposta">

                        <td data-label="Nome">{{ proposta.nome_titular }}</td>

                        <td data-label="Telefone">{{ proposta.telefone_titular }}</td>

                        <td data-label="Produto">{{ proposta.produto }}</td>

                        <td data-label="Administradora">{{ proposta.administradora }}</td>

                        <td>
                            <button data-toggle="modal" data-target="#detalhes-modal" @click="show(proposta)" type="button"
                                class="btn btn-primary btn-sm">
                                Gerenciar
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="modal fade" id="detalhes-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <form @submit.prevent>
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                &times;
                            </button>
                            <h4>Editar Proposta</h4>
                            <br />
                            <div class="form-group">
                                <label for="email">Nome</label>
                                <input class="form-control" id="nome" type="text"
                                    v-model="proposta_selecionada.nome_titular" />
                            </div>

                            <div class="form-group">
                                <label for="email">Telefone</label>
                                <input class="form-control" id="nome" type="text"
                                    v-model="proposta_selecionada.telefone_titular" />
                            </div>

                            <div class="form-group">
                                <label for="email">CPF</label>
                                <input class="form-control" id="nome" type="text"
                                    v-model="proposta_selecionada.cpf_titular" />
                            </div>

                            <div class="form-group">
                                <label for="email">Administradora</label>
                                <select class="form-control" id="administradora"
                                    v-model="proposta_selecionada.administradora_id">
                                    <option v-for="administradora in administradoras" :key="administradora.id"
                                        :value="administradora.id">
                                        {{ administradora.nome }}
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="email">Operadora</label>
                                <select class="form-control" id="operadora" v-model="proposta_selecionada.operadora_id">
                                    <option v-for="operadora in operadoras" :key="operadora.id" :value="operadora.id">
                                        {{ operadora.nome }}
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="email">Produto</label>
                                <select class="form-control" id="produto" v-model="proposta_selecionada.produto_id">
                                    <option v-for="produto in produtos" :key="produto.id" :value="produto.id">
                                        {{ produto.nome }}
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="email">Tipo produto</label>
                                <select class="form-control" id="produto" v-model="proposta_selecionada.tipo_produto_id">
                                    <option v-for="tipo_produto in tipo" :key="tipo_produto.id" :value="tipo_produto.id">
                                        {{ tipo_produto.nome }}
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="email">Status</label>
                                <select class="form-control" id="status" v-model="proposta_selecionada.status_id">
                                    <option v-for="status_proposta in status" :key="status_proposta.id"
                                        :value="status_proposta.id">
                                        {{ status_proposta.nome }}
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="user_type">Quantidade de vidas</label>
                                <select class="form-control" id="user_type" v-model="proposta_selecionada.qtd_vidas">
                                    <option v-for="vida in vidas" :key="vida" :value="vida">
                                        {{ vida }}
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <div class="col_half">
                                    <label>Data de envio da documentação</label>
                                    <div class="input_field">
                                        <input id="dataInicio" name="dataInicio" type="date"
                                            v-model="proposta_selecionada.data_envio_documentacao" />
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password" class="col-md-4 col-form-label text-md-end">Complemento</label>
                                <textarea class="form-control" id="senha" type="senha"
                                    v-model="proposta_selecionada.observacoes" autocomplete="senha" rows="5" cols="40" />
                            </div>

                            <div class="form-group">
                                <label>Selecione um Supervisor</label>
                                <select class="form-control" v-model="proposta_selecionada.supervisor_id"
                                    @change="buscarCorretorPorId(proposta_selecionada.supervisor_id, true)">
                                    <option value=""></option>
                                    <option v-for="supervisor in supervisores" :key="supervisor" :value="supervisor.id">
                                        {{ supervisor.name }}
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Selecione um Corretor</label>
                                <select class="form-control" v-model="proposta_selecionada.corretor_id">
                                    <option value=""></option>
                                    <option v-for="corretor in corretores" :key="corretor" :value="corretor.id">
                                        {{ corretor.name }}
                                    </option>
                                </select>
                            </div>

                            <!-- PARCELAS -->
                            <div id="accordion" class="col-md-12">

                                <!-- PARCELA 1 -->
                                <div class="card card-info"
                                    :class="proposta_selecionada.data_repasse_1 ? 'card-green' : 'card-white'">
                                    <div class="card-header" data-toggle="collapse" :href="'#collapse1'">
                                        <label class="card-title"
                                            :style="proposta_selecionada.data_repasse_1 ? 'color: white' : 'color: black'">Parcela
                                            1</label>
                                    </div>
                                    <div :id="'collapse1'" class="collapse" data-parent="#accordion"
                                        style="background-color: white;">
                                        <div class="card-body">
                                            <div class="row" style="padding: 10px 10px 10px 10px">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="imageToSend">Valor da parcela:</label>
                                                        <input type="number" min="1" step="any" id="parcela1"
                                                            nome="parcela1" v-model="proposta_selecionada.parcela_1"
                                                            class="form-control" />
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Data de pagamento</label>
                                                        <div class="input_field">
                                                            <input id="dataInicio" name="dataInicio" type="date"
                                                                v-model="proposta_selecionada.data_pagamento_1" />
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Data de repasse</label>
                                                        <div class="input_field">
                                                            <input id="dataInicio" name="dataInicio" type="date"
                                                                v-model="proposta_selecionada.data_repasse_1" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- PARCELA 2 -->
                                <div class="card card-info"
                                    :class="proposta_selecionada.data_repasse_2 ? 'card-green' : 'card-white'">
                                    <div class="card-header" data-toggle="collapse" :href="'#collapse2'">
                                        <label class="card-title"
                                            :style="proposta_selecionada.data_repasse_2 ? 'color: white' : 'color: black'">Parcela
                                            2</label>
                                    </div>
                                    <div :id="'collapse2'" class="collapse" data-parent="#accordion"
                                        style="background-color: white;">
                                        <div class="card-body">
                                            <div class="row" style="padding: 10px 10px 10px 10px">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="imageToSend">Valor da parcela:</label>
                                                        <input type="number" min="1" step="any" id="parcela1"
                                                            nome="parcela1" v-model="proposta_selecionada.parcela_2"
                                                            class="form-control" />
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Data de pagamento</label>
                                                        <div class="input_field">
                                                            <input id="dataInicio" name="dataInicio" type="date"
                                                                v-model="proposta_selecionada.data_pagamento_2" />
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Data de repasse</label>
                                                        <div class="input_field">
                                                            <input id="dataInicio" name="dataInicio" type="date"
                                                                v-model="proposta_selecionada.data_repasse_2" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- PARCELA 3 -->
                                <div class="card card-info"
                                    :class="proposta_selecionada.data_repasse_3 ? 'card-green' : 'card-white'">
                                    <div class="card-header" data-toggle="collapse" :href="'#collapse3'">
                                        <label class="card-title"
                                            :style="proposta_selecionada.data_repasse_3 ? 'color: white' : 'color: black'">Parcela
                                            3</label>
                                    </div>
                                    <div :id="'collapse3'" class="collapse" data-parent="#accordion"
                                        style="background-color: white;">
                                        <div class="card-body">
                                            <div class="row" style="padding: 10px 10px 10px 10px">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="imageToSend">Valor da parcela:</label>
                                                        <input type="number" min="1" step="any" id="parcela1"
                                                            nome="parcela1" v-model="proposta_selecionada.parcela_3"
                                                            class="form-control" />
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Data de pagamento</label>
                                                        <div class="input_field">
                                                            <input id="dataInicio" name="dataInicio" type="date"
                                                                v-model="proposta_selecionada.data_pagamento_3" />
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Data de repasse</label>
                                                        <div class="input_field">
                                                            <input id="dataInicio" name="dataInicio" type="date"
                                                                v-model="proposta_selecionada.data_repasse_3" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button data-toggle="modal" data-target="#detalhes-modal" @click="show(plano)" type="button"
                                class="btn btn-primary btn-sm">
                                Voltar
                            </button>

                            <button style="text-align: right" class="btn btn-danger  btn-sm" @click="deletarOrigem()">
                                Deletar <i class="fa fa-trash"></i>
                            </button>

                            <button class="btn btn-success btn-sm" @click="update()">
                                Salvar <i class="fa fa-save"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>



    </div>
</template>
  
<script>
import sweetAlert from "../controller/sweetAlert";
import Swal from "sweetalert2/dist/sweetalert2.js";
import AuxController from "../mixins/auxController.js";
import moment from "moment";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";

export default {

    props: ['propostas', 'operadoras', 'administradoras', 'produtos', 'status', 'supervisores', 'tipo'],

    mixins: [sweetAlert, Swal, AuxController],

    components: { Loading },

    data() {
        return {
            isLoading: false,
            aux_origens: "",
            comissionamento: [],
            proposta_selecionada: "",
            vidas: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29],
            dataInicio: "",
            dataFim: "",
            period: 0,
            aux_propostas: "",
            total: "",
            avaliacoes_pendentes: "",
            total_positivas: "",
            total_negativas: "",
            corretor_selecionado: "",
            telefone: "",
            supervisor_selecionado: "",
            corretores: "",
            operadora_selecionada: "",
            tipo_produto: "",
            aux_produtos: "",
            status_selecionado: "",
            total_recebido: 0,
            id_teste: 1
        };
    },

    mounted() {
        this.aux_propostas = this.propostas;

        //DEFININDO DATA DE HOJE
        this.dataInicio = new Date();
        this.dataInicio = this.formatSelectedDate(this.dataInicio);

        //DEFININDO DATA DE HOJE
        this.dataFim = new Date();
        this.dataFim = this.formatSelectedDate(this.dataFim);

        //console.log("Tipo");
        //console.log(this.tipo);

        this.calcularTotal();
    },

    methods: {

        calcularTotal() {

            this.total_recebido = 0;

            let dataInicio = new Date(this.dataInicio + " 00:00:00");
            let dataFim = new Date(this.dataFim + " 23:59:59");

            console.log("Inicio " + dataInicio);
            console.log("Fim " + dataFim);

            for (let i = 0; i < this.aux_propostas.length; i++) {

                //PARCELA 1
                if (this.aux_propostas[i].data_repasse_1) {
                    let data_pg_1 = new Date(this.aux_propostas[i].data_repasse_1 + " 00:00:00");

                    console.log(this.aux_propostas[i].data_repasse_1 + " 00:00:00");

                    if (data_pg_1 >= dataInicio && data_pg_1 <= dataFim) {
                        this.total_recebido = this.total_recebido + Number(this.aux_propostas[i].parcela_1);
                    }
                }

                //PARCELA 2
                if (this.aux_propostas[i].data_repasse_2) {
                    let data_pg_2 = new Date(this.aux_propostas[i].data_repasse_2 + " 00:00:00");

                    console.log(this.aux_propostas[i].data_repasse_2 + " 00:00:00");

                    if (data_pg_2 >= dataInicio && data_pg_2 <= dataFim) {
                        this.total_recebido = this.total_recebido + Number(this.aux_propostas[i].parcela_2);
                    }
                }

                //PARCELA 3
                if (this.aux_propostas[i].data_repasse_3) {
                    let data_pg_3 = new Date(this.aux_propostas[i].data_repasse_3 + " 00:00:00");

                    console.log(this.aux_propostas[i].data_repasse_3 + " 00:00:00");

                    if (data_pg_3 >= dataInicio && data_pg_3 <= dataFim) {
                        this.total_recebido = this.total_recebido + Number(this.aux_propostas[i].parcela_3);
                    }
                }
            }
        },

        show(proposta) {
            this.proposta_selecionada = proposta;
            console.log(proposta);

            this.buscarCorretorPorId(proposta.supervisor_id, false);
        },

        buscarCorretorPorId(supervisor_id, reset_corretor) {
            this.isLoading = true;

            let data = {
                supervisor_id: supervisor_id,
            }

            console.log("Enviando");
            console.log(data);

            axios
                .post(`/admin/corretores/search`, data)
                .then((response) => {
                    this.corretores = response.data.corretores;
                    console.log(this.corretores);
                    this.isLoading = false;

                    if (reset_corretor) {
                        this.proposta_selecionada.corretor_id = null;
                    }
                })
                .catch((error) => {
                    this.showErrorMessageWithButton("Ops..", error.response.data);
                    console.log(error.response.data);
                });
        },

        buscarCorretor() {
            this.isLoading = true;

            let data = {
                supervisor_id: this.supervisor_selecionado,
            }

            console.log("Enviando");
            console.log(data);

            axios
                .post(`/admin/corretores/search`, data)
                .then((response) => {
                    this.corretores = response.data.corretores;
                    console.log(this.corretores);
                    this.isLoading = false;
                })
                .catch((error) => {
                    this.showErrorMessageWithButton("Ops..", error.response.data);
                    console.log(error.response.data);
                });
        },

        buscarProdutos() {
            this.isLoading = true;

            let data = {
                operadora_id: this.operadora_selecionada,
            }

            console.log("Enviando");
            console.log(data);

            axios
                .post(`/admin/produto/search`, data)
                .then((response) => {
                    this.aux_produtos = response.data.produtos;
                    console.log(this.aux_produtos);
                    this.isLoading = false;
                })
                .catch((error) => {
                    this.showErrorMessageWithButton("Ops..", error);
                    console.log(error);
                });
        },

        search(number) {
            this.isLoading = true;

            this.period = number;

            if (this.period == 1) {

                //DEFININDO DATA DE HOJE
                this.dataInicio = new Date();
                this.dataInicio = this.formatSelectedDate(this.dataInicio);

                //DEFININDO DATA DE HOJE
                this.dataFim = new Date();
                this.dataFim = this.formatSelectedDate(this.dataFim);
            } else if (this.period > 0) {

                //DEFININDO DATA DE HOJE
                this.dataFim = new Date();
                this.dataFim = this.formatSelectedDate(this.dataFim);

                //DEFININDO DATA DE ACORDO COM O PERIODO
                let dataHoje = new Date();
                dataHoje.setDate(dataHoje.getDate() - this.period);
                this.dataInicio = this.formatSelectedDate(dataHoje);
            }

            let aux_dataInicio = this.formatDateToSearch(this.dataInicio);
            let aux_dataFim = this.formatDateToSearchTime(this.dataFim);

            let data = {
                data_inicio: aux_dataInicio,
                data_fim: aux_dataFim,
                supervisor_id: this.supervisor_selecionado,
                corretor_id: this.corretor_selecionado,
                produto_id: this.produto_selecionado,
                tipo_produto_id: this.tipo_produto,
                operadora_selecionada: this.operadora_selecionada,
                telefone: this.telefone,
                status_id: this.status_selecionado
            }

            console.log("Enviando");
            console.log(data);

            axios
                .post(`/admin/proposta-relatorio/search`, data)
                .then((response) => {
                    this.aux_propostas = response.data.propostas;
                    console.log(this.aux_propostas);
                    this.calcularTotal();
                    this.isLoading = false;
                })
                .catch((error) => {
                    this.showErrorMessageWithButton("Ops..", error.response.data);
                    console.log(error.response.data);
                });
        },

        deletarOrigem() {
            this.$swal
                .fire({
                    title: "<h2 style='color:#616060'>Deseja deletar esta origem? <b>" + this.plano_selecionado.nome + "</b></h2>",
                    text: "Voce nao poderá reverter essa operação!",
                    icon: "warning",
                    padding: '1.5em',
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sim, quero deletar!"
                })
                .then(result => {
                    if (result.value) {
                        this.deletar();
                    }
                });
        },

        deletar() {

            let data = {
                id: this.plano_selecionado.id
            }

            axios
                .post(`/admin/proposta-relatorio/delete`, data)
                .then((response) => {

                    this.showSuccessMessage("Plano deletado!");

                    window.location.reload();

                })
                .catch((error) => {
                    this.showErrorMessageWithButton("Ops..", error.response.data);
                    console.log(error.response.data);
                });
        },

        validateFields() {
            if (!this.nome) {
                this.showErrorMessageWithButton("Ops...", "O campo nome é obrigatório!");
            } else {
                this.create();
            }
        },

        update() {
            this.isLoading = true;

            let data = {
                id: this.proposta_selecionada.id,
                nome_titular: this.proposta_selecionada.nome_titular,
                cpf_titular: this.proposta_selecionada.cpf_titular,
                telefone_titular: this.proposta_selecionada.telefone_titular,
                data_envio_documentacao: this.proposta_selecionada.data_envio_documentacao,
                administradora_id: this.proposta_selecionada.administradora_id,
                operadora_id: this.proposta_selecionada.operadora_id,
                produto_id: this.proposta_selecionada.produto_id,
                status_id: this.proposta_selecionada.status_id,
                observacoes: this.proposta_selecionada.observacoes,
                qtd_vidas: this.proposta_selecionada.qtd_vidas,
                supervisor_id: this.proposta_selecionada.supervisor_id,
                corretor_id: this.proposta_selecionada.corretor_id,
                parcela_1: this.proposta_selecionada.parcela_1,
                data_pagamento_1: this.proposta_selecionada.data_pagamento_1,
                data_repasse_1: this.proposta_selecionada.data_repasse_1,
                parcela_2: this.proposta_selecionada.parcela_2,
                data_pagamento_2: this.proposta_selecionada.data_pagamento_2,
                data_repasse_2: this.proposta_selecionada.data_repasse_2,
                parcela_3: this.proposta_selecionada.parcela_3,
                data_pagamento_3: this.proposta_selecionada.data_pagamento_3,
                data_repasse_3: this.proposta_selecionada.data_repasse_3,
            };

            console.log("ATUALIZANDO");
            console.log(data);

            axios
                .post(`/admin/proposta-relatorio/update`, data)
                .then((response) => {

                    this.showSuccessMessage("Proposta atualizada!");

                    window.location.reload();
                })
                .catch((error) => {
                    this.showErrorMessageWithButton("Ops..", error);
                    console.log(error);
                });

        },

        formatDate(date) {
            return moment(date).format("DD/MM/YYYY HH:mm:ss");
        },

        formatSelectedDate(date) {
            return moment(date).format("yyyy-MM-DD");
        },

        formatDateToSearch(date) {
            return moment(date).format("yyyy-MM-DD 00:00:00");
        },

        formatDateToSearchTime(date) {
            return moment(date).format("yyyy-MM-DD 23:59:59");
        },
    },
};
</script>

<style scoped>
.card-green {
    background: #22ca80 !important;
    color: white !important;
}

.card-white {
    background-color: white;
    color: black
}

label {
    font-weight: normal;
    color: gray;
}

.stati {
    background: #fff;
    width: 12em;
    height: 4em;
    padding: 1em;
    margin: 1em 0;
    /* Safari */
    box-shadow: 0px 0.2em 0.3em rgb(0, 0, 0, 0.8);
}

.stati.bg-orange {
    background: rgb(243, 156, 18);
    color: white;
}

.stati.bg-nephritis {
    background: rgb(39, 174, 96);
    color: white;
}

.stati.bg-peter_river {
    background: rgb(52, 152, 219);
    color: white;
}

.stati.bg-green_sea {
    background: rgb(22, 160, 133);
    color: white;
}

.stati div {
    width: calc(100% - 3.5em);
    display: block;
    float: right;
    text-align: right;
}

.stati fa {
    font-size: 3.5em;
}

.stati div {
    width: calc(100% - 3.5em);
    display: block;
    float: right;
    text-align: right;
}

.stati div b {
    font-size: 2.2em;
    width: 100%;
    padding-top: 0px;
    margin-top: -0.2em;
    margin-bottom: -0.2em;
    display: block;
}

.stati div span {
    font-size: 1em;
    width: 100%;
    color: rgb(0, 0, 0, 0.8);
    display: block;
}

.stati.left div {
    float: left;
    text-align: left;
}

.stati.bg-turquoise {
    background: rgb(26, 188, 156);
    color: white;
}

table {
    border: 1px solid #ccc;
    border-collapse: collapse;
    margin: 0;
    padding: 0;
    width: 100%;
    table-layout: fixed;
}

table tr:nth-child(even) {
    background: #fff;
}

table tr:nth-child(odd) {
    background: #eee;
}

table.pdi tr:nth-child(even) {
    background: #fff;
}

table.pdi tr:nth-child(odd) {
    background: #80f0af;
}

table caption {
    font-size: 1.2em;
    margin: 0.1em 0 0.5em;
}

table tr {
    background-color: #f8f8f8;
    border: 1px solid #ddd;
    padding: 0.1em;
}

table th,
table td {
    padding: 0.2em;
    text-align: center;
    font-size: 0.9em;
}

table th {
    font-size: 0.7em;
    letter-spacing: 0.1em;
    text-transform: uppercase;
}

@media screen and (max-width: 600px) {
    table {
        border: 0;
    }

    table caption {
        font-size: 1em;
    }

    table thead {
        border: none;
        clip: rect(0 0 0 0);
        height: 1px;
        margin: -1px;
        overflow: hidden;
        padding: 0;
        position: absolute;
        width: 1px;
    }

    table tr {
        border-bottom: 3px solid #ddd;
        display: block;
        margin-bottom: 0.625em;
    }

    table td {
        padding: 0.4em;
        border-bottom: 1px solid #ddd;
        display: block;
        font-size: 0.9em;
        text-align: right;
    }

    table td::before {
        /*
* aria-label has no advantage, it won't be read inside a table
content: attr(aria-label);
*/
        content: attr(data-label);
        float: left;
        font-weight: bold;
        text-transform: uppercase;
    }

    table td:last-child {
        border-bottom: 0;
    }
}

.cursor {
    cursor: pointer;
}

.button-voltar {
    background-color: #b8ab39;
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
}

.button-select-date {
    background-color: white;
    color: black;
    padding: 5px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 13px;
    border-radius: 5px;
    border: 1px solid #ccc;
    box-sizing: border-box;
    width: 100%;
    margin: 4px 2px;
    cursor: pointer;
}

.button-select-date-focused {
    background-color: white;
    color: black;
    padding: 5px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 13px;
    border-radius: 5px;
    border: 1px solid #ccc;
    box-sizing: border-box;
    width: 100%;
    margin: 4px 2px;
    cursor: pointer;
    -webkit-box-shadow: 0 0 2px 1px rgba(255, 169, 0, 0.9);
    -moz-box-shadow: 0 0 2px 1px rgba(255, 169, 0, 0.9);
    box-shadow: 0 0 3px 1px rgba(255, 169, 0, 0.9);
}

.button {
    background-color: #4caf50;
    border: none;
    color: white;
    padding: 5px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 17;
    border-radius: 5px;
    width: 100%;
    margin: 4px 2px;
    cursor: pointer;
}

.button-exit {
    background-color: #a67e21;
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
}

body {
    font-family: Verdana, Geneva, sans-serif;
    font-size: 14px;
    background: #f2f2f2;
}

.clearfix:after {
    content: "";
    display: block;
    clear: both;
    visibility: hidden;
    height: 0;
}

.form_wrapper {
    width: 500px;
    max-width: 100%;
    box-sizing: border-box;
    padding: 15px;
    margin: 0% auto 0;
    position: relative;
    z-index: 1;
}

.form_container {
    padding: 15px;
}

.form_wrapper h2 {
    font-size: 1.5em;
    line-height: 1.5em;
    margin: 0;
}

.form_wrapper .title_container {
    text-align: center;
    margin: -15px -15px 15px;
    padding: 15px 0;
}

.form_wrapper h3 {
    font-size: 1.1em;
    font-weight: normal;
    line-height: 1.5em;
    margin: 0;
}

.form_wrapper .row {
    margin: 10px -15px;
}

.form_wrapper .row>div {
    padding: 0 15px;
    box-sizing: border-box;
}

.form_wrapper .col_half {
    width: 50%;
    float: left;
}

.form_wrapper .col_full {
    width: 100%;
    float: left;
}

.form_wrapper .col_three {
    width: 33%;
    float: left;
}

.form_wrapper .col_four {
    width: 25%;
    float: left;
}

.form_wrapper label {
    display: block;
    margin: 0 0 5px;
}

.form_wrapper .input_field,
.form_wrapper .textarea_field {
    position: relative;
}

.form_wrapper .input_field>span,
.form_wrapper .textarea_field>span {
    position: absolute;
    left: 0;
    top: 0;
    color: #333;
    height: 100%;
    text-align: center;
    width: 20px;
}

.form_wrapper .textarea_field>span {
    max-height: 35px;
}

.input_field {
    font-size: 12px;
}

.form_wrapper .input_field>span>i,
.form_wrapper .textarea_field>span>i {
    padding-top: 5px;
}

.form_wrapper input[type="text"],
.form_wrapper input[type="date"],
.form_wrapper input[type="email"],
.form_wrapper input[type="tel"],
textarea {
    width: 100%;
    padding: 6px 6px 6px 35px;
    border: 1px solid #ccc;
    box-sizing: border-box;
    outline: none;
    -webkit-transition: all 0.3s ease-in-out;
    -moz-transition: all 0.3s ease-in-out;
    -ms-transition: all 0.3s ease-in-out;
    transition: all 0.3s ease-in-out;
}

.form_wrapper textarea {
    height: 8em;
}

.form_wrapper input[type="text"]:focus,
.form_wrapper input[type="date"]:focus,
.form_wrapper input[type="email"]:focus,
.form_wrapper input[type="tel"]:focus,
textarea:focus {
    -webkit-box-shadow: 0 0 2px 1px rgba(255, 169, 0, 0.5);
    -moz-box-shadow: 0 0 2px 1px rgba(255, 169, 0, 0.5);
    box-shadow: 0 0 2px 1px rgba(255, 169, 0, 0.5);
    border: 1px solid #f5ba1a;
}

.form_wrapper input[type="submit"] {
    height: 50px;
    line-height: 50px;
    width: 100%;
    border: none;
    outline: none;
    cursor: pointer;
    color: #fff;
    font-size: 1.2em;
    -webkit-transition: all 0.3s ease-in-out;
    -moz-transition: all 0.3s ease-in-out;
    -ms-transition: all 0.3s ease-in-out;
    transition: all 0.3s ease-in-out;
}

.form_wrapper input[type="submit"]:hover,
.form_wrapper input[type="submit"]:focus {
    background: #daa106;
}

.credit {
    position: relative;
    z-index: 1;
    text-align: center;
    padding: 15px;
    color: #f5ba1a;
}

.credit a {
    color: #daa106;
}

@media (max-width: 600px) {

    .form_wrapper .col_half,
    .form_wrapper .col_three,
    .form_wrapper .col_four {
        width: 100%;
        float: none;
    }

    .form_wrapper label {
        margin: 10px 0;
    }
}
</style>

