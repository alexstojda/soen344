<template>
    <div>
        <table align="center" border="1">
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
    <br/>
        <textarea id="testTextArea">{{ appointments}} </textarea>
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

<style>
    #testTextArea {
        display: block;
        width: 50%;
        height: 200px;
        margin: auto;
    }
</style>
