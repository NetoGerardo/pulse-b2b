<template>
  <div class="container-fluid">

    <button style="border-radius: 0px 5px 0px;" class="btn btn-info" v-if="!nova_proposta" @click="novaProposta">
      Nova Proposta<i class="mdi mdi-plus"></i>
    </button>

    <button style="border-radius: 0px 5px 0px;" class="btn btn-info" v-if="nova_proposta" @click="listarPropostas">
      Voltar<i class="mdi mdi-plus"></i>
    </button>

    <div v-if="nova_proposta">
      <nova-proposta :planos="planos" />
    </div>

    <div v-if="!nova_proposta">
      <listar-propostas :propostas="propostas" />
    </div>

  </div>
</template>

<script>
import wppController from "../controller/wppController";
import sweetAlert from "../controller/sweetAlert";
import Swal from "sweetalert2/dist/sweetalert2.js";

export default {

  props: ['planos', 'propostas'],

  mixins: [wppController, sweetAlert, Swal],

  data() {
    return {
      nova_proposta: false,
    };
  },

  mounted() {
    console.log("Create Send Ready");
    console.log(this.propostas);
  },

  methods: {
    novaProposta() {
      this.nova_proposta = true;
    },

    listarPropostas() {
      this.nova_proposta = false;
    }
  },
};
</script>