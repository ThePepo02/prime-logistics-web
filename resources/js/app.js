import './bootstrap';
import { createApp } from 'vue/dist/vue.esm-bundler.js';

// Componentes Admin (compañero)
import DashboardAdminComponent from './components/admin/DashboardAdminComponent.vue';

const app = createApp({});






app.component('dashboardAdmin-component', DashboardAdminComponent);
app.component('gestion-usuarios-component', GestionUsuariosComponent);







app.mount('#app');
