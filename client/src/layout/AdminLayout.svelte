<script>
	import { onMount, afterUpdate } from 'svelte';
	import { Route } from 'svelte-router-spa';
	import { cityService, companyService } from '../services/admin';
	import { cities, companies } from '../stores/admin';

	import { logoutUser, navigateToRoute, setAuthHeader } from '../utils/helpers';

	export let currentRoute;
	export let params = {};

	let toggled = false;
	$: pageName = currentRoute.childRoute.name;

	onMount(async () => {
		try {
			await cityService.loadItems();
			await companyService.loadItems();

		} catch (err) {
			console.log(err);
		}

	});

	const toggleMenu = () => {
		toggled = !toggled;
	}

	const logout = () => {
		logoutUser();
		navigateToRoute('login');
	}
</script>

<style>
	#sidebar-wrapper {
		min-height: 100vh;
		margin-left: -15rem;
		-webkit-transition: margin .25s ease-out;
		-moz-transition: margin .25s ease-out;
		-o-transition: margin .25s ease-out;
		transition: margin .25s ease-out;
	}

	#sidebar-wrapper .sidebar-heading {
		padding: 0.875rem 1.25rem;
		font-size: 1.2rem;
	}

	#sidebar-wrapper .list-group {
		width: 15rem;
	}

	#page-content-wrapper {
		min-width: 100vw;
	}

	:global(.top-margin) {
		margin-top: 15px !important;
	}

	:global(.space-left) {
		margin-left: 5px !important;
	}

	:global(.small-top-margin) {
		margin-top: 5px !important;
	}

	:global(.medium-top-margin) {
		margin-top: 10px !important;
	}

	:global(.swal2-content) {
		font-size: 16px !important;
	}

	:global(.swal2-title) {
		font-size: 20px !important;
	}

	:global(.page-title) {
		font-size: 22px !important;
	}

	:global(.subheading) {
		font-size: 18px !important;
	}

	:global(.table td, .table th) {
    white-space: nowrap !important;
	}



	.toggled #sidebar-wrapper {
		margin-left: 0;
	}

	.selected {
    background-color: #d5d4d4 !important;
	}

	@media (min-width: 768px) {
		#sidebar-wrapper {
			margin-left: 0;
		}

		#page-content-wrapper {
			min-width: 0;
			width: 100%;
		}

		.toggled #sidebar-wrapper {
			margin-left: -15rem;
		}
	}
</style>

<div class="d-flex {toggled ? 'toggled' : ''}" id="wrapper">
  <!-- Sidebar -->
  <div class="bg-light border-right" id="sidebar-wrapper">
		<div class="sidebar-heading">Admin </div>
		<div class="list-group list-group-flush">
			<a href="/admin" class="list-group-item list-group-item-action bg-light {pageName == '/admin/index' ? 'selected': ''}">
				Dashboard
			</a>
			<a href="/admin/cities" class="list-group-item list-group-item-action bg-light {pageName == '/admin/cities' ? 'selected': ''}">
				Cities
			</a>
			<a href="/admin/companies" class="list-group-item list-group-item-action bg-light {pageName == '/admin/companies' ? 'selected': ''}">
				Companies
			</a>
			<button on:click={logout} class="list-group-item list-group-item-action bg-light">
				Logout
			</button>
		</div>
	</div>
  <!-- /#sidebar-wrapper -->

  <!-- Page Content -->
  <div id="page-content-wrapper">
    <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
      <button class="btn btn-secondary" on:click={toggleMenu}>
				<span class="navbar-toggler-icon"></span>
			</button>
    </nav>

		<div class="container-fluid">
			<Route { currentRoute } { params }/>
		</div>
  </div>
  <!-- /#page-content-wrapper -->
</div>
