<template>
  <button @click="deleteToken()" type="button" class="btn btn-danger btn-block btn-sm">
    <i class="fa fa-trash"></i>
  </button>
</template>

<script>
import wppController from "../controller/wppController";
import sweetAlert from "../controller/sweetAlert";

export default {
  props: ["my_container", "teste", "usuario"],

  mixins: [wppController, sweetAlert],

  data() {
    return {
      container: {},
      isConnected: false,
      porta: ""
    };
  },

  mounted() {
    this.container = this.my_container;

    if (this.my_container.porta) {
      this.porta = this.my_container.porta;
    } else {
      this.porta = this.usuario.porta;
    }

    console.log("Load component");
    console.log(this.container);

    this.getStatus();
  },

  methods: {
    deleteToken() {
      this.confirmWarning("Deseja resetar o token da sua sessão?", () => {
        let data = {
          id: this.container.id,
          chave_api: this.generateKey(),
        };

        console.log(data);

        this.axios
          .post(`/reset/key`, data)
          .then((response) => {
            window.location.reload();
          })
          .catch((error) => {
            console.log(error);
          });
      });
    },

    getStatus() {
      this.getConnectionStatus(this.container.chave_api, this.porta, (status) => {
        console.log("STATUS");
        console.log(status);

        if (status == "CONNECTED" || status == "inChat") {
          this.isConnected = true;
        } else {
          this.isConnected = false;
        }
      });
    },

    generateKey() {
      return Math.random().toString(36).slice(2);
    },
  },
};
</script>
