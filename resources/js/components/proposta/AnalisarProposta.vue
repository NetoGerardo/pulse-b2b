<template>
  <loading v-model:active="isLoading" :can-cancel="false" :is-full-page="true" />
  <div class="container-fluid">
    <div class="row">

      <div v-if="proposta.descricao_pendencia" class="col-lg-12 col-md-12 col-sm-12">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Pendências:</h3>
          </div>
          <div class="card-body">
            <textarea readonly=" true" class="form-control" id="senha" type="senha"
              v-model="proposta.descricao_pendencia" autocomplete="senha" rows="5" cols="40" />
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
                  <input class="form-control" type="file" @change="selecionarComprovanteResidencia"
                    ref="fileComprovanteResidencia" />
                  <button @click="enviar_comprovante = false" class="btn btn-outline-danger"
                    style="float: right;">Cancelar</button>
                </div>

                <div v-if="proposta.comprovante_residencia && !enviar_comprovante">
                  <button data-toggle="modal" data-target="#bs-example-modal-lg"
                    @click="abrirImagemOuPDF(proposta.comprovante_residencia)" class="btn btn-outline-info">Exibir
                    Comprovante</button>
                  <button @click="enviar_comprovante = true" class="btn btn-outline-success">Enviar novo</button>
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
                    @click="abrirImagemOuPDF(proposta.comprovante_vinculo)" class="btn btn-outline-info">Exibir
                    Comprovante
                    de vínculo</button>
                  <button @click="enviar_comprovante_vinculo = true" class="btn btn-outline-success">Enviar
                    novo</button>
                </div>
              </div>

            </div>
          </div>

          <!--  BOTÕES PRINCIPAIS -->
          <form @submit.prevent enctype="multipart/form-data">
            <div class="card-footer">
              <button @click="voltar" class="btn btn-flat btn-info">Voltar</button>
              <button @click="confirmacaoSalvar()" class="btn btn-flat btn-danger">Registrar pendência</button>
              <button @click="valdiateFields()" class="btn btn-flat btn-success">Cadastrar Proposta</button>

              <div class="form-group">
                <br />
                <label>PDF da proposta</label>
                <div v-if="!proposta.pdf_proposta || enviar_pdf_proposta">
                  <input class="form-control" type="file" @change="selecionarPDFProposta" ref="fileProposta" />
                  <button @click="enviar_pdf_proposta = false" class="btn btn-outline-danger"
                    style="float: right;">Cancelar</button>
                </div>

                <div v-if="proposta.pdf_proposta && !enviar_pdf_proposta">
                  <button data-toggle="modal" data-target="#bs-example-modal-lg"
                    @click="abrirImagemOuPDF(proposta.pdf_proposta)" class="btn btn-outline-info">Exibir
                    PDF Proposta</button>
                  <button @click="enviar_pdf_proposta = true" class="btn btn-outline-success">Enviar
                    novo</button>
                </div>
                <br />
                <br />
              </div>
            </div>
          </form>
        </div>
      </div>
      <br />

      <!--  DADOS FAMILIARES -->
      <div v-if="proposta.qtd_vidas > 0" class="col-lg-6 col-md-6 col-sm-12" v-for="(vida, indice) in vidas"
        :key="vida.id">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Documentos Familiar {{ indice + 1 }}</h3>
          </div>
          <div class="card-body" style="padding: 10px 10px 10px 10px">

            <div class="form-group">
              <label for="exampleInputEmail1">Tipo</label>
              <select class="form-control" id="user_type" v-model="vida.tipo">
                <option value="Conjuge">Conjuge</option>
                <option value="Dependente">Dependente</option>
                <option value="Sobrinho">Sobrinho</option>
                <option value="Tutelado">Tutelado</option>
              </select>
            </div>

            <!-- ENDEREÇO -->
            <div class="form-group">
              <form enctype="multipart/form-data">
                <label>Endereço</label>
                <input class="form-control" type="text" v-model="vida.endereco" />
              </form>
            </div>

            <!--  DOCUMENTO DEPENDENTE -->
            <div class="form-group">
              <form enctype="multipart/form-data">
                <label>Identidade</label>
                <input class="form-control" type="text" v-model="vida.identidade" />
              </form>
            </div>

            <!--  DOCUMENTO DEPENDENTE -->
            <div class="form-group">
              <form enctype="multipart/form-data">
                <label>CPF</label>
                <input class="form-control" type="text" v-model="vida.cpf" />
              </form>
            </div>

            <!--  DOCUMENTO DEPENDENTE -->
            <div class="form-group">
              <!--
              <form enctype="multipart/form-data">
                <label>Identidade</label>
                <input v-if="vida.enviar_nova_identidade" class="form-control" type="file"
                  :ref="'fileIdentidadeDependente' + indice" />
              </form>
              -->

              <button v-if="vida.arquivo_identidade && !vida.enviar_nova_identidade" data-toggle="modal"
                data-target="#bs-example-modal-lg" @click="abrirImagemOuPDF(vida.arquivo_identidade)"
                class="btn btn-outline-info">Exibir
                Identidade</button>
            </div>

            <!--  DOCUMENTO DEPENDENTE -->
            <div class="form-group">
              <!--
              <form enctype="multipart/form-data">
                <label>CPF</label>
                <input v-if="vida.enviar_novo_cpf" class="form-control" type="file"
                  :ref="'fileCPFDependente' + indice" />
              </form>
              -->

              <button v-if="vida.arquivo_cpf && !vida.enviar_novo_cpf" data-toggle="modal"
                data-target="#bs-example-modal-lg" @click="abrirImagemOuPDF(vida.arquivo_cpf)"
                class="btn btn-outline-info">Exibir
                CPF</button>

              <!-- 
              <button v-if="!vida.enviar_novo_cpf" @click="vida.enviar_novo_cpf = true"
                class="btn btn-outline-success">Enviar
                novo</button>
              -->

              <button v-if="vida.enviar_novo_cpf" style="float: left;" @click="vida.enviar_novo_cpf = false" class="btn
                  btn-outline-danger">Cancelar</button>

              <button v-if="vida.enviar_novo_cpf" style="float: right;" @click="capturarArquivosEmpregado(indice)"
                class="btn
                  btn-outline-success">Upload</button>
            </div>

            <!--  DOCUMENTO DEPENDENTE -->
            <div class="form-group" v-if="vida.tipo == 'Sobrinho'">
              <form enctype="multipart/form-data">
                <label>Documento Responsável</label>
                <input class="form-control" type="text" v-model="vida.documento_responsavel" />
              </form>
            </div>

            <!--  DOCUMENTO CONJUGE -->
            <div class="form-group" v-if="vida.tipo == 'Conjuge'">
              <form enctype="multipart/form-data">
                <label>Certidão de casamento</label>
                <input class="form-control" type="file" ref="fileConjuge" @change="selecionarDocumentoConjuge" />
              </form>
            </div>
          </div>
        </div>
      </div>

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
                    @click="abrirImagemOuPDF(proposta.contrato_social)" class="btn btn-outline-info">Exibir
                    contrato social</button>
                  <button @click="enviar_contrato_social = true" class="btn btn-outline-success">Enviar
                    novo</button>
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
                    @click="abrirImagemOuPDF(proposta.identidade_titular)" class="btn btn-outline-info">Exibir
                    identidade</button>
                  <button @click="enviar_identidade = true" class="btn btn-outline-success">Enviar
                    novo</button>
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
                    @click="abrirImagemOuPDF(proposta.cpf_titular)" class="btn btn-outline-info">Exibir
                    CPF</button>
                  <button @click="enviar_cpf = true" class="btn btn-outline-success">Enviar
                    novo</button>
                </div>
              </div>

              <div v-if="proposta.tipo_empresa == 'Outros'" class="form-group col-lg-3 col-md-4 col-sm-6">
                <label for="email">Empregados</label>
                <select class="form-control" id="user_type" v-model="proposta.qtd_empregados"
                  @change="changeEmpregados">
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

      <!--  DADOS EMPREGADOS-->
      <div v-if="empregados.length > 0" class="col-lg-6 col-md-6 col-sm-12" v-for="(empregado, indice) in empregados"
        :key="empregado.id">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Documentos Empregado {{ indice + 1 }}</h3>
          </div>
          <div class="card-body" style="padding: 10px 10px 10px 10px">

            <div class="form-group">
              <label for="exampleInputEmail1">Esocial</label>
              <input class="form-control" id="exampleInputEmail1" v-model="empregado.esocial" />
            </div>

            <div class="form-group">
              <form enctype="multipart/form-data">
                <label>Comprovante de residência</label>
                <input v-show="empregado.enviar_novo" class="form-control" type="file"
                  :ref="'fileEmpregados' + indice" />
              </form>

              <button v-if="empregado.comprovante_residencia && !empregado.enviar_novo" data-toggle="modal"
                data-target="#bs-example-modal-lg" @click="abrirImagemOuPDF(proposta.identidade_titular)"
                class="btn btn-outline-info">Exibir
                Comprovante</button>

              <button v-if="!empregado.enviar_novo" @click="empregado.enviar_novo = true"
                class="btn btn-outline-success">Enviar
                novo</button>

              <button v-if="empregado.enviar_novo" style="float: left;" @click="empregado.enviar_novo = false" class="btn
                  btn-outline-danger">Cancelar</button>

              <button v-if="empregado.enviar_novo" style="float: right;" @click="capturarArquivosEmpregado(indice)"
                class="btn
                  btn-outline-success">Upload</button>
            </div>
          </div>
        </div>
      </div>

      <div v-if="imagem_selecionada != ''" class="modal fade" id="bs-example-modal-lg" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
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

  props: ['proposta', 'planos', 'voltar'],

  mixins: [wppController, sweetAlert, Swal],

  components: { Loading },

  data() {
    return {
      isLoading: false,
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
      enviar_comprovante_vinculo: false,
      enviar_contrato_social: false,
      enviar_identidade: false,
      enviar_cpf: false,
      enviar_comprovante: false,
      enviar_pdf_proposta: false,
      vidas: []
    };
  },

  mounted() {
    console.log("Create Send Ready");
    console.log(this.tipo_envio);

    this.addProposta();

    this.plano_selecionado = this.planos[this.findPlano(this.proposta.plano_id)];

    this.empregados = this.extrairEmpregados(this.proposta.empregados);

    this.vidas = this.extrairVidas(this.proposta.vidas);
  },

  methods: {

    abrirImagemOuPDF(caminho) {
      let array_caminho = caminho.split(".");

      let extensao = array_caminho[1];
      this.imagem_selecionada = caminho;

      if (extensao == "pdf" || extensao == "PDF") {
        window.open(process.env.MIX_VUE_APP_ENDPOINT_SITE + caminho, '_blank');
        this.imagem_selecionada = "";
      }

    },

    extrairVidas(json) {

      let vidas = [];

      for (let i = 0; i < this.proposta.qtd_vidas; i++) {
        vidas.push({
          id: i,
          nome: "",
          cpf: "",
        });
      }

      try {
        let jsonValido = JSON.parse(json);

        if (jsonValido) {

          //RESETANDO OPÇÃO DE ENVIAR NOVA IMAGEM
          for (let i = 0; i < jsonValido.length; i++) {
            jsonValido[i].enviar_novo_cpf = false;
            jsonValido[i].enviar_nova_identidade = false;
          }

          return jsonValido;
        } else {
          return vidas;
        }
      } catch (e) {
        return vidas;
      }
    },

    extrairEmpregados(json) {

      let empregados = [];

      for (let i = 0; i < this.proposta.qtd_empregados; i++) {
        empregados.push({
          id: i,
          nome: "",
          esocial: "",
          comprovante_residencia: "",
        });
      }

      try {
        let jsonValido = JSON.parse(json);

        if (jsonValido) {

          //RESETANDO OPÇÃO DE ENVIAR NOVA IMAGEM
          for (let i = 0; i < jsonValido.length; i++) {
            jsonValido[i].enviar_novo = false;
          }

          return jsonValido;
        } else {
          return empregados;
        }
      } catch (e) {
        return empregados;
      }
    },

    valdiateFields() {
      if (!this.proposta.pdf_proposta && !this.arquivoProposta) {
        this.showErrorMessageWithButton("Ops...", "Você precisa enviar o PDF da proposta!");
      } else {
        this.salvarAtualizacoes(() => {
          this.update()
        });
      }
    },

    async update() {
      this.isLoading = true;

      let caminhoArquivoPDFProposta = undefined;

      if (!this.proposta.pdf_proposta && !this.enviar_pdf_proposta) {
        caminhoArquivoPDFProposta = await this.sendFile(this.arquivoProposta, this.proposta.id);
      }

      let data = {
        id: this.proposta.id,
        pdf_proposta: caminhoArquivoPDFProposta,
        contato_id: this.proposta.contato_id
      };

      axios
        .post(`/admin/proposta/cadastrar`, data)
        .then((response) => {
          this.showSuccessMessage("Proposta cadastrada!");

          window.location.reload();
        })
        .catch((error) => {
          this.showErrorMessageWithButton("Ops..", error);
          console.log(error);
        });
    },

    updateProposta() {

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
            } else {
              this.salvarAtualizacoes(() => {
                this.addPendencias(value);
              })
            }
          }
        })
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

    async salvarAtualizacoes(callback) {

      this.isLoading = true;

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

      axios
        .post(`/admin/proposta/update`, data)
        .then((response) => {
          callback();
        })
        .catch((error) => {
          this.showErrorMessageWithButton("Ops..", error);
          console.log(error);
        });

    },

    selecionarComprovanteResidencia() {

      let file = this.$refs.fileComprovanteResidencia.files[0];

      const allowedTypes = ["image/jpeg", "image/png", "image/gif", "application/pdf"]

      if (allowedTypes.includes(file.type)) {
        this.arquivoComprovanteResidencia = file;
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

    selecionarPDFProposta() {

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

            let arrayPath = path.split("public");

            path = arrayPath[1];

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
