/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';

import 'es6-promise/auto'
import axios from 'axios'
import Vue from 'vue';
import VueAuth from '@websanova/vue-auth'
import VueAxios from 'vue-axios'
import VueRouter from 'vue-router'
import Index from './Index';
import auth from './auth'
import router from './router'
import VueToast from 'vue-toast-notification';
import 'vue-toast-notification/dist/index.css';
import VModal from 'vue-js-modal'
import VMdDateRangePicker from "v-md-date-range-picker";

import VueMoment from 'vue-moment';

// Set Vue globally
window.Vue = require('vue');

// Set Vue router
Vue.router = router;
Vue.use(VueRouter);
Vue.use(VueToast, {
    // One of options
    position: 'top'
});
Vue.use(VModal);
// Set Vue authentication
Vue.use(VueAxios, axios);
// axios.defaults.baseURL = `${process.env.MIX_APP_URL}/api/v1`
axios.defaults.baseURL = window.base_url + '/api/v1';
Vue.use(VueAuth, auth);
Vue.use(VMdDateRangePicker);
Vue.use(VueMoment)

// Load Index
Vue.component('index', Index);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router
});
