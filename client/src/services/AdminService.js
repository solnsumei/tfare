import autoBind from 'auto-bind';
import pluralize from 'pluralize';
import { navigateToRoute } from '../utils/helpers';
import { showMessage, confirmAction } from '../utils/alertHelpers';


class AdminService {
	constructor({ name, client, store }) {
		this.name = name;
		this.client = client;
		this.store = store;
		autoBind(this);
	}

	async loadItems(query) {
		try {
			const data = await this.client.fetchItems(query);
			this.store.load(data[pluralize.plural(this.name)]);
		} catch (err) {
			throw err;
		}
	}

	async loadItem({ id, target }) {
		try {
			const data = await this.client.fetchItem(id);

			const localData = data[this.name];

			this.store.updateItem(localData);

			if (target) {
				target.store.load(localData[pluralize.plural(target.name)]);
			}

			return localData;

		} catch (err) {
			throw err;
		}
	}

	async save({ item, page, cb }) {
		try {
			const data = await this.client.saveItem(item);
			this.store.updateItem(data[this.name]);

			if (cb) {
				cb();
			}

			await showMessage({
				icon: 'success',
				text: data.message,
			});

			if (page) {
				return navigateToRoute(page);
			}
		} catch (err) {
			showMessage({
				icon: 'error',
				text: err.response.data.message,
			});
		}
	}

	async delete(item) {
		try {
			const response = await confirmAction({
				title: 'Confirm Delete',
				text: `Do you want to delete ${item.name} from ${pluralize.plural(this.name)}?`
			});

			if (response.value) {
				await this.client.deleteItem(item.id);
				this.store.deleteItem(item.id);

				showMessage({
					icon: 'success',
					text: 'Item was deleted successfully!',
				});
			}
		} catch (err) {
			showMessage({
				icon: 'error',
				text: `Error ${err.response.data.message}`,
			});
		}
	};
}

export default AdminService;
