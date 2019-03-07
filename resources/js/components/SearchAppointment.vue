<template>
    <div>
        Select Date of Appointment
        <datepicker v-on:selected=getAvailabilities placeholder="Select Date" format="yyyy-MM-dd"></datepicker>
        <br />
        <Table :values="doctors">
            <thead>
            <tr>
                <th scope="col" style="text-align: left; width: 10rem;">
                    Doctor
                </th>
                <th scope="col" style="text-align: left;">
                    Availabilities
                </th>
            </tr>
            </thead>
            <tbody slot="body" slot-scope="sort">
            <tr v-for="doctor in availabilities" v-bind:class="availabilities">
                <td>{{ doctor.first_name }} {{ doctor.last_name }}</td>
                <td style="float: left;" v-for="availability in filterAvailabilities(doctor.permit_number)" :key="availability.id">
                    <button>{{splitDate(availability.date_time)[1]}}</button>
                </td>
            </tr>
            </tbody>
        </Table>
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
                doctors: []
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
                axios.get('/api/availabilitiesByDate', {
                        params: {
                            date: moment(inDate).format('YYYY/MM/DD HH:mm:ss')
                        }
                    }
                )
                    .then(response => {
                        if(response.status == 200) {
                            this.availabilities = response.data.data;
                            console.log("getAvailabilitiesByDate " + response.data.data)
                        } else {
                            console.log("GetAvailabilitiesByDate failed: Response code " + response.status)
                        }
                    })
                    .catch(error => {
                        console.log(error.response)
                    })
            },
            filterAvailabilities: function(permit_number) {
                return this.availabilities.filter(availability => availability.doctor_id === permit_number)
            },
            formatDate: function(date) {
                var d = new Date(date),
                    month = '' + (d.getMonth() + 1),
                    day = '' + d.getDate(),
                    year = d.getFullYear();
                if (month.length < 2) month = '0' + month;
                if (day.length < 2) day = '0' + day;
                return [year, month, day].join('-');
            }
        }
    };
</script>
