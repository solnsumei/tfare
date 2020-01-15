import { writable } from 'svelte/store';

const createItemStore = () => {
	const { subscribe, set, update } = writable([]);

	return {
		subscribe,
		load: (items) => set(items),
		updateItem: (item) => update(items => [...items.filter((n) => n.id !== item.id), item]),
		deleteItem: (id) => update(items => [...items.filter((n) => n.id !== id)]),
		reset: () => set([]),
	}
}

export const cities = createItemStore();
export const companies = createItemStore();
export const parks = createItemStore();
export const terminals = createItemStore();
export const busRoutes = createItemStore();
