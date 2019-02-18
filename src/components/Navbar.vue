<template>
  <div>

    <nav class="navbar navbar-dark bg-dark">
      <a class="navbar-brand" href="#">Bon Matin</a>

      <button class="navbar-toggler align-right" type="button" data-toggle="collapse" data-target="#navbarsExample01" aria-controls="navbarsExample01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarsExample01">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Schedule an Appointment</a>
          </li>
          <li class="nav-item" href="#">
            <cart></cart>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="http://example.com" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Username</a>
            <div class="dropdown-menu" aria-labelledby="dropdown01">
              <a class="dropdown-item" href="#">Appointments</a>
              <a class="dropdown-item" href="#">Schedule</a>
              <a class="dropdown-item" href="#">Profile</a>
            </div>
          </li>
        </ul>
      </div>
    </nav>

  </div>
</template>

<script>
  import Cart from './Cart.vue';
  export default {
    components: {
      'cart': Cart
    }
  };
</script>
