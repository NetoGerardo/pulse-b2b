<template>
  <loading v-model:active="isLoading" :can-cancel="false" :is-full-page="true" />
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Parcela atual:</h3>
          </div>
          <div class="card-body">
            <table id="zero_config" class="table table-striped table-bordered no-wrap">
              <thead>
                <tr>
                  <th>Status</th>
                  <th>Valor</th>
                  <th>Vencimento</th>
                  <th>Parcela Nº</th>
                  <th style="width:10%" v-if="exibir_confirmar">Ações</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>{{ parcela.status }}</td>
                  <td>{{ parcela.valor }}</td>

                  <td data-label="Data">
                    {{ formatDate(parcela.data_vencimento) }}
                  </td>

                  <td>{{ parcela.numero_parcela }}</td>

                  <td v-if="exibir_confirmar">
                    <button data-toggle="modal" data-target="#confirmar-parcela" class="btn btn-success btn-flat">
                      Confirmar recebimento
                    </button>
                  </td>

                </tr>
              </tbody>
            </table>
          </div>

          <div class="card-footer">
            <button @click="voltar" class="btn btn-flat btn-warning" style="color:white">Voltar</button>
            <button v-if="!parcelas_buscadas" @click="buscarParcelas()" class="btn btn-flat btn-info">Buscar todas <i
                class="fa fa-search"></i></button>

          </div>
        </div>
      </div>

      <div v-if="parcelas_buscadas" class="col-lg-12 col-md-12 col-sm-12">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Todas as parcelas:</h3>
          </div>
          <div class="card-body">
            <table id="zero_config" class="table table-striped table-bordered no-wrap">
              <thead>
                <tr>
                  <th>Status</th>
                  <th>Valor</th>
                  <th>Vencimento</th>
                  <th>Parcela Nº</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="parcela in parcelas" :key="parcela" :value="parcela">
                  <td>{{ parcela.status }}</td>

                  <td>{{ parcela.valor }}</td>

                  <td data-label="Data">
                    {{ formatDate(parcela.data_vencimento) }}
                  </td>

                  <td>{{ parcela.numero_parcela }}</td>

                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="col-md-12">
        <div class="card card-success">
          <div class="card-header">

            <h3 class="card-title">Dados da Proposta</h3>
            <div v-if="plano_selecionado != ''">
              <img alt="Avatar" class="table-avatar" style="min-width:120px; width:120px;opacity: .8"
                :src="plano_selecionado.imagem" />
              <br />
            </div>
          </div>

          <!--  DADOS DA PROPOSTA -->
          <div class="card-body">
            <div class="row">
              <div class="form-group col-lg-6 col-md-6 col-sm-6">
                <label for="exampleInputEmail1">Nome do Titular</label>
                <input class="form-control" id="exampleInputEmail1" placeholder="Digite o nome do titular do plano"
                  v-model="proposta.nome_titular" />
              </div>

              <div class="form-group col-lg-3 col-md-6 col-sm-6">
                <label for="exampleInputEmail1">WhatsApp</label>
                <input class="form-control" id="exampleInputEmail1" placeholder="Whatsapp"
                  v-model="proposta.whatsapp" />
              </div>

              <div class="form-group col-lg-3 col-md-4 col-sm-6">
                <label for="exampleInputEmail1">Valor da proposta</label>
                <input class="form-control" id="exampleInputEmail1" placeholder="Valor"
                  v-model="proposta.valor_proposta" />
              </div>

              <div class="form-group col-lg-3 col-md-4 col-sm-6">
                <label>Selecione um Plano</label>
                <select class="form-control" v-model="plano_selecionado">
                  <option value=""></option>
                  <option v-for="plano in planos" :key="plano" :value="plano">
                    {{ plano.nome }}
                  </option>
                </select>
              </div>

              <div class="form-group col-lg-3 col-md-4 col-sm-6">
                <label for="email">Tipo de plano</label>
                <select disabled="true" class="form-control" id="user_type" v-model="plano_selecionado.tipo_plano">
                  <option value="individual">Plano Individual</option>
                  <option value="empresarial_pme">Empresarial PME</option>
                  <option value="adesao">Adesão</option>
                </select>
              </div>

              <div class="form-group col-lg-3 col-md-4 col-sm-6">
                <label for="exampleInputEmail1">Data de vencimento</label>
                <input id="dataInicio" class="form-control" name="dataInicio" type="date"
                  v-model="proposta.data_vencimento" />
              </div>

              <div class="form-group col-lg-3 col-md-4 col-sm-6">
                <label for="email">Quantidade de Vidas</label>
                <select class="form-control" id="user_type" v-model="proposta.qtd_vidas" @change="changeVidas">
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                </select>
              </div>

              <div class="form-group col-lg-6 col-md-6 col-sm-6">
                <label>Comprovante de residência</label>
                <div v-if="!proposta.comprovante_residencia || enviar_comprovante">
                  <input class="form-control" type="file" @change="selecionarComprovante" ref="fileComprovante" />
                  <button @click="enviar_comprovante = false" class="btn btn-outline-danger"
                    style="float: right;">Cancelar</button>
                </div>

                <div v-if="proposta.comprovante_residencia && !enviar_comprovante">
                  <button data-toggle="modal" data-target="#bs-example-modal-lg"
                    @click="imagem_selecionada = proposta.comprovante_residencia" class="btn btn-outline-info">Exibir
                    Comprovante</button>
                </div>
              </div>

              <div class="form-group col-lg-6 col-md-6 col-sm-6" v-if="plano_selecionado.tipo_plano == 'individual'">
                <label>Comprovante de Vínculo</label>
                <div v-if="!proposta.comprovante_vinculo || enviar_comprovante_vinculo">
                  <input class="form-control" type="file" @change="selecionarComprovanteVinculo"
                    ref="fileComprovanteVinculo" />
                  <button @click="enviar_comprovante_vinculo = false" class="btn btn-outline-danger"
                    style="float: right;">Cancelar</button>
                </div>

                <div v-if="proposta.comprovante_vinculo && !enviar_comprovante_vinculo">
                  <button data-toggle="modal" data-target="#bs-example-modal-lg"
                    @click="imagem_selecionada = proposta.comprovante_vinculo" class="btn btn-outline-info">Exibir
                    Comprovante
                    de vínculo</button>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
      <br />
      <!--  DADOS DA EMPRESA -->
      <div v-if="plano_selecionado.tipo_plano == 'empresarial_pme'">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Dados da Empresa</h3>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="form-group col-lg-3 col-md-6 col-sm-6">
                <label for="email">Tipo empresa</label>
                <select class="form-control" id="user_type" v-model="proposta.tipo_empresa">
                  <option value="Mei">Mei</option>
                  <option value="Outros">Outros</option>
                </select>
              </div>

              <div class="form-group col-lg-3 col-md-6 col-sm-6">
                <label for="exampleInputEmail1">CNPJ</label>
                <input class="form-control" id="exampleInputEmail1" v-model="proposta.cnpj" />
              </div>

              <div class="form-group col-lg-3 col-md-6 col-sm-6">
                <label for="exampleInputEmail1">Identidade Títular</label>
                <input class="form-control" id="exampleInputEmail1" v-model="proposta.identidade" />
              </div>

              <div class="form-group col-lg-3 col-md-6 col-sm-6">
                <label for="exampleInputEmail1">CPF</label>
                <input class="form-control" id="exampleInputEmail1" v-model="proposta.cpf" />
              </div>

              <div class="form-group col-lg-6 col-md-6 col-sm-6">
                <label>Contrato Social (Ccmei)</label>

                <div v-if="!proposta.contrato_social || enviar_contrato_social">
                  <input class="form-control" type="file" @change="selecionarContratoSocial" ref="fileContratoSocial" />
                  <button @click="enviar_contrato_social = false" class="btn btn-outline-danger"
                    style="float: right;">Cancelar</button>
                </div>

                <div v-if="proposta.contrato_social && !enviar_contrato_social">
                  <button data-toggle="modal" data-target="#bs-example-modal-lg"
                    @click="imagem_selecionada = proposta.contrato_social" class="btn btn-outline-info">Exibir
                    contrato social</button>
                </div>
              </div>

              <div class="form-group col-lg-6 col-md-6 col-sm-6">
                <label>Identidade Títular</label>

                <div v-if="!proposta.identidade_titular || enviar_identidade">
                  <input class="form-control" type="file" @change="selecionarIdentidadeTitular"
                    ref="fileIdentidadeTitular" />
                  <button @click="enviar_identidade = false" class="btn btn-outline-danger"
                    style="float: right;">Cancelar</button>
                </div>

                <div v-if="proposta.identidade_titular && !enviar_identidade">
                  <button data-toggle="modal" data-target="#bs-example-modal-lg"
                    @click="imagem_selecionada = proposta.identidade_titular" class="btn btn-outline-info">Exibir
                    identidade</button>
                </div>
              </div>

              <div class="form-group col-lg-6 col-md-6 col-sm-6">
                <label>CPF titular</label>

                <div v-if="!proposta.cpf_titular || enviar_cpf">
                  <input class="form-control" type="file" @change="selecionarCPFTitular" ref="fileCPFTitular" />
                  <button @click="enviar_cpf = false" class="btn btn-outline-danger"
                    style="float: right;">Cancelar</button>
                </div>

                <div v-if="proposta.cpf_titular && !enviar_cpf">
                  <button data-toggle="modal" data-target="#bs-example-modal-lg"
                    @click="imagem_selecionada = proposta.cpf_titular" class="btn btn-outline-info">Exibir
                    CPF</button>
                </div>
              </div>

              <div v-if="tipo_empresa == 'Outros'" class="form-group col-lg-3 col-md-4 col-sm-6">
                <label for="email">Empregados</label>
                <select class="form-control" id="user_type" v-model="qtd_empregados" @change="changeEmpregados">
                  <option value="0">0</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12" v-if="documentos.length > 0">
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

      <div class="modal fade" id="bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="myLargeModalLabel"></h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
              <img alt="Avatar" class="table-avatar" :src="imagem_selecionada" style="max-width:100%" />
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light" data-dismiss="modal">Fechar</button>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->

      <!-- Confirmar data recebimento -->
      <div ref="modal" class="modal fade" id="confirmar-parcela" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <form @submit.prevent>
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                  &times;
                </button>
                <h4>Gerar parcelas</h4>
                <br />

                <div class="form-group">
                  <label for="email">Data de recebimento</label>
                  <input class="form-control" type="date" v-model="data_recebimento" />
                </div>

                <button class="btn btn-primary btn-flat" data-toggle="modal" data-target="#detalhes-usuario">
                  Fechar
                </button>

                <button @click="confirmarParcela" class="btn btn-success btn-flat text-md-end">
                  Confirmar
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>

      <div v-if="empregados.length > 0" class="col-lg-6 col-md-6 col-sm-12" v-for="(empregado, indice) in empregados"
        :key="empregado.id">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Documentos Empregado {{ indice + 1 }}</h3>
          </div>
          <div class="card-body" style="padding: 10px 10px 10px 10px">

            <div class="form-group">
              <label for="exampleInputEmail1">Nome</label>
              <input class="form-control" id="exampleInputEmail1" v-model="empregado.nome" />
            </div>

            <div class="form-group">
              <form enctype="multipart/form-data">
                <label>Comprovante de residência</label>
                <input class="form-control" type="file" @change="selectFile" ref="file" />
              </form>
            </div>
          </div>
        </div>
      </div>
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

  props: ['proposta', 'planos', 'voltar', 'parcela', 'exibir_confirmar'],

  mixins: [wppController, sweetAlert, Swal],

  components: { Loading },

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
      tipo_plano: "",
      tipo_empresa: "",
      qtd_empregados: 0,
      empregados: [],
      identidade_titular: "",
      cpf: "",
      cnpj: "",
      id_proposta: "",
      enviar_comprovante: false,
      imagem_selecionada: "",
      enviar_comprovante: false,
      enviar_comprovante_vinculo: false,
      enviar_contrato_social: false,
      enviar_identidade: false,
      enviar_cpf: false,
      parcelas_buscadas: false,
      parcelas: [],
      isLoading: false,
      data_recebimento: ""
    };
  },

  mounted() {
    console.log("Create Send Ready");
    console.log(this.tipo_envio);

    this.addProposta();

    this.plano_selecionado = this.planos[this.findPlano(this.proposta.plano_id)];
  },

  methods: {

    confirmarParcela() {
      this.$swal
        .fire({
          title: "Confirmação",
          html: "Deseja cofirmar o recebimento de <b style='color:green'> R$" + this.parcela.valor + "</b> ?</b>",
          icon: "info",
          showCancelButton: true,
          confirmButtonColor: "#4caf50",
          cancelButtonColor: "#d33",
          confirmButtonText: "Sim!"
        })
        .then(result => {
          if (result.value) {
            let data = {
              id: this.parcela.id,
              status: "Confirmado",
              data_recebimento: this.data_recebimento
            };

            axios
              .post(`/admin/parcelas/update`, data)
              .then((response) => {

                this.showSuccessMessage("Parcela confirmada!");

                window.location.reload();
              })
              .catch((error) => {
                this.showErrorMessageWithButton("Ops..", error);
                console.log(error);
              });
          }
        });
    },

    buscarParcelas() {

      this.isLoading = true;

      let data = {
        proposta_id: this.proposta.id,
      }

      console.log(data);

      axios
        .post(`/admin/parcelas/find-all`, data)
        .then((response) => {
          this.parcelas_buscadas = true;
          this.parcelas = response.data.parcelas;
          this.isLoading = false;

          console.log(this.parcelas);
          console.log(this.parcelas_buscadas);
        })
        .catch((error) => {
          this.showErrorMessageWithButton("Ops..", error);
          console.log(error);
        });
    },

    updateProposta() {
      this.showSuccessMessage("Proposta cadastrada!");
    },

    findPlano(id) {
      let plano = this.planos.findIndex(element => element['id'] == id);

      return plano;
    },

    changeEmpregados() {

      this.empregados = [];

      for (let i = 0; i < this.qtd_empregados; i++) {
        this.empregados.push({
          id: i,
          nome: "",
          esocial: "",
          comprovante_residencia: "",
        });
      }
    },

    formatSelectedDate(date) {
      return moment(date).format("yyyy-MM-DD");
    },

    async reenviarProposta() {

      let data = {
        id: this.proposta.id,
        contato_id: this.proposta.contato_id,
        nome_titular: this.proposta.nome_titular,
        whatsapp: this.proposta.whatsapp,
        valor_proposta: this.proposta.valor_proposta,
        plano_id: this.plano_selecionado.id,
        tipo_proposta: this.plano_selecionado.tipo_plano,
        data_vencimento: this.proposta.data_vencimento,
        tipo_empresa: this.proposta.tipo_empresa,
        cnpj: this.proposta.cnpj,
        qtd_empregados: this.proposta.qtd_empregados,
        qtd_vidas: this.proposta.qtd_vidas,
        cpf: this.proposta.cpf,
        identidade: this.proposta.identidade,
      }

      //COMPROVANTE DE RESIDENCIA
      if (this.enviar_comprovante) {
        let caminhoArquivoComprovanteResidencia = await this.sendFile(this.arquivoComprovanteResidencia);
        data.comprovante_residencia = caminhoArquivoComprovanteResidencia;
      }

      //CONTRATO SOCIAL
      if (this.enviar_contrato_social) {
        let caminhoContratoSocial = await this.sendFile(this.arquivoContratoSocial);
        data.contrato_social = caminhoContratoSocial;
      }

      //IDENTIDADE TITULAR
      if (this.enviar_identidade) {
        let caminhoArquivoIdentidadeTitular = await this.sendFile(this.arquivoIdentidadeTitular);
        data.arquivo_identidade_titular = caminhoArquivoIdentidadeTitular;
      }

      //CPF TITULAR
      if (this.enviar_cpf) {
        let caminhoArquivoCPFTitular = await this.sendFile(this.arquivoCPFTitular);
        data.cpf_titular = caminhoArquivoCPFTitular;
      }

      console.log("Enviando");
      console.log(data);

      axios
        .post(`/user/proposta/reenviar`, data)
        .then((response) => {
          this.showSuccessMessage("Proposta cadastrada!");
          window.location.reload();
        })
        .catch((error) => {
          this.showErrorMessageWithButton("Ops..", error);
          console.log(error);
        });

    },

    selecionarComprovanteResidenciaTitular() {

      let file = this.$refs.fileProposta.files[0];

      const allowedTypes = ["image/jpeg", "image/png", "image/gif", "application/pdf"]

      if (allowedTypes.includes(file.type)) {
        this.arquivoComprovanteResidenciaTitular = file;
      } else {
        this.showErrorMessageWithButton("Ops...", "Apenas imagens e PDF's são permitidos!");
      }
    },

    selecionarCPFTitular() {

      let file = this.$refs.fileCPFTitular.files[0];

      const allowedTypes = ["image/jpeg", "image/png", "image/gif", "application/pdf"]

      if (allowedTypes.includes(file.type)) {
        this.arquivoCPFTitular = file;
      } else {
        this.showErrorMessageWithButton("Ops...", "Apenas imagens e PDF's são permitidos!");
      }
    },

    selecionarIdentidadeTitular() {

      let file = this.$refs.fileIdentidadeTitular.files[0];

      const allowedTypes = ["image/jpeg", "image/png", "image/gif", "application/pdf"]

      if (allowedTypes.includes(file.type)) {
        this.arquivoIdentidadeTitular = file;
      } else {
        this.showErrorMessageWithButton("Ops...", "Apenas imagens e PDF's são permitidos!");
      }
    },

    selecionarContratoSocial() {

      let file = this.$refs.fileContratoSocial.files[0];

      const allowedTypes = ["image/jpeg", "image/png", "image/gif", "application/pdf"]

      if (allowedTypes.includes(file.type)) {
        this.arquivoContratoSocial = file;
      } else {
        this.showErrorMessageWithButton("Ops...", "Apenas imagens e PDF's são permitidos!");
      }
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

    sendFile(arquivo) {
      return new Promise((resolve, reject) => {
        const formData = new FormData();
        formData.append('id', this.id_proposta);
        formData.append('file', arquivo);

        axios
          .post(process.env.MIX_VUE_APP_ENDPOINT + `/upload`, formData)
          .then((response) => {

            let path = "/" + this.replaceAll(response.data.file.path, "\\", "/");

            resolve(path);

          })
          .catch((error) => {
            this.showErrorMessageWithButton("Ops..", error.response.data.error);
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
        tempo: 0,
        aguardar_resposta: true,
        resposta_padrao: "",
      };

      this.mensagens.push(mensagem);
    },

    confirmacaoSalvar() {
      this.$swal
        .fire({
          title: "Atenção",
          text: "Deseja salvar as alterações feitas antes de rejeitar?",
          icon: "warning",
          padding: '1.5em',
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Sim, quero salvar!",
          cancelButtonText: "Não"
        })
        .then(result => {
          if (result.value) {
            this.updateProposta();
          } else {
            this.descreverPendencias();
          }
        });
    },

    descreverPendencias(element) {
      let html = "Descreva as <b style='color:red'>pendências </b> a serem resolvidas:";

      this.$swal
        .fire({
          title: "Confirmação",
          html: html,
          icon: "info",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Confirmar",
          input: 'textarea',
          inputAttributes: {
            autocapitalize: 'off',
            required: false
          },
          inputValidator: (value) => {
            if (!value) {
              return 'Descreva as pendências!'
            }
            else {
              this.addPendencias(value)
            }
          }
        })
    },

    addPendencias(pendencias) {
      this.isLoading = true;

      let data = {
        contato_id: this.proposta.contato_id,
        descricao_pendencia: pendencias,
        proposta_id: this.proposta.id
      }

      console.log(data);

      axios
        .post(`/admin/proposta/add-pendencias`, data)
        .then((response) => {
          this.showSuccessMessage("Pendências registradas!");
          window.location.reload();
        })
        .catch((error) => {
          this.showErrorMessageWithButton("Ops..", error);
          console.log(error);
        });
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

    formatDate(date) {
      return moment(date).format("DD/MM/YYYY");
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
