<script>
	import { Route } from 'svelte-router-spa';

	import Navbar from './Navbar.svelte';

	export let currentRoute;
	export let params = {};
</script>

<Navbar />
<div class="container">
  <Route {currentRoute} {params} />
</div>
