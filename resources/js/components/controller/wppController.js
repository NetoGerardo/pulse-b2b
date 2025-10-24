export default {
    methods: {

        async enviar(tipo_envio, numero, texto, url, id_api, callback) {

            let p1 = new Promise((res) => setTimeout(() => {

                let response = {
                    timeout: true,
                }

                return res(response)
            }, 30000));

            let p2 = new Promise((res) => {

                let data = {
                    number: numero,
                    text: texto,
                    apiId: id_api,
                    url: url
                };

                console.log("DADOS PARA O ENVIO");
                console.log(data);

                axios
                    .post(process.env.MIX_VUE_APP_ENDPOINT + `/send/` + tipo_envio, data)
                    .then((response) => {

                        let json = {
                            phone: response.data.success.phone
                        }

                        if (response.data.success.phone) {
                            json.sucess = true;
                        } else {
                            json.sucess = false;
                        }

                        return res(json);

                    })
                    .catch((error) => {
                        console.log(error);
                    });

            });

            const result = await Promise.race([p1, p2]);

            callback(result.timeout, result.phone, result.sucess);
        },

        async getConnectionStatus(url, callback) {

            console.log("getConnection().wpp");

            this.axios
                .get(process.env.MIX_VUE_APP_ENDPOINT + `/sessionStatus/` + url)
                .then((response) => {
                    callback(response.data.status);
                })
                .catch((error) => {
                    console.log(error);
                });

        },

    }
};

