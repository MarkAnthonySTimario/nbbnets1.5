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

// Import and use Vue Froala lib.
import VueFroala from 'vue-froala-wysiwyg'
Vue.use(VueFroala);

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

 Vue.mixin({
     methods : {
         printBloodBagLabel(facility_cd,donation_id,component_cd){
            let url =  'http://'+window.location.host+window.location.pathname+'label?facility_cd='+facility_cd+'&donation_id='+donation_id+'&component_cd='+component_cd;
            
            let w = window.open(url,'winname','directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=375,height=270');
            w.onload = () => {
                    w.print();
                w.close();
            };
         }
     }
 })


const app = new Vue({
    el: '#app',
    template: `<start></start>`,
    router,
    store,
    data : {
        messages : [] , users : []
    },
    watch : {
        messages(){
            this.$store.state.messages = this.messages;
        },
        users(){
            this.$store.state.users = this.users;
        }
    }
});

let socket = ioClient('http://'+window.location.hostname+':3000');
Window.socket = socket;

if(app.$session.get('user')){
    socket.emit("add-user",app.$session.get('user').user_id);
}

socket.on('init', function(messages){
    app.messages = messages;
});

socket.on('read', function(message){
    app.messages.push(message);
});

socket.on('seen-message', function({from,to}){
    _.filter(this.messages,{from,to}).map(message => {
        message.seen = true;
    });
    app.$store.state.messages = this.messages;
});

socket.on('update-users', function(users){
    app.users = users;
    app.$store.state.users = users;
});

window.app = app;
