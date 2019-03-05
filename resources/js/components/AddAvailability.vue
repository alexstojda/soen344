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
            <input v-model="startTime" placeholder="StartTime">
            <p>Time is: {{ startTime }}</p>
        </div>

        <div class="input">
            <input v-model="endTime" placeholder="EndTime">
            <p>Time is: {{ endTime }}</p>
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
                startTime: "",
                endTime: ""
            }
        },
        mounted() {
        },
        methods: {
            addAvailability: function() {
                axios.post('api/addAvailability', {
                    doctor_id: this.doctor_id,
                    start: this.date + this.start,
                    end: this.time + this.end
                }).then(response => {
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
