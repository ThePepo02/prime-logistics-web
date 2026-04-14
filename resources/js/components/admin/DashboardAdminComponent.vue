<template>
    <div>
        <!-- encabezado -->
        <header>
            <div class="logo">
                <img :src="primeLogisticsWebIcon" alt="prime logistics web logo">
            </div>
            <!-- Cambié <title> por <h1> porque <title> no va dentro de <header> -->
            <h1>Dashboard Admin</h1>
        </header>

        <main>
            <h1>Dashboard</h1>

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

            <!-- Botón Exportar -->
            <!-- Se corrigió la ruta de la imagen para Laravel Mix / asset() -->
            <button type="button" class="btn_exportarDatos">
                <img :src="exportIcon" alt="Icono exportar datos">
                Exportar datos
            </button>

            <!-- Botón Notificaciones -->
            <!-- Se corrigió el <a> dentro del botón y se convirtió en solo <a> con estilo de botón -->
            <a href="#" class="btn_notificaciones">
                <img :src="notificacionesIcon" alt="Notificaciones logo">
            </a>

            <!-- Icono Perfil -->
            <div id="iconoPerfilUsuarioAdmin">
                <img :src="perfilIcon" alt="Perfil Usuario administrador">
            </div>
        </main>
    </div>
</template>

<script setup>
import { ref } from 'vue';


const exportIcon = ref('/iconoExportarDatos.png');
const notificacionesIcon = ref('/notificaciones-logo.png');
const perfilIcon = ref('/perfilUsuarioAdmin.png');
const primeLogisticsWebIcon = ref('/prime-logistics-logo.png');
</script>

<style lang="scss" scoped>
header {
    display: flex;
    align-items: center;
    gap: 1rem;
}

nav {
    display: flex;
    gap: 2rem;
}

.btn_exportarDatos,
.btn_notificaciones {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
}
</style>
