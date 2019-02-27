<template>
    <div id="appointments">
        <div class="input">
            <input v-model="patient" placeholder="Patient name">
            <p>Patient is: {{ patient }}</p>
        </div>

        <div class="input">
            <input v-model="doctor" placeholder="Doctor's name">
            <p>Doctor is: {{ doctor }}</p>
        </div>

        <div class="input">
            <input v-model="room" placeholder="Room">
            <p>Room is: {{ room }}</p>
        </div>

        <div class="input">
            <input v-model="date" placeholder="Date">
            <p>Date is: {{ date }}</p>
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
                patient: "",
                doctor: "",
                room: "",
                date: ""
            }
        },
        mounted() {
        },

        methods: {
            addAppointment: function() {
                axios.post('http://127.0.0.1:5000/addAppointment', {
                    patient: this.patient,
                    doctor: this.doctor,
                    room: this.room,
                    date: this.date
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

