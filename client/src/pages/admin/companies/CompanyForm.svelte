<script>
	import { afterUpdate, beforeUpdate } from 'svelte';
	import { Navigate } from 'svelte-router-spa';
	import { validateName } from '../../../utils/validation';
	import { openUploadWidget } from '../../../utils/uploadHelpers';
	import { navigateToRoute } from '../../../utils/helpers';
	import { showMessage } from '../../../utils/alertHelpers';
	import { companyService } from '../../../services/admin';
	import { companies } from '../../../stores/admin';
	import TextInput from '../../../components/TextInput.svelte';

	export let currentRoute;

	const initCompany = () => ({
		name: '',
		logoUrl: '',
	});

	let company = initCompany();
	$: id = currentRoute.namedParams.id || null;

	let nameError;
	let logoError;
	let isDisabled = false;

	afterUpdate(async () => {
		if (id && $companies.length > 0 && company && !company.id) {
			company = $companies.find(item => item.id === Number(id)) || initCompany();
			if (company.name === '') {
				await showMessage({
					icon: 'error',
					text: 'Resource not found',
				});
				navigateToRoute('/admin/companies');
			}
		}
	});

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
	};

	const resetCompany = () => {
		company = initCompany();
	};

	const submitForm = async (event) => {
		const errors = validateName(company.name);
		if (errors) {
			return nameError = errors.name;
		}

		await companyService.save({
			page: '/admin/companies',
			item: company,
			cb: resetCompany,
		});
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

{#if (!(id && company.name === ''))}
	<div class="row top-margin bottom-margin">
		<div class="col-12">
			<form on:submit|preventDefault={submitForm}>
				<div class="row">
					<div class="col-sm-6">
						<div>
							<img src={company.logoUrl} alt={company.logoUrl} />
							<button class="space-left" type="button" on:click={uploadLogo} disabled={isDisabled}>
							{company && company.logoUrl && company.logoUrl !== '' ? 'Change' : 'Add'} logo</button>
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
							<button disabled={!(company.logoUrl && company.logoUrl !== '' && !nameError && company.name.length > 2)}
								class="btn btn-primary">{ company && company.id ? 'Save' : 'Add Company'}</button>
							{#if (company && company.id)}
								<Navigate to="/admin/companies">
									<span class="btn btn-danger space-left">Cancel</span>
								</Navigate>
							{/if}
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
{/if}

