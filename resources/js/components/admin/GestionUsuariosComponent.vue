<template>
    <div class="dashboard-container">
        <!-- encabezado -->
        <header class="dashboard-header">
            <div class="logo">
                <div class="logo-icon">
                    <img :src="logoPrimeLogistics" alt="logo Prime Logistics">
                </div>
                <span class="logo-text">Prime Logistics</span>
                <h1 class="page-title">Dashboard
                    <span class="title-sub">Vista global del sistema — Prime Logistics</span>
                </h1>
                <!-- Botones de acción -->
                <div class="actions-bar">
                    <button type="button" class="btn_exportarDatos" @click="exportData">
                        <img :src="imgBtnExportarDatos" alt="Icono exportarDatos">
                        Exportar datos
                    </button>
                    <a href="#" class="btn_notificaciones" @click.prevent="clickNotification">
                        <img :src="imgBtnNotificaciones" alt="Icono notificaciones">
                    </a>
                    <div class="perfil-icon" @click="clickProfile">
                        <img :src="imgPerfilUsuarioAdmin" alt="Imagen Perfil Usuario admin">
                    </div>
                </div>
            </div>
        </header>

        <div class="dashboard-layout">
            <!-- Menú lateral -->
            <aside :class="['sidebar', { 'sidebar-mobile-open': sidebarOpen }]">
                <nav class="side-nav">
                    <div class="nav-section-administracion">
                        <h2 class="nav-section-title">Administración</h2>
                        <ul class="nav-list">
                            <li><a href="#" class="nav-link active" @click.prevent>Dashboard</a></li>
                            <li><a href="#" class="nav-link" @click.prevent>Gestión de Usuarios</a></li>
                        </ul>
                    </div>
                    <div class="nav-section-operaciones">
                        <h2 class="nav-section-title">Operaciones</h2>
                        <ul class="nav-list">
                            <li><a href="#" class="nav-link" @click.prevent>Todas las Ofertas</a></li>
                            <li><a href="#" class="nav-link" @click.prevent>Operaciones Activas</a></li>
                        </ul>
                    </div>
                    <div class="nav-section-sistema">
                        <h2 class="nav-section-title">Sistema</h2>
                        <ul class="nav-list">
                            <li><a href="#" class="nav-link" @click.prevent>Datos Maestros</a></li>
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
                        <div class="icon blue">👥</div>
                        <div>
                            <h2>14</h2>
                            <p>Usuarios Totales</p>
                        </div>
                    </div>

                    <div class="user-kpi-card">
                        <div class="icon green">✔</div>
                        <div>
                            <h2>11</h2>
                            <p>Usuarios Activos</p>
                        </div>
                    </div>

                    <div class="user-kpi-card">
                        <div class="icon red">✖</div>
                        <div>
                            <h2>3</h2>
                            <p>Desactivados</p>
                        </div>
                    </div>
                </div>

                <!-- Tabla usuarios -->
                <div class="users-table-container">
                    <div class="table-header">
                        <h3>Usuarios del sistema</h3>

                        <div class="filters">
                            <input v-model="userSearch" placeholder="Buscar usuario..." />
                            <select v-model="roleFilter">
                                <option value="">Todos los roles</option>
                                <option value="ADMIN">Admin</option>
                                <option value="CLIENTE">Cliente</option>
                                <option value="OPERADOR">Operador</option>
                            </select>
                        </div>
                    </div>

                    <table class="users-table">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Correo</th>
                                <th>Empresa</th>
                                <th>Rol</th>
                                <th>Último acceso</th>
                                <th>Estado</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="user in filteredUsers" :key="user.id">
                                <td class="user-cell">
                                    <div class="avatar">{{ user.iniciales }}</div>
                                    {{ user.nombre }}
                                </td>
                                <td>{{ user.email }}</td>
                                <td>{{ user.empresa }}</td>

                                <td>
                                    <span :class="['role-badge', user.rol.toLowerCase()]">
                                        {{ user.rol }}
                                    </span>
                                </td>

                                <td>{{ user.ultimo }}</td>

                                <td>
                                    <div class="toggle" :class="{ active: user.activo }"></div>
                                </td>

                                <td class="actions">
                                    ✏️ 🗑️
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>

        <footer class="dashboard-footer">
            <span>Carlos Martinez</span>
            <span class="rol_user">Administrador</span>
        </footer>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
const userSearch = ref('')
const roleFilter = ref('')

const users = ref([
    { id: 1, nombre: 'Carlos Martínez', iniciales: 'CM', email: 'carlos@prime.com', empresa: 'Prime Logistics', rol: 'ADMIN', ultimo: 'Hoy 09:42', activo: true },
    { id: 2, nombre: 'María García', iniciales: 'MG', email: 'maria@textil.com', empresa: 'Textil SA', rol: 'CLIENTE', ultimo: 'Hoy 08:15', activo: true },
    { id: 3, nombre: 'Ana López', iniciales: 'AL', email: 'ana@prime.com', empresa: 'Prime Logistics', rol: 'OPERADOR', ultimo: 'Ayer', activo: true },
    { id: 4, nombre: 'Sara Ruiz', iniciales: 'SR', email: 'sara@import.com', empresa: 'Import Global', rol: 'CLIENTE', ultimo: 'Hace 8 días', activo: false }
])

const filteredUsers = computed(() => {
    return users.value.filter(u => {
        const matchSearch =
            u.nombre.toLowerCase().includes(userSearch.value.toLowerCase()) ||
            u.email.toLowerCase().includes(userSearch.value.toLowerCase())

        const matchRole = roleFilter.value ? u.rol === roleFilter.value : true

        return matchSearch && matchRole
    })
})
</script>

<style lang="scss" scoped>
/* KPI usuarios */
.users-kpi-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.user-kpi-card {
    background: white;
    border-radius: 12px;
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;

    &.blue { background: #e0f2fe; }
    &.green { background: #dcfce7; }
    &.red { background: #fee2e2; }
}

/* Tabla */
.users-table-container {
    background: white;
    border-radius: 12px;
    padding: 1rem;
}

.users-table {
    width: 100%;
    border-collapse: collapse;

    th, td {
        padding: 12px;
        border-bottom: 1px solid #eee;
    }
}

/* Usuario */
.user-cell {
    display: flex;
    align-items: center;
    gap: 10px;
}

.avatar {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: #2563eb;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Roles */
.role-badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;

    &.admin { background: #fde68a; }
    &.cliente { background: #bfdbfe; }
    &.operador { background: #bbf7d0; }
}

/* Toggle */
.toggle {
    width: 40px;
    height: 20px;
    background: #ccc;
    border-radius: 20px;
    position: relative;

    &::after {
        content: '';
        width: 16px;
        height: 16px;
        background: white;
        position: absolute;
        top: 2px;
        left: 2px;
        border-radius: 50%;
        transition: 0.3s;
    }

    &.active {
        background: #22c55e;
    }

    &.active::after {
        transform: translateX(20px);
    }
}

/* Filtros */
.filters {
    display: flex;
    gap: 10px;

    input, select {
        padding: 6px;
        border-radius: 8px;
        border: 1px solid #ddd;
    }
}
</style>