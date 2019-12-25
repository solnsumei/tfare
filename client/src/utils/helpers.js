import { navigateTo } from 'svelte-router-spa';


export const TOKEN = 'token';

export const validateAuthError = (err, axios) => {
  if (err.response.status === 401 && localStorage.getItem(TOKEN)) {
    delete axios.defaults.headers.common['Authorization'];
    localStorage.removeItem(TOKEN);
  }
};

export const navigateToRoute = (routeName) => navigateTo(routeName);

export const isAuthenticated = () => localStorage.getItem(TOKEN);

export const isGuest = () => !localStorage.getItem(TOKEN);

