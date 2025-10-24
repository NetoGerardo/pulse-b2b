<template>
  <div>
    <loading v-model:active="isLoading" :can-cancel="false" :is-full-page="true" />
    <button data-toggle="modal" data-target="#login-modal" type="button" class="btn btn-info">
      Novo Turno
      <i class="nav-icon far fa-plus-square"></i>
    </button>

    <div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <form @submit.prevent>
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-body">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4>Novo Turno</h4>
              <br />
              <div class="form-group">
                <label for="email">Nome</label>
                <input class="form-control" id="email" type="text" v-model="nome_turno" />
              </div>

              <div class="form-group">
                <label for="user_type">Hora Início</label>
                <select class="form-control" id="user_type" v-model="hora_inicio">
                  <option value="0">00:00</option>
                  <option value="1">01:00</option>
                  <option value="2">02:00</option>
                  <option value="3">03:00</option>
                  <option value="4">04:00</option>
                  <option value="5">05:00</option>
                  <option value="6">06:00</option>
                  <option value="7">07:00</option>
                  <option value="8">08:00</option>
                  <option value="9">09:00</option>
                  <option value="10">10:00</option>
                  <option value="11">11:00</option>
                  <option value="12">12:00</option>
                  <option value="13">13:00</option>
                  <option value="14">14:00</option>
                  <option value="15">15:00</option>
                  <option value="16">16:00</option>
                  <option value="17">17:00</option>
                  <option value="18">18:00</option>
                  <option value="19">19:00</option>
                  <option value="20">20:00</option>
                  <option value="21">21:00</option>
                  <option value="22">22:00</option>
                  <option value="23">23:00</option>
                </select>
              </div>

              <div class="form-group">
                <label for="user_type">Hora Fim</label>
                <select class="form-control" id="user_type" v-model="hora_fim">
                  <option value="0">00:00</option>
                  <option value="1">01:00</option>
                  <option value="2">02:00</option>
                  <option value="3">03:00</option>
                  <option value="4">04:00</option>
                  <option value="5">05:00</option>
                  <option value="6">06:00</option>
                  <option value="7">07:00</option>
                  <option value="8">08:00</option>
                  <option value="9">09:00</option>
                  <option value="10">10:00</option>
                  <option value="11">11:00</option>
                  <option value="12">12:00</option>
                  <option value="13">13:00</option>
                  <option value="14">14:00</option>
                  <option value="15">15:00</option>
                  <option value="16">16:00</option>
                  <option value="17">17:00</option>
                  <option value="18">18:00</option>
                  <option value="19">19:00</option>
                  <option value="20">20:00</option>
                  <option value="21">21:00</option>
                  <option value="22">22:00</option>
                  <option value="23">23:00</option>
                </select>
              </div>

              <button @click="validateFields()" class="btn btn-primary btn-flat">
                Cadastrar
                <i class="fa fa-search"></i>
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import wppController from "../controller/wppController";
import sweetAlert from "../controller/sweetAlert";
import Swal from "sweetalert2/dist/sweetalert2.js";

import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";

export default {
  mixins: [wppController, sweetAlert, Swal],

  props: ["supervisores", "gerentes"],

  components: { Loading },

  data() {
    return {
      hora_inicio: "",
      hora_fim: "",
      nome_turno: ""
    };
  },

  mounted() {
    console.log("GERENTES");
    console.log(this.gerentes);
  },

  methods: {
    validateFields() {
      if (this.hora_inicio == "") {
        this.showErrorMessageWithButton("Ops...", "Preencha a hora de início do turno!");
      } else if (this.hora_fim == "") {
        this.showErrorMessageWithButton("Ops...", "Preencha a hora de fim do turno!");
      } else {
        this.create();
      }
    },

    create() {
      this.isLoading = true;

      let data = {
        hora_inicio: this.hora_inicio,
        hora_fim: this.hora_fim,
        nome: this.nome_turno,
      };

      console.log("CRIANDO");
      console.log(data);

      axios
        .post(`/admin/turnos/store`, data)
        .then((response) => {
          this.showSuccessMessage("Turno criado!");
          window.location.reload();
        })
        .catch((error) => {
          this.showErrorMessageWithButton("Ops..", error.response.data);
          console.log(error.response.data);
        });
    },

    validateEmail() {
      if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.email)) {
        return true;
      } else {
        return false;
      }
    },

    generateKey() {
      return Math.random().toString(36).slice(2);
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
