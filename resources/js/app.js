import './bootstrap';
import '../css/app.css';
import 'flowbite';

import { createApp } from 'vue/dist/vue.esm-bundler.js';

// Componentes Admin
import DashboardAdminComponent from './components/admin/DashboardAdminComponent.vue';
import GestionUsuariosComponent from './components/admin/GestionUsuariosComponent.vue';
import NuevoUsuarioComponent from './components/admin/NuevoUsuarioComponent.vue';
import EditarNuevoUsuarioComponent from './components/admin/EditarNuevoUsuarioComponent.vue';
import EliminarNuevoUsuarioComponent from './components/admin/EliminarNuevoUsuarioComponent.vue';
import DashboardOfertasAdminComponent from './components/admin/DashboardOfertasAdminComponent.vue';
import OfertasActivasAdminComponent from './components/admin/OfertasActivasAdminComponent.vue'
import DatosMaestrosComponent from './components/admin/DatosMaestrosComponent.vue';

// Componentes Login
import LoginComponents from './components/LoginComponents.vue';

// Componentes Operador
import SidebarOperador from './components/operador/SidebarOperador.vue';
import DashboardOperador from './components/operador/DashboardOperador.vue';
import ClientesComponent from './components/operador/ClientesComponent.vue';
import OfertasComerciales from './components/operador/OfertasComerciales.vue';

const app = createApp({});

// Admin
app.component('dashboard-admin-component', DashboardAdminComponent);
app.component('gestion-usuarios-component', GestionUsuariosComponent);
app.component('nuevo-usuario-component', NuevoUsuarioComponent);
app.component('editar-nuevo-usuario-component', EditarNuevoUsuarioComponent);
app.component('eliminar-nuevo-usuario-component', EliminarNuevoUsuarioComponent);
app.component('dashboard-ofertas-admin-component', DashboardOfertasAdminComponent);
app.component('ofertas-activas-admin-component', OfertasActivasAdminComponent);
app.component('datos-maestros-component', DatosMaestrosComponent);

// Login
app.component('login-components', LoginComponents);

// Operador
app.component('sidebar-operador', SidebarOperador);
app.component('dashboard-operador', DashboardOperador);
app.component('clientes-component', ClientesComponent);
app.component('ofertas-comerciales', OfertasComerciales);

app.mount('#app');
