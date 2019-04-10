<template>
    <div>
        <div class="form-inline">
            <div class="input-group col">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        Start Date
                    </div>
                </div>
                <el-date-picker v-model="start" type="date" placeholder="Start date" default-value="2019-01-01">
                </el-date-picker>
            </div>
            <div class="input-group col">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        End Date
                    </div>
                </div>
                <el-date-picker v-model="end" type="date" placeholder="End date" default-value="2019-01-01">
                </el-date-picker>
            </div>
            <div class="input-group col">
                <div class="input-group-prepend">
                    <label class="input-group-text" for="perPage">
                        Results per page:
                    </label>
                </div>
                <input id="perPage" name="perPage" v-model="perPage" type="number" class="form-control"/>
            </div>
            <div class="input-group col-1">
                <button @click="getAvailabilities()" class="btn btn-primary">Update</button>
            </div>
        </div>
        <br/>
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
                perPage: {},
                page: {},
                start: {},
                end: {},
            }
        },
        props: {
            doctorId: Number,
            clinicId: Number,
        },
        methods: {
            getAvailabilities: function () {
                console.debug('getAvail called');
                let params = {};
                params.per_page = this.perPage;
                params.page = this.page;
                params.start = this.start;
                params.end = this.end;
                if (this.doctorId) params.doctor_id = this.doctorId;
                if (this.clinicId) params.clinic_id = this.clinicId;
                axios.get('/api/availability', {params: params})
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
                console.debug('deleteAvail called');
                axios.delete('/api/availability/' + id).catch(error => {
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
        created() {
            this.perPage = 50;
            this.page = 1;
            this.start = moment().format('YYYY-MM-DD');
            this.end = moment().add(4, 'weeks').format('YYYY-MM-DD');
        },
        mounted() {
            this.getAvailabilities();
        }
    }
</script>

<style scoped>

</style>
