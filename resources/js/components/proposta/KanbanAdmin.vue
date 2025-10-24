<template>
    <div class="container-fluid">
        <div class="kanban-board">

            <!--Aguardando aprovação -->
            <div class="col-lg-3 col-md-4 col-sm-6 bg-white card">
                <h3 style="margin-top:5px;">Aguardando aprovação</h3>
                <br />
                <draggable @change="updateAguarandoAprovacao" ghost-class="moving-card" :list="aguardando_aprovacao"
                    class="list-group pointer bg-white" group="a" item-key="name">
                    <template #item="{ element }">
                        <div @click="exibirDetalhes(element)" class="px-1 py-2 bg-white inside-card" data-toggle="modal"
                            data-target="#detalhes-usuario">
                            <div class="bg-white" style="text-align: center">
                                <div class="mr-2" style="float: left;"><img alt="Avatar" class="table-avatar"
                                        style=" min-width:20px;" width="20" height="20"
                                        src="/assets/images/whats-logo.png" /></div>
                                <div>
                                    <h5 class="text-dark mb-0 font-14 font-weight-medium">{{ element.nome }}</h5>
                                    <span class="text-muted font-12">{{ element.telefone }}</span>
                                </div>
                            </div>
                        </div>
                    </template>
                </draggable>
            </div>

            <!--Pendência -->
            <div class="col-lg-3 col-md-4 col-sm-6  bg-warning card">
                <h3 style="color:white; margin-top:5px;">Pendência</h3>
                <br />
                <draggable @change="updatePendencia" ghost-class="moving-card" :list="pendencia" class="list-group"
                    group="a" item-key="name">
                    <template #item="{ element }">
                        <div @click="exibirDetalhes(element)" class="px-1 py-2 inside-card" style="">

                            <!-- PENDÊNCIAS CORRIGIDAS -->
                            <div v-if="element.status == 'Pendências corrigidas'" class="bg-white"
                                style="text-align: center">
                                <div class="mr-2" style="float: left;"><img alt="Avatar" class="table-avatar"
                                        style=" min-width:20px;" width="20" height="20"
                                        src="/assets/images/whats-logo.png" /></div>
                                <div data-toggle="modal" data-target="#detalhes-usuario" class="pointer">
                                    <h5 class="text-dark mb-0 font-14 font-weight-medium">{{ element.nome }}</h5>
                                    <span class="text-muted font-12">{{ element.telefone }}</span>
                                </div>

                                <small id="name13"
                                    class="badge badge-default badge-success form-text text-white float-right">Pendências
                                    corrigidas</small>
                            </div>

                            <!-- PENDÊNCIA COM BOTÃO-->
                            <div v-if="element.status == 'Pendência'" class="bg-white flex-box-2"
                                style="text-align: center">
                                <div class="mr-2" style="float: left;"><img alt="Avatar" class="table-avatar"
                                        style=" min-width:20px;" width="20" height="20"
                                        src="/assets/images/whats-logo.png" /></div>
                                <div data-toggle="modal" data-target="#detalhes-usuario" class="pointer">
                                    <h5 class="text-dark mb-0 font-14 font-weight-medium">{{ element.nome }}</h5>
                                    <span class="text-muted font-12">{{ element.telefone }}</span>
                                </div>

                                <div class="text-right" style="float: right">
                                    <button @click="descreverPendencias" style="float: right; border-radius: 10%"
                                        type="button" class="btn btn-outline-danger btn-sm">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- PENDÊNCIA SEM BOTÃO-->
                            <div v-if="element.status == 'Pendências aguardando'" class="bg-white"
                                style="text-align: center">
                                <div class="mr-2" style="float: left;"><img alt="Avatar" class="table-avatar"
                                        style=" min-width:20px;" width="20" height="20"
                                        src="/assets/images/whats-logo.png" /></div>
                                <div data-toggle="modal" data-target="#detalhes-usuario" class="pointer">
                                    <h5 class="text-dark mb-0 font-14 font-weight-medium">{{ element.nome }}</h5>
                                    <span class="text-muted font-12">{{ element.telefone }}</span>
                                </div>

                                <small id="name13"
                                    class="badge badge-default badge-danger form-text text-white float-right">Aguardando
                                    resolução</small>
                            </div>
                        </div>
                    </template>
                </draggable>
            </div>

            <!--Aguardando vigência -->
            <div class="col-lg-3 col-md-4 col-sm-6  bg-info card">
                <h3 style="color:white; margin-top:5px;">Aguardando vigência</h3>
                <br />
                <draggable @change="updateAguardandoVigencia" ghost-class="moving-card" :list="aguardando_vigencia"
                    class="list-group pointer" group="a" item-key="name" data-toggle="modal"
                    data-target="#detalhes-usuario">
                    <template #item="{ element }">
                        <div @click="exibirDetalhes(element)" class="px-1 py-2 inside-card" style="">
                            <div class="bg-white" style="text-align: center">
                                <div class="mr-2" style="float: left;"><img alt="Avatar" class="table-avatar"
                                        style=" min-width:20px;" width="20" height="20"
                                        src="/assets/images/whats-logo.png" /></div>
                                <div>
                                    <h5 class="text-dark mb-0 font-14 font-weight-medium">{{ element.nome }}</h5>
                                    <span class="text-muted font-12">{{ element.telefone }}</span>
                                </div>
                            </div>
                        </div>
                    </template>
                </draggable>
            </div>

            <!--Implantada -->
            <div class="col-lg-3 col-md-4 col-sm-6 card" style="background-color: #acd6be">
                <h3 style="color:white; margin-top:5px;">Implantada</h3>
                <br />
                <draggable @change="updateImplantada" ghost-class="moving-card" :list="implantada"
                    class="list-group pointer" group="a" item-key="name">
                    <template #item="{ element }">
                        <div @click="exibirDetalhes(element)" class="px-1 py-2 inside-card" style="">

                            <div v-if="element.status != 'Aguardando parcelas'" class="bg-white"
                                style="text-align: center">
                                <div class="mr-2" style="float: left;"><img alt="Avatar" class="table-avatar"
                                        style=" min-width:20px;" width="20" height="20"
                                        src="/assets/images/whats-logo.png" /></div>
                                <div>
                                    <h5 class="text-dark mb-0 font-14 font-weight-medium">{{ element.nome }}</h5>
                                    <span class="text-muted font-12">{{ element.telefone }}</span>
                                </div>
                            </div>

                            <!-- AGUARDANDO PARCELAS-->
                            <div v-if="element.status == 'Aguardando parcelas'" class="bg-white flex-box-2"
                                style="text-align: center">
                                <div class="mr-2" style="float: left;"><img alt="Avatar" class="table-avatar"
                                        style=" min-width:20px;" width="20" height="20"
                                        src="/assets/images/whats-logo.png" /></div>
                                <div class="pointer">
                                    <h5 class="text-dark mb-0 font-14 font-weight-medium">{{ element.nome }}</h5>
                                    <span class="text-muted font-12">{{ element.telefone }}</span>
                                </div>

                                <div @click="exibirDetalhes(element)" class="text-right" style="float: right"
                                    data-toggle="modal" data-target="#gerar-parcelas">
                                    <button style="float: right; border-radius: 10%" type="button"
                                        class="btn btn-outline-success btn-sm">
                                        <i class="fa fa-dollar-sign"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </draggable>
            </div>

            <!--Cancelada -->
            <div class="col-lg-3 col-md-4 col-sm-6 bg-danger card">
                <h3 style="color:white; margin-top:5px;">Cancelada</h3>
                <br />
                <draggable @change="updateCancelada" ghost-class="moving-card" :list="cancelada"
                    class="list-group pointer" group="a" item-key="name" data-toggle="modal"
                    data-target="#detalhes-usuario">
                    <template #item="{ element }">
                        <div @click="exibirDetalhes(element)" class="px-1 py-2 inside-card" style="">
                            <div class="bg-white" style="text-align: center">
                                <div class="mr-2" style="float: left;"><img alt="Avatar" class="table-avatar"
                                        style=" min-width:20px;" width="20" height="20"
                                        src="/assets/images/whats-logo.png" /></div>
                                <div>
                                    <h5 class="text-dark mb-0 font-14 font-weight-medium">{{ element.nome }}</h5>
                                    <span class="text-muted font-12">{{ element.telefone }}</span>
                                </div>
                            </div>
                        </div>
                    </template>
                </draggable>
            </div>

            <!-- Detalhes Usuário -->
            <div ref="modal" class="modal fade" id="detalhes-usuario" tabindex="-1" role="dialog"
                aria-labelledby="myModalLabel" aria-hidden="true">
                <form @submit.prevent>
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                    &times;
                                </button>
                                <h4>Nova indicação</h4>
                                <br />

                                <div class="form-group">
                                    <label for="email">Nome</label>
                                    <input readonly="true" class="form-control" id="email" type="text"
                                        v-model="contato_selecionado.nome" />
                                </div>

                                <div class="form-group">
                                    <label for="user_type">Possui CNPJ?</label>
                                    <input readonly="true" class="form-control" id="cnpj" type="text"
                                        :value="converterCNPJ(contato_selecionado.possui_cnpj)" />
                                </div>

                                <div class="form-group">
                                    <label for="user_type">Ocupação?</label>
                                    <input readonly="true" class="form-control" id="telefone" type="text"
                                        v-model="contato_selecionado.ocupacao" />
                                </div>

                                <div class="form-group">
                                    <label for="email">Telefone</label>
                                    <input readonly="true" class="form-control" id="telefone" type="text"
                                        v-model="contato_selecionado.telefone" />
                                </div>

                                <div class="form-group">
                                    <label for="user_type">Bairro</label>
                                    <input readonly="true" class="form-control" id="telefone" type="text"
                                        v-model="contato_selecionado.bairro" />
                                </div>

                                <div class="form-group">
                                    <label for="user_type">Possui Plano?</label>
                                    <input readonly="true" class="form-control" id="cnpj" type="text"
                                        :value="converterCNPJ(contato_selecionado.possui_plano)" />
                                </div>

                                <div class="form-group">
                                    <label for="user_type">Quantidade de vidas</label>
                                    <input readonly="true" class="form-control" id="senha" type="senha"
                                        v-model="contato_selecionado.qtd_vidas" autocomplete="senha" />
                                </div>

                                <div class="form-group">
                                    <label for="password">Complemento</label>
                                    <textarea readonly=" true" class="form-control" id="senha" type="senha"
                                        v-model="contato_selecionado.complemento" autocomplete="senha" rows="5"
                                        cols="40" />
                                </div>

                                <div class="form-group">
                                    <label for="user_type">Origem</label>
                                    <input readonly="true" class="form-control" id="senha" type="senha"
                                        v-model="contato_selecionado.origem" autocomplete="senha" />
                                </div>

                                <div class="form-group">
                                    <label>Observações Corretor</label>
                                    <textarea readonly="true" class="form-control" id="senha" type="senha"
                                        v-model="contato_selecionado.observacoes_corretor" autocomplete="senha" rows="5"
                                        cols="40" />
                                </div>

                                <div class="form-group">
                                    <label>Observações Admin</label>
                                    <textarea class="form-control" id="senha" type="senha"
                                        v-model="contato_selecionado.observacoes_admin" autocomplete="senha" rows="5"
                                        cols="40" />
                                </div>


                                <button class="btn btn-primary btn-flat" data-toggle="modal"
                                    data-target="#detalhes-usuario">
                                    Voltar
                                </button>

                                <button @click="addObservacoes" class="btn btn-success btn-flat text-md-end">
                                    Salvar observações
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Gerar parcelas -->
            <div ref="modal" class="modal fade" id="gerar-parcelas" tabindex="-1" role="dialog"
                aria-labelledby="myModalLabel" aria-hidden="true">
                <form @submit.prevent>
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                    &times;
                                </button>
                                <h4>Gerar parcelas</h4>
                                <br />

                                <div class="form-group">
                                    <label for="email">Data vencimento</label>
                                    <input class="form-control" type="date" v-model="data_vencimento" />
                                </div>

                                <button class="btn btn-primary btn-flat" data-toggle="modal"
                                    data-target="#detalhes-usuario">
                                    Fechar
                                </button>

                                <button @click="gerarParcelas" class="btn btn-success btn-flat text-md-end">
                                    Gerar parcelas
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</template>
  
<script>
import draggable from "vuedraggable";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
import Swal from "sweetalert2/dist/sweetalert2.js";
import sweetAlert from "../controller/sweetAlert";
import moment from "moment";

export default {
    props: ['contatos', 'planos'],
    components: {
        draggable, Loading
    },
    mixins: [sweetAlert, Swal],
    data() {
        return {
            aguardando_aprovacao: [],
            pendencia: [],
            aguardando_vigencia: [],
            implantada: [],
            cancelada: [],
            isLoading: false,
            contato_selecionado: "",
            proposta_selecionada: "",
            data_vencimento: ""
        };
    },

    mounted() {
        this.inicializarListas();
    },

    methods: {

        inicializarListas() {
            for (let i = 0; i < this.contatos.length; i++) {
                if (this.contatos[i].contato.status == "Aguardando aprovação") {
                    this.aguardando_aprovacao.push(this.contatos[i].contato);
                } else if (this.contatos[i].contato.status == "Pendência" || this.contatos[i].status == "Pendências aguardando" || this.contatos[i].status == "Pendências corrigidas") {
                    this.pendencia.push(this.contatos[i].contato);
                } else if (this.contatos[i].contato.status == "Aguardando vigência") {
                    this.aguardando_vigencia.push(this.contatos[i].contato);
                } else if (this.contatos[i].contato.status == "Implantada" || this.contatos[i].contato.status == "Aguardando parcelas") {
                    this.implantada.push(this.contatos[i].contato);
                } else if (this.contatos[i].contato.status == "Cancelada") {
                    this.cancelada.push(this.contatos[i].contato);
                }
            }
        },

        findPlano(id) {
            let plano = this.planos.findIndex(element => element['id'] == id);

            return plano;
        },

        findContato(id) {
            let contato = this.contatos.findIndex(element => element.contato['id'] == id);

            return contato;
        },

        gerarParcelas() {

            let plano_selecionado = this.planos[this.findPlano(this.proposta_selecionada.plano_id)];

            let comissionamento = this.validateJSON(plano_selecionado.comissionamento);

            let ultima_data_vencimento = new Date(this.data_vencimento);

            //ADICIONANDO 1 DIA DEVIDO AO BUG NA INTERFACE QUE TRAZ UM DIA A MENOS
            ultima_data_vencimento.setDate(ultima_data_vencimento.getDate() + 1);

            let parcelas = [];

            for (let i = 0; i < comissionamento.length; i++) {

                data_vencimento = this.formatSelectedDate(data_vencimento);

                let valor = (Number(comissionamento[i].comissao) / 100) * Number(this.proposta_selecionada.valor_proposta);

                parcelas.push({
                    valor: valor,
                    data_vencimento: data_vencimento,
                    proposta_id: this.proposta_selecionada.id,
                    numero_parcela: i + 1
                });

                let data_vencimento = ultima_data_vencimento;

                data_vencimento.setMonth(data_vencimento.getMonth() + 1);

                ultima_data_vencimento = new Date(data_vencimento);
            }

            console.log("Parcelas");
            console.log(parcelas);

            let data = {
                parcelas: parcelas,
                contato_id: this.contato_selecionado.id,
                proposta_id: this.proposta_selecionada.id,
            }

            axios
                .post(`/admin/parcelas/store`, data)
                .then((response) => {
                    this.showSuccessMessage("Parcelas geradas!");
                    window.location.reload();
                })
                .catch((error) => {
                    this.showErrorMessageWithButton("Ops..", error);
                    console.log(error);
                });

        },

        descreverPendencias(element) {
            let html = "Descreva as <b style='color:red'>pendências </b> a serem resolvidas:";

            this.$swal
                .fire({
                    title: "Confirmação",
                    html: html,
                    icon: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Confirmar",
                    input: 'textarea',
                    inputAttributes: {
                        autocapitalize: 'off',
                        required: false
                    },
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Descreva as pendências!'
                        }
                        else {
                            this.addPendencias(value)
                        }
                    }
                })
        },

        addPendencias(pendencias) {
            this.isLoading = true;

            let data = {
                contato_id: this.contato_selecionado.id,
                descricao_pendencia: pendencias,
                proposta_id: this.contato_selecionado.proposta_id
            }

            console.log(data);

            axios
                .post(`/admin/proposta/add-pendencias`, data)
                .then((response) => {
                    this.showSuccessMessage("Pendências registradas!");
                    window.location.reload();
                })
                .catch((error) => {
                    this.showErrorMessageWithButton("Ops..", error);
                    console.log(error);
                });
        },

        addObservacoes() {
            this.isLoading = true;

            let data = {
                id: this.contato_selecionado.id,
                observacoes_admin: this.contato_selecionado.observacoes_admin
            }

            axios
                .post(`/admin/contato/add-observacoes`, data)
                .then((response) => {
                    this.showSuccessMessage("Observações adicionadas!");
                    this.isLoading = false;
                })
                .catch((error) => {
                    this.showErrorMessageWithButton("Ops..", error);
                    console.log(error);
                });
        },

        atualizarContato(element, status, proposta_id, refresh) {
            if (element.status == "Implantada") {
                this.$swal
                    .fire({
                        title: "Ops...",
                        text: "Essa proposta já foi implantada!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ok"
                    })
                    .then(result => {
                        window.location.reload();
                    });

            } else {
                this.isLoading = true;

                let data = {
                    id: element.id,
                    status: status,
                    proposta_id: proposta_id
                }

                axios
                    .post(`/admin/contato/update-status`, data)
                    .then((response) => {
                        this.showSuccessMessage("Contato atualizado!");
                        if (refresh) {
                            window.location.reload();
                        } else {
                            this.isLoading = false;
                        }
                    })
                    .catch((error) => {
                        this.showErrorMessageWithButton("Ops..", error);
                        console.log(error);
                    });
            }

        },

        updatePendencia: function (evt) {
            if (evt.added) {
                evt.added.element.status = "Pendência";
                this.atualizarContato(evt.added.element, "Pendência", evt.added.element.proposta_id);
            }
        },

        updateAguarandoAprovacao: function (evt) {
            if (evt.added) {
                this.atualizarContato(evt.added.element, "Aguardando aprovação", evt.added.element.proposta_id);
            }
        },

        updateAguardandoVigencia: function (evt) {
            if (evt.added) {
                this.atualizarContato(evt.added.element, "Aguardando vigência", evt.added.element.proposta_id);
            }
        },

        updateImplantada: function (evt) {
            if (evt.added) {
                this.atualizarContato(evt.added.element, "Aguardando parcelas", evt.added.element.proposta_id, true);
            }
        },

        updateCancelada: function (evt) {
            if (evt.added) {
                this.atualizarContato(evt.added.element, "Cancelada", evt.added.element.proposta_id);
            }
        },

        exibirDetalhes(element) {
            this.contato_selecionado = element;

            let indice = this.findContato(element.id);

            this.proposta_selecionada = this.contatos[indice].proposta;
        },

        converterCNPJ(possui_cnpj) {
            if (possui_cnpj == 0) {
                return "Não";
            } else {
                return "Sim";
            }
        },

        validateJSON(json) {
            console.log("VALIDANDO JSON");

            try {
                let jsonValido = JSON.parse(json);

                console.log("JS");
                console.log(jsonValido);

                if (jsonValido) {
                    return jsonValido;
                } else {
                    return null;
                }
            } catch (e) {
                return null;
            }
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
    }
};
</script>
<style scoped>
.kanban-board {
    width: auto;
    height: auto;
    overflow-x: scroll;
    overflow-y: hidden;
    white-space: nowrap;
}

.pointer {
    cursor: pointer;
}

.inside-card {
    margin: 2%;
    border-color: black;
    border-radius: 5%;
    border-width: 1px;
    border-style: solid;
    background-color: white;
    text-align: center;
}

.card {
    border-color: black;
    border-radius: 5%;
    border-width: 1px;
    border-radius: 3%;
    text-align: center;
    padding-bottom: 3%;
    display: inline-block;
    vertical-align: top;
}

.text-inside-card {
    text-align: center;
}

.flex-box {
    display: flex;
    justify-content: center;
}

.flex-box-2 {
    display: flex;
    justify-content: space-between;
}
</style>
