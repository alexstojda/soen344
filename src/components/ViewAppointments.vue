<template>
    <div id="appointments" style="width: 75%">
        <SortedTable :values="appointments">
            <thead>
                <tr>
                    <th scope="col" style="text-align: left; width: 10rem;">
                        <SortLink name="doctor">Doctor</SortLink>
                    </th>
                    <th scope="col" style="text-align: left; width: 10rem;">
                        <SortLink name="room">Room</SortLink>
                    </th>
                    <th scope="col" style="text-align: left; width: 10rem;">
                        <SortLink name="date">Date</SortLink>
                    </th>
                    <th scope="col" style="text-align: left; width: 10rem;"/>
                </tr>
            </thead>
            <tbody slot="body" slot-scope="sort">
                <tr v-for="appointment in sort.values" :key="appointment.id">
                    <td>{{ appointment.doctor }}</td>
                    <td>{{ appointment.room }}</td>
                    <td>{{ appointment.date }}</td>
                    <td><button vertical-align="center">Modify</button>
                        <button v-on:click="cancel(appointment.id)" vertical-align="center">Cancel</button></td>
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

            cancel: function(id) {
                axios.post('http://127.0.0.1:5000/cancelAppointment', {
                    appointment_id: id
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
