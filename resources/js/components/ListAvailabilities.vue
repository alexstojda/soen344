<template>
    <div class="row">
        <div v-for="row in rows" class="card col-md-3">
            <div class="card-body row">
                <div class="col-6">
                    <b>Start</b>: {{ getTime(row.start) }}<br/>
                    <b>End</b>: {{ getTime(row.end) }}
                </div>
                <div class="col-6">
                    <span v-if="row.is_available" class="badge badge-success">Available</span>
                    <span v-if="row.is_booked" class="badge badge-warning">Booked</span>
                    <span v-if="!row.is_working" class="badge badge-danger">Not Available</span>
                </div>
            </div>
            <button v-on:click="deleteAvailability(row.id)" type="button" class="btn btn-danger">Delete
            </button>
            <br>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import moment from 'moment';

    export default {
        name: "ListAvailabilities",
        data() {
            return {
                rows: {},
            }
        },
        props: {
            doctorId: Number,
        },
        methods: {
            getAvailabilities: function () {
                var perPage = 100;
                var today = moment().format('YYYY-MM-DD');
                console.log('/api/availability?per_page=' + perPage + '&start=' + today + '&doctor_id=' + this.doctorId);
                axios.get('/api/availability?per_page=' + perPage + '&start=' + today + '&doctor_id=' + this.doctorId)
                    .catch(error => {
                        console.log(error.response.data, {type: 'error'});
                    }).then(response => {
                    if (response.status === 200 || response.status === 201) {
                        console.log(response);
                        this.rows = response.data.data;
                    } else {
                        console.log('Add availability: Response code ' + response.status);
                    }
                });
            },
            getTime: function (str) {
                return moment(str).format('YYYY-MM-DD h:mm')
            },
            deleteAvailability: function (id) {
                axios.delete('/api/availability/'+id).catch(error => {
                    console.log(error.response.data, {type: 'error'});
                }).then(response => {
                    if (response.status === 200 || response.status === 201) {
                        this.getAvailabilities();
                    } else {
                        console.log('Delete availability: Response code ' + response.status);
                    }
                });
            }
        },
        mounted() {
            this.getAvailabilities();
        }
    }
</script>

<style scoped>

</style>
