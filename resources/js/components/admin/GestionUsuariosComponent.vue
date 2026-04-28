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
            <!-- Menú lateral -->
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
                        <div class="kpi-number">{{ stats.total }}</div>
                        <div class="kpi-label">Usuarios Totales</div>
                    </div>

                    <div class="user-kpi-card">
                        <div class="kpi-number">{{ stats.activos }}</div>
                        <div class="kpi-label">Usuarios Activos</div>
                    </div>

                    <div class="user-kpi-card">
                        <div class="kpi-number">{{ stats.desactivados }}</div>
                        <div class="kpi-label">Desactivados</div>
                    </div>
                </div>

                <!-- Tabla usuarios -->
                <div class="users-table-container">
                    <div class="table-header">
                        <h3>Usuarios del sistema</h3>
                        <span class="table-subtitle">Todos los activos registrados</span>
                    </div>

                    <div v-if="cargando" class="loading-spinner">
                        Cargando usuarios...
                    </div>

                    <table v-else class="users-table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Empresa</th>
                                <th>Último Acceso</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in usuarios" :key="user.id">
                                <td class="user-cell">{{ user.nom }} {{ user.cognoms }}</td>
                                <td>{{ user.correu }}</td>
                                <td>{{ user.empresa }}</td>
                                <td>{{ formatFecha(user.ultimo_acceso) }}</td>
                                <td>
                                    <div :class="['status-indicator', user.activo ? 'active' : 'inactive']"></div>
                                </td>
                                <td class="actions">
                                    <button class="action-btn edit-btn" @click="editarUsuario(user)" title="Editar">✏️</button>
                                    <button class="action-btn delete-btn" @click="eliminarUsuario(user)" title="Eliminar">🗑️</button>
                                    <button class="action-btn toggle-btn" @click="toggleEstadoUsuario(user)" :title="user.activo ? 'Desactivar' : 'Activar'">
                                        {{ user.activo ? '🔴' : '🟢' }}
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="table-footer">
                        <span class="pagination-note">Mostrando {{ usuarios.length }} de {{ stats.total }} usuarios</span>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="action-buttons">
                    <button class="btn-nuevo-usuario" @click="handleCreateUser">
                        <img :src="imgNuevoUsuario" alt="icono de añadir un nuevo usuario">Nuevo Usuario
                        <NuevoUsuario @create-user="handleCreateUser" @close="modalOpen = false" />
                    </button>
                    <button class="btn-on" @click="activarTodos">
                        Activar Todos
                    </button>
                </div>

                <!-- Modal para nuevo/editar usuario -->
                <div v-if="modalVisible" class="modal-overlay" @click.self="cerrarModal">
                    <div class="modal-content">
                        <h3>{{ modoEdicion ? 'Editar Usuario' : 'Nuevo Usuario' }}</h3>
                        <form @submit.prevent="guardarUsuario">
                            <div class="form-group">
                                <label>Nombre</label>
                                <input v-model="formData.nom" type="text" required>
                            </div>
                            <div class="form-group">
                                <label>Apellidos</label>
                                <input v-model="formData.cognoms" type="text" required>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input v-model="formData.correu" type="email" required :disabled="modoEdicion">
                            </div>
                            <div class="form-group">
                                <label>Empresa</label>
                                <input v-model="formData.empresa" type="text" required>
                            </div>
                            <div class="form-group">
                                <label>Contraseña</label>
                                <input v-model="formData.contrasenya" type="password" :required="!modoEdicion">
                            </div>
                            <div class="modal-buttons">
                                <button type="button" @click="cerrarModal" class="btn-cancelar">Cancelar</button>
                                <button type="submit" class="btn-guardar">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const logoPrimeLogistics = '/images/logo-empresa.png'
const imgNuevoUsuario = '/images/iconoNuevoUsuario.png'

// Estado
const sidebarOpen = ref(false)
const usuarios = ref([])
const stats = ref({ total: 0, activos: 0, desactivados: 0 })
const cargando = ref(false)
const modalVisible = ref(false)
const modoEdicion = ref(false)
const usuarioEditando = ref(null)

// Formulario
const formData = ref({
    nom: '',
    cognoms: '',
    correu: '',
    empresa: '',
    contrasenya: ''
})

// Métodos
const cargarUsuarios = async () => {
    cargando.value = true
    try {
        const response = await axios.get(`${API_BASE_URL}/usuarios`)
        usuarios.value = response.data.data
        await cargarEstadisticas()
    } catch (error) {
        console.error('Error al cargar usuarios:', error)
        alert('Error al cargar los usuarios')
    } finally {
        cargando.value = false
    }
}

const cargarEstadisticas = async () => {
    try {
        const response = await axios.get(`${API_BASE_URL}/usuarios/stats`)
        stats.value = response.data
    } catch (error) {
        console.error('Error al cargar estadísticas:', error)
    }
}

const handleCreateUser = (userData) => {
    console.log('Usuario creado:', userData)
}

const guardarUsuario = async () => {
    try {
        if (modoEdicion.value) {
            await axios.put(`${API_BASE_URL}/usuarios/${usuarioEditando.value.id}`, formData.value)
            alert('Usuario actualizado correctamente')
        } else {
            await axios.post(`${API_BASE_URL}/usuarios`, formData.value)
            alert('Usuario creado correctamente')
        }
        cerrarModal()
        await cargarUsuarios()
    } catch (error) {
        console.error('Error al guardar usuario:', error)
        alert(error.response?.data?.message || 'Error al guardar el usuario')
    }
}

const editarUsuario = (user) => {
    modoEdicion.value = true
    usuarioEditando.value = user
    formData.value = {
        nom: user.nom,
        cognoms: user.cognoms,
        correu: user.correu,
        empresa: user.empresa,
        contrasenya: ''
    }
    modalVisible.value = true
}

const eliminarUsuario = async (user) => {
    if (confirm(`¿Estás seguro de eliminar a ${user.nom} ${user.cognoms}?`)) {
        try {
            await axios.delete(`${API_BASE_URL}/usuarios/${user.id}`)
            alert('Usuario eliminado correctamente')
            await cargarUsuarios()
        } catch (error) {
            console.error('Error al eliminar usuario:', error)
            alert('Error al eliminar el usuario')
        }
    }
}

const toggleEstadoUsuario = async (user) => {
    try {
        await axios.put(`${API_BASE_URL}/usuarios/${user.id}`, {
            activo: !user.activo
        })
        await cargarUsuarios()
    } catch (error) {
        console.error('Error al cambiar estado:', error)
        alert('Error al cambiar el estado del usuario')
    }
}

const activarTodos = async () => {
    if (confirm('¿Activar todos los usuarios?')) {
        try {
            const promesas = usuarios.value.map(user => 
                axios.put(`${API_BASE_URL}/usuarios/${user.id}`, { activo: true })
            )
            await Promise.all(promesas)
            await cargarUsuarios()
            alert('Todos los usuarios han sido activados')
        } catch (error) {
            console.error('Error al activar usuarios:', error)
            alert('Error al activar los usuarios')
        }
    }
}

const cerrarModal = () => {
    modalVisible.value = false
    modoEdicion.value = false
    usuarioEditando.value = null
}

const formatFecha = (fecha) => {
    if (!fecha) return 'Nunca'
    const date = new Date(fecha)
    return date.toLocaleString()
}

// Cargar datos al montar el componente
onMounted(() => {
    cargarUsuarios()
})
</script>

<style scoped>
/* Mantén todos los estilos existentes y añade estos nuevos */

.loading-spinner {
    text-align: center;
    padding: 2rem;
    color: #7f8c8d;
}

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
}

.modal-content {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    width: 90%;
    max-width: 500px;
}

.modal-content h3 {
    margin-bottom: 1.5rem;
    color: #2c3e50;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: #2c3e50;
    font-weight: 500;
}

.form-group input {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 0.9rem;
}

.form-group input:focus {
    outline: none;
    border-color: #1a5d8c;
}

.modal-buttons {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
    justify-content: flex-end;
}

.btn-cancelar,
.btn-guardar {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.btn-cancelar {
    background: #e74c3c;
    color: white;
}

.btn-guardar {
    background: #2a9d8f;
    color: white;
}

.toggle-btn {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 1rem;
}
</style>
