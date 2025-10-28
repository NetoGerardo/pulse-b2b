<template>
  <!--ESSE BOTÃO INICIA A CONEXÃO DEPOIS SOME -->
  <button
    data-toggle="modal"
    data-target="#myModal"
    @click="connect()"
    v-if="!isConnected && !conectando"
    id="qrCode"
    type="button"
    class="btn btn-block btn-primary btn-sm"
  >
    QR Code
  </button>

  <!--ESSE BOTÃO SERVE SOMENTE PARA EXIBIR O MODAL SEM CHAMAR O MÉTODO DE CONEXÃO NOVAMENTE-->
  <button
    data-toggle="modal"
    data-target="#myModal"
    v-if="!isConnected && conectando"
    id="qrCode"
    type="button"
    class="btn btn-block btn-primary btn-sm"
  >
    QR Code
  </button>

  <button @click="disconnect()" v-if="isConnected" type="button" class="btn btn-block btn-outline-danger btn-sm">DESCONECTAR</button>
</template>

<script>
import wppController from "../controller/wppController";
import sweetAlert from "../controller/sweetAlert";

export default {
  props: ["my_container", "teste", "usuario"],

  mixins: [wppController, sweetAlert],

  data() {
    return {
      conectando: false,
      container: {},
      isConnected: false,
      porta: "",
    };
  },

  mounted() {
    this.container = this.my_container;

    console.log("Load component");
    console.log(this.container);

    if (this.my_container.porta) {
      this.porta = this.my_container.porta;
    } else {
      this.porta = this.usuario.porta;
    }

    this.getStatus();
  },

  methods: {
    connect() {
      this.conectando = true;

      console.log("Carregando QR - Sessão = " + this.container.chave_api);

      this.axios
        .get(process.env.MIX_VUE_APP_ENDPOINT + `/load/` + this.container.id + "/" + this.container.chave_api + "/" + this.container.user_id)
        .then((response) => {
          console.log("Resposta do carregamento ");
          console.log(this.porta);
          console.log(response.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },

    disconnect() {
      this.confirmWarning("Deseja desconectar sua sessão?", () => {
        this.axios
          .get(process.env.MIX_VUE_APP_ENDPOINT + this.porta + `/logout/` + this.container.chave_api)
          .then((response) => {
            console.log("Resposta do carregamento ");
            console.log(response.data);

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
  },
};
</script>
