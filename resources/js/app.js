import './bootstrap';
import { createApp } from 'vue/dist/vue.esm-bundler.js';
import DashboardAdminComponent from './components/admin/DashboardAdminComponent.vue';
import GestionUsuariosComponent from './components/admin/GestionUsuariosComponent.vue';

const app = createApp({});
app.component('dashboard-admin-component', DashboardAdminComponent);
app.component('gestion-usuarios-component', GestionUsuariosComponent);
app.mount('#app');