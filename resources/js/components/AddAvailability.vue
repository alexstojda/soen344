<template>
    <div>
        <div class="form-group row">
            <label class="col-sm-3 offset-md-1 col-md-3 col-lg-2 col-form-label">Doctor ID</label>
            <div class="col-sm-9 col-md-7 col-lg-8">
                <input v-model="doctor_id" type="text" class="form-control" placeholder="Doctor ID">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 offset-md-1 col-md-3 col-lg-2 col-form-label">Date</label>
            <div class="col-sm-9 col-md-7 col-lg-8">
                <datepicker class="form-control" v-model="date" placeholder="Select Date" format="yyyy-MM-dd"></datepicker>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 offset-md-1 col-md-3 col-lg-2 col-form-label">Start Time</label>
            <div class="col-sm-9 col-md-7 col-lg-8">
                <input v-model="startTime" type="text" class="form-control" placeholder="Start Time">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 offset-md-1 col-md-3 col-lg-2 col-form-label">End Time</label>
            <div class="col-sm-9 col-md-7 col-lg-8">
                <input v-model="endTime" type="text" class="form-control" placeholder="End Time">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-lg-10 offset-lg-1 col-md-10 offset-md-1 text-right">
                <button v-on:click="addAvailability()" type="button" class="btn btn-success btn-lg">Add Availability</button>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from "axios";
    import Datepicker from 'vuejs-datepicker';
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
        components: {
            Datepicker
        },
        mounted() {
        },
        methods: {
            addAvailability: function() {
                axios.post('api/addAvailability', {
                    doctor_id: this.doctor_id,
                    start: this.date + this.startTime,
                    end: this.date + this.endTime
                }).then(response => {
                    if(response.status == 200 || response.status == 201) {
                        console.log("Added availability")
                        console.log(response);
                    } else {
                        console.log("Add availability failed: Response code " + response.status)
                    }
                })
            }
        }
    };
</script>
