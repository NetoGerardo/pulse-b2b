<template>
  <loading v-model:active="isLoading" :can-cancel="false" :is-full-page="true" />

  <h4>Editar apelido bloqueado</h4>

  <!-- EDITAR-->
  <div class="row">
    <div class="col-md-12">
      <div class="row">
        <div class="col-sm-12">
          <div class="form-group">
            <label for="nome">Nome</label>
            <input disabled class="form-control" id="nome" v-model="formData.nome" />

            <span style="color: red" v-for="error in v$.nome.$errors" :key="error.$uid">
              {{ error.$message }}
            </span>
          </div>
        </div>

        <div class="col-sm-12">
          <div class="form-group">
            <label for="nome">Grupo</label>
            <input class="form-control" id="nome" v-model="formData.grupo" />
          </div>
        </div>

        <div class="col-sm-12">
          <div class="form-group">
            <label for="nome">Tipo</label>
            <input class="form-control" id="nome" v-model="formData.tipo" />
          </div>
        </div>

        <div class="col-sm-12">
          <div class="form-group">
            <label for="nome">Lista</label>
            <input class="form-control" id="nome" v-model="formData.lista" />
          </div>
        </div>

        <div class="col-sm-12">
          <div class="form-group">
            <label for="nome">Valor</label>
            <input class="form-control" id="nome" v-model="formData.valor" />

            <span style="color: red" v-for="error in v$.valor.$errors" :key="error.$uid">
              {{ error.$message }}
            </span>
          </div>
        </div>
      </div>
    </div>

    <div class="card-footer" style="width: 100%">
      <button type="submit" class="btn btn-primary" @click="update()">Salvar</button>
      <a id="close_editar" data-dismiss="modal" @click="close" class="btn btn-danger" style="margin-left: 15px">Voltar</a>
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

  props: ["parametro", "closeModal", "acaoRealizada"],

  components: { Loading },

  data() {
    return {
      isLoading: false,
    };
  },

  setup() {
    const formData = reactive({
      nome: "",
      valor: "",
      tipo: "",
      lista: "",
      grupo: "",
    });

    const rules = computed(() => {
      return {
        nome: { required },
        valor: { required },
      };
    });

    const v$ = useVueLidate(rules, formData);

    return { formData, v$ };
  },

  mounted() {
    this.formData.nome = this.parametro.nome;
    this.formData.grupo = this.parametro.grupo;
    this.formData.valor = this.parametro.valor;
    this.formData.lista = this.parametro.lista;
    this.formData.tipo = this.parametro.tipo;

    console.log("Editar criado");
    console.log(this.parametro);
  },

  methods: {
    formatar() {
      this.formData.name = this.formData.name.toUpperCase();
    },

    close() {
      let btn = document.getElementById("close_editar");
      btn.click();

      this.closeModal();
    },

    async update() {
      const result = await this.v$.$validate();

      if (result) {
        this.isLoading = true;

        let data = {
          id: this.parametro.id,
          valor: this.formData.valor,
          grupo: this.formData.grupo,
          lista: this.formData.lista,
          tipo: this.formData.tipo,
        };

        console.log("Atualizando");
        console.log(data);

        axios
          .post(`/admin/parametro/update`, data)
          .then((response) => {
            this.isLoading = false;

            let btn = document.getElementById("close_editar");
            btn.click();

            this.showSuccessMessageWithButton("Sucesso", "Parâmetro editado");
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
