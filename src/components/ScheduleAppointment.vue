<template>
  <div>
    <div>
      <navbar></navbar>
    </div>
    <div>
        <h1>Schedule an Appointment</h1>
        <searchAppointments id="searchAppointments"></searchAppointments>
    </div>
  </div>
</template>

<script>
    import Navbar from './Navbar.vue';
    import SearchAppointments from './SearchAppointments.vue';
    
    export default {
        components: {
            'navbar': Navbar,
            'searchAppointments' : SearchAppointments
        }
    };
</script>

<style>

h1 {
    text-align: center;
    margin:1%;
}

#searchAppointments {
    width: 75%;
    margin-top: 100px;
    margin-left: auto;
    margin-right: auto;
}
</style>