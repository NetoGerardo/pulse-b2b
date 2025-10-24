<template>
    <loading v-model:active="isLoading" :can-cancel="false" :is-full-page="true" />
    <div>
        <div class="form-group">
            <label>Arquivo</label>
            <input class="form-control" type="file" @change="selecionarLista" ref="file" />
        </div>

        <div class="form-group">
            <label>Lista</label>
            <select class=" form-control" v-model="lista_selecionada">
                <option v-for="lista in listas" :key="lista" :value="lista">
                    {{ lista.nome }}
                </option>
            </select>
        </div>

        <div class="form-group">
            <label>Origem</label>
            <select class="form-control" v-model="origem_selecionada">
                <option value=""></option>
                <option v-for="origem in origens" :key="origem" :value="origem">
                    {{ origem.nome }}
                </option>
            </select>
        </div>

        <div class="form-group">
            <label>Tipo de arquivo</label>
            <select class=" form-control" v-model="tipo_lista">
                <option value="Nome/Telefone/CNPJ">Nome/Telefone/CNPJ</option>
                <option value="Martins">Martins</option>
            </select>
        </div>

        <div class="form-group" v-if="tipo_lista == 'Nome/Telefone/CNPJ'">
            <label>Possui CNPJ?</label>
            <select class="form-control" v-model="possui_cnpj">
                <option value="1">Sim</option>
                <option value="0">Não</option>
            </select>
        </div>

        <!--
        <div class="form-group">
            <label>Corretor</label>
            <select class=" form-control" v-model="corretor_selecionado">
                <option value=""></option>
                <option v-for="corretor in corretores" :key="corretor" :value="corretor">
                    {{ corretor.name }}
                </option>
            </select>
        </div>
        -->

        <button @click="importar()" class="btn btn-sm btn-info">
            <i class="fa fa-plus"></i>Importar
        </button>

    </div>
</template>
  
<script>
import sweetAlert from "../controller/sweetAlert";
import Swal from "sweetalert2/dist/sweetalert2.js";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";

export default {

    mixins: [sweetAlert, Swal],

    components: { Loading },

    props: ['origens', 'listas'],

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
            lista_selecinada: ""
        };
    },

    mounted() { },

    methods: {

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
                lista_id: this.lista_selecionada.id,
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

