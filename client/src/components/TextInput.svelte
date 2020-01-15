<script>
	export let inputValue;
	export let label = null;
	export let error;
	export let placeholderText = '';
</script>

<div class="form-group">
	{#if (label)}
		<label for="name" class="form-control-label">{label}</label>
	{/if}
	<input type="text" bind:value={inputValue} required class="form-control {error? 'is-invalid' : ''}" placeholder={placeholderText}>
	{#if (error)}
		<div class="invalid-feedback">{error}</div>
	{/if}
</div>
