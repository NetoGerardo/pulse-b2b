<template>
    <loading v-model:active="isLoading" :can-cancel="false" :is-full-page="true" />

    <nova-lista/>
    <div class="card">
        <div class="card-body p-0">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 25%">Nome</th>
                        <th style="width: 25%">Data da criação</th>
                        <th style="width: 25%">Disponiveis</th>
                        <th style="width: 25%">Total</th>
                        <th style="width: 12%">Gerenciar</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="lista in listas" :key="lista">
                        <td data-label="Nome">{{ lista.nome }}</td>
                        <td data-label="Data">
                            {{ formatDate(lista.created_at) }}
                        </td>

                        <td data-label="Disponiveis">{{ lista.numeros_disponiveis }}</td>
                        <td data-label="Total">{{ lista.total_numeros }}</td>
                        <td>
                            <button data-toggle="modal" data-target="#editar-modal" @click="selecionar(lista)" type="button"
                                class="btn btn-primary btn-sm">
                                Gerenciar
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div v-if="lista_selecionada != ''" class="modal fade" id="editar-modal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel" aria-hidden="true">
        <form @submit.prevent>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            &times;
                        </button>
                        <h4>Editar Lista</h4>
                        <br />
                        <div class="form-group">
                            <label for="email">Nome</label>
                            <input class="form-control" id="email" type="text" v-model="lista_selecionada.lista.nome" />
                        </div>

                        <div class="form-group">
                            <label>Add contatos</label>
                            <div class="input-group mb-3">
                                <textarea class="form-control" id="senha" type="senha" v-model="novos_contatos"
                                    autocomplete="senha" rows="5" cols="40" />
                                <div class="input-group-prepend">
                                    <button type="button" class="btn btn-success" @click="addTelefone()">Add
                                        <i style="margin-left: 3px;" class="nav-icon far fa-plus-square"></i></button>
                                </div>
                            </div>
                        </div>

                        <br /><br />
                        <label v-if="lista_selecionada.telefones_formatados">Total: {{
                            lista_selecionada.telefones_formatados.length
                        }}</label>
                        <table id="zero_config" class="table table-striped table-bordered no-wrap">
                            <thead>
                                <tr>
                                    <th>Telefone</th>
                                    <th style="width: 10%;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(telefone, index) in lista_selecionada.contatos" :key="telefone"
                                    :value="telefone">

                                    <td>{{ telefone.telefone }}</td>

                                    <td>
                                        <button @click="confirmarDelete(telefone, index)" type="button"
                                            class="btn btn-danger">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <button data-toggle="modal" data-target="#editar-modal" class="btn btn-danger">
                            Cancelar
                        </button>
                        <button @click="validateFields()" class="btn btn-primary">
                            Salvar
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>
  
<script>
import sweetAlert from "../controller/sweetAlert";
import Swal from "sweetalert2/dist/sweetalert2.js";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
import moment from "moment";

export default {

    mixins: [sweetAlert, Swal],

    components: { Loading },

    props: ['listas'],

    data() {
        return {
            nome: "",
            categoria: "",
            isLoading: false,
            arquivoLista: "",
            origem_selecionada: "",
            corretor_selecionado: "",
            possui_cnpj: "",
            tipo_lista: "",
            lista_selecionada: "",
            novo_telefone: ""
        };
    },

    mounted() {
        console.log("Teste");
        console.log(this.listas);
    },

    methods: {

        selecionar(lista) {
            this.lista_selecionada = lista;

            console.log("Selecionada");
            console.log(this.lista_selecionada);
        },

        formatDate(date) {
            return moment(date).format("DD/MM/YYYY HH:mm:ss");
        },

        importar() {

            this.isLoading = true;

            const reader = new FileReader();

            reader.onload = (e) => {
                /* Parse data */
                const bstr = e.target.result;
                const wb = XLSX.read(bstr, { type: 'binary' });
                /* Get first worksheet */
                const wsname = wb.SheetNames[0];
                const ws = wb.Sheets[wsname];
                /* Convert array of arrays */
                const data = XLSX.utils.sheet_to_json(ws, { header: 1 });

                data.shift();

                //LIMPANDO TELEFONES

                let new_data = [];
                let telefones_adicionados = [];

                for (let i = 0; i < data.length; i++) {

                    if (this.tipo_lista == 'Nome/Telefone/CNPJ') {

                        let telefone = this.limparTelefone(data[i][1]);

                        if (telefone && !telefones_adicionados.includes(telefone) && telefone.length >= 8) {

                            let novo_contato = {};

                            novo_contato.telefone = telefone;
                            novo_contato.nome = data[i][0].trim();

                            if (this.possui_cnpj != "") {
                                novo_contato.possui_cnpj = this.possui_cnpj;
                            } else if (!data[i][2]) {
                                novo_contato.possui_cnpj = 0;
                            } else {
                                novo_contato.possui_cnpj = data[i][2];
                            }

                            new_data.push(novo_contato);
                            telefones_adicionados.push(telefone);
                        }
                    }

                    if (this.tipo_lista == 'Martins') {

                        if (!telefones_adicionados.includes(data[i][2])) {
                            let add = false;

                            let novo_contato = {};

                            //NOME
                            novo_contato.nome = data[i][0];

                            //TELEFONE
                            novo_contato.telefone = this.limparTelefone(data[i][2]);

                            //OCUPACAO
                            novo_contato.ocupacao = data[i][7];

                            //BAIRRO
                            novo_contato.bairro = data[i][8];

                            //PLANO
                            novo_contato.possui_plano = this.converterBoolean(data[i][9]);

                            //CNPJ
                            novo_contato.possui_cnpj = this.converterBoolean(data[i][10]);

                            //VIDAS
                            novo_contato.qtd_vidas = data[i][11];

                            //IDADES
                            novo_contato.idades = data[i][12];

                            //COMPLEMENTO
                            novo_contato.complemento = data[i][13];

                            if (novo_contato.nome) {
                                new_data.push(novo_contato);
                                telefones_adicionados.push(data[i][2]);
                            }
                        }

                    }
                }

                console.log(new_data);

                this.store(new_data);
            }

            reader.readAsBinaryString(this.arquivoLista);
        },

        converterBoolean(opcao) {
            if (opcao == "Não") {
                return 0;
            } else {
                return 1;
            }
        },

        selecionarLista() {
            let file = this.$refs.file.files[0];

            this.arquivoLista = file;
        },

        limparTelefone(telefone) {
            console.log(telefone);
            if (telefone) {
                return telefone.toString(10).replace(/([^\d])+/gim, '');
            } else {
                return telefone
            }
        },

        store(contatos) {
            this.isLoading = true;

            let data = {
                contatos: contatos,
                origem_id: this.origem_selecionada.id,
                origem: this.origem_selecionada.nome,
                user_id: this.corretor_selecionado.id
            };

            console.log("CRIANDO");
            console.log(data);

            axios
                .post(`/admin/listas/store`, data)
                .then((response) => {
                    console.log("Usuário criado!");
                    console.log(response);

                    this.showSuccessMessage("Lista importada!");

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

