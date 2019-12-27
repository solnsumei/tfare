import axios from 'axios';
import { navigateTo } from 'svelte-router-spa';
import { user, isLoggedIn } from '../stores/app';


const TOKEN = 'token';
axios.defaults.baseURL = 'http://tfare.test/api/v1';
axios.defaults.headers.common['Content-Type'] = 'application/json';

export const setAuthHeader = () => {
  const token = localStorage.getItem(TOKEN)
  if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
  }
}

export const authenticateUser = (data) => {
  localStorage.setItem(TOKEN, data.token);
  user.authenticate(data.user);
  isLoggedIn.setItem();
  setAuthHeader();
};

const reset = () => {
  delete axios.defaults.headers.common['Authorization'];
  localStorage.removeItem(TOKEN);
  user.reset();
  isLoggedIn.setItem();
};

export const validateAuthError = (err) => {
  if (err.response.status === 401 && localStorage.getItem(TOKEN)) {
    reset();
  }
};

export const logoutUser = () => {
  reset();
};

export const sortItems = (a, b) => {
  var nameA = a.name.toUpperCase(); // ignore upper and lowercase
  var nameB = b.name.toUpperCase(); // ignore upper and lowercase

  if (nameA === nameB) {
    return 0;
  }

  return nameA < nameB ? -1 : 1;
};

export const navigateToRoute = (routeName) => navigateTo(routeName);

export const isAuthenticated = () => localStorage.getItem(TOKEN);

export const isGuest = () => !localStorage.getItem(TOKEN);

export { axios };
