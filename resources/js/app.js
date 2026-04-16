import './bootstrap';
import '../css/app.css';
import 'flowbite';

import { createApp } from 'vue/dist/vue.esm-bundler.js';

// Componentes Admin
import DashboardAdminComponent from './components/admin/DashboardAdminComponent.vue';
import GestionUsuariosComponent from './components/admin/GestionUsuariosComponent.vue';

// Componentes Login
import LoginComponents from './components/LoginComponents.vue';

const app = createApp({});

app.component('dashboardAdmin-component', DashboardAdminComponent);
app.component('gestion-usuarios-component', GestionUsuariosComponent);
app.component('login-components', LoginComponents);

app.mount('#app');
