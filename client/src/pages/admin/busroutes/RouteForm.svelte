<script>
	import { onMount, afterUpdate } from 'svelte';
	import { Navigate } from 'svelte-router-spa';
 	import { terminalService } from '../../../services/admin';
	import { navigateToRoute } from '../../../utils/helpers';
	import { companies, cities, terminals } from '../../../stores/admin';
	import { confirmAction, showMessage } from '../../../utils/alertHelpers';
	import { sortItems, initTerminal } from '../../../utils/helpers';
	import { validateTerminalRequest } from '../../../utils/validation';
	import TextInput from '../../../components/TextInput.svelte';
	import SelectInput from '../../../components/SelectInput.svelte';

	export let id;
	export let terminalId;

	$: formTitle = terminalId ? 'Edit' : 'Add';

	let terminal = initTerminal(Number(id));
	let parks = [];
	let city = '';
	let park = '';
	let errors;

	$: nameError = errors && errors.name ? errors.name : undefined;
	$: phoneError = errors && errors.phone ? errors.phone : undefined;
	$: addressError = errors && errors.address ? errors.address : undefined;

	$: enableSubmit = (!errors && city && city.id && park && park.id);

	afterUpdate(async () => {
		if (terminalId && !terminal.id && $terminals.length > 0 && $cities.length > 0) {
			terminal = $terminals.find(item => item.id === Number(terminalId)) || initTerminal(Number(id));
			if (terminals.id === '') {
				await showMessage({
					icon: 'error',
					text: 'Resource not found',
				});

				return navigateToRoute(`/admin/companies/${id}`);
			}

			if (terminal.city) {
				city = $cities.find(item => item.id === terminal.city_id);
				parks = city.parks;
			}

			if (terminal.park && parks) {
				park = terminal.park;
			}
		}

		if (city && city.id) {
			parks = city.parks;
		}

		terminal.city = city;
		terminal.park = park;

		terminal.city_id = terminal.city ? city.id : null;
		terminal.park_id = terminal.park ? park.id : null;

		if (errors) {
			errors = validateTerminalRequest(terminal);
		}
	});

  const handleCityChange = () => {
    park = '';
  }

	const submitForm = async (event) => {
		errors = validateTerminalRequest(terminal);

		if (errors) {
			return;
		}

		await terminalService.save({
			page: `/admin/companies/${id}`,
			item: terminal,
		});
	};

</script>

<div class="row">
	<div class="col-8 col-sm-10">
		<h4 class="subheading top-margin">
			<i class="fa fa-bus"></i>
			{formTitle} Terminal
		</h4>
	</div>
</div>

<div class="row top-margin">
	<div class="col-12">
		<form on:submit|preventDefault={submitForm} >
			<div class="row">
				<div class="col-sm-5">
					<TextInput
						label='Name'
						bind:inputValue={terminal.name}
						error={nameError}
						placeholderText='Enter Terminal Name' />
				</div>

				<div class="col-sm-3">
					<TextInput
						label='Phone'
						bind:inputValue={terminal.phone}
						error={phoneError}
						placeholderText='Enter Phone' />
				</div>

				<div class="col-sm-4">
					<SelectInput
						defaultOption={true}
						label='City'
						isObjectList={true},
						bind:selectedValue={city}
						itemList={$cities}
						on:change={handleCityChange} />
				</div>
			</div>

			<div class="row">
				<div class="col-sm-8">
					<TextInput
						label='Address'
						bind:inputValue={terminal.address}
						error={addressError}
						placeholderText='Enter Address' />
				</div>

				<div class="col-sm-4">
					<SelectInput
						defaultOption={true}
						label='Nearest Landmark'
						isObjectList={true},
						bind:selectedValue={park}
						itemList={parks} />
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<div class="form-group">
						<label for=parks>&nbsp;</label> <br/>
						<button class="btn btn-primary" disabled={!enableSubmit}>Save</button>
						<Navigate to="{`/admin/companies/${terminal.company_id}`}">
							<span class="btn btn-danger space-left">
								Cancel
							</span>
						</Navigate>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
