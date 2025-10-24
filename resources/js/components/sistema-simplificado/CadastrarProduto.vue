<template>
    <div>
        <button data-toggle="modal" data-target="#login-modal" type="button" class="btn btn-info">
            Cadastrar<i class="mdi mdi-plus"></i>
        </button>

        <div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <form @submit.prevent>
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                &times;
                            </button>
                            <h4>Nova Operadora</h4>
                            <br />

                            <div class="form-group">
                                <label for="email">Nome</label>
                                <input class="form-control" id="nome" type="text" v-model="nome" />
                            </div>

                            <div class="form-group">
                                <label for="email">Operadora</label>
                                <select class="form-control" id="operadora" v-model="operadora">
                                    <option v-for="operadora in operadoras" :key="operadora.id" :value="operadora.id">
                                        {{ operadora.nome }}
                                    </option>
                                </select>
                            </div>

                            <button @click="validateFields()" class="btn btn-primary btn-flat">
                                Cadastrar <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
  
<script>
import sweetAlert from "../controller/sweetAlert";
import Swal from "sweetalert2/dist/sweetalert2.js";
import moment from "moment";
import "vue-loading-overlay/dist/vue-loading.css";
import Loading from "vue-loading-overlay";

export default {

    mixins: [sweetAlert, Swal],

    props: ['operadoras'],

    components: [Loading],

    data() {
        return {
            nome: "",
            isLoading: false,
            operadora: ""
        };
    },

    mounted() { },

    methods: {

        validateFields() {
            if (!this.nome) {
                this.showErrorMessageWithButton("Ops...", "O campo nome é obrigatório!");
            } else if(!this.operadora || this.operadora == ""){
                this.showErrorMessageWithButton("Ops...", "Selecione uma operadora!");
            } else {
                this.create();
            }
        },

        create() {
            this.isLoading = true;

            let data = {
                nome: this.nome,
                operadora_id: this.operadora,
            };

            console.log("CRIANDO");
            console.log(data);

            axios
                .post(`/admin/produto/store`, data)
                .then((response) => {

                    this.showSuccessMessage("Produto cadastrada!");

                    window.location.reload();
                })
                .catch((error) => {
                    this.showErrorMessageWithButton("Ops..", error);
                    console.log(error);
                });

        },
    },
};
</script>

