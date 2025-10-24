<template>
  <loading v-model:active="isLoading" :can-cancel="false" :is-full-page="true" />

  <h3>Cadastrar tarefa</h3>

  <!-- CADASTRAR-->
  <div class="row">
    <div class="col-md-12">
      <div class="row">
        <div class="col-sm-12">
          <div class="form-group">
            <label for="nome">Nome</label>
            <input class="form-control" id="nome" v-model="formData.nome" />

            <span style="color: red" v-for="error in v$.nome.$errors" :key="error.$uid">
              {{ error.$message }}
            </span>
          </div>
        </div>

        <div class="col-sm-12">
          <div class="form-group">
            <label for="nome">Disponível em todas as etapas?</label>

            <select class="form-control" id="user_type" v-model="formData.todas_as_etapas">
              <option :value="0">Não</option>
              <option :value="1">Sim</option>
            </select>

            <span style="color: red" v-for="error in v$.todas_as_etapas.$errors" :key="error.$uid">
              {{ error.$message }}
            </span>
          </div>
        </div>

        <div class="col-sm-12" v-if="formData.todas_as_etapas == 0">
          <div class="form-group">
            <label for="nome">Etapa</label>

            <select class="form-control" id="user_type" v-model="formData.etapa">
              <option value="Indicação Cadastrada">Indicação Cadastrada</option>
              <option value="Coleta de dados">Coleta de dados</option>
              <option value="Cotação Enviada">Cotação Enviada</option>
              <option value="Em negociaçã">Em negociaçã</option>
              <option value="Negociação encerrada">Negociação encerrada</option>
              <option value="Documentação">Documentação</option>
              <option value="Aguardando Pagamento">Aguardando Pagamento</option>
              <option value="Implantada">Implantada</option>
            </select>

            <span style="color: red" v-for="error in v$.todas_as_etapas.$errors" :key="error.$uid">
              {{ error.$message }}
            </span>
          </div>
        </div>
      </div>
    </div>

    <div class="card-footer" style="width: 100%">
      <button type="submit" class="btn btn-primary" @click="store()">Salvar</button>
      <a id="close_cadastrar" data-dismiss="modal" @click="close" class="btn btn-danger" style="margin-left: 15px">Fechar</a>
    </div>
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

export default {
  mixins: [sweetAlert, Swal],

  props: ["closeModal", "acaoRealizada"],

  components: { Loading },

  data() {
    return {
      isLoading: false,
    };
  },

  setup() {
    const formData = reactive({
      nome: "",
      etapa: "",
      todas_as_etapas: 0,
    });

    const rules = computed(() => {
      return {
        nome: { required },
        etapa: "",
        todas_as_etapas: "",
      };
    });

    const v$ = useVueLidate(rules, formData);

    return { formData, v$ };
  },

  mounted() {},

  methods: {
    formatar() {
      this.formData.name = this.formData.name.toUpperCase();
    },

    close() {
      let btn = document.getElementById("close_cadastrar");
      btn.click();

      this.closeModal();
    },

    async store() {
      const result = await this.v$.$validate();

      if (result) {
        this.isLoading = true;

        if (this.formData.todas_as_etapas == 1) {
          this.formData.etapa = null;
        }

        let data = {
          nome: this.formData.nome,
          etapa: this.formData.etapa,
          todas_as_etapas: this.formData.todas_as_etapas,
        };

        console.log("Cadastrar");
        console.log(data);

        axios
          .post(`/admin/tarefa/store`, data)
          .then((response) => {
            this.isLoading = false;

            if (response.data.success) {
              let btn = document.getElementById("close_cadastrar");
              btn.click();

              this.showSuccessMessageWithButton("Sucesso", "Tarefa cadastrada!");
              this.acaoRealizada("cadastrar");
            } else {
              this.showErrorMessageWithButton("Ops...", response.data.msg);
            }
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
