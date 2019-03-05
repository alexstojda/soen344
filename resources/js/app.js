
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Enable Debug Mode based on env
 */
if (process.env.NODE_ENV !== 'production') {
    Vue.config.debug = true;
    Vue.config.devtools = true;
} else {
    Vue.config.devtools = false;
    Vue.config.debug = false;
    Vue.config.silent = true;
}

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('cart', require('./components/Cart.vue').default);
Vue.component('cart-line', require('./components/CartLine.vue').default);
Vue.component('checkout', require('./components/Checkout.vue').default);
Vue.component('search-appointment', require('./components/SearchAppointment.vue').default);
Vue.component('view-appointments', require('./components/ViewAppointments.vue').default);
Vue.component('add-appointment', require('./components/AddAppointment.vue').default);
Vue.component('add-availability', require('./components/AddAvailability.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app'
});
