<template>
    <div>
        <div class="form-group row">
            <label class="col-sm-3 offset-md-1 col-md-3 col-lg-2 col-form-label">Date</label>
            <div class="col-sm-9 col-md-7 col-lg-8">
                <datepicker class="form-control" v-model="date" placeholder="Select Date"
                            format="yyyy-MM-dd"></datepicker>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 offset-md-1 col-md-3 col-lg-2 col-form-label">Start Time</label>
            <div class="col-sm-9 col-md-7 col-lg-8">
                <vue-timepicker v-model="startTime"></vue-timepicker>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 offset-md-1 col-md-3 col-lg-2 col-form-label">End Time</label>
            <div class="col-sm-9 col-md-7 col-lg-8">
                <vue-timepicker v-model="endTime"></vue-timepicker>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 offset-md-1 col-md-3 col-lg-2 col-form-label">End Time</label>
            <div class="col-sm-9 col-md-7 col-lg-8">
                <el-time-select
                        v-model="value1" :picker-options="{start: '08:30', step: '00:15', end: '18:30'}"
                        placeholder="Select time">
                </el-time-select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-lg-10 offset-lg-1 col-md-10 offset-md-1 text-right">
                <button v-on:click="addAvailability()" type="button" class="btn btn-success btn-lg">Add Availability
                </button>
            </div>
        </div>
    </div>
</template>

<script>
  import axios from 'axios';
  import Datepicker from 'vuejs-datepicker';
  import VueTimepicker from 'vuejs-timepicker';
  import moment from 'moment';

  export default {
    name: 'AddAvailability',
    data() {
      return {
        value1: '',
        date: '',
        startTime: {
          HH: '',
          mm: '',
        },
        endTime: {
          HH: '',
          mm: '',
        },
      };
    },
    components: {
      Datepicker,
      VueTimepicker,
    },
    props: {
      doctorId: Number,
    },
    mounted() {
    },
    methods: {
      addAvailability: function() {
        axios.post('/api/availability', {
          doctor_id: this.doctorId,
          start: moment(this.setDate(this.date, this.startTime)).format('YYYY-MM-DD HH:MM:SS'),
          end: moment(this.setDate(this.date, this.endTime)).format('YYYY-MM-DD HH:MM:SS'),
        }).catch(error => {
          console.log(error.response.data, {type: 'error'});
        }).then(response => {
          if (response.status === 200 || response.status === 201) {
            console.log('Added availability');
            console.log(response);
          } else {
            console.log('Add availability failed: Response code ' + response.status);
          }
        });
      },
      setDate: function(date, time) {
        let dateMaker = new Date(date);
        dateMaker.setHours(time.HH);
        dateMaker.setMinutes(time.MM);
        return date;
      },
    },
  };
</script>
