<template>
  <div>
    <br />
    <admin-create-user :supervisores="supervisores" :gerentes="gerentes" />
    <div class="card">
      <loading v-model:active="isLoading" :can-cancel="false" :is-full-page="true" />
      <div class="card-body p-3">
        <div class="row">
          <br />
          <div class="col-12 col-sm-12 col-md-6">
            <div class="form-group">
              <form v-on:submit.prevent="buscarUsuarios()">
                <label for="user_type">Nome/Email</label>
                <div class="input-group mb-3">
                  <!-- /btn-group -->
                  <input v-model="nome" type="text" class="form-control" />

                  <div class="input-group-prepend">
                    <button @click="buscarUsuarios()" style="border-radius: 2px" type="button" class="btn btn-info">Buscar</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <div class="col-12 col-sm-12 col-md-6">
            <div class="form-group">
              <label for="user_type">Tipo de Usuário</label>
              <select class="form-control" id="user_type" v-model="tipo_usuario" @change="buscarUsuarios()">
                <option value=""></option>
                <option value="user">Usuário</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="card-body p-0">
        <table class="table">
          <thead>
            <tr>
              <th style="width: 25%">Nome</th>
              <th style="width: 25%">Email</th>
              <th style="width: 10%">Função</th>
              <th style="width: 25%">Data da criação</th>
              <th style="width: 12%">Gerenciar</th>
              <th style="width: 13%">Deletar</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="usuario in users" :key="usuario.id">
              <td data-label="Nome">{{ usuario.usuario.name }}</td>
              <td data-label="Email">{{ usuario.usuario.email }}</td>
              <td data-label="Email">{{ usuario.roles[0].name }}</td>
              <td data-label="Data">
                {{ formatDate(usuario.usuario.created_at) }}
              </td>
              <td>
                <button
                  style="width: 100%"
                  @click="(usuario_selecionado = usuario.usuario), (editar_user = true)"
                  type="button"
                  class="btn btn-primary btn-sm"
                  data-toggle="modal"
                  data-target="#editar-modal"
                >
                  Gerenciar
                </button>
              </td>
              <td>
                <button
                  style="width: 100%"
                  @click="handleRemove('Deseja deletar ' + usuario.usuario.name, usuario.usuario.id, remove)"
                  type="button"
                  class="btn btn-danger btn-sm"
                >
                  Deletar
                  <i class="fa fa-trash"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- /.card-body -->
    </div>
  </div>

  <!-- EDITAR USERS -->
  <div
    class="modal fade"
    id="editar-modal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="myModalLabel"
    aria-hidden="true"
    @click.self="editar_user = false"
    @keydown.esc="editar_user = false"
  >
    <form @submit.prevent>
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body" v-if="editar_user">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: black">&times;</button>
            <admin-edit-user :closeModal="closeModal" :acaoRealizada="acaoRealizada" :user="usuario_selecionado" />
          </div>

          <button
            id="editar_campanha"
            type="submit"
            class="btn btn-primary"
            @click="cadastrar_blockname = true"
            data-toggle="modal"
            data-target="#editar-modal"
            style="display: none"
          >
            <i class="fa-solid fa-plus" style="margin-right: 5px"></i>
            Cancelar
          </button>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import wppController from "../controller/wppController";
import sweetAlert from "../controller/sweetAlert";
import Swal from "sweetalert2/dist/sweetalert2.js";
import moment from "moment";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";

export default {
  mixins: [wppController, sweetAlert, Swal],

  components: { Loading },

  props: ["usuarios", "supervisores", "gerentes"],

  data() {
    return {
      nome: "",
      isLoading: false,
      tipo_usuario: "",
      users: "",
      editar_user: false,
      usuario_selecionado: ""
    };
  },

  mounted() {
    console.log("MOUNTEDD");
    console.log(this.usuarios);
    this.users = this.usuarios;
  },

  methods: {
    acaoRealizada(acao) {
      if (acao == "editar") {
        this.editar_user = false;
        this.buscarUsuarios(true);
      }
    },

    closeModal() {
      this.editar_user = false;
    },

    show(usuario) {
      this.editUser(usuario.name, usuario.email, usuario.telefone, (response) => {
        if (!response.isDismissed) {
          let data = {
            nome: response.value[0],
            email: response.value[1],
            senha: response.value[2],
            telefone: response.value[3],
            user_id: usuario.id,
          };

          console.log("DADOS");
          console.log(data);
          this.validateFields(data);
        }
      });
    },

    validateFields(data) {
      if (!data.nome || data.nome == "") {
        this.showErrorMessageWithButton("Ops...", "O campo nome é obrigatório!");
      } else {
        this.update(data);
      }
    },

    update(data) {
      axios
        .post(`/admin/users/update`, data)
        .then((response) => {
          console.log("Usuário atualizado!");
          console.log(response);

          this.showSuccessMessage("Usuário atualizado!");

          window.location.reload();
        })
        .catch((error) => {
          this.showErrorMessageWithButton("Ops..", error.response.data);
          console.log(error.response.data);
        });
    },

    getUrl(user_id) {
      return "/admin/lists/list/" + user_id;
    },

    remove(id) {
      console.log("TESTEEE " + id);

      let data = {
        user_id: id,
      };
      axios
        .post(`/admin/users/delete`, data)
        .then((response) => {
          this.$swal.fire("Removido!", "O processo foi executado com sucesso.", "success");

          window.location.reload();
        })
        .catch((error) => {
          this.showErrorMessageWithButton("Ops..", error.response.data);
          console.log(error.response.data);
        });
    },

    buscarUsuarios() {
      this.isLoading = true;

      let data = {
        role: this.tipo_usuario,
        nome: this.nome,
      };

      console.log(data);

      axios
        .post(`/admin/users/search-by-role`, data)
        .then((response) => {
          this.users = response.data.usuarios;
          this.isLoading = false;
        })
        .catch((error) => {
          this.isLoading = false;
          this.showErrorMessageWithButton("Ops..", error.response.data);
          console.log(error.response.data);
        });
    },

    formatDate(date) {
      return moment(date).format("DD/MM/YYYY HH:mm:ss");
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

table {
  border: 1px solid #ccc;
  border-collapse: collapse;
  margin: 0;
  padding: 0;
  width: 100%;
  table-layout: fixed;
}

table tr:nth-child(even) {
  background: #fff;
}

table tr:nth-child(odd) {
  background: #eee;
}

table.pdi tr:nth-child(even) {
  background: #fff;
}

table.pdi tr:nth-child(odd) {
  background: #80f0af;
}

table caption {
  font-size: 1.2em;
  margin: 0.1em 0 0.5em;
}

table tr {
  background-color: #f8f8f8;
  border: 1px solid #ddd;
  padding: 0.1em;
}

table th,
table td {
  padding: 0.2em;
  text-align: center;
  font-size: 0.9em;
}

table th {
  font-size: 0.7em;
  letter-spacing: 0.1em;
  text-transform: uppercase;
}

@media screen and (max-width: 600px) {
  table {
    border: 0;
  }

  table caption {
    font-size: 1em;
  }

  table thead {
    border: none;
    clip: rect(0 0 0 0);
    height: 1px;
    margin: -1px;
    overflow: hidden;
    padding: 0;
    position: absolute;
    width: 1px;
  }

  table tr {
    border-bottom: 3px solid #ddd;
    display: block;
    margin-bottom: 0.625em;
  }

  table td {
    padding: 0.4em;
    border-bottom: 1px solid #ddd;
    display: block;
    font-size: 0.9em;
    text-align: right;
  }

  table td::before {
    /*
    * aria-label has no advantage, it won't be read inside a table
    content: attr(aria-label);
    */
    content: attr(data-label);
    float: left;
    font-weight: bold;
    text-transform: uppercase;
  }

  table td:last-child {
    border-bottom: 0;
  }
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

body {
  font-family: Verdana, Geneva, sans-serif;
  font-size: 14px;
  background: #f2f2f2;
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

.form_wrapper .row > div {
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

.form_wrapper .input_field > span,
.form_wrapper .textarea_field > span {
  position: absolute;
  left: 0;
  top: 0;
  color: #333;
  height: 100%;
  text-align: center;
  width: 20px;
}

.form_wrapper .textarea_field > span {
  max-height: 35px;
}

.input_field {
  font-size: 12px;
}

.form_wrapper .input_field > span > i,
.form_wrapper .textarea_field > span > i {
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
