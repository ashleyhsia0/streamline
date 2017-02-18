
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('tasks-list', require('./components/TasksList.vue'));
Vue.component('task', require('./components/Task.vue'));
Vue.component('edit-task', require('./components/EditTask.vue'));

const app = new Vue({
    el: '#app'
});
