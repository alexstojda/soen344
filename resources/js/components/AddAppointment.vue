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
                <el-date-picker v-model="date" type="date" placeholder="Pick a date" default-value="2019-04-01">
                </el-date-picker>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-3 offset-md-1 col-md-3 col-lg-2 col-form-label">Time</label>
            <div class="col-sm-9 col-md-7 col-lg-8">
                <el-time-select placeholder="Start time" v-model="time"
                                :picker-options="{ start: '08:00',  step: '00:20', end: '20:00'}">
                </el-time-select>
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
    import moment from 'moment';
    export default {
        name: "AddAppointment",
        data () {
            return {
                patient_id: "",
                doctor_id: "",
                room_id: "",
                date: "",
                time: "",
                type: "",
                status: "",
            }
        },
        props: {
          isNurse: Boolean,
        },
        mounted() {
            this.moment = moment;
        },
        methods: {
            addAppointment: function() {
                if(this.isNurse)
                {
                    axios.post('/api/createAnAppointmentNurse', {
                        patient_id: this.patient_id,
                        doctor_id: this.doctor_id,
                        room_id: this.room_id,
                        start: this.setDate(this.date, this.time),
                        end: this.setDateTime(this.date, this.time, this.type),
                        type: this.type,
                        status: 'active'
                    }).then(response => {
                        if (response.status == 200) {
                            console.log("Added appointment")
                        } else {
                            console.log("Add appointment failed: Response code " + response.status)
                        }
                    }).catch(error => {
                        console.log(error.response)
                    })
                }
                else
                {
                    axios.post('/api/createAnAppointment', {
                        patient_id: this.patient_id,
                        doctor_id: this.doctor_id,
                        room_id: this.room_id,
                        start: this.setDate(this.date, this.time),
                        end: this.setDateTime(this.date, this.time, this.type),
                        type: this.type,
                        status: 'cart'
                    }).then(response => {
                        if (response.status == 200) {
                            console.log("Added appointment");
                        } else {
                            console.log("Add appointment failed: Response code " + response.status)
                        }
                    }).catch(error => {
                        console.log(error.response)
                    })
                }
            },
            setDate: function(date, time) {
                let timeSplit = time.split(':');
                console.log(moment(date).add(timeSplit[0], "hours").add(timeSplit[1], "minutes").format("YYYY-MM-DD HH:mm"))
                return moment(date).add(timeSplit[0], "hours").add(timeSplit[1], "minutes").format("YYYY-MM-DD HH:mm");
            },
            setDateTime: function(date, time, type) {
                let timeSplit = time.split(':');
                let dateMaker = moment(date).add(timeSplit[0], "hours").add(timeSplit[1], "minutes");

                if(type === '')
                {
                    return date;
                }
                else if(type === "walk-in")
                {
                    dateMaker.add(20, "minutes");
                }
                else if(type === "annual checkup")
                {
                    dateMaker.add(1, "hours");
                }
                console.log(moment(dateMaker).format("YYYY-MM-DD HH:mm"))

                return moment(dateMaker).format("YYYY-MM-DD HH:mm");
            }
        }
    };
</script>

