<script>
	import { beforeUpdate, createEventDispatcher } from 'svelte';
	import { validateName } from '../../../utils/validation';
	import { openUploadWidget } from '../../../utils/uploadHelpers';
	import { companiesApiClient } from '../../../services/admin';
	import { showMessage } from '../../../utils/alertHelpers';
	import { companies } from '../../../stores/admin';
	import TextInput from '../../../components/TextInput.svelte';
	// import SelectInput from '../../../components/SelectInput.svelte';

	const dispatch = createEventDispatcher();

	export let company;

	let nameError;
	let logoError;
	let isDisabled = false;


	beforeUpdate(async () => {
		if (nameError && company.name.length >= 2) {
			nameError = null;
		}
		if (logoError) {
			logoError = null;
		}
	});

	const uploadLogo = () => {
		isDisabled = true;
		openUploadWidget((error, result) => {
			if (error) {
				logoError = error.statusText;
			}

			if (!error && result && result.event === 'success') {
				company.logoUrl = result.info.secure_url;
			}

			isDisabled = false;

		}).open();
	}

	const resetCompany = () => {
		dispatch('resetCompany', {
			company: {
				name: '',
				logoUrl: '',
			}
		});
	};

	const cancelEdit = () => {
		resetCompany();
	};

	const submitForm = async (event) => {
		const errors = validateName(company.name);
		if (errors) {
			return nameError = errors.name;
		}

		try {
			const data = await companiesApiClient.saveItem(company);
			companies.updateItem(data.company);
			resetCompany();
			showMessage({
				icon: 'success',
				text: data.message,
			});
		} catch (err) {
			showMessage({
				icon: 'error',
				text: err.response.data.message,
			});
		}
	};
</script>

<style>
	.space-left {
		margin-left: 5px !important;
	}

	.bottom-margin {
		margin-bottom: 15px !important;
	}

	img {
		min-height: 100px;
		width: 100px;
		border: 1px solid #ccc;
		margin-bottom: 15px !important;
	}
</style>
<div class="row top-margin bottom-margin">
	<div class="col-12">
		<form on:submit|preventDefault={submitForm}>
			<div class="row">
				<div class="col-sm-6">
					<div>
						<img src={company.logoUrl} alt={company.logoUrl} />
						<button class="space-left" type="button" on:click={uploadLogo} disabled={isDisabled}>
						{company.logoUrl && company.logoUrl !== '' ? 'Change' : 'Add'} logo</button>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4">
					<TextInput
						label='Company Name'
						bind:inputValue={company.name}
						error={nameError}
						placeholderText='Enter Name' />
				</div>

				<div class="col-sm-3">
					<div class="form-group">
						<label for=parks>&nbsp;</label> <br/>
						<button disabled={!(company.logoUrl && company.logoUrl !== '')} class="btn btn-primary">{ company && company.id ? 'Save' : 'Add Company'}</button>
						{#if (company.id)}
							<button type="button" class="btn btn-danger space-left" on:click={cancelEdit}>Cancel</button>
						{/if}
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
