<template>
    <div>
        <!-- encabezado -->
        <header>
            <div class="logo">
                <img :src="imgLogo" alt="">
            </div>
            <title>Dashboard</title>
        </header>
        <main>
            <h1>Dashboard</h1>
            <p>Vista global del sistema - Prime Logistics</p>
            <aside>
                <nav>
                    <div class="administracion">
                        <h2>Administración</h2>
                        <ul>
                            <li><a href="#">Dashboard</a></li>
                            <li><a href="#">Gestión de Usuarios</a></li>
                        </ul>
                    </div>
                    <div class="operaciones">
                        <h2>Operaciones</h2>
                        <ul>
                            <li><a href="#">Todas las Ofertas</a></li>
                            <li><a href="#">Operaciones Activas</a></li>
                        </ul>
                    </div>
                    <div class="sistema">
                        <h2>Sistema</h2>
                        <ul>
                            <li><a href="#">Datos Maestros</a></li>
                            <li><a href="#">Configuración</a></li>
                        </ul>
                    </div>
                </nav>
                <button type="button" class="btn_exportarDatos">
                    <img src="/public/iconoExportarDatos.png" alt="Icono exportar datos">Exportar datos
                </button>
                <button type="button">
                    <a href="">
                        <img src="/public/notificaciones-logo.png" alt="Notificaciones logo">
                    </a>
                </button>
                <div id="iconoPerfilUsuarioAdmin">
                    <img src="/public/perfilUsuarioAdmin.png" alt="Perfil Usuario administrador">
                </div>
            </aside>
            <aside>
                <div class="totalOfertas">
                    <img src="" alt="Icono de total de ofertas">
                    <h2 :data-number="numeroAleatorio">{{ numeroAleatorio }}</h2>
                    <p>Total Ofertas</p>
                    <p :data-number="numeroAleatorio">{{ numeroAleatorio }}% vs mes anterior</p>
                </div>
                <div class="aceptadas">
                    <img src="" alt="Icono de ofertas aceptadas">
                    <h2 :data-number="numeroAleatorio">{{ numeroAleatorio }}</h2>
                    <p>Total Ofertas</p>
                    <p :data-number="numeroAleatorio">{{ numeroAleatorio }}% tasa global</p>
                </div>
                <div class="enTransito">
                    <img src="" alt="Icono de ofertas en tránsito">
                    <h2 :data-number="numeroAleatorio">{{ numeroAleatorio }}</h2>
                    <p>Total Ofertas</p>
                    <p :data-number="numeroAleatorio">{{ numeroAleatorio }} esta semana</p>
                </div>
                <div class="incidentes">
                    <img src="" alt="Icono de incidentes en las ofertas">
                    <h2 :data-number="numeroAleatorio">{{ numeroAleatorio }}</h2>
                    <p>Total Ofertas</p>
                    <p :data-number="numeroAleatorio">{{ numeroAleatorio }} vs semana pasada</p>
                </div>
            </aside>
            <aside>
                <div id="graficoActividad global">
                    <h2>Actividad semanal global</h2>
                    <ul>
                        <li>Enviadas</li>
                        <li>Aceptadas</li>
                        <li>Incidentes</li>
                    </ul>
                </div>
            </aside>
            <aside>
                <div class="table-container">
                    <input v-model="searchQuery" type="text" placeholder="Buscar por ID, cliente, ruta..."
                        class="search-input">

                    <!-- Filtro por categoría -->
                    <select v-model="selectedCategory">
                        <option v-for="categoria in categorias" :key="categoria" :value="categoria">{{ categoria }}
                        </option>
                    </select>

                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID Oferta</th>
                                <th>Cliente</th>
                                <th>Empresa</th>
                                <th>Modo</th>
                                <th>Ruta</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in datosFiltrados" :key="item.id">
                                <td>{{ item.id }}</td>
                                <td>{{ item.cliente }}</td>
                                <td>{{ item.fecha }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </aside>
        </main>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
const numeroAleatorio = ref(0);
const numeroActualizado = ref(0);
const intervalo = ref(0);
const imgExportar = ref('/resources/img/iconoExportarDatos.png');
const imgNotificaciones = ref('/resources/img/notificaciones-logo.png');
const imgPerfil = ref('/resources/img/perfilUsuarioAdmin.png');
const imgLogo = ref('/resources/img/prime-logistics-logo.png');

const generarNumerosAutomaticamente = () => {
    numeroAleatorio.value = Math.floor(Math.random() * 100) + 1;
};

const actualizarNumero = () => {
    numeroActualizado = Math.random() < 0.5 ? -1 : 1; //Aumenta y disminuye en 50%
    numeroAleatorio.value += numeroActualizado;
};

onMounted(() => {
    generarNumerosAutomaticamente();

    intervalo = setInterval(actualizarNumero, 1000);
    clearInterval(intervalo);
});
</script>

<style lang="scss" scoped></style>