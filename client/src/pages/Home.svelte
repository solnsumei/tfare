<script>
  import { onMount, afterUpdate } from 'svelte';
  import { getCities, getParks, searchBuses } from '../services/public';
  import SearchForm from '../components/SearchForm.svelte';
  import BusRoutes from '../components/BusRoutes.svelte';

  let cities = [];
  let busRoutes = [];

  onMount(async () => {
    const data = await getCities();
    cities = [...data.cities];
  });

  const handleResult = (event) => {
    busRoutes = event.detail.busRoutes;
  };

</script>

<style>
  .centered {
    text-align: center;
  }
</style>

<div class="row">
  <div>&nbsp;</div>
  <div class="jumbotron col-12">
    <h2 class="display-4 centered">Travelling by bus?</h2>
    <p class="lead centered">See best bus prices here to help you make better decisions.</p>
    <hr class="my-4">
    <SearchForm {cities} on:result={handleResult}/>
  </div>
</div>
<BusRoutes routes={busRoutes} />
