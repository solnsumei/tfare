<script>
	import { onMount } from 'svelte';
 	import { companiesApiClient } from '../../../services/admin';
	import { companies } from '../../../stores/admin';
	import { confirmAction, showMessage } from '../../../utils/alertHelpers';
	import { sortItems } from '../../../utils/helpers';
	import CompanyForm from './CompanyForm.svelte';


	$: companyList = $companies.sort(sortItems);

	const initCompany = () => ({
		name: '',
		logoUrl: '',
	});

	let company = initCompany();

	let editing = false;

	const setCompany = (item) => {
		company = item;
		editing = true;
	};

	const handleAddReset = (value) => {
		company = initCompany();
		editing = value;
	};

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

	const handleReset = (event) => {
		company = event.detail.company;
		editing = false;
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
		{#if (!editing)}
			<h3>Companies</h3>
		{:else}
			<h3>{company.id ? 'Edit' : 'Add'} Company</h3>
		{/if}
	</div>
	<div class="col-2">
		<p class="align-text-right">
			{#if (editing)}
				<button on:click={() => handleAddReset(false)} class="btn btn-secondary top-margin">
					<i class="fa fa-close"></i>
				</button>
			{:else}
				<button on:click={() => handleAddReset(true)} class="btn btn-primary top-margin">
					Add New
				</button>
			{/if}
		</p>
	</div>
</div>

{#if (editing)}
	<CompanyForm {company} on:resetCompany={handleReset}/>
{/if}

{#if (!company.id)}
	<div class="row">
		<div class="col-12">
			<table class="table table-hover">
				<tbody>
					{#if (companyList.length > 0)}
						{#each companyList as item}
							<tr>
								<td>{item.name}</td>
								<td>
									{#if (!editing)}
										<button class="btn btn-primary" on:click={() => setCompany(item)}><i class="fa fa-edit"></i></button>
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

