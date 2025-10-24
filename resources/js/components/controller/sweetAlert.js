export default {
    methods: {

        handleRemove(title, id, callBackSuccess) {
            this.$swal
                .fire({
                    title: title,
                    text: "Voce nao poderá reverter essa operação!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sim, quero deletar!"
                })
                .then(result => {
                    if (result.value) {
                        callBackSuccess(id);
                    }
                });
        },


        showSuccessMessageWithButtonAndRedirect(tittle, message, href) {
            this.$swal.fire({
                icon: 'success',
                title: tittle,
                padding: '1.5em',
                text: message,
            }).then(function () {
                window.location.href = href;
            });
        },

        showErrorMessageWithButtonAndRedirect(tittle, message, href) {
            this.$swal.fire({
                icon: 'error',
                title: tittle,
                padding: '1.5em',
                text: message,
            }).then(function () {
                window.location.href = href;
            });
        },

        showErrorMessageWithButtonAndRedirectIfConfirm(tittle, message, href) {
            this.$swal.fire({
                icon: 'error',
                title: tittle,
                padding: '1.5em',
                text: message,
                showCancelButton: true,
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.value) {
                    window.location.href = href;
                }
            });
        },

        showErrorMessageWithButtonAndReloadIfConfirm(tittle, message, href) {
            this.$swal.fire({
                icon: 'error',
                title: tittle,
                padding: '1.5em',
                text: message,
            }).then((result) => {
                if (result.value) {
                    window.location.reload();
                }
            });
        },

        showSuccessMessageWithButtonAndReloadIfConfirm(tittle, message) {
            this.$swal.fire({
                icon: 'success',
                title: tittle,
                padding: '1.5em',
                text: message,
                showCancelButton: true,
            }).then((result) => {
                if (result.value) {
                    window.location.reload();
                }
            });
        },

        showErrorMessageWithButtonAndReload(tittle, message) {
            this.$swal.fire({
                icon: 'error',
                title: tittle,
                padding: '1.5em',
                text: message,
            }).then(function () {
                window.location.reload();
            });
        },

        showSuccessMessageWithButtonAndReload(tittle, message) {
            this.$swal.fire({
                icon: 'success',
                title: tittle,
                padding: '1.5em',
                text: message,
            }).then(function () {
                window.location.reload();
            });
        },

        showSuccessMessageWithButtonAndReloadHtml(tittle, html) {
            this.$swal.fire({
                icon: 'success',
                title: tittle,
                padding: '1.5em',
                html: html,
            }).then(function () {
                window.location.reload();
            });
        },

        showErrorMessageWithButtonAndRefresh(tittle, message) {
            this.$swal.fire({
                icon: 'error',
                title: tittle,
                padding: '1.5em',
                text: message,
            }).then(function () {
                window.location.reload();
            });
        },

        showErrorMessageWithButtonAndReload(tittle, message) {
            this.$swal.fire({
                icon: 'error',
                title: tittle,
                padding: '1.5em',
                text: message,
            }).then(function () {
                window.location.reload();
            });
        },

        confirmWarning(title, callBackSuccess) {
            this.$swal
                .fire({
                    title: title,
                    text: "Voce nao poderá reverter essa operação!",
                    icon: "warning",
                    padding: '1.5em',
                    showCancelButton: true,
                    confirmButtonColor: "#4caf50",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sim!"
                })
                .then(result => {
                    if (result.value) {
                        callBackSuccess();
                        this.$swal.fire({
                            title: "Ok!",
                            text: "O processo foi executado com sucesso.",
                            padding: '1.5em',
                            icon: "success",
                            confirmButtonText: "OK!"
                        }
                        );
                    }
                });
        },

        confirmarEnvio(title, mensagem, callBackSuccess) {
            this.$swal
                .fire({
                    title: title,
                    text: mensagem,
                    icon: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#4caf50",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sim!",
                    padding: '1.5em',
                    className: 'swal-modal',
                    customClass: {
                        container: 'swal-modal',
                        popup: 'swal-modal',
                        header: 'swal-modal',
                        title: 'swal-modal',
                        closeButton: 'swal-modal',
                        icon: 'swal-modal',
                        image: 'swal-modal',
                        content: 'swal-modal',
                        input: 'swal-modal',
                        actions: 'swal-modal',
                        confirmButton: 'swal-modal',
                        cancelButton: 'swal-modal',
                        footer: 'swal-modal'
                    }
                })
                .then(result => {
                    if (result.value) {
                        callBackSuccess();
                        this.$swal.fire(
                            "Ok!",
                            "O processo foi executado com sucesso.",
                            "success"
                        );
                    }
                });
        },

        confirmarRecuperacaoMensagens(title, html, callBackSuccess) {
            this.$swal
                .fire({
                    title: title,
                    html: html,
                    icon: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#4caf50",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sim!"
                })
                .then(result => {
                    if (result.value) {
                        callBackSuccess();
                    }
                });
        },

        showErrorMessage(msg) {
            this.$swal.fire({
                toast: true,
                icon: "error",
                title: msg,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                onOpen: toast => {
                    toast.addEventListener("mouseenter", this.$swal.stopTimer);
                    toast.addEventListener("mouseleave", this.$swal.resumeTimer);
                }
            });
        },

        showSuccessMessage(msg) {
            this.$swal.fire({
                toast: true,
                icon: "success",
                title: msg,
                position: "top-end",
                showConfirmButton: false,
                padding: '1.5em',
                timer: 3000,
                timerProgressBar: true,
                onOpen: toast => {
                    toast.addEventListener("mouseenter", this.$swal.stopTimer);
                    toast.addEventListener("mouseleave", this.$swal.resumeTimer);
                }
            });
        },

        showErrorMessageWithButton(tittle, message) {
            this.$swal.fire({
                icon: 'error',
                title: tittle,
                padding: '1.5em',
                text: message,
            })
        },
        createButton(text, id) {
            return $('<button class="swal2-input swal2-styled" id="' + id + '">' + text + '</button>');
        },

        createMessage(text) {
            return $('<div class="swal2-content" style="display: block;">' + text + '</div>');
        },

        showLotesBtnsOptions(leilao_id, lote_id) {
            /*
            var buttonsPlus = $('<div>')
                .append(this.createButton("OK2", 'sw_butt1'))
                .append(this.createButton("OK3", 'sw_butt2'))
                .append(this.createButton("OK4", 'sw_butt3'))
                .append(this.createButton("OK4", 'sw_butt4'));

            this.$swal.fire({
                title: 'Select Option',
                html: buttonsPlus,
                type: 'question',

                showCancelButton: false,
                showConfirmButton: false,
                animation: false,
                customClass: "animated zoomIn",
                onOpen: function (dObj) {
                    $('#sw_butt1').on('click', function () {
                        swal.close();
                        callbackOne();
                    });
                    $('#sw_butt2').on('click', function () {
                        swal.close();
                        callbackTwo();
                    });
                    $('#sw_butt3').on('click', function () {
                        swal.close();
                        callbackThree();
                    });
                }
            });
            */

            this.$swal.fire({
                title: "O que você gostaria de fazer agora?",
                showCancelButton: true,
                confirmButtonText: "Listar lotes",
                cancelButtonText: "Adicionar lotes",
                denyButtonText: `Cadastrar fotos`,
                denyButtonColor: `#000080`,
                confirmButtonColor: `#000080`,
                cancelButtonColor: `#000080`,
                showDenyButton: true,
            }).then((result) => {
                console.log(result);
                if (result.isConfirmed) {
                    window.location.href = "/admin/leilao/" + leilao_id + "/lotes";
                } else if (result.isDismissed && result.dismiss == "cancel") {
                    window.location.href = "/admin/leilao/" + leilao_id + "/lotes/cadastrar_lote";
                    //CLIQUE FORA
                } else if (result.isDenied) {
                    window.location.href = "/admin/fotos/" + lote_id;
                } else if (result.isDismissed && result.dismiss == "backdrop") {
                    //window.location.href = "/admin/leilao/" + leilao_id + "/lotes/cadastrar_lote";
                }
            });
        },

        showSuccessMessageWithButtonAndCallback(tittle, message, callback) {
            this.$swal.fire({
                icon: 'success',
                title: tittle,
                padding: '1.5em',
                text: message,
            }).then(function () {
                callback();
            });
        },

        showSuccessMessageWithButton(tittle, message) {
            this.$swal.fire({
                icon: 'success',
                title: tittle,
                padding: '1.5em',
                text: message,
            })
        },

        showSuccessMessageWithButtonAndRedirect(tittle, message, href) {
            this.$swal.fire({
                icon: 'success',
                title: tittle,
                padding: '1.5em',
                text: message,
            }).then(function () {
                console.log("Redirecionando agora!");
                window.location.href = href;
            });
        },


        showErrorWithButton(title, mensagem, html) {
            // Use sweetalert2
            this.$swal
                .fire({
                    title: title,
                    text: mensagem,
                    icon: "error",
                    showCancelButton: true,
                    confirmButtonColor: "#4caf50",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sim!",
                    html: html
                })
                .then(result => {
                    result;
                });
        },

        edit(nome, status1, status2, email, callback) {
            this.$swal.fire({
                title: 'Editar Usuário',
                showCancelButton: true,
                html:
                    '<label for="nome">Nome</label>' +
                    '<input id="swal-input1" class="form-control" value="' + nome + '">' +
                    '<br/>' +
                    '<label for="status">Status</label>' +
                    '<select class="form-control" id="status">' +
                    '<option value="' + status1 + '">' + status1 + '</option>' +
                    '<option value="' + status2 + '">' + status2 + '</option>' +
                    '</select>' +
                    '<br/>' +
                    '<label for="email">Email</label>' +
                    '<input id="swal-input3" class="form-control" value="' + email + '">' +
                    '<br/>' +
                    '<label for="senha">Redefinir Senha</label>' +
                    '<input id="swal-input2" class="form-control" />',
                focusConfirm: false,
                preConfirm: () => {
                    return [
                        document.getElementById('swal-input1').value,
                        document.getElementById('swal-input2').value,
                        document.getElementById('status').value,
                        document.getElementById('swal-input3').value,
                    ]
                }
            }).then((response) => {
                if (response) {
                    callback(response);
                }
            })

        },

        editUser(nome, email, callback) {
            this.$swal.fire({
                title: 'Editar Usuário',
                showCancelButton: true,
                html:
                    '<label for="nome">Nome</label>' +
                    '<input id="swal-input1" class="form-control" value="' + nome + '">' +
                    '<br/>' +
                    '<label for="email">Email</label>' +
                    '<input id="swal-input2" class="form-control" value="' + email + '">' +
                    '<br/>' +
                    '<label for="senha">Redefinir Senha</label>' +
                    '<input id="swal-input3" class="form-control" />',
                focusConfirm: false,
                preConfirm: () => {
                    return [
                        document.getElementById('swal-input1').value,
                        document.getElementById('swal-input2').value,
                        document.getElementById('swal-input3').value,
                    ]
                }
            }).then((response) => {
                if (response) {
                    callback(response);
                }
            })

        },

        editLista(nome, callback) {
            this.$swal.fire({
                title: 'Editar Lista',
                showCancelButton: true,
                html:
                    '<label for="nome">Nome</label>' +
                    '<input id="swal-input1" class="form-control" value="' + nome + '">' +
                    '<br/>',
                focusConfirm: false,
                preConfirm: () => {
                    return [
                        document.getElementById('swal-input1').value,
                    ]
                }
            }).then((response) => {
                if (response) {
                    callback(response);
                }
            })

        },

        confirmDeleteList(title, mensagem, callBackSuccess) {
            this.$swal
                .fire({
                    title: title,
                    text: mensagem,
                    icon: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#4caf50",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sim!"
                })
                .then(result => {
                    if (result.value) {
                        callBackSuccess();
                    }
                });
        },

        confirmarCadastroLead(title, html, callBackSuccess) {
            this.$swal
                .fire({
                    title: title,
                    html: html,
                    icon: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#4caf50",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sim!"
                })
                .then(result => {
                    if (result.value) {
                        callBackSuccess();
                    }
                });
        },
    }
};


