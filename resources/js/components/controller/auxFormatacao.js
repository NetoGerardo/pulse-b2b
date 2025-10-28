
import currencyController from "../controller/currencyController";

export default {

    mixins: [currencyController],

    methods: {

        getApelidoFormatado(apelido) {
            if (apelido) {
                if (apelido.length < 6 && apelido.length >= 3) {
                    let apelido_formatado = apelido.substring(0, 2);

                    for (let i = 2; i < apelido.length; i++) {
                        apelido_formatado = apelido_formatado + "*";
                    }

                    apelido_formatado = apelido_formatado + apelido.substring(apelido.length - 1, apelido.length);

                    return apelido_formatado;
                } else if (apelido.length >= 6) {
                    let apelido_formatado = apelido.substring(0, 2);

                    for (let i = 2; i < apelido.length - 1; i++) {
                        apelido_formatado = apelido_formatado + "*";
                    }

                    apelido_formatado = apelido_formatado + apelido.substring(apelido.length - 1, apelido.length);

                    return apelido_formatado;
                } else if (apelido.length < 3) {
                    let apelido_formatado = apelido.substring(0, 1);

                    for (let i = 1; i < apelido.length; i++) {
                        apelido_formatado = apelido_formatado + "*";
                    }

                    return apelido_formatado;
                }
            } else {
                return apelido;
            }
        },

        htmlParaTexto(text) {
            if (typeof text == "string" && text.includes("</")) {
                var div = document.createElement("div");
                div.innerHTML = text;
                text = div.textContent || div.innerText || "";
            }

            return text;
        },

        formatarTelefone(phoneNumberString) {
            if (phoneNumberString) {
                const digits = phoneNumberString.replace(/\D/g, "");

                if (digits.length >= 11) {
                    const areaCode = digits.substring(0, 2);
                    const prefix = digits.substring(2, 7);
                    const suffix = digits.substring(7, phoneNumberString.length);

                    return `(${areaCode}) ${prefix}-${suffix}`;
                } else if (digits.length == 10) {
                    const areaCode = digits.substring(0, 2);
                    const prefix = digits.substring(2, 6);
                    const suffix = digits.substring(6, phoneNumberString.length);

                    return `(${areaCode}) ${prefix}-${suffix}`;
                } else {
                    return phoneNumberString;
                }
            } else {
                phoneNumberString
            }
        },

        formatarCPF(cpf) {
            if (cpf) {
                const digits = cpf.replace(/\D/g, "");

                if (digits.length == 11) {
                    const areaCode = digits.substring(0, 3);
                    const prefix = digits.substring(3, 6);
                    const suffix = digits.substring(6, 9);
                    const final = digits.substring(9, cpf.length);

                    return `${areaCode}.${prefix}.${suffix}-${final}`;
                }
            } else {
                cpf
            }
        },

        formatarCNPJ(cnpj) {
            // Remove tudo que não for número
            cnpj = cnpj.replace(/[^\d]/g, '');

            // Verifica se o CNPJ tem o tamanho correto
            if (cnpj.length !== 14) {
                return 'CNPJ inválido';
            }

            // Formata o CNPJ
            return cnpj.replace(
                /^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/,
                '$1.$2.$3/$4-$5'
            );
        },

        formatarCep(cep) {
            // Remove tudo que não for número
            cep = cep.replace(/[^\d]/g, '');

            // Verifica se o CEP tem o tamanho correto
            if (cep.length !== 8) {
                return 'CEP inválido';
            }

            // Formata o CEP
            return cep.replace(/^(\d{2})(\d{3})(\d{3})$/, "$1.$2-$3");
        },

        formatarMoeda01(valor) {

            if (typeof valor != "number") {
                valor = this.toNumber(valor);
            }

            if (valor >= 1000) {
                let money = valor.toString().replace(/\D/g, "");

                let pre = money.substring(money.length - 3, money.length);
                let pos = money.substring(0, money.length - 3);

                return `${pos}.${pre}`;

            } else {
                return valor;
            }
        },

        //FORMATA O VALOR PARA NÃO TEM CASA DECIMAIS OU TER NO MÁXIMO 1 CASA DECIMAL
        formatar01CasaDecimal(valor) {
            if (typeof valor != "number") {
                valor = this.toNumber(valor);
            }

            if (valor % 1 != 0) {
                return Number.parseFloat(valor).toFixed(1);

            } else {
                return valor;
            }
        },

        //FORMATA O VALOR PARA NÃO TEM CASA DECIMAIS OU TER NO MÁXIMO 1 CASA DECIMAL
        formatar02CasaDecimal(valor) {
            if (typeof valor != "number") {
                valor = this.toNumber(valor);
            }

            if (valor % 1 != 0) {
                return Number.parseFloat(valor).toFixed(2);

            } else {
                return valor;
            }
        },
    },
};


