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
                <!-- Secciones KPI -->
                <div class="kpi-grid">
                    <div class="kpi-card">
                        <div class="kpi-header">
                            <span class="kpi-label">Todas las Ofertas</span>
                            <i class="bi bi-file-spreadsheet kpi-icon"></i>
                        </div>
                        <div class="kpi-value">{{ formatNumber(kpiData.totalOfertas) }}</div>
                        <div class="kpi-trend-info" :class="kpiData.tasaOfertasTrend >= 0 ? 'trend-up' : 'trend-down'">
                            <i :class="kpiData.tasaOfertasTrend >= 0 ? 'bi bi-arrow-up' : 'bi bi-arrow-down'"></i>
                            {{ Math.abs(kpiData.tasaOfertasTrend) }}% vs mes anterior
                        </div>
                    </div>

                    <div class="kpi-card">
                        <div class="kpi-header">
                            <span class="kpi-label">Aceptadas</span>
                            <i class="bi bi-check2-circle kpi-icon"></i>
                        </div>
                        <div class="kpi-value">{{ formatNumber(kpiData.aceptadas) }}</div>
                        <div class="kpi-trend-info">
                            <span :class="kpiData.aceptadasTrend >= 0 ? 'trend-up' : 'trend-down'">
                                {{ Math.abs(kpiData.aceptadasTrend) }}% tasa global
                            </span>
                        </div>
                    </div>

                    <div class="kpi-card">
                        <div class="kpi-header">
                            <span class="kpi-label">En Tránsito</span>
                            <i class="bi bi-hand-thumbs-up kpi-icon"></i>
                        </div>
                        <div class="kpi-value">{{ formatNumber(kpiData.enFavor) }}</div>
                        <div class="kpi-trend-info">
                            <span :class="kpiData.enFavorTrend >= 0 ? 'trend-up' : 'trend-down'">
                                {{ Math.abs(kpiData.enFavorTrend) }} esta semana
                            </span>
                        </div>
                    </div>

                    <div class="kpi-card">
                        <div class="kpi-header">
                            <span class="kpi-label">Incidentes</span>
                            <i class="bi bi-exclamation-triangle kpi-icon"></i>
                        </div>
                        <div class="kpi-value">{{ formatNumber(kpiData.incidencias) }}</div>
                        <div class="kpi-trend-info" :class="kpiData.incidenciasTrend <= 0 ? 'trend-down' : 'trend-up'">
                            <i :class="kpiData.incidenciasTrend <= 0 ? 'bi bi-arrow-down' : 'bi bi-arrow-up'"></i>
                            {{ Math.abs(kpiData.incidenciasTrend) }} vs semana pasada
                        </div>
                    </div>
                </div>

                <!-- Actividad semanal global -->
                <div class="contenedor_act_semanal">
                    <div class="act_semanal">
                        <h3 class="title-section">Actividad semanal global</h3>
                        <div class="info_act_semanal">
                            <!-- Gráfico de columnas agrupadas (añadir datos de la BD) -->
                            <div class="grafico-columnas">
                                <div>
                                    <img :src="imgGraficos" alt="gráficos" class="imgGraficos">
                                </div>
                                <div class="leyenda-grafico">
                                    <span class="leyenda-item enviadas-leyenda">Enviadas</span>
                                    <span class="leyenda-item aceptadas-leyenda">Aceptadas</span>
                                    <span class="leyenda-item incidentes-leyenda">Incidentes</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de ofertas -->
                <div class="offers_table_container">
                    <div class="table-header">
                        <h3 class="section-title">Todas las ofertas</h3>
                        <div class="table-filters">
                            <input type="text" v-model="searchTerm" placeholder="Buscar oferta..." class="search-input">
                            <select v-model="statusFilter" class="status-filter">
                                <option value="">Todos los estados</option>
                                <option value="EN TRÁNSITO">En tránsito</option>
                                <option value="ACEPTADA">Aceptada</option>
                                <option value="COMPLETADA">Completada</option>
                                <option value="RECHAZADA">Rechazada</option>
                            </select>
                        </div>
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
                        <span>Mostrando {{ filteredOffers.length }} de {{ offers.length }} ofertas</span>
                        <span class="total-info">Cantidad de ofertas: {{ offers.length }} de {{
                            formatNumber(kpiData.totalOfertas) }} totales</span>
                    </div>
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
// import logotipo from '@/img/logo-empresa.png'
// import iconoExportarDatos from '@/img/iconoExportarDatos.png'
// import fotoPerfil from '@/img/perfilUsuarioAdmin.png'
// import iconoNotificaciones from '@/img/notificaciones-logo.png'
// import graficos from '@/img/graficos.png'

const logoPrimeLogistics = ''
const imgBtnExportarDatos = ''
const imgPerfilUsuarioAdmin = ''
const imgBtnNotificaciones = ''
const imgGraficos = ''

const logoPrimeLogistics = logotipo
const imgBtnExportarDatos = ''
const imgPerfilUsuarioAdmin = fotoPerfil
const imgBtnNotificaciones = iconoNotificaciones
const imgGraficos = graficos

// Datos reactivos
const sidebarOpen = ref(false)
const searchTerm = ref('')
const statusFilter = ref('')
const loading = ref(false)

// Datos KPI (vendrían desde API de SQL Server)
const kpiData = reactive({
    totalOfertas: 4200,
    tasaOfertasTrend: 12,
    aceptadas: 3108,
    porcentajeAceptacion: 38,
    aceptadasTrend: 7,
    enFavor: 38,
    enFavorTrend: 7,
    incidencias: 7,
    incidenciasTrend: -2,
    activas: 189
})

// Datos de actividad semanal
const weeklyData = ref([
    { name: 'Sevilla', value: 1280, percentage: 82 },
    { name: 'Santiago', value: 940, percentage: 60 },
    { name: 'Barcelona', value: 2150, percentage: 92 },
    { name: 'Madrid', value: 3420, percentage: 100 },
    { name: 'Granada', value: 560, percentage: 35 },
    { name: 'Valencia', value: 1970, percentage: 78 }
])

const safeMax = (arr, key) => {
    return Math.max(...arr.map(d => d[key]), 1)
}

// Datos de ofertas (vendrían desde API)
const offers = ref([
    { id: 'OC-2024-021', cliente: 'Marta García', empresa: 'Textil SA', modo: 'Marítimo', ruta: 'Valencia-Shanghai', distancia: '0.1 Km', estado: 'EN TRÁNSITO' },
    { id: 'OC-2024-020', cliente: 'Pedro López', empresa: 'Multiusos Españoles', modo: 'Aéreo', ruta: 'Barcelona-NY', distancia: '0.8 Km', estado: 'ACEPTADA' },
    { id: 'OC-2024-019', cliente: 'Marta García', empresa: 'Textil SA', modo: 'Aéreo', ruta: 'Madrid-Miami', distancia: '0.1 Km', estado: 'ACEPTADA' },
    { id: 'OC-2024-018', cliente: 'Sara Ruz', empresa: 'Import Grado', modo: 'Terrestre', ruta: 'Madrid-Lisboa', distancia: '20 Km', estado: 'COMPLETADA' },
    { id: 'OC-2024-017', cliente: 'Pedro López', empresa: 'Motivo Empresas', modo: 'Marítimo', ruta: 'Valencia-Montenegro', distancia: '22 Km', estado: 'COMPLETADA' },
    { id: 'OC-2024-016', cliente: 'Laura Gómez', empresa: 'Tech reports', modo: 'Aéreo', ruta: 'Bilbao-Chicago', distancia: '10 Km', estado: 'RECHAZADA' },
    { id: 'OC-2024-015', cliente: 'María García', empresa: 'Textil SA', modo: 'Marítimo', ruta: 'Bilbao-Reykjavik', distancia: '15 Km', estado: 'RECHAZADA' },
    { id: 'OC-2024-014', cliente: 'Sara Ruiz', empresa: 'Import Global', modo: 'Terrestre', ruta: 'Barcelona-Lyon', distancia: '10 Km', estado: 'COMPLETADA' },
    { id: 'OC-2024-013', cliente: 'Pedro López', empresa: 'Multiusos Españoles', modo: 'Marítimo', ruta: 'Valencia-Singapur', distancia: '25 Km', estado: 'COMPLETADA' },
    { id: 'OC-2024-012', cliente: 'Marta García', empresa: 'Textil SA', modo: 'Aéreo', ruta: 'Valencia-Almería', distancia: '17 Km', estado: 'COMPLETADA' }
])

// Datos de usuario
const userData = reactive({
    nombre: 'Cortes Martínez',
    rol: 'MANDATARIO'
})

// Computed: ofertas filtradas
const filteredOffers = computed(() => {
    let result = offers.value

    if (searchTerm.value) {
        const term = searchTerm.value.toLowerCase()
        result = result.filter(offer =>
            offer.id.toLowerCase().includes(term) ||
            offer.cliente.toLowerCase().includes(term) ||
            offer.empresa.toLowerCase().includes(term) ||
            offer.ruta.toLowerCase().includes(term)
        )
    }

    if (statusFilter.value) {
        result = result.filter(offer => offer.estado === statusFilter.value)
    }

    return result
})

// Métodos
const formatNumber = (num) => {
    return new Intl.NumberFormat('es-ES').format(num)
}

const getStatusClass = (status) => {
    const statusMap = {
        'EN TRÁNSITO': 'status-transito',
        'ACEPTADA': 'status-aceptado',
        'COMPLETADA': 'status-completada',
        'RECHAZADA': 'status-rechazada'
    }
    return statusMap[status] || 'status-default'
}

const toggleSidebar = () => {
    sidebarOpen.value = !sidebarOpen.value
}

const exportData = () => {
    console.log('Exportando datos...')
    // Aquí iría la lógica de exportación
    alert('Exportar datos - Conectando con API')
}

const clickNotification = () => {
    console.log('Notificaciones clickeado')
    alert('Notificaciones - Próximamente')
}

const clickProfile = () => {
    console.log('Perfil clickeado')
    alert('Perfil de administrador')
}

const viewOffer = (offerId) => {
    console.log('Ver oferta:', offerId)
    alert(`Ver detalles de la oferta ${offerId}`)
}

onMounted(() => {
    loadData()
})
</script>

<style lang="scss" scoped>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

$primary-color: #1a5d8c;
$primary-dark: #0e3d5c;
$success-color: #2a9d8f;
$danger-color: #e76f51;
$gray-light: #f8f9fa;
$gray-border: #e9ecef;
$text-dark: #2c3e50;
$text-muted: #6c757d;

.dashboard-container {
    font-family: 'Inter', sans-serif;
    background-color: #f4f7fc;
    min-height: 100vh;
}

.dashboard-header {
    background: white;
    padding: 1rem 2rem;
    border-bottom: 1px solid $gray-border;
    display: grid;
    justify-content: space-between;
    align-items: center;
}

.logo {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.logo-icon {
    width: 200px;
    height: 100px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

i {
    font-size: 1.3rem;
}

.logo-text {
    font-weight: 700;
    font-size: 1.25rem;
    color: $primary-dark;
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
    transition: transform 0.3s ease;

    @media (max-width: 992px) {
        position: fixed;
        top: 73px;
        left: 0;
        z-index: 1000;
        transform: translateX(-100%);

        &.sidebar-mobile-open {
            transform: translateX(0);
        }
    }

    .sidebar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        color: white;

        h3 {
            margin: 0;
            font-size: 1rem;
        }

        .btn-close-sidebar {
            background: none;
            border: none;
            color: white;
        }
    }
}

.side-nav {
    .nav-section-title {
        font-size: 0.7rem;
        text-transform: uppercase;
        color: #80a6c2;
        margin-bottom: 0.75rem;
    }

    .nav-list {
        list-style: none;
        padding: 0;
        margin-bottom: 1.5rem;

        .nav-link {
            display: block;
            padding: 0.5rem 0;
            color: #cfdfed;
            text-decoration: none;
            font-size: 0.9rem;

            &:hover,
            &.active {
                color: white;
            }
        }
    }
}

.dashboard-content {
    flex: 1;
    padding: 1.5rem 2rem;
    max-width: calc(100% - 260px);

    @media (max-width: 992px) {
        max-width: 100%;
    }
}

.page-title {
    margin-top: 34px;
    font-size: 1.75rem;
    font-weight: 600;
    color: $text-dark;

    .title-sub {
        display: flex;
        flex-direction: row;
        align-items: baseline;
        gap: 0.5rem;
        font-size: 0.85rem;
        font-weight: 500;
        color: $primary-color;
        letter-spacing: 0.5px;
    }
}

.actions-bar {
    display: flex;
    margin-left: 900px;
    gap: 1rem;

    .btn_exportarDatos {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1.25rem;
        background: white;
        border: 1px solid $gray-border;
        border-radius: 40px;
        height: 50px;
        width: 180px;

        &:hover {
            background: $gray-light;
            border-color: $primary-color;
        }
    }

    .btn_notificaciones {
        width: 60px;
        height: 50px;
        background: white;
        border-radius: 40px;
        display: flex;
        align-items: center;
        justify-content: center;

        &:hover {
            background: $gray-light;
        }
    }

    .perfil-icon {
        width: 60px;
        height: 50px;
        background: white;
        border-radius: 40px;
        display: flex;
        align-items: center;
        justify-content: center;

        &:hover {
            background: $gray-light;
        }
    }
}

.kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.kpi-card {
    background: white;
    border-radius: 1rem;
    padding: 1.25rem;
    border-left: 4px solid $primary-color;

    .kpi-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.75rem;

        .kpi-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: $text-muted;
        }

        .kpi-icon {
            font-size: 1.4rem;
            color: $primary-color;
        }
    }

    .kpi-value {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .kpi-trend-info {
        font-size: 0.75rem;

        .trend-up {
            color: $success-color;
        }

        .trend-down {
            color: $danger-color;
        }

        .percentage-badge {
            background: #e9ecef;
            padding: 0.2rem 0.5rem;
            border-radius: 20px;
            margin-right: 0.5rem;
        }
    }
}

.act_semanal {
    background: white;
    border-radius: 1rem;
    padding: 1.25rem;
    margin-bottom: 2rem;

    .city-item {
        margin-bottom: 1rem;

        .city-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.3rem;
        }

        .progress-bar-wrapper {
            background: #e9ecef;
            border-radius: 10px;
            height: 8px;

            .progress-fill {
                background: $primary-color;
                height: 100%;
                border-radius: 10px;
            }
        }
    }
}

.offers_table_container {
    background: white;
    border-radius: 1rem;
    padding: 1.25rem;
    overflow-x: auto;

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 1rem;

        .search-input,
        .status-filter {
            padding: 0.5rem;
            border: 1px solid $gray-border;
            border-radius: 8px;
        }
    }

    .offers {
        width: 100%;
        border-collapse: collapse;

        th,
        td {
            padding: 0.75rem 0.5rem;
            text-align: left;
            border-bottom: 1px solid $gray-border;
        }

        th {
            background: $gray-light;
            font-weight: 600;
        }

        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.6rem;
            border-radius: 30px;
            font-size: 0.7rem;

            &.status-transito {
                background: #fff3cd;
                color: #856404;
            }

            &.status-aceptado {
                background: #cfe2ff;
                color: #084298;
            }

            &.status-completada {
                background: #d1e7dd;
                color: #0f5132;
            }

            &.status-rechazada {
                background: #f8d7da;
                color: #842029;
            }
        }

        .action-btn {
            padding: 0.25rem 0.75rem;
            background: $primary-color;
            color: white;
            border: none;
            border-radius: 20px;
            cursor: pointer;

            &:hover {
                background: $primary-dark;
            }
        }
    }

    .table-footer {
        display: flex;
        justify-content: space-between;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid $gray-border;
        font-size: 0.8rem;
        color: $text-muted;
    }
}

.dashboard-footer {
    background: white;
    border-top: 1px solid $gray-border;
    padding: 1rem 2rem;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;

    .user-name {
        font-size: 0.85rem;
        font-weight: 500;
        color: $text-dark;

        @media (max-width: 768px) {
            font-size: 0.75rem;
        }
    }

    .rol_user {
        background: $primary-color;
        color: white;
        padding: 0.15rem 0.5rem;
        border-radius: 20px;
        font-size: 0.6rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;

        @media (max-width: 768px) {
            padding: 0.1rem 0.4rem;
            font-size: 0.55rem;
            border-radius: 16px;
        }
    }
}

@media (max-width: 768px) {
    .dashboard-content {
        padding: 1rem;
    }

    .kpi-grid {
        gap: 1rem;
    }

    .actions-bar {
        margin-top: 1rem;
    }
}

.info_act_semanal {
    .chart-columns-container {
        padding: 1rem 0;
    }

    .chart-bars {
        display: flex;
        justify-content: space-around;
        align-items: flex-end;
        gap: 1rem;
        min-height: 300px;
        padding: 1rem 0;
    }

    .chart-bar-item {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;

        .bar-column-wrapper {
            width: 100%;
            height: 200px;
            display: flex;
            align-items: flex-end;
            justify-content: center;
        }

        .bar-column {
            width: 60px;
            max-width: 80px;
            background: linear-gradient(180deg, $primary-color 0%, 100%);
            border-radius: 8px 8px 0 0;
            position: relative;
            transition: height 0.5s ease;
            cursor: pointer;
            min-height: 5px;

            &:hover {
                background: linear-gradient(180deg, 0%, $primary-color 100%);

                .bar-value {
                    opacity: 1;
                    transform: translateY(-5px);
                }
            }

            .bar-value {
                position: absolute;
                top: -30px;
                left: 50%;
                transform: translateX(-50%);
                font-size: 0.75rem;
                font-weight: 600;
                color: $text-dark;
                white-space: nowrap;
                opacity: 0.7;
                transition: all 0.3s ease;
            }
        }

        .bar-label {
            font-size: 0.85rem;
            font-weight: 500;
            color: $text-dark;
            text-align: center;
        }
    }

    @media (max-width: 768px) {
        .chart-bars {
            gap: 0.5rem;
        }

        .bar-column {
            width: 40px !important;

            .bar-value {
                font-size: 0.65rem;
                top: -25px;
            }
        }

        .bar-label {
            font-size: 0.7rem;
        }
    }
}

.info-footer {
    margin-top: 1.5rem;
    padding-top: 1rem;
    border-top: 1px solid $gray-border;
    text-align: center;

    small {
        color: $text-muted;
        font-size: 0.75rem;
    }
}

// Gráfico de columnas agrupadas
.grafico-columnas {
    margin-top: 1rem;
    overflow-x: auto;

    .columnas-container {
        display: flex;
        justify-content: space-around;
        align-items: flex-end;
        gap: 1.5rem;
        min-height: 300px;
        padding: 1rem 0;

        .columna-semana {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;

            .barras-agrupadas {
                display: flex;
                gap: 0.25rem;
                align-items: flex-end;
                height: 220px;

                .barra-columna {
                    width: 35px;
                    border-radius: 6px 6px 0 0;
                    position: relative;
                    transition: height 0.5s ease;
                    cursor: pointer;
                    min-height: 5px;

                    &:hover {
                        opacity: 0.8;
                        transform: scaleX(1.05);

                        .valor-columna {
                            opacity: 1;
                            transform: translateY(-5px);
                        }
                    }

                    .valor-columna {
                        position: absolute;
                        top: -25px;
                        left: 50%;
                        transform: translateX(-50%);
                        font-size: 0.7rem;
                        font-weight: 600;
                        white-space: nowrap;
                        opacity: 0.7;
                        transition: all 0.3s ease;
                    }
                }

                .enviadas {
                    background: linear-gradient(180deg, $primary-color 0%, 100%);
                }

                .aceptadas {
                    background: linear-gradient(180deg, $success-color 0%, 100%);
                }

                .incidentes {
                    background: linear-gradient(180deg, $danger-color 0%, 100%);
                }
            }

            .etiqueta-semana {
                font-size: 0.75rem;
                font-weight: 500;
                color: $text-muted;
                text-align: center;
            }
        }
    }

    .leyenda-grafico {
        display: flex;
        justify-content: center;
        gap: 2rem;
        margin-top: 1.5rem;
        padding-top: 1rem;
        border-top: 1px solid $gray-border;

        .leyenda-item {
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;

            &::before {
                content: '';
                width: 16px;
                height: 16px;
                border-radius: 4px;
                display: inline-block;
            }

            &.enviadas-leyenda::before {
                background: $primary-color;
            }

            &.aceptadas-leyenda::before {
                background: $success-color;
            }

            &.incidentes-leyenda::before {
                background: $danger-color;
            }
        }
    }
}

.imgGraficos {
    width: 1500px;
}
</style>
