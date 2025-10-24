<template>
  <loading v-model:active="isLoading" :can-cancel="false" :is-full-page="true" />

  <div class="row">
    <br />
    <div class="col-12 col-sm-12 col-md-12">
      <div class="form-group">
        <form v-on:submit.prevent="buscarLeads()">
          <label for="user_type">Nome/Telefone</label>
          <div class="input-group mb-3">
            <!-- /btn-group -->
            <input v-model="search" type="text" class="form-control" />

            <div class="input-group-prepend">
              <button @click="buscarLeads()" style="border-radius: 2px" type="button" class="btn btn-info">
                <i class="fa fa-search"></i>
              </button>
              <button @click="(search = ''), buscarLeads()" style="border-radius: 2px" type="button" class="btn btn-default">
                <i class="fa fa-trash"></i>
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div style="padding: 0px 0px 10px 0px; color: red" v-if="searchBuscado">Exibindo resultados para "{{ search }}"</div>
  <br />

  <button @click="exibirLista('nao_contactados')" type="button" class="btn btn-success btn-sm">Mensagem não enviada</button>

  <button @click="exibirLista('todos')" type="button" class="btn btn-secondary btn-sm">Todos</button>

  <button @click="exibirLista('contactados')" type="button" class="btn btn-info btn-sm">Mensagem enviada</button>

  <div class="card-tools">
    <!-- PAGINAÇÃO  -->
    <ul class="pagination pagination-sm m-0 float-right">
      <li class="page-item"><a class="page-link" href="#" @click="selecionarPagina(1)">&Lt;</a></li>
      <li class="page-item"><a class="page-link" href="#" @click="selecionarPagina(pagina_atual - 1)">&lt;</a></li>

      <li v-for="pagina in paginas" :key="pagina" :value="pagina" class="page-item">
        <a :class="pagina === pagina_atual ? 'page-link current-page' : 'stopped'" class="page-link" href="#" @click="selecionarPagina(pagina)">
          {{ pagina }}
        </a>
      </li>

      <li class="page-item">
        <a class="page-link" href="#" @click="selecionarPagina(pagina_atual + 1)">&gt;</a>
      </li>
      <li class="page-item"><a class="page-link" href="#" @click="selecionarPagina(max_page)">&Gt;</a></li>
    </ul>
  </div>

  <div class="table-responsive">
    <table id="zero_config" class="table table-striped table-bordered no-wrap">
      <thead>
        <tr>
          <th>Nome</th>
          <th>WhatsApp</th>
          <th>CNPJ?</th>
          <th>Data</th>
          <th>Bairro</th>
          <th>Ocupação</th>
          <th>Interessado?</th>
          <th>Mensagem enviada?</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="lead in aux_leads" :key="lead.id">
          <td data-label="Nome">{{ truncarTexto(lead.nome, 25) }}</td>
          <td data-label="WhatsApp">
            <a :href="getUrl(lead.telefone)" class="float" target="_blank">
              <img alt="WhatsApp" class="table-avatar" style="width: 20px; opacity: 0.8" src="/assets/images/whats-logo.png" />
            </a>
            {{ lead.telefone }}
          </td>
          <td data-label="CNPJ?">{{ converterCNPJ(lead.possui_cnpj) }}</td>
          <td data-label="Data">{{ formatDate(lead.created_at) }}</td>
          <td data-label="Bairro">{{ lead.bairro }}</td>
          <td data-label="Ocupação">{{ lead.ocupacao }}</td>

          <td data-label="Interessado?">
            <div v-if="!lead.data_avaliacao" class="d-flex">
              <button style="flex: 1; margin-right: 5px" @click="confirmarPositivo(lead)" type="button" class="btn btn-success btn-sm">Sim</button>
              <button style="flex: 1" @click="confirmarNegativo(lead)" type="button" class="btn btn-danger btn-sm">Não</button>
            </div>
            <span v-else>{{ lead.interessado ? "Sim" : "Não" }}</span>
          </td>
          <td data-label="Mensagem enviada?">
            <button v-if="lead.contactado == 0" style="width: 100%" @click="confirmarContactado(lead)" type="button" class="btn btn-info btn-sm">
              Sim
            </button>
            <span v-if="lead.contactado == 1" style="color: green; font-size: 15px" class="badge badge-light-info">Enviada</span>
          </td>
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

  props: [],

  components: { Loading },

  data() {
    return {
      total_cadastrados: 0,
      indicacoes_recebidas: 0,
      total_positivas: 0,
      total_negativas: 0,
      aux_leads: [],
      aux_leads_contactados: [],
      aux_leads_nao_contactados: [],
      leads: [],
      searchBuscado: false,
      isLoading: false,
      search: "",
      inicio: 0,
      paginas: [],
      pagina_atual: 1,
      max_page: 0,
      qtd_por_pagina: 25
    };
  },

  mounted() {
    this.buscarLeads();
  },

  methods: {
    selecionarPagina(pagina) {
      if (pagina <= this.paginas[this.paginas.length - 1] && pagina > 0) {
        this.inicio = this.qtd_por_pagina * (pagina - 1);
        this.pagina_atual = pagina;
        this.buscarLeads();
      } else if (pagina == this.max_page && pagina > 0) {
        this.inicio = this.qtd_por_pagina * (pagina - 1);
        this.pagina_atual = pagina;
        this.buscarLeads();
      }
    },

    pagination(data) {
      this.paginas = [];

      let length = data.total / this.qtd_por_pagina;
      let i = 0;

      this.max_page = Math.ceil(length);

      //DEFININDO MÁXIMO E MÍNIMO DE PÁGINAS
      if (length < 0) {
        length = 0;
      } else if (length > 10) {
        if (this.pagina_atual >= 10) {
          let minimo = this.pagina_atual - 10;

          length = Math.ceil(length);

          if (length - this.pagina_atual >= 10) {
            length = this.pagina_atual + 10;
            i = minimo;
          } else {
            i = this.pagina_atual - 10 + (length - this.pagina_atual);
          }

          //CASO O CALCULO O MINIMO SEJA IGUAL A PAGINA ATUAL - 1, REDUZIR 10 DA PAGINA ATUAL CASO ELA SEJA MAIOR QUE 10
          if (i == this.pagina_atual - 1 && this.pagina_atual >= 10) {
            i = this.pagina_atual - 10;
          }

          console.log("I = " + i);
        } else {
          length = 10;
        }
      }

      for (i; i < length; i++) {
        this.paginas.push(i + 1);
      }
    },

    buscarLeads() {
      this.isLoading = true;

      let data = {
        inicio: this.inicio,
        tamanho: this.qtd_por_pagina,
        search: this.search,
      };

      axios
        .post(`/user/leads/search_lista`, data)
        .then((response) => {
          if (this.search && this.search != "") {
            this.searchBuscado = true;
          } else {
            this.searchBuscado = false;
          }

          this.isLoading = false;

          this.leads = response.data.leads;

          this.pagination(response.data);

          this.separarLeads();
        })
        .catch((error) => {
          this.showErrorMessageWithButton("Ops..", error.response.data);
          console.log(error.response.data);
        });
    },

    separarLeads() {
      this.aux_leads_contactados = [];
      this.aux_leads_nao_contactados = [];

      for (let i = 0; i < this.leads.length; i++) {
        if (this.leads[i].contactado == 1) {
          this.aux_leads_contactados.push(this.leads[i]);
        } else {
          this.aux_leads_nao_contactados.push(this.leads[i]);
        }

        if (this.leads[i].origem_lead == "CADASTRO MANUAL") {
          this.total_cadastrados++;
        } else {
          this.indicacoes_recebidas++;
        }

        if (this.leads[i].data_avaliacao) {
          if (this.leads[i].avaliacao == "POSITIVA") {
            this.total_positivas++;
          } else {
            this.total_negativas++;
          }
        }
      }

      this.aux_leads = this.aux_leads_nao_contactados;
    },

    truncarTexto(texto, tamanhoMaximo) {
      if (texto.length > tamanhoMaximo) {
        return texto.substring(0, tamanhoMaximo) + "...";
      }
      return texto;
    },

    exibirLista(tipo) {
      if (tipo == "todos") {
        this.aux_leads = this.leads;
      } else if (tipo == "contactados") {
        this.aux_leads = this.aux_leads_contactados;
      } else if (tipo == "nao_contactados") {
        this.aux_leads = this.aux_leads_nao_contactados;
      }
    },

    getUrl(telefone) {
      return "https://api.whatsapp.com/send?phone=" + this.limparTelefone(telefone);
    },

    limparTelefone(telefone) {
      telefone = telefone.replace(/([^\d])+/gim, "");

      //ADICIONANDO MANUALMENTE DDD
      if (telefone.length < 10) {
        telefone = "21" + telefone;
      }

      //ADICIONANDO CÓDIGO DO PAÍS
      if (telefone.substr(0, 2) != "55") {
        telefone = "55" + telefone;
      }

      return telefone;
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

    confirmarNegativo(lead) {
      let html = "Acrescente um comentário à sua avaliação <b style='color:red'> Negativa?</b>";

      this.$swal.fire({
        title: "Confirmação",
        html: html,
        icon: "info",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Confirmar",
        input: "text",
        inputAttributes: {
          autocapitalize: "off",
          required: false,
        },
        inputValidator: (value) => {
          if (!value) {
            return "Coloque uma justificativa!";
          } else {
            this.confirmar(lead, value, "NEGATIVA");
          }
        },
      });
    },

    confirmarContactado(lead) {
      let html = "Que bom! Você contactou o cliente com o telefone <b style='color:green'>" + this.limparTelefone(lead.telefone) + "</b>";

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
        .then((result) => {
          if (result.isConfirmed) {
            this.confirmarJaContactado(lead);
          }
        });
    },

    confirmarJaContactado(lead) {
      let indice = this.findLead(this.aux_leads_nao_contactados, lead.id);

      this.aux_leads_nao_contactados.splice(indice, 1)[0];

      this.aux_leads_contactados.push(lead);

      let data = {
        lead_id: lead.id,
      };

      axios
        .post(`/user/leads/conectado/update`, data)
        .then((response) => {
          this.showSuccessMessage("Contactado");
        })
        .catch((error) => {
          this.showErrorMessageWithButton("Ops..", error.response.data);
          console.log(error.response.data);
        });
    },

    findLead(aray, id) {
      let lead = aray.findIndex((element) => element["id"] == id);

      return lead;
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
        })
        .then((result) => {
          if (result.isConfirmed) {
            this.confirmar(lead, result.value, "POSITIVA");
          }
        });
    },

    atualizarStatus(lead) {
      let data = {
        lead_id: lead.id,
        status: lead.status_negociacao,
      };

      axios
        .post(`/user/leads/status/update`, data)
        .then((response) => {
          this.showSuccessMessage("Status atualizado!");
        })
        .catch((error) => {
          this.showErrorMessageWithButton("Ops..", error.response.data);
          console.log(error.response.data);
        });
    },

    confirmar(lead, comentario, avaliacao) {
      let data = {
        lead_id: lead.id,
        comentario: comentario,
        avaliacao: avaliacao,
      };

      axios
        .post(`/user/leads/avaliar`, data)
        .then((response) => {
          this.showSuccessMessage("Lead avaliado!");
          window.location.reload();
        })
        .catch((error) => {
          this.showErrorMessageWithButton("Ops..", error.response.data);
          console.log(error.response.data);
        });
    },
  },
};
</script>

<style>
/* Estilos para mobile - breakpoint pode ser ajustado (ex: 768px) */
@media screen and (max-width: 600px) {
  /* Esconde o cabeçalho da tabela */
  #zero_config thead {
    display: none;
  }

  /* Transforma a tabela, linha e corpo em blocos */
  #zero_config,
  #zero_config tbody,
  #zero_config tr {
    display: block;
    width: 100%;
  }

  /* Estilo do Card para cada linha */
  #zero_config tr {
    margin-bottom: 15px;
    border: 1px solid #ddd;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden; /* Garante que o border-radius funcione */
  }

  /* Transforma as células em linhas dentro do card */
  #zero_config td {
    display: flex; /* Alinha o label e o conteúdo */
    justify-content: space-between; /* Coloca o label de um lado e o valor do outro */
    align-items: center;
    padding: 10px;
    border: none;
    border-bottom: 1px solid #eee;
    text-align: right; /* Alinha o texto do valor à direita */
    width: 100%;
  }

  #zero_config td:last-child {
    border-bottom: none; /* Remove a borda da última célula */
  }

  /* Cria o "label" a partir do atributo data-label */
  #zero_config td::before {
    content: attr(data-label);
    font-weight: bold;
    text-align: left; /* Alinha o texto do label à esquerda */
    margin-right: 10px;
    color: #333;
  }

  /* Ajuste para botões ocuparem o espaço corretamente */
  #zero_config td[data-label="Interessado?"] > div,
  #zero_config td[data-label="Mensagem enviada?"] > button {
    width: 100%;
    margin-top: 5px; /* Adiciona um espaço caso o layout quebre */
  }
}
</style>
