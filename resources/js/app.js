import './bootstrap';
import '../css/app.css';
import 'flowbite';

import { createApp } from 'vue/dist/vue.esm-bundler.js';

// Componentes Admin
import DashboardAdminComponent from './components/admin/DashboardAdminComponent.vue';
import GestionUsuariosComponent from './components/admin/GestionUsuariosComponent.vue';
import NuevoUsuarioComponent from './components/admin/NuevoUsuarioComponent.vue';

// Componentes Login
import LoginComponents from './components/LoginComponents.vue';

// Componentes Operador
import SidebarOperador from './components/operador/SidebarOperador.vue';
import DashboardOperador from './components/operador/DashboardOperador.vue';
import ClientesComponent from './components/operador/ClientesComponent.vue';
import OfertasComerciales from './components/operador/OfertasComerciales.vue';

const app = createApp({});

// Admin
app.component('dashboardAdmin-component', DashboardAdminComponent);
app.component('gestion-usuarios-component', GestionUsuariosComponent);
app.component('nuevo-usuario-component', NuevoUsuarioComponent);

// Login
app.component('login-components', LoginComponents);

// Operador
app.component('sidebar-operador', SidebarOperador);
app.component('dashboard-operador', DashboardOperador);
app.component('clientes-component', ClientesComponent);
app.component('ofertas-comerciales', OfertasComerciales);

app.mount('#app');
