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

    <!-- FILTROS 
    <div class="row">
        <div class="col-sm-12 col-md-4">
            <div class="form-group">
                <label>Selecione um Status</label>
                <select class="form-control" v-model="status_selecionado" @change="search(-1)">
                    <option value=""></option>
                    <option v-for="status in status" :key="status" :value="status">
                        {{ status }}
                    </option>
                </select>
            </div>
        </div>
    </div>
    -->

    <!-- CALENDÁRIO -->
    <FullCalendar :options='calendarOptions'>
        <template v-slot:eventContent='arg' style="background-color: green; color: red">
            <b>{{ arg.timeText }}</b>
            <i>{{ arg.event.title }}</i>
        </template>
    </FullCalendar>

</template>
  
<script>
import wppController from "../controller/wppController";
import sweetAlert from "../controller/sweetAlert";
import Swal from "sweetalert2/dist/sweetalert2.js";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
import moment from "moment";

import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import interactionPlugin from '@fullcalendar/interaction'

export default {
    mixins: [wppController, sweetAlert, Swal],

    components: { Loading, FullCalendar },

    props: ['parcelas', 'status'],

    data() {
        return {
            isLoading: false,
            calendarOptions: {
                plugins: [
                    dayGridPlugin,
                    timeGridPlugin,
                    interactionPlugin // needed for dateClick
                ],
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridDay'
                },
                initialView: 'dayGridMonth',
                initialEvents: this.events, // alternatively, use the `events` setting to fetch from a feed
                editable: true,
                selectable: true,
                selectMirror: true,
                dayMaxEvents: true,
                weekends: true,
                select: this.handleDateSelect,
                eventClick: this.handleEventClick,
                eventsSet: this.handleEvents
                /* you can update a remote database when these fire:
                eventAdd:
                eventChange:
                eventRemove:
                */
            },
            dataInicio: "",
            dataFim: "",
            period: "",
            aux_parcelas: "",
            events: [],
            status_selecionado: ""
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

        this.gerarEventos();
    },

    methods: {

        gerarEventos() {
            this.events = [];

            for (let i = 0; i < this.aux_parcelas.length; i++) {

                let color = "blue";

                if (this.aux_parcelas[i].status == "Pendente") {
                    color = "#ffc107";
                } else if (this.aux_parcelas[i].status == "A receber") {
                    color = "blue";
                } else if (this.aux_parcelas[i].status == "Recebido") {
                    color = "green";
                } else if (this.aux_parcelas[i].status == "Estornado") {
                    color = "#dc3545";
                }

                this.events.push({
                    id: this.aux_parcelas[i].id,
                    title: this.aux_parcelas[i].status,
                    start: this.aux_parcelas[i].data_vencimento,
                    nome: this.aux_parcelas[i].nome,
                    valor: this.aux_parcelas[i].valor,
                    whatsapp: this.aux_parcelas[i].whatsapp,
                    backgroundColor: color,
                    borderColor: color
                });
            }

            this.calendarOptions.events = this.events;
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
                status: this.status
            }

            console.log("Enviando");
            console.log(data);

            axios
                .post(`/admin/financeiro/search`, data)
                .then((response) => {

                    this.aux_parcelas = response.data.parcelas;
                    this.isLoading = false;

                    this.gerarEventos();

                })
                .catch((error) => {
                    this.showErrorMessageWithButton("Ops..", error);
                    console.log(error);
                });
        },

        handleDateSelect(selectInfo) {
            let calendarApi = selectInfo.view.calendar
            calendarApi.changeView('timeGridDay');

            calendarApi.gotoDate(selectInfo.startStr);

            console.log(calendarApi);
        },

        handleEventClick(evt) {
            this.exibirDetalhes(evt.event.extendedProps['nome'], evt.event.extendedProps['valor'], evt.event.extendedProps['whatsapp'], evt.event.id);
        },

        exibirDetalhes(nome, valor, whatsapp, id) {
            this.$swal.fire({
                title: 'Detalhes',
                showCancelButton: true,
                html:
                    '<label for="nome">Nome</label>' +
                    '<input readonly="true" id="swal-input1" class="form-control" value="' + nome + '">' +
                    '<br/>' +
                    '<label for="email">Valor</label>' +
                    '<input readonly="true" id="swal-input2" class="form-control" value="' + valor + '">' +
                    '<br/>' +
                    '<label for="telefone">WhatsApp</label>' +
                    '<input readonly="true" id="swal-input2" class="form-control" value="' + whatsapp + '">' +
                    '<br/>',
                focusConfirm: false,
                cancelButtonColor: "#d33",
                confirmButtonText: "Confirmar!",
                preConfirm: () => {
                    return [
                        document.getElementById('swal-input1').value,
                        document.getElementById('swal-input2').value,
                    ]
                }
            }).then((result) => {
                if (result.value) {
                    this.confirmarRecebimento(nome, valor, whatsapp, id);
                }
            })

        },

        confirmarRecebimento(nome, valor, whatsapp, id) {
            this.$swal
                .fire({
                    title: "Confirmação",
                    html: "Deseja cofirmar o recebimento de <b style='color:green'> R$" + valor + "</b> ?</b>",
                    icon: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#4caf50",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sim!"
                })
                .then(result => {
                    if (result.value) {
                        let data = {
                            id: id,
                            status: "A receber",
                        };

                        axios
                            .post(`/admin/parcelas/update`, data)
                            .then((response) => {

                                this.showSuccessMessage("Parcela confirmada!");

                                window.location.reload();
                            })
                            .catch((error) => {
                                this.showErrorMessageWithButton("Ops..", error);
                                console.log(error);
                            });
                    }
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