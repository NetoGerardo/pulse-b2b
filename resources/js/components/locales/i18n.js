import * as validators from '@vuelidate/validators'
const { createI18nMessage } = validators;
import { createI18n } from 'vue-i18n';
import { sizeValidator } from './size';
import { minLengthValidator } from './size';
import { maxLengthValidator } from './size';
import { emailValidator  } from './size';


const messages = {
    pt: {
        validations: {
            required: "Campo obrigatório.",
            size: 'O campo {property} precisa ter {amount} caracteres.',
            minLength: 'O campo {property} precisa ter no mínimo {amount} caracteres.',
            maxLength: 'O campo {property} precisa ter no máximo {amount} caracteres.',
        },
    },
};

const i18n = createI18n({
    locale: "pt",
    messages,
});

const withI18nMessage = createI18nMessage({ t: i18n.global.t.bind(i18n) });
export const required = withI18nMessage(validators.required);
export const size = withI18nMessage(sizeValidator, { withArguments: true })
export const minLength = withI18nMessage(minLengthValidator, { withArguments: true })
export const maxLength = withI18nMessage(maxLengthValidator, { withArguments: true })
export const email = withI18nMessage(emailValidator, { withArguments: true })

export default i18n;