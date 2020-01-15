<script>
	import { onMount, afterUpdate } from 'svelte';
	import { Navigate } from 'svelte-router-spa';
 	import {
		terminalService,
		routeService,
	} from '../../../services/admin';
	import { navigateToRoute, pageLoading, unsetLoading } from '../../../utils/helpers';
	import { terminals, busRoutes } from '../../../stores/admin';
	import { showMessage } from '../../../utils/alertHelpers';
	import { routeSort } from '../../../utils/helpers';
	import RouteForm from '../busroutes/RouteForm.svelte';
	import LoadingIndicator from '../../../components/LoadingIndicator.svelte';

	export let currentRoute;
	export let params = {};
	let widget = pageLoading();
	let terminal;

	$: id = currentRoute.namedParams.id;
	$: terminalId = currentRoute.namedParams.terminalId;
	$: routeList = $busRoutes.sort(routeSort);
	$: page = currentRoute.name !== `/admin/companies/${id}/terminals/${terminalId}/index` ? currentRoute.name : undefined;
	$: formTitle = page && page === `/admin/companies/${id}/terminals/${terminalId}/routes` ? 'Add' : 'Edit';
	$: routeId = page ? currentRoute.namedParams.routeId : undefined;

	$: showForm = !!(page);

	onMount(async () => {
		params = {};
		busRoutes.reset();

		if ($terminals.length > 0) {
			terminal = $terminals.find(item => item.id === Number(id));
		}

		if (terminal) {
			widget.loading = false;
		}

		try {
			terminal = await terminalService.loadItem({
				id: terminalId,
				target: { name: 'route', store: busRoutes }
			});

			widget = unsetLoading();
		} catch (err) {
			await showMessage({
				icon: 'error',
				text: 'Resource not found',
			});
			return navigateToRoute(`/admin/companies/${id}/terminals/${terminalId}`);
		}
	});
</script>

<style></style>

{#if (!widget.loading && terminal && terminal.id)}
	<div class="row">
		<div class="col-8 col-sm-10 top-margin">
			<h3 class="page-title">
				{#if (page)}
					Routes
				{:else}
					{terminal.company ? terminal.company.name : 'Terminal Details'}
				{/if}
			</h3>
		</div>
		<div class="col-4 col-sm-2">
			<p class="text-right">
				{#if (!page)}
					<Navigate to="{`/admin/companies/${id}`}">
						<span class="btn btn-secondary medium-top-margin">
							<i class="fa fa-arrow-left"></i>
						</span>
					</Navigate>
				{:else}
					<Navigate to="{`/admin/companies/${id}/terminals/${terminalId}`}">
						<span class="btn btn-secondary medium-top-margin">
							<i class="fa fa-close"></i>
						</span>
					</Navigate>
				{/if}
			</p>
		</div>
	</div>
	{#if (!widget.loadingItems)}
		<div class="row">
			<div class="col-12">
				<h4 class="subheading medium-top-margin">
					{terminal.name} Terminal
				</h4>
				<address>
					<i>{terminal.address}, {terminal.city.name}.</i> <br/>
					<i class="fa fa-phone"></i> <i>{terminal.phone}</i>
				</address>
			</div>
		</div>
		{#if (showForm)}
			<RouteForm {id} {terminalId} {routeId} />
		{:else}
			<div class="row">
				<div class="col-8 col-sm-10">
					<h4 class="subheading top-margin">
						<i class="fa fa-rocket"></i>
						{#if (page)}
							{formTitle} {terminal.city.name} Route
						{:else}
							<b>{terminal.city.name}</b> Routes
						{/if}
					</h4>
				</div>
				<div class="col-4 col-sm-2">
					<p class="text-right">
						{#if (!showForm)}
							<Navigate to="{`/admin/companies/${id}/terminals/${terminalId}/routes`}">
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
						<table class={`table ${routeList.length > 0 ? 'table-hover' : ''}`}>
							<thead class="thead-light">
								<tr>
									<th>To</th>
									<th>Vehicle Types</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								{#if (routeList.length > 0)}
									{#each routeList as item}
										<tr>
											<td>{item.destination.name}</td>
											<td>{item.fares.length}</td>
											<td class="text-right">
												{#if (!page)}
													<Navigate to={`/admin/companies/${id}/terminals/${terminalId}/routes/${item.id}/edit`}>
														<span class="btn btn-primary"><i class="fa fa-edit"></i></span>
													</Navigate>
													<button class="btn btn-danger" on:click={() => routeService.delete(item)}>
														<i class="fa fa-trash"></i>
													</button>
												{/if}
											</td>
										</tr>
									{/each}
								{:else}
									<tr>
										<td colspan="3">No routes found.</td>
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


