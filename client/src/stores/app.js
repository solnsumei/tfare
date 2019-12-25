import { writable }  from 'svelte/store';

const userStore = () => {
  const { subscribe, set } = writable(null);

  return {
    subscribe,
    authenticate: (user) => set(user),
    reset: () => set(null),
  }
}

export const user = userStore();
