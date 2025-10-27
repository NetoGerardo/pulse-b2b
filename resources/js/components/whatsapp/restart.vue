<template>
  <loading v-model:active="isLoading" :can-cancel="false" :is-full-page="true" />
  <!--
  <button v-if="!restart" @click="confirmacao()" data-toggle="modal" data-target="#confirmModal" style="color: white"
    type="button" class="btn btn-warning btn-block btn-sm">
    <i class="fa fa-solid fa-laptop"></i>
  </button>

  <button v-show="restart" @click="reiniciar()" id="restart" style="color: white" type="button"
    class="btn btn-success btn-block btn-sm">
    <i class="fa fa-solid fa-laptop"></i>
  </button>
  -->

  <button @click="confirmacao()" style="color: white" type="button" class="btn btn-warning btn-block btn-sm">
    <i class="fa fa-solid fa-laptop"></i>
  </button>

  <button id="restart" style="display: none; color: white" type="button" class="btn btn-warning btn-block btn-sm">
    <i class="fa fa-solid fa-laptop"></i>
  </button>
</template>

<script>
import wppController from "../controller/wppController";
import sweetAlert from "../controller/sweetAlert";

import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";

export default {
  props: ["my_container", "usuario"],

  mixins: [wppController, sweetAlert],

  components: { Loading },

  data() {
    return {
      container: {},
      isConnected: false,
      isLoading: false,
      restart: false,
      porta: "",
    };
  },

  mounted() {
    this.container = this.my_container;

    console.log("Restart componenst");
    console.log("aqui");
    console.log(this.container);
    console.log("Users 2");
    console.log(this.usuario);

    if (this.my_container.porta) {
      this.porta = this.my_container.porta;
    } else {
      this.porta = this.usuario.porta;
    }
  },

  methods: {
    connect() {
      this.isLoading = false;

      console.log("Carregando QR - Sessão = " + this.container.chave_api);

      this.axios
        .get(
          process.env.MIX_VUE_APP_ENDPOINT + this.porta + `/load/` + this.container.id + "/" + this.container.chave_api + "/" + this.container.user_id
        )
        .then((response) => {
          console.log("Resposta do carregamento ");
          console.log(this.porta);
          console.log(response.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },

    confirmacao() {
      let html = "Deseja <b style='font-size:20px'>Reiniciar </b> o seu servidor ?";

      this.$swal
        .fire({
          title: "Confirmação",
          html: html,
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Sim!",
        })
        .then((result) => {
          if (result.value) {
            this.reiniciar();
          }
        });
    },

    reiniciar() {
      let btn = document.getElementById("restart");
      btn.click();

      let data = {
        appName: this.usuario.url,
      };

      axios
        .post(process.env.MIX_VUE_APP_ENDPOINT_PM2 + `/restart`, data)
        .then((response) => {
          this.showSuccessMessage("Reiniciado com sucesso!");

          setTimeout(() => {
            this.connect();
          }, 3000);
        })
        .catch((error) => {
          this.showErrorMessageWithButton("Ops..", error.response.data);
          console.log(error.response.data);
        });
    },
  },
};
</script>
