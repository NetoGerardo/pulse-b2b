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
        <label>Selecione um Corretor</label>
        <select class="form-control" v-model="corretor_selecionado" @change="search()">
          <option value=""></option>
          <option v-for="corretor in corretores" :key="corretor" :value="corretor.id">
            {{ corretor.name }}
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
          <h1 class="font-light text-white"> {{ aux_leads.length }}</h1>
          <h6 class="text-white">Total Contatos</h6>
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

  <div class="table-responsive">
    <table id="zero_config" class="table table-striped table-bordered no-wrap">
      <thead>
        <tr>
          <th>Nome</th>
          <th>Aguardando</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="corretor in aux_corretores" :key="corretor" :value="corretor">
          <td>{{corretor.name}}</td>

          <td>{{corretor.LeadsCount}}</td>

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

  props: ['corretores'],

  data() {
    return {
      total: 0,
      avaliacoes_pendentes: 0,
      total_positivas: 0,
      total_negativas: 0,
      corretor_selecionado: "",
      dataInicio: "",
      dataFim: "",
      period: 0,
      isLoading: false,
      aux_leads: "",
      corretores_formatados: []
    };
  },

  mounted() {

    console.log(this.corretores);

    this.aux_corretores = this.corretores;

    //DEFININDO DATA DE HOJE
    this.dataInicio = new Date();
    this.dataInicio = this.formatSelectedDate(this.dataInicio);

    //DEFININDO DATA DE HOJE
    this.dataFim = new Date();
    this.dataFim = this.formatSelectedDate(this.dataFim);

    //this.calcularTotais();

  },

  methods: {

    calcularTotais() {

      this.corretores_formatados = []

      for (let i = 0; i < this.aux_corretores.length; i++) {

        let indice = this.findCorretor(this.aux_corretores[i].id_corretor);

        if (indice == -1) {
          let corretor = {
            id: this.aux_corretores[i].id_corretor,
            nome: this.aux_corretores[i].nome_corretor,
            aguardando: 0,
            positivas: 0,
            negativas: 0,
          }

          this.corretores_formatados.push(corretor);
          this.calcular(i, this.corretores_formatados.length - 1);

        } else {
          this.calcular(i, indice);
        }
      }
    },

    calcular(i, indice) {
      if (this.aux_corretores[i].data_avaliacao) {
        if (this.aux_corretores[i].avaliacao == 'POSITIVA') {
          this.corretores_formatados[indice].positivas = this.corretores_formatados[indice].positivas + 1;
        } else {
          this.corretores_formatados[indice].negativas = this.corretores_formatados[indice].negativas + 1;
        }
      } else {
        this.corretores_formatados[indice].aguardando = this.corretores_formatados[indice].aguardando + 1;
      }
    },

    findCorretor(id) {
      let corretor = this.corretores_formatados.findIndex(element => element['id'] == id);

      return corretor;
    },

    getUrl(telefone) {
      return "https://api.whatsapp.com/send?phone=" + telefone;
    },

    converterCNPJ(possui_cnpj) {
      if (possui_cnpj == 0) {
        return "Não";
      } else {
        return "Sim";
      }
    },

    formatDate(date) {
      return moment(date).format("DD/MM/YYYY HH:mm:ss");
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
      }

      console.log("Enviando");
      console.log(data);

      axios
        .post(`/admin/ranking/search`, data)
        .then((response) => {

          this.aux_corretores = response.data.corretores;
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