<script>
	import { Route } from 'svelte-router-spa';


	export let currentRoute;
	export let params;

	let toggled = false;

	function toggleMenu () {
		toggled = !toggled;
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

	.toggled #sidebar-wrapper {
		margin-left: 0;
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
			<a href="/admin" class="list-group-item list-group-item-action bg-light">Dashboard</a>
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
			<Route { currentRoute }  { params } />
		</div>
  </div>
  <!-- /#page-content-wrapper -->
</div>
