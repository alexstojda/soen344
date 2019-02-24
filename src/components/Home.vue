<template>
  <div>
    <div>
      <navbar></navbar>
    </div>
    <div>
        <h1>Your Appointments</h1>
        <viewAppointments id="appointmentTable"></viewAppointments>
        <br/>
        <addAppointment id="addAppointment"></addAppointment>
        <br/>
    </div>
  </div>
</template>

<script>
    import Navbar from './Navbar.vue';
    import ViewAppointments from './ViewAppointments.vue';
    import AddAppointment from './AddAppointment.vue';
    
    export default {
        components: {
            'navbar': Navbar,
            'viewAppointments': ViewAppointments,
            'addAppointment' : AddAppointment
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

#addAppointment {
    width: 75%;
    margin-top: 100px;
    margin-left: auto;
    margin-right: auto;
}
</style>