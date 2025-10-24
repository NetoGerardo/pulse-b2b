<template>
  <div>
    <loading v-model:active="isLoading" :can-cancel="false" :is-full-page="true" />
    <button data-toggle="modal" data-target="#login-modal" type="button" class="btn btn-info">
      Novo usuário <i class="nav-icon far fa-plus-square"></i>
    </button>

    <div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
      aria-hidden="true">
      <form @submit.prevent>
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-body">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                &times;
              </button>
              <h4>Novo usuário</h4>
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
                <label for="user_type">Tipo Usuário</label>
                <select class="form-control" id="user_type" v-model="tipo_usuario">
                  <option value="user">Corretor</option>
                  <option value="gerente">Gerente</option>
                  <option value="supervisor">Supervisor</option>
                </select>
              </div>

              <!--
              <div class="form-group">
                <label for="email">Código Interno</label>
                <input class="form-control" id="email" v-model="codigo_interno" autocomplete="email" />
              </div>

              <div v-if="tipo_usuario == 'user'" class="form-group">
                <label for="user_type">Supervisor</label>
                <select class="form-control" id="user_type" v-model="supervisor_selecionado">
                  <option v-for="supervisor in supervisores" :key="supervisor" :value="supervisor">
                    {{ supervisor.name }}
                  </option>
                </select>
              </div>

              <div v-if="tipo_usuario == 'supervisor'" class="form-group">
                <label for="user_type">Gerente</label>
                <select class="form-control" id="user_type" v-model="gerente_selecionado">
                  <option v-for="gerente in gerentes" :key="gerente" :value="gerente">
                    {{ gerente.name }}
                  </option>
                </select>
              </div>
              -->

              <div v-if="tipo_usuario == 'user'" class="form-group">
                <label for="user_type">Supervisor</label>
                <select class="form-control" id="user_type" v-model="supervisor_selecionado">
                  <option v-for="supervisor in supervisores" :key="supervisor" :value="supervisor">
                    {{ supervisor.name }}
                  </option>
                </select>
              </div>

              <button @click="validateFields()" class="btn btn-primary btn-flat">
                Cadastrar <i class="fa fa-search"></i>
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import wppController from "../controller/wppController";
import sweetAlert from "../controller/sweetAlert";
import Swal from "sweetalert2/dist/sweetalert2.js";

import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";

export default {
  mixins: [wppController, sweetAlert, Swal],

  props: ['supervisores', 'gerentes'],

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
    };
  },

  mounted() {
    console.log("GERENTES");
    console.log(this.gerentes);
  },

  methods: {
    validateFields() {
      if (!this.nome) {
        this.showErrorMessageWithButton("Ops...", "O campo nome é obrigatório!");
      } else if (!this.validateEmail()) {
        this.showErrorMessageWithButton("Ops...", "Digite um email válido!");
      } else if (this.senha == "") {
        this.showErrorMessageWithButton("Ops...", "A senha não pode ficar em branco!");
      } else if (this.tipo_usuario == "") {
        this.showErrorMessageWithButton("Ops...", "Selecione o tipo do usuário!");
      } else if (this.tipo_usuario == "user" && !this.supervisor_selecionado) {
        this.showErrorMessageWithButton("Ops...", "Selecione um supervisor!");
      } else {
        this.create();
      }
    },

    create() {
      this.isLoading = true;

      let data = {
        nome: this.nome,
        email: this.email,
        senha: this.senha,
        chave: this.generateKey(),
        codigo_interno: this.codigo_interno,
        telefone: this.telefone,
        supervisor_id: this.supervisor_selecionado.id,
        tipo_usuario: this.tipo_usuario,
        gerente_id: this.gerente_selecionado.id
      };

      console.log("CRIANDO");
      console.log(data);

      axios
        .post(`/admin/users/create`, data)
        .then((response) => {
          console.log("Usuário criado!");
          console.log(response);

          this.showSuccessMessage("Usuário criado!");

          window.location.reload();
        })
        .catch((error) => {
          this.showErrorMessageWithButton("Ops..", error.response.data);
          console.log(error.response.data);
        });
    },

    validateEmail() {
      if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.email)) {
        return true;
      } else {
        return false;
      }
    },

    generateKey() {
      return Math.random().toString(36).slice(2);
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
