<template>
  <i id="connectionStatus" v-if="!isConnected" style="color: red" class="fa fa-solid fa-wifi">!</i>
  <i id="connectionStatus" v-if="isConnected" style="color: green" class="fa fa-solid fa-wifi"></i>
</template>

<script>
import wppController from "../controller/wppController";

export default {
  props: ["my_container", "usuario"],

  mixins: [wppController],

  data() {
    return {
      container: {},
      isConnected: false,
      porta: "",
    };
  },

  mounted() {
    this.container = this.my_container;

    if (this.my_container.porta) {
      this.porta = this.my_container.porta;
    } else {
      this.porta = this.usuario.porta;
    }

    console.log("Status connection component " + this.container.chave_api);
    console.log("Porta " + this.porta);

    this.getStatus();
  },

  methods: {
    getStatus() {
      this.getConnectionStatus(this.container.chave_api, this.porta, (status) => {
        console.log("STATUS RESULTADO");
        console.log(status);

        if (status == "CONNECTED" || status == "inChat") {
          this.isConnected = true;
        } else {
          this.isConnected = false;
        }
      });
    },
  },
};
</script>
