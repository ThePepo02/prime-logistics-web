import './bootstrap';
import { createApp } from 'vue/dist/vue.esm-bundler.js';

// Componentes Admin (compañero)
import DashboardAdminComponent from './components/admin/DashboardAdminComponent.vue';
import DashboardClientComponent from './components/client/DashboardClientComponent.vue';

// Componentes Operador (tú)
import SidebarOperador from './components/operador/SidebarOperador.vue';


const app = createApp({});

// Admin
app.component('dashboardAdmin-component', DashboardAdminComponent);
app.component('dashboard-client-component', DashboardClientComponent);

// Operador
app.component('sidebar-operador', SidebarOperador);

app.mount('#app');
