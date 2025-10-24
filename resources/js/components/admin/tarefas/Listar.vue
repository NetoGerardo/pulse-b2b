<template>
  <div>
    <!-- TABELA DE parametro-->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          Tarefas
         
          <button
            type="submit"
            class="btn btn-primary"
            @click="cadastrar_tarefa = true"
            data-toggle="modal"
            data-target="#cadastrar-modal"
            style="margin-left: 15px"
          >
            <i class="fa-solid fa-plus" style="margin-right: 5px"></i>
            Cadastrar tarefa
          </button>
        </h3>
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
              <th>Etapa</th>
              <th>Todas as etapas</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(parametro, index) in tarefas" :key="parametro">
              <td>
                {{ parametro.nome }}
              </td>

              <td>
                {{ parametro.etapa }}
              </td>

              <td>
                <span v-if="parametro.todas_as_etapas">Sim</span>
                <span v-else>Não</span>
              </td>

              <td>
                <a
                  id="navbarDropdown"
                  class="nav-link"
                  href="#"
                  role="button"
                  data-bs-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                  v-pre
                >
                  <i class="fa-solid fa-bars" style="color: gray"></i>
                </a>

                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" aria-labelledby="navbarDropdown" ref="menu">
                  <div class="dropdown-divider"></div>
                  <a
                    class="dropdown-item"
                    @click="(parametro_selecionado = parametro), (editar_parametro = true)"
                    data-toggle="modal"
                    data-target="#editar-modal"
                  >
                    <i class="fa-solid fa-pen"></i>
                    &nbsp;&nbsp; Editar
                  </a>
                  <!--
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" @click="deletar(parametro)">
                    <i class="fa-solid fa-trash"></i>
                    &nbsp;&nbsp; Excluir
                  </a>
                  -->
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="card-footer clearfix">
        <!-- TOTAIS  -->
        <div style="font-size: 13px" class="float-left" aria-live="polite">
          Exibindo do {{ inicio + 1 }} ao {{ inicio + tarefas.length }}
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
    @click.self="editar_parametro = false"
    @keydown.esc="editar_parametro = false"
  >
    <form @submit.prevent>
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body" v-if="editar_parametro">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: black">&times;</button>
            <admin-editar-tarefa :parametro="parametro_selecionado" :closeModal="closeModal" :acaoRealizada="acaoRealizada" />
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
  props: ["estados"],

  mixins: [sweetAlert, Swal, AuxController],

  components: [Loading],

  data() {
    return {
      cadastrar_tarefa: false,
      editar_parametro: false,
      isLoading: false,
      parametro_selecionado: "",
      filtro: "",
      paginas: [],
      total: 0,
      inicio: 0,
      qtd_por_pagina: 25,
      pagina_atual: 1,
      tarefas: [],
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
    this.buscarTarefas();
  },

  methods: {
    acaoRealizada(acao) {
      if (acao == "editar") {
        this.editar_parametro = false;
        this.buscarTarefas();
      }

      if (acao == "cadastrar") {
        this.cadastrar_tarefa = false;
        this.buscarTarefas();
      }
    },

    closeModal() {
      this.editar_parametro = false;
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
        this.buscarTarefas();
      } else if (pagina == this.max_page && pagina > 0) {
        this.inicio = this.qtd_por_pagina * (pagina - 1);
        this.pagina_atual = pagina;
        this.buscarTarefas();
      }
    },

    buscarTarefas() {
      this.isLoading = true;

      let data = {
        inicio: this.inicio,
        tamanho: this.qtd_por_pagina,
      };

      console.log("Buscando");
      console.log(data);

      axios
        .post(`/admin/tarefas/search`, data)
        .then((response) => {
          this.tarefas = response.data.tarefas;
          this.total = response.data.total;

          console.log("Tarefas encontradas");
          console.log(tarefas);

          this.pagination(response.data);
        })
        .catch((error) => {
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

      this.buscarTarefas();
    },

    deletar(parametro) {
      this.parametro_selecionado = parametro;

      this.$swal
        .fire({
          title: "<h3 style='color:#616060'>Deseja deletar o parametro<b><br/>" + this.parametro_selecionado.nome + "</b>? </h3>",
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
              id: this.parametro_selecionado.id,
            };

            axios
              .post(`/admin/parametro/delete`, data)
              .then((response) => {
                this.showSuccessMessage("Parâmetro deletado!");

                this.buscarTarefas();
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
