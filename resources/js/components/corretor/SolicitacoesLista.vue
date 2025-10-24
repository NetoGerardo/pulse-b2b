<template>
  <button @click="confirmacao()" type="button" class="btn btn-success btn-sm">
    Solicitar Lista
  </button>
  <div class="card">

    <div class="card-body p-0">
      <table class="table">
        <thead>
          <tr>
            <th style="width: 25%">Data</th>
            <th style="width: 25%">Status</th>
            <th style="width: 25%">Observação</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="lista in solicitacoes_lista" :key="lista.id">

            <td data-label="Data">
              {{ formatDate(lista.created_at) }}
            </td>

            <td v-bind:class="{
              aguardando: lista.status == 'AGUARDANDO', enviada: lista.status == 'ENVIADA', recusada: lista.status == 'RECUSADA', normal: lista.status != 'AGUARDANDO' && lista.status != 'ENVIADA'
            }" data-label=" Status">
              {{ lista.status }}
            </td>
            <td data-label="Observação">
              {{ lista.observacao }}
            </td>

          </tr>
        </tbody>
      </table>
    </div>
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

  props: ["solicitacoes_lista"],

  data() {
    return {
      container: {},
      nome: "",
      email: "",
      senha: "",
      isLoading: false,
    };
  },

  mounted() {
    console.log("MOUNTEDD");
    console.log(this.containers);
  },

  methods: {
    confirmacao() {
      this.$swal
        .fire({
          title: "Confirmação",
          text: "Deseja solicitar uma nova lista?",
          icon: "info",
          showCancelButton: true,
          confirmButtonColor: "#4caf50",
          cancelButtonColor: "#d33",
          confirmButtonText: "Sim!"
        })
        .then(result => {
          if (result.value) {
            this.solicitar();
          }
        });
    },

    solicitar() {
      axios
        .post(`/user/listas/store`)
        .then((response) => {
          this.showSuccessMessage("Lista solicitada!");

          window.location.reload();
        })
        .catch((error) => {
          this.showErrorMessageWithButton("Ops..", error.response.data);
          console.log(error.response.data);
        });

    },

    show() {
      let status1 = this.my_container.status_usuario;
      let status2 = "Ativo";
      let email = this.my_container.user.email;

      if (status1 == "Ativo") {
        status2 = "Bloqueado";
      }

      console.log("Status 1 " + status1);
      console.log("Status 2 " + status2);

      this.edit(this.my_container.nome, status1, status2, (response) => {
        if (!response.isDismissed) {
          let data = {
            nome: response.value[0],
            senha: response.value[1],
            status: response.value[2],
            id: this.my_container.id,
            user_id: this.my_container.user_id,
          };

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
          console.log("Usuário criado!");
          console.log(response);

          this.showSuccessMessage("Usuário atualizado!");

          window.location.reload();
        })
        .catch((error) => {
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
.enviada {
  color: green;
}

.aguardando {
  color: orange;
}

.normal {
  color: black;
}

.recusada {
  color: red;
}

.swal-modal {
  color: green;
  background-color: rgba(63, 255, 106, 0.69);
  border: 3px solid white;
}

.swal-overlay {
  background-color: rgba(43, 165, 137, 0.45);
}
</style>
