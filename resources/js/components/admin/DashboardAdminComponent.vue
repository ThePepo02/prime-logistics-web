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
                            <li><a href="#" class="nav-link" :class="{ active: activeSection === 'dashboard' }"
                                    @click.prevent="activeSection = 'dashboard'">Dashboard</a></li>
                            <li><a href="#" class="nav-link" :class="{ active: activeSection === 'usuarios' }"
                                    @click.prevent="activeSection = 'usuarios'">Gestión de Usuarios</a></li>
                        </ul>
                    </div>
                    <div class="nav-section-operaciones">
                        <h2 class="nav-section-title">Operaciones</h2>
                        <ul class="nav-list">
                            <li><a href="#" class="nav-link" :class="{ active: activeSection === 'ofertas' }"
                                    @click.prevent="activeSection = 'ofertas'">Todas las Ofertas</a></li>
                            <li><a href="#" class="nav-link" :class="{ active: activeSection === 'activas' }"
                                    @click.prevent="activeSection = 'activas'">Operaciones Activas</a></li>
                        </ul>
                    </div>
                    <div class="nav-section-sistema">
                        <h2 class="nav-section-title">Sistema</h2>
                        <ul class="nav-list">
                            <li><a href="#" class="nav-link" :class="{ active: activeSection === 'datos-maestros' }"
                                    @click.prevent="activeSection = 'datos-maestros'">Datos Maestros</a></li>
                            <li><a href="#" class="nav-link" :class="{ active: activeSection === 'configuracion' }"
                                    @click.prevent>Configuración</a></li>
                        </ul>
                    </div>
                </nav>
            </aside>
        </div>
        <!-- Contenido principal -->
        <main class="main-content">
            <!-- Header -->
            <header class="top-header">
                <button class="btn-mobile-menu" @click="toggleSidebar">
                    ☰
                </button>
                <img :src="logoPrimeLogistics" alt="Logo" class="logo" />
                <div class="header-actions">
                    <button class="btn-notificaciones" @click="clickNotification">
                        <img :src="imgBtnNotificaciones" alt="Notificaciones" />
                    </button>
                    <button class="btn-perfil" @click="clickProfile">
                        <img :src="imgPerfilUsuarioAdmin" alt="Perfil" />
                    </button>
                </div>
            </header>

            <!-- Vista Dashboard -->
            <div v-if="activeSection === 'dashboard'" class="dashboard-content">
                <!-- KPIs -->
                <div class="kpi-grid">
                    <div class="kpi-card">
                        <h3>Total Ofertas</h3>
                        <p class="kpi-value">{{ formatNumber(kpiData.totalOfertas) }}</p>
                        <span :class="['trend', kpiData.tasaOfertasTrend >= 0 ? 'positive' : 'negative']">
                            {{ kpiData.tasaOfertasTrend }}%
                        </span>
                    </div>
                    <div class="kpi-card">
                        <h3>Aceptadas</h3>
                        <p class="kpi-value">{{ formatNumber(kpiData.aceptadas) }}</p>
                        <span :class="['trend', kpiData.aceptadasTrend >= 0 ? 'positive' : 'negative']">
                            {{ kpiData.aceptadasTrend }}%
                        </span>
                    </div>
                    <div class="kpi-card">
                        <h3>En Favor</h3>
                        <p class="kpi-value">{{ formatNumber(kpiData.enFavor) }}</p>
                        <span :class="['trend', kpiData.enFavorTrend >= 0 ? 'positive' : 'negative']">
                            {{ kpiData.enFavorTrend }}%
                        </span>
                    </div>
                    <div class="kpi-card">
                        <h3>Incidencias</h3>
                        <p class="kpi-value">{{ formatNumber(kpiData.incidencias) }}</p>
                        <span :class="['trend', kpiData.incidenciasTrend >= 0 ? 'positive' : 'negative']">
                            {{ kpiData.incidenciasTrend }}%
                        </span>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="filters-bar">
                    <input v-model="searchTerm" type="text" placeholder="Buscar cliente o empresa..."
                        class="search-input" />
                    <select v-model="statusFilter" class="status-filter">
                        <option value="">Todos los estados</option>
                        <option value="PENDIENTE">Pendiente</option>
                        <option value="ACEPTADA">Aceptada</option>
                        <option value="EN TRÁNSITO">En Tránsito</option>
                        <option value="COMPLETADA">Completada</option>
                        <option value="RECHAZADA">Rechazada</option>
                    </select>
                    <button @click="exportData" class="btn-exportar">
                        <img :src="imgBtnExportarDatos" alt="Exportar" />
                        Exportar
                    </button>
                </div>

                    <div class="offers_table">
                        <table class="offers">
                            <thead>
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
                                    <td>{{ offer.id }}</td>
                                    <td>{{ offer.cliente }}</td>
                                    <td>{{ offer.empresa }}</td>
                                    <td>{{ offer.modo }}</td>
                                    <td>{{ offer.ruta }}</td>
                                    <td>{{ offer.distancia }}</td>
                                    <td><span :class="['status-badge', getStatusClass(offer.estado)]">{{ offer.estado
                                            }}</span></td>
                                    <td>
                                        <button class="action-btn" @click="viewOffer(offer.id)">
                                            <i class="bi bi-eye"></i> Ver
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="filteredOffers.length === 0">
                                    <td colspan="8" class="text-center">No se encontraron ofertas</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-footer">
                        <p>Total: {{ totalOffers }} ofertas</p>
                    </div>
                </div>
            </div>

            <!-- Vista Gestión de Usuarios -->
            <div v-else-if="activeSection === 'usuarios'" class="section-content">
                <h2>Gestión de Usuarios</h2>
                <p>Contenido de gestión de usuarios aquí...</p>
            </div>

            <!-- Vista Todas las Ofertas -->
            <div v-else-if="activeSection === 'ofertas'" class="section-content">
                <h2>Todas las Ofertas</h2>
                <p>Contenido de todas las ofertas aquí...</p>
            </div>

            <!-- Vista Operaciones Activas -->
            <div v-else-if="activeSection === 'activas'" class="section-content">
                <h2>Operaciones Activas</h2>
                <p>Contenido de operaciones activas aquí...</p>
            </div>

            <!-- Vista Datos Maestros -->
            <div v-else-if="activeSection === 'datos-maestros'" class="section-content">
                <h2>Datos Maestros</h2>
                <p>Contenido de datos maestros aquí...</p>
            </div>

            <!-- Vista Configuración -->
            <div v-else-if="activeSection === 'configuracion'" class="section-content">
                <h2>Configuración</h2>
                <p>Contenido de configuración aquí...</p>
            </div>
        </main>
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
const sidebarOpen = ref(false)
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

// Sidebar responsive en móvil
const toggleSidebar = () => {
    sidebarOpen.value = !sidebarOpen.value
}

// Formatear número
const formatNumber = (num) => {
    return num?.toLocaleString('es-ES') ?? '0'
}

// Clase del badge de estado
const getStatusClass = (estado) => {
    const map = {
        'EN TRÁNSITO': 'status-transito',
        'ACEPTADA': 'status-aceptado',
        'COMPLETADA': 'status-completada',
        'RECHAZADA': 'status-rechazada',
    }
    return map[estado] ?? 'status-default'
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

// Exportar datos
const exportData = async () => {
    try {
        const token = localStorage.getItem('token')
        await fetch('/api/dashboard/export', {
            headers: { 'Authorization': `Bearer ${token}` }
        })
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

// Watch filtros
watch([searchTerm, statusFilter], () => {
    loadData()
})

onMounted(() => {
    loadData()
})
</script>

<style lang="scss" scoped>
/* Estilos del layout */
.dashboard-wrapper {
    display: flex;
    min-height: 100vh;
}

/* Sidebar */
.sidebar {
    width: 250px;
    background-color: #1e293b;
    color: white;
    transition: transform 0.3s ease;
    position: fixed;
    left: 0;
    top: 0;
    bottom: 0;
    z-index: 100;
    overflow-y: auto;
}

.sidebar-mobile-open {
    transform: translateX(0);
}

@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
    }

    .sidebar-mobile-open {
        transform: translateX(0);
    }
}

.nav-section-title {
    padding: 1rem;
    font-size: 0.875rem;
    text-transform: uppercase;
    color: #94a3b8;
}

.nav-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.nav-link {
    display: block;
    padding: 0.75rem 1rem;
    color: #cbd5e1;
    text-decoration: none;
    transition: all 0.2s;
}

.nav-link:hover {
    background-color: #334155;
    color: white;
}

.nav-link.active {
    background-color: #3b82f6;
    color: white;
}

/* Contenido principal */
.main-content {
    flex: 1;
    margin-left: 250px;
    background-color: #f1f5f9;
    min-height: 100vh;
}

@media (max-width: 768px) {
    .main-content {
        margin-left: 0;
    }
}

/* Header */
.top-header {
    background-color: white;
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.btn-mobile-menu {
    display: none;
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
}

@media (max-width: 768px) {
    .btn-mobile-menu {
        display: block;
    }
}

.logo {
    height: 40px;
}

.header-actions {
    margin-left: auto;
    display: flex;
    gap: 1rem;
}

/* Dashboard content */
.dashboard-content {
    padding: 2rem;
}

.kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.kpi-card {
    background-color: white;
    padding: 1.5rem;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.kpi-value {
    font-size: 2rem;
    font-weight: bold;
    margin: 0.5rem 0;
}

.trend {
    font-size: 0.875rem;
}

.trend.positive {
    color: #10b981;
}

.trend.negative {
    color: #ef4444;
}

/* Filtros */
.filters-bar {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
}

.search-input {
    flex: 1;
    padding: 0.5rem;
    border: 1px solid #cbd5e1;
    border-radius: 0.25rem;
}

.status-filter {
    padding: 0.5rem;
    border: 1px solid #cbd5e1;
    border-radius: 0.25rem;
}

.btn-exportar {
    padding: 0.5rem 1rem;
    background-color: #3b82f6;
    color: white;
    border: none;
    border-radius: 0.25rem;
    cursor: pointer;
}

/* Tabla */
.offers-table-container {
    background-color: white;
    border-radius: 0.5rem;
    overflow-x: auto;
}

.offers-table {
    width: 100%;
    border-collapse: collapse;
}

.offers-table th {
    text-align: left;
    padding: 1rem;
    background-color: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}

.offers-table td {
    padding: 1rem;
    border-bottom: 1px solid #e2e8f0;
}

.status-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 9999px;
    font-size: 0.875rem;
}

.status-transito {
    background-color: #fef3c7;
    color: #92400e;
}

.status-aceptado {
    background-color: #d1fae5;
    color: #065f46;
}

.status-completada {
    background-color: #dbeafe;
    color: #1e40af;
}

.status-rechazada {
    background-color: #fee2e2;
    color: #991b1b;
}

.btn-view {
    padding: 0.25rem 0.75rem;
    background-color: #3b82f6;
    color: white;
    border: none;
    border-radius: 0.25rem;
    cursor: pointer;
}

.loading-spinner {
    text-align: center;
    padding: 2rem;
}

.table-footer {
    padding: 1rem;
    text-align: right;
    border-top: 1px solid #e2e8f0;
}

.section-content {
    padding: 2rem;
}
</style>
