<template>
  <div class="container-fluid">
    <!-- Controles de Paginação e Título -->
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Minhas Tarefas</h3>
        <!-- PAGINAÇÃO -->
        <ul class="pagination pagination-sm m-0">
          <li class="page-item"><a class="page-link" href="#" @click.prevent="selecionarPagina(1)">&Lt;</a></li>
          <li class="page-item"><a class="page-link" href="#" @click.prevent="selecionarPagina(pagina_atual - 1)">&lt;</a></li>
          <li v-for="pagina in paginas" :key="pagina" class="page-item">
            <a :class="['page-link', { 'current-page': pagina === pagina_atual }]" href="#" @click.prevent="selecionarPagina(pagina)">
              {{ pagina }}
            </a>
          </li>
          <li class="page-item"><a class="page-link" href="#" @click.prevent="selecionarPagina(pagina_atual + 1)">&gt;</a></li>
          <li class="page-item"><a class="page-link" href="#" @click.prevent="selecionarPagina(max_page)">&Gt;</a></li>
        </ul>
      </div>
    </div>

    <!-- Novo Layout Kanban para Tarefas -->
    <div class="row mt-3">
      <div v-for="column in columns" :key="column.id" class="col-lg-12 col-md-12 col-sm-12 mb-4" :style="{ borderLeft: `5px solid ${column.color}` }">
        <div class="kanban-column">
          <h3 class="kanban-title" :style="{ color: column.color }">{{ column.title }} ({{ column.list.length }})</h3>
          <div v-if="column.list.length === 0" class="text-muted p-3 text-center">Nenhuma tarefa aqui.</div>
          <!-- Usando div em vez de draggable, já que não há ordenação -->
          <div class="list-group">
            <div
              @click="exibirDetalhes(tarefa)"
              data-toggle="modal"
              data-target="#detalhes-usuario"
              style="cursor: pointer"
              v-for="tarefa in column.list"
              :key="tarefa.id"
              class="kanban-card"
              :style="{ borderLeft: `5px solid ${column.color}` }"
            >
              <div class="task-card-content">
                <div class="task-info">
                  <div class="task-name">{{ tarefa.tarefa.nome }}</div>
                  <div class="lead-details">
                    <span class="font-weight-bold">Lead:</span>
                    {{ tarefa.contato.nome }} |
                    <span class="font-weight-bold">Telefone:</span>
                    {{ tarefa.contato.telefone }} |
                    <span class="font-weight-bold">Etapa:</span>
                    <span class="badge badge-info">{{ tarefa.contato.status }}</span>
                  </div>
                </div>
                <div class="task-actions">
                  <div class="expiration-date">Expira em: {{ formatOnlyDate(tarefa.expira_em) }}</div>
                  <button type="button" class="btn btn-sm btn-outline-danger" @click="deletarTarefa(tarefa)">
                    <i class="fa fa-trash"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Detalhes Usuário -->
  <div ref="modal" class="modal fade" id="detalhes-usuario" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form @submit.prevent>
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <button id="close" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4>Detalhes</h4>

            <div class="form-group">
              <label for="email">Anotações da tarefa</label>
              <textarea class="form-control" id="senha" type="senha" v-model="anotacoes" rows="5" cols="40" />
            </div>

            <button @click="salvarAnotacoesTarefa()" class="btn btn-success btn-sm text-md-end">Salvar anotações</button>
            <button @click="concluirTarefa()" class="btn btn-primary btn-sm text-md-end" style="margin-left: 15px">Concluir tarefa</button>

            <br />
            <br />

            <div class="form-group">
              <label for="email">Nome</label>
              <input :readonly="editar_contato" class="form-control" type="text" v-model="contato_selecionado.nome" />
            </div>

            <div class="form-group">
              <label for="user_type">Possui CNPJ?</label>
              <select class="form-control" v-model="contato_selecionado.possui_cnpj" readonly="true">
                <option value="0">Não</option>
                <option value="1">Sim</option>
              </select>
            </div>

            <div class="form-group">
              <label for="user_type">Ocupação?</label>
              <input :readonly="editar_contato" class="form-control" type="text" v-model="contato_selecionado.ocupacao" />
            </div>

            <div class="form-group">
              <label for="email">Telefone</label>
              <input :readonly="editar_contato" class="form-control" type="text" v-model="contato_selecionado.telefone" />
            </div>

            <div class="form-group">
              <label for="user_type">Bairro</label>
              <input :readonly="editar_contato" class="form-control" type="text" v-model="contato_selecionado.bairro" />
            </div>

            <div class="form-group">
              <label for="user_type">Possui Plano?</label>
              <select class="form-control" v-model="contato_selecionado.possui_plano" readonly="true">
                <option value="0">Não</option>
                <option value="1">Sim</option>
              </select>
            </div>

            <div class="form-group">
              <label for="user_type">Quantidade de vidas</label>
              <input :readonly="editar_contato" class="form-control" type="senha" v-model="contato_selecionado.qtd_vidas" autocomplete="senha" />
            </div>

            <div class="form-group">
              <label for="password">Complemento</label>
              <textarea
                :readonly="editar_contato"
                class="form-control"
                type="senha"
                v-model="contato_selecionado.complemento"
                autocomplete="senha"
                rows="5"
                cols="40"
              />
            </div>

            <div class="form-group">
              <label for="user_type">Origem</label>
              <input readonly="true" class="form-control" type="senha" v-model="contato_selecionado.origem" autocomplete="senha" />
            </div>

            <div class="form-group">
              <label>Observações Admin</label>
              <textarea
                readonly="true"
                class="form-control"
                type="senha"
                v-model="contato_selecionado.observacoes_admin"
                autocomplete="senha"
                rows="5"
                cols="40"
              />
            </div>

            <div class="form-group">
              <label>Observações Corretor</label>
              <textarea
                readonly="true"
                class="form-control"
                type="senha"
                v-model="contato_selecionado.observacoes_corretor"
                autocomplete="senha"
                rows="5"
                cols="40"
              />
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import sweetAlert from "../../controller/sweetAlert.js";
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
      contato_selecionado: "",
      editar_contato: "",
      anotacoes: "",
      tarefa_selecionada: "",

      // Nova estrutura para as colunas de tarefas
      columns: [
        { id: "ontem", title: "Ontem", color: "#d0021b", list: [] },
        { id: "hoje", title: "Hoje", color: "#f5a623", list: [] },
        { id: "semana", title: "Esta Semana", color: "#4a90e2", list: [] },
        { id: "futuras", title: "Tarefas Futuras", color: "#7ed321", list: [] },
      ],
    };
  },

  mounted() {
    this.buscarTarefas();
  },

  methods: {
    exibirDetalhes(element) {
      this.contato_selecionado = element.contato;
      this.anotacoes = element.anotacoes;
      this.tarefa_selecionada = element;

      if (this.contato_selecionado.tarefas) {
        for (let i = 0; i < this.contato_selecionado.tarefas.length; i++) {
          this.contato_selecionado.tarefas[i].tarefa_concluida = this.contato_selecionado.tarefas[i].concluida == 1 ? true : false;
        }
      }
    },

    salvarAnotacoesTarefa() {
      this.isLoading = true;

      let data = {
        id: this.tarefa_selecionada.id,
        anotacoes: this.anotacoes,
      };

      console.log("Buscando");
      console.log(data);

      axios
        .post(`/user/tarefa-lead/salvar-anotacoes`, data)
        .then((response) => {
          this.showSuccessMessage("Anotações atualizadas!");
          this.buscarTarefas();
        })
        .catch((error) => {
          console.log(error);
          this.showErrorMessageWithButtonAndRefresh("Ops..", error.response.data);
          console.log(error.response.data);
        });
    },

    concluirTarefa() {
      this.$swal
        .fire({
          title: "Confirmação",
          html: "Deseja marcar essa tarefa como concluída?<br/>Está ação é irreversível.",
          icon: "warning",
          padding: "1.5em",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Sim, concluir tarefa!",
        })
        .then((result) => {
          if (result.value) {
            this.isLoading = true;

            let data = {
              id: this.tarefa_selecionada.id,
              concluida: 1,
            };

            console.log(data);

            axios
              .post(`/user/tarefa/concluir`, data)
              .then((response) => {
                this.isLoading = false;

                this.buscarTarefas();

                if (response.data.success) {
                  this.showSuccessMessage("Tarefa concluída!");
                  document.getElementById("close").click();
                } else {
                  this.showErrorMessageWithButton("Ops...", response.data.msg);
                }
              })
              .catch((error) => {
                this.isLoading = false;
                this.showErrorMessageWithButton("Ops..", error);
                console.log(error);
              });
          }
        });
    },

    categorizeTasks() {
      const hoje = moment().startOf("day");
      const ontem = moment().subtract(1, "day").startOf("day");
      const inicioSemana = moment().startOf("isoWeek"); // isoWeek considera a semana de Seg a Dom
      const fimSemana = moment().endOf("isoWeek");

      // Limpa as listas antes de categorizar novamente
      let tarefasOntem = [];
      let tarefasHoje = [];
      let tarefasSemana = [];
      let tarefasFuturas = [];

      this.tarefas.forEach((tarefa) => {
        const expira = moment(tarefa.expira_em).startOf("day");

        if (expira.isSame(ontem)) {
          tarefasOntem.push(tarefa);
        } else if (expira.isSame(hoje)) {
          tarefasHoje.push(tarefa);
        } else if (expira.isAfter(hoje) && expira.isBetween(inicioSemana, fimSemana, null, "[]")) {
          tarefasSemana.push(tarefa);
        } else if (expira.isAfter(fimSemana)) {
          tarefasFuturas.push(tarefa);
        } else if (expira.isBefore(ontem)) {
          // Tarefas atrasadas que não são de ontem
          tarefasOntem.push(tarefa);
        }
      });

      this.columns[0].list = tarefasOntem;
      this.columns[1].list = tarefasHoje;
      this.columns[2].list = tarefasSemana;
      this.columns[3].list = tarefasFuturas;
    },

    deletarTarefa(tarefa) {
      this.$swal
        .fire({
          title: "Deletar Tarefa",
          html:
            `<p>Deseja deletar a tarefa <b>"${tarefa.tarefa.nome}"</b>?</p>` +
            `<textarea style="width:100%; margin-left: 0px" id="motivo-delecao" class="swal2-textarea" placeholder="Justificativa"></textarea>`,
          icon: "warning",
          showCancelButton: true,
          cancelButtonText: "Cancelar",
          confirmButtonColor: "#d33",
          cancelButtonColor: "#3085d6",
          confirmButtonText: "Sim, quero deletar!",
          preConfirm: () => {
            const motivo = document.getElementById("motivo-delecao").value;
            // Adiciona a validação: se o motivo estiver vazio, mostra uma mensagem de erro
            if (!motivo || motivo.trim() === "") {
              this.$swal.showValidationMessage("A justificativa é obrigatória para deletar a tarefa.");
              return false; // Impede que o modal feche
            }
            return motivo;
          },
        })
        .then((result) => {
          // A propriedade "isConfirmed" é mais segura que "value" para checar a confirmação
          if (result.isConfirmed) {
            const motivo = result.value;

            let data = {
              id: tarefa.id,
              motivo_deletar: motivo,
            };

            console.log("Buscando");
            console.log(data);

            axios
              .post(`/user/tarefa-lead/delete`, data)
              .then((response) => {
                this.tarefas = response.data.tarefas;
                this.total = response.data.total;

                this.showSuccessMessage("Tarefa deletada!");
                this.buscarTarefas(); // Recarrega a lista
              })
              .catch((error) => {
                console.log(error);
                this.showErrorMessageWithButtonAndRefresh("Ops..", error.response.data);
                console.log(error.response.data);
              });
          }
        });
    },

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
        .post(`/user/tarefa-lead/search`, data)
        .then((response) => {
          this.tarefas = response.data.tarefas;
          this.total = response.data.total;

          console.log("Tarefas encontradas");
          console.log(this.tarefas);

          this.pagination(response.data);
          this.categorizeTasks();
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

    formatOnlyDate(date) {
      return moment(date).format("DD/MM/YYYY");
    },
  },
};
</script>

<style scoped>
/* Estilos importados do KanbanBoard e adaptados */
.kanban-column {
  background-color: #f4f5f7;
  border-radius: 8px;
  padding: 15px;
  height: 100%;
}

.kanban-title {
  font-size: 1.1rem;
  font-weight: bold;
  padding-bottom: 15px;
  margin-bottom: 15px;
  border-bottom: 1px solid #dfe1e6;
}

.kanban-card {
  background-color: #ffffff;
  border-radius: 4px;
  padding: 15px;
  margin-bottom: 10px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.task-card-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
}

.task-info {
  flex-grow: 1;
  word-break: break-word; /* Garante que texto longo não quebre o layout */
  padding-right: 15px; /* Adiciona um respiro antes das ações */
}

.task-name {
  font-weight: 600;
  color: #172b4d;
  font-size: 1rem;
}

.lead-details {
  font-size: 0.85rem;
  color: #6b778c;
  margin-top: 5px;
}

.task-actions {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  text-align: right;
  min-width: 120px;
}

.expiration-date {
  font-size: 0.8rem;
  font-weight: 500;
  color: #5e6c84;
  margin-bottom: 8px;
}

.current-page {
  background-color: #007bff;
  color: white !important;
  border-color: #007bff;
}

/* === MELHORIA PARA MOBILE === */
@media (max-width: 767px) {
  .task-card-content {
    flex-direction: column; /* Empilha os itens verticalmente */
    align-items: flex-start; /* Alinha no começo */
  }

  .task-info {
    padding-right: 0; /* Remove o espaçamento em mobile */
    width: 100%;
  }

  .task-actions {
    width: 100%;
    margin-top: 15px; /* Espaço entre a info e as ações */
    flex-direction: row; /* Itens lado a lado */
    justify-content: space-between; /* Data na esquerda, botão na direita */
    align-items: center;
  }

  .expiration-date {
    margin-bottom: 0; /* Remove a margem desnecessária */
  }
}
</style>
