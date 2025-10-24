<template>
    <div>
        <loading v-model:active="isLoading" :can-cancel="false" :is-full-page="true" />
        <div class="card-body p-0">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 25%">Nome</th>
                        <th style="width: 25%">Plano</th>
                        <th style="width: 25%">Valor</th>
                        <th style="width: 25%">Detalhes</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="proposta in propostas" :key="plano">
                        <td data-label="Nome">{{ proposta.proposta.nome_titular }}</td>

                        <td data-label="Plano">{{ proposta.proposta.plano }}</td>

                        <td data-label="Valor">R${{ proposta.proposta.valor_proposta }}</td>

                        <td>
                            <button data-toggle="modal" style="border-radius:7%" data-target="#detalhes-modal"
                                @click="show(proposta)" type="button" class="btn btn-primary btn-sm">
                                Detalhes
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
                            <h4>Detalhes da Proposta</h4>

                            <br /><br />
                            <h4>Parcelas</h4>

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="width: 25%">Valor</th>
                                        <th style="width: 25%">Data de vencimento</th>
                                        <th style="width: 25%">Status</th>
                                        <th style="width: 25%">Confirmar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="parcela in proposta_selecionada.parcelas" :key="parcela">
                                        <td data-label="Nome">R${{ parcela.valor }}</td>

                                        <td data-label="Plano">{{ formatDate(parcela.data_vencimento) }}</td>

                                        <td data-label="Valor">{{ parcela.status }}</td>

                                        <td>
                                            <button data-toggle="modal" data-target="#detalhes-modal"
                                                @click="confirmarEscolha(parcela)" type="button"
                                                class="btn btn-outline-success btn-sm">
                                                Confirmar
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <br />
                            <h4>Documentos</h4>

                            <button type="button" style="width:100%; border-color: #005eff; color: #005eff"
                                class="btn btn-outline btn-sm">
                                PDF da Proposta
                            </button>

                            <button type="button" style="width:100%; border-color: #005eff; color: #005eff"
                                class="btn btn-outline-success btn-sm">
                                Comprovante de Residência
                            </button>

                            <button type="button" style="width:100%; border-color: #005eff; color: #005eff"
                                class="btn btn-outline-success btn-sm">
                                Comprovante de Vínculo
                            </button>

                            <br /><br />
                            <h4>Ações</h4>

                            <button type="button" style="width:100%;" class="btn btn-outline-danger btn-sm">
                                Estornar
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
import Loading from "vue-loading-overlay";
import moment from "moment";
import "vue-loading-overlay/dist/vue-loading.css";

export default {

    props: ['propostas'],

    mixins: [sweetAlert, Swal, AuxController],

    components: [Loading],

    data() {
        return {
            isLoading: false,
            plano_selecionado: "",
            aux_origens: "",
            comissionamento: [],
            parcelas: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29],
            imagem_selecionada: "",
            proposta_selecionada: "",
        };
    },

    mounted() {
        console.log(this.propostas);
    },

    methods: {

        confirmarEscolha(parcela) {

            let html = "Deseja confirmar a comissão no valor de <b style='color:green'> " + parcela.valor + "</b>";

            this.$swal
                .fire({
                    title: "Confirmação",
                    html: html,
                    icon: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Confirmar",
                })
                .then(result => {
                    if (result.isConfirmed) {
                        this.confirmar(parcela);
                    }
                });

        },


        confirmar(parcela) {

            let data = {
                id: parcela.id,
                status: "A receber"
            }

            parcela.status = "A receber";

            axios
                .post(`/admin/parcela/update`, data)
                .then((response) => {
                    this.showSuccessMessage("Valor confirmado!");

                })
                .catch((error) => {
                    this.showErrorMessageWithButton("Ops..", error.response.data);
                    console.log(error.response.data);
                });
        },

        showPDF() {
            this.imagem_selecionada = "/assets/images" + this.proposta_selecionada.proposta.pdf_proposta;
        },

        show(proposta) {
            this.proposta_selecionada = proposta;
        },

        extrairParcelas(json) {

            let comissionamento = [];
            comissionamento.push({ comissao: 0 });

            try {
                let jsonValido = JSON.parse(json);

                if (jsonValido) {
                    return jsonValido;
                } else {
                    return comissionamento;
                }
            } catch (e) {
                //console.log(e);
                return comissionamento;
            }
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
                .post(`/admin/plano/delete`, data)
                .then((response) => {

                    this.showSuccessMessage("Plano deletado!");

                    window.location.reload();

                })
                .catch((error) => {
                    this.showErrorMessageWithButton("Ops..", error.response.data);
                    console.log(error.response.data);
                });
        },

        findOrigem(id) {
            let origem = this.aux_origens.findIndex(element => element['id'] == id);
            return origem;
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
                id: this.plano_selecionado.id,
                nome: this.plano_selecionado.nome,
                categoria: this.plano_selecionado.categoria,
                qtd_parcelas: this.plano_selecionado.qtd_parcelas,
                comissao_longo_prazo: this.plano_selecionado.comissao_longo_prazo,
                imagem: this.plano_selecionado.imagem,
                comissionamento: this.comissionamento,
                tipo_comissao_longo_prazo: this.plano_selecionado.tipo_comissao_longo_prazo,
                tipo_plano: this.plano_selecionado.tipo_plano
            };

            axios
                .post(`/admin/plano/update`, data)
                .then((response) => {
                    this.showSuccessMessage("Plano atualizado!");

                    window.location.reload();
                })
                .catch((error) => {
                    this.showErrorMessageWithButton("Ops..", error);
                    console.log(error);
                });
        },

        formatDate(date) {
            return moment(date).format("DD/MM/YYYY");
        },
    },
};
</script>

<style scoped>
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

