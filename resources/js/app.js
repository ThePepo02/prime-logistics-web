import './bootstrap';
import { createApp } from 'vue/dist/vue.esm-bundler.js';

// Componentes Admin ()
import DashboardAdminComponent from './components/admin/DashboardAdminComponent.vue';
import DashboardClientComponent from './components/client/DashboardClientComponent.vue';
import ClientNewOrderComponent from './components/client/ClientNewOrderComponent.vue';
import ClientOrdersComponent from './components/client/ClientOrdersComponent.vue';
import ClientTrackingComponent from './components/client/ClientTrackingComponent.vue';
import ClientNotificationsComponent from './components/client/ClientNotificationsComponent.vue';

// Componentes Operador ()
import SidebarOperador from './components/operador/SidebarOperador.vue';


const app = createApp({});

app.component('dashboard-admin-component', DashboardAdminComponent);


app.component('dashboard-client-component', DashboardClientComponent);
app.component('client-new-order-component', ClientNewOrderComponent);
app.component('client-orders-component', ClientOrdersComponent);
app.component('client-tracking-component', ClientTrackingComponent);
app.component('client-notifications-component', ClientNotificationsComponent);

// Operador
app.component('sidebar-operador', SidebarOperador);

app.mount('#app');
