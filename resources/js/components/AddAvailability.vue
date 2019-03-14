<template>
    <div>
        <div class="row">
            <el-date-picker v-model="date" type="date" placeholder="Pick a date" default-value="2019-01-01">
            </el-date-picker>
            <el-time-select placeholder="Start time" v-model="startTime"
                            :picker-options="{ start: '08:00',  step: '00:20', end: '20:00'}">
            </el-time-select>
            <el-time-select placeholder="End time" v-model="endTime"
                            :picker-options="{ start: '08:00', step: '00:20', end: '20:00', minTime: startTime}">
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
      this.moment = moment;
    },
    methods: {
      addAvailability: function() {
        let date = { y: this.date.getFullYear(), m: this.date.getMonth(), d: this.date.getDate() };
        let startSplit = this.startTime.split(':');
        let endSplit = this.endTime.split(':');

        let start = moment({y: date.y, M: date.m, d: date.d, h: startSplit[0], m: startSplit[1]}).format("YYYY-MM-DD HH:mm:ss");
        let end = moment({y: date.y, M: date.m, d: date.d, h: endSplit[0], m: endSplit[1]}).format("YYYY-MM-DD HH:mm:ss");

        axios.post('/api/availability', {
          doctor_id: this.doctorId, start: start, end: end,
        }).catch(error => {
          console.log(error.response.data, {type: 'error'});
        }).then(response => {
          if (response.status === 200 || response.status === 201) {
            console.log('Added availability');
            console.log(response);
          } else {
            console.log('Add availability: Response code ' + response.status);
          }
        });
      },
    },
  };
</script>
