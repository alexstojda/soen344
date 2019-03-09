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
                <datepicker class="form-control" v-model="date" placeholder="Select Date" format="yyyy-MM-dd"></datepicker>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-3 offset-md-1 col-md-3 col-lg-2 col-form-label">Time</label>
            <div class="col-sm-9 col-md-7 col-lg-8">
                <vue-timepicker v-model="time" placeholder="Set Time"></vue-timepicker>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-3 offset-md-1 col-md-3 col-lg-2 col-form-label">Type</label>
            <div class="col-sm-9 col-md-7 col-lg-8">
                <select v-model="type" class="form-control">
                    <option value="" disabled selected>Select Type</option>
                    <option value="walk-in">Walk-in</option>
                    <option value="annual checkup">Annual Checkup</option>
                </select>
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
    import Datepicker from 'vuejs-datepicker';
    import VueTimepicker from 'vuejs-timepicker';
    export default {
        name: "AddAppointment",
        data () {
            return {
                patient_id: "",
                doctor_id: "",
                room_id: "",
                date: "",
                time: {
                    HH: "",
                    mm: "",
                },
                type: "",
                status: ""
            }
        },
        components: {
            Datepicker,
            VueTimepicker
        },
        mounted() {
        },
        methods: {
            addAppointment: function() {
                axios.post('/api/createAnAppointment', {
                    patient_id: this.patient_id,
                    doctor_id: this.doctor_id,
                    room_id: this.room_id,
                    start: this.setDate(this.date, this.time),
                    end: this.setDate(this.date, this.time, this.type),
                    type: this.type,
                    status: 'active'
                }).then(response => {
                    if(response.status == 200) {
                        console.log("Added appointment")
                    } else {
                        console.log("Add appointment failed: Response code " + response.status)
                    }
                })
            },
            setDate: function(date, time, type=null) {
                let dateMaker = new Date(date);
                dateMaker.setHours(time.HH);
                dateMaker.setMinutes(time.MM);

                if(type.equals("walk-in"))
                {
                    dateMaker.setMinutes( dateMaker.getMinutes() + 20 );
                }
                else if(type.equals("annual checkup"))
                {
                    dateMaker.setHours( dateMaker.getHours() + 1 );
                }

                return date;
            }
        }
    };
</script>

