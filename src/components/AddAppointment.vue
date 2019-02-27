<template>
    <div id="appointments">
        <div class="input">
            <input v-model="patient_id" placeholder="Patient ID">
            <p>Patient ID is: {{ patient_id }}</p>
        </div>

        <div class="input">
            <input v-model="doctor_id" placeholder="Doctor ID">
            <p>Doctor ID is: {{ doctor_id }}</p>
        </div>

        <div class="input">
            <input v-model="room_id" placeholder="Room ID">
            <p>Room ID is: {{ room_id }}</p>
        </div>

        <div class="input">
            <input v-model="date" placeholder="Date">
            <p>Date is: {{ date }}</p>
        </div>
        <div class="input">
            <input v-model="time" placeholder="Time">
            <p>Time is: {{ time }}</p>
        </div>

        <div class="input">
            <button v-on:click="addAppointment()">Add appointment</button>
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
                date: "",
                time: ""
            }
        },
        mounted() {
        },

        methods: {
            addAppointment: function() {
                axios.post('http://127.0.0.1:5000/addAppointment', {
                    patient_id: this.patient_id,
                    doctor_id: this.doctor_id,
                    room_id: this.room_id,
                    date: this.date,
                    time: this.time
                }, {'Content-Type': 'application/json; charset=utf-8',
                    'Access-Control-Allow-Origin': '*'}).then(response => {
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

<style>
    .input {
        display: inline-block;
        padding: 10px;
    }
</style>

