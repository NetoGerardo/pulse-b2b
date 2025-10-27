<template>
  <button
    data-toggle="modal"
    data-target="#enviar-teste-modal"
    type="button"
    class="btn btn-success btn-sm"
    @click="mensagem_teste_envio= ''"
  >
    Testar
  </button>

  <!-- MODAL ENVIO TESTE -->
  <div class="modal fade" id="enviar-teste-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form @submit.prevent>
      <div class="modal-dialog" style="text-align: left">
        <div class="modal-content">
          <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4>Enviar teste</h4>
            <br />
            <div class="form-group">
              <label for="user_type">Mensagem</label>
              <textarea id="nome" rows="4"  type="text" class="form-control" v-model="texto" />
            </div>
            <div class="form-group">
              <label for="user_type">Telefone</label>
              <input id="nome" type="text" class="form-control" v-model="telefone_teste" autocomplete="name" />
            </div>

            <div class="form-group">
              <h6 style="font-weight: bold">{{ mensagem_teste_envio }}</h6>
            </div>
            <br />
            <button data-toggle="modal" @click="enviarTeste" type="button" class="btn btn-success btn-sm">Enviar teste</button>
            <button data-toggle="modal" data-target="#enviar-teste-modal" type="button" style="margin-left: 5px" class="btn btn-danger btn-sm">
              Fechar
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import moment from "moment";
import wppController from "../controller/wppController";
import sendController from "../controller/sendController";
import sweetAlert from "../controller/sweetAlert";
import Swal from "sweetalert2/dist/sweetalert2.js";

import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";

export default {
  mixins: [wppController, sendController, sweetAlert, Swal],

  components: { Loading },

  props: ["container", "usuario"],

  data() {
    return {
      nome: "",
      email: "",
      senha: "",
      isLoading: false,
      containers: [],
      acoes_usuario: [],
      acao_selecionada: "",
      filtro: "",
      paginas: [],
      inicio: 0,
      total: 0,
      qtd_por_pagina: 25,
      pagina_atual: 1,
      editar_container: false,
      criar_container: false,
      user_selecionado: "",
      telefone_teste: "84988992898",
      texto: "Teste",
      mensagem_teste_envio: "",
      tag: "",
      cor_tag: "#FF0000",
      array_tags: [],
      array_historico: [],
      data_tag: "",
      tag_padrao_option: "",
      ultima_atualizacao_status: "",
      numero_conectado: "",
      nome_container: "",
      array_tags_padrao: [],
    };
  },

  mounted() {
    console.log("Botão de teste");
    console.log(this.container);
  },

  methods: {
    enviarTeste() {
      console.log("Enviando");
      console.log(this.container);
      console.log(this.usuario);

      if (this.telefone_teste) {
        this.telefone_teste = this.telefone_teste.replace(/[^Z0-9]/g, "");

        //ADICIONANDO 55 CASO NÃO TENHA
        if (this.telefone_teste.substr(0, 2) != "55") {
          this.telefone_teste = "55" + this.telefone_teste;
        }

        this.mensagem_teste_envio = "Enviando agora...";

        let porta = this.container.porta;

        if (!porta) {
          porta = this.usuario.porta;
        }
        this.validarNumero(this.telefone_teste, this.container.chave_api, porta, (validNumber, timeout) => {
          if (validNumber) {
            this.enviar("texto", validNumber, this.texto, "", this.container.chave_api, porta, (timeout, phone, sucess) => {
              if (timeout) {
                this.mensagem_teste_envio = "Verifique a aba Conexão...";
              } else {
                //Verificando se foi enviado com sucesso
                console.log("Checking status...");

                if (sucess) {
                  console.log("Success");
                  //Prosseguir envio
                  if (phone) {
                    this.mensagem_teste_envio = "Mensagem enviada...";
                  } else {
                    this.mensagem_teste_envio = "Ops... Falha no envio";
                  }
                } else {
                  this.mensagem_teste_envio = "Falha no envio";
                }
              }
            });
          } else {
            if (timeout) {
              this.mensagem_teste_envio = "Falha ao validar número, conexão instável...";
            } else {
              this.mensagem_teste_envio = "WhatsApp inválido...";
            }
          }
        });
      }
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

.modal-content {
  width: 1100px;
  margin-left: -250px;
}

@media screen and (max-width: 1200px) {
  .modal-content {
    width: 800px;
    margin-left: -20px;
    margin-top: 100px;
    margin-bottom: 100px;
  }
}

@media screen and (max-width: 1100px) {
  .modal-content {
    width: 600px;
    margin-left: -20px;
    margin-bottom: 100px;
  }
}

@media screen and (max-width: 960px) {
  .modal-content {
    width: 750px;
    margin-left: -120px;
    margin-bottom: 100px;
  }
}

@media screen and (max-width: 800px) {
  .modal-content {
    width: 600px;
    margin-left: -50px;
    margin-bottom: 100px;
  }
}

@media screen and (max-width: 600px) {
  .modal-content {
    width: 100%;
    margin-left: 0;
    margin-bottom: 100px;
  }
}
</style>
