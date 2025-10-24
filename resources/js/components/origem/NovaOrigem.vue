<template>
    <div>
        <load v-model:active="isLoading" :can-cancel="false" :is-full-page="true" />
        <button data-toggle="modal" data-target="#login-modal" type="button" class="btn btn-info">
            Nova Origem<i class="mdi mdi-plus"></i>
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
                            <h4>Nova Origem</h4>
                            <br />

                            <div class="form-group">
                                <label for="email">Nome</label>
                                <input class="form-control" id="nome" type="text" v-model="nome" />
                            </div>

                            <div class="form-group">
                                <label for="user_type">Categoria</label>
                                <select class="form-control" id="user_type" v-model="categoria">
                                    <option value="Bronze">Bronze</option>
                                    <option value="Prata">Prata</option>
                                    <option value="Ouro">Ouro</option>
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
import Load from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";

export default {

    mixins: [sweetAlert, Swal],

    components: [Load],

    props: ['categorias', 'projetos'],

    data() {
        return {
            nome: "",
            categoria: "",
            isLoading: false,
        };
    },

    mounted() { },

    methods: {
        validateFields() {
            if (!this.nome) {
                this.showErrorMessageWithButton("Ops...", "O campo nome é obrigatório!");
            } else if (!this.categoria) {
                this.showErrorMessageWithButton("Ops...", "Selecione uma categoria");
            } else {
                this.create();
            }
        },

        formatSelectedDate(date) {
            return moment(date).format("yyyy-MM-DD");
        },

        formatSelectedDate(date) {
            return moment(date).format("yyyy-MM-DD");
        },

        create() {
            this.isLoading = true;

            let data = {
                nome: this.nome,
                categoria: this.categoria,
            };

            console.log("CRIANDO");
            console.log(data);

            axios
                .post(`/admin/origem/store`, data)
                .then((response) => {
                    console.log("Usuário criado!");
                    console.log(response);

                    this.showSuccessMessage("Produto cadastrado!");

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

