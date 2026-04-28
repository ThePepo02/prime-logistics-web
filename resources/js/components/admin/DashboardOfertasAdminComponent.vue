<template>
    <div class="operaciones-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <div class="logo-icon">
                    <img :src="logoPrimeLogistics" alt="logo Prime Logistics">
                </div>
            </div>
            <nav class="nav-menu">
                <div class="nav-section">
                    <div class="nav-title">Administración</div>
                    <ul>
                        <li><a href="#">Dashboard</a></li>
                        <li><a href="#">Gestión de Usuarios</a></li>
                    </ul>
                </div>
                <div class="nav-section">
                    <div class="nav-title">Comunicaciones</div>
                    <ul>
                        <li><a href="#">Todas las Oficinas</a></li>
                        <li><a href="#" class="active">Operaciones Activas</a></li>
                    </ul>
                </div>
                <div class="nav-section">
                    <div class="nav-title">Sistema</div>
                    <ul>
                        <li><a href="#">Datos Maestros</a></li>
                        <li><a href="#">Configuración</a></li>
                    </ul>
                </div>
            </nav>
        </div>

        <!-- Contenido principal -->
        <div class="main-content">
            <div class="header">
                <h1>Operaciones Activas</h1>
                <button @click="refreshData" class="refresh-btn" :disabled="loading">
                    <span v-if="loading">Cargando...</span>
                    <span v-else>⟳ Actualizar</span>
                </button>
            </div>

            <!-- Loading y Error -->
            <div v-if="error" class="error-message">
                {{ error }}
                <button @click="refreshData">Reintentar</button>
            </div>

            <!-- Distribución por Modo de Transporte -->
            <div class="distribucion-section" v-if="!loading || distribucionModos.length">
                <h3>Distribución por Modo de Transporte</h3>
                <p class="subtitulo">Operaciones activas en curso</p>
                <div class="modos-grid">
                    <div class="modo-item" v-for="modo in distribucionModos" :key="modo.nombre">
                        <div class="modo-nombre">{{ modo.nombre }}</div>
                        <div class="modo-stats">
                            <span class="modo-cantidad">{{ modo.cantidad }} envíos</span>
                            <span class="modo-porcentaje">{{ modo.porcentaje }}%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" :style="{ width: modo.porcentaje + '%', backgroundColor: modo.color }"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resumen operaciones -->
            <div class="operaciones-resumen">
                <h3>Operaciones en Curso</h3>
                <div class="total-envios">{{ totalEnviosActivos }} envíos activos</div>
            </div>

<!-- Filtros -->
            <div class="filters-bar">
                <input 
                    v-model="filters.search" 
                    type="text" 
                    placeholder="Buscar operación:" 
                    class="search-input" 
                    @input="handleSearch"
                />
                <select v-model="filters.modo" class="filter-select" @change="handleFilterChange">
                    <option value="">Todos los modos</option>
                    <option value="Marítimo">Marítimo</option>
                    <option value="Aéreo">Aéreo</option>
                    <option value="Terrestre">Terrestre</option>
                    <option value="Multimodal">Multimodal</option>
                </select>
                <select v-model="filters.ruta" class="filter-select" @change="handleFilterChange">
                    <option value="">Todas las rutas</option>
                    <option v-for="ruta in rutasDisponibles" :key="ruta" :value="ruta">
                        {{ ruta }}
                    </option>
                </select>
            </div>

            <!-- Tabla -->
            <div class="table-wrapper">
                <table class="operaciones-table">
                    <thead>
                        <tr>
                            <th>ID Origen</th>
                            <th>Cliente</th>
                            <th>Empresa</th>
                            <th>Modo</th>
                            <th>Ruta</th>
                            <th>ETD</th>
                            <th>ETA</th>
                            <th>Fase Actual</th>
                            <th>Estado</th>
                            <th v-if="isAdmin">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="loading && !operaciones.length">
                            <td colspan="10" class="loading-cell">
                                <div class="spinner"></div>
                                Cargando operaciones...
                            </td>
                        </tr>
                        <tr v-else-if="!loading && !operaciones.length">
                            <td colspan="10" class="empty-cell">
                                No se encontraron operaciones
                            </td>
                        </tr>
                        <tr v-for="operacion in paginatedOperaciones" :key="operacion.id">
                            <td class="id-cell">{{ operacion.codigo }}</td>
                            <td>{{ operacion.cliente }}</td>
                            <td>{{ operacion.empresa }}</td>
                            <td><span class="modo-badge">{{ operacion.modo }}</span></td>
                            <td>{{ operacion.ruta }}</td>
                            <td>{{ formatDate(operacion.etd) }}</td>
                            <td>{{ formatDate(operacion.eta) }}</td>
                            <td>{{ operacion.fase_actual }}</td>
                            <td>
                                <span :class="['estado-badge', getEstadoClass(operacion.estado)]">
                                    {{ operacion.estado }}
                                </span>
                            </td>
                            <td v-if="isAdmin">
                                <button @click="editOperacion(operacion)" class="action-btn edit">✏️</button>
                                <button @click="deleteOperacion(operacion.id)" class="action-btn delete">🗑️</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="pagination-info" v-if="totalItems > 0">
                Mostrando {{ paginationInfo.from }} - {{ paginationInfo.to }} de {{ totalItems }} operaciones
            </div>

            <div class="pagination" v-if="totalPages > 1">
                <button class="page-btn" :disabled="currentPage === 1" @click="goToPage(currentPage - 1)">
                    Anterior
                </button>
                <button 
                    v-for="page in visiblePages" 
                    :key="page" 
                    :class="['page-btn', { active: page === currentPage }]"
                    @click="goToPage(page)"
                    :disabled="page === '...'"
                >
                    {{ page }}
                </button>
                <button class="page-btn" :disabled="currentPage === totalPages" @click="goToPage(currentPage + 1)">
                    Siguiente
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'

const logoPrimeLogistics = '/images/logo-empresa.png'

// Estado
const currentPage = ref(1)
const itemsPerPage = ref(10)
const totalItems = ref(0)
const loading = ref(false)
const error = ref(null)
const isAdmin = ref(true) // Cambiar según roles de usuario

// Filtros
const filters = ref({
    search: '',
    modo: '',
    ruta: ''
})

// Datos desde backend
const distribucionModos = ref([])
const totalEnviosActivos = ref(0)
const operaciones = ref([])
const rutasDisponibles = ref([])

// URL base de la API
const API_BASE_URL = import.meta.env.VITE_API_URL || '/api'

// Funciones API
const fetchDistribucionModos = async () => {
    try {
        const response = await fetch(`${API_BASE_URL}/operaciones/distribucion-modos`)
        if (!response.ok) throw new Error('Error al cargar distribución de modos')
        const data = await response.json()
        distribucionModos.value = data
    } catch (err) {
        console.error('Error fetching distribucion modos:', err)
    }
}

const fetchTotalEnviosActivos = async () => {
    try {
        const response = await fetch(`${API_BASE_URL}/operaciones/total-activos`)
        if (!response.ok) throw new Error('Error al cargar total de envíos')
        const data = await response.json()
        totalEnviosActivos.value = data.total
    } catch (err) {
        console.error('Error fetching total envios:', err)
    }
}

const fetchOperaciones = async () => {
    loading.value = true
    error.value = null
    
    try {
        const params = new URLSearchParams({
            page: currentPage.value,
            limit: itemsPerPage.value,
            ...(filters.value.search && { search: filters.value.search }),
            ...(filters.value.modo && { modo: filters.value.modo }),
            ...(filters.value.ruta && { ruta: filters.value.ruta })
        })
        
        const response = await fetch(`${API_BASE_URL}/operaciones?${params}`)
        if (!response.ok) throw new Error('Error al cargar operaciones')
        
        const data = await response.json()
        operaciones.value = data.operaciones
        totalItems.value = data.total
    } catch (err) {
        console.error('Error fetching operaciones:', err)
        error.value = 'Error al cargar las operaciones'
        operaciones.value = []
        totalItems.value = 0
    } finally {
        loading.value = false
    }
}

const fetchRutasDisponibles = async () => {
    try {
        const response = await fetch(`${API_BASE_URL}/operaciones/rutas`)
        if (!response.ok) throw new Error('Error al cargar rutas')
        const data = await response.json()
        rutasDisponibles.value = data
    } catch (err) {
        console.error('Error fetching rutas:', err)
    }
}

// Handlers
let searchTimeout
const handleSearch = () => {
    clearTimeout(searchTimeout)
    searchTimeout = setTimeout(() => {
        currentPage.value = 1
        fetchOperaciones()
    }, 500)
}

const handleFilterChange = () => {
    currentPage.value = 1
    fetchOperaciones()
}

const refreshData = async () => {
    await Promise.all([
        fetchDistribucionModos(),
        fetchTotalEnviosActivos(),
        fetchOperaciones(),
        fetchRutasDisponibles()
    ])
}

const editOperacion = (operacion) => {
    console.log('Editar operación:', operacion)
    // Implementar modal de edición
}

const deleteOperacion = async (id) => {
    if (!confirm('¿Estás seguro de eliminar esta operación?')) return
    
    try {
        const response = await fetch(`${API_BASE_URL}/operaciones/${id}`, {
            method: 'DELETE'
        })
        
        if (!response.ok) throw new Error('Error al eliminar')
        
        await refreshData()
    } catch (err) {
        console.error('Error deleting operacion:', err)
        error.value = 'Error al eliminar la operación'
    }
}

// Computed
const totalPages = computed(() => Math.ceil(totalItems.value / itemsPerPage.value))

const paginatedOperaciones = computed(() => operaciones.value)

const paginationInfo = computed(() => {
    const start = totalItems.value === 0 ? 0 : (currentPage.value - 1) * itemsPerPage.value + 1
    const end = Math.min(currentPage.value * itemsPerPage.value, totalItems.value)
    return { from: start, to: end }
})

const visiblePages = computed(() => {
    const maxVisible = 5
    const pages = []
    
    if (totalPages.value <= maxVisible) {
        for (let i = 1; i <= totalPages.value; i++) pages.push(i)
    } else {
        if (currentPage.value <= 3) {
            pages.push(1, 2, 3, 4, '...', totalPages.value)
        } else if (currentPage.value >= totalPages.value - 2) {
            pages.push(1, '...', totalPages.value - 3, totalPages.value - 2, totalPages.value - 1, totalPages.value)
        } else {
            pages.push(1, '...', currentPage.value - 1, currentPage.value, currentPage.value + 1, '...', totalPages.value)
        }
    }
    
    return pages
})

// Utils
const formatDate = (date) => {
    if (!date) return '-'
    const d = new Date(date)
    return d.toLocaleDateString('es-ES', { day: '2-digit', month: 'short' })
}

const getEstadoClass = (estado) => {
    const classes = {
        'EN TRANSITO': 'estado-transito',
        'COMPLETADO': 'estado-completado',
        'CANCELADO': 'estado-cancelado'
    }
    return classes[estado] || 'estado-transito'
}

const goToPage = (page) => {
    if (page === '...' || page < 1 || page > totalPages.value) return
    currentPage.value = page
    fetchOperaciones()
}

// Watch para cambios de página
watch(currentPage, () => {
    fetchOperaciones()
})

// Lifecycle
onMounted(() => {
    refreshData()
})
</script>

<style scoped>
/* Mantén tus estilos existentes y añade: */

.refresh-btn {
    padding: 8px 16px;
    background: #3498db;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-left: auto;
}

.refresh-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.error-message {
    background: #f8d7da;
    color: #721c24;
    padding: 12px;
    border-radius: 4px;
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.error-message button {
    background: #dc3545;
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 4px;
    cursor: pointer;
}

.loading-cell, .empty-cell {
    text-align: center;
    padding: 40px !important;
}

.spinner {
    border: 3px solid #f3f3f3;
    border-top: 3px solid #3498db;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    animation: spin 1s linear infinite;
    margin: 0 auto 10px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.estado-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.estado-transito {
    background: #fff3cd;
    color: #856404;
}

.estado-completado {
    background: #d4edda;
    color: #155724;
}

.estado-cancelado {
    background: #f8d7da;
    color: #721c24;
}

.action-btn {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 16px;
    margin: 0 5px;
    padding: 5px;
}

.action-btn.edit:hover {
    transform: scale(1.1);
}

.action-btn.delete:hover {
    transform: scale(1.1);
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.header h1 {
    margin: 0;
}
</style>
