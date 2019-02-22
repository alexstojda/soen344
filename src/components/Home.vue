<template>
  <div>
    <div>
      <navbar></navbar>
    </div>
    <div>
        <h1>Your Appointments</h1>
        <viewAppointments id="appointmentTable"></viewAppointments>
        <br/>
        <helloworld></helloworld>
    </div>
  </div>
</template>

<script>
    import Navbar from './Navbar.vue';
    import HelloWorld from './HelloWorld.vue';
    import ViewAppointments from './ViewAppointments.vue';
    
    export default {
        components: {
            'navbar': Navbar,
            'helloworld': HelloWorld,
            'viewAppointments': ViewAppointments,
        }
    };
</script>

<style>

h1 {
    text-align: center;
    margin:1%;
}
#testApi {
    margin-left: auto;
    margin-right: auto;
}

#appointmentTable {
    width: 75%;
    margin-top: 100px;
    margin-left: auto;
    margin-right: auto;
}
</style>