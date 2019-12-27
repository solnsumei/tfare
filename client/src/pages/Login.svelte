<script>
	import { loginUser } from '../services/public';
	import { navigateToRoute } from '../utils/helpers';


  let email = '';
  let password = '';
	let errorMessage;


  const login = async (event) => {
    try {
			const response = await loginUser({ email, password });
      if (errorMessage) {
				errorMessage = null;
				email = '',
				password = '';
			};
			navigateToRoute('admin');
    } catch (err) {
      errorMessage = err.response.data.message;
    }
  }
</script>

<style></style>

<div class="row">
  <div class="col-sm"></div>
  <div class="col-sm">
    <div>&nbsp;</div>
    <form on:submit|preventDefault={login}>
        <fieldset>
          <legend>Please log in</legend>
          {#if errorMessage}
            <p class='text-danger'>** {errorMessage}</p>
          {/if}
          <div class="form-group">
            <label for="email">Email address</label>
            <input bind:value={email} autocomplete="email" type="email" class="form-control" aria-describedby="emailHelp" placeholder="Enter email" required/>
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input bind:value={password} autocomplete="current-password" type="password" class="form-control" placeholder="Password" required/>
          </div>

          <button class="btn btn-primary">Log in</button>
        </fieldset>
    </form>
  </div>
  <div class="col-sm"></div>
</div>
