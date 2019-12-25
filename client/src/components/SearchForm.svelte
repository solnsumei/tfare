<script>
  import { afterUpdate, createEventDispatcher } from 'svelte';
  import { getParks, searchBuses } from '../services/api';

  const dispatch = createEventDispatcher();
  
  export let cities;

  let fromCity = '';
  let toCity = '';
  let parks = [];
  let selectedPark = '';

  let enableSubmit = false;

  $: filteredCity = cities.filter(city => city !== fromCity);

  afterUpdate(async () => {
    if (fromCity && fromCity.id) {
      const data = await getParks(fromCity.id);
      parks = [...data.parks];
    }
  });
  
  $: if (fromCity && toCity
    && fromCity.id && toCity.id) {
      enableSubmit = true;
    } else {
      enableSubmit = false;
    }
  
  const handleFromCityChange = () => {
    if (toCity && toCity.id) {
      toCity = '';
    }
    selectedPark = '';
  }

  const search = async () => {
    const data = await searchBuses(fromCity.id, toCity.id, selectedPark.id);
    dispatch('result', {
      busRoutes: data.routes,
    });
  }
</script>

<div class="row">
  <div class="col-sm-3">
    <div class="form-group">
      <label for="from">From</label>
      <select bind:value={fromCity} on:change={handleFromCityChange} class="form-control" id="from">
        <option value=''> -- Select -- </option>
        {#if cities && cities.length > 0}
          {#each cities as city}
            <option value={city}>{city.name}</option>
          {/each}
        {/if}
      </select>
    </div>
  </div>    
  <div class="col-sm-3">
    <div class="form-group">
      <label for=to>To</label>
      <select bind:value={toCity} class="form-control" id="to">
        <option value=''> -- Select -- </option>
        {#if filteredCity && filteredCity.length > 0}
          {#each filteredCity as city}
            <option value={city}>{city.name}</option>
          {/each}
        {/if}
      </select>
    </div>
  </div>
  <div class="col-sm-3">
    <div class="form-group">
      <label for="parks">Nearest Bus Park</label>
      <select bind:value={selectedPark} class="form-control" id="park">
        <option value=''> -- Select -- </option>
        {#if parks && parks.length > 0}
          {#each parks as park}
            <option value={park}>{park.name}</option>
          {/each}
        {/if}
      </select>
    </div>
  </div>

  <div class="col-sm-3">
    <label for=parks>&nbsp;</label> <br/>
    <button on:click={search} disabled={!enableSubmit} class="btn btn-primary">
      Find Bus
    </button>
  </div>    
</div>
