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
                    <button vertical-align="center">Cancel</button></td>
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
            axios({ method: "GET", "url": "http://127.0.0.1:5000/getAppointments" }).then(result => {
                console.log(result)
                this.appointments = result.data;
            }, error => {
                console.error(error);
            });
         }
};
</script>
