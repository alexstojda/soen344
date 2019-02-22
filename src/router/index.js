import Vue from 'vue'
import Router from 'vue-router'
import Home from '@/components/Home'
import SortedTablePlugin from "vue-sorted-table";
import ViewAppointments from '@/components/ViewAppointments'

Vue.use(SortedTablePlugin)
Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'Home',
      component: Home
    },

    {
        path: '/appointments',
        name: 'ViewAppointments',
        component: ViewAppointments
    }
  ]
})
