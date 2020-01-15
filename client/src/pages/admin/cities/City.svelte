<script>
	import { onMount, afterUpdate } from 'svelte';
	import { Navigate } from 'svelte-router-spa';
 	import { parkService, cityService } from '../../../services/admin';
	import { cities, parks } from '../../../stores/admin';
	import { navigateToRoute, pageLoading, unsetLoading } from '../../../utils/helpers';
	import { confirmAction, showMessage } from '../../../utils/alertHelpers';
	import { sortItems } from '../../../utils/helpers';
	import ParkForm from './ParkForm.svelte';
	import LoadingIndicator from '../../../components/LoadingIndicator.svelte';

	export let currentRoute;
	export let params;

	let city;
	let showParkForm = false;
	let widget = pageLoading();

	$: id = currentRoute.namedParams.id;
	$: parkList = $parks.sort(sortItems);

	const initPark = () => ({
		name: '',
	});

	let park = initPark();

	onMount(async () => {
		params = {};
		parks.reset();
		try {
			if ($cities.length > 0) {
				city = $cities.find(item => item.id === Number(id));
			}

			if (city) {
				parks.load(city.parks);
				widget.loading = false;
			}

			city = await cityService.loadItem({
				id,
				target: { name: 'park', store: parks },
			});

			if (!park.city_id) {
				park.city_id = city.id;
			}

			widget = unsetLoading();
		} catch (err) {
			await showMessage({
				icon: 'error',
				text: 'Resource not found',
			});
			return navigateToRoute('/admin/cities');
		}
	});

	const handleEditClick = (item) => {
		park = item;
		showParkForm = true;
	};

	const handleReset = () => {
		park = { ...initPark(), city_id: city.id };
		showParkForm = false;
	};
</script>

	<div class="row">
		<div class="col-10 top-margin">
			<h3 class="page-title">{city ? `${city.name}, ${city.country} ` : 'City' }</h3>
		</div>
		<div class="col-2">
			{#if (!widget.loading)}
				<p class="text-right">
					{#if (!showParkForm)}
						<Navigate to="/admin/cities">
							<span class="btn btn-secondary top-margin">
								<i class="fa fa-arrow-left"></i>
							</span>
						</Navigate>
					{:else}
						<button class="btn btn-secondary top-margin" on:click={handleReset}>
							<i class="fa fa-times"></i>
						</button>
					{/if}
				</p>
			{/if}
		</div>
	</div>

	{#if (!widget.loading && city && city.id)}
		<div class="row">
			<div class="col-8 col-sm-10">
				<h4 class="subheading top-margin">
					<i class="fa fa-subway"></i>
					Parks and Landmarks
				</h4>
			</div>
			<div class="col-4 col-sm-2">
				<p class="text-right">
					{#if (!showParkForm)}
						<button class="btn btn-primary" on:click={() => showParkForm = true}>
							<i class="fa fa-plus"></i> New
						</button>
					{/if}
				</p>
			</div>
		</div>

		{#if (showParkForm)}
			<ParkForm {park} on:resetPark={handleReset}/>
		{/if}

		{#if (!widget.loadingItems)}
			<div class="row">
				<div class="col-12">
					<div class="table-responsive">
						<table class="table table-hover">
							<tbody>
								{#if (parkList.length > 0)}
									{#each parkList as item (item)}
										<tr>
											<td>{item.name}</td>
											<td class="text-right">
												{#if (!showParkForm)}
													<button class="btn btn-primary" on:click={() => handleEditClick(item)}>
														<i class="fa fa-edit"></i>
													</button>
													<button class="btn btn-danger"
														on:click={() => parkService.delete(item)}>
														<i class="fa fa-trash"></i>
													</button>
												{/if}
											</td>
										</tr>
									{/each}
								{:else}
								<tr>
									<td colspan="2">No item added to this city.</td>
								</tr>
								{/if}
							</tbody>
						</table>
					</div>
				</div>
			</div>
		{:else}
			<LoadingIndicator />
		{/if}
	{/if}

