<script>
	import JQuery from 'jquery';
	window.$ = window.JQuery = JQuery;
  // import '../../node_modules/jquery/dist/jquery.slim.min.js';
  import '../../node_modules/popper.js/dist/popper.min.js';
	import '../../node_modules/bootstrap/dist/js/bootstrap.min.js';
	import '../../node_modules/sweetalert2/dist/sweetalert2.min.css';
	import '../../node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css';
	import datatable from 'datatables.net-bs4';
	import { Router } from 'svelte-router-spa';

	import routes from './routes';

	datatable();
</script>

<Router {routes} />
