<template>
    <div>
        <button data-toggle="modal" data-target="#login-modal" type="button" class="btn btn-info">
            Novo Plano<i class="mdi mdi-plus"></i>
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
                            <h4>Novo Plano</h4>
                            <br />

                            <div class="form-group">
                                <label for="email">Nome</label>
                                <input class="form-control" id="nome" type="text" v-model="nome" />
                            </div>

                            <div class="form-group">
                                <label for="email" style="margin-right:10px">Imagem</label>
                                <img v-if="imagem != ''" alt="Avatar" class="table-avatar"
                                    style="width:70px;opacity: .8" :src="imagem" /> <br />

                                <select class="form-control" id="user_type" v-model="imagem">
                                    <option v-for="imagem in imagens" :key="imagem" :value="imagem">
                                        {{ imagem }}
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="email">Tipo de Plano</label>
                                <select class="form-control" id="user_type" v-model="tipo_plano">
                                    <option value="individual">Plano Individual</option>
                                    <option value="empresarial_pme">Empresarial PME</option>
                                    <option value="adesao">Adesão</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="email">Categoria</label>
                                <select class="form-control" id="user_type" v-model="categoria">
                                    <option value="saude">Saúde</option>
                                    <option value="idoso">Idoso</option>
                                    <option value="odonto">Odonto</option>
                                    <option value="pet">Pet</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="user_type">Quantidade de parcelas</label>
                                <select class="form-control" id="user_type" v-model="qtd_parcelas"
                                    @change="changeParcelas()">
                                    <option v-for="parcela in parcelas" :key="parcela" :value="parcela">
                                        {{ parcela }}
                                    </option>
                                </select>
                            </div>

                            <div class="form-group" v-for="(comissao, index) in comissionamento">
                                <label for="user_type">Comissão {{ index + 1 }}º parcela</label>
                                <input class="form-control" id="senha" type="senha" v-model="comissao.comissao"
                                    autocomplete="senha" />
                            </div>

                            <div class="form-group">
                                <label for="email">Comissão a longo prazo</label>
                                <input class="form-control" id="nome" type="text" v-model="comissao_longo_prazo" />
                            </div>

                            <div class="form-group">
                                <label for="user_type">Tipo comissão</label>
                                <select class="form-control" id="user_type" v-model="tipo_comissao_longo_prazo">
                                    <option value="vitalicio">Vitalício</option>
                                    <option value="limitada">Limitada</option>
                                </select>
                            </div>

                            <div v-if="tipo_comissao_longo_prazo == 'limitada'" class="form-group">
                                <label for="user_type">Parcelas a longo prazo</label>
                                <select class="form-control" id="user_type" v-model="parcela_longo_prazo">
                                    <option v-for="parcela in parcelas" :key="parcela" :value="parcela">
                                        {{ parcela }}
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

export default {

    mixins: [sweetAlert, Swal],

    props: [],

    data() {
        return {
            nome: "",
            categoria: "",
            isLoading: false,
            qtd_parcelas: 0,
            idades: 0,
            parcelas: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29],
            comissao_longo_prazo: "",
            tipo_comissao_longo_prazo: "",
            imagem: "",
            comissionamento: [],
            imagens: [],
            tipo_plano: ""
        };
    },

    mounted() {
        this.push("amil_dental");
        this.push("amil");
        this.push("amil_facil");
        this.push("amil_one");
        this.push("assim_saude");
        this.push("assim_saude_dental");
        this.push("bradesco_dental");
        this.push("cemeru");
        this.push("dental_com");
        this.push("goldental_cross");
        this.push("health_pet");
        this.push("healthmed");    
        this.push("integral_saude_caberj");          
        this.push("klini");
        this.push("leve_saude");
        this.push("med_senior");
        this.push("notedrame");        
        this.push("odonto_prev");
        this.push("quality");
        this.push("samoc");
        this.push("sulamerica_odonto");
        this.push("sulamerica");        
        this.push("unimed_rio");
        this.push("unimed_leste_fluminense");
        this.push("unimed");       
    },

    methods: {

        changeParcelas() {
            this.comissionamento = [];

            for (let i = 0; i < this.qtd_parcelas; i++) {
                this.comissionamento.push({ comissao: 0 });
            }
        },

        push(imagem) {
            this.imagens.push("/assets/images/planos/" + imagem + ".png");
        },

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
                imagem: this.imagem,
                qtd_parcelas: this.qtd_parcelas,
                comissionamento: this.comissionamento,
                comissao_longo_prazo: this.comissao_longo_prazo,
                tipo_comissao_longo_prazo: this.tipo_comissao_longo_prazo,
                categoria: this.categoria,
                tipo_plano: this.tipo_plano
            };

            console.log("CRIANDO");
            console.log(data);

            axios
                .post(`/admin/plano/store`, data)
                .then((response) => {

                    this.showSuccessMessage("Plano cadastrado!");

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

