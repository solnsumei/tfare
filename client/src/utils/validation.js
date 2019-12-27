import Joi from '@hapi/joi';


const nameSchema = Joi.string().required().min(2);

const validate = (schema, request, opt={}) => {
	if (!schema) {
		return 'schema must be provided';
	}

	const options = { ...opt, allowUnknown: true };
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

