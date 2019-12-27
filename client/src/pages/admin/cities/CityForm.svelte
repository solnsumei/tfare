<script>
	import { beforeUpdate, afterUpdate, createEventDispatcher } from 'svelte';
	import { validateName } from '../../../utils/validation';
	import { saveCity } from '../../../services/admin';
	import { showMessage } from '../../../utils/alertHelpers';
	import { cities } from '../../../stores/admin';
	import TextInput from '../../../components/TextInput.svelte';
	import SelectInput from '../../../components/SelectInput.svelte';

	const dispatch = createEventDispatcher();

	export let city;

	let nameError;
	let countryList = ['Nigeria'];

	beforeUpdate(async () => {
		if (nameError && city.name.length >= 2) {
			nameError = null;
		}
	});

	const resetCity = () => {
		dispatch('resetCity', {
			city: {
				name: '',
				country: 'Nigeria',
			}
		});
	};

	const cancelEdit = () => {
		resetCity();
	};

	const submitForm = async (event) => {
		const errors = validateName(city.name);
		if (errors) {
			return nameError = errors.name;
		}

		try {
			const data = await saveCity(city);
			cities.updateCity(data.city);
			resetCity();
			showMessage({
				icon: 'success',
				text: data.message,
			});
		} catch (err) {
			showMessage({
				icon: 'error',
				text: err.response.data.message,
			});
		}
	};
</script>

<style>
	.space-left {
		margin-left: 5px !important;
	}
</style>

<div class="top-margin"></div>
<div class="row">
	<div class="col-12">
		<form on:submit|preventDefault={submitForm} >
			<div class="row">
				<div class="col-sm-4">
					<TextInput
						label='Name'
						bind:inputValue={city.name}
						error={nameError}
						placeholderText='Enter City Name' />
				</div>

				<div class="col-sm-3">
					<SelectInput
						label='Country'
						bind:selectedValue={city.country}
						itemList={countryList} />
				</div>

				<div class="col-sm-3">
					<div class="form-group">
						<label for=parks>&nbsp;</label> <br/>
						<button class="btn btn-primary">{ city && city.id ? 'Edit City' : 'Add City' }</button>
						{#if (city.id)}
							<button class="btn btn-danger space-left" on:click={cancelEdit}>Cancel</button>
						{/if}
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
