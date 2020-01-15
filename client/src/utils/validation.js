import Joi from '@hapi/joi';


const nameSchema = Joi.string().required().min(2);

const validate = (schema, request, opt={}) => {
	if (!schema) {
		return 'schema must be provided';
	}

	const options = { ...opt, allowUnknown: true, abortEarly: false };
	const errors = {};

	const result = schema.validate(request, { ...options });

	if (!result.error) {
		return;
	}

	result.error.details.forEach((e) => {
		errors[e.context.key] = e.message.replace(/"/g, '');
	});

	return errors;
}

export const validateName = (name) => {
	return validate(Joi.object({
		name: nameSchema,
	}), { name });
};

export const validateTerminalRequest = (terminalRequest) => {
	return validate(Joi.object({
		name: nameSchema,
		phone: Joi.string().min(8).max(15).pattern(/^[0-9]+$/, 'phone').required()
			.error(errors => {
				const [error] = errors;
				error.message = 'Phone number is invalid';
				return [error];
			}),
		address: Joi.string().min(5).max(150),
		company_id: Joi.number().min(1).required(),
		city_id: Joi.number().min(1).required(),
		park_id: Joi.number().min(1).required(),
	}), terminalRequest);
};

