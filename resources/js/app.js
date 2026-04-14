import './bootstrap';
import { createApp } from 'vue/dist/vue.esm-bundler.js';
import DashboardAdminComponent from './components/admin/DashboardAdminComponent.vue';
import DashboardClientComponent from './components/client/DashboardClientComponent.vue';

const app = createApp({});






app.component('dashboardAdmin-component', DashboardAdminComponent);
app.component('dashboard-client-component', DashboardClientComponent);







app.mount('#app');
