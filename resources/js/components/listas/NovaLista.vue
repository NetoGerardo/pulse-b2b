<template>
  <div>
    <loading v-model:active="isLoading" :can-cancel="false" :is-full-page="true" />
    <button data-toggle="modal" data-target="#cadastrar-modal" type="button" class="btn btn-info">
      Nova Lista <i class="nav-icon far fa-plus-square"></i>
    </button>

    <div class="modal fade" id="cadastrar-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
      aria-hidden="true">
      <form @submit.prevent>
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-body">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                &times;
              </button>
              <h4>Nova Lista</h4>
              <br />
              <div class="form-group">
                <label for="email">Nome</label>
                <input class="form-control" id="email" type="text" v-model="nome" />
              </div>
              <button @click="validateFields()" class="btn btn-success">
                Salvar
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

  props: ['origens'],

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
      telefones: ""
    };
  },

  mounted() { },

  methods: {
    validateFields() {
      if (!this.nome) {
        this.showErrorMessageWithButton("Ops...", "O campo nome é obrigatório!");
      } else {
        this.create();
      }
    },

    formatarContatos() {
      let array_contatos = this.telefones.split("\n");
      let contatos_formatados = [];

      for (let i = 0; i < array_contatos.length; i++) {
        contatos_formatados.push(array_contatos[i]);
      }

      console.log(contatos_formatados);

      if (contatos_formatados.length > 0 && this.telefones != "") {
        this.create(contatos_formatados);
      } else {
        this.showErrorMessageWithButton("Ops...", "A lista de telefones está vazia");
      }

    },

    create(telefones) {
      this.isLoading = true;

      let data = {
        nome: this.nome,
      };

      console.log("CRIANDO");
      console.log(data);

      axios
        .post(`/admin/lista/store`, data)
        .then((response) => {
          console.log("Lista criada!");
          console.log(response);

          this.showSuccessMessage("Lista criada!");

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
