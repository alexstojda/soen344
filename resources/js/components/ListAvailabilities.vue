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
        <div class="row mb-4">
            <div v-for="row in rows" :key="row.id" class="col-md-3 card mx-auto">
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
                <button v-if="showDelete" v-on:click="deleteAvailability(row.id)" type="button"
                        class="btn btn-danger mb-4">Delete
                </button>
            </div>
        </div>
        <nav v-if="lastPage > 1" aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <li v-if="isValidPage(page-1)" class="page-item">
                    <button class="page-link" @click="goToPage(page-1)" tabindex="-1">Previous</button>
                </li>
                <template v-for="pageNum in pageArray">
                    <li :key="pageNum" v-if="pageNum" v-bind:class="{'page-item':true, 'active':(page === pageNum)}">
                        <button class="page-link" @click="goToPage(pageNum)">{{pageNum}}</button>
                    </li>
                </template>
                <li v-if="isValidPage(page+1)" class="page-item">
                    <button @click="goToPage(page+1)" class="page-link">Next</button>
                </li>
            </ul>
        </nav>
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
                lastPage: {},
                pageArray: {},
            }
        },
        props: {
            doctorId: Number,
            clinicId: Number,
            showDelete: Boolean,
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
                        this.lastPage = response.data.meta.last_page;
                        this.getPages()
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
            },
            isValidPage: function (page) {
                return (page >= 1 && page <= this.lastPage);
            },
            getPages: function () {
                let pages = [];
                if (this.isValidPage(this.page - 2))
                    pages[0] = this.page - 2;
                if (this.isValidPage(this.page - 1))
                    pages[1] = this.page - 1;
                if (this.isValidPage(this.page))
                    pages[2] = this.page;
                if (this.isValidPage(this.page + 1))
                    pages[3] = this.page + 1;
                if (this.isValidPage(this.page + 2))
                    pages[4] = this.page + 2;
                this.pageArray = pages;
            },
            goToPage: function (page) {
                this.page = page;
                this.getAvailabilities();
            }
        },
        created() {
            this.perPage = 25;
            this.page = 1;
            this.start = moment().format('YYYY-MM-DD');
            this.end = moment().add(1, 'years').format('YYYY-MM-DD');
        },
        mounted() {
            this.getAvailabilities();
        }
    }
</script>

<style scoped>

</style>
