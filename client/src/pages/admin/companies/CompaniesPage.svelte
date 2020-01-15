<script>
	import { onMount, afterUpdate } from 'svelte';
	import { Navigate } from 'svelte-router-spa';
 	import { companyService } from '../../../services/admin';
	import { companies } from '../../../stores/admin';
	import { sortItems, pageLoading, unsetLoading, loadDataTable } from '../../../utils/helpers';
	import CompanyForm from './CompanyForm.svelte';
	import LoadingIndicator from '../../../components/LoadingIndicator.svelte';

	$: companyList = $companies.sort(sortItems);

	export let currentRoute;
	export let params = {};

	let widget = pageLoading();

	$: page = currentRoute.name !== '/admin/companies' ? currentRoute.name : undefined;
	$: id = page ? currentRoute.namedParams.id : undefined;

	onMount(async () => {
		params = {};
		if ($companies.length > 0) {
			widget.loading = false;
		}
		try {
			await companyService.loadItems();
		} catch (err) {
			console.log(err);
		} finally {
			widget = unsetLoading();
		}
	});

	afterUpdate(async () => {
		if (companyList.length > 0) {
			loadDataTable('#companyId');
		}
	});
</script>

<style>
	img {
		min-height: 50px;
		width: 50px;
		margin-right: 5px;
	}
</style>

<div class="row">
	<div class="col-8 col-sm-10 top-margin">
		{#if (!id && !page)}
			<h3 class="page-title">Companies</h3>
		{:else}
			<h3>{id ? 'Edit' : 'Add'} Company</h3>
		{/if}
	</div>
	<div class="col-4 col-sm-2">
		<p class="text-right">
			{#if (id || page)}
				<Navigate to="/admin/companies">
					<span class="btn btn-secondary top-margin">
						<i class="fa fa-close"></i>
					</span>
				</Navigate>
			{:else}
				<Navigate to="/admin/companies/create">
					<span class="btn btn-primary top-margin">
						Add New
					</span>
				</Navigate>
			{/if}
		</p>
	</div>
</div>

{#if (id || page)}
	<CompanyForm {currentRoute} />
{/if}

{#if (!id)}
	{#if (!widget.loadingItems)}
		<div class="row">
		<div class="col-12">
			<div class="table-responsive">
				<table id="companyId" class="table table-hover">
					<thead class="thead-light">
						<tr>
							<th>Logo</th>
							<th>Name</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						{#if (companyList.length > 0)}
							{#each companyList as item}
								<tr>
									<td>
										<span><img src={item.logoUrl} alt="logo" class="img-thumbnail img-responsive"/></span>
									</td>
									<td>
										{item.name}
									</td>
									<td class="text-right">
										{#if (!page)}
											<Navigate to={`/admin/companies/${item.id}`}>
												<span class="btn btn-primary"><i class="fa fa-eye"></i></span>
											</Navigate>
											<Navigate to={`/admin/companies/${item.id}/edit`}>
												<span class="btn btn-primary"><i class="fa fa-edit"></i></span>
											</Navigate>
											<button class="btn btn-danger"
												on:click={() => companyService.delete(item)}>
												<i class="fa fa-trash"></i>
											</button>
										{/if}
									</td>
								</tr>
							{/each}
						{:else}
						<tr>
							<td colspan="3">No companies found</td>
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

