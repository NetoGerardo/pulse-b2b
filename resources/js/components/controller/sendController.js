export default {
    methods: {

        salvarEnvioDB(nome, id_container, tipo_envio, mensagem, url, numeros, id_funil, callback) {

            let data = {
                nome: nome,
                id_container: id_container,
                tipo_envio: tipo_envio,
                mensagem: mensagem,
                url: url,
                id_funil: id_funil
            };

            axios
                .post(process.env.MIX_VUE_APP_ENDPOINT + `/database/send/create-send`, data)
                .then((response) => {
                    console.log("Retorno do envio");

                    if (response.data.id_envio) {
                        let id_envio = response.data.id_envio;

                        this.salvarTodosOsNumerosDBTESTE(numeros, id_envio, callback);
                    } else {
                        console.log("ENVIO NÃO SALVO");
                        console.log(response.data);
                    }
                })
                .catch((error) => {
                    console.log(error);
                });
        },

        salvarTodosOsNumerosDB(numbers, id_envio, callback) {

            for (let i = 0; i < numbers.length; i++) {

                let array = numbers[i].phone.split("@");

                let data = {
                    numero: array[0],
                    id_envio: id_envio,
                };

                axios
                    .post(process.env.MIX_VUE_APP_ENDPOINT + `/database/send/save_number`, data)
                    .then((response) => {

                        if (response.data) {
                            console.log("Número salvo no banco");
                        }

                        if (i + 1 == numbers.length) {
                            callback(id_envio);
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });


            }
        },

        salvarTodosOsNumerosDBTESTE(numbers, id_envio, callback) {

            let arrayNumeros = []

            for (let i = 0; i < numbers.length; i++) {
                console.log("Numero");
                console.log(numbers[i]);
                let array = numbers[i].phone.split("@");
                arrayNumeros.push(array[0]);
            }

            let data = {
                numeros: arrayNumeros,
                id_envio: id_envio,
            };

            console.log("SALVANDO LISTA AGORA");
            console.log(data);

            axios
                .post(process.env.MIX_VUE_APP_ENDPOINT + `/database/send/save_list_of_numbers`, data)
                .then((response) => {

                    if (response.data) {
                        console.log("Números salvos no banco");
                    }

                    callback(id_envio);

                })
                .catch((error) => {
                    console.log(error);
                });
        },

        atualizarStatusNumeroDB(envio_id, numero, status, numero_validado) {

            let data = {
                numero: numero,
                envio_id: envio_id,
                status_envio: status,
                numero_validado: numero_validado
            };

            console.log("ATUALIZANDO STATUS");
            console.log(data);

            axios
                .post(`/numeros_envios/update`, data)
                .then((response) => {

                    if (response.data) {
                        console.log("Número atualizado");
                    }
                })
                .catch((error) => {
                    console.log(error);
                });

        },

        criarMensagemDB(numero, mensagem, envio_id, container_id) {

            let data = {
                envio_id: envio_id,
                numero: numero,
                mensagem: mensagem,
                container_id: container_id,
            };

            console.log("CRIANDO MENSAGEM AGORA");
            console.log(data);

            axios
                .post(`/send/store_message`, data)
                .then((response) => {
                    console.log("Mensagem registrada no banco");
                    console.log(response);
                })
                .catch((error) => {
                    console.log(error);
                });
        },

        criarMensagemDBStatus(numero, mensagem, id_envio, id_container, status_da_resposta) {

            let data = {
                id_envio: id_envio,
                numero: numero,
                mensagem: mensagem,
                quantidade: 1,
                id_container: id_container,
                status_da_resposta: status_da_resposta
            };

            axios
                .post(
                    process.env.MIX_VUE_APP_ENDPOINT + `/database/send/create-message`,
                    data
                )
                .then((response) => {
                    console.log("Mensagem registrada no banco");
                    console.log(response);
                })
                .catch((error) => {
                    console.log(error);
                });
        },

        validarNumeros(numeros, id_container, callback) {

            let numerosValidos = [];

            for (let i = 0; i < numeros.length; i++) {

                let array = numeros[i].phone.split("@");

                let data = {
                    numero: array[0],
                    apiId: id_container
                };

                console.log("Validando agora");
                console.log(data);

                axios
                    .post(
                        process.env.MIX_VUE_APP_ENDPOINT + `/validate`,
                        data
                    )
                    .then((response) => {

                        console.log("Resposta da validação");
                        console.log(response.data);

                        if (response.data.phone) {

                            numerosValidos.push({
                                phone: response.data.phone,
                            });

                        }

                        if (i + 1 >= numeros.length) {

                            console.log("CHAMANDO CALLBACK");
                            console.log(numerosValidos);

                            callback(numerosValidos);
                        }

                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }

        },

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
                    process.env.MIX_VUE_APP_ENDPOINT + `/validate/all`,
                    data
                )
                .then((response) => {

                    console.log("Resposta da validação");
                    console.log(response.data);

                    let phones = response.data.phones;

                    if (phones) {
                        for (let i = 0; i < phones.length; i++) {
                            if (phones[i].phone) {
                                numerosValidos.push({
                                    phone: phones[i].phone.split("@")[0],
                                });
                            }
                        }
                    }

                    callback(undefined);

                })
                .catch((error) => {
                    console.log(error);
                });


        },

        async validarNumero(numero, id_container) {

            let p1 = new Promise((res) => setTimeout(() => {

                let response = {
                    phone: false,
                    timeout: true,
                }

                return res(response)
            }, 60000));

            let p2 = new Promise((res) => {

                let data = {
                    numero: numero + "@c.us",
                    apiId: id_container
                };

                console.log("Validando agora");
                console.log(data);

                axios
                    .post(
                        process.env.MIX_VUE_APP_ENDPOINT + `/validate`,
                        data
                    )
                    .then((response) => {

                        console.log("Resposta da validação");
                        console.log(response.data);

                        if (response.data.phone) {
                            let json = {
                                phone: response.data.phone + "@c.us",
                                timeout: false
                            }

                            return res(json);
                        } else {
                            let json = {
                                phone: false,
                                timeout: response.data.timeout,
                            }

                            return res(json);
                        }

                    })
                    .catch((error) => {
                        let json = {
                            phone: false,
                            timeout: true,
                            error: error
                        }

                        return res(json);
                    });

            })

            return Promise.race([p1, p2]);
        },

        finalizarEnvio(envio_id) {

            let data = {
                envio_id: envio_id,
            };

            console.log("ATUALIZANDO STATUS DO ENVIO");
            console.log(data);

            axios
                .post(`/send/finish`, data)
                .then((response) => {

                    if (response.data) {
                        console.log("Status do envio atualizado!");
                    }
                })
                .catch((error) => {
                    console.log(error);
                });
        },

        atualizarTotalEnviados(envio_id) {

            let data = {
                envio_id: envio_id,
                contatos: 1
            }

            console.log("Atualizando total enviados");
            console.log(data);

            axios
                .post(`/send/update/total`, data)
                .then((response) => {
                    console.log("Total enviado atualizado");
                    console.log(response.data);
                })
                .catch((error) => {
                    console.log(error);
                });
        },

        atualizarTotalInvalidos(id_envio) {

            let data = {
                id_envio: id_envio,
                contatos: 1
            }

            console.log("Atualizando total enviados");
            console.log(data);

            axios
                .post(
                    process.env.MIX_VUE_APP_ENDPOINT + `/database/send/update_invalids_count`,
                    data
                )
                .then((response) => {
                    console.log("Total enviado atualizado");
                    console.log(response.data);
                })
                .catch((error) => {
                    console.log(error);
                });
        },

        atualizarTotalContatosNoEnvio(id_envio, total_contatos) {

            let data = {
                id_envio: id_envio,
                total_contatos: total_contatos
            }

            console.log("Atualizando total de contatos no envio");
            console.log(data);

            axios
                .post(
                    process.env.MIX_VUE_APP_ENDPOINT + `/database/send/update_send`,
                    data
                )
                .then((response) => {
                    console.log("Total contatos no envio atualizado");
                    console.log(response.data);
                })
                .catch((error) => {
                    console.log(error);
                });
        },

        buscarEnviosAbertos(id_container, callback) {

            let data = {
                id_container: id_container
            }

            axios
                .post(
                    process.env.MIX_VUE_APP_ENDPOINT + `/database/send/search_open_sends`,
                    data
                )
                .then((response) => {
                    console.log("Envios listados");
                    console.log(response.data);

                    callback(response.data.envios);

                })
                .catch((error) => {
                    console.log(error);
                });
        },

        buscarUmEnvio(id_envio, callback) {

            let data = {
                id_envio: id_envio
            }

            axios
                .post(
                    process.env.MIX_VUE_APP_ENDPOINT + `/database/send/search_one_send`,
                    data
                )
                .then((response) => {
                    callback(response.data.envio);
                })
                .catch((error) => {
                    console.log(error);
                });
        },

        buscarNumerosNaoEnviados(id_envio, callback) {

            let data = {
                id_envio: id_envio
            }

            axios
                .post(`/numeros_envios/search`, data)
                .then((response) => {
                    callback(response.data.numeros);
                })
                .catch((error) => {
                    console.log(error);
                });
        },

        buscarNomeUsuario(numero, id_container, callback) {
            console.log(numero, id_container, callback);
        },

        addToFunil(container_id, numero, funil_id) {

            let data = {
                container_id: container_id,
                funil_id: funil_id,
                numero: numero
            }

            console.log("\n\nADICIONANDO AO FUNIL");

            axios
                .post(`/funil/add`, data)
                .then((response) => {
                    if (response.data.success) {
                        sendToWebhook(container_id, numero, funil_id);
                    }
                })
                .catch((error) => {
                    console.log(error);
                });
        },

        sendToWebhook(container_id, numero, funil_id, chave_api) {
            let data = {
                container_id: container_id,
                funil_id: funil_id,
                numero: numero,
                chave_api: chave_api
            }

            console.log("ENVIANDO PARA WEBHOOK");
            console.log(data);

            axios
                .post(process.env.MIX_VUE_APP_ENDPOINT_WEBHOOK + `/funil/start`, data)
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
        }

    }
};

