<template>
    <div id="appointments">
        <SortedTable :values="appointments">
            <thead>
                <tr>
                    <th scope="col" style="text-align: left; width: 10rem;">
                        <SortLink name="doctor">Doctor ID</SortLink>
                    </th>
                    <th scope="col" style="text-align: left; width: 10rem;">
                        <SortLink name="room">Room ID</SortLink>
                    </th>
                    <th scope="col" style="text-align: left; width: 10rem;">
                        <SortLink name="date">Date</SortLink>
                    </th>
                    <th scope="col" style="text-align: left; width: 10rem;"/>
                </tr>
            </thead>
            <tbody slot="body" slot-scope="sort">
                <tr v-for="appointment in sort.values" :key="appointment.id">
                    <td>{{ appointment.doctor_id }}</td>
                    <td>{{ appointment.room_id }}</td>
                    <td>{{ appointment.date_time }}</td>
                    <td><button vertical-align="center">Modify</button>
                        <button v-on:click="cancelAppointment(appointment.id)" vertical-align="center">Cancel</button></td>
                </tr>
            </tbody>
        </SortedTable>
    </div>
</template>

<script>
    import axios from "axios";

export default {
    name: "ViewAppointments",
        data () {
            return {
                appointments: ""
            }
        },
        mounted() {
            this.getAppointments();
        },

        methods: {
            getAppointments: function() {
                axios.get('http://127.0.0.1:5000/getAppointments')
                .then(response => {
                    if(response.status == 200) {
                        this.appointments = response.data;
                        console.log("getAppointments" + response)
                    } else {
                        console.log("Get appointments failed: Response code " + response.status)
                    }
                })
            },

            cancelAppointment: function(id) {
                console.log("VIEEW APPOINTMSNT" + id)
                axios.post('http://127.0.0.1:5000/cancelAppointment', {
                    id: id
                }, {'Content-Type': 'application/json; charset=utf-8',
                    'Access-Control-Allow-Origin': '*'}).then(response => {
                    if(response.status == 200) {
                        console.log("Cancelled appointment : "+ id)
                        this.getAppointments();
                    } else {
                        console.log("Cancel appointment failed: Response code " + response.status)
                    }
                })
            }
        }
};
</script>
