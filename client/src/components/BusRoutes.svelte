<script>
  import Bus from './Bus.svelte';
  export let routes
</script>

<div class="row">
  {#if routes && routes.length > 0}
    {#each routes as route}
      <div class="col-sm-6">
        <h3>
          {route.terminal.company.name}, <small>{route.terminal.name}</small>
        </h3>
        {#if route.fares && route.fares.length > 0}
          {#each route.fares as bus}
            <Bus {bus} />
          {/each}
        {:else}
          <p>No vehicle available.</p>
        {/if}  
      </div>
    {/each}
  {/if}
</div>
