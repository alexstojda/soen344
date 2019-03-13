<template>
    <div>
        <div class="row">
            <el-date-picker v-model="date" type="date"
                            placeholder="Pick a date"
                            default-value="2010-10-01">
            </el-date-picker>
            <el-time-select placeholder="Start time" v-model="startTime"
                            :picker-options="{ start: '08:30',  step: '00:15', end: '18:30'}">
            </el-time-select>
            <el-time-select placeholder="End time" v-model="endTime"
                            :picker-options="{ start: '08:30', step: '00:15', end: '18:30', minTime: startTime}">
            </el-time-select>
        </div>
        <div class="row">
            <div class="col-lg-10 offset-lg-1 col-md-10 offset-md-1 text-right">
                <button v-on:click="addAvailability()" type="button" class="btn btn-success btn-lg">Add Availability
                </button>
            </div>
        </div>
    </div>
</template>

<script>
  import axios from 'axios';
  import moment from 'moment';

  export default {
    name: 'AddAvailability',
    data() {
      return {
        startTime: '',
        endTime: '',
        date: '',
      };
    },
    components: {
    },
    props: {
      doctorId: Number,
    },
    mounted() {
    },
    methods: {
      addAvailability: function() {
        console.log(this.date);
        /*axios.post('/api/availability', {
          doctor_id: this.doctorId,
          //start: moment(this.setDate(this.date, { HH: '08', mm: '00',})).format('YYYY-MM-DD HH:MM:SS'),
          //end: moment(this.setDate(this.date, { HH: '08', mm: '30',})).format('YYYY-MM-DD HH:MM:SS'),
        }).catch(error => {
          console.log(error.response.data, {type: 'error'});
        }).then(response => {
          if (response.status === 200 || response.status === 201) {
            console.log('Added availability');
            console.log(response);
          } else {
            console.log('Add availability failed: Response code ' + response.status);
          }
        });*/
      },
    },
  };
</script>
