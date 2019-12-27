import { axios, validateAuthError, authenticateUser } from '../utils/helpers';

// Public routes
export const getCities = async () => {
  try {
    const response = await axios.get('/cities');
    return response.data;
  } catch (err) {
    throw err;
  }
};

export const getParks = async (cityId) => {
  try {
    const response = await axios.get(`/cities/${cityId}/parks`);
    return response.data;
  } catch (err) {
    throw err;
  }
};

export const searchBuses = async (from, to, park) => {
  let url = `/search?from=${from}&to=${to}`;

  if (park) {
    url += `&park=${park}`;
  }

  try {
    const response = await axios.get(url);
    return response.data;
  } catch (err) {
    throw err;
  }
};

export const loginUser = async ({ email, password }) => {
  try {
    const response = await axios.post('/login', { email, password });
    authenticateUser(response.data);
    return 'success';
  } catch (err) {
    validateAuthError(err);
    throw err;
  }
};
