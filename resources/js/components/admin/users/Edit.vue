<template>
  <div>
    <loading v-model:active="isLoading" :can-cancel="false" :is-full-page="true" />

    <h4>Editar usuario</h4>
    <br />
    <div class="form-group">
      <label for="email">Nome</label>
      <input class="form-control" id="nome" type="text" v-model="nome" autocomplete="name" />
    </div>

    <div class="form-group">
      <label for="email">Email</label>
      <input class="form-control" id="email" type="email" v-model="email" autocomplete="email" />
    </div>

    <div class="form-group">
      <label for="password">Senha</label>
      <input class="form-control" id="senha" type="senha" v-model="senha" autocomplete="senha" />
    </div>

    <div class="form-group">
      <label for="email">Telefone</label>
      <input class="form-control" id="email" v-model="telefone" autocomplete="email" />
    </div>

    <div class="form-group">
      <label for="email">Código Interno</label>
      <input class="form-control" id="email" v-model="codigo_interno" autocomplete="email" />
    </div>

    <div class="form-group">
      <label for="email">Limite leads</label>
      <input class="form-control" id="email" v-model="limite_leads" autocomplete="email" />
    </div>

    <div class="form-group">
      <label for="user_type">Adicionar origem</label>

      <div class="input-group mb-3">
        <select class="form-control" id="user_type" v-model="origem_selecionada">
          <option v-for="origem in origens" :key="origem" :value="origem">
            {{ origem.nome }}
          </option>
        </select>
        <div class="input-group-prepend">
          <button type="button" class="btn btn-success" @click="addOrigem()">
            Add
            <i style="margin-left: 3px" class="nav-icon far fa-plus-square"></i>
          </button>
        </div>
      </div>
    </div>

    <table id="zero_config" class="table table-striped table-bordered no-wrap">
      <thead>
        <tr>
          <th>Nome</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="origem in user_origens" :key="user" :value="user">
          <td>{{ origem.nome }}</td>
          <td>
            <button @click="deletarOrigem(origem)" class="btn btn-danger btn-flat" data-toggle="modal" data-target="#gerenciar-modal">
              Deletar
              <i class="fa fa-trash"></i>
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <button class="btn btn-primary btn-flat" @click="update">Salvar alterações</button>

    <button id="close_editar" data-dismiss="modal" @click="close" class="btn btn-danger" style="margin-left: 15px; color: white">Cancelar</button>
  </div>
</template>

<script>
import wppController from "../../controller/wppController";
import sweetAlert from "../../controller/sweetAlert";
import Swal from "sweetalert2/dist/sweetalert2.js";

import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";

export default {
  mixins: [wppController, sweetAlert, Swal],

  props: ["user", "acaoRealizada", "closeModal"],

  components: { Loading },

  data() {
    return {
      container: {},
      nome: "",
      email: "",
      senha: "",
      tipo_usuario: "",
      isLoading: false,
      codigo_interno: "",
      telefone: "",
      supervisor_selecionado: "",
      gerente_selecionado: "",
      limite_leads: 10,
      origens: "",
      user_origens: "",
      origem_selecionada: "",
    };
  },

  mounted() {
    console.log("EDITAR USER");
    console.log(this.user);

    this.buscarOrigens();
    this.buscarOrigensUsuario();

    this.nome = this.user.name;
    this.email = this.user.email;
    this.codigo_interno = this.user.codigo_interno;
    this.telefone = this.user.telefone;
    this.supervisor_id = this.user.supervisor_id;
    this.tipo_usuario = this.user.tipo_usuario;
    this.gerente_id = this.user.gerente_id;
    this.tipo_corretor = this.user.tipo_corretor;
    this.limite_leads = this.user.limite_leads;
  },

  methods: {
    addOrigem() {
      let data = {
        user_id: this.user.id,
        origem_id: this.origem_selecionada.id,
      };

      console.log(data);

      axios
        .post(`/admin/user-origens/add`, data)
        .then((response) => {
          this.showSuccessMessageWithButton("Sucesso", "Origem adicionada com sucesso!");
          this.buscarOrigensUsuario();
        })
        .catch((error) => {
          console.log(error);
          this.showErrorMessageWithButton("Ops..", error);
          console.log(error);
        });
    },

    deletarOrigem(origem) {
      let html = "Deseja <b style='font-size:20px; color: red'> Deletar </b> essa origem deste usuário? ";

      this.$swal
        .fire({
          title: "Confirmação",
          html: html,
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Sim!",
          cancelButtonText: "Cancelar",
        })
        .then((result) => {
          if (result.value) {
            let data = {
              user_id: this.user.id,
              origem_id: origem.id,
            };

            console.log(origem);
            console.log(data);

            axios
              .post(`/admin/user-origens/delete`, data)
              .then((response) => {
                this.showSuccessMessageWithButton("Sucesso", "Origem deletada com sucesso!");
                this.buscarOrigensUsuario();
              })
              .catch((error) => {
                console.log(error);
                this.showErrorMessageWithButton("Ops..", error);
                console.log(error);
              });
          }
        });
    },

    getOrigens() {},

    update() {
      this.isLoading = true;

      let data = {
        user_id: this.user.id,
        nome: this.nome,
        email: this.email,
        senha: this.senha,
        codigo_interno: this.codigo_interno,
        telefone: this.telefone,
        limite_leads: this.limite_leads,
      };

      console.log("CRIANDO");
      console.log(data);

      axios
        .post(`/admin/users/update`, data)
        .then((response) => {
          console.log("Usuário criado!");
          console.log(response);

          let btn = document.getElementById("close_editar");
          btn.click();

          this.showSuccessMessage("Usuário atualizado com sucesso!");
          this.acaoRealizada("editar");
        })
        .catch((error) => {
          this.isLoading = false;

          this.showErrorMessageWithButton("Ops..", error.response.data);
          console.log(error.response.data);
        });
    },

    close() {
      let btn = document.getElementById("close_editar");
      btn.click();

      this.closeModal();
    },

    buscarOrigensUsuario() {
      let data = {
        user_id: this.user.id,
      };

      console.log(data);

      axios
        .post(`/admin/user-origens/search`, data)
        .then((response) => {
          this.user_origens = response.data.user_origens;

          console.log("user_origens");
          console.log(this.user_origens);
        })
        .catch((error) => {
          this.showErrorMessageWithButton("Ops..", error.response.data);
          console.log(error.response.data);
        });
    },

    buscarOrigens() {
      let data = {
        inicio: 0,
        tamanho: 10000000,
      };

      console.log(data);

      axios
        .post(`/admin/origens/search`, data)
        .then((response) => {
          this.origens = response.data.origens;

          console.log("origens");
          console.log(this.origens);
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
.swal-modal {
  color: green;
  background-color: rgba(63, 255, 106, 0.69);
  border: 3px solid white;
}

.swal-overlay {
  background-color: rgba(43, 165, 137, 0.45);
}
</style>
