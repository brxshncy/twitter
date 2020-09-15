
require('./bootstrap');

window.Vue = require('vue');


Vue.component('notification',require('./components/Notification.vue').default);

const app = new Vue({
    el: '#here',
});
