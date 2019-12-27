<script>
  import '../../node_modules/jquery/dist/jquery.slim.min.js';
  import '../../node_modules/popper.js/dist/popper.min.js';
	import '../../node_modules/bootstrap/dist/js/bootstrap.min.js';
	import '../../node_modules/sweetalert2/dist/sweetalert2.min.css';

  import { Router } from 'svelte-router-spa';
	import routes from './routes';
</script>

<Router {routes} />
