export default {
    methods: {

        toCurrency(value) {

            let money = value;

            if (typeof money !== "number") {
                money = this.toNumber(money);
            }

            money = new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'BRL' }).format(
                money,
            );

            //console.log(money);

            if (money.includes("R$")) {
                money = money.substr(0, money.length - 3);
            }

            return money;
        },

        toNumber(value) {
            //console.log("Not a number " + value);
            //console.log(value);

            if (value) {
                if (typeof value == "number") {
                    return value;
                } else {

                    let money = value;

                    if (money.charAt(money.length - 2) == ",") {
                        money = money + "0";
                    }

                    money = this.replaceAll(money, ",", ".");
                    money = this.replaceAll(money, ' ', "");

                    let possui_decimal = false;

                    if (money.length >= 3) {
                        possui_decimal = money.charAt(money.length - 3) == ".";

                        money = this.replaceAll(money, ".", "");

                        if (possui_decimal) {
                            let before = money.substr(0, money.length - 2);
                            let after = money.substr(-2, money.length);

                            money = before + "." + after;
                        }

                    }

                    return money;

                }
            } else {
                return value;
            }


        },

        replaceAll(string, search, replace) {
            return string.split(search).join(replace);
        },

    }
};


