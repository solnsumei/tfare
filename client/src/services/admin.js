import { axios, setAuthHeader, validateAuthError } from '../utils/helpers';

// Admin routes
setAuthHeader();

const urlPrefix = '/admin';
const urlCities = `${urlPrefix}/cities`;

export const getCities = async () => {
  try {
    const response = await axios.get(`${urlCities}`);
    return response.data;
  } catch (err) {
		validateAuthError(err);
    throw err;
  }
};

export const saveCity = async (city) => {
	let response;
	try {
		if (city.id) {
			response = await axios.put(`${urlCities}/${city.id}`, city);
		} else {
			response = await axios.post(`${urlCities}`, city);
		}
		return response.data;
	} catch (err) {
		validateAuthError(err);
		throw err;
	}
};

export const deleteCity = async (id) => {
  try {
		const response = await axios.delete(`${urlCities}/${id}`);
    return response.data;
  } catch (err) {
		validateAuthError(err);
    throw err;
  }
};
