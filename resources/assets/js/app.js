/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import VueSession from 'vue-session';
import VeeValidate from 'vee-validate';
import _ from 'lodash';
import $ from 'jquery';
import ioClient from 'socket.io-client';



import router from './router';
import store from './store';
import http from './http';
import error from './components/Tools/error.vue';

import _globals from './_globals';

require('./bootstrap');

window.Vue = require('vue');
import './filters';

Vue.use(VueSession);
Vue.use(VeeValidate);
Vue.prototype.$http = http;

Vue.component('start', require('./components/Start.vue'));
Vue.component('error',error);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


const app = new Vue({
    el: '#app',
    template: `<start></start>`,
    router,
    store,
    data : {
        messages : []
    },
    watch : {
        messages(){
            this.$store.state.messages = this.messages;
        }
    }
});

let socket = ioClient('http://localhost:3000');
Window.socket = socket;

socket.on('init-chat', function(messages){
    app.messages = messages;
});


window.app = app;