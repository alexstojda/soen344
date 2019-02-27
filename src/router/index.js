import Vue from 'vue'
import Router from 'vue-router'
import YourAppointments from '@/components/YourAppointments'
import ScheduleAppointment from '@/components/ScheduleAppointment'
import SortedTablePlugin from "vue-sorted-table"
import Home from '@/components/Home'
import Login from '@/components/Login'

Vue.use(SortedTablePlugin)
Vue.use(Router)

export default new Router({
    mode: 'history',
    routes: [
    {
      path: '/',
      name: 'Home',
      component: Home
    },
    {
      path: '/notHome',
      name: 'notHome',
      component: Login
    }
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
