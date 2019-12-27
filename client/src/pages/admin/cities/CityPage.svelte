<script>
	import { onMount, afterUpdate } from 'svelte';
 	import { getCities, deleteCity } from '../../../services/admin';
	import { cities } from '../../../stores/admin';
	import { confirmAction, showMessage } from '../../../utils/alertHelpers';
	import { sortItems } from '../../../utils/helpers';
	import CityForm from './CityForm.svelte';


	$: cityList = $cities.sort(sortItems);

	let city = {
		name: '',
		country: 'Nigeria',
	};

	onMount(async () => {
		try {
			const data = await getCities();
			cities.loadCities(data.cities);
		} catch (err) {
			console.log(err);
		}
	});

	const deleteItem = async (city) => {
		try {
			const response = await confirmAction({
				title: 'Confirm Delete',
				text: `Do you want to delete ${city.name}`
			});

			if (response.value) {
				const data = await deleteCity(city.id);
				cities.deleteCity(city.id);

				showMessage({
					icon: 'success',
					text: 'Item was deleted successfully!',
				});
			}
		} catch (err) {
			showMessage({
				icon: 'error',
				text: `Error ${err.message}`,
			});
		}
	}

	const handleReset = (event) => {
		city = event.detail.city;
	};
</script>

<style></style>

<div class="row">
	<div class="col-12">
		<h3 class="top-margin">Cities</h3>
	</div>
</div>

<CityForm {city} on:resetCity={handleReset}/>

{#if (!city.id)}
	<div class="row">
		<div class="col-12">
			<table class="table table-hover">
				<tbody>
					{#if (cityList.length > 0)}
						{#each cityList as item}
							<tr>
								<td>{item.name}</td>
								<td>
									<button class="btn btn-primary" on:click={() => city = item}><i class="fa fa-edit"></i></button>
									<button class="btn btn-danger" on:click={() => deleteItem(item)}>
										<i class="fa fa-trash"></i>
									</button>
								</td>
							</tr>
						{/each}
					{:else}
					<tr>
						<td colspan="2">No cities found</td>
					</tr>
					{/if}
				</tbody>
			</table>
		</div>
	</div>
{/if}

