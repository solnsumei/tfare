<script>
	import { onMount, afterUpdate } from 'svelte';
	import { Navigate } from 'svelte-router-spa';
 	import { companiesApiClient } from '../../../services/admin';
	import { companies } from '../../../stores/admin';
	import { confirmAction, showMessage } from '../../../utils/alertHelpers';
	import { sortItems } from '../../../utils/helpers';
	import CompanyForm from './CompanyForm.svelte';

	$: companyList = $companies.sort(sortItems);

	export let currentRoute;
	export let params;

	$: page = currentRoute.childRoute;
	$: id = page ? page.namedParams.id : null;

	onMount(async () => {
		try {
			const data = await companiesApiClient.fetchItems();
			companies.load(data.companies);
		} catch (err) {
			console.log(err);
		}
	});

	const deleteItem = async (company) => {
		try {
			const response = await confirmAction({
				title: 'Confirm Delete',
				text: `Do you want to delete ${company.name}`
			});

			if (response.value) {
				const data = await companiesApiClient.deleteItem(company.id);
				companies.deleteItem(company.id);

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
	};
</script>

<style>
	.align-text-right {
		text-align: right;
		text-align: end;
	}

</style>

<div class="row">
	<div class="col-10 top-margin">
		{#if (!id && !page)}
			<h3>Companies</h3>
		{:else}
			<h3>{id ? 'Edit' : 'Add'} Company</h3>
		{/if}
	</div>
	<div class="col-2">
		<p class="align-text-right">
			{#if (id || page)}
				<Navigate to="/admin/companies">
					<button class="btn btn-secondary top-margin" type="button">
						<i class="fa fa-close"></i>
					</button>
				</Navigate>
			{:else}
				<Navigate to="/admin/companies/create">
					<button class="btn btn-primary top-margin">
						Add New
					</button>
				</Navigate>
			{/if}
		</p>
	</div>
</div>

{#if (id || page)}
	<CompanyForm {currentRoute} {params} />
{/if}

{#if (!id)}
	<div class="row">
		<div class="col-12">
			<table class="table table-hover">
				<tbody>
					{#if (companyList.length > 0)}
						{#each companyList as item}
							<tr>
								<td>{item.name}</td>
								<td>
									{#if (!page)}
										<Navigate to={`/admin/companies/edit/${item.id}`}>
											<button class="btn btn-primary"><i class="fa fa-edit"></i></button>
										</Navigate>
										<button class="btn btn-danger" on:click={() => deleteItem(item)}>
											<i class="fa fa-trash"></i>
										</button>
									{/if}
								</td>
							</tr>
						{/each}
					{:else}
					<tr>
						<td colspan="2">No companies found</td>
					</tr>
					{/if}
				</tbody>
			</table>
		</div>
	</div>
{/if}

