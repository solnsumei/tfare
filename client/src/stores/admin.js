import { writable } from 'svelte/store';

const citiesStore = () => {
	const { subscribe, set, update } = writable([]);

	return {
		subscribe,
		loadCities: (cities) => set(cities),
		updateCity: (city) => update(cities => [...cities.filter((item) => item.id !== city.id), city]),
		deleteCity: (id) => update(cities => [...cities.filter((item) => item.id !== id)]),
		reset: () => set([]),
	}
}

export const cities = citiesStore();
