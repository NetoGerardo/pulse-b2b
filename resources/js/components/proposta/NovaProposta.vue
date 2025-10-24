<template>
  <div>
    <loading v-model:active="isLoading" :can-cancel="false" :is-full-page="true" />
  </div>

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-success">
          <div class="card-header">
            <h3 class="card-title">Nova Proposta</h3>
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
                  v-model="nome_titular" />
              </div>

              <div class="form-group col-lg-3 col-md-6 col-sm-6">
                <label for="exampleInputEmail1">WhatsApp</label>
                <input class="form-control" id="exampleInputEmail1" placeholder="Whatsapp" v-model="whatsapp" />
              </div>

              <div class="form-group col-lg-3 col-md-4 col-sm-6">
                <label for="exampleInputEmail1">Valor da proposta</label>
                <input class="form-control" id="exampleInputEmail1" placeholder="Valor" v-model="valor_proposta" />
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
                <input id="dataInicio" class="form-control" name="dataInicio" type="date" v-model="data_vencimento" />
              </div>

              <div class="form-group col-lg-3 col-md-4 col-sm-6">
                <label for="email">Quantidade de Vidas</label>
                <select class="form-control" id="user_type" v-model="qtd_vidas" @change="changeVidas">
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                </select>
              </div>

              <div class="form-group col-lg-6 col-md-6 col-sm-6">
                <label>Comprovante de residência</label>
                <input class="form-control" type="file" @change="selecionarComprovanteResidencia"
                  ref="fileComprovanteResidencia" />
              </div>

              <div v-if="plano_selecionado.tipo_plano == 'individual'" class="form-group col-lg-6 col-md-6 col-sm-6">
                <label>Comprovante de Vínculo</label>
                <input class="form-control" type="file" @change="selecionarComprovanteVinculo"
                  ref="fileComprovanteVinculo" />
              </div>

            </div>
          </div>

          <!--  BOTÕES PRINCIPAIS -->
          <form @submit.prevent enctype="multipart/form-data">
            <div class="card-footer">
              <button @click="voltar" class="btn btn-flat btn-info">Voltar</button>
              <button @click="criarNovaProposta()" class="btn btn-flat btn-success">Cadastrar</button>
            </div>
          </form>
        </div>
      </div>
      <br />

      <!--  DADOS FAMILIARES -->
      <div v-if="qtd_vidas > 0" class="col-lg-6 col-md-6 col-sm-12" v-for="(vida, indice) in vidas" :key="vida.id">
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
              <form enctype="multipart/form-data">
                <label>Identidade</label>
                <input class="form-control" type="file" :ref="'fileIdentidadeDependente' + indice" />
              </form>
            </div>

            <!--  DOCUMENTO DEPENDENTE -->
            <div class="form-group">
              <form enctype="multipart/form-data">
                <label>CPF</label>
                <input class="form-control" type="file" :ref="'fileCPFDependente' + indice" />
              </form>
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
                <select class="form-control" id="user_type" v-model="tipo_empresa">
                  <option value="Mei">Mei</option>
                  <option value="Outros">Outros</option>
                </select>
              </div>

              <div class="form-group col-lg-3 col-md-6 col-sm-6">
                <label for="exampleInputEmail1">CNPJ</label>
                <input class="form-control" id="exampleInputEmail1" v-model="cnpj" />
              </div>

              <div class="form-group col-lg-3 col-md-6 col-sm-6">
                <label for="exampleInputEmail1">Identidade Títular</label>
                <input class="form-control" id="exampleInputEmail1" v-model="identidade" />
              </div>

              <div class="form-group col-lg-3 col-md-6 col-sm-6">
                <label for="exampleInputEmail1">CPF</label>
                <input class="form-control" id="exampleInputEmail1" v-model="cpf" />
              </div>

              <div class="form-group col-lg-6 col-md-6 col-sm-6">
                <label>Contrato Social (Ccmei)</label>
                <input class="form-control" type="file" @change="selecionarContratoSocial" ref="fileContratoSocial" />
              </div>

              <div class="form-group col-lg-6 col-md-6 col-sm-6">
                <label>Identidade Títular</label>
                <input class="form-control" type="file" @change="selecionarIdentidadeTitular"
                  ref="fileIdentidadeTitular" />
              </div>

              <div class="form-group col-lg-6 col-md-6 col-sm-6">
                <label>CPF titular</label>
                <input class="form-control" type="file" @change="selecionarCPFTitular" ref="fileCPFTitular" />
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
                <input class="form-control" type="file" :ref="'fileEmpregados' + indice" />
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import sweetAlert from "../controller/sweetAlert";
import Swal from "sweetalert2/dist/sweetalert2.js";
import Loading from "vue-loading-overlay";
import moment from "moment";
import "vue-loading-overlay/dist/vue-loading.css";

export default {

  components: { Loading },

  props: ['contato', 'planos', 'voltar'],

  mixins: [sweetAlert, Swal],

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
      identidade: "",
      cpf: "",
      cnpj: "",
      id_proposta: "",
      vidas: [],
      isLoading: false
    };
  },

  mounted() {
    console.log("Create Send Ready");
    console.log(this.tipo_envio);

    this.nome_titular = this.contato.nome;
    this.whatsapp = this.contato.telefone;

    this.addProposta();

    this.id = this.generateKey();
  },

  methods: {

    selecionarDocumentoConjuge() {

      console.log(this.$refs);
      console.log(this.$refs.fileConjuge);
      let file = this.$refs.fileConjuge[0].files[0];

      const allowedTypes = ["image/jpeg", "image/png", "image/gif", "application/pdf"]

      if (allowedTypes.includes(file.type)) {
        this.arquivoCertidaoCasamento = file;
      } else {
        this.showErrorMessageWithButton("Ops...", "Apenas imagens e PDF's são permitidos!");
      }
    },

    capturarArquivosEmpregados() {

      let error = false;
      let message = "";

      const allowedTypes = ["image/jpeg", "image/png", "image/gif", "application/pdf"]

      if (this.qtd_empregados > 0) {
        let arquivosEmpregados = []
        for (let [key, value] of Object.entries(this.$refs)) {
          if (/^fileEmpregados/.test(key)) {
            arquivosEmpregados.push({ key: value })
          }
        }

        for (let i = 0; i < arquivosEmpregados.length; i++) {
          let arquivo = arquivosEmpregados[i].key[0].files[0];

          if (arquivo) {
            if (allowedTypes.includes(arquivo.type)) {
              this.empregados[i].arquivoComprovanteResidencia = arquivo;
            } else {
              error = true;
              message = "Apenas imagens e PDF's são permitidos!";
            }

          } else {
            error = true;
            message = "Envie todos os dados dos empregados!";
          }
        }
      }

      if (this.qtd_vidas > 0) {

        //IDENTIDADES DEPENDENTES
        let identidadeDependentes = []
        for (let [key, value] of Object.entries(this.$refs)) {
          if (/^fileIdentidadeDependente/.test(key)) {
            identidadeDependentes.push({ key: value })
          }
        }

        //IDENTIDADES DEPENDENTES
        for (let i = 0; i < identidadeDependentes.length; i++) {
          let arquivo = identidadeDependentes[i].key[0].files[0];

          if (arquivo) {
            if (allowedTypes.includes(arquivo.type)) {
              this.vidas[i].arquivoIdentidade = arquivo;
            } else {
              error = true;
              message = "Apenas imagens e PDF's são permitidos!";
            }

          } else {
            error = true;
            message = "Envie todos os dados dos dependentes!";
          }
        }

        //CPF'S DEPENDENTES
        let cpfDependentes = []
        for (let [key, value] of Object.entries(this.$refs)) {
          if (/^fileCPFDependente/.test(key)) {
            cpfDependentes.push({ key: value })
          }
        }

        //CPF'S DEPENDENTES
        for (let i = 0; i < cpfDependentes.length; i++) {
          let arquivo = cpfDependentes[i].key[0].files[0];

          if (arquivo) {
            if (allowedTypes.includes(arquivo.type)) {
              this.vidas[i].arquivoCPF = arquivo;
            } else {
              error = true;
              message = "Apenas imagens e PDF's são permitidos!";
            }

          } else {
            error = true;
            message = "Envie todos os dados dos dependentes!";
          }
        }
      }

      if (error) {
        this.showErrorMessageWithButton("Ops...", message);
        this.isLoading = false;
      } else {
        this.validateFields();
      }

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

    calcularParcelas(proposta_id) {
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
        .post(`/admin/parcelas/store`, data)
        .then((response) => {
          this.showSuccessMessage("Proposta cadastrada!");
          window.location.reload();
        })
        .catch((error) => {
          this.showErrorMessageWithButton("Ops..", error);
          console.log(error);
        });

    },

    formatSelectedDate(date) {
      return moment(date).format("yyyy-MM-DD");
    },

    criarNovaProposta() {

      this.isLoading = true;

      let data = {
        id: this.contato.id,
        status: "Aguardando pagamento"
      }

      axios
        .post(`/user/proposta/new`, data)
        .then((response) => {
          this.id_proposta = response.data.id;
          console.log("ID NOVA PROPOSTA = " + this.id_proposta);
          this.capturarArquivosEmpregados();
        })
        .catch((error) => {
          this.showErrorMessageWithButton("Ops..", error);
          console.log(error);
        });
    },

    validateFields() {

      let error = false;
      let message = "";

      if (!this.arquivoComprovanteResidencia) {
        error = true;
        message = "O comprovante de residência é obrigatório!"
      }

      if (this.plano_selecionado.tipo_plano == "empresarial_pme") {
        if (!this.arquivoContratoSocial ||
          !this.arquivoIdentidadeTitular ||
          !this.arquivoCPFTitular) {
          error = true;
          message = "Envie todos os dados do titular!"
        }

      } else if (this.plano_selecionado.tipo_plano == "individual") {

        if (!this.arquivoComprovanteVinculo) {
          error = true;
          message = "O comprovante de vínculo é obrigatório!"
        }

        if (this.qtd_vidas > 0) {
          for (let i = 0; i < this.vidas.length; i++) {

            //CASO TENHA CONJUGE, VERIFICAR SE O ARQUIVO FOI ENVIADO
            if (this.vidas[i].tipo == "Conjuge") {
              if (!this.arquivoCertidaoCasamento) {
                error = true;
                message = "Envie a certidão de casamento!"
              }
            }
          }
        }
      }

      if (error) {
        this.showErrorMessageWithButton("Ops...", message);
      } else {
        this.upload();
      }
    },

    async upload() {

      let data = {
        id: this.id_proposta,
        contato_id: this.contato.id,
        nome_titular: this.nome_titular,
        whatsapp: this.whatsapp,
        identidade: this.identidade,
        cpf: this.cpf,
        valor_proposta: this.valor_proposta,
        plano_id: this.plano_selecionado.id,
        data_vencimento: this.data_vencimento,
        tipo_empresa: this.tipo_empresa,
        tipo_proposta: this.plano_selecionado.tipo_plano,
        cnpj: this.cnpj,
        qtd_empregados: this.qtd_empregados,
        qtd_vidas: this.qtd_vidas
      }

      let caminhoArquivoComprovanteResidencia = await this.sendFile(this.arquivoComprovanteResidencia);

      data.comprovante_residencia = caminhoArquivoComprovanteResidencia;
      //data.comprovante_vinculo = caminhoArquivoComprovanteVinculo;

      if (this.plano_selecionado.tipo_plano == "empresarial_pme") {
        //CONTRATO SOCIAL
        let caminhoContratoSocial = await this.sendFile(this.arquivoContratoSocial);
        data.contrato_social = caminhoContratoSocial;

        //IDENTIDADE TITULAR
        let caminhoArquivoIdentidadeTitular = await this.sendFile(this.arquivoIdentidadeTitular);
        data.identidade_titular = caminhoArquivoIdentidadeTitular;

        //CPF TITULAR
        let caminhoArquivoCPFTitular = await this.sendFile(this.arquivoCPFTitular);
        data.cpf_titular = caminhoArquivoCPFTitular;

        if (this.qtd_empregados > 0) {
          for (let i = 0; i < this.empregados.length; i++) {
            this.empregados[i].comprovante_residencia = await this.sendFile(this.empregados[i].arquivoComprovanteResidencia);
            this.empregados[i].arquivoComprovanteResidencia = undefined;
          }

          data.empregados = this.empregados;
        }

      } else if (this.plano_selecionado.tipo_plano == "individual") {

        //COMPROVANTE VÍNCULO
        let caminhoComprovanteVinculo = await this.sendFile(this.arquivoComprovanteVinculo);
        data.comprovante_vinculo = caminhoComprovanteVinculo;
      }

      //ARQUIVOS DOS DEPENDENTES
      if (this.qtd_vidas > 0) {
        for (let i = 0; i < this.vidas.length; i++) {
          if (this.vidas[i].tipo == "Conjuge") {
            this.vidas[i].certidao_casamento = await this.sendFile(this.arquivoCertidaoCasamento);
          }

          //UPLOAD CPF DEPENDENTE
          this.vidas[i].arquivo_cpf = await this.sendFile(this.vidas[i].arquivoCPF);
          this.vidas[i].arquivoCPF = undefined;

          //UPLOAD CPF DEPENDENTE
          this.vidas[i].arquivo_identidade = await this.sendFile(this.vidas[i].arquivoIdentidade);
          this.vidas[i].arquivoIdentidade = undefined;
        }

        data.vidas = this.vidas;
      }

      console.log("Enviando");
      console.log(data);

      //let caminhoArquivoProposta = await this.sendFile(this.arquivoProposta);
      //let caminhoArquivoComprovanteVinculo = await this.sendFile(this.arquivoComprovanteVinculo);

      axios
        .post(`/user/proposta/store`, data)
        .then((response) => {
          this.showSuccessMessage("Proposta cadastrada!");
          window.location.reload();
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

    selecionarProposta() {

      let file = this.$refs.fileProposta.files[0];

      const allowedTypes = ["image/jpeg", "image/png", "image/gif", "application/pdf"]

      if (allowedTypes.includes(file.type)) {
        this.arquivoProposta = file;
      } else {
        this.showErrorMessageWithButton("Ops...", "Apenas imagens e PDF's são permitidos!");
      }
    },

    selecionarComprovanteVinculo() {

      let file = this.$refs.fileComprovanteVinculo.files[0];

      const allowedTypes = ["image/jpeg", "image/png", "image/gif", "application/pdf"]

      if (allowedTypes.includes(file.type)) {
        this.arquivoComprovanteVinculo = file;
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

      this.vidas = [];

      for (let i = 0; i < this.qtd_vidas; i++) {
        this.vidas.push({
          id: i,
          nome: "",
          tipo: "",
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
