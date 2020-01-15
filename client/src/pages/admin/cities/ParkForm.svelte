<script>
	import { beforeUpdate, createEventDispatcher } from 'svelte';
	import { validateName } from '../../../utils/validation';
	import { parkService } from '../../../services/admin';
	import TextInput from '../../../components/TextInput.svelte';
	import { parks } from '../../../stores/admin';

	const dispatch = createEventDispatcher();

	export let park;

	let nameError;

	beforeUpdate(async () => {
		if (nameError && park.name.length >= 2) {
			nameError = null;
		}
	});

	const resetPark = () => {
		dispatch('resetPark', {
			park: {
				name: '',
				city_id: park.city_id,
			}
		});
	};

	const cancelEdit = () => {
		resetPark();
	};

	const submitForm = async (event) => {
		const errors = validateName(park.name);
		if (errors) {
			return nameError = errors.name;
		}

		await parkService.save({
			item: park,
			cb: resetPark
		});
	};
</script>

<style>
	.space-left {
		margin-left: 5px !important;
	}
</style>

<div class="top-margin"></div>
<div class="row">
	<div class="col-12">
		<form on:submit|preventDefault={submitForm} >
			<div class="row">
				<div class="col-sm-4">
					<TextInput
						bind:inputValue={park.name}
						error={nameError}
						placeholderText='Enter Park or Landmark Name' />
				</div>

				<div class="col-sm-3">
					<div class="form-group">
						<button class="btn btn-primary">Save</button>
						<button type="button" class="btn btn-danger space-left" on:click={cancelEdit}>Cancel</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<div class="top-margin"></div>
