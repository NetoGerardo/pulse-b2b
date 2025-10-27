<template>
  <loading v-model:active="isLoading" :can-cancel="false" :is-full-page="true" />

  <h4>Editar campanha</h4>

  <!-- EDITAR-->
  <div class="row" style="margin-top: 20px">
    <div class="col-sm-3">
      <div class="form-group">
        <label for="nome">Nome</label>
        <input class="form-control" id="nome" v-model="formData.nome" />

        <span style="color: red" v-for="error in v$.nome.$errors" :key="error.$uid">
          {{ error.$message }}
        </span>
      </div>
    </div>

    <div class="col-sm-3">
      <div class="form-group">
        <label for="nome">Data de ínicio</label>

        <input class="form-control" type="date" v-model="formData.data_inicio" />

        <span style="color: red" v-for="error in v$.data_inicio.$errors" :key="error.$uid">
          {{ error.$message }}
        </span>
      </div>
    </div>

    <div class="col-sm-3">
      <div class="form-group">
        <label for="nome">Opção MEI</label>

        <select class="form-control" id="user_type" v-model="formData.opcao_mei">
          <option value=""></option>
          <option value="Somente MEI">Somente MEI</option>
          <option value="Excluir MEI">Excluir MEI</option>
        </select>

        <span style="color: red" v-for="error in v$.opcao_mei.$errors" :key="error.$uid">
          {{ error.$message }}
        </span>
      </div>
    </div>

    <div class="col-sm-3">
      <div class="form-group">
        <label for="nome">Produto</label>

        <select class="form-control" id="user_type" v-model="formData.produto">
          <option value=""></option>
          <option value="Produto 01">Produto 01</option>
          <option value="Produto 02">Produto 02</option>
          <option value="Produto 03">Produto 03</option>
        </select>

        <span style="color: red" v-for="error in v$.produto.$errors" :key="error.$uid">
          {{ error.$message }}
        </span>
      </div>
    </div>

    <div class="col-sm-3">
      <div class="form-group">
        <label for="nome">UF</label>

        <select @change="buscarCidades()" class="custom-select rounded-0" v-model="formData.estado_id">
          <option value=""></option>
          <option v-for="estado in estados" :key="estado.id" :value="estado.id">
            {{ estado.nome }}
          </option>
        </select>

        <span style="color: red" v-for="error in v$.estado_id.$errors" :key="error.$uid">
          {{ error.$message }}
        </span>
      </div>
    </div>

    <div class="col-sm-3">
      <div class="form-group">
        <label for="nome">Município</label>

        <select class="custom-select rounded-0" v-model="formData.cidade_id">
          <option value=""></option>
          <option v-for="cidade in cidades" :key="cidade.id" :value="cidade.id">
            {{ cidade.nome }}
          </option>
        </select>

        <span style="color: red" v-for="error in v$.cidade_id.$errors" :key="error.$uid">
          {{ error.$message }}
        </span>
      </div>
    </div>

    <div class="col-sm-3">
      <div class="form-group">
        <label for="nome">Data abertura de</label>

        <input class="form-control" type="date" v-model="formData.data_abertura_inicio" />
      </div>
    </div>

    <div class="col-sm-3">
      <div class="form-group">
        <label for="nome">Data abertura até</label>

        <input class="form-control" type="date" v-model="formData.data_abertura_fim" />
      </div>
    </div>

    <div class="col-sm-3">
      <div class="form-group">
        <label for="nome">Total contatos</label>
        <input class="form-control" id="nome" v-model="formData.total_contatos" />
      </div>
    </div>

    <div class="col-sm-3">
      <div class="form-group">
        <label for="nome">Canal</label>

        <select class="form-control" id="user_type" v-model="formData.canal">
          <option value=""></option>
          <option value="Canal 01">Canal 01</option>
          <option value="Canal 02">Canal 02</option>
        </select>
      </div>
    </div>

    <div class="col-sm-3">
      <div class="form-group">
        <label for="nome">Status</label>

        <select class="form-control" id="user_type" v-model="formData.status">
          <option value=""></option>
          <option value="Agendada">Agendada</option>
          <option value="Em andamento">Em andamento</option>
          <option value="Finalizada">Finalizada</option>
          <option value="Cancelada">Cancelada</option>
        </select>
      </div>
    </div>
    <div class="col-sm-12">
      <div class="form-group">
        <label>Mensagem</label>
        <textarea class="form-control" id="senha" type="senha" v-model="formData.mensagem" autocomplete="senha" rows="5" cols="40" />
      </div>
    </div>
  </div>

  <div class="card-footer" style="width: 100%; margin-top: 20px">
    <button type="submit" class="btn btn-primary" @click="update()">Salvar</button>
    <button id="close_editar" data-dismiss="modal" @click="close" class="btn btn-danger" style="margin-left: 15px">Fechar</button>
  </div>
</template>

<script>
import sweetAlert from "../../controller/sweetAlert";
import Swal from "sweetalert2/dist/sweetalert2.js";
import "vue-loading-overlay/dist/vue-loading.css";
import Loading from "vue-loading-overlay";

import useVueLidate from "@vuelidate/core";
import { required } from "../../locales/i18n.js";
import { computed, reactive } from "vue";
import { helpers } from "@vuelidate/validators";

export default {
  mixins: [sweetAlert, Swal],

  props: ["campanha", "estados", "closeModal", "acaoRealizada"],

  components: { Loading },

  data() {
    return {
      isLoading: false,
      cidades: "",
    };
  },

  setup() {
    const formData = reactive({
      nome: "",
      data_inicio: "",
      data_fim: "",
      opcao_mei: "",
      estado_id: "",
      cidade_id: "",
      data_abertura_inicio: "",
      data_abertura_fim: "",
      produto: "",
      status: "",
      canal: "",
      mensagem: "",
    });

    const rules = computed(() => {
      return {
        nome: { required },
        data_inicio: {
          required,
          validDate: helpers.withMessage(" Digite uma data válida.", validDate),
          validarDiaUtil: helpers.withMessage(" Não é possível iniciar campanhas no fim de semana.", validarDiaUtil),
        },
        opcao_mei: { required },
        estado_id: { required },
        cidade_id: { required },
        produto: { required },
      };
    });

    const validDate = (value) => {
      const dataInicio = new Date(value);

      const hoje = new Date();
      hoje.setHours(0, 0, 0, 0);

      if (dataInicio < hoje) {
        return false;
      }

      return true;
    };

    const validarDiaUtil = (value) => {
      const data = new Date(value);

      // getDay() retorna 0 para Domingo e 6 para Sábado
      const diaDaSemana = data.getDay();

      // Log para depuração
      console.log("Data para validar:", data);
      console.log("Dia da semana (0=Dom, 6=Sáb):", diaDaSemana);

      // Se for 0 (Domingo) ou 6 (Sábado), é inválido (fim de semana)
      if (diaDaSemana === 5 || diaDaSemana === 6) {
        console.log("retornando false (fim de semana)");
        return false;
      }

      // Se não for 0 ou 6, é um dia de semana válido
      console.log("retornando true (dia de semana)");
      return true;
    };

    const v$ = useVueLidate(rules, formData);

    return { formData, v$ };
  },

  mounted() {
    this.formData.nome = this.campanha.nome;
    this.formData.data_inicio = this.campanha.data_inicio;
    this.formData.data_fim = this.campanha.data_fim;
    this.formData.opcao_mei = this.campanha.opcao_mei;
    this.formData.estado_id = this.campanha.estado_id;
    this.formData.cidade_id = this.campanha.cidade_id;
    this.formData.data_abertura_inicio = this.campanha.data_abertura_inicio;
    this.formData.data_abertura_fim = this.campanha.data_abertura_fim;
    this.formData.produto = this.campanha.produto;
    this.formData.status = this.campanha.status;
    this.formData.canal = this.campanha.canal;
    this.formData.total_contatos = this.campanha.total_contatos;
    this.formData.mensagem = this.campanha.mensagem;

    console.log("Editar criado");
    console.log(this.campanha);

    this.buscarCidades();
  },

  methods: {
    buscarProspects() {
      this.isLoading = true;

      let data = {
        inicio: this.inicio,
        tamanho: this.qtd_por_pagina,
      };

      console.log("Buscando");
      console.log(data);

      axios
        .post(`/admin/prospects/search`, data)
        .then((response) => {
          this.isLoading = false;

          this.campanhas = response.data.campanhas;
          this.total = response.data.total;

          console.log("Campanhas encontradas");
          console.log(response.data.campanhas);

          this.pagination(response.data);
        })
        .catch((error) => {
          console.log(error);
          this.showErrorMessageWithButtonAndRefresh("Ops..", error.response.data);
          console.log(error.response.data);
        });
    },

    formatar() {
      this.formData.name = this.formData.name.toUpperCase();
    },

    close() {
      let btn = document.getElementById("close_editar");
      btn.click();

      this.closeModal();
    },

    buscarCidades() {
      let data = {
        estado_id: this.formData.estado_id,
      };

      axios
        .post(`/endereco/cidades_por_estado`, data)
        .then((response) => {
          this.isLoading = false;
          this.cidades = response.data.cidades;
        })
        .catch((error) => {
          this.isLoading = false;
          this.showErrorMessageWithButton("Ops..", error);
          console.log(error);
        });
    },

    async update() {
      const result = await this.v$.$validate();

      if (result) {
        this.isLoading = true;

        let data = {
          id: this.campanha.id,
          nome: this.formData.nome,
          data_inicio: this.formData.data_inicio,
          opcao_mei: this.formData.opcao_mei,
          estado_id: this.formData.estado_id,
          cidade_id: this.formData.cidade_id,
          data_abertura_inicio: this.formData.data_abertura_inicio,
          data_abertura_fim: this.formData.data_abertura_fim,
          produto: this.formData.produto,
          status: this.formData.status,
          total_contatos: this.formData.total_contatos,
          canal: this.formData.canal,
          mensagem: this.formData.mensagem,
        };

        console.log("Atualizando");
        console.log(data);

        axios
          .post(`/admin/campanha/update`, data)
          .then((response) => {
            this.isLoading = false;

            let btn = document.getElementById("close_editar");
            btn.click();

            this.showSuccessMessageWithButton("Sucesso", "Campanha editada");
            this.acaoRealizada("editar");
          })
          .catch((error) => {
            this.isLoading = false;
            this.showErrorMessageWithButton("Ops..", error);
            console.log(error);
          });
      }
    },
  },
};
</script>
