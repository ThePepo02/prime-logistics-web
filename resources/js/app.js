import './bootstrap';
import { createApp } from 'vue/dist/vue.esm-bundler.js';
import DashboardAdminComponent from './components/DashboardAdminComponent.vue';

const app = createApp({});






app.component('dashboardAdmin-component', DashboardAdminComponent);







app.mount('#app');