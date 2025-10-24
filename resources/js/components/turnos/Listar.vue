<template>
  <admin-create-turnos />
  <loading v-model:active="isLoading" :can-cancel="false" :is-full-page="true" />
  <div class="table-responsive">
    <table id="zero_config" class="table table-striped table-bordered no-wrap">
      <thead>
        <tr>
          <th>Nome</th>
          <th>Hora Início</th>
          <th>Hora Fim</th>
          <th style="width: 150px">Status</th>
          <th style="width: 50px">Ações</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="turno in turnos" :key="turno" :value="turno">
          <td>{{ turno.nome }}</td>
          <td>{{ turno.hora_inicio }}:00</td>
          <td>{{ turno.hora_fim }}:00</td>
          <td>
            <select
              @change="updateStatusTurno(turno)"
              :style="turno.ativo ? 'color: green; font-weight: bold' : ''"
              class="form-control"
              id="user_type"
              v-model="turno.ativo"
            >
              <option value="0">Inativo</option>
              <option value="1">Ativo</option>
            </select>
          </td>
          <td>
            <button @click="selecionarTurno(turno)" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#gerenciar-modal">
              Gerenciar
              <i class="fa fa-search"></i>
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="modal fade" id="gerenciar-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form @submit.prevent>
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4
              style="
                font-weight: bold;
                font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
                color: black;
              "
            >
              Gerenciar Turno
            </h4>
            <br />

            <div class="form-group">
              <label for="email">Nome</label>
              <input class="form-control" id="email" type="text" v-model="nome_turno" />
            </div>

            <div class="row">
              <div class="col-6 form-group">
                <label for="user_type">Hora Início</label>
                <select class="form-control" id="user_type" v-model="hora_inicio">
                  <option value="0">00:00</option>
                  <option value="1">01:00</option>
                  <option value="2">02:00</option>
                  <option value="3">03:00</option>
                  <option value="4">04:00</option>
                  <option value="5">05:00</option>
                  <option value="6">06:00</option>
                  <option value="7">07:00</option>
                  <option value="8">08:00</option>
                  <option value="9">09:00</option>
                  <option value="10">10:00</option>
                  <option value="11">11:00</option>
                  <option value="12">12:00</option>
                  <option value="13">13:00</option>
                  <option value="14">14:00</option>
                  <option value="15">15:00</option>
                  <option value="16">16:00</option>
                  <option value="17">17:00</option>
                  <option value="18">18:00</option>
                  <option value="19">19:00</option>
                  <option value="20">20:00</option>
                  <option value="21">21:00</option>
                  <option value="22">22:00</option>
                  <option value="23">23:00</option>
                </select>
              </div>

              <div class="col-6 form-group">
                <label for="user_type">Hora Fim</label>
                <select class="form-control" id="user_type" v-model="hora_fim">
                  <option value="0">00:00</option>
                  <option value="1">01:00</option>
                  <option value="2">02:00</option>
                  <option value="3">03:00</option>
                  <option value="4">04:00</option>
                  <option value="5">05:00</option>
                  <option value="6">06:00</option>
                  <option value="7">07:00</option>
                  <option value="8">08:00</option>
                  <option value="9">09:00</option>
                  <option value="10">10:00</option>
                  <option value="11">11:00</option>
                  <option value="12">12:00</option>
                  <option value="13">13:00</option>
                  <option value="14">14:00</option>
                  <option value="15">15:00</option>
                  <option value="16">16:00</option>
                  <option value="17">17:00</option>
                  <option value="18">18:00</option>
                  <option value="19">19:00</option>
                  <option value="20">20:00</option>
                  <option value="21">21:00</option>
                  <option value="22">22:00</option>
                  <option value="23">23:00</option>
                </select>
              </div>
            </div>

            <button @click="updateTurno()" style="color: white" class="btn btn-success" data-toggle="modal" data-target="#gerenciar-modal">
              Salvar alterações
              <i class="fa fa-check"></i>
            </button>
            <button
              @click="deletarTurno()"
              style="color: white; margin-left: 10px"
              class="btn btn-danger"
              data-toggle="modal"
              data-target="#gerenciar-modal"
            >
              Deletar Turno
              <i class="fa fa-trash"></i>
            </button>

            <br />
            <br />
            <div>
              <!--<label for="user_type" style="font-weight: bold">Supervisor</label> -->
              <br />
              <div class="input-form">
                <!--
                <select class="custom-select" id="inputGroupSelect04" v-model="supervisor_selecionado" @change="buscarCorretores()">
                  <option v-for="supervisor in supervisores" :key="supervisor" :value="supervisor">
                    {{ supervisor.name }}
                  </option>
                </select> -->

                <v-autocomplete
                  label="Supervisor"
                  id="comitente"
                  variant="outlined"
                  :items="supervisores"
                  item-title="name"
                  return-object
                  v-model="supervisor_selecionado"
                  density="compact"
                  style="text-transform: uppercase"
                  @update:modelValue="buscarCorretores"
                  hide-details
                >
                  <template #prepend>
                    <v-btn variant="text" :disabled="!supervisor_selecionado" @click="supervisor_selecionado = null">
                      <v-icon icon="fa-solid fa-trash" color="red" style="cursor: pointer" />
                    </v-btn>
                  </template>
                </v-autocomplete>
              </div>
            </div>

            <div v-if="supervisor_selecionado">
              <!-- <label for="user_type">Corretor</label> -->
              <br />
              <br />
              <!--
              <div class="input-group">
                <select class="custom-select" id="inputGroupSelect04" v-model="corretor_selecionado">
                  <option v-for="corretor in corretores" :key="corretor" :value="corretor">
                    {{ corretor.name }}
                  </option>
                </select>
                <div class="input-group-append">
                  <button class="btn btn-outline-secondary" type="button" @click="adicionarTurno()">Add</button>
                </div>
              </div>
              -->
              <div class="input-group">
                <v-autocomplete
                  label="Corretor"
                  clearable
                  id="comitente"
                  variant="outlined"
                  :items="corretores"
                  item-title="name"
                  return-object
                  v-model="corretor_selecionado"
                  density="compact"
                  style="text-transform: uppercase"
                >
                  <template #prepend>
                    <v-btn variant="text" :disabled="!supervisor_selecionado" @click="corretor_selecionado = null">
                      <v-icon icon="fa-solid fa-trash" color="red" style="cursor: pointer" />
                    </v-btn>
                  </template>
                </v-autocomplete>
              </div>

              <button :class="corretor_selecionado ? 'btn btn-outline-success' : 'btn btn-outline-danger'" type="button" @click="adicionarTurno()">
                Adicionar corretor
              </button>
            </div>

            <br />
            <br />

            <button v-if="editado" @click="salvar()" class="btn btn-success btn-success" data-toggle="modal" data-target="#gerenciar-modal">
              Salvar
            </button>

            <table id="zero_config" class="table table-striped table-bordered no-wrap">
              <thead>
                <tr>
                  <th>Nome</th>
                  <th>Alterar Ordem</th>
                  <th>Status corretor</th>
                  <th>Limite leads</th>
                  <th>Telefone</th>
                  <th>Ações</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="user in users_turno" :key="user" :value="user" class="hover">
                  <td style="text-align: center; padding: 0px 0px 0px 0px">{{ user.nome }}</td>
                  <td style="text-align: center; padding: 0px 0px 0px 0px">
                    <button @click="moverParaCima(user)" class="btn btn-success btn-sm">
                      <img alt="Avatar" class="table-avatar" style="width: 20px; opacity: 0.8" src="/assets/images/arrow-up-circle.svg" />
                    </button>
                    <button @click="moverParaBaixo(user)" class="btn btn-info btn-sm">
                      <img alt="Avatar" class="filter-green" style="width: 20px; opacity: 0.8" src="/assets/images/arrow-down-circle.svg" />
                    </button>
                  </td>
                  <td>
                    <select class="form-control" id="user_type" v-model="user.status_corretor" @change="alterarStatusCorretor(user)">
                      <option value="Bonus">Bonus</option>
                      <option value="Extra">Extra</option>
                      <option value="Habilitado">Habilitado</option>
                    </select>
                  </td>
                  <td>
                    <div class="input-form" v-if="user.user">
                      <v-card class="mx-auto" color="surface-light" v-if="!user.editar_qtd_leads">
                        <v-text-field
                          readonly="true"
                          bg-color="#c9c9c9"
                          append-inner-icon="fa-solid fa-pen"
                          density="compact"
                          label="Limite leads"
                          v-model="user.user.limite_leads"
                          variant="solo"
                          hide-details
                          single-line
                          @click:append-inner="user.editar_qtd_leads = true"
                        ></v-text-field>
                      </v-card>

                      <v-card class="mx-auto" color="surface-light" v-else>
                        <v-text-field
                          :loading="loading"
                          density="compact"
                          label="Limite leads"
                          v-model="user.user.limite_leads"
                          variant="solo"
                          hide-details
                          single-line
                        >
                          <template #append-inner>
                            <v-icon icon="fa-solid fa-check" color="green" style="cursor: pointer" @click="editarQtdLeads(user)" />
                          </template>
                        </v-text-field>
                      </v-card>
                    </div>
                  </td>
                  <td>
                    <span v-if="user.user">{{ user.user.telefone }}</span>
                  </td>
                  <td style="text-align: center; padding: 0px 0px 0px 0px">
                    <button @click="deletar(user)" class="btn btn-danger btn-sm">
                      Remover
                      <i class="fa fa-trash"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>

            <button style="color: white" class="btn btn-warning" data-toggle="modal" data-target="#gerenciar-modal">Cancelar</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import sweetAlert from "../controller/sweetAlert";
import moment from "moment";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";

export default {
  mixins: [sweetAlert],

  components: { Loading },

  props: ["supervisores"],

  data() {
    return {
      corretor_selecionado: "",
      supervisor_selecionado: "",
      lead_selecionado: "",
      turno_selecionado: "",
      users_turno: "",
      editado: false,
      corretores: "",
      isLoading: false,
      turnos: [],
      nome_turno: "",
      hora_inicio: "",
      hora_fim: "",
      corretores_turno: "",
    };
  },

  mounted() {
    console.log(this.turnos);

    this.buscarTurnos();
  },

  methods: {
    editarQtdLeads(user) {
      let data = {
        turno_id: this.turno_selecionado.id,
        user_id: user.id,
        limite_leads: user.user.limite_leads,
      };

      console.log("Buscando turnos");
      console.log(data);

      axios
        .post(`/admin/user/update_dados_turno`, data)
        .then((response) => {
          this.showSuccessMessage("Corretor atualizado!");

          user.editar_qtd_leads = false;
        })
        .catch((error) => {
          this.showErrorMessageWithButton("Ops..", error);
          this.isLoading = false;
          console.log(error);
        });
    },

    buscarTurnos() {
      let data = {};

      console.log("Buscando turnos");
      console.log(data);

      axios
        .post(`/admin/turnos/search`, data)
        .then((response) => {
          this.turnos = response.data.turnos;

          if (this.turno_selecionado && this.turno_selecionado.id) {
            console.log("Turno selecionado");
            console.log(this.turno_selecionado);
            console.log(this.turnos);

            let indice = this.turnos.findIndex((element) => element.id == this.turno_selecionado.id);

            console.log("Turno 01");
            console.log(indice);

            if (indice >= 0) {
              this.selecionarTurno(this.turnos[indice]);
            }
          }
        })
        .catch((error) => {
          this.showErrorMessageWithButton("Ops..", error);
          this.isLoading = false;
          console.log(error);
        });
    },

    buscarCorretoresTurno() {
      let ordem = "";

      if (typeof this.turno_selecionado.ordem_lista == "string") {
        ordem = JSON.parse(this.turno_selecionado.ordem_lista);
      } else {
        ordem = this.turno_selecionado.ordem_lista;
      }

      let ids_corretores = [];

      for (let i = 0; i < ordem.length; i++) {
        ids_corretores.push(ordem[i].id);
      }

      let data = {
        ids_corretores: ids_corretores,
      };

      console.log("Buscando turnos");
      console.log(data);

      axios
        .post(`/admin/turnos/search_corretores`, data)
        .then((response) => {
          this.corretores_turno = response.data.corretores;

          this.getUsers();

          console.log("Corretores turno");
          console.log(this.corretores_turno);
          console.log(this.data);
        })
        .catch((error) => {
          this.showErrorMessageWithButton("Ops..", error);
          this.isLoading = false;
          console.log(error);
        });
    },

    updateStatusTurno(turno) {
      let data = {
        turno_id: turno.id,
        ativo: turno.ativo,
      };

      this.isLoading = true;

      axios
        .post(`/admin/turnos/update_status`, data)
        .then((response) => {
          this.showSuccessMessage("Turno atualizado!");

          window.location.reload();
        })
        .catch((error) => {
          this.showErrorMessageWithButton("Ops..", error);
          console.log(error);
        });
    },

    updateTurno() {
      let data = {
        turno_id: this.turno_selecionado.id,
        nome: this.nome_turno,
        hora_inicio: this.hora_inicio,
        hora_fim: this.hora_fim,
      };

      console.log(data);

      axios
        .post(`/admin/turnos/update`, data)
        .then((response) => {
          this.showSuccessMessage("Turno atualizado!");

          window.location.reload();
        })
        .catch((error) => {
          this.showErrorMessageWithButton("Ops..", error);
          console.log(error);
        });
    },

    deletarTurno() {
      this.confirmWarning("Deseja DELETAR este turno?", () => {
        let data = {
          turno_id: this.turno_selecionado.id,
        };

        axios
          .post(`/admin/turnos/delete`, data)
          .then((response) => {
            this.showSuccessMessage("Turno deletado!");

            window.location.reload();
          })
          .catch((error) => {
            this.showErrorMessageWithButton("Ops..", error);
            console.log(error);
          });
      });
    },

    buscarCorretores() {
      if (this.supervisor_selecionado) {
        this.isLoading = true;

        let data = {
          supervisor_id: this.supervisor_selecionado.id,
          tipo_corretor: "crm",
        };

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
      }
    },

    atualizarArrayTurno(turno) {
      for (let i = 0; i < this.turnos.length; i++) {
        if (this.turnos[i].id == turno.id) {
          this.turnos[i] = turno;
        }
      }
    },

    deletar(user) {
      this.confirmWarning("Deseja remover " + user.nome + " deste turno?", () => {
        let indice = this.findUser(user.id);

        this.users_turno.splice(indice, 1)[0];

        let data = {
          user_id: user.id,
          turno_id: this.turno_selecionado.id,
          ordem_lista: this.users_turno,
        };

        console.log(data);

        axios
          .post(`/admin/turnos/user/delete`, data)
          .then((response) => {
            this.showSuccessMessage("Corretor removido!");
            this.selecionarTurno(response.data.turno);
            this.atualizarArrayTurno(response.data.turno);
            //window.location.reload();
          })
          .catch((error) => {
            this.showErrorMessageWithButton("Ops..", error);
            console.log(error);
          });
      });
    },

    salvar() {
      let data = {
        turno_id: this.turno_selecionado.id,
        ordem_lista: this.users_turno,
      };

      console.log(data);

      axios
        .post(`/admin/turnos/ordem/update`, data)
        .then((response) => {
          this.showSuccessMessage("Corretores atualizados!");

          window.location.reload();
        })
        .catch((error) => {
          this.showErrorMessageWithButton("Ops..", error);
          console.log(error);
        });
    },

    getUsers() {
      let json = {};
      let json2 = {};

      if (typeof this.turno_selecionado.ordem_lista == "string") {
        json = JSON.parse(this.turno_selecionado.ordem_lista);
      } else {
        json = this.turno_selecionado.ordem_lista;
      }

      if (typeof this.turno_selecionado.corretores_aguardando == "string") {
        json2 = JSON.parse(this.turno_selecionado.corretores_aguardando);
      } else {
        json2 = this.turno_selecionado.corretores_aguardando;
      }

      this.users_turno = json;

      //this.users_turno = json.sort((a, b) => (a.ordem > b.ordem) ? 1 : ((b.ordem > a.ordem) ? -1 : 0));

      var res = [];
      var ids = [];

      for (var i in this.users_turno) {
        let user = this.users_turno[i];
        let indice = this.corretores_turno.findIndex((element) => element["id"] == user.id);

        let user_object = this.corretores_turno[indice];

        user.user = user_object;

        res.push(user);
        ids.push(this.users_turno[i].id);
      }

      console.log("ARRAY 02");
      console.log(ids);
      console.log(json2);

      for (var i in json2) {
        if (!ids.includes(json2[i].id)) {
          let user = json2[i];
          let indice = this.corretores_turno.findIndex((element) => element["id"] == user.id);

          let user_object = this.corretores_turno[indice];

          user.user = user_object;

          res.push(user);
        }
      }

      this.users_turno = res;

      console.log("RESULTADO FINAL");
      console.log(this.users_turno);

      console.log("É array? " + Array.isArray(this.users_turno));
    },

    moverParaCima(user) {
      this.editado = true;

      let indice = this.findUser(user.id);

      if (indice > 0) {
        const element = this.users_turno.splice(indice, 1)[0];
        console.log(element);

        this.users_turno.splice(indice - 1, 0, element);
      }
    },

    alterarStatusCorretor(user) {
      this.editado = true;

      let indice = this.findUser(user.id);

      console.log("INDICE - " + indice);
      console.log("INDICE - " + this.users_turno.length);

      if (indice < this.users_turno.length - 1) {
        this.users_turno[indice].status_corretor = user.status_corretor;
      }
    },

    moverParaBaixo(user) {
      this.editado = true;

      let indice = this.findUser(user.id);

      console.log("INDICE - " + indice);
      console.log("INDICE - " + this.users_turno.length);

      if (indice < this.users_turno.length - 1) {
        const element = this.users_turno.splice(indice, 1)[0];
        console.log(element);

        this.users_turno.splice(indice + 1, 0, element);
      }
    },

    findUser(id) {
      let user = this.users_turno.findIndex((element) => element["id"] == id);

      return user;
    },

    selecionarTurno(turno) {
      this.editado = false;

      this.turno_selecionado = turno;

      this.nome_turno = this.turno_selecionado.nome;
      this.hora_inicio = this.turno_selecionado.hora_inicio;
      this.hora_fim = this.turno_selecionado.hora_fim;

      console.log("Selecionado");
      console.log(this.turno_selecionado);

      this.buscarCorretoresTurno();
    },

    adicionarTurno() {
      if (this.corretor_selecionado) {
        let data = {
          user_id: this.corretor_selecionado.id,
          turno_id: this.turno_selecionado.id,
        };

        console.log(data);

        axios
          .post(`/admin/turnos/add`, data)
          .then((response) => {
            if (response.data.success) {
              this.showSuccessMessage("Adicionado ao turno!");

              console.log("Turno novo");
              console.log(response.data.turno);

              this.selecionarTurno(response.data.turno);
              this.atualizarArrayTurno(response.data.turno);

              //window.location.reload();
            } else {
              this.showErrorMessageWithButton("Ops...", response.data.msg);

              this.buscarTurnos();
            }
          })
          .catch((error) => {
            this.showErrorMessageWithButton("Ops..", error);
            console.log(error);
          });
      }
    },

    selecionarLead(lead) {
      this.lead_selecionado = lead;
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
  },
};
</script>

<style scoped>
/* Ajustes opcionais para garantir o alinhamento perfeito */
.v-row {
  flex-wrap: nowrap; /* Impede que o botão quebre para a linha de baixo em telas pequenas */
}

.readonly-field-clickable-icon {
  background-color: gray !important;
  cursor: default !important;
}

.hover:hover {
  background-color: #7dabd3;
  color: white;
  cursor: pointer;
}

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
