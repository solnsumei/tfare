<script>
	export let isObjectList = false;
	export let itemList;
	export let selectedValue;
	export let label;
	export let defaultOption = false;
</script>

<div class="form-group">
	<label for="{label}" class="form-control-label">{label}</label>
	<select bind:value={selectedValue} class="form-control" on:change>
		{#if (itemList && Array.isArray(itemList) && itemList.length > 0)}
			{#if (defaultOption)}
				<option value=''> -- Select -- </option>
			{/if}
			{#each itemList as item}
				{#if (isObjectList && item.name && item.id)}
					<option value={item} selected={item.id === selectedValue.id}>{item.name}</option>
				{:else}
					<option value={item} selected={item === selectedValue}>{item}</option>
				{/if}
			{/each}
		{:else}
			<option value=''> -- Select -- </option>
		{/if}
	</select>
</div>
