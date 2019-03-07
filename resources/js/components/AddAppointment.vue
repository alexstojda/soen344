<template>
    <div id="appointments">
        <div class="form-group row">
            <label class="col-sm-3 offset-md-1 col-md-3 col-lg-2 col-form-label">Patient ID</label>
            <div class="col-sm-9 col-md-7 col-lg-8">
                <input v-model="patient_id" type="text" class="form-control" placeholder="Patient ID">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-3 offset-md-1 col-md-3 col-lg-2 col-form-label">Doctor ID</label>
            <div class="col-sm-9 col-md-7 col-lg-8">
                <input v-model="doctor_id" type="text" class="form-control" placeholder="Doctor ID">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-3 offset-md-1 col-md-3 col-lg-2 col-form-label">Room ID</label>
            <div class="col-sm-9 col-md-7 col-lg-8">
                <input v-model="room_id" type="text" class="form-control" placeholder="Room ID">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-3 offset-md-1 col-md-3 col-lg-2 col-form-label">Date</label>
            <div class="col-sm-9 col-md-7 col-lg-8">
                <input v-model="date" type="text" class="form-control" placeholder="Date">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-3 offset-md-1 col-md-3 col-lg-2 col-form-label">Time</label>
            <div class="col-sm-9 col-md-7 col-lg-8">
                <input v-model="time" type="text" class="form-control" placeholder="Time">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-3 offset-md-1 col-md-3 col-lg-2 col-form-label">Type</label>
            <div class="col-sm-9 col-md-7 col-lg-8">
                <input v-model="type" type="text" class="form-control" placeholder="Type">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-lg-10 offset-lg-1 col-md-10 offset-md-1 text-right">
                <button v-on:click="addAppointment()" type="button" class="btn btn-success btn-lg">Add Appointment</button>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from "axios";
    export default {
        name: "AddAppointment",
        data () {
            return {
                patient_id: "",
                doctor_id: "",
                room_id: "",
                start: "",
                end: "",
                type: "",
                status: ""
            }
        },
        mounted() {
        },
        methods: {
            addAppointment: function() {
                axios.post('/api/createAnAppointment', {
                    patient_id: this.patient_id,
                    doctor_id: this.doctor_id,
                    room_id: this.room_id,
                    start: this.date + this.time,
                    end: this.date + this.time + 20,
                    type: this.type,
                    status: 'active'
                }).then(response => {
                    if(response.status == 200) {
                        console.log("Added appointment")
                    } else {
                        console.log("Add appointment failed: Response code " + response.status)
                    }
                })
            }
        }
    };
</script>

