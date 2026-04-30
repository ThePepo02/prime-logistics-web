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

                        <h6 class="text-secondary text-uppercase small mt-4 mb-3 px-2">Operaciones</h6>

                        <a href="#" @click.prevent="activeSection = 'ofertas'" class="nav-link text-white mb-1 rounded"
                            :class="activeSection === 'ofertas' ? 'bg-primary active' : 'hover-bg-secondary'">
                            <i class="bi bi-file-text-fill me-2"></i>
                            Todas las Ofertas
                        </a>

                        <a href="#" @click.prevent="activeSection = 'activas'" class="nav-link text-white mb-1 rounded"
                            :class="activeSection === 'activas' ? 'bg-primary active' : 'hover-bg-secondary'">
                            <i class="bi bi-truck me-2"></i>
                            Operaciones Activas
                        </a>

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
                            <h4 class="mb-0 text-dark">{{ tituloActual }}</h4>
                            <small class="text-muted">{{ subtituloActual }}</small>
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

                    <!-- Vista Dashboard -->
                    <div v-if="activeSection === 'dashboard'">
                        <!-- KPIs -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-3">
                                <div class="card border-0 shadow-sm h-100 border-top border-primary">
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
                                            <i
                                                :class="kpiData.tasaOfertasTrend >= 0 ? 'bi-arrow-up text-success' : 'bi-arrow-down text-danger'"></i>
                                            {{ Math.abs(kpiData.tasaOfertasTrend) }}% vs mes anterior
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card border-0 shadow-sm h-100 border-top border-success">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="text-muted mb-2">Aceptadas</h6>
                                                <h2 class="mb-0">{{ formatNumber(kpiData.aceptadas) }}</h2>
                                            </div>
                                            <div class="bg-success bg-opacity-10 rounded p-2">
                                                <i class="bi bi-check-circle text-success fs-4"></i>
                                            </div>
                                        </div>
                                        <small class="text-muted mt-2 d-block">
                                            <i
                                                :class="kpiData.aceptadasTrend >= 0 ? 'bi-arrow-up text-success' : 'bi-arrow-down text-danger'"></i>
                                            {{ Math.abs(kpiData.aceptadasTrend) }}% vs mes anterior
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card border-0 shadow-sm h-100 border-top border-info">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="text-muted mb-2">En Favor</h6>
                                                <h2 class="mb-0">{{ formatNumber(kpiData.enFavor) }}</h2>
                                            </div>
                                            <div class="bg-info bg-opacity-10 rounded p-2">
                                                <i class="bi bi-truck text-info fs-4"></i>
                                            </div>
                                        </div>
                                        <small class="text-muted mt-2 d-block">
                                            <i
                                                :class="kpiData.enFavorTrend >= 0 ? 'bi-arrow-up text-success' : 'bi-arrow-down text-danger'"></i>
                                            {{ Math.abs(kpiData.enFavorTrend) }}% vs mes anterior
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card border-0 shadow-sm h-100 border-top border-danger">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="text-muted mb-2">Incidencias</h6>
                                                <h2 class="mb-0">{{ formatNumber(kpiData.incidencias) }}</h2>
                                            </div>
                                            <div class="bg-danger bg-opacity-10 rounded p-2">
                                                <i class="bi bi-exclamation-triangle text-danger fs-4"></i>
                                            </div>
                                        </div>
                                        <small class="text-muted mt-2 d-block">
                                            <i
                                                :class="kpiData.incidenciasTrend >= 0 ? 'bi-arrow-up text-success' : 'bi-arrow-down text-danger'"></i>
                                            {{ Math.abs(kpiData.incidenciasTrend) }}% vs mes anterior
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filtros -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label small text-muted">Buscar</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0">
                                                <i class="bi bi-search"></i>
                                            </span>
                                            <input v-model="searchTerm" type="text"
                                                class="form-control border-start-0 ps-0"
                                                placeholder="Cliente o empresa...">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small text-muted">Estado</label>
                                        <select v-model="statusFilter" class="form-select">
                                            <option value="">Todos los estados</option>
                                            <option value="PENDIENTE">Pendiente</option>
                                            <option value="ACEPTADA">Aceptada</option>
                                            <option value="EN TRÁNSITO">En Tránsito</option>
                                            <option value="COMPLETADA">Completada</option>
                                            <option value="RECHAZADA">Rechazada</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button @click="exportData" class="btn btn-primary w-100">
                                            <i class="bi bi-download me-2"></i>
                                            Exportar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Loading -->
                        <div v-if="loading" class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p class="text-muted mt-2">Cargando datos...</p>
                        </div>

                        <!-- Tabla de ofertas -->
                        <div v-else class="card border-0 shadow-sm">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>ID de la oferta</th>
                                                <th>Cliente</th>
                                                <th>Empresa</th>
                                                <th>Modo</th>
                                                <th>Ruta</th>
                                                <th>Distancia</th>
                                                <th>Estatus</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="offer in filteredOffers" :key="offer.id">
                                                <td class="fw-medium">{{ offer.id }}</td>
                                                <td>{{ offer.cliente }}</td>
                                                <td class="text-muted">{{ offer.empresa }}</td>
                                                <td>{{ offer.modo }}</td>
                                                <td>{{ offer.ruta }}</td>
                                                <td>{{ offer.distancia }}</td>
                                                <td>
                                                    <span :class="'badge ' + getStatusClass(offer.estado)">
                                                        {{ offer.estado }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary"
                                                        @click="viewOffer(offer.id)" title="Ver">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr v-if="filteredOffers.length === 0">
                                                <td colspan="8" class="text-center py-4 text-muted">
                                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                                    No se encontraron ofertas
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="border-top px-3 py-2 text-end text-muted small">
                                    Total: {{ totalOffers }} ofertas
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Vistas de otras secciones -->
                    <div v-else-if="['usuarios', 'ofertas', 'activas', 'datos-maestros', 'configuracion'].includes(activeSection)"
                        class="text-center py-5">
                        <i class="bi bi-tools fs-1 text-muted"></i>
                        <h3 class="text-muted mt-3">{{ tituloActual }}</h3>
                        <p class="text-muted">Página en construcción</p>
                    </div>

                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, watch } from 'vue'

// Imágenes
const logoPrimeLogistics = '/images/logo-empresa.png'
const imgBtnExportarDatos = '/images/iconoExportarDatos.png'
const imgPerfilUsuarioAdmin = '/images/perfilUsuarioAdmin.png'
const imgBtnNotificaciones = '/images/notificaciones-logo.png'
const imgGraficos = '/images/graficos.png'

// Estado
const activeSection = ref('dashboard')
const searchTerm = ref('')
const statusFilter = ref('')
const loading = ref(false)
const offers = ref([])
const totalOffers = ref(0)

// Datos KPI
const kpiData = reactive({
    totalOfertas: 0,
    tasaOfertasTrend: 0,
    aceptadas: 0,
    porcentajeAceptacion: 0,
    aceptadasTrend: 0,
    enFavor: 0,
    enFavorTrend: 0,
    incidencias: 0,
    incidenciasTrend: 0,
    activas: 0
})

// Datos de actividad semanal
const weeklyData = ref([])

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

// Computed: título actual
const tituloActual = computed(() => {
    const titulos = {
        dashboard: 'Dashboard',
        usuarios: 'Gestión de Usuarios',
        ofertas: 'Todas las Ofertas',
        activas: 'Operaciones Activas',
        'datos-maestros': 'Datos Maestros',
        configuracion: 'Configuración',
    }
    return titulos[activeSection.value] || 'Dashboard'
})

// Computed: subtítulo actual
const subtituloActual = computed(() => {
    const subs = {
        dashboard: `Buenos días, ${nombreUsuario.value} — Prime Logistics`,
        usuarios: 'Gestión de usuarios del sistema',
        ofertas: 'Gestión de ofertas comerciales',
        activas: 'Estado de operaciones activas',
        'datos-maestros': 'Mantenimiento de datos maestros',
        configuracion: 'Configuración del sistema',
    }
    return subs[activeSection.value] || ''
})

// Ofertas filtradas
const filteredOffers = computed(() => {
    return offers.value.filter(offer => {
        const coincideTexto = !searchTerm.value ||
            offer.cliente?.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
            offer.empresa?.toLowerCase().includes(searchTerm.value.toLowerCase())
        const coincideEstado = !statusFilter.value || offer.estado === statusFilter.value
        return coincideTexto && coincideEstado
    })
})

// Formatear número
const formatNumber = (num) => {
    return num?.toLocaleString('es-ES') ?? '0'
}

// Clase del badge de estado
const getStatusClass = (estado) => {
    const map = {
        'EN TRÁNSITO': 'bg-warning text-dark',
        'ACEPTADA': 'bg-success',
        'COMPLETADA': 'bg-info text-dark',
        'RECHAZADA': 'bg-danger',
        'PENDIENTE': 'bg-secondary'
    }
    return map[estado] ?? 'bg-secondary'
}

// Convertir estado BD → frontend
const getStatusForFrontend = (status) => {
    const map = {
        'PENDIENTE': 'PENDIENTE',
        'ACEPTADA': 'ACEPTADA',
        'EN_TRANSIT': 'EN TRÁNSITO',
        'COMPLETADA': 'COMPLETADA',
        'RECHAZADA': 'RECHAZADA',
    }
    return map[status] ?? status
}

// Cargar datos desde API
const loadData = async () => {
    loading.value = true
    try {
        const token = localStorage.getItem('token')
        const params = new URLSearchParams({
            search: searchTerm.value,
            status: statusFilter.value,
            per_page: 10
        })

        const response = await fetch(`/api/dashboard/data?${params}`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
            }
        })

        const data = await response.json()

        if (data.success) {
            Object.assign(kpiData, data.data.kpi)
            weeklyData.value = data.data.weekly_activity

            offers.value = data.data.offers.data.map(offer => ({
                id: offer.id,
                cliente: offer.client ? `${offer.client.nom} ${offer.client.cognoms}` : 'N/A',
                empresa: offer.empresa || 'N/A',
                modo: offer.tipus_transport?.nom || 'N/A',
                ruta: `${offer.origen || 'N/A'}-${offer.desti || 'N/A'}`,
                distancia: offer.distancia ? `${offer.distancia} Km` : 'N/A',
                estado: getStatusForFrontend(offer.estat)
            }))

            totalOffers.value = data.data.offers.total
        }
    } catch (error) {
        console.error('Error loading dashboard data:', error)
    } finally {
        loading.value = false
    }
}

// Exportar datos
const exportData = async () => {
    try {
        const token = localStorage.getItem('token')
        await fetch('/api/dashboard/export', {
            headers: { 'Authorization': `Bearer ${token}` }
        })
        alert('Datos exportados correctamente')
    } catch (error) {
        console.error('Error exporting data:', error)
        alert('Error al exportar los datos')
    }
}

// Ver oferta
const viewOffer = (offerId) => {
    alert(`Ver oferta: ${offerId}`)
}

// Click notificaciones
const clickNotification = () => {
    alert('Notificaciones - Conectando con API')
}

// Click perfil
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
        // Si falla la llamada al servidor, borramos igualmente
    } finally {
        localStorage.removeItem('token')
        localStorage.removeItem('user')
        localStorage.removeItem('usuario')
        window.location.href = '/'
    }
}

// Watch filtros
watch([searchTerm, statusFilter], () => {
    loadData()
})

onMounted(() => {
    loadData()
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
</style>