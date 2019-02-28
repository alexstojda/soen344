import Vue from 'vue'
import Router from 'vue-router'
import YourAppointments from '@/components/YourAppointments'
import ScheduleAppointment from '@/components/ScheduleAppointment'
import SortedTablePlugin from "vue-sorted-table"

Vue.use(SortedTablePlugin)
Vue.use(Router)

export default new Router({
    mode: 'history',
    routes: [
    {
      path: '/',
      name: 'YourAppointments',
      component: YourAppointments
    },

    {
        path: '/scheduleAppointment',
        name: 'ScheduleAppointment',
        component: ScheduleAppointment
      }
  ]
})
