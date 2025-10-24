<template>
  <div>
    <loading v-model:active="isLoading" :can-cancel="false" :is-full-page="true" />
    <button data-toggle="modal" data-target="#login-modal" type="button" class="btn btn-info btn-sm" >
      Nova Indicação
    </button>

    <div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <form @submit.prevent>
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-body">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4>Nova indicação</h4>
              <br />
              <div class="form-group">
                <label for="email">Nome</label>
                <input class="form-control" id="email" type="text" v-model="nome" />
              </div>

              <div class="form-group">
                <label for="user_type">Possui CNPJ?</label>
                <select class="form-control" id="user_type" v-model="possui_cnpj">
                  <option :value="false">Não</option>
                  <option :value="true">Sim</option>
                </select>
              </div>

              <div class="form-group">
                <label for="user_type">Ocupação?</label>
                <input class="form-control" id="telefone" type="text" v-model="ocupacao" />
              </div>

              <div class="form-group">
                <label for="email">Telefone</label>
                <input class="form-control" id="telefone" type="text" v-model="telefone" />
              </div>

              <div class="form-group">
                <label for="user_type">Bairro?</label>
                <input class="form-control" id="telefone" type="text" v-model="bairro" />
              </div>

              <div class="form-group">
                <label for="user_type">Possui Plano?</label>
                <select class="form-control" id="user_type" v-model="possui_plano">
                  <option :value="false">Não</option>
                  <option :value="true">Sim</option>
                </select>
              </div>

              <div class="form-group">
                <label for="user_type">Quantidade de vidas</label>
                <select class="form-control" id="user_type" v-model="qtd_vidas" @change="changeVidas()">
                  <option v-for="vida in vidas" :key="vida" :value="vida">
                    {{ vida }}
                  </option>
                </select>
              </div>

              <div class="form-group" v-for="(idade, index) in idades">
                <label for="user_type">Idade {{ index + 1 }}º pessoa</label>
                <input class="form-control" id="senha" type="senha" v-model="idade.idade" autocomplete="senha" />
              </div>

              <div class="form-group">
                <label for="password" class="col-md-4 col-form-label text-md-end">Complemento</label>
                <textarea class="form-control" id="senha" type="senha" v-model="complemento" autocomplete="senha" rows="5" cols="40" />
              </div>

              <div class="form-group">
                <label for="user_type">Origem</label>
                <select class="form-control" id="user_type" v-model="origem_selecionada">
                  <option v-for="origem in origens" :key="origem" :value="origem">
                    {{ origem.nome }}
                  </option>
                </select>
              </div>

              <button @click="validateFields()" class="btn btn-primary btn-flat">
                Cadastrar
                <i class="fa fa-search"></i>
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

  components: { Loading },

  props: ["origens"],

  data() {
    return {
      container: {},
      complemento: "",
      bairro: "",
      ocupacao: "",
      nome: "",
      telefone: "",
      possui_cnpj: false,
      possui_plano: false,
      qtd_vidas: 1,
      vidas: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
      isLoading: false,
      idades: [],
      origem_selecionada: "",
    };
  },

  mounted() {},

  methods: {
    validateFields() {
      if (!this.nome) {
        this.showErrorMessageWithButton("Ops...", "O campo nome é obrigatório!");
      } else if (!this.origem_selecionada) {
        this.showErrorMessageWithButton("Ops...", "Selecione a origem da indicação");
      } else {
        this.create();
      }
    },

    create() {
      this.isLoading = true;

      let data = {
        ocupacao: this.ocupacao,
        nome: this.nome,
        bairro: this.bairro,
        telefone: this.telefone,
        possui_cnpj: this.possui_cnpj,
        possui_plano: this.possui_plano,
        qtd_vidas: this.qtd_vidas,
        complemento: this.complemento,
        origem: this.origem_selecionada,
      };

      console.log("CRIANDO");
      console.log(data);

      axios
        .post(`/user/leads/store`, data)
        .then((response) => {
          console.log("Usuário criado!");
          console.log(response);

          this.showSuccessMessage("Indicação cadastrada!");

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
