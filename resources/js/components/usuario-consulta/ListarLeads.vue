<template>
    <div class="table-responsive">
        <table id="zero_config" class="table table-striped table-bordered no-wrap">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Data</th>
                    <th>Origem</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="lead in leads" :key="lead" :value="lead">
                    <td>{{ lead.nome }}</td>

                    <td data-label="Data">
                        {{ formatDate(lead.created_at) }}
                    </td>

                    <td>{{ lead.origem_lead }}</td>

                </tr>
            </tbody>
        </table>
    </div>
</template>
  
<script>
import sweetAlert from "../controller/sweetAlert";
import moment from "moment";

export default {
    mixins: [sweetAlert],

    props: ['leads'],

    data() {
        return {
            corretor_selecionado: "",
            lead_selecionado: ""
        };
    },

    mounted() {
        console.log(this.corretores);
    },

    methods: {
        formatDate(date) {
            return moment(date).format("DD/MM/YYYY HH:mm:ss");
        },
    },
};
</script>
  