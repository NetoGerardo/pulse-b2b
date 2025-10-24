<template>
    <div>
        <form enctype="multipart/form-data">

            <input type="file" @change="selectFile" ref="file" />


            <button type="button" class="btn btn-success" @click="sendFile">
                Send file<i class="mdi mdi-plus"></i>
            </button>


        </form>
        <br /> <br /> <br />
    </div>
</template>
  
<script>
import sweetAlert from "../controller/sweetAlert";
import Swal from "sweetalert2/dist/sweetalert2.js";
import "vue-loading-overlay/dist/vue-loading.css";

export default {

    mixins: [sweetAlert, Swal],

    props: [],

    data() {
        return {
            file: "",
            loading: false,
        };
    },

    mounted() {

    },

    methods: {

        selectFile() {
            this.file = this.$refs.file.files[0];

            const allowedTypes = ["image/jpeg", "image/png", "image/gif", "application/pdf"]

            if (allowedTypes.includes(file.type)) {
                this.file = file;
            } else{
                this.showErrorMessageWithButton("Ops...", "Apenas imagens e PDF's são permitidos!");
            }
        },

        sendFile() {
            const formData = new FormData();
            formData.append('id', this.generateKey());
            formData.append('file', this.file);

            axios
                .post(process.env.MIX_VUE_APP_ENDPOINT + `/upload`, formData)
                .then((response) => {
                    this.showSuccessMessage("Proposta criada!");
                })
                .catch((error) => {
                    this.showErrorMessageWithButton("Ops..", error.response.data.error);
                    console.log(error);
                });
        },

        generateKey() {
            return Math.random().toString(36).slice(2);
        },
    },
};
</script>

