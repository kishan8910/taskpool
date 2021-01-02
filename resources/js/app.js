/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import VueRouter from 'vue-router';

Vue.use(VueRouter);



import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
// Install BootstrapVue
Vue.use(BootstrapVue)
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin)


if(document.querySelector("meta[name='user-id']"))
{
    Vue.prototype.$userId = document.querySelector("meta[name='user-id']").getAttribute('content');
}

if(document.querySelector("meta[name='user-role']"))
{
    Vue.prototype.$userRole = document.querySelector("meta[name='user-role']").getAttribute('content');
}



/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))


Vue.component('task-app', require('./components/TaskApp.vue').default);
Vue.component('sidemenu', require('./components/SideMenu.vue').default);
Vue.component('tasks', require('./components/Tasks.vue').default);
Vue.component('roles', require('./components/Roles.vue').default);
Vue.component('navbar', require('./components/Navbar.vue').default);
Vue.component('createedittask', require('./components/CreateEditTask.vue').default);
Vue.component('createeditrole', require('./components/CreateRoles.vue').default);


import Roles from './components/Roles.vue';

const routes= [

    { path: '/roles', component: Roles}

];

const router = new VueRouter({
    routes,
    mode: 'history'
});




/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


const app = new Vue({
    el: '#app',
    router
});

