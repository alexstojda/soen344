<template>
    <div>
        <div class="input">
            <input v-model="doctor_id" placeholder="Doctor ID">
            <p>Doctor ID is: {{ doctor_id }}</p>
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
            <button v-on:click="addAvailability()">Add Availability</button>
        </div>
    </div>
</template>

<script>
    import axios from "axios";
export default {
    name: "AddAvailability",
        data () {
            return {
                doctor_id: "",
                date: "",
                time: ""
            }
        },
        mounted() {
        },

        methods: {
            addAvailability: function() {
                axios.post('http://127.0.0.1:5000/addAvailability', {
                    doctor_id: this.doctor_id,
                    date: this.date,
                    time: this.time
                }, {'Content-Type': 'application/json; charset=utf-8',
                    'Access-Control-Allow-Origin': '*'}).then(response => {
                    if(response.status == 200) {
                        console.log("Added availability")
                    } else {
                        console.log("Add availability failed: Response code " + response.status)
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

