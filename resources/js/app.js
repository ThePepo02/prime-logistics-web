import './bootstrap';
import '../css/app.css';
import 'flowbite';

import { createApp } from 'vue/dist/vue.esm-bundler.js';
import LoginComponents from './components/LoginComponents.vue';


const app = createApp({});

// Los nombres de los componentes deben coincidir con los nombres de los archivos .vue tipo ('clicles-components', CiclesComponents)
// Luego arriba en justo debajo del createApp se agrega import CiclesComponents from './components/CiclesComponents.vue';

app.component('login-components', LoginComponents);


// Luego se mosntan los componentes en algun sitio del blade usando el app-amount('');
app.mount('#app');
