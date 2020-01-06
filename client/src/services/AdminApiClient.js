import autoBind from 'auto-bind'
import { axios, validateAuthError } from '../utils/helpers';


class AdminApiClient {
	constructor(url) {
		this.url = url;
		autoBind(this);
	}

	async fetchItems() {
		try {
			const response = await axios.get(this.url);
			return response.data;
		} catch (err) {
			validateAuthError(err);
			throw err;
		}
	}

	async saveItem(item) {
		let response;
		try {
			if (item.id) {
				response = await axios.put(`${this.url}/${item.id}`, item);
			} else {
				response = await axios.post(this.url, item);
			}
			return response.data;
		} catch (err) {
			validateAuthError(err);
			throw err;
		}
	}

	async deleteItem(id) {
		try {
			const response = await axios.delete(`${this.url}/${id}`);
			return response.data;
		} catch (err) {
			validateAuthError(err);
			throw err;
		}
	}
}

export default AdminApiClient;
