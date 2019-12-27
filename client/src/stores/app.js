import { writable }  from 'svelte/store';

const isLoggedInStore = () => {
    const { subscribe, set } = writable(localStorage.getItem('token'));

  return {
    subscribe,
    setItem: () => set(localStorage.getItem('token')),
  }
}

export const isLoggedIn = isLoggedInStore();

const userStore = () => {
  const { subscribe, set } = writable(null);

  return {
    subscribe,
    authenticate: (user) => set(user),
    reset: () => set(null),
  }
}

export const user = userStore();

