<template>
  <div class="container-fluid">

    <div class="row">
      <div class="col-sm-12 col-md-4">
        <div class="form-group">
          <label>Selecione um Corretor</label>
          <select class="form-control" v-model="corretor_selecionado" @change="search()">
            <option value=""></option>
            <option v-for="corretor in corretores" :key="corretor" :value="corretor">
              {{ corretor.name }}
            </option>
          </select>
        </div>
      </div>
    </div>

    <div v-if="contatos != ''">
      <corretor-index :contatos="contatos" :planos="planos" :user_id="corretor_selecionado.id"/>
    </div>

  </div>
</template>

<script>
import wppController from "../controller/wppController";
import sweetAlert from "../controller/sweetAlert";
import Swal from "sweetalert2/dist/sweetalert2.js";

export default {

  props: ['planos', 'corretores'],

  mixins: [wppController, sweetAlert, Swal],

  data() {
    return {
      nova_proposta: false,
      contatos: "",
      corretor_selecionado: ""
    };
  },

  mounted() {
    console.log("Create Send Ready");
    console.log(this.propostas);
  },

  methods: {

    search() {

      this.contatos = "";

      let data = {
        id: this.corretor_selecionado.id,
      };

      axios
        .post(`/admin/relatorio/kanban/search`, data)
        .then((response) => {
          this.contatos = response.data.contatos;   
          this.showSuccessMessage("Contatos buscados");
        })
        .catch((error) => {
          this.showErrorMessageWithButton("Ops..", error);
          console.log(error);
        });
    },

    novaProposta() {
      this.nova_proposta = true;
    },

    listarPropostas() {
      this.nova_proposta = false;
    }
  },
};
</script>