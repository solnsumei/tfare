<script>
  import { Navigate } from 'svelte-router-spa';
</script>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <Navigate to="/"><span class="navbar-brand">Travel Easy</span></Navigate>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarColor01">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <Navigate to="/"><span class="nav-link">Home <span class="sr-only">(current)</span></span></Navigate>
        </li>
        <li class="nav-item">
          <Navigate to="/about"><span class="nav-link">About</span></Navigate>
        </li>
        <li class="nav-item">
          <Navigate to="/login"><span class="nav-link">Log in</span></Navigate>
        </li>
      </ul>
    </div>
  </div>
</nav>
