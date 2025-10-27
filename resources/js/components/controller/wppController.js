export default {
    methods: {

        async enviarClickMassa(tipo_envio, numero, texto, url_midia, token, url, callback) {

            let p1 = new Promise((res) => setTimeout(() => {

                let response = {
                    timeout: true,
                }

                return res(response)
            }, 120000));

            let p2 = new Promise((res) => {

                let data = "";

                if (tipo_envio == "TEXTO") {
                    data = {
                        "body": texto,
                        "number": numero,
                        "externalKey": "0"
                    }
                } else {
                    data = {
                        "body": texto,
                        "number": numero,
                        "mediaUrl": url_midia,
                        "externalKey": "0"
                    }
                }

                console.log("DADOS PARA O ENVIO");
                console.log(data);

                /*
                var config = {
                    method: "POST",
                    url: url,
                    headers: {
                        "Authorization": "Bearer " + token,
                        "Content-Type": "application/json",
                    },
                };
                */

                axios
                    .post(url, data)
                    .then((response) => {

                        let json = {
                            phone: numero,
                            sucess: true
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

        async enviarKualiz(tipo_envio, numero, texto, url_midia, queue_id, url, callback) {

            let p1 = new Promise((res) => setTimeout(() => {

                let response = {
                    timeout: true,
                }

                return res(response)
            }, 120000));

            let p2 = new Promise((res) => {

                let data = {
                    telefone: numero,
                    msg: texto,
                };

                axios
                    .post("/send/kualiz", data)
                    .then((response) => {
                        console.log("RESPOSTA SEND KUALIZ CONTROLLER");
                        console.log(response);

                        let json = {
                            phone: numero,
                            sucess: true
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

        async sendStatus(tipo_status, texto, cor, url, id_api, porta, callback) {

            let p1 = new Promise((res) => setTimeout(() => {

                let response = {
                    timeout: true,
                }

                return res(response)
            }, 60000));

            let p2 = new Promise((res) => {

                let data = {
                    apiId: id_api,
                    font: 2,
                    color: cor,
                    text: texto,
                    url: url,
                };

                console.log("DADOS PARA O ENVIO");
                console.log(data);

                axios
                    .post(process.env.MIX_VUE_APP_ENDPOINT + porta + `/status/` + tipo_status, data)
                    .then((response) => {

                        console.log("Data");
                        console.log(response.data);

                        return res(response.data);
                    })
                    .catch((error) => {

                        console.log("Erro");
                        console.log(error);

                        let json = {
                            timeout: true,
                            phone: false,
                            sucess: false
                        }

                        return res(json);
                    });

            });

            const result = await Promise.race([p1, p2]);

            callback(result);
        },

        async joinGroup(invite_code, id_api, porta, callback) {

            let p1 = new Promise((res) => setTimeout(() => {

                let response = {
                    timeout: true,
                }

                return res(response)
            }, 60000));

            let p2 = new Promise((res) => {

                let data = {
                    invite_code: invite_code,
                    chave_api: id_api,
                };

                console.log("DADOS PARA O ENVIO");
                console.log(data);

                axios
                    .post(process.env.MIX_VUE_APP_ENDPOINT + porta + `/list/join-group`, data)
                    .then((response) => {

                        console.log("Data");
                        console.log(response.data);

                        return res(response.data);
                    })
                    .catch((error) => {

                        console.log("Erro");
                        console.log(error);

                        let json = {
                            timeout: true,
                            phone: false,
                            sucess: false
                        }

                        return res(json);
                    });

            });

            const result = await Promise.race([p1, p2]);

            callback(result);
        },

        async getGroupAdmin(group_id, id_api, porta, callback) {

            let p1 = new Promise((res) => setTimeout(() => {

                let response = {
                    timeout: true,
                }

                return res(response)
            }, 60000));

            let p2 = new Promise((res) => {

                let data = {
                    group_id: group_id,
                    chave_api: id_api,
                };

                console.log("DADOS PARA O ENVIO");
                console.log(data);

                axios
                    .post(process.env.MIX_VUE_APP_ENDPOINT + porta + `/list/groups-admin`, data)
                    .then((response) => {

                        console.log("Data");
                        console.log(response.data);

                        return res(response.data);
                    })
                    .catch((error) => {

                        console.log("Erro");
                        console.log(error);

                        let json = {
                            timeout: true,
                            phone: false,
                            sucess: false
                        }

                        return res(json);
                    });

            });

            const result = await Promise.race([p1, p2]);

            callback(result);
        },

        async enviar(tipo_envio, numero, texto, url_midia, id_api, porta, callback) {

            let p1 = new Promise((res) => setTimeout(() => {

                let response = {
                    timeout: true,
                }

                return res(response)
            }, 60000));

            let p2 = new Promise((res) => {
                let data = {
                    number: numero,
                    text: texto,
                    apiId: id_api,
                    url: url_midia
                };

                console.log("Dados para envio");
                console.log(data);

                let url = process.env.MIX_VUE_APP_ENDPOINT + porta + `/send/` + tipo_envio;

                this.enviarAgora(1, data, url, res);
            });

            const result = await Promise.race([p1, p2]);

            callback(result.timeout, result.phone, result.sucess);
        },

        async enviarAgora(tentativa, data, url, res) {


            console.log("DADOS PARA O ENVIO, tentativa " + tentativa);
            console.log(data);

            axios
                .post(url, data)
                .then((response) => {

                    console.log("Resposta recebida ENVIAR");
                    console.log(response);

                    if (response.data.error && response.data.error.name && response.data.error.name == "CreateChatDuplicateError") {

                        if (tentativa < 2) {
                            console.log("Erro desconhecido... Tentando novamente");

                            this.enviarAgora(tentativa + 1, data, url, res);
                        } else {
                            console.log("Falha na tentativa 2, retornando...");

                            let json = {
                                timeout: true,
                                phone: false,
                                sucess: false
                            }

                            return res(json);
                        }
                    } else {
                        let phone = response.data.success.phone;

                        //ENVIO DE BOTÕES TEM UM RETORNO DIFERENTE
                        if (url.includes("botoes")) {

                            if (response.data.success && response.data.success.result && response.data.success.result.id && response.data.success.result.id.includes("@c.us")) {
                                console.log("ENTROU NO PHONE btn");
                                phone = response.data.success.result.id.split("@c.us")[0].replace("true_", "");
                            }

                            response.data.success.phone = phone;
                        }

                        let json = {
                            phone: phone
                        }

                        if (response.data.success.phone) {
                            json.sucess = true;
                        } else {
                            json.sucess = false;
                        }

                        console.log("Retornando no controller");
                        console.log(json);

                        return res(json);
                    }
                })
                .catch((error) => {
                    console.log("Ops... catch");
                    console.log(error);

                    let json = {
                        timeout: true,
                        phone: false,
                        sucess: false
                    }

                    return res(json);
                });
        },

        async getConnectionStatus(url, porta, callback) {

            console.log("getConnection().wpp");

            this.axios
                .get(process.env.MIX_VUE_APP_ENDPOINT + porta + `/sessionStatus/` + url)
                .then((response) => {
                    callback(response.data.status);
                })
                .catch((error) => {
                    console.log(error);
                });

        },

        async getAllStatus(porta, callback) {

            this.axios
                .get(process.env.MIX_VUE_APP_ENDPOINT + porta + `/get_all_sessions`)
                .then((response) => {
                    callback(response.data);
                })
                .catch((error) => {
                    console.log(error);
                });

        },

    }
};

