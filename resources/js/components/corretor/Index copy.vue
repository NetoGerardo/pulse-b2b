<template>
  <div>
    <loading v-model:active="isLoading" :can-cancel="false" :is-full-page="true" />
  </div>

  <div>
    <user-create-lead :origens="origens" />
  </div>

  <div class="container-fluid">
    <div v-if="!cadastrar_proposta && !corrigir_pendencias" class="kanban-board">
      <!--Pendência -->
      <div class="col-lg-3 col-md-4 col-sm-6 bg-warning card">
        <h3 style="color: white; margin-top: 5px">Pendência</h3>
        <br />
        <draggable ghost-class="moving-card" :list="pendencia" class="list-group" group="a" item-key="name">
          <template #item="{ element }">
            <div class="px-1 py-2 inside-card" style="" @click="corrigirPendencias(element)">
              <!-- PENDÊNCIA COM BOTÃO-->
              <div v-if="element.status == 'Pendência'" class="bg-white flex-box-2" style="text-align: center">
                <div class="mr-2" style="float: left">
                  <img alt="Avatar" class="table-avatar" style="min-width: 20px" width="20" height="20" src="/assets/images/whats-logo.png" />
                </div>
                <div class="pointer">
                  <h5 class="text-dark mb-0 font-14 font-weight-medium">{{ formatarNome(element.nome) }}</h5>
                  <span class="text-muted font-12">{{ element.telefone }}</span>
                </div>

                <div class="text-right" style="float: right">
                  <button @click="descreverPendencias" style="float: right; border-radius: 10%" type="button" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>

              <!-- PENDÊNCIA SEM BOTÃO-->
              <div v-if="element.status != 'Pendência'" class="bg-white" style="text-align: center">
                <div class="mr-2" style="float: left">
                  <img alt="Avatar" class="table-avatar" style="min-width: 20px" width="20" height="20" src="/assets/images/whats-logo.png" />
                </div>
                <div class="pointer">
                  <h5 class="text-dark mb-0 font-14 font-weight-medium">{{ formatarNome(element.nome) }}</h5>
                  <span class="text-muted font-12">{{ element.telefone }}</span>
                </div>

                <small id="name13" class="badge badge-default badge-danger form-text text-white float-right">Aguardando resolução</small>
              </div>
            </div>
          </template>
        </draggable>
      </div>

      <!--Indicação Cadastrada -->
      <div class="col-lg-3 col-md-4 col-sm-6 bg-white card">
        <h3 style="margin-top: 5px">Indicação Cadastrada</h3>
        <br />
        <draggable
          @change="updateCadastrado"
          ghost-class="moving-card"
          :list="indicacoes_cadastradas"
          class="list-group bg-white"
          group="a"
          item-key="name"
        >
          <template #item="{ element }">
            <div
              @click="exibirDetalhes(element)"
              data-toggle="modal"
              data-target="#detalhes-usuario"
              class="px-1 py-2 bg-white inside-card"
              :class="getStyleLead(element)"
            >
              <div class="bg-white" style="text-align: center" :class="getStyleLead(element)">
                <div class="mr-2" style="float: left">
                  <img alt="Avatar" class="table-avatar" style="min-width: 20px" width="20" height="20" src="/assets/images/whats-logo.png" />
                </div>
                <div>
                  <dados-card-lead :lead="element" />
                </div>
              </div>
            </div>
          </template>
        </draggable>
      </div>

      <!--Coleta de dados -->
      <div class="col-lg-3 col-md-4 col-sm-6 bg-dark card">
        <h3 style="color: white; margin-top: 5px">Coleta de dados</h3>
        <br />
        <draggable @change="updateColeta" ghost-class="moving-card" :list="coleta_de_dados" class="list-group" group="a" item-key="name">
          <template #item="{ element }">
            <div @click="exibirDetalhes(element)" data-toggle="modal" data-target="#detalhes-usuario" class="px-1 py-2 inside-card" style="">
              <div class="bg-white" style="text-align: center">
                <div class="mr-2" style="float: left">
                  <img alt="Avatar" class="table-avatar" style="min-width: 20px" width="20" height="20" src="/assets/images/whats-logo.png" />
                </div>
                <div>
                  <dados-card-lead :lead="element" />
                </div>
              </div>
            </div>
          </template>
        </draggable>
      </div>

      <!--Cotação Enviada -->
      <div class="col-lg-3 col-md-4 col-sm-6 bg-info card">
        <h3 style="color: white; margin-top: 5px">Cotação Enviada</h3>
        <br />
        <draggable @change="updateCotacaoEnviada" ghost-class="moving-card" :list="cotacao_enviada" class="list-group" group="a" item-key="name">
          <template #item="{ element }">
            <div @click="exibirDetalhes(element)" data-toggle="modal" data-target="#detalhes-usuario" class="px-1 py-2 inside-card" style="">
              <div class="bg-white" style="text-align: center">
                <div class="mr-2" style="float: left">
                  <img alt="Avatar" class="table-avatar" style="min-width: 20px" width="20" height="20" src="/assets/images/whats-logo.png" />
                </div>
                <div>
                  <dados-card-lead :lead="element" />
                </div>
              </div>
            </div>
          </template>
        </draggable>
      </div>

      <!--Em negociação -->
      <div class="col-lg-3 col-md-4 col-sm-6 bg-warning card">
        <h3 style="color: white; margin-top: 5px">Em negociação</h3>
        <br />
        <draggable @change="updateEmNegociacao" ghost-class="moving-card" :list="em_negociacao" class="list-group" group="a" item-key="name">
          <template #item="{ element }">
            <div @click="exibirDetalhes(element)" data-toggle="modal" data-target="#detalhes-usuario" class="px-1 py-2 inside-card" style="">
              <div class="bg-white" style="text-align: center">
                <div class="mr-2" style="float: left">
                  <img alt="Avatar" class="table-avatar" style="min-width: 20px" width="20" height="20" src="/assets/images/whats-logo.png" />
                </div>
                <div>
                  <dados-card-lead :lead="element" />
                </div>
              </div>
            </div>
          </template>
        </draggable>
      </div>

      <!--Negociação encerrada -->
      <div class="col-lg-3 col-md-4 col-sm-6 bg-danger card">
        <h3 style="color: white; margin-top: 5px">Negociação encerrada</h3>
        <br />
        <draggable
          @change="updateNegociacaoEncerrada"
          ghost-class="moving-card"
          :list="negociacao_encerrada"
          class="list-group"
          group="a"
          item-key="name"
        >
          <template #item="{ element }">
            <div @click="exibirDetalhes(element)" data-toggle="modal" data-target="#detalhes-usuario" class="px-1 py-2 inside-card" style="">
              <div class="bg-white" style="text-align: center">
                <div class="mr-2" style="float: left">
                  <img alt="Avatar" class="table-avatar" style="min-width: 20px" width="20" height="20" src="/assets/images/whats-logo.png" />
                </div>
                <div>
                  <dados-card-lead :lead="element" />
                </div>
              </div>
            </div>
          </template>
        </draggable>
      </div>

      <!--Documentação -->
      <div class="col-lg-3 col-md-4 col-sm-6 card" style="background-color: #bde0ff">
        <h3 style="color: white; margin-top: 5px">Documentação</h3>
        <br />
        <draggable @change="updateDocumentacao" ghost-class="moving-card" :list="documentacao" class="list-group" group="a" item-key="name">
          <template #item="{ element }">
            <div class="px-1 py-2 inside-card" style="">
              <div class="d-flex no-block align-items-center bg-white flex-box-2">
                <div class="mr-2">
                  <img alt="Avatar" class="table-avatar" style="min-width: 20px" width="20" height="20" src="/assets/images/whats-logo.png" />
                </div>
                <div class="">
                  <dados-card-lead :lead="element" />
                </div>

                <div class="text-right" style="float: right">
                  <!-- <nova-proposta-button :planos="planos" :element="element" /> -->

                  <button
                    @click="openCadastroProposta(element)"
                    style="float: right; border-radius: 10%"
                    type="button"
                    class="btn btn-outline-success btn-sm"
                  >
                    <i class="fa fa-edit"></i>
                  </button>
                </div>
              </div>
            </div>
          </template>
        </draggable>
      </div>

      <!--Aguardando Pagamento -->
      <div class="col-lg-3 col-md-4 col-sm-6 card" style="background-color: #bdc6ff">
        <h3 style="color: white; margin-top: 5px">Aguardando Pagamento</h3>
        <br />
        <draggable :disabled="true" ghost-class="moving-card" :list="aguardando_pagamento" class="list-group" group="a" item-key="name">
          <template #item="{ element }">
            <div @click="exibirDetalhes(element)" data-toggle="modal" data-target="#detalhes-usuario" class="px-1 py-2 inside-card" style="">
              <div class="bg-white" style="text-align: center">
                <div class="mr-2" style="float: left">
                  <img alt="Avatar" class="table-avatar" style="min-width: 20px" width="20" height="20" src="/assets/images/whats-logo.png" />
                </div>
                <div>
                  <dados-card-lead :lead="element" />
                </div>

                <small id="name13" class="badge badge-default badge-info form-text text-white float-right">{{ element.status }}</small>
              </div>
            </div>
          </template>
        </draggable>
      </div>

      <!--Implantada -->
      <div class="col-lg-3 col-md-4 col-sm-6 card" style="background-color: #acd6be">
        <h3 style="color: white; margin-top: 5px">Implantada</h3>
        <br />
        <draggable :disabled="true" ghost-class="moving-card" :list="implantada" class="list-group" group="a" item-key="name">
          <template #item="{ element }">
            <div @click="exibirDetalhes(element)" data-toggle="modal" data-target="#detalhes-usuario" class="px-1 py-2 inside-card" style="">
              <div class="bg-white" style="text-align: center">
                <div class="mr-2" style="float: left">
                  <img alt="Avatar" class="table-avatar" style="min-width: 20px" width="20" height="20" src="/assets/images/whats-logo.png" />
                </div>
                <div>
                  <dados-card-lead :lead="element" />
                </div>
              </div>
            </div>
          </template>
        </draggable>
      </div>

      <!-- Detalhes Usuário -->
      <div ref="modal" class="modal fade" id="detalhes-usuario" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <form @submit.prevent>
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4>Nova indicação</h4>
                <br />

                <div class="form-group">
                  <label for="email">Nome</label>
                  <input :readonly="editar_contato" class="form-control" type="text" v-model="contato_selecionado.nome" />
                </div>

                <div class="form-group">
                  <label for="user_type">Possui CNPJ?</label>
                  <select class="form-control" v-model="contato_selecionado.possui_cnpj">
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
                  <select class="form-control" v-model="contato_selecionado.possui_plano">
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
                    class="form-control"
                    type="senha"
                    v-model="contato_selecionado.observacoes_corretor"
                    autocomplete="senha"
                    rows="5"
                    cols="40"
                  />
                </div>

                <h3>
                  Tarefas
                  <button
                    type="submit"
                    class="btn btn-primary"
                    @click="buscarTarefas()"
                    data-toggle="modal"
                    data-target="#cadastrar-modal"
                    style="margin-left: 15px"
                  >
                    Cadastrar tarefa
                  </button>
                </h3>

                <div class="table-responsive">
                  <table id="zero_config" class="table table-striped table-bordered no-wrap">
                    <thead>
                      <tr>
                        <th>Nome</th>
                        <th>Aguardando</th>
                        <th>Concluída</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-if="contato_selecionado.tarefas" v-for="tarefa in contato_selecionado.tarefas" :key="tarefa" :value="tarefa">
                        <td>{{ tarefa.tarefa.nome }}</td>

                        <td>{{ formatDate(tarefa.expira_em) }}</td>

                        <td><input type="checkbox" v-model="tarefa.tarefa_concluida" @change="concluirTarefa(tarefa)" /></td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <!-- CADASTRAR TAREFA -->
                <div class="modal fade" id="cadastrar-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <form @submit.prevent>
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-body">
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: black">&times;</button>

                          <h3>Cadastrar tarefa</h3>

                          <div class="row">
                            <div class="col-md-12">
                              <div class="row">
                                <div class="col-sm-12">
                                  <label for="nome">Tarefa</label>
                                  <select class="form-control" v-model="formData.tarefa">
                                    <option v-for="tarefa in tarefas" :key="tarefa" :value="tarefa">
                                      {{ tarefa.nome }}
                                    </option>
                                  </select>

                                  <span style="color: red" v-for="error in v$.tarefa.$errors" :key="error.$uid">
                                    {{ error.$message }}
                                  </span>
                                </div>

                                <div class="col-sm-12">
                                  <div class="form-group">
                                    <label for="nome">Expira em</label>
                                    <input class="form-control" type="date" v-model="formData.expira_em" />

                                    <span style="color: red" v-for="error in v$.expira_em.$errors" :key="error.$uid">
                                      {{ error.$message }}
                                    </span>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="card-footer" style="width: 100%">
                            <button type="submit" class="btn btn-primary" @click="cadastrarTarefa()">Salvar</button>
                            <button style="margin-left: 15px" class="btn btn-danger btn-flat" data-toggle="modal" data-target="#cadastrar-modal">
                              Cancelar
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>

                <button @click="salvarAlteracoes" class="btn btn-success btn-flat text-md-end">Salvar alterações</button>
                <button id="close_cadastrar" data-dismiss="modal" @click="close" class="btn btn-danger" style="margin-left: 15px">Voltar</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div v-if="cadastrar_proposta">
      <nova-proposta :contato="contato_selecionado" :voltar="voltar" :planos="planos" />
    </div>

    <div v-if="corrigir_pendencias">
      <corrigir-pendencias :proposta="proposta_selecionada" :voltar="voltar" :planos="planos" />
    </div>
  </div>
</template>

<script>
import moment from "moment";
import draggable from "vuedraggable";
import Swal from "sweetalert2/dist/sweetalert2.js";
import sweetAlert from "../controller/sweetAlert";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";

import useVueLidate from "@vuelidate/core";
import { required } from "../locales/i18n.js";
import { computed, reactive } from "vue";

export default {
  components: {
    draggable,
    Loading,
  },
  props: ["planos", "origens", "tarefas"],
  mixins: [sweetAlert, Swal],
  data() {
    return {
      indicacoes_cadastradas: [],
      coleta_de_dados: [],
      cotacao_enviada: [],
      em_negociacao: [],
      documentacao: [],
      negociacao_encerrada: [],
      aguardando_pagamento: [],
      implantada: [],
      pendencia: [],
      isLoading: false,
      contato_selecionado: "",
      proposta_selecionada: "",
      cadastrar_proposta: false,
      corrigir_pendencias: false,
      editar_contato: false,
      tarefas: [],
      contatos: [],
    };
  },

  setup() {
    const formData = reactive({
      tarefa: "",
      expira_em: "",
    });

    const rules = computed(() => {
      return {
        tarefa: { required },
        expira_em: { required },
      };
    });

    const v$ = useVueLidate(rules, formData);

    return { formData, v$ };
  },

  mounted() {
    this.buscarContatos();
  },

  methods: {
    async cadastrarTarefa() {
      const result = await this.v$.$validate();

      if (result) {
        this.isLoading = true;

        let data = {
          contato_id: this.contato_selecionado.id,
          tarefa_id: this.formData.tarefa.id,
          expira_em: this.formData.expira_em,
        };

        console.log("Cadastrando");
        console.log(data);

        axios
          .post(`/user/tarefa-lead/store`, data)
          .then((response) => {
            this.isLoading = false;

            if (response.data.success) {
              this.showSuccessMessageWithButton("Sucesso!", "Tarefa cadastrada!");
              this.buscarContatos();
            } else {
              this.showErrorMessageWithButton("Ops..", response.data.msg);
            }
          })
          .catch((error) => {
            this.isLoading = false;

            this.showErrorMessageWithButtonAndRefresh("Ops..", error.response.data);
            console.log(error.response.data);
          });
      }
    },

    buscarContatos() {
      this.isLoading = true;

      let data = {
        inicio: 0,
        tamanho: 100000,
      };

      console.log("Buscando");
      console.log(data);

      axios
        .post(`/user/contatos/search`, data)
        .then((response) => {
          this.contatos = response.data.contatos;
          this.total = response.data.total;

          console.log("CONTATOS ENCONTRADOS");
          console.log(this.contatos);

          this.inicializarListas();

          if (this.contato_selecionado != null) {
            let indice = this.contatos.findIndex((element) => element["id"] == this.contato_selecionado.id);

            if (indice >= 1) {
              this.contato_selecionado = this.contatos[indice];
            }
          }

          this.isLoading = false;
        })
        .catch((error) => {
          this.isLoading = false;

          console.log(error);

          this.showErrorMessageWithButtonAndRefresh("Ops..", error.response.data);
          console.log(error.response.data);
        });
    },

    buscarTarefas() {
      this.isLoading = true;

      let data = {
        inicio: 0,
        tamanho: 100000,
        etapa: this.contato_selecionado.status,
        todas_as_etapas: true,
      };

      console.log("Buscando");
      console.log(data);

      axios
        .post(`/user/tarefas/search`, data)
        .then((response) => {
          this.tarefas = response.data.tarefas;

          console.log("Tarefas encontradas");
          console.log(this.tarefas);

          this.isLoading = false;
        })
        .catch((error) => {
          this.isLoading = false;

          this.showErrorMessageWithButtonAndRefresh("Ops..", error.response.data);
          console.log(error.response.data);
        });
    },

    getStyleLead(lead) {
      if (lead.tarefas) {
        for (let i = 0; i < lead.tarefas.length; i++) {
          // Ignora tarefas que já foram concluídas
          if (lead.tarefas[i].concluida == 1) {
            continue; // Pula para a próxima iteração do loop
          }

          const hoje = new Date();
          hoje.setHours(0, 0, 0, 0);

          const dataVencimento = new Date(lead.tarefas[i].expira_em);
          dataVencimento.setHours(0, 0, 0, 0);

          //VENCIDO
          if (hoje > dataVencimento) {
            return "card-vencido";
          }

          //VENCE HOJE
          if (hoje.getTime() === dataVencimento.getTime()) {
            return "card-vencendo";
          }
        }
        return "";
      }
    },

    formatDate(date) {
      return moment(date).format("DD/MM/YYYY HH:mm:ss");
    },

    formatarNome(nome_completo) {
      let array_nome = nome_completo.split(" ");

      let nome = nome_completo;

      if (array_nome.length >= 2) {
        nome = array_nome[0] + " " + array_nome[1];
      }

      return nome;
    },

    corrigirPendencias(element) {
      this.contato_selecionado = element;

      let indice = this.findContato(element.id);

      this.proposta_selecionada = this.contatos[indice].proposta;

      this.corrigir_pendencias = true;
    },

    voltar() {
      this.cadastrar_proposta = false;
      this.corrigir_pendencias = false;
    },

    openCadastroProposta(element) {
      this.contato_selecionado = element;
      this.cadastrar_proposta = true;
    },

    concluirTarefa(tarefa) {
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
              id: tarefa.id,
              concluida: tarefa.tarefa_concluida ? 1 : 0,
            };

            console.log(data);

            axios
              .post(`/user/tarefa/concluir`, data)
              .then((response) => {
                this.showSuccessMessage("Tarefa concluída!");
                this.isLoading = false;
              })
              .catch((error) => {
                this.isLoading = false;
                this.showErrorMessageWithButton("Ops..", error);
                console.log(error);
              });
          }
        });
    },

    salvarAlteracoes() {
      this.isLoading = true;

      let data = {
        id: this.contato_selecionado.id,
        possui_cnpj: this.contato_selecionado.possui_cnpj,
        possui_plano: this.contato_selecionado.possui_plano,
        nome: this.contato_selecionado.nome,
        ocupacao: this.contato_selecionado.ocupacao,
        qtd_vidas: this.contato_selecionado.qtd_vidas,
        complemento: this.contato_selecionado.complemento,
        observacoes_corretor: this.contato_selecionado.observacoes_corretor,
      };

      axios
        .post(`/user/contato/update`, data)
        .then((response) => {
          this.showSuccessMessage("Observações adicionadas!");
          this.isLoading = false;
        })
        .catch((error) => {
          this.showErrorMessageWithButton("Ops..", error);
          console.log(error);
        });
    },

    inicializarListas() {
      for (let i = 0; i < this.contatos.length; i++) {
        if (this.contatos[i].status == "Indicação Cadastrada") {
          this.indicacoes_cadastradas.push(this.contatos[i]);
        } else if (this.contatos[i].status == "Coleta de dados") {
          this.coleta_de_dados.push(this.contatos[i]);
        } else if (this.contatos[i].status == "Pendências aguardando") {
          this.pendencia.push(this.contatos[i]);
        } else if (this.contatos[i].status == "Cotação enviada") {
          this.cotacao_enviada.push(this.contatos[i]);
        } else if (this.contatos[i].status == "Em negociação") {
          this.em_negociacao.push(this.contatos[i]);
        } else if (this.contatos[i].status == "Documentação") {
          this.documentacao.push(this.contatos[i]);
        } else if (this.contatos[i].status == "Negociação encerrada") {
          this.negociacao_encerrada.push(this.contatos[i]);
        } else if (
          this.contatos[i].status == "Aguardando pagamento" ||
          this.contatos[i].status == "Aguardando aprovação" ||
          this.contatos[i].status == "Aguardando vigência" ||
          this.contatos[i].status == "Aguardando parcelas"
        ) {
          this.aguardando_pagamento.push(this.contatos[i]);
        } else if (this.contatos[i].status == "Implantada") {
          this.implantada.push(this.contatos[i]);
        }
      }
    },

    atualizarContato(id, element, status) {
      if (element.proposta_id) {
        this.$swal
          .fire({
            title: "Ops...",
            text: "Esse cliente possui pendências!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ok",
          })
          .then((result) => {
            window.location.reload();
          });
      } else {
        this.isLoading = true;

        let data = {
          id: id,
          status: status,
        };

        axios
          .post(`/user/contato/update-status`, data)
          .then((response) => {
            this.showSuccessMessage("Contato atualizado!");
            this.isLoading = false;
            //window.location.reload();
          })
          .catch((error) => {
            this.showErrorMessageWithButton("Ops..", error);
            console.log(error);
          });
      }
    },

    updateCadastrado: function (evt) {
      if (evt.added) {
        console.log("Atualizando status de " + evt.added.element.name + " para Cadastrado");

        this.atualizarContato(evt.added.element.id, evt.added.element, "Cadastrado");
      }
    },

    updateColeta: function (evt) {
      if (evt.added) {
        console.log("Atualizando status de " + evt.added.element.name + " para Coleta de dados");

        this.atualizarContato(evt.added.element.id, evt.added.element, "Coleta de dados");
      }
    },

    updateCotacaoEnviada: function (evt) {
      if (evt.added) {
        this.atualizarContato(evt.added.element.id, evt.added.element, "Cotação enviada");
      }
    },

    updateEmNegociacao: function (evt) {
      if (evt.added) {
        this.atualizarContato(evt.added.element.id, evt.added.element, "Em negociação");
      }
    },

    updateDocumentacao: function (evt) {
      if (evt.added) {
        this.atualizarContato(evt.added.element.id, evt.added.element, "Documentação");
      }
    },

    updateNegociacaoEncerrada: function (evt) {
      if (evt.added) {
        let html = "Informe o motivo da negociação ter sido <b style='color:red'> encerrada</b>";

        this.$swal
          .fire({
            title: "Confirmação",
            html: html,
            icon: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Confirmar",
            input: "textarea",
            inputAttributes: {
              autocapitalize: "off",
              required: false,
            },
            inputValidator: (result) => {
              if (!result) {
                return "Insira uma justificativa!";
              }
            },
          })
          .then((result) => {
            if (result.value) {
              this.atualizarContato(evt.added.element.id, evt.added.element, "Negociação encerrada");
              this.enviarJustificativa(evt.added.element.id, result.value);
            } else {
              window.location.reload();
            }
          });
      }
    },

    enviarJustificativa(id, justificativa) {
      this.isLoading = true;

      let data = {
        id: id,
        justificativa: justificativa,
      };

      axios
        .post(`/user/contato/add-justificativa`, data)
        .then((response) => {
          this.isLoading = false;
        })
        .catch((error) => {
          this.showErrorMessageWithButton("Ops..", error);
          console.log(error);
        });
    },

    exibirDetalhes(element) {
      this.contato_selecionado = element;

      if (this.contato_selecionado.tarefas) {
        for (let i = 0; i < this.contato_selecionado.tarefas.length; i++) {
          this.contato_selecionado.tarefas[i].tarefa_concluida = this.contato_selecionado.tarefas[i].concluida == 1 ? true : false;
        }
      }

      console.log(element);

      let indice = this.findContato(element.id);

      this.proposta_selecionada = this.contatos[indice].proposta;
    },

    converterCNPJ(possui_cnpj) {
      if (possui_cnpj == 0) {
        return "Não";
      } else {
        return "Sim";
      }
    },

    findContato(id) {
      let contato = this.contatos.findIndex((element) => element.contato["id"] == id);

      return contato;
    },
  },
};
</script>
<style scoped>
.card-vencido {
  background-color: red !important;
}

.card-vencido p {
  color: white !important;
}

.card-vencendo {
  background-color: yellow !important;
}

.card-vencendo p {
  color: white !important;
}

.kanban-board {
  width: auto;
  height: auto;
  overflow-x: scroll;
  overflow-y: hidden;
  white-space: nowrap;
}

.inside-card {
  margin: 2%;
  border-color: black;
  border-radius: 5%;
  border-width: 1px;
  border-style: solid;
  background-color: white;
  text-align: center;
}

.list-group {
  cursor: pointer;
}

.card {
  border-color: black;
  border-radius: 5%;
  border-width: 1px;
  border-radius: 3%;
  text-align: center;
  padding-bottom: 3%;
  display: inline-block;
  vertical-align: top;
  min-width: 300px;
}

.text-inside-card {
  text-align: center;
}

.flex-box {
  display: flex;
  justify-content: center;
}

.flex-box-2 {
  display: flex;
  justify-content: space-between;
}
</style>
