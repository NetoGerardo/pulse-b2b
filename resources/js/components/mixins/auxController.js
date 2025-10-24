export default {
    methods: {

        converterNomePlano(tipo_plano) {
            if (tipo_plano == 'individual') {
                return "Individual"
            } else if (tipo_plano == 'empresarial_pme') {
                return "Empresarial PME"
            } else if (tipo_plano == 'adesao') {
                return "Adesão"
            }
        },

        converterCategoria(categoria) {
            if (categoria == 'saude') {
                return "Saúde"
            } else if (categoria == 'idoso') {
                return "Idoso"
            } else if (categoria == 'odonto') {
                return "Odonto"
            }
        },

    }
};

