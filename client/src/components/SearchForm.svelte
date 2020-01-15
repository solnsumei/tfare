<script>
  import { afterUpdate, createEventDispatcher } from 'svelte';
	import { getParks, searchBuses } from '../services/public';
	import SelectInput from './SelectInput.svelte';

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
      parks = fromCity.parks;
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
		<SelectInput
			defaultOption={true}
			bind:selectedValue={fromCity}
			isObjectList={true}
			itemList={cities}
			on:change={handleFromCityChange}
			label='From' />
  </div>
  <div class="col-sm-3">
		<SelectInput
			defaultOption={true}
			bind:selectedValue={toCity}
			isObjectList={true}
			itemList={filteredCity}
			label='To' />
  </div>
  <div class="col-sm-3">
	<SelectInput
		defaultOption={true}
		bind:selectedValue={selectedPark}
		isObjectList={true}
		itemList={parks}
		label='Nearest Bus Park' />
  </div>

  <div class="col-sm-3">
    <label for=parks>&nbsp;</label> <br/>
    <button on:click={search} disabled={!enableSubmit} class="btn btn-primary">
      Find Bus
    </button>
  </div>
</div>
