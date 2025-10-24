<template>
    <loading v-model:active="isLoading" :can-cancel="false" :is-full-page="true" />

    <!-- DATA 
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
                            <button v-on:click="search(0)" :class="
                                period === 0 ? 'button-select-date-focused' : 'button-select-date'
                            " id="dataFim">
                                Esse Mês
                            </button>
                        </div>
                    </div>
                    <div class="col_three">
                        <div class="input_field">
                            <button v-on:click="search(1)" :class="
                                period === 1 ? 'button-select-date-focused' : 'button-select-date'
                            " id="dataFim">
                                2 Meses
                            </button>
                        </div>
                    </div>
                    <div class="col_three">
                        <div class="input_field">
                            <button v-on:click="search(2)" :class="
                                period === 2 ? 'button-select-date-focused' : 'button-select-date'
                            " id="dataFim">
                                3 Meses
                            </button>
                        </div>
                    </div>
                </div>
                <button class="button" type="button" v-on:click="search(-1)">Buscar</button>
            </form>
        </div>
    </div>
    -->

    <!-- FILTROS -->
    <div class="row">
        <div class="col-sm-12 col-md-4">
            <div class="form-group">
                <label>Tipo de plano</label>
                <select class="form-control" v-model="tipo_plano" @change="search(-1)">
                    <option value=""></option>
                    <option value="empresarial_pme">Empresarial PME</option>
                    <option value="individual">Individual</option>
                    <option value="adesao">Adesão</option>
                </select>
            </div>
        </div>
    </div>

    <div class="table-responsive" v-if="!detalhes_proposta">
        <table id="zero_config" class="table table-striped table-bordered no-wrap">
            <thead>
                <tr>
                    <th>Parcela</th>
                    <th>Valor</th>
                    <th>Vencimento</th>
                    <th>Parcela Nº</th>
                    <th style="width:10%">Ações</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="parcela in aux_parcelas" :key="parcela" :value="parcela">
                    <td>{{ parcela.proposta.nome_titular }}</td>

                    <td>{{ parcela.parcela.valor }}</td>

                    <td data-label="Data">
                        {{ formatDate(parcela.parcela.data_vencimento) }}
                    </td>

                    <td>{{ parcela.parcela.numero_parcela }}</td>

                    <td>
                        <button @click="show(parcela)" class="btn btn-primary btn-flat">
                            Detalhes <i class="fa fa-search"></i>
                        </button>
                    </td>

                </tr>
            </tbody>
        </table>
    </div>

    <div v-if="detalhes_proposta">
        <detalhes-proposta :proposta="proposta_selecionada" :voltar="voltar" :planos="planos"
            :parcela="parcela_selecionada" :exibir_confirmar="true" />
    </div>

</template>
  
<script>
import wppController from "../controller/wppController";
import sweetAlert from "../controller/sweetAlert";
import Swal from "sweetalert2/dist/sweetalert2.js";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
import moment from "moment";

export default {
    mixins: [wppController, sweetAlert, Swal],

    components: { Loading },

    props: ['parcelas', 'status', 'planos'],

    data() {
        return {
            isLoading: false,
            detalhes_proposta: false,
            dataInicio: "",
            dataFim: "",
            period: "",
            aux_parcelas: "",
            events: [],
            status_selecionado: "",
            parcela_selecionada: "",
            proposta_selecionada: "",
            tipo_plano: ""
        };
    },

    mounted() {

        //DEFININDO DATA DE HOJE
        this.dataInicio = new Date();
        this.dataInicio = this.formatSelectedDate(this.dataInicio);

        //DEFININDO DATA DE HOJE
        this.dataFim = new Date();
        this.dataFim = this.formatSelectedDate(this.dataFim);

        if (this.status == "pendente") {
            this.status_selecionado = "Pendente";
        } else if (this.status == "receber") {
            this.status_selecionado = "A receber";
        } else if (this.status == "recebido") {
            this.status_selecionado = "Recebido";
        } else if (this.status == "estornado") {
            this.status_selecionado = "Estornado";
        }

        this.aux_parcelas = this.parcelas;
    },

    methods: {

        show(parcela) {
            this.proposta_selecionada = parcela.proposta;
            this.parcela_selecionada = parcela.parcela;
            this.detalhes_proposta = true;
        },

        voltar() {
            this.detalhes_proposta = false;
        },

        search(number) {
            this.isLoading = true;

            this.period = number;

            if (this.period >= 0) {

                //DEFININDO INÍCIO DO MÊS
                this.dataInicio = new Date();
                this.dataInicio = new Date(this.dataInicio.getFullYear(), this.dataInicio.getMonth(), 1);
                this.dataInicio = this.formatSelectedDate(this.dataInicio);

                //DEFININDO DATA FIM
                this.dataFim = new Date();

                //ADICIONANDO MESES
                this.dataFim = new Date(this.dataFim.setMonth(this.dataFim.getMonth() + this.period));

                //PEGANDO ÚLTIMO DIA DO MÊS
                this.dataFim = new Date(this.dataFim.getFullYear(), this.dataFim.getMonth() + 1, 0);
                this.dataFim = this.formatSelectedDate(this.dataFim);

            }

            let aux_dataInicio = this.formatDateToSearch(this.dataInicio);
            let aux_dataFim = this.formatDateToSearchTime(this.dataFim);

            let data = {
                data_inicio: aux_dataInicio,
                data_fim: aux_dataFim,
                status: "A receber",
                tipo_plano: this.tipo_plano
            }

            console.log("Enviando");
            console.log(data);

            axios
                .post(`/admin/parcelas/search/status`, data)
                .then((response) => {

                    this.aux_parcelas = response.data.parcelas;
                    console.log(this.aux_parcelas);
                    this.isLoading = false;

                })
                .catch((error) => {
                    this.showErrorMessageWithButton("Ops..", error);
                    console.log(error);
                });
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