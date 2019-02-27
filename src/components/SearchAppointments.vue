<template>
    <div>
        <datepicker v-on:selected=getAvailabilities placeholder="Select Date" format="yyyy-MM-dd"></datepicker>
        </br>
        <SortedTable :values="doctors">
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
                <tr v-for="doctor in sort.values" :key="doctor.permit_number">
                    <td>{{ doctor.first_name }} {{ doctor.last_name }}</td>
                    <td style="float: left;" v-for="availability in filterAvailabilities(doctor.permit_number)" :key="availability.id">
                        <button>{{availability.time}}</button>
                    </td>
                </tr>
            </tbody>
        </SortedTable>
    </div>
</template>

<script>
    import axios from "axios";
    import Datepicker from 'vuejs-datepicker';

export default {
    name: "SearchAppointments",
        data () {
            return {
                doctors: [],
                availabilities: []
            }
        },
        mounted() {
            this.getDoctors();
            this.getAvailabilities();
        },

        components: {
            Datepicker
        },

        methods: {
            getDoctors: function() {
                axios.get('http://127.0.0.1:5000/getDoctors')
                .then(response => {
                    if(response.status == 200) {
                        this.doctors = response.data;
                        console.log("getDoctors " + response)
                    } else {
                        console.log("getDoctors failed: Response code " + response.status)
                    }
                })
            },

            getAvailabilities: function(inDate) {
                axios.post('http://127.0.0.1:5000/getAvailabilities', {
                    date: this.formatDate(inDate)
                })
                .then(response => {
                    if(response.status == 200) {
                        this.availabilities = response.data;
                        console.log("getAvailabilities" + response)
                    } else {
                        console.log("Get availabilities failed: Response code " + response.status)
                    }
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
