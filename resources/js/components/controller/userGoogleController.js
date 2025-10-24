export default {
    methods: {

        validarTodos(numeros, id_container, callback) {

            let numerosValidos = [];

            let data = {
                numeros: numeros,
                apiId: id_container
            };

            console.log("Validando agora");
            console.log(data);

            axios
                .post(
                    process.env.MIX_VUE_APP_ENDPOINT_VALIDADOR + `/validate/all`,
                    data
                )
                .then((response) => {

                    console.log("Resposta da validação");
                    console.log(response.data);

                    let phones = response.data.phones;

                    if (phones) {
                        for (let i = 0; i < phones.length; i++) {
                            if (phones[i].phone && phones[i].success) {
                                numerosValidos.push({
                                    phone: phones[i].phone.split("@")[0],
                                });
                            } else if (phones[i].timeout) {
                                numerosValidos.push({
                                    timeout: true,
                                });
                            }
                        }
                        callback(numerosValidos)
                    } else {
                        callback(undefined);
                    }

                })
                .catch((error) => {
                    callback(undefined);
                });
        },

        getConnectionStatus(url, callback) {

            console.log("getConnection().wpp");

            axios
                .get(process.env.MIX_VUE_APP_ENDPOINT_VALIDADOR + `/sessionStatus/` + url)
                .then((response) => {
                    callback(response.data.status);
                })
                .catch((error) => {
                    console.log(error);
                });

        },

    }
};

