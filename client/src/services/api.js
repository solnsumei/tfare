import axios from 'axios';
import { TOKEN, validateAuthError } from '../utils/helpers';
import { user } from '../stores/app';


axios.defaults.baseURL = 'http://tfare.test/api/v1';
axios.defaults.headers.common['Content-Type'] = 'application/json';

export const getCities = async () => {
  try {
    const response = await axios.get('/cities');
    return response.data;
  } catch (err) {
    console.log(err.message);
  }
};

export const getParks = async (cityId) => {
  try {
    const response = await axios.get(`/cities/${cityId}/parks`);
    return response.data;
  } catch (err) {
    console.log(err.message);
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
    console.log(err.message);
  }
};

export const loginUser = async ({ email, password }) => {
  try {
    const response = await axios.post('/login', { email, password });

    localStorage.setItem(TOKEN, response.data.token);
    user.authenticate(response.data.user);

    return 'success';
  } catch (err) {
    validateAuthError(err, axios);
    throw err;
  }
};
