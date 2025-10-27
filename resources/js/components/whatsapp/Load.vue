<template>
  <div id="app">
    <router-view></router-view>
    <button class="button" type="button" @click="connect">Conectar</button>

    <button class="button" type="button" @click="sendText">Enviar mensagem</button>
</div>
</template>

<script>
export default {
  name: "LoadQRCode",

  props: ["my_container", "teste", "usuario"],

  data() {
    return {
      loading: false,
      sessionName: "",
      qrCodePath: "",
      attempt: 1,
      container: "",
      showContainerNotFound: false,
      refreshQR: true,
      isScanning: false,
      showLoadingQR: false,
      conflict: false,
      unpaired: false,
      timeout: false,
      status: "",
      isRunning5Attempts: false,
      timeToReconnect: 30,
      isReconnecting: false,
      container: [],
    };
  },

  mounted() {
    this.container = this.my_container;

    console.log("Load component");
    console.log(this.container);

  },

  methods: {
    show() {
      console.log(this.container);
    },

    connect() {
      console.log("Carregando QR - Sessão = " + this.container.chave_api);
      console.log(this.usuario.porta)
      this.axios
        .get(process.env.MIX_VUE_APP_ENDPOINT + this.usuario.porta + `/load/` + this.container.id + "/" + this.container.chave_api + "/" + this.container.chave_api + "/" + this.usuario.id)
        .then((response) => {

          console.log("Resposta do carregamento ");

          console.log(response.data);

          if (response.data.status == "CONNECTED") {
            window.location.href = `#/user/` + this.container.url + `/send/texto`;
            this.refreshQR = false;
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },

    sendText() {
      console.log("Enviando mensagem");

      let data = {
        apiId: this.container.url,
        number: "5584988992898",
        text: "Hello from WhatsZ!"
      }

      this.axios
        .post(process.env.MIX_VUE_APP_ENDPOINT + this.usuario.porta + `/send/text`, data)
        .then((response) => {
          console.log("Resposta do envio!");
          console.log(response.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>
