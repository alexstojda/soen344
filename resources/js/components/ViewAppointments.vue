<template>
    <div id="appointments">
        <SortedTable :values="appointments">
            <thead>
            <tr>
                <th scope="col">
                    <SortLink name="doctor">Doctor ID</SortLink>
                </th>
                <th scope="col" >
                    <SortLink name="room">Room ID</SortLink>
                </th>
                <th scope="col">
                    <SortLink name="status">Status</SortLink>
                </th>
                <th scope="col" >
                    <SortLink name="date">Date</SortLink>
                </th>
                <th scope="col" />
            </tr>
            </thead>
            <tbody slot="body" slot-scope="sort">
            <tr v-for="appointment in sort.values" :key="appointment.id">
                <td>{{ appointment.doctor["name"] }}</td>
                <td>{{ appointment.room["id"] }}</td>
                <td>{{ appointment.status }}</td>
                <td>{{ dateFormatter(appointment.start) }}</td>
                <td><button type="button" class="btn btn-warning" vertical-align="center">Modify</button>
                    <button type="button" class="btn btn-danger" v-on:click="cancelAppointment(appointment.id, appointment.doctor_id)" vertical-align="center">Cancel</button></td>
            </tr>
            </tbody>
        </SortedTable>
    </div>
</template>

<script>
    import axios from "axios";
    import moment from "moment";
    import { SortedTable, SortLink } from "vue-sorted-table";
    export default {
        name: "ViewAppointments",
        data () {
            return {
                appointments: []
            }
        },
        mounted() {
            this.getAppointments();
        },
        methods: {
            getAppointments: function() {
                axios.get('/api/appointments')
                    .then(response => {
                        if(response.status == 200) {
                            this.appointments = response.data.data;
                            console.log("getAppointments" + response)
                        } else {
                            console.log("Get appointments failed: Response code " + response.status)
                        }
                    })
            },
            cancelAppointment: function(id, doctor_id) {
                axios.post('/api/deleteAppointment', {
                    appointment_id: id,
                    doctor_id: doctor_id
                }).then(response => {
                    if(response.status == 200) {
                        console.log("Cancelled appointment : " + id)
                        this.getAppointments();
                    } else {
                        console.log("Cancel appointment failed: Response code " + response.status)
                    }
                })
            },
            dateFormatter: function(date) {
                return moment(date).format("YYYY-MM-DD");
            }
        }
    };
</script>
