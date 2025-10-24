<template>
  <loading v-model:active="isLoading" :can-cancel="false" :is-full-page="true" />

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
              <button v-on:click="search(1)" :class="
                period === 1 ? 'button-select-date-focused' : 'button-select-date'
              " id="dataFim">
                Hoje
              </button>
            </div>
          </div>
          <div class="col_three">
            <div class="input_field">
              <button v-on:click="search(7)" :class="
                period === 7 ? 'button-select-date-focused' : 'button-select-date'
              " id="dataFim">
                7 dias
              </button>
            </div>
          </div>
          <div class="col_three">
            <div class="input_field">
              <button v-on:click="search(15)" :class="
                period === 15 ? 'button-select-date-focused' : 'button-select-date'
              " id="dataFim">
                15 dias
              </button>
            </div>
          </div>
        </div>
        <button class="button" type="button" v-on:click="search(0)">Buscar</button>
      </form>
    </div>
  </div>

  <div class="row" style="padding: 10px 10px 10px 10px">

    <div class="col-sm-12 col-md-3">
      <div class="form-group">
        <label>Supervisor</label>
        <select class="custom-select" id="inputGroupSelect04" v-model="supervisor_selecionado"
          @change="buscarCorretores()">
          <option v-for="supervisor in supervisores" :key="supervisor" :value="supervisor">
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
        <label>Origem da Indicação</label>
        <select class="form-control" v-model="origem_selecionada" @change="search()">
          <option value=""></option>
          <option v-for="origem in origens" :key="origem" :value="origem">
            {{ origem }}
          </option>
        </select>
      </div>
    </div>
  </div>
  <div class="row">
    <!-- Column -->
    <div class="col-md-6 col-lg-3 col-xlg-3">
      <div class="card card-hover">
        <div class="p-2 bg-primary text-center">
          <h1 class="font-light text-white"> {{ total }}</h1>
          <h6 class="text-white">Total Indicações</h6>
        </div>
      </div>
    </div>
    <!-- Column -->
    <div class="col-md-6 col-lg-3 col-xlg-3">
      <div class="card card-hover">
        <div class="p-2 bg-cyan text-center">
          <h1 class="font-light text-white">{{ avaliacoes_pendentes }}</h1>
          <h6 class="text-white">Avaliações Pendentes</h6>
        </div>
      </div>
    </div>
    <!-- Column -->
    <div class="col-md-6 col-lg-3 col-xlg-3">
      <div class="card card-hover">
        <div class="p-2 bg-success text-center">
          <h1 class="font-light text-white">{{ total_positivas }}</h1>
          <h6 class="text-white">Positivas</h6>
        </div>
      </div>
    </div>
    <!-- Column -->
    <div class="col-md-6 col-lg-3 col-xlg-3">
      <div class="card card-hover">
        <div class="p-2 bg-danger text-center">
          <h1 class="font-light text-white">{{ total_negativas }}</h1>
          <h6 class="text-white">Negativas</h6>
        </div>
      </div>
    </div>
    <!-- Column -->
  </div>
  <h3 v-if="exibirDetalhes">Detalhamento </h3>


  <div v-if="exibirDetalhes" class="row">

    <!-- Column -->
    <div class="col-md-6 col-lg-3 col-xlg-3">
      <div class="card card-hover">
        <div class="p-2 bg-success text-center">
          <h1 class="font-light text-white">{{ total_em_negociacao }}</h1>
          <h6 class="text-white">Em negociação</h6>
        </div>
      </div>
    </div>
    <!-- Column -->
    <div class="col-md-6 col-lg-3 col-xlg-3">
      <div class="card card-hover">
        <div class="p-2 bg-success text-center">
          <h1 class="font-light text-white">{{ total_cotacao_enviada }}</h1>
          <h6 class="text-white">Cotação enviada</h6>
        </div>
      </div>
    </div>
  </div>
  <div v-if="exibirDetalhes" class="row">
    <div class="col-md-6 col-lg-3 col-xlg-3">
      <div class="card card-hover">
        <div class="p-2 bg-danger text-center">
          <h1 class="font-light text-white">{{ total_sem_contato }}</h1>
          <h6 class="text-white">Sem contato</h6>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3 col-xlg-3">
      <div class="card card-hover">
        <div class="p-2 bg-danger text-center">
          <h1 class="font-light text-white">{{ total_sem_interesse }}</h1>
          <h6 class="text-white">Sem interesse</h6>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3 col-xlg-3">
      <div class="card card-hover">
        <div class="p-2 bg-danger text-center">
          <h1 class="font-light text-white">{{ total_ja_esta_sendo_atendido }}</h1>
          <h6 class="text-white">Já está sendo atendido</h6>
        </div>
      </div>
    </div>
  </div>

  <a v-if="!exibirDetalhes" href="#" @click="exibirDetalhes = !exibirDetalhes"> Exibir detalhes</a>
  <a v-if="exibirDetalhes" href="#" @click="exibirDetalhes = !exibirDetalhes"> Resumo</a>

  <div class="table-responsive" v-if="!exibirDetalhes">
    <table id="zero_config" class="table table-striped table-bordered no-wrap">
      <thead>
        <tr>
          <th>Origem</th>
          <th>Indicações</th>
          <th>Positivas</th>
          <th>Negativas</th>
          <th>Aguardando</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="lead in aux_leads" :key="lead" :value="lead">

          <td>{{ lead.origem_lead }}</td>

          <td>{{ lead.quantidade }}</td>

          <td>{{ lead.positivas }}</td>

          <td>{{ lead.negativas }}</td>

          <td>{{ lead.aguardando }}</td>

        </tr>
      </tbody>
    </table>
  </div>

  <div class="table-responsive" v-if="exibirDetalhes">
    <table id="zero_config" class="table table-striped table-bordered no-wrap">
      <thead>
        <tr>
          <th>Origem</th>
          <th>Indicações</th>
          <th>Cotação enviada</th>
          <th>Em negociação</th>
          <th>Sem contato</th>
          <th>Sem interesse</th>
          <th>Já está sendo atendido</th>
          <th>Aguardando</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="lead in aux_leads" :key="lead" :value="lead">

          <td>{{ lead.origem_lead }}</td>

          <td>{{ lead.quantidade }}</td>

          <td>{{ lead.cotacao_enviada }}</td>

          <td>{{ lead.em_negociacao }}</td>

          <td>{{ lead.sem_contato }}</td>

          <td>{{ lead.sem_interesse }}</td>

          <td>{{ lead.ja_esta_sendo_atendido }}</td>

          <td>{{ lead.aguardando }}</td>

        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import sweetAlert from "../controller/sweetAlert";
import moment from "moment";
import Swal from "sweetalert2/dist/sweetalert2.js";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";

export default {
  mixins: [sweetAlert, Swal],

  components: { Loading },

  props: ['leads', 'supervisores'],

  data() {
    return {
      total: 0,
      avaliacoes_pendentes: 0,
      total_positivas: 0,
      total_negativas: 0,
      corretor_selecionado: "",
      supervisor_selecionado: "",
      dataInicio: "",
      dataFim: "",
      period: 0,
      isLoading: false,
      aux_leads: "",
      status_indicacao: "",
      exibirDetalhes: false,
      total_em_negociacao: 0,
      total_cotacao_enviada: 0,
      total_sem_contato: 0,
      total_sem_interesse: 0,
      total_ja_esta_sendo_atendido: 0,
      origem_selecionada: "",
      origens: [],
      ultima_data_inicio: "",
      ultima_data_fim: ""
    };
  },

  mounted() {

    this.aux_leads = this.leads;

    //DEFININDO DATA DE HOJE
    this.dataInicio = new Date();
    this.dataInicio = this.formatSelectedDate(this.dataInicio);

    //DEFININDO DATA DE HOJE
    this.dataFim = new Date();
    this.dataFim = this.formatSelectedDate(this.dataFim);

    this.calcularTotais();
    this.popularOrigens();

  },

  methods: {

    popularOrigens() {

      this.origens = [];

      for (let i = 0; i < this.aux_leads.length; i++) {
        if (!this.origens.includes(this.aux_leads[i].origem_lead)) {
          this.origens.push(this.aux_leads[i].origem_lead);
        }
      }

    },

    buscarCorretores() {
      this.isLoading = true;

      let data = {
        supervisor_id: this.supervisor_selecionado.id,
      }

      console.log(data);

      axios
        .post(`/admin/corretores/search`, data)
        .then((response) => {

          this.isLoading = false;
          this.corretores = response.data.corretores;

        })
        .catch((error) => {
          this.showErrorMessageWithButton("Ops..", error);
          console.log(error);
        });
    },

    calcularTotais() {

      this.total = 0;
      this.avaliacoes_pendentes = 0;
      this.total_positivas = 0;
      this.total_negativas = 0;
      this.total_em_negociacao = 0;
      this.total_cotacao_enviada = 0;
      this.total_sem_contato = 0;
      this.total_sem_interesse = 0;
      this.total_ja_esta_sendo_atendido = 0;

      for (let i = 0; i < this.aux_leads.length; i++) {

        this.total = this.total + Number(this.aux_leads[i].quantidade);
        this.total_positivas = this.total_positivas + Number(this.aux_leads[i].positivas);
        this.total_negativas = this.total_negativas + Number(this.aux_leads[i].negativas);
        this.avaliacoes_pendentes = this.avaliacoes_pendentes + Number(this.aux_leads[i].aguardando);

        this.total_em_negociacao = this.total_em_negociacao + Number(this.aux_leads[i].em_negociacao);
        this.total_cotacao_enviada = this.total_cotacao_enviada + Number(this.aux_leads[i].cotacao_enviada);
        this.total_sem_contato = this.total_sem_contato + Number(this.aux_leads[i].sem_contato);
        this.total_sem_interesse = this.total_sem_interesse + Number(this.aux_leads[i].sem_interesse);
        this.total_ja_esta_sendo_atendido = this.total_ja_esta_sendo_atendido + Number(this.aux_leads[i].ja_esta_sendo_atendido);
      }
    },

    confirmarNegativo(lead) {
      let html = "Acrescente um comentário à sua avaliação <b style='color:red'> Negativa?</b>";

      this.$swal
        .fire({
          title: "Confirmação",
          html: html,
          icon: "info",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Confirmar",
          input: 'text',
          inputAttributes: {
            autocapitalize: 'off',
            required: false
          },
          inputValidator: (value) => {
            if (!value) {
              return 'Coloque uma justificativa!'
            }
            else {
              this.confirmar(lead, value, "NEGATIVA")
            }
          }
        })
    },

    confirmarPositivo(lead) {
      let html = "Que bom! Deseja acrescentar algum comentário à sua avaliação <b style='color:green'> Positiva?</b>";

      this.$swal
        .fire({
          title: "Confirmação",
          html: html,
          icon: "info",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Confirmar",
          input: 'text',
          inputAttributes: {
            autocapitalize: 'off',
          },
        })
        .then(result => {
          if (result.isConfirmed) {
            this.confirmar(lead, result.value, "POSITIVA");
          }
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
        corretor_id: this.corretor_selecionado,
        status_indicacao: this.status_indicacao,
        origem_lead: this.origem_selecionada
      }

      axios
        .post(`/admin/relatorio/origens/search`, data)
        .then((response) => {

          this.aux_leads = response.data.leads;

          this.calcularTotais();

          if (this.ultima_data_inicio != this.dataInicio || this.ultima_data_fim != this.dataFim) {
            console.log("Entrou no IF");
            this.popularOrigens();
          }

          this.ultima_data_inicio = this.dataInicio;
          this.ultima_data_fim = this.dataFim;

          this.isLoading = false;

        })
        .catch((error) => {
          this.showErrorMessageWithButton("Ops..", error.response.data);
          console.log(error.response.data);
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