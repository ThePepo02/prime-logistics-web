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
            <h1 class="page-title">Gestión de Usuarios</h1>
            <p class="page-title">Crear, asignar roles y gestionar accesos</p>
        </header>

        <div class="dashboard-layout">
            <!-- Menú lateral -->
            <aside :class="['sidebar', { 'sidebar-mobile-open': sidebarOpen }]">
                <nav class="side-nav">
                    <div class="logo-prime">Prime</div>
                    <ul class="main-nav-list">
                        <li><a href="#" class="nav-link" @click.prevent>Notas</a></li>
                        <li>
                            <a href="#" class="nav-link has-submenu" @click.prevent="toggleSubmenu">Administración</a>
                            <ul class="submenu" v-show="showSubmenu">
                                <li><a href="#" class="nav-link" @click.prevent>Contratos</a></li>
                                <li><a href="#" class="nav-link" @click.prevent>Recursos</a></li>
                                <li><a href="#" class="nav-link" @click.prevent>Servicios</a></li>
                            </ul>
                        </li>
                        <li><a href="#" class="nav-link" @click.prevent>Nota de trabajo</a></li>
                        <li><a href="#" class="nav-link active" @click.prevent>Registro de usuarios</a></li>
                        <li><a href="#" class="nav-link" @click.prevent>Configuración</a></li>
                        <li><a href="#" class="nav-link" @click.prevent>Perfil</a></li>
                        <li><a href="#" class="nav-link" @click.prevent>Información</a></li>
                        <li><a href="#" class="nav-link" @click.prevent>Ajustes</a></li>
                        <li><a href="#" class="nav-link" @click.prevent>Salir</a></li>
                    </ul>
                </nav>
            </aside>

            <!-- Contenido principal -->
            <main class="dashboard-content">
                <div class="page-header">
                    <h2>Página principal</h2>
                    <h3>Listado de usuarios</h3>
                </div>

                <!-- Tabla usuarios -->
                <div class="users-table-container">
                    <div v-if="cargando" class="loading-spinner">
                        <div class="spinner"></div>
                        <p>Cargando usuarios...</p>
                    </div>

                    <table v-else class="users-table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Apellido Paterno</th>
                                <th>Apellido Materno</th>
                                <th>Estado</th>
                                <th>Fecha de nacimiento</th>
                                <th>Tipo de usuario</th>
                                <th>Nivel de acceso</th>
                                <th>Editar</th>
                                <th>Eliminar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in usuariosPaginados" :key="user.id">
                                <td class="user-cell">{{ user.nombre }}</td>
                                <td>{{ user.apellido_paterno }}</td>
                                <td>{{ user.apellido_materno }}</td>
                                <td>
                                    <span
                                        :class="['status-badge', user.estado === 'Active' ? 'status-active' : 'status-inactive']">
                                        {{ user.estado }}
                                    </span>
                                </td>
                                <td>{{ formatFecha(user.fecha_nacimiento) }}</td>
                                <td>{{ user.tipo_usuario }}</td>
                                <td class="text-center">{{ user.nivel_acceso }}</td>
                                <td class="actions-cell">
                                    <button class="action-btn edit-btn" @click="abrirModalEditar(user)" title="Editar">
                                        ✏️
                                    </button>
                                </td>
                                <td class="actions-cell">
                                    <button class="action-btn delete-btn" @click="confirmarEliminar(user)"
                                        title="Eliminar">
                                        🗑️
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="usuarios.length === 0">
                                <td colspan="9" class="empty-row">No hay usuarios registrados</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Paginador -->
                    <div class="pagination-container" v-if="usuarios.length > 0">
                        <div class="pagination-info">
                            Paginador: {{ paginaActual }} de {{ totalPaginas }}
                        </div>
                        <div class="pagination-controls">
                            <button @click="cambiarPagina(paginaActual - 1)" :disabled="paginaActual === 1"
                                class="page-btn">
                                Anterior
                            </button>
                            <div class="page-numbers">
                                <button v-for="page in totalPaginas" :key="page" @click="cambiarPagina(page)"
                                    :class="['page-number', { 'active-page': paginaActual === page }]">
                                    {{ page }}
                                </button>
                            </div>
                            <button @click="cambiarPagina(paginaActual + 1)" :disabled="paginaActual === totalPaginas"
                                class="page-btn">
                                Siguiente
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal de Edición -->
                <div v-if="modalVisible" class="modal-overlay" @click.self="cerrarModal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3>Editar Usuario</h3>
                            <button class="modal-close" @click="cerrarModal">&times;</button>
                        </div>
                        <form @submit.prevent="guardarUsuario">
                            <div class="form-group">
                                <label>Nombre *</label>
                                <input v-model="formData.nombre" type="text" required placeholder="Ingrese el nombre">
                            </div>
                            <div class="form-group">
                                <label>Apellido Paterno *</label>
                                <input v-model="formData.apellido_paterno" type="text" required
                                    placeholder="Ingrese el apellido paterno">
                            </div>
                            <div class="form-group">
                                <label>Apellido Materno *</label>
                                <input v-model="formData.apellido_materno" type="text" required
                                    placeholder="Ingrese el apellido materno">
                            </div>
                            <div class="form-group">
                                <label>Estado *</label>
                                <select v-model="formData.estado" required>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Fecha de nacimiento *</label>
                                <input v-model="formData.fecha_nacimiento" type="date" required>
                            </div>
                            <div class="form-group">
                                <label>Tipo de usuario *</label>
                                <input v-model="formData.tipo_usuario" type="text" required
                                    placeholder="Ej: Profesor, Estudiante, Admin">
                            </div>
                            <div class="form-group">
                                <label>Nivel de acceso *</label>
                                <input v-model="formData.nivel_acceso" type="number" required min="1" max="10"
                                    placeholder="1-10">
                            </div>
                            <div class="modal-buttons">
                                <button type="button" @click="cerrarModal" class="btn-cancelar">
                                    Cancelar
                                </button>
                                <button type="submit" class="btn-guardar" :disabled="guardando">
                                    {{ guardando ? 'Guardando...' : 'Guardar cambios' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Toast de notificación -->
                <div v-if="toast.show" :class="['toast-notification', toast.type]">
                    {{ toast.message }}
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

// Configuración
const logoPrimeLogistics = '/images/logo-empresa.png'

// Estado reactivo
const sidebarOpen = ref(false)
const showSubmenu = ref(false)
const usuarios = ref([])
const cargando = ref(false)
const guardando = ref(false)
const modalVisible = ref(false)
const usuarioEditando = ref(null)
const paginaActual = ref(1)
const elementosPorPagina = ref(5)

// Toast notification
const toast = ref({
    show: false,
    message: '',
    type: 'success'
})

// Formulario
const formData = ref({
    nombre: '',
    apellido_paterno: '',
    apellido_materno: '',
    estado: 'Active',
    fecha_nacimiento: '',
    tipo_usuario: '',
    nivel_acceso: 1
})

// Computed properties
const totalPaginas = computed(() => {
    return Math.ceil(usuarios.value.length / elementosPorPagina.value)
})

const usuariosPaginados = computed(() => {
    const inicio = (paginaActual.value - 1) * elementosPorPagina.value
    const fin = inicio + elementosPorPagina.value
    return usuarios.value.slice(inicio, fin)
})

// Métodos de notificación
const mostrarToast = (message, type = 'success') => {
    toast.value = {
        show: true,
        message,
        type
    }
    setTimeout(() => {
        toast.value.show = false
    }, 3000)
}

// Métodos CRUD
const cargarUsuarios = async () => {
    cargando.value = true
    try {
        const response = await axios.get(`${API_BASE_URL}/usuarios`)
        if (response.data.success) {
            usuarios.value = response.data.data
            mostrarToast('Usuarios cargados correctamente', 'success')
        }
    } catch (error) {
        console.error('Error al cargar usuarios:', error)
        mostrarToast('Error al cargar los usuarios', 'error')
        // Datos de ejemplo si falla la API
        usuarios.value = [
            {
                id: 1,
                nombre: 'José Luis',
                apellido_paterno: 'Rodríguez',
                apellido_materno: 'Rodríguez',
                estado: 'Active',
                fecha_nacimiento: '2023-01-01',
                tipo_usuario: 'Profesor',
                nivel_acceso: 1
            },
            {
                id: 2,
                nombre: 'María',
                apellido_paterno: 'García',
                apellido_materno: 'García',
                estado: 'Active',
                fecha_nacimiento: '2023-02-02',
                tipo_usuario: 'Profesor',
                nivel_acceso: 2
            },
            {
                id: 3,
                nombre: 'Juan',
                apellido_paterno: 'Pérez',
                apellido_materno: 'Pérez',
                estado: 'Active',
                fecha_nacimiento: '2023-03-03',
                tipo_usuario: 'Profesor',
                nivel_acceso: 3
            },
            {
                id: 4,
                nombre: 'Ana',
                apellido_paterno: 'Martínez',
                apellido_materno: 'Martínez',
                estado: 'Active',
                fecha_nacimiento: '2023-04-04',
                tipo_usuario: 'Profesor',
                nivel_acceso: 4
            }
        ]
    } finally {
        cargando.value = false
    }
}

const guardarUsuario = async () => {
    guardando.value = true
    try {
        const response = await axios.put(
            `${API_BASE_URL}/usuarios/${usuarioEditando.value.id}`,
            formData.value
        )

        if (response.data.success) {
            // Actualizar el usuario en la lista local
            const index = usuarios.value.findIndex(u => u.id === usuarioEditando.value.id)
            if (index !== -1) {
                usuarios.value[index] = { ...usuarios.value[index], ...formData.value }
            }
            mostrarToast('Usuario actualizado correctamente', 'success')
            cerrarModal()
        }
    } catch (error) {
        console.error('Error al guardar usuario:', error)
        if (error.response?.data?.errors) {
            const errors = error.response.data.errors
            const firstError = Object.values(errors)[0]
            mostrarToast(firstError[0], 'error')
        } else {
            mostrarToast(error.response?.data?.message || 'Error al guardar el usuario', 'error')
        }
    } finally {
        guardando.value = false
    }
}

const eliminarUsuario = async (user) => {
    try {
        const response = await axios.delete(`${API_BASE_URL}/usuarios/${user.id}`)

        if (response.data.success) {
            // Eliminar de la lista local
            usuarios.value = usuarios.value.filter(u => u.id !== user.id)
            mostrarToast('Usuario eliminado correctamente', 'success')

            // Ajustar página actual si es necesario
            if (usuarios.value.length === 0) {
                paginaActual.value = 1
            } else if (paginaActual.value > totalPaginas.value) {
                paginaActual.value = totalPaginas.value
            }
        }
    } catch (error) {
        console.error('Error al eliminar usuario:', error)
        mostrarToast('Error al eliminar el usuario', 'error')
    }
}

const confirmarEliminar = (user) => {
    if (confirm(`¿Estás seguro de eliminar a ${user.nombre} ${user.apellido_paterno}?`)) {
        eliminarUsuario(user)
    }
}

const abrirModalEditar = (user) => {
    usuarioEditando.value = user
    formData.value = {
        nombre: user.nombre,
        apellido_paterno: user.apellido_paterno,
        apellido_materno: user.apellido_materno,
        estado: user.estado,
        fecha_nacimiento: user.fecha_nacimiento,
        tipo_usuario: user.tipo_usuario,
        nivel_acceso: user.nivel_acceso
    }
    modalVisible.value = true
}

const cerrarModal = () => {
    modalVisible.value = false
    usuarioEditando.value = null
    formData.value = {
        nombre: '',
        apellido_paterno: '',
        apellido_materno: '',
        estado: 'Active',
        fecha_nacimiento: '',
        tipo_usuario: '',
        nivel_acceso: 1
    }
}

// Utilidades
const formatFecha = (fecha) => {
    if (!fecha) return ''
    const date = new Date(fecha)
    const day = date.getDate().toString().padStart(2, '0')
    const month = (date.getMonth() + 1).toString().padStart(2, '0')
    const year = date.getFullYear()
    return `${day}/${month}/${year}`
}

const cambiarPagina = (page) => {
    if (page >= 1 && page <= totalPaginas.value) {
        paginaActual.value = page
    }
}

const toggleSubmenu = () => {
    showSubmenu.value = !showSubmenu.value
}

// Lifecycle
onMounted(() => {
    cargarUsuarios()
})
</script>

<style scoped>
/* Estilos principales */
.dashboard-container {
    min-height: 100vh;
    background: #f5f7fa;
}

.dashboard-header {
    background: white;
    padding: 1rem 2rem;
    display: flex;
    align-items: center;
    gap: 2rem;
    border-bottom: 1px solid #e0e0e0;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.logo {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.logo-icon img {
    width: 32px;
    height: 32px;
}

.logo-text {
    font-size: 1.2rem;
    font-weight: bold;
    color: #2c3e50;
}

.page-title {
    font-size: 1.2rem;
    color: #555;
    font-weight: normal;
    margin: 0;
}

.page_subtitle {
    font-size: 1rem;
    color: #555;
    font-weight: normal;
    margin: 0;
}

.dashboard-layout {
    display: flex;
    min-height: calc(100vh - 70px);
}

/* Sidebar */
.sidebar {
    width: 260px;
    background: #2c3e50;
    color: white;
    flex-shrink: 0;
    transition: transform 0.3s ease;
}

.side-nav {
    padding: 1.5rem 0;
}

.logo-prime {
    font-size: 1.5rem;
    font-weight: bold;
    padding: 0 1.5rem;
    margin-bottom: 2rem;
    color: #ecf0f1;
}

.main-nav-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.main-nav-list li {
    margin-bottom: 0.25rem;
}

.nav-link {
    display: block;
    padding: 0.75rem 1.5rem;
    color: #bdc3c7;
    text-decoration: none;
    transition: all 0.3s;
    font-size: 0.95rem;
    cursor: pointer;
}

.nav-link:hover,
.nav-link.active {
    background: #34495e;
    color: white;
}

.has-submenu {
    position: relative;
}

.submenu {
    list-style: none;
    padding-left: 2rem;
    margin: 0.25rem 0;
}

.submenu .nav-link {
    padding: 0.5rem 1.5rem;
    font-size: 0.85rem;
}

/* Contenido principal */
.dashboard-content {
    flex: 1;
    padding: 2rem;
    background: #f5f7fa;
    position: relative;
}

.page-header {
    margin-bottom: 1.5rem;
}

.page-header h2 {
    color: #2c3e50;
    margin: 0 0 0.25rem 0;
    font-size: 1.5rem;
}

.page-header h3 {
    color: #7f8c8d;
    margin: 0;
    font-size: 1.1rem;
    font-weight: normal;
}

/* Tabla */
.users-table-container {
    background: white;
    border-radius: 8px;
    overflow-x: auto;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.users-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.9rem;
}

.users-table th,
.users-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #ecf0f1;
}

.users-table th {
    background: #f8f9fa;
    font-weight: 600;
    color: #2c3e50;
    border-bottom: 2px solid #e0e0e0;
}

.users-table tbody tr:hover {
    background: #f8f9fa;
}

.text-center {
    text-align: center;
}

.user-cell {
    font-weight: 500;
}

.empty-row {
    text-align: center;
    color: #7f8c8d;
    padding: 2rem;
}

/* Estados */
.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.status-active {
    background: #d4edda;
    color: #155724;
}

.status-inactive {
    background: #f8d7da;
    color: #721c24;
}

/* Acciones */
.actions-cell {
    text-align: center;
    width: 50px;
}

.action-btn {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 1.1rem;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    transition: all 0.2s;
}

.edit-btn:hover {
    background: #e0f2fe;
    transform: scale(1.05);
}

.delete-btn:hover {
    background: #fee2e2;
    transform: scale(1.05);
}

/* Paginador */
.pagination-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: white;
    border-top: 1px solid #ecf0f1;
    flex-wrap: wrap;
    gap: 1rem;
}

.pagination-info {
    color: #7f8c8d;
    font-size: 0.85rem;
}

.pagination-controls {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.page-btn {
    padding: 0.35rem 0.75rem;
    border: 1px solid #ddd;
    background: white;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s;
}

.page-btn:hover:not(:disabled) {
    background: #3498db;
    color: white;
    border-color: #3498db;
}

.page-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.page-numbers {
    display: flex;
    gap: 0.25rem;
}

.page-number {
    padding: 0.35rem 0.65rem;
    border: 1px solid #ddd;
    background: white;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s;
}

.page-number:hover {
    background: #ecf0f1;
}

.active-page {
    background: #3498db;
    color: white;
    border-color: #3498db;
}

/* Loading spinner */
.loading-spinner {
    text-align: center;
    padding: 3rem;
    color: #7f8c8d;
}

.spinner {
    border: 3px solid #f3f3f3;
    border-top: 3px solid #3498db;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 1s linear infinite;
    margin: 0 auto 1rem;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}

/* Modal */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

.modal-content {
    background: white;
    border-radius: 12px;
    width: 90%;
    max-width: 550px;
    max-height: 90vh;
    overflow-y: auto;
    animation: slideUp 0.3s ease;
}

@keyframes slideUp {
    from {
        transform: translateY(50px);
        opacity: 0;
    }

    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 1.5rem 0 1.5rem;
    border-bottom: 1px solid #ecf0f1;
}

.modal-header h3 {
    margin: 0;
    color: #2c3e50;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #7f8c8d;
    transition: color 0.2s;
}

.modal-close:hover {
    color: #e74c3c;
}

/* Formulario */
form {
    padding: 1.5rem;
}

.form-group {
    margin-bottom: 1.2rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: #2c3e50;
    font-weight: 500;
    font-size: 0.9rem;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 0.6rem 0.8rem;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 0.9rem;
    transition: all 0.2s;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #3498db;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
}

.modal-buttons {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
    justify-content: flex-end;
}

.btn-cancelar,
.btn-guardar {
    padding: 0.6rem 1.2rem;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.2s;
}

.btn-cancelar {
    background: #e74c3c;
    color: white;
}

.btn-cancelar:hover {
    background: #c0392b;
    transform: translateY(-1px);
}

.btn-guardar {
    background: #2a9d8f;
    color: white;
}

.btn-guardar:hover:not(:disabled) {
    background: #248277;
    transform: translateY(-1px);
}

.btn-guardar:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Toast notification */
.toast-notification {
    position: fixed;
    bottom: 20px;
    right: 20px;
    padding: 1rem 1.5rem;
    border-radius: 8px;
    color: white;
    font-size: 0.9rem;
    font-weight: 500;
    z-index: 2000;
    animation: slideInRight 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.toast-notification.success {
    background: #2a9d8f;
}

.toast-notification.error {
    background: #e74c3c;
}

@keyframes slideInRight {
    from {
        transform: translateX(100px);
        opacity: 0;
    }

    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Responsive */
@media (max-width: 768px) {
    .sidebar {
        position: fixed;
        transform: translateX(-100%);
        z-index: 100;
        height: 100%;
        top: 0;
        left: 0;
    }

    .sidebar-mobile-open {
        transform: translateX(0);
    }

    .dashboard-content {
        padding: 1rem;
    }

    .users-table th,
    .users-table td {
        padding: 0.75rem 0.5rem;
        font-size: 0.8rem;
    }

    .pagination-container {
        flex-direction: column;
        align-items: flex-start;
    }

    .modal-content {
        width: 95%;
        margin: 1rem;
    }
}
</style>