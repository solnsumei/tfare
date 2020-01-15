<script>
	import { onMount, afterUpdate } from 'svelte';
	import { Navigate } from 'svelte-router-spa';
	import { cityService } from '../../../services/admin';
	import { cities } from '../../../stores/admin';
	import { sortItems, pageLoading, loadDataTable } from '../../../utils/helpers';
	import CityForm from './CityForm.svelte';
	import LoadingIndicator from '../../../components/LoadingIndicator.svelte';

	$: cityList = $cities.sort(sortItems);

	let city = {
		name: '',
		country: 'Nigeria',
	};

	const widget = pageLoading();

	onMount(async () => {
		if ($cities.length > 0) {
			widget.loading = false;
		}

		try {
			await cityService.loadItems();
		} catch (err) {
			console.log(err);
		} finally {
			widget.loading = false;
		}
	});

	afterUpdate(async () => {
		if (cityList.length > 0) {
			loadDataTable('#cityId');
		}
	});

	const handleReset = (event) => {
		city = event.detail.city;
	};
</script>

<style></style>

<div class="row">
	<div class="col-12">
		<h3 class="top-margin page-title">Cities</h3>
	</div>
</div>

<CityForm {city} on:resetCity={handleReset}/>

{#if (!city.id)}
	{#if (!widget.loading)}
		<div class="row">
			<div class="col-12 table-responsive">
				<table id="cityId" class="table table-hover">
					<thead class="thead-light">
						<tr>
							<th>City</th>
							<th>Country</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						{#if (cityList.length > 0)}
							{#each cityList as item}
								<tr>
									<td>{item.name}</td>
									<td>{item.country}</td>
									<td class="text-right">
										<Navigate to={`/admin/cities/${item.id}`}>
											<span class="btn btn-primary"><i class="fa fa-eye"></i></span>
										</Navigate>
										<button class="btn btn-primary" on:click={() => city = item}><i class="fa fa-edit"></i></button>
										<button class="btn btn-danger"
											on:click={() => cityService.delete(item)}>
											<i class="fa fa-trash"></i>
										</button>
									</td>
								</tr>
							{/each}
						{:else}
						<tr>
							<td colspan="3">No cities found</td>
						</tr>
						{/if}
					</tbody>
				</table>
			</div>
		</div>
	{:else}
		<LoadingIndicator />
	{/if}
{/if}

