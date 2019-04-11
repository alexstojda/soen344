<template>
    <div>
        <div class="form-inline">
            <div class="input-group col-4">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        Start Date
                    </div>
                </div>
                <el-date-picker v-model="start" type="date" placeholder="Start date" default-value="2019-01-01">
                </el-date-picker>
            </div>
            <div class="input-group col-4">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        End Date
                    </div>
                </div>
                <el-date-picker v-model="end" type="date" placeholder="End date" default-value="2019-01-01">
                </el-date-picker>
            </div>
            <div class="input-group col-3">
                <div class="input-group-prepend">
                    <label class="input-group-text" for="perPage">
                        Results per page:
                    </label>
                </div>
                <input id="perPage" name="perPage" v-model="perPage" type="number" class="form-control"/>
            </div>
            <div class="input-group col-4 mt-3">
                <div class="input-group-prepend">
                    <label class="input-group-text">
                        Doctor:
                    </label>
                </div>
                <div>
                    <model-select class="m-0" :options="doctorsSelectList"
                                  v-model="selectedDoctor"
                                  placeholder="Select Doctor">
                    </model-select>
                </div>
            </div>
            <div class="input-group col-1">
                <button @click="getAvailabilities()" class="btn btn-primary">Update</button>
            </div>
        </div>
        <br/>
        <div class="row mb-4">
            <div v-for="row in rows" :key="row.id" class="col-md-3 card mx-auto">
                <div class="card-body">
                    <h3 v-if="!doctorId">{{row.doctor.name}}</h3>
                    <div class="row">
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
                </div>
                <button v-if="showDelete" v-on:click="deleteAvailability(row.id)" type="button"
                        class="btn btn-danger mb-4">Delete
                </button>
                <button v-if="showCreateAppointment" v-on:click="openAppointmentModal(row)" type="button"
                        class="btn btn-success mb-4">Book
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
        <modal name="newAppointment"
               @before-open="beforeOpenNewAppointment"
               :height="'auto'">
            <div class="p-4">
                <h3>New Appointment</h3>
                <div class="form-group row">
                    <label for="staticName" class="col-sm-2 col-form-label">Doctor</label>
                    <div class="col-sm-10">
                        <input type="text" disabled class="form-control-plaintext" id="staticName"
                               :value="newAppointment.row.doctor.name">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">
                        Patient:
                    </label>
                    <div class="col-sm-10">
                        <model-select :options="patientSelectList"
                                      v-model="selectedPatient"
                                      placeholder="Select patient">
                        </model-select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Type</label>
                    <div class="col-sm-10">
                        <select id="appointmentType" v-model="newAppointment.type" class="form-control">
                            <option value="" disabled selected>Select Type</option>
                            <option value="walk-in">Walk-in</option>
                            <option value="checkup">Annual Checkup</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="staticTime" class="col-sm-3 col-form-label">Appointment Time</label>
                    <div class="col-sm-9">
                        <input type="text" disabled class="form-control-plaintext" id="staticTime"
                               :value="this.getTime(newAppointment.row.start)">
                    </div>
                </div>
                <div class="form-group row">
                    <button class="btn btn-success" @click="createAppointment">Book Appointment</button>
                </div>
            </div>
        </modal>
        <modal name="createdAppointment"
               @before-open="beforeOpenCreatedAppointment"
               :height="'auto'"
               @close="afterClosedCreatedAppointment">
            <div class="p-4">
                <h3>Appointment Details</h3>
                <table>
                    <tr>
                        <th>
                            Patient:
                        </th>
                        <td>
                            {{newAppointment.details.patient.name}}
                        </td>
                        <th>
                            Doctor:
                        </th>
                        <td>
                            {{newAppointment.details.doctor.name}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Start:
                        </th>
                        <td>
                            {{newAppointment.details.start}}
                        </td>
                        <th>
                            End:
                        </th>
                        <td>
                            {{newAppointment.details.end}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Type:
                        </th>
                        <td>
                            {{newAppointment.details.type}}
                        </td>
                        <th>
                            Status:
                        </th>
                        <td>
                            {{newAppointment.details.status}}
                        </td>
                    </tr>
                </table>
            </div>
        </modal>
        <v-dialog :height="'auto'" />
    </div>
</template>

<script>
    import {ModelSelect} from 'vue-search-select'
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
                doctorsList: {},
                doctorsSelectList: [],
                selectedDoctor: {},
                newAppointment: {
                    row: {
                        doctor: {},
                    },
                    type: {},
                    details: {
                        doctor: {
                            id: {},
                            name: {},
                        },
                        duration: {},
                        end: {},
                        id: {},
                        patient: {
                            id: {},
                            name: {},
                        },
                        room: {
                            id: {},
                        },
                        start: {},
                        status: {},
                        type: {},
                    }
                },
                time: {},
                patientsList: [],
                patientSelectList: {},
                selectedPatient: {},
            }
        },
        props: {
            doctorId: Number,
            clinicId: Number,
            showDelete: Boolean,
            showCreateAppointment: Boolean,
            showDoctorFilter: Boolean,
        },
        methods: {
            getAvailabilities: function () {
                console.debug('getAvail called');
                let params = {};
                params.per_page = this.perPage;
                params.page = this.page;
                params.start = this.start;
                params.end = this.end;
                if (this.doctorId || this.selectedDoctor.value) params.doctor_id = this.doctorId ? this.doctorId : this.selectedDoctor.value;
                if (this.clinicId) params.clinic_id = this.clinicId;
                axios.get('/api/availability', {params: params})
                    .catch(error => {
                        console.log(error.response.data, {type: 'error'});
                        this.throwDialogModal('Error', error.response);
                    }).then(response => {
                    if (response.status === 200 || response.status === 201) {
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
                axios.delete('/api/availability/' + id).catch(error => {
                    console.log(error.response.data, {type: 'error'});
                    this.throwDialogModal('Error', error.response);
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
            },
            prepareDoctorSelect: function () {
                this.doctorsSelectList = [];
                this.doctorsSelectList.push({value: "", text: "All Doctors"});
                for (let i = 0; i < this.doctorsList.length; i++) {
                    this.doctorsSelectList.push({value: this.doctorsList[i].id, text: this.doctorsList[i].name})
                }
            },
            getDoctors: function () {
                axios.get('/api/doctor')
                    .catch(error => {
                        console.log(error.response.data, {type: 'error'});
                        this.throwDialogModal('Error', error.response);
                    }).then(response => {
                    if (response.status === 200 || response.status === 201) {
                        this.doctorsList = response.data.data;
                        this.prepareDoctorSelect();
                    } else {
                        console.log('get doctors: Response code ' + response.status);
                    }
                });
            },
            openAppointmentModal: function (row) {
                this.$modal.show('newAppointment', {row: row})
            },
            beforeOpenNewAppointment: function (event) {
                this.newAppointment.row = event.params.row;
            },
            preparePatientSelect: function () {
                this.patientSelectList = [];
                for (let i = 0; i < this.patientsList.length; i++) {
                    this.patientSelectList.push({value: this.patientsList[i].id, text: this.patientsList[i].name})
                }
            },
            getPatients: function () {
                axios.get('/api/patient')
                    .catch(error => {
                        console.log(error.response.data, {type: 'error'});
                        this.throwDialogModal('Error', error.response);
                    }).then(response => {
                    if (response.status === 200 || response.status === 201) {
                        this.patientsList = response.data.data;
                        this.preparePatientSelect();
                    } else {
                        this.throwDialogModal('Error', 'Response code: ' + response.status);
                        console.log('get patients: Response code ' + response.status);
                    }
                });
            },
            createAppointment: function () {
                axios.post('/api/appointment', {
                    doctor_id: this.newAppointment.row.doctor.id,
                    patient_id: this.selectedPatient.value,
                    type: this.newAppointment.type,
                    availabilities: [this.newAppointment.row.id],
                    status: 'complete'
                }).catch(error => {
                    console.log(error.response.data, {type: error});
                    this.throwDialogModal('Error', error.response);
                }).then(response => {
                    if (response.status === 200 || response.status === 201) {
                        this.getAvailabilities();
                        this.$modal.hide('newAppointment');
                        this.$modal.show('createdAppointment', {details: response.data.data})
                    } else {
                        console.log('get patients: Response code ' + response.status);
                    }
                })
            },
            beforeOpenCreatedAppointment: function (event) {
                this.newAppointment.details = event.params.details;
            },
            afterClosedCreatedAppointment: function (event) {
                this.newAppointment = {};
            },
            throwDialogModal: function (title, text) {
                this.$modal.show('dialog', {
                    title: title,
                    text: '<pre>'+JSON.stringify(text, null, 2)+'</pre>',
                    buttons: [
                        {
                            title: 'Close'
                        }
                    ]
                })
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
            if (this.showCreateAppointment) {
                this.getDoctors();
                this.getPatients();
            }
        },
        components: {
            ModelSelect
        }
    }
</script>

<style scoped>

</style>
