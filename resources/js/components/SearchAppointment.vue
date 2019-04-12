<template>
    <div>
        <div class="form-group row">
            <label class="col-sm-3 offset-md-1 col-md-3 col-lg-2 col-form-label">Select Date</label>
            <div class="col-sm-9 col-md-7 col-lg-8">
                <datepicker v-on:selected=getAvailabilities class="form-control" placeholder="Select Date" format="yyyy-MM-dd"></datepicker>
            </div>
        </div>
        <br />
        <table v-if="notEmpty">
            <thead>
            <tr>
                <th scope="col" style="text-align: left; width: 10rem;">
                    Doctor
                </th>
                <th scope="col" style="text-align: left; width: 10rem;">
                    Doctor Id
                </th>
                <th scope="col" style="text-align: left;">
                    Availabilities
                </th>
            </tr>
            </thead>
            <tbody slot="body">
                <tr v-for="availability in availabilities" v-bind:key="availabilities">
                    <td>{{ availability.doctor["name"]}}</td>
                    <td>{{ availability.doctor["id"]}}</td>
                    <td>{{ dateFormatter(availability.start) }} to {{ dateFormatter(availability.end) }}</td>
                </tr>
            </tbody>
        </table>

        <div align="center" v-if="empty">
            No Results
        </div>

        <br/>

        <table v-if="notEmpty">
            <thead>
            <tr>
                <th scope="col" style="text-align: left; width: 10rem;">
                    Doctor
                </th>
                <th scope="col" style="text-align: left; width: 10rem;">
                    Booked Appointment On Selected Date
                </th>
            </tr>
            </thead>
            <tbody slot="body">
            <tr v-for="doctor in doctors">
                <td>{{ doctor.doctor['name']}}</td>
                <td>{{ dateFormatter(doctor.start) }} to {{ dateFormatter(doctor.end) }}</td>
            </tr>
            </tbody>
        </table>

    </div>
</template>

<script>
    import axios from "axios";
    import moment from 'moment'
    import { SortedTable, SortLink } from "vue-sorted-table";
    import Datepicker from 'vuejs-datepicker';
    export default {
        name: "SearchAppointments",
        data () {
            return {
                availabilities: [],
                doctors: [],
                empty: false,
                notEmpty: false,
            }
        },
        components: {
            Datepicker
        },
        methods: {
            splitDate: function(dateString) {
                var dateTime = dateString.split(" ");
                var date = dateTime[0] + dateTime[1] + dateTime[2] + dateTime[3];
                var timeParts = dateTime[4].split(":");
                var time = timeParts[0] + ":" + timeParts[1];
                return [date, time];
            },
            getAvailabilities: function(inDate) {
                axios.get('/api/availability?date=' + moment(inDate).format('YYYY-MM-DD'))
                    .then(response => {
                        if(response.status == 200) {
                            this.availabilities = response.data.data;
                            if(this.availabilities.length == 0)
                            {
                                this.empty = true;
                                this.notEmpty = false;
                            }
                            else
                            {
                                this.doctorAvalabilities(inDate);
                                this.empty = false;
                                this.notEmpty = true;
                            }

                            this.$forceUpdate();
                            console.log("getAvailabilitiesByDate " + response.data.data)
                        } else {
                            console.log("GetAvailabilitiesByDate failed: Response code " + response.status)
                        }
                    })
                    .catch(error => {
                        console.log(error.response)
                    })
            },
            formatDate: function(date) {
                var d = new Date(date),
                    month = '' + (d.getMonth() + 1),
                    day = '' + d.getDate(),
                    year = d.getFullYear();
                if (month.length < 2) month = '0' + month;
                if (day.length < 2) day = '0' + day;
                return [year, month, day].join('-');
            },
            dateFormatter: function(date) {
                return moment(date).format("YYYY-MM-DD HH:mm");
            },
            doctorAvalabilities: function(date) {
                axios.get('/api/appointment?start=' + moment(date).format("YYYY-MM-DD"))
                    .then(response => {
                    if (response.status == 200) {
                        console.log("Appointments for : " + date)
                        console.log(response.data.data)
                        this.doctors = response.data.data;
                    } else {
                        console.log("Cancel appointment failed: Response code " + response.status)
                    }
                })
            }
        }
    };
</script>
