<template>
    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- SIDEBAR -->
            <aside class="col-auto bg-dark text-white vh-100" style="width: 280px;">
                <div class="d-flex flex-column h-100">
                    <div class="text-center py-4 border-bottom border-secondary">
                        <img :src="logoPrimeLogistics" alt="Prime Logistics" class="img-fluid"
                            style="max-width: 180px;">
                    </div>

                    <nav class="nav flex-column mt-4 px-3 flex-grow-1">
                        <!-- Administración -->
                        <h6 class="text-secondary text-uppercase small mb-3 px-2">Administración</h6>

                        <a href="#" @click.prevent="activeSection = 'dashboard'"
                            class="nav-link text-white mb-1 rounded"
                            :class="activeSection === 'dashboard' ? 'bg-primary active' : 'hover-bg-secondary'">
                            <i class="bi bi-grid-3x3-gap-fill me-2"></i>
                            Dashboard
                        </a>

                        <a href="#" @click.prevent="activeSection = 'usuarios'" class="nav-link text-white mb-1 rounded"
                            :class="activeSection === 'usuarios' ? 'bg-primary active' : 'hover-bg-secondary'">
                            <i class="bi bi-people-fill me-2"></i>
                            Gestión de Usuarios
                        </a>

                        <!-- Comunicaciones -->
                        <h6 class="text-secondary text-uppercase small mt-4 mb-3 px-2">Comunicaciones</h6>

                        <a href="#" @click.prevent="activeSection = 'oficinas'" class="nav-link text-white mb-1 rounded"
                            :class="activeSection === 'oficinas' ? 'bg-primary active' : 'hover-bg-secondary'">
                            <i class="bi bi-building me-2"></i>
                            Todas las Oficinas
                        </a>

                        <a href="#" @click.prevent="activeSection = 'operaciones'"
                            class="nav-link text-white mb-1 rounded"
                            :class="activeSection === 'operaciones' ? 'bg-primary active' : 'hover-bg-secondary'">
                            <i class="bi bi-truck me-2"></i>
                            Operaciones Activas
                        </a>

                        <!-- Sistema -->
                        <h6 class="text-secondary text-uppercase small mt-4 mb-3 px-2">Sistema</h6>

                        <a href="#" @click.prevent="activeSection = 'datos-maestros'"
                            class="nav-link text-white mb-1 rounded"
                            :class="activeSection === 'datos-maestros' ? 'bg-primary active' : 'hover-bg-secondary'">
                            <i class="bi bi-database-fill me-2"></i>
                            Datos Maestros
                        </a>

                        <a href="#" @click.prevent="activeSection = 'configuracion'"
                            class="nav-link text-white mb-1 rounded"
                            :class="activeSection === 'configuracion' ? 'bg-primary active' : 'hover-bg-secondary'">
                            <i class="bi bi-gear-fill me-2"></i>
                            Configuración
                        </a>
                    </nav>

                    <!-- PERFIL USUARIO -->
                    <div class="border-top border-secondary p-3 mt-auto">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-success rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                style="width: 40px; height: 40px;">
                                {{ inicialesUsuario }}
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-white mb-0 fw-medium">{{ nombreUsuario }}</p>
                                <small class="text-secondary text-uppercase">Administrador</small>
                            </div>
                            <button @click="logout" class="btn btn-sm btn-outline-secondary text-white"
                                title="Cerrar sesión">
                                <i class="bi bi-box-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- ÁREA PRINCIPAL -->
            <main class="col bg-light" style="height: 100vh; overflow-y: auto;">

                <!-- TOPBAR -->
                <div class="bg-white border-bottom px-4 py-3 sticky-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0 text-dark">Operaciones Activas</h4>
                            <small class="text-muted">Gestión y seguimiento de operaciones activas</small>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <button class="btn btn-link text-dark position-relative" @click="clickNotification">
                                <i class="bi bi-bell fs-5"></i>
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    3
                                </span>
                            </button>
                            <div @click="clickProfile" class="cursor-pointer">
                                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                    style="width: 38px; height: 38px;">
                                    {{ inicialesUsuario }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CONTENIDO -->
                <div class="p-4">

                    <!-- Error message -->
                    <div v-if="error" class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        {{ error }}
                        <button type="button" class="btn-close" @click="error = null"></button>
                        <button @click="refreshData" class="btn btn-sm btn-danger ms-3">Reintentar</button>
                    </div>

                    <!-- Estadísticas / KPIs -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="text-muted mb-2">Total Ofertas</h6>
                                            <h2 class="mb-0">{{ formatNumber(kpiData.totalOfertas) }}</h2>
                                        </div>
                                        <div class="bg-primary bg-opacity-10 rounded p-2">
                                            <i class="bi bi-file-text text-primary fs-4"></i>
                                        </div>
                                    </div>
                                    <small class="text-muted mt-2 d-block">
                                        <i class="bi bi-arrow-up text-success"></i> 12% vs mes anterior
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="text-muted mb-2">Todas las ofertas</h6>
                                            <h2 class="mb-0">{{ formatNumber(totalItems) }}</h2>
                                        </div>
                                        <div class="bg-info bg-opacity-10 rounded p-2">
                                            <i class="bi bi-list-check text-info fs-4"></i>
                                        </div>
                                    </div>
                                    <small class="text-muted mt-2 d-block">
                                        <i class="bi bi-arrow-up text-success"></i> 8% vs mes anterior
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="text-muted mb-2">Completadas</h6>
                                            <h2 class="mb-0">{{ formatNumber(operacionesCompletadas) }}</h2>
                                        </div>
                                        <div class="bg-success bg-opacity-10 rounded p-2">
                                            <i class="bi bi-check-circle text-success fs-4"></i>
                                        </div>
                                    </div>
                                    <small class="text-muted mt-2 d-block">
                                        <i class="bi bi-arrow-up text-success"></i> 5% vs mes anterior
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="text-muted mb-2">Pendientes</h6>
                                            <h2 class="mb-0">{{ formatNumber(operacionesPendientes) }}</h2>
                                        </div>
                                        <div class="bg-warning bg-opacity-10 rounded p-2">
                                            <i class="bi bi-hourglass-split text-warning fs-4"></i>
                                        </div>
                                    </div>
                                    <small class="text-muted mt-2 d-block">
                                        <i class="bi bi-arrow-down text-danger"></i> 3% vs mes anterior
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Distribución por Modo de Transporte -->
                    <div class="card border-0 shadow-sm mb-4" v-if="distribucionModos.length">
                        <div class="card-header bg-white border-0 pt-4 pb-0">
                            <h5 class="mb-0">Distribución por Modo de Transporte</h5>
                            <small class="text-muted">Operaciones activas en curso</small>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6" v-for="modo in distribucionModos" :key="modo.nombre">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="fw-medium">{{ modo.nombre }}</span>
                                        <span class="text-muted">{{ modo.cantidad }} envíos ({{ modo.porcentaje
                                            }}%)</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar" :class="modo.colorClass"
                                            :style="{ width: modo.porcentaje + '%' }" :aria-valuenow="modo.porcentaje"
                                            aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Resumen operaciones -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="mb-0">Operaciones en Curso</h5>
                            <small class="text-muted">{{ totalEnviosActivos }} envíos activos</small>
                        </div>
                        <button @click="refreshData" class="btn btn-primary btn-sm" :disabled="loading">
                            <i class="bi bi-arrow-repeat me-1"></i>
                            <span v-if="loading">Cargando...</span>
                            <span v-else>Actualizar</span>
                        </button>
                    </div>

                    <!-- Filtros -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label small text-muted">Buscar operación</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="bi bi-search"></i>
                                        </span>
                                        <input v-model="filters.search" type="text"
                                            class="form-control border-start-0 ps-0"
                                            placeholder="ID, cliente o empresa..." @input="handleSearch">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small text-muted">Modo de transporte</label>
                                    <select v-model="filters.modo" class="form-select" @change="handleFilterChange">
                                        <option value="">Todos los modos</option>
                                        <option value="Marítimo">Marítimo</option>
                                        <option value="Aéreo">Aéreo</option>
                                        <option value="Terrestre">Terrestre</option>
                                        <option value="Multimodal">Multimodal</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small text-muted">Ruta</label>
                                    <select v-model="filters.ruta" class="form-select" @change="handleFilterChange">
                                        <option value="">Todas las rutas</option>
                                        <option v-for="ruta in rutasDisponibles" :key="ruta" :value="ruta">
                                            {{ ruta }}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button @click="clearFilters" class="btn btn-outline-secondary w-100">
                                        <i class="bi bi-eraser me-1"></i>
                                        Limpiar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Loading -->
                    <div v-if="loading && !operaciones.length" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="text-muted mt-2">Cargando operaciones...</p>
                    </div>

                    <!-- Tabla de operaciones -->
                    <div v-else class="card border-0 shadow-sm">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
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
                                        <tr v-for="operacion in paginatedOperaciones" :key="operacion.id">
                                            <td class="fw-medium">{{ operacion.codigo }}</td>
                                            <td>{{ operacion.cliente }}</td>
                                            <td class="text-muted">{{ operacion.empresa }}</td>
                                            <td>
                                                <span :class="'badge ' + getModoClass(operacion.modo)">
                                                    {{ operacion.modo }}
                                                </span>
                                            </td>
                                            <td>{{ operacion.ruta }}</td>
                                            <td>{{ formatDate(operacion.etd) }}</td>
                                            <td>{{ formatDate(operacion.eta) }}</td>
                                            <td>
                                                <span class="badge bg-secondary">{{ operacion.fase_actual }}</span>
                                            </td>
                                            <td>
                                                <span :class="'badge ' + getEstadoClass(operacion.estado)">
                                                    {{ operacion.estado }}
                                                </span>
                                            </td>
                                            <td v-if="isAdmin">
                                                <div class="btn-group btn-group-sm">
                                                    <button @click="editOperacion(operacion)"
                                                        class="btn btn-outline-primary" title="Ver detalles">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    <button @click="editOperacion(operacion)"
                                                        class="btn btn-outline-warning" title="Editar">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button @click="deleteOperacion(operacion.id)"
                                                        class="btn btn-outline-danger" title="Eliminar">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-if="!loading && paginatedOperaciones.length === 0">
                                            <td :colspan="isAdmin ? 10 : 9" class="text-center py-4 text-muted">
                                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                                No se encontraron operaciones
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Footer con navegación -->
                            <div
                                class="border-top px-3 py-2 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div class="text-muted small">
                                    Mostrando {{ paginationInfo.from }} - {{ paginationInfo.to }} de {{ totalItems }}
                                    operaciones
                                </div>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-sm btn-outline-secondary" :disabled="currentPage === 1"
                                        @click="goToPage(currentPage - 1)">
                                        <i class="bi bi-chevron-left"></i> Anterior
                                    </button>
                                    <button v-for="page in visiblePages" :key="page"
                                        :class="['btn btn-sm', page === currentPage ? 'btn-primary' : 'btn-outline-secondary']"
                                        @click="goToPage(page)" :disabled="page === '...'">
                                        {{ page }}
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary"
                                        :disabled="currentPage === totalPages" @click="goToPage(currentPage + 1)">
                                        Siguiente <i class="bi bi-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer del contenido (navegación adicional) -->
                    <div class="mt-4 pt-3 border-top">
                        <div class="row">
                            <div class="col-md-3">
                                <h6 class="text-muted mb-3">Navegación</h6>
                                <ul class="list-unstyled small">
                                    <li><a href="#" class="text-decoration-none text-muted">Dashboard</a></li>
                                    <li class="mt-2"><a href="#" class="text-decoration-none text-muted">Gestión de
                                            Usuarios</a></li>
                                    <li class="mt-2"><a href="#"
                                            class="text-decoration-none text-muted">Configuración</a></li>
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <h6 class="text-muted mb-3">Administración</h6>
                                <ul class="list-unstyled small">
                                    <li><a href="#" class="text-decoration-none text-muted">Servicios</a></li>
                                    <li class="mt-2"><a href="#"
                                            class="text-decoration-none text-muted">Contratación</a></li>
                                    <li class="mt-2"><a href="#" class="text-decoration-none text-muted">Recursos
                                            Humanos</a></li>
                                    <li class="mt-2"><a href="#" class="text-decoration-none text-muted">Finanzas</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <h6 class="text-muted mb-3">Operaciones</h6>
                                <ul class="list-unstyled small">
                                    <li><a href="#" class="text-decoration-none text-muted">Marketing</a></li>
                                    <li class="mt-2"><a href="#" class="text-decoration-none text-muted">Tecnología</a>
                                    </li>
                                    <li class="mt-2"><a href="#" class="text-decoration-none text-muted">Gestión de
                                            Proyectos</a></li>
                                    <li class="mt-2"><a href="#" class="text-decoration-none text-muted">Informática</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <h6 class="text-muted mb-3">Sistema</h6>
                                <ul class="list-unstyled small">
                                    <li><a href="#" class="text-decoration-none text-muted">Comunicaciones</a></li>
                                    <li class="mt-2"><a href="#" class="text-decoration-none text-muted">Seguridad y
                                            Salud</a></li>
                                    <li class="mt-2"><a href="#" class="text-decoration-none text-muted">Medio
                                            Ambiente</a></li>
                                    <li class="mt-2"><a href="#" class="text-decoration-none text-muted">Desarrollo
                                            Sostenible</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'

const logoPrimeLogistics = '/images/logo-empresa.png'

// Estado de navegación
const activeSection = ref('operaciones')

// Estado
const currentPage = ref(1)
const itemsPerPage = ref(10)
const totalItems = ref(0)
const loading = ref(false)
const error = ref(null)
const isAdmin = ref(true)

// Filtros
const filters = ref({
    search: '',
    modo: '',
    ruta: ''
})

// Datos KPI
const kpiData = ref({
    totalOfertas: 4200,
    totalOfertasTrend: 12
})

// Datos desde backend
const distribucionModos = ref([])
const totalEnviosActivos = ref(0)
const operaciones = ref([])
const rutasDisponibles = ref([])

// URL base de la API
const API_BASE_URL = import.meta.env.VITE_API_URL || '/api'

// Computed adicionales
const operacionesCompletadas = computed(() => {
    return operaciones.value.filter(op => op.estado === 'COMPLETADO').length
})

const operacionesPendientes = computed(() => {
    return operaciones.value.filter(op => op.estado === 'EN TRANSITO').length
})

// Computed: nombre del usuario
const nombreUsuario = computed(() => {
    try {
        const usuario = JSON.parse(localStorage.getItem('user') || localStorage.getItem('usuario') || '{}')
        return usuario.nom || usuario.name || 'Administrador'
    } catch (e) {
        return 'Administrador'
    }
})

// Computed: iniciales del usuario
const inicialesUsuario = computed(() => {
    const partes = nombreUsuario.value.split(' ')
    if (partes.length >= 2) return (partes[0][0] + partes[1][0]).toUpperCase()
    return nombreUsuario.value.substring(0, 2).toUpperCase()
})

// Funciones API
const fetchDistribucionModos = async () => {
    try {
        const response = await fetch(`${API_BASE_URL}/operaciones/distribucion-modos`)
        if (!response.ok) throw new Error('Error al cargar distribución de modos')
        const data = await response.json()
        // Asignar clases de color
        distribucionModos.value = data.map((modo, index) => ({
            ...modo,
            colorClass: ['bg-primary', 'bg-success', 'bg-info', 'bg-warning'][index % 4]
        }))
    } catch (err) {
        console.error('Error fetching distribucion modos:', err)
        // Datos de ejemplo
        distribucionModos.value = [
            { nombre: 'Marítimo', cantidad: 45, porcentaje: 45, colorClass: 'bg-primary' },
            { nombre: 'Aéreo', cantidad: 30, porcentaje: 30, colorClass: 'bg-success' },
            { nombre: 'Terrestre', cantidad: 25, porcentaje: 25, colorClass: 'bg-info' }
        ]
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
        totalEnviosActivos.value = 124
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
        // Datos de ejemplo
        operaciones.value = [
            { id: 1, codigo: 'OP-001', cliente: 'Textil SA', empresa: 'Logistics Corp', modo: 'Marítimo', ruta: 'Valencia-Shanghai', etd: '2026-05-01', eta: '2026-05-15', fase_actual: 'En tránsito', estado: 'EN TRANSITO' },
            { id: 2, codigo: 'OP-002', cliente: 'Moda Express', empresa: 'Fast Ship', modo: 'Aéreo', ruta: 'Madrid-París', etd: '2026-05-02', eta: '2026-05-03', fase_actual: 'Preparación', estado: 'EN TRANSITO' },
            { id: 3, codigo: 'OP-003', cliente: 'ElectroTech', empresa: 'Cargo Plus', modo: 'Terrestre', ruta: 'Barcelona-Lisboa', etd: '2026-05-04', eta: '2026-05-07', fase_actual: 'En ruta', estado: 'EN TRANSITO' }
        ]
        totalItems.value = operaciones.value.length
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
        rutasDisponibles.value = ['Valencia-Shanghai', 'Madrid-París', 'Barcelona-Lisboa']
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

const clearFilters = () => {
    filters.value = {
        search: '',
        modo: '',
        ruta: ''
    }
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
    alert(`Ver detalles de operación: ${operacion.codigo}`)
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
const formatNumber = (num) => {
    return num?.toLocaleString('es-ES') ?? '0'
}

const formatDate = (date) => {
    if (!date) return '-'
    const d = new Date(date)
    return d.toLocaleDateString('es-ES', { day: '2-digit', month: 'short' })
}

const getEstadoClass = (estado) => {
    const classes = {
        'EN TRANSITO': 'bg-warning text-dark',
        'COMPLETADO': 'bg-success',
        'CANCELADO': 'bg-danger'
    }
    return classes[estado] || 'bg-secondary'
}

const getModoClass = (modo) => {
    const classes = {
        'Marítimo': 'bg-primary',
        'Aéreo': 'bg-info text-dark',
        'Terrestre': 'bg-success',
        'Multimodal': 'bg-warning text-dark'
    }
    return classes[modo] || 'bg-secondary'
}

const goToPage = (page) => {
    if (page === '...' || page < 1 || page > totalPages.value) return
    currentPage.value = page
    fetchOperaciones()
}

// Notificaciones y perfil
const clickNotification = () => {
    alert('Notificaciones')
}

const clickProfile = () => {
    alert('Perfil de usuario')
}

// Logout
const logout = async () => {
    try {
        const token = localStorage.getItem('token')
        await fetch('/api/logout', {
            method: 'POST',
            headers: { 'Authorization': `Bearer ${token}` }
        })
    } catch (e) {
        // Si falla
    } finally {
        localStorage.removeItem('token')
        localStorage.removeItem('user')
        localStorage.removeItem('usuario')
        window.location.href = '/'
    }
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
.hover-bg-secondary:hover {
    background-color: rgba(108, 117, 125, 0.2);
}

.cursor-pointer {
    cursor: pointer;
}

.nav-link.active {
    background-color: #0d6efd !important;
}

/* Animaciones */
.btn-group .btn {
    transition: all 0.2s;
}

.btn-group .btn:hover {
    transform: scale(1.05);
}

.table tbody tr {
    transition: background-color 0.2s;
}

/* Progress bar personalizada */
.progress {
    background-color: #e9ecef;
    border-radius: 4px;
    overflow: hidden;
}

.progress-bar {
    transition: width 0.3s ease;
}
</style>
