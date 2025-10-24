import { helpers } from '@vuelidate/validators'

export function sizeValidator(amount) {
	return {
		$validator: function (value) {
			return !helpers.req(value) || value.length === amount
		},
		$message: ({ $params }) => `This field should be exactly ${$params.amount} long.`,
		$params: { amount, type: 'size' }
	}
}

export function minLengthValidator(amount) {
	return {
		$validator: function (value) {
			return !helpers.req(value) || value.length >= amount
		},
		$message: ({ $params }) => `This field should be exactly ${$params.amount} long.`,
		$params: { amount, type: 'size' }
	}
}

export function maxLengthValidator(amount) {
	return {
		$validator: function (value) {
			return !helpers.req(value) || value.length < amount
		},
		$message: ({ $params }) => `This field should be exactly ${$params.amount} long.`,
		$params: { amount, type: 'size' }
	}
}

export function emailValidator(amount) {
	return {
		$validator: function (value) {
			return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(value)
		},
		$message: ({ $params }) => `Digite um e-mail válido`,
		$params: { amount, type: 'size' }
	}
}
