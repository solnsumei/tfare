<script>
	import { onMount, afterUpdate } from 'svelte';
	import { Navigate } from 'svelte-router-spa';
 	import {
		companyService,
		terminalService
	} from '../../../services/admin';
	import { sortItems, initTerminal, loadDataTable,
		navigateToRoute, pageLoading, unsetLoading } from '../../../utils/helpers';
	import { companies, terminals } from '../../../stores/admin';
	import { showMessage } from '../../../utils/alertHelpers';
	import TerminalForm from '../terminals/TerminalForm.svelte';
	import LoadingIndicator from '../../../components/LoadingIndicator.svelte';

	export let currentRoute;
	export let params = {};
	let widget = pageLoading();
	let company;

	$: id = currentRoute.namedParams.id;
	$: terminalList = $terminals.sort(sortItems);
	$: routeName = currentRoute.name;
	$: page = (routeName !== `/admin/companies/${id}/index`
		&& routeName !== `/admin/companies/${id}/terminals`) ? routeName : undefined;
	$: formTitle = page && page === `/admin/companies/${id}/terminals/create` ? 'Add' : 'Edit';
	$: terminalId = page ? currentRoute.namedParams.terminalId : undefined;

	$: showForm = !!(page);

	onMount(async () => {
		params = {};
		terminals.reset();

		if ($companies.length > 0) {
			company = $companies.find(item => item.id === Number(id));
		}

		if (company) {
			widget.loading = false;
		}

		try {
			company = await companyService.loadItem({
				id,
				target: { name: 'terminal', store: terminals }
			});

			widget = unsetLoading();
		} catch (err) {
			await showMessage({
				icon: 'error',
				text: 'Resource not found',
			});
			return navigateToRoute('/admin/companies');
		}
	});

	afterUpdate(async () => {
		if ($terminals.length > 0) {
			loadDataTable('#terminalId');
		}
	});

</script>

<style>
	img {
		min-height: 60px;
		width: 60px;
		border: 1px solid #ccc;
		margin-bottom: 10px !important;
	}
</style>

<div class="row">
	<div class="col-8 col-sm-10 top-margin">
		<h3 class="page-title">
			{#if (page)}
				Terminals
			{:else}
				Company Details
			{/if}
		</h3>
	</div>
	<div class="col-4 col-sm-2">
		<p class="text-right">
			{#if (!page)}
				<Navigate to="/admin/companies">
					<span class="btn btn-secondary medium-top-margin">
						<i class="fa fa-arrow-left"></i>
					</span>
				</Navigate>
			{:else}
				<Navigate to="{`/admin/companies/${id}`}">
					<span class="btn btn-secondary medium-top-margin">
						<i class="fa fa-close"></i>
					</span>
				</Navigate>
			{/if}
		</p>
	</div>
</div>

{#if (!widget.loading && company && company.id)}
	<div class="row">
		<div class="col-12">
			<h4 class="subheading top-margin">
				<span><img src={company.logoUrl} alt="logo" class="img-thumbnail img-responsive"/></span>
				{company.name}
			</h4>
		</div>
	</div>
	{#if (!widget.loadingItems)}
		{#if (showForm)}
			<TerminalForm {id} {terminalId} />
		{:else}
			<div class="row">
				<div class="col-8 col-sm-10">
					<h4 class="subheading top-margin">
						<i class="fa fa-bus"></i>
						{#if (page)}
							{formTitle} Terminal
						{:else}
							Terminals
						{/if}
					</h4>
				</div>
				<div class="col-4 col-sm-2">
					<p class="text-right">
						{#if (!showForm)}
							<Navigate to="{`/admin/companies/${company.id}/terminals/create`}">
								<span class="btn btn-primary small-top-margin">
									<i class="fa fa-plus"></i> New
								</span>
							</Navigate>
						{/if}
					</p>
				</div>
			</div>
			<div class="row">
				<div class="col-12 top-margin">
					<div class="table-responsive">
						<table id="terminalId" class="table table-hover">
							<thead class="thead-light">
								<tr>
									<th>Name</th>
									<th>Phone</th>
									<th>Address</th>
									<th>City</th>
									<th>Nearest Landmark</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								{#if (terminalList.length > 0)}
									{#each terminalList as item}
										<tr>
											<td>{item.name}</td>
											<td>{item.phone}</td>
											<td><i>{item.address}</i></td>
											<td>{item.city.name}</td>
											<td>{item.park ? item.park.name : '-'}</td>
											<td class="text-right">
												{#if (!page)}
													<Navigate to={`/admin/companies/${company.id}/terminals/${item.id}`}>
														<span class="btn btn-primary"><i class="fa fa-eye"></i></span>
													</Navigate>
													<Navigate to={`/admin/companies/${company.id}/terminals/${item.id}/edit`}>
														<span class="btn btn-primary"><i class="fa fa-edit"></i></span>
													</Navigate>
													<button class="btn btn-danger" on:click={() => terminalService.delete(item)}>
														<i class="fa fa-trash"></i>
													</button>
												{/if}
											</td>
										</tr>
									{/each}
								{:else}
									<tr>
										<td colspan="4">No terminal found.</td>
									</tr>
								{/if}
							</tbody>
						</table>
					</div>
				</div>
			</div>
		{/if}
	{:else}
		<LoadingIndicator />
	{/if}
{/if}


