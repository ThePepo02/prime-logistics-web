<template>
    <div class="dashboard-container">
        <!-- Header simplificado -->
        <header class="dashboard-header">
            <div class="logo">
                <div class="logo-icon">
                    <img :src="logoPrimeLogistics" alt="logo Prime Logistics">
                </div>
                <span class="logo-text">Prime</span>
            </div>
            <h1 class="page-title">Gestión de Usuarios admin</h1>
        </header>

        <div class="dashboard-layout">
            <!-- Menú lateral corregido -->
            <aside :class="['sidebar', { 'sidebar-mobile-open': sidebarOpen }]">
                <nav class="side-nav">
                    <div class="logo-prime">Prime</div>
                    <div class="nav-section-administracion">
                        <h2 class="nav-section-title">Administración</h2>
                        <ul class="nav-list">
                            <li><a href="#" class="nav-link" @click.prevent>Desafío</a></li>
                            <li><a href="#" class="nav-link" @click.prevent>Contratos</a></li>
                            <li><a href="#" class="nav-link active" @click.prevent>Operaciones Activas</a></li>
                            <li><a href="#" class="nav-link" @click.prevent>Servicios</a></li>
                            <li><a href="#" class="nav-link" @click.prevent>Configuración</a></li>
                        </ul>
                    </div>
                </nav>
            </aside>

            <!-- Contenido principal -->
            <main class="dashboard-content">
                <!-- KPI usuarios -->
                <div class="users-kpi-grid">
                    <div class="user-kpi-card">
                        <div class="kpi-number">14</div>
                        <div class="kpi-label">Usuarios Totales</div>
                    </div>

                    <div class="user-kpi-card">
                        <div class="kpi-number">11</div>
                        <div class="kpi-label">Usuarios Activos</div>
                    </div>

                    <div class="user-kpi-card">
                        <div class="kpi-number">3</div>
                        <div class="kpi-label">Desactivados</div>
                    </div>
                </div>

                <!-- Tabla usuarios -->
                <div class="users-table-container">
                    <div class="table-header">
                        <h3>Usuarios del sistema</h3>
                        <span class="table-subtitle">Todos los activos registrados</span>
                    </div>

                    <table class="users-table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Empresa</th>
                                <th>Jefe</th>
                                <th>Último Acceso</th>
                                <th>Estado</th>
                                <th>Acordes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in users" :key="user.id">
                                <td class="user-cell">{{ user.nombre }}</td>
                                <td>{{ user.email }}</td>
                                <td>{{ user.empresa }}</td>
                                <td>{{ user.jefe || '' }}</td>
                                <td>{{ user.ultimoAcceso }}</td>
                                <td>
                                    <div :class="['status-indicator', user.activo ? 'active' : 'inactive']"></div>
                                </td>
                                <td class="actions">
                                    <button class="action-btn edit-btn" @click="editarUsuario(user)">✏️</button>
                                    <button class="action-btn delete-btn" @click="eliminarUsuario(user)">🗑️</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Nota de paginación -->
                    <div class="table-footer">
                        <span class="pagination-note">Máximo 7 de 16 usuarios</span>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="action-buttons">
                    <button class="btn-nuevo-usuario" @click="nuevoUsuario">
                        <img :src="imgNuevoUsuario" alt="icono de añadir un nuevo usuario">Nuevo Usuario
                    </button>
                    <button class="btn-on" @click="activarTodos">
                        ON
                    </button>
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import logotipo from '../../../img/logo-empresa.png'
import iconoNuevoUsuario from '../../../img/iconoNuevoUsuario.png'


const logoPrimeLogistics = logotipo
const imgNuevoUsuario = iconoNuevoUsuario
const sidebarOpen = ref(false)

// Datos de usuarios según la imagen
const users = ref([
    { id: 1, nombre: 'Carlos Martínez', email: 'carlos@prime.com', empresa: 'Prime Logistics', jefe: '', ultimoAcceso: 'Hoy 08:42', activo: true },
    { id: 2, nombre: 'María García', email: 'maria@prime.com', empresa: 'Textil S.A.', jefe: '', ultimoAcceso: 'Hoy 08:15', activo: true },
    { id: 3, nombre: 'Ana López', email: 'ana@prime.com', empresa: 'Prime Logistics', jefe: '', ultimoAcceso: 'Hoy 07:30', activo: true },
    { id: 4, nombre: 'Pedro López', email: 'pedro@prime.com', empresa: 'Moto Exames SL', jefe: '', ultimoAcceso: 'Hoy 10:22', activo: true },
    { id: 5, nombre: 'David Ruiz', email: 'david@prime.com', empresa: 'Prime Logistics', jefe: '', ultimoAcceso: 'Mar 2:00', activo: true },
    { id: 6, nombre: 'Sara Ruiz', email: 'sara@prime.com', empresa: 'Import Global', jefe: '', ultimoAcceso: 'Mar 5:00', activo: true },
    { id: 7, nombre: 'Laura Gómez', email: 'laura@mctc.com', empresa: 'Tech Imports SA', jefe: '', ultimoAcceso: 'Mar 13:00', activo: true }
])

const nuevoUsuario = () => {
    alert('Abrir formulario para nuevo usuario')
}

const activarTodos = () => {
    alert('Activar todos los usuarios')
}

const editarUsuario = (user) => {
    alert(`Editar usuario: ${user.nombre}`)
}

const eliminarUsuario = (user) => {
    alert(`Eliminar usuario: ${user.nombre}`)
}
</script>

<style scoped>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.dashboard-container {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f7fc;
    min-height: 100vh;
}

.dashboard-header {
    background: white;
    padding: 1rem 2rem;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    align-items: center;
    gap: 2rem;
}

.logo {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.logo-icon img {
    height: 40px;
}

.logo-text {
    font-weight: 700;
    font-size: 1.25rem;
    color: #0e3d5c;
}

.page-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #2c3e50;
    margin: 0;
}

.dashboard-layout {
    display: flex;
    flex: 1;
}

.sidebar {
    width: 260px;
    background: #0a2b3e;
    min-height: calc(100vh - 73px);
    padding: 1.5rem;
}

.logo-prime {
    font-size: 1.5rem;
    font-weight: bold;
    color: white;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #1a4a6e;
}

.nav-section-title {
    font-size: 0.7rem;
    text-transform: uppercase;
    color: #80a6c2;
    margin-bottom: 0.75rem;
    letter-spacing: 1px;
}

.nav-list {
    list-style: none;
    padding: 0;
    margin-bottom: 1.5rem;
}

.nav-link {
    display: block;
    padding: 0.5rem 0;
    color: #cfdfed;
    text-decoration: none;
    font-size: 0.9rem;
}

.nav-link:hover,
.nav-link.active {
    color: white;
    font-weight: 500;
}

.dashboard-content {
    flex: 1;
    padding: 1.5rem 2rem;
    max-width: calc(100% - 260px);
}

/* KPI usuarios */
.users-kpi-grid {
    display: flex;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.user-kpi-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem 2rem;
    text-align: center;
    min-width: 150px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.kpi-number {
    font-size: 2rem;
    font-weight: bold;
    color: #2c3e50;
}

.kpi-label {
    font-size: 0.85rem;
    color: #7f8c8d;
    margin-top: 0.25rem;
}

/* Tabla */
.users-table-container {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.table-header {
    margin-bottom: 1.5rem;
}

.table-header h3 {
    font-size: 1.1rem;
    color: #2c3e50;
    margin-bottom: 0.25rem;
}

.table-subtitle {
    font-size: 0.8rem;
    color: #7f8c8d;
}

.users-table {
    width: 100%;
    border-collapse: collapse;
}

.users-table th,
.users-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #e9ecef;
}

.users-table th {
    background-color: #f8f9fa;
    font-weight: 600;
    color: #2c3e50;
    font-size: 0.85rem;
}

.users-table td {
    font-size: 0.85rem;
    color: #495057;
}

.user-cell {
    font-weight: 500;
    color: #2c3e50;
}

.status-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.status-indicator.active {
    background-color: #2a9d8f;
}

.status-indicator.inactive {
    background-color: #e76f51;
}

.actions {
    display: flex;
    gap: 8px;
}

.action-btn {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 1rem;
    padding: 4px;
}

.edit-btn:hover {
    color: #f39c12;
}

.delete-btn:hover {
    color: #e74c3c;
}

.table-footer {
    margin-top: 1rem;
    text-align: center;
}

.pagination-note {
    font-size: 0.8rem;
    color: #7f8c8d;
}

/* Botones de acción */
.action-buttons {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
}

.btn-nuevo-usuario,
.btn-on {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.3s;
}

.btn-nuevo-usuario {
    background-color: #1a5d8c;
    color: white;
}

.btn-nuevo-usuario:hover {
    background-color: #0e3d5c;
}

.btn-on {
    background-color: #2a9d8f;
    color: white;
}

.btn-on:hover {
    background-color: #21867a;
}

@media (max-width: 992px) {
    .dashboard-content {
        max-width: 100%;
    }

    .users-table {
        display: block;
        overflow-x: auto;
    }
}
</style>
