<template>
  <button type="submit" class="btn btn-primary" @click="cadastrar_campanha = true">
    <i class="fa-solid fa-plus" style="margin-right: 5px"></i>
    Nova campanha
  </button>

  <div v-if="cadastrar_campanha">
    <user-cadastrar-campanha :estados="estados" :acaoRealizada="acaoRealizada" :close="close" />
  </div>

  <div class="card">
    <div class="card-body p-3">
      <h4 style="color: black">Última campanha</h4>

      <!-- ÚLTIMA CAMPANHA-->
      <table class="table table-hover" v-if="ultima_campanha">
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
          <tr>
            <td>
              {{ ultima_campanha.nome }}
            </td>

            <td>
              <div v-if="ultima_campanha.status">
                <span v-if="ultima_campanha.status == 'Em andamento'" style="color: green">{{ ultima_campanha.status }}</span>
                <span v-else-if="ultima_campanha.status == 'Finalizada'">{{ ultima_campanha.status }}</span>
                <span v-else>{{ ultima_campanha.status }}</span>
              </div>
              <span v-else>-</span>
            </td>

            <td>
              {{ formatOnlyDate(ultima_campanha.data_inicio) }}
            </td>

            <td>
              {{ ultima_campanha.total_contatos }}
            </td>

            <td>
              <button v-if="ultima_campanha.status != 'Em andamento'" type="submit" class="btn btn-danger" @click="deletar()">
                <i class="fa-solid fa-trash"></i>
              </button>

              <button v-else-if="ultima_campanha.status != 'Finalizada'" type="submit" class="btn btn-warning" @click="concluirCampanha()">
                <i class="fa-solid fa-check"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <h5 v-else>Nenhuma campanha encontrada...</h5>
    </div>
  </div>

  <div class="card">
    <div class="card-body p-3">
      <h4 style="color: black">Canais</h4>

      <!-- ÚLTIMA CAMPANHA-->
      <table class="table table-hover" v-if="prospects_canais">
        <thead>
          <tr>
            <th>Canal</th>
            <th>Interessados</th>
            <th>Não interessados</th>
            <th>Total</th>
            <th>CPL</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="canal in prospects_canais" :key="canal.id">
            <td>
              {{ canal.nome }}
            </td>

            <td>
              {{ canal.interessados }}
            </td>

            <td>
              {{ canal.nao_interessados }}
            </td>

            <td>
              {{ canal.total_prospects }}
            </td>

            <td>
              <span v-if="canal.interessados > 0">R${{ toCurrency(80 / canal.interessados) }}</span>
            </td>
          </tr>
        </tbody>
      </table>

      <h5 v-else>Nenhuma campanha encontrada...</h5>
    </div>
  </div>

  <div class="card">
    <div class="card-body p-0">
      <div class="card-header" style="background-color: white">
        <div class="card-title" id="name" style="display: flex; align-items: center; justify-content: space-between">
          <h4 style="color: black; margin: 0">Prospects</h4>

          <select class="form-control" style="max-width: 200px" id="user_type" v-model="filtro_status" @change="buscarProspects">
            <option value="">Todos</option>
            <option value="Qualificados">Somente qualificados</option>
            <option value="Desqualificados">Desqualificados</option>
          </select>

          <div class="progress-btn" data-progress-style="fill-back" @click="buscarLotes">
            <div class="btn update-btn">
              <i class="fa-solid fa-arrow-rotate-left"></i>
              Atualizar
            </div>
            <div class="progress"></div>
          </div>
        </div>
      </div>

      <table class="tabela-prospects table table-hover">
        <thead>
          <tr>
            <th>Nome (Razão Social)</th>
            <th>Qualificação</th>
            <th>Status (Whatsapp)</th>
            <th>Contatos</th>

            <th>Dados Adicionais</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="prospects.length === 0">
            <td :colspan="6" class="sem-dados">Nenhum prospect encontrado.</td>
          </tr>

          <tr v-for="prospect in prospects" :key="prospect.id">
            <td>{{ prospect.dados["Razao Social"] || "Não informado" }}</td>

            <td>
              <span :class="['status-badge', getStatusClass(prospect.status_ligacao)]">
                <span v-if="prospect.status_ligacao == 'interessado' || prospect.status_ligacao == 'muito interessado'">
                  {{ prospect.status_ligacao }}
                </span>

                <span v-else>Sem interesse</span>
              </span>
            </td>

            <td>
              <span :class="['status-badge', getStatusClass(prospect.status_whatsapp)]">
                {{ prospect.status_whatsapp || "N/A" }}
              </span>
            </td>

            <td class="coluna-contatos">
              <div v-if="prospect.telefone">
                <strong>Telefone:</strong>
                {{ prospect.telefone }}
              </div>
              <div v-if="prospect.dados['E-mail']">
                <strong>Email:</strong>
                {{ prospect.dados["E-mail"] }}
              </div>
            </td>

            <td class="coluna-dados">
              <ul v-if="prospect.dados && Object.keys(prospect.dados).length > 0">
                <li v-for="(valor, chave) in filtrarDados(prospect.dados)" :key="chave">
                  <strong>{{ chave }}:</strong>
                  {{ valor }}
                </li>
              </ul>
              <span v-else>Nenhum dado adicional.</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="card-tools p-2">
      <!-- TOTAIS  -->
      <div style="font-size: 13px" class="float-left" aria-live="polite">
        Exibindo do {{ inicio + 1 }} ao {{ inicio + prospects.length }}
        <p>Total {{ total }}</p>
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
</template>

<script>
import sweetAlert from "../controller/sweetAlert";
import auxFormatacao from "../controller/auxFormatacao";
import currencyController from "../controller/currencyController";
import moment from "moment";
import Swal from "sweetalert2/dist/sweetalert2.js";

export default {
  mixins: [sweetAlert, Swal, auxFormatacao, currencyController],

  props: ["estados", "ultima_campanha"],

  data() {
    return {
      filtro_status: "",
      cadastrar_campanha: false,
      total_cadastrados: 0,
      indicacoes_recebidas: 0,
      total_positivas: 0,
      total_negativas: 0,
      prospects: [],
      inicio: 0,
      qtd_por_pagina: 25,
      paginas: [],
      pagina_atual: 1,
      prospects_canais: [],
    };
  },

  mounted() {
    console.log(this.ultima_campanha);

    this.buscarProspects();
    this.buscarProspectsCanais();

    this.searchForever();
  },

  methods: {
    buscarProspectsCanais() {
      let data = {
        campanha_id: this.ultima_campanha.id,
      };

      axios
        .post(`/user/canais/prospects_canais`, data)
        .then((response) => {
          this.prospects_canais = response.data.prospects_canais;

          console.log("prospects_canais");
          console.log(this.prospects_canais);
        })
        .catch((error) => {
          this.showErrorMessageWithButton("Ops..", error.response.data);
          console.log(error.response.data);
        });
    },

    searchForever() {
      let that = this;

      setTimeout(() => {
        that.buscarProspects();
        that.buscarProspectsCanais();
        that.searchForever();
      }, 5000);
    },

    formatarData(dataIso) {
      if (!dataIso) return "N/A";
      const data = new Date(dataIso);
      return data.toLocaleString("pt-BR", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
      });
    },

    filtrarDados(dados) {
      const dadosFiltrados = { ...dados };
      // Remove chaves que já têm colunas próprias
      delete dadosFiltrados["Razao Social"];
      delete dadosFiltrados["E-mail"];
      return dadosFiltrados;
    },

    getStatusClass(status) {
      if (!status) return "status-default";

      const statusNormalizado = status.toLowerCase();
      if (statusNormalizado.includes("não qualificada") || statusNormalizado.includes("recusou") || statusNormalizado.includes("falha no envio")) {
        return "status-falha";
      }

      if (
        statusNormalizado.includes("interessado") ||
        statusNormalizado.includes("muito interessado") ||
        statusNormalizado.includes("concluída") ||
        statusNormalizado.includes("mensagem enviada")
      ) {
        return "status-sucesso";
      }

      if (statusNormalizado.includes("pendente") || statusNormalizado.includes("agendado")) {
        return "status-pendente";
      }
      return "status-default";
    },

    buscarProspects() {
      this.isLoading = true;

      let data = {
        campanha_id: this.ultima_campanha ? this.ultima_campanha.id : null,
        inicio: this.inicio,
        tamanho: this.qtd_por_pagina,
        status: this.filtro_status,
      };

      console.log("Buscando prospects");
      console.log(data);

      axios
        .post(`/user/prospects/search`, data)
        .then((response) => {
          this.isLoading = false;

          this.prospects = response.data.prospects;
          this.total = response.data.total;

          console.log("prospects encontradas");
          console.log(response.data.prospects);

          this.pagination(response.data);
        })
        .catch((error) => {
          console.log(error);
          this.showErrorMessageWithButtonAndRefresh("Ops..", error.response.data);
          console.log(error.response.data);
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

    concluirCampanha() {
      if (this.ultima_campanha.status == "Agendada") {
        this.showErrorMessageWithButton("Ops...", `Não é possível finalizar campanhas com o status "${this.ultima_campanha.status}"`);
      } else {
        this.$swal
          .fire({
            title: `<h3 style='color:#616060'>Deseja <b style="color:orange">finalizar</b> a campanha <br/><b>${this.ultima_campanha.nome}</b>?<br/>Esta ação é irreversível.</h3>`,
            icon: "warning",
            padding: "1.5em",
            showCancelButton: true,
            cancelButtonText: "Cancelar",
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim, quero finalizar!",
          })
          .then((result) => {
            if (result.value) {
              this.isLoading = true;

              let data = {
                id: this.ultima_campanha.id,
                status: "Finalizada",
              };

              axios
                .post(`/user/campanha/update_status`, data)
                .then((response) => {
                  this.showSuccessMessageWithButtonAndRefresh("Sucesso", "Campanha atualizada!");
                })
                .catch((error) => {
                  this.showErrorMessageWithButtonAndRefresh("Ops..", error.response.data);
                  console.log(error.response.data);
                });
            }
          });
      }
    },

    deletar() {
      if (this.ultima_campanha.status != "Agendada") {
        this.showErrorMessageWithButton("Ops...", `Não é possível excluir campanhas com o status "${this.ultima_campanha.status}"`);
      } else {
        this.$swal
          .fire({
            title: `<h3 style='color:#616060'>Deseja <b style="color:red">deletar</b> a campanha <br/><b>${this.ultima_campanha.nome}</b>?<br/>Esta ação é irreversível.</h3>`,
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
                id: this.ultima_campanha.id,
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
      }
    },

    acaoRealizada(acao) {
      if (acao == "cadastrar") {
        this.cadastrar_campanha = false;
      }
    },

    close(acao) {
      if (acao == "cadastrar") {
        this.cadastrar_campanha = false;
      }
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

    formatOnlyDate(date) {
      return moment(date).format("DD/MM/YYYY");
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
          input: "text",
          inputAttributes: {
            autocapitalize: "off",
          },
        })
        .then((result) => {
          if (result.isConfirmed) {
            this.confirmar(lead, result.value, "POSITIVA");
          }
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
        })
        .catch((error) => {
          this.showErrorMessageWithButton("Ops..", error.response.data);
          console.log(error.response.data);
        });
    },
  },
};
</script>

<style scoped>
/* Estilos básicos para a tabela */
.tabela-container {
  font-family: Arial, sans-serif;
  width: 100%;
  margin: 20px auto;
  overflow-x: auto; /* Permite scroll horizontal em telas pequenas */
}

.tabela-prospects {
  width: 100%;
  border-collapse: collapse;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.tabela-prospects th,
.tabela-prospects td {
  border: 1px solid #ddd;
  padding: 12px 15px;
  text-align: left;
  vertical-align: top;
}

.tabela-prospects thead {
  background-color: #f4f6f8;
}

.tabela-prospects thead th {
  color: #333;
  font-weight: bold;
}

.tabela-prospects tbody tr:nth-of-type(even) {
  background-color: #f9f9f9;
}

.tabela-prospects tbody tr:hover {
  background-color: #f1f1f1;
}

.sem-dados {
  text-align: center;
  color: #777;
  padding: 30px;
  font-style: italic;
}

/* Estilo para a coluna de DADOS (JSON) */
.coluna-dados ul {
  list-style-type: none;
  padding-left: 0;
  margin: 0;
  font-size: 0.9em; /* Tamanho menor para os dados internos */
  max-width: 300px; /* Limita a largura */
}

.coluna-dados li {
  padding: 4px 0;
  border-bottom: 1px solid #eee;
  word-break: break-word; /* Quebra palavras longas */
}

.coluna-dados li:last-child {
  border-bottom: none;
}

.coluna-dados li strong {
  color: #555;
  margin-right: 5px;
}

/* Estilos para a coluna de Contatos */
.coluna-contatos div {
  margin-bottom: 5px;
  font-size: 0.9em;
}

/* Estilos para Ações */
.coluna-acoes button {
  margin-right: 5px;
  padding: 5px 10px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}
.coluna-acoes button:first-of-type {
  background-color: #007bff;
  color: white;
}
.coluna-acoes button:last-of-type {
  background-color: #ffc107;
  color: #333;
}

/* Estilos para os Badges de Status */
.status-badge {
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 0.85em;
  font-weight: bold;
  color: white;
  white-space: nowrap;
}

.status-default {
  background-color: #6c757d;
}
.status-sucesso {
  background-color: #28a745;
}
.status-falha {
  background-color: #dc3545;
}
.status-pendente {
  background-color: #ffc107;
  color: #333;
}

.progress-btn {
  position: relative;
  width: 150px;
  height: 40px;
  display: inline-block;
  font-family: "RobotoDraft", "Roboto", sans-serif;
  color: #fff !important;
  font-weight: normal;
  font-size: 20px;
  transition: all 0.4s ease;
  border: 1px solid gray;
}

.progress-btn:not(.active) {
  cursor: pointer;
}

.progress-btn .btn {
  position: absolute;
  left: 0;
  top: 0;
  right: 0;
  bottom: 0;
  line-height: 40px; /* <<< MUDE AQUI (de 5px para 40px) */
  text-align: center;
  z-index: 10;
  opacity: 1;

  /* --- Mágica do Flexbox --- */
  /* Adicione estas 4 linhas: */
  display: flex;
  align-items: center; /* Alinha verticalmente */
  justify-content: center; /* Alinha horizontalmente */
  gap: 8px; /* (Opcional) Espaço entre o ícone e o texto */
}

.progress-btn .progress {
  width: 0%;
  z-index: 5;
  background: #1d743a;
  opacity: 0;
  transition: all 0.3s ease;
  height: 5px;
}

.progress-btn .progress {
  opacity: 1;
  animation: progress-anim 5s ease infinite;
}

.progress-btn[data-progress-style="fill-back"] .progress {
  position: absolute;
  left: 0;
  right: 0;
  bottom: 0;
}

@keyframes progress-anim {
  0% {
    width: 0%;
  }
  5% {
    width: 0%;
  }
  10% {
    width: 15%;
  }
  30% {
    width: 40%;
  }
  50% {
    width: 55%;
  }
  80% {
    width: 100%;
  }
  95% {
    width: 100%;
  }
  100% {
    width: 0%;
  }
}

.span-green {
  background-color: green;
}

.span-grwy {
  background-color: gray;
}
</style>
