<template>

  <button data-toggle="modal" data-target="#login-modal" style="float: right; border-radius: 10%" type="button"
    class="btn btn-outline-success btn-sm">
    <i class="fa fa-edit"></i>
  </button>

  <loading v-model:active="isLoading" :can-cancel="false" :is-full-page="true" />

  <div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <form @submit.prevent>
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body" style="text-align: left">

            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label for="exampleInputEmail1">Valor da proposta</label>
                  <input class="form-control" id="exampleInputEmail1" placeholder="Valor" v-model="valor_proposta" />
                </div>

                <div class="form-group">
                  <label>Selecione um Plano</label>
                  <select class="form-control" v-model="plano_selecionado">
                    <option value=""></option>
                    <option v-for="plano in planos" :key="plano" :value="plano">
                      {{ plano.nome }}
                    </option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="exampleInputEmail1">Data de vencimento</label>
                  <input id="dataInicio" class="form-control" name="dataInicio" type="date" v-model="data_vencimento" />
                </div>

                <div class="form-group">
                  <label>Comprovante de residência</label>
                  <input class="form-control" type="file" @change="selecionarComprovante" ref="fileComprovante" />
                </div>

                <div class="form-group">
                  <label>Comprovante de Vínculo</label>
                  <input class="form-control" type="file" @change="selecionarComprovanteVinculo"
                    ref="fileComprovanteVinculo" />
                </div>

                <!-- 
                <div class="form-group">
                  <label for="email">Quantidade de Vidas</label>
                  <select class="form-control" id="user_type" v-model="qtd_vidas" @change="changeVidas">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                  </select>
                </div>
                -->

              </div>
            </div>
            <form @submit.prevent enctype="multipart/form-data">
              <button style="width:100%;" @click="criarNovaProposta()" class="btn btn-success">Cadastrar</button>
            </form>

            <button data-toggle="modal" data-target="#login-modal" style="width:100%;" class="btn btn-info mt-2">
              Voltar
            </button>
          </div>


          <div class="col-md-12" v-if="documentos.length > 0">

            <br />
            <br />
            <br />

            <div class="card direct-chat direct-chat-primary card-info" v-for="(mensagem, indice) in mensagens"
              :key="mensagem.id">
              <div class="card-header">
              </div>
              <div class="card-body">

                <div class="row" style="padding: 10px 10px 10px 10px">
                  <div class="col-sm-12">

                    <div v-if="tipo_plano == 'individual'">
                      <div class="form-group">
                        <label>Mensagem</label>
                        <textarea class="form-control" rows="3" placeholder="Digite a mensagem que deseja enviar ..."
                          v-model="mensagem.texto"></textarea>
                      </div>
                    </div>

                    <div v-if="tipo_plano == 'empresarial_pme'">
                      <div class="form-group">
                        <label>Mensagem</label>
                        <textarea class="form-control" rows="3" placeholder="Digite a mensagem que deseja enviar ..."
                          v-model="mensagem.texto"></textarea>
                      </div>
                    </div>

                    <div v-if="tipo_plano == 'adesao'">
                      <div class="form-group">
                        <label>Mensagem</label>
                        <textarea class="form-control" rows="3" placeholder="Digite a mensagem que deseja enviar ..."
                          v-model="mensagem.texto"></textarea>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- BOTÃO ADD RESPOSTA -->

                <!-- 
            <div v-if="mensagem.aguardar_resposta" class="row" style="padding: 10px 10px 10px 10px">
              <button style="margin: 5px 5px 0px 10px; font-size: 12px" @click="addPessoa(mensagem)"
                class="btn btn-sm btn-danger">
                <i class="fa fa-plus"></i> Resposta Esperada
                <span class="badge badge-warning">{{ mensagem.resposta_esperada.length }}</span>
              </button>
            </div>
            -->


                <!-- DOCUMENTOS PESSOAS -->

                <!-- 
            <div class="row" style="padding: 10px 10px 10px 10px">
              <div class="col-sm-12 col-md-6" v-for="(documento, indice) in documentos" :key="documento.id">
                <div class="card card-primary">
                  <div class="card-header">
                    <h3 class="card-title">Documentos Pessoa {{ indice + 1 }}</h3>
                  </div>
                  <div class="card-body" style="padding: 10px 10px 10px 10px">
                    <div class="row">
                      <div class="col-sm-12">

                        <div class="form-group">
                          <label for="exampleInputEmail1">Nome</label>
                          <input class="form-control" id="exampleInputEmail1" v-model="documento.nome" />
                        </div>

                        <div class="form-group">
                          <form enctype="multipart/form-data">
                            <label>CPF</label>
                            <input class="form-control" type="file" @change="selectFile" ref="file" />
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

              -->
              </div>
            </div>
          </div>
        </div>
      </div>

    </form>
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

  props: ['planos', 'element'],

  mixins: [wppController, sweetAlert, Swal],

  components: {
    Loading
  },

  data() {
    return {
      container: {},
      nome_titular: null,
      mesage_to_send: "",
      url_to_send: null,
      isConnected: false,
      send_to_list: false,
      add_to_funil: false,
      list_break: "CSV",
      listToSend: null,
      contacts: [],
      preview: false,
      tipo_envio: "texto",
      mensagens: [],
      resposta_padrao: "",
      qtd_vidas: 0,
      documentos: [],
      arquivoProposta: "",
      arquivoComprovanteResidencia: "",
      arquivoComprovanteVinculo: "",
      id: "",
      plano_selecionado: "",
      data_vencimento: "",
      whatsapp: "",
      valor_proposta: "",
      isLoading: false
    };
  },

  mounted() {
    console.log("Create Send Ready");
    console.log(this.tipo_envio);

    this.addProposta();

    this.id = this.generateKey();
  },

  methods: {

    criarNovaProposta() {

      this.isLoading = true;

      let data = {
        id: this.element.id,
        status: "Aguardando pagamento"
      }

      axios
        .post(`/user/proposta/new`, data)
        .then((response) => {
          this.id_proposta = response.data.id;
          console.log("ID = " + this.id_proposta);
          this.upload();
        })
        .catch((error) => {
          this.showErrorMessageWithButton("Ops..", error);
          console.log(error);
        });
    },

    calcularParcelas(proposta_id) {


    },

    formatSelectedDate(date) {
      return moment(date).format("yyyy-MM-DD");
    },

    async upload() {

      //let caminhoArquivoProposta = await this.sendFile(this.arquivoPropost, this.id_propostaa);
      let caminhoArquivoComprovanteResidencia = await this.sendFile(this.arquivoComprovanteResidencia, this.id_proposta);
      let caminhoArquivoComprovanteVinculo = await this.sendFile(this.arquivoComprovanteVinculo, this.id_proposta);

      //console.log(caminhoArquivoProposta);
      console.log(caminhoArquivoComprovanteResidencia);
      console.log(caminhoArquivoComprovanteVinculo);

      let data = {
        id: this.id_proposta,
        nome_titular: this.element.nome,
        whatsapp: this.element.telefone,
        contato_id: this.element.id,
        valor_proposta: this.valor_proposta,
        comprovante_residencia: "/" + caminhoArquivoComprovanteResidencia,
        comprovante_vinculo: "/" + caminhoArquivoComprovanteVinculo,
        plano_id: this.plano_selecionado.id,
        data_vencimento: this.data_vencimento
      }

      console.log("FAZENDO UPLOAD");
      console.log(data);

      axios
        .post(`/user/proposta/store`, data)
        .then((response) => {
          let proposta_id = response.data.id;

          let comissionamento = this.validateJSON(this.plano_selecionado.comissionamento);

          let ultima_data_vencimento = new Date(this.data_vencimento);

          //ADICIONANDO 1 DIA DEVIDO AO BUG NA INTERFACE QUE TRAZ UM DIA A MENOS
          ultima_data_vencimento.setDate(ultima_data_vencimento.getDate() + 1);

          let parcelas = [];

          for (let i = 0; i < comissionamento.length; i++) {

            data_vencimento = this.formatSelectedDate(data_vencimento);

            let valor = (Number(comissionamento[i].comissao) / 100) * Number(this.valor_proposta);

            parcelas.push({
              valor: valor,
              data_vencimento: data_vencimento,
              proposta_id: proposta_id
            });

            let data_vencimento = ultima_data_vencimento;
            data_vencimento.setMonth(data_vencimento.getMonth() + 1);

            ultima_data_vencimento = new Date(data_vencimento);
          }

          let data = {
            parcelas: parcelas
          }

          axios
            .post(`/user/parcelas/store`, data)
            .then((response) => {
              let data = {
                id: this.element.id,
                status: "Aguardando pagamento"
              }

              axios
                .post(`/user/contato/update-status`, data)
                .then((response) => {
                  this.isLoading = false;
                  this.showSuccessMessage("Contato atualizado!");

                  window.location.reload();
                })
                .catch((error) => {
                  this.showErrorMessageWithButton("Ops..", error);
                  console.log(error);
                });
            })
            .catch((error) => {
              this.showErrorMessageWithButton("Ops..", error);
              console.log(error);
            });
        })
        .catch((error) => {
          this.showErrorMessageWithButton("Ops..", error);
          console.log(error);
        });

    },

    selecionarProposta() {

      let file = this.$refs.fileProposta.files[0];

      const allowedTypes = ["image/jpeg", "image/png", "image/gif", "application/pdf"]

      if (allowedTypes.includes(file.type)) {
        this.arquivoProposta = file;
      } else {
        this.showErrorMessageWithButton("Ops...", "Apenas imagens e PDF's são permitidos!");
      }
    },

    selecionarComprovante() {

      let file = this.$refs.fileComprovante.files[0];

      const allowedTypes = ["image/jpeg", "image/png", "image/gif", "application/pdf"]

      if (allowedTypes.includes(file.type)) {
        this.arquivoComprovanteVinculo = file;
      } else {
        this.showErrorMessageWithButton("Ops...", "Apenas imagens e PDF's são permitidos!");
      }
    },

    selecionarComprovanteVinculo() {

      let file = this.$refs.fileComprovanteVinculo.files[0];

      const allowedTypes = ["image/jpeg", "image/png", "image/gif", "application/pdf"]

      if (allowedTypes.includes(file.type)) {
        this.arquivoComprovanteResidencia = file;
      } else {
        this.showErrorMessageWithButton("Ops...", "Apenas imagens e PDF's são permitidos!");
      }
    },

    sendFile(arquivo, id) {

      return new Promise((resolve, reject) => {
        const formData = new FormData();
        formData.append('id', id);
        formData.append('file', arquivo);

        axios
          .post(process.env.MIX_VUE_APP_ENDPOINT + `/upload`, formData)
          .then((response) => {

            let path = this.replaceAll(response.data.file.path, "\\", "/");

            resolve(path);

          })
          .catch((error) => {
            this.showErrorMessageWithButton("Ops..", error);
            console.log(error);
            reject();
          });

      })
    },

    replaceAll(string, search, replace) {
      return string.split(search).join(replace);
    },

    generateKey() {
      return Math.random().toString(36).slice(2);
    },

    changeVidas() {

      this.documentos = [];

      for (let i = 0; i < this.qtd_vidas; i++) {
        this.documentos.push({
          id: i,
          nome: "",
          tipo_documento: "CPF",
          file: "",
          filePath: ""
        });
      }
    },

    editarNome(mensagem) {
      mensagem.editar_nome = !mensagem.editar_nome;
    },

    salvar() {
      console.log(this.mensagens);
    },

    addPessoa(mensagem) {
      mensagem.resposta_esperada.push({
        resposta_esperada: "",
        resposta: "",
        proxima_tag: "",
      });
    },

    addProposta() {
      let mensagem = {
        nome: "",
        id: this.mensagens.length + 1,
        tipo_envio: "texto",
        tag: this.getTag(),
        tempo: 0,
        aguardar_resposta: true,
        resposta_padrao: "",
      };

      this.mensagens.push(mensagem);
    },

    getTag() {
      return (
        "01"
      );
    },

    deleteMessage(mensagem) {
      let html = "Deseja deletar esta mensagem?";

      this.$swal
        .fire({
          title: "Confirmação",
          html: html,
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Sim!"
        })
        .then(result => {
          if (result.value) {
            const index = this.mensagens.indexOf(mensagem);

            if (index > -1) {
              this.mensagens.splice(index, 1);
            }

            let aux = 1;

            //REORDENANDO ARRAY
            this.mensagens.forEach((element) => {
              element.id = aux;
              aux++;
            });
          }
        });
    },

    deleteRespostaEsperada(resposta_esperada, indice) {

      let html = "Deseja deletar esta resposta esperada?";

      this.$swal
        .fire({
          title: "Confirmação",
          html: html,
          icon: "info",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Sim!"
        })
        .then(result => {
          if (result.value) {
            resposta_esperada.splice(indice, 1);

            let aux = 1;

            //REORDENANDO ARRAY
            resposta_esperada.forEach((element) => {
              element.id = aux;
              aux++;
            });
          }
        });

    },

    validateResposta(resposta) {
      if (!resposta.resposta) {
        resposta.message_error = true;
      } else {
        resposta.message_error = false;
      }
    },

    validateInput(mensagem) {
      if (mensagem.tipo_envio == "texto" && !mensagem.mensagem) {
        mensagem.message_error = true;
      } else {
        mensagem.message_error = false;
      }

      if (mensagem.tipo_envio != "texto" && !mensagem.url) {
        mensagem.url_error = true;
      } else {
        mensagem.url_error = false;
      }
    },

    validateFields() {
      if (this.mensagens.length <= 0) {
        this.showErrorMessageWithButton("Ops...", "Você precisa adicionar uma mensagem!");
      } else {
        this.mesage_to_send = this.mensagens[0].mensagem;
        this.url_to_send = this.mensagens[0].url;
        this.tipo_envio = this.mensagens[0].tipo_envio;

        if (!this.nome_envio) {
          this.showErrorMessageWithButton("Ops...", "O nome do envio é obrigatório!");
        } else if (this.hasInputError()) {
          this.showErrorMessageWithButton(
            "Ops...",
            "Preencha todos os campos corretamente!"
          );
        } else if (!this.listToSend) {
          this.showErrorMessageWithButton(
            "Ops...",
            "A lista de contatos não pode ficar em branco!"
          );
        } else if (this.mensagens.length <= 1) {
          this.showErrorMessageWithButton(
            "Ops...",
            "A sequência precisa ter pelo menos 2 mensagens!"
          );
        } else {
          this.sendToList();
        }
      }
    },

    hasInputError() {
      let hasError = false;

      for (let i = 0; i < this.mensagens.length; i++) {
        if (this.mensagens[i].tipo_envio == "texto" && !this.mensagens[i].mensagem) {
          this.mensagens[i].message_error = true;
          hasError = true;
        }

        if (this.mensagens[i].tipo_envio != "texto" && !this.mensagens[i].url) {
          this.mensagens[i].url_error = true;
          hasError = true;
        }
      }

      return hasError;
    },

    validateJSON(json) {
      console.log("VALIDANDO JSON");

      try {
        let jsonValido = JSON.parse(json);

        console.log("JS");
        console.log(jsonValido);

        if (jsonValido) {
          return jsonValido;
        } else {
          return null;
        }
      } catch (e) {
        return null;
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
</style>
