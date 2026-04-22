import './bootstrap';
import '../css/app.css';
import 'flowbite';

import { createApp } from 'vue/dist/vue.esm-bundler.js';


// Componentes Admin ()
import DashboardAdminComponent from './components/admin/DashboardAdminComponent.vue';

import DashboardClientComponent from './components/client/DashboardClientComponent.vue';
import ClientNewOrderComponent from './components/client/ClientNewOrderComponent.vue';
import ClientOrdersComponent from './components/client/ClientOrdersComponent.vue';
import ClientTrackingComponent from './components/client/ClientTrackingComponent.vue';
import ClientNotificationsComponent from './components/client/ClientNotificationsComponent.vue';

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

app.component('dashboard-admin-component', DashboardAdminComponent);
app.component('dashboard-client-component', DashboardClientComponent);
app.component('client-new-order-component', ClientNewOrderComponent);
app.component('client-orders-component', ClientOrdersComponent);
app.component('client-tracking-component', ClientTrackingComponent);
app.component('client-notifications-component', ClientNotificationsComponent);

app.component('dashboardAdmin-component', DashboardAdminComponent);
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
