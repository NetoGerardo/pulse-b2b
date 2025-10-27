<template>
  <loading v-model:active="isLoading" :can-cancel="false" :is-full-page="true" />

  <!-- FILTROS -->
  <div class="card">
    <div class="card-header">
      <h5 class="card-title">Filtrar campanhas</h5>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-sm-4">
          <div class="form-group">
            <label for="user_type">Usuário</label>
            <select class="form-control" id="user_type" v-model="usuario_selecionado">
              <option value=""></option>
              <option v-for="user in users" :key="user" :value="user">
                {{ user.name }}
              </option>
            </select>
          </div>
        </div>

        <div class="col-sm-12">
          <div class="form-group">
            <button type="button" class="btn btn-primary" @click="buscarCampanhas()">Buscar</button>

            <button type="button" class="btn btn-default" @click="limparFiltros()" style="margin-left: 15px">Limpar filtros</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div>
    <!-- TABELA DE parametro-->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Campanhas</h3>
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
      </div>
      <div class="card-body table-responsive p-0">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Nome</th>
              <th>Status</th>
              <th>Data de início</th>
              <th>Contatos</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="campanha in campanhas" :key="campanha.id" :value="campanha">
              <td>
                {{ campanha.nome }}
              </td>

              <td>
                <div v-if="campanha.status">
                  <span v-if="campanha.status == 'Agendada'" style="color: #fed000">{{ campanha.status }}</span>
                  <span v-else-if="campanha.status == 'Em andamento'" style="color: green">{{ campanha.status }}</span>
                  <span v-else-if="campanha.status == 'Finalizada'">{{ campanha.status }}</span>
                  <span v-else>{{ campanha.status }}</span>
                </div>
                <span v-else>-</span>
              </td>

              <td>
                {{ formatOnlyDate(campanha.data_inicio) }}
              </td>

              <td>
                {{ campanha.total_contatos }}
              </td>

              <td>
                <button
                  data-toggle="modal"
                  data-target="#editar-modal"
                  @click="(campanha_selecionada = campanha), (editar_campanha = true)"
                  type="submit"
                  class="btn btn-success"
                  style="margin-right: 5px"
                >
                  <i class="fa-solid fa-pen"></i>
                </button>

                <button type="submit" class="btn btn-danger" @click="deletar(campanha)">
                  <i class="fa-solid fa-trash"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="card-footer clearfix">
        <!-- TOTAIS  -->
        <div style="font-size: 13px" class="float-left" aria-live="polite">
          Exibindo do {{ inicio + 1 }} ao {{ inicio + campanhas.length }}
          <p>Total {{ total }}</p>
        </div>

        <p class="card-title" style="font-size: 12px; color: black; margin-right: 5px; margin-left: 10px">Por página</p>

        <select @change="alterarQtdPagina()" class="float-left card-titlecustom-select rounded-0" style="font-size: 12px" v-model="qtd_por_pagina">
          <option :value="25">25</option>
          <option :value="50">50</option>
          <option :value="100">100</option>
          <option :value="200">200</option>
        </select>

        <div class="card-tools">
          <div class="input-group input-group-sm" style="width: 150px"></div>
        </div>

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
    </div>
  </div>

  <!-- CADASTRAR -->
  <div
    class="modal fade"
    id="cadastrar-modal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="myModalLabel"
    aria-hidden="true"
    @click.self="cadastrar_tarefa = false"
    @keydown.esc="cadastrar_tarefa = false"
  >
    <form @submit.prevent>
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body" v-if="cadastrar_tarefa">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: black">&times;</button>
            <admin-cadastrar-tarefa :closeModal="closeModal" :acaoRealizada="acaoRealizada" />
          </div>
        </div>
      </div>
    </form>
  </div>

  <!-- EDITAR -->
  <div
    class="modal fade"
    id="editar-modal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="myModalLabel"
    aria-hidden="true"
    @click.self="editar_campanha = false"
    @keydown.esc="editar_campanha = false"
  >
    <form @submit.prevent>
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body" v-if="editar_campanha">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: black">&times;</button>
            <admin-editar-campanha :estados="estados" :campanha="campanha_selecionada" :closeModal="closeModal" :acaoRealizada="acaoRealizada" />
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import sweetAlert from "../../controller/sweetAlert";
import Swal from "sweetalert2/dist/sweetalert2.js";
import AuxController from "../../mixins/auxController.js";
import Loading from "vue-loading-overlay";
import moment from "moment";
import "vue-loading-overlay/dist/vue-loading.css";

export default {
  props: ["estados", "users"],

  mixins: [sweetAlert, Swal, AuxController],

  components: { Loading },

  data() {
    return {
      campanha_selecionada: "",
      usuario_selecionado: "",
      cadastrar_tarefa: false,
      editar_campanha: false,
      isLoading: false,
      parametro_selecionado: "",
      filtro: "",
      paginas: [],
      total: 0,
      inicio: 0,
      qtd_por_pagina: 25,
      pagina_atual: 1,
      campanhas: [],
      parametro_numero: "",
      parametro_vara: "",
      parametro_classe: "",
      parametro_comitente: "",
      parametro_executado: "",
      parametro_exequente: "",
      parametro_referencia: "",
      parametro_numero: "",
      parametro_local: "",
      parametro_juiz: "",
      parametro_depositario: "",
      estado: "",
      cidade: "",
      max_page: 0,
    };
  },

  mounted() {
    this.buscarCampanhas();
  },

  methods: {
    acaoRealizada(acao) {
      if (acao == "editar") {
        this.editar_campanha = false;
        this.buscarCampanhas();
      }

      if (acao == "cadastrar") {
        this.cadastrar_tarefa = false;
        this.buscarCampanhas();
      }
    },

    formatOnlyDate(date) {
      return moment(date).format("DD/MM/YYYY");
    },

    closeModal() {
      this.editar_campanha = false;
      this.cadastrar_tarefa = false;
    },

    getUrlEdit(parametro) {
      return "/admin/parametro/" + parametro.id;
    },

    show(parametro) {
      this.parametro_selecionado = parametro;
    },

    selecionarPagina(pagina) {
      if (pagina <= this.paginas[this.paginas.length - 1] && pagina > 0) {
        this.inicio = this.qtd_por_pagina * (pagina - 1);
        this.pagina_atual = pagina;
        this.buscarCampanhas();
      } else if (pagina == this.max_page && pagina > 0) {
        this.inicio = this.qtd_por_pagina * (pagina - 1);
        this.pagina_atual = pagina;
        this.buscarCampanhas();
      }
    },

    limparFiltros() {
      this.usuario_selecionado = "";
      this.buscarCampanhas();
    },

    buscarCampanhas() {
      this.isLoading = true;

      let data = {
        inicio: this.inicio,
        tamanho: this.qtd_por_pagina,
        user_id: this.usuario_selecionado ? this.usuario_selecionado.id : null,
      };

      console.log("Buscando");
      console.log(data);

      axios
        .post(`/admin/campanhas/search`, data)
        .then((response) => {
          this.isLoading = false;

          this.campanhas = response.data.campanhas;
          this.total = response.data.total;

          console.log("Campanhas encontradas");
          console.log(response.data.campanhas);

          this.pagination(response.data);
        })
        .catch((error) => {
          console.log(error);
          this.showErrorMessageWithButtonAndRefresh("Ops..", error.response.data);
          console.log(error.response.data);
        });
    },

    buscarCidades() {
      this.isLoading = true;

      let data = {
        estado_id: this.estado.id,
      };

      axios
        .post(`/admin/endereco/cidades_por_estado`, data)
        .then((response) => {
          this.cidades = response.data.cidades;

          this.isLoading = false;
        })
        .catch((error) => {
          this.showErrorMessageWithButton("Ops..", error);
          console.log(error);
          this.isLoading = false;
        });
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

    alterarQtdPagina() {
      this.pagina_atual = 1;
      this.inicio = 0;

      this.buscarCampanhas();
    },

    deletar(campanha) {
      this.$swal
        .fire({
          title: `<h3 style='color:#616060'>Deseja <b style="color:red">deletar</b> a campanha <br/><b>${campanha.nome}</b><br/><br/>Do usuário <b>${campanha.user.name}</b>?<br/>Esta ação é irreversível.</h3>`,
          icon: "warning",
          padding: "1.5em",
          showCancelButton: true,
          cancelButtonText: "Cancelar",
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Sim, quero deletar!",
        })
        .then((result) => {
          if (result.value) {
            this.isLoading = true;

            let data = {
              id: campanha.id,
            };

            axios
              .post(`/user/campanha/delete`, data)
              .then((response) => {
                this.showSuccessMessageWithButtonAndRefresh("Sucesso", "Campanha deletada!");
              })
              .catch((error) => {
                this.showErrorMessageWithButtonAndRefresh("Ops..", error.response.data);
                console.log(error.response.data);
              });
          }
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
        id: this.parametro_selecionado.id,
        cadastro_tipo: this.parametro_selecionado.cadastro_tipo,
        tipodoc: this.parametro_selecionado.tipodoc,
        estado_civil: this.parametro_selecionado.estado_civil,
        obrigatorio: this.parametro_selecionado.obrigatorio,
      };

      console.log("ATUALIZANDO");
      console.log(data);

      axios
        .post(`/admin/documento_tipo_usuario/update`, data)
        .then((response) => {
          this.showSuccessMessage("Estilo atualizado!");

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
  },
};
</script>

<style scoped>
.current-page {
  background-color: #428bca !important;
  color: white;
}

.modal-content {
  width: 1500px;
  margin-left: -430px;
}

@media screen and (max-width: 1600px) {
  .modal-content {
    width: 1350px;
    margin-left: -400px;
    margin-bottom: 100px;
  }
}

@media screen and (max-width: 1350px) {
  .modal-content {
    width: 1250px;
    margin-left: -350px;
    margin-bottom: 100px;
  }
}

@media screen and (max-width: 1200px) {
  .modal-content {
    width: 1100px;
    margin-left: -280px;
    margin-bottom: 100px;
  }
}

@media screen and (max-width: 1100px) {
  .modal-content {
    width: 1000px;
    margin-left: -240px;
    margin-bottom: 100px;
  }
}

@media screen and (max-width: 960px) {
  .modal-content {
    width: 750px;
    margin-left: -120px;
    margin-bottom: 100px;
  }
}

@media screen and (max-width: 800px) {
  .modal-content {
    width: 600px;
    margin-left: -50px;
    margin-bottom: 100px;
  }
}

@media screen and (max-width: 600px) {
  .modal-content {
    width: 100%;
    margin-left: 0;
    margin-bottom: 100px;
    min-width: 400px;
  }
}
</style>
