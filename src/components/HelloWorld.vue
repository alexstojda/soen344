<template>
    <div class="hello">
        <table id="firstTable">
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Date</th>
                </tr>
            </thead>
        <tbody>
            <tr v-for="appointment in appointments">
                <td>{{appointment.patient}}</td>
                <td>{{appointment.doctor}}</td>
                <td>{{appointment.date}}</td>
            </tr>
        </tbody>
    </table>
        <textarea>{{ appointments}} </textarea>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        name: 'HelloWorld',
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

    }
</script>

<style scoped>
    h1, h2 {
        font-weight: normal;
    }

    ul {
        list-style-type: none;
        padding: 0;
    }

    li {
        display: inline-block;
        margin: 0 10px;
    }

    a {
        color: #42b983;
    }

    textarea {
        width: 600px;
        height: 200px;
    }
</style>
