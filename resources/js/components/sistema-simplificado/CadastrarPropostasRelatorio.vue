<template>
    <div>
        <button data-toggle="modal" data-target="#login-modal" type="button" class="btn btn-info">
            Nova Proposta<i class="mdi mdi-plus"></i>
        </button>

        <div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <form @submit.prevent>
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                &times;
                            </button>
                            <h4>Novo Plano</h4>
                            <br />

                            <div class="form-group">
                                <label for="email">Nome titular</label>
                                <input class="form-control" id="nome" type="text" v-model="nome_titular" />
                            </div>

                            <div class="form-group">
                                <label for="email">CPF titular</label>
                                <input class="form-control" id="cpf" type="text" v-model="cpf_titular" />
                            </div>

                            <div class="form-group">
                                <label for="email">Telefone titular</label>
                                <input class="form-control" id="telefone" type="text" v-model="telefone_titular" />
                            </div>

                            <div class="form-group">
                                <label for="email">Administradora</label>
                                <select class="form-control" id="administradora" v-model="administradora">
                                    <option v-for="administradora in administradoras" :key="administradora.id"
                                        :value="administradora.id">
                                        {{ administradora.nome }}
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="email">Operadora</label>
                                <select class="form-control" id="operadora" v-model="operadora">
                                    <option v-for="operadora in operadoras" :key="operadora.id" :value="operadora.id">
                                        {{ operadora.nome }}
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="email">Produto</label>
                                <select class="form-control" id="produto" v-model="produto">
                                    <option v-for="produto in produtos" :key="produto.id" :value="produto.id">
                                        {{ produto.nome }}
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="email">Tipo de produto</label>
                                <select class="form-control" id="administradora" v-model="tipo_produto">
                                    <option v-for="tipo_produto in tipo" :key="tipo_produto.id" :value="tipo_produto.id">
                                        {{ tipo_produto.nome }}
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="email">Status</label>
                                <select class="form-control" id="status" v-model="status_proposta">
                                    <option v-for="status_proposta in status" :key="status_proposta.id"
                                        :value="status_proposta.id">
                                        {{ status_proposta.nome }}
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="user_type">Quantidade de vidas</label>
                                <select class="form-control" id="user_type" v-model="qtd_vidas">
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
                                            v-model="data_envio_documentacao" />
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password" class="col-md-4 col-form-label text-md-end">Complemento</label>
                                <textarea class="form-control" id="senha" type="senha" v-model="observacoes"
                                    autocomplete="senha" rows="5" cols="40" />
                            </div>

                            <div class="form-group">
                                <label>Selecione um Supervisor</label>
                                <select class="form-control" v-model="supervisor_selecionado" @change="buscarCorretor()">
                                    <option value=""></option>
                                    <option v-for="supervisor in supervisores" :key="supervisor" :value="supervisor.id">
                                        {{ supervisor.name }}
                                    </option>
                                </select>
                            </div>

                            <div class="form-group" v-if="supervisor_selecionado">
                                <label>Selecione um Corretor</label>
                                <select class="form-control" v-model="corretor_selecionado">
                                    <option value=""></option>
                                    <option v-for="corretor in corretores" :key="corretor" :value="corretor.id">
                                        {{ corretor.name }}
                                    </option>
                                </select>
                            </div>

                            <button @click="validateFields()" class="btn btn-primary btn-flat">
                                Cadastrar <i class="fa fa-search"></i>
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
import moment from "moment";
import "vue-loading-overlay/dist/vue-loading.css";
import Loading from "vue-loading-overlay";

export default {

    mixins: [sweetAlert, Swal],

    props: ['operadoras', 'administradoras', 'produtos', 'status', 'tipo', 'supervisores'],

    components: [Loading],

    data() {
        return {
            cpf_titular: "",
            nome_titular: "",
            telefone_titular: "",
            isLoading: false,
            qtd_vidas: 0,
            vidas: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29],
            produto: "",
            operadora: "",
            administradora: "",
            data_envio_documentacao: "",
            observacoes: "",
            tipo_produto: "",
            status_proposta: "",
            corretores: "",
            supervisor_selecionado: "",
            corretor_selecionado: ""
        };
    },

    mounted() {
        console.log(this.status);
    },

    methods: {

        validateFields() {
            if (!this.nome_titular) {
                this.showErrorMessageWithButton("Ops...", "O campo nome é obrigatório!");
            } else if (!this.administradora) {
                this.showErrorMessageWithButton("Ops...", "Selecione uma administradora");
            } else if (!this.corretor_selecionado || this.corretor_selecionado == "") {
                this.showErrorMessageWithButton("Ops...", "Selecione um corretor");
            } else if (!this.supervisor_selecionado || this.supervisor_selecionado == "") {
                this.showErrorMessageWithButton("Ops...", "Selecione um supervisor");
            } else {
                this.create();
            }
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

        formatSelectedDate(date) {
            return moment(date).format("yyyy-MM-DD");
        },

        formatSelectedDate(date) {
            return moment(date).format("yyyy-MM-DD");
        },

        create() {
            this.isLoading = true;

            let data = {
                nome_titular: this.nome_titular,
                cpf_titular: this.cpf_titular,
                telefone_titular: this.telefone_titular,
                data_envio_documentacao: this.data_envio_documentacao,
                administradora: this.administradora,
                operadora: this.operadora,
                produto: this.produto,
                observacoes: this.observacoes,
                qtd_vidas: this.qtd_vidas,
                tipo_produto: this.tipo_produto,
                supervisor_id: this.supervisor_selecionado,
                corretor_id: this.corretor_selecionado,
            };

            console.log("CRIANDO");
            console.log(data);

            axios
                .post(`/admin/proposta-relatorio/store`, data)
                .then((response) => {

                    this.showSuccessMessage("Proposta cadastrada!");

                    window.location.reload();
                })
                .catch((error) => {
                    this.showErrorMessageWithButton("Ops..", error);
                    console.log(error);
                });

        },
    },
};
</script>

