import './bootstrap';
import { createApp } from 'vue/dist/vue.esm-bundler.js';

// Componentes Admin (compañero)
import DashboardAdminComponent from './components/admin/DashboardAdminComponent.vue';

// Componentes Operador (tú)
import SidebarOperador from './components/operador/SidebarOperador.vue';
import DashboardOperador from './components/operador/DashboardOperador.vue';

const app = createApp({});

// Admin
app.component('dashboardAdmin-component', DashboardAdminComponent);

// Operador
app.component('sidebar-operador', SidebarOperador);
app.component('dashboard-operador', DashboardOperador);

app.mount('#app');
