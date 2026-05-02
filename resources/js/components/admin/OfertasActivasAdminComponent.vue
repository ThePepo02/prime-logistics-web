<template>
    <div class="operaciones-container">
        <!-- Sidebar según diseño del figma -->
        <div class="sidebar">
            <div class="logo">
                <h2>Admin Panel</h2>
            </div>
            <nav class="nav-menu">
                <div class="nav-section">
                    <div class="nav-title">Menu Principal</div>
                    <ul>
                        <li><a href="#" @click.prevent>Administración</a></li>
                        <li><a href="#" @click.prevent>Servicios</a></li>
                        <li><a href="#" @click.prevent>Contabilidad</a></li>
                        <li><a href="#" @click.prevent>Recursos Humanos</a></li>
                        <li><a href="#" @click.prevent>Empresas</a></li>
                        <li><a href="#" @click.prevent>Datos Personales</a></li>
                        <li><a href="#" @click.prevent>Noticias</a></li>
                        <li><a href="#" @click.prevent>Aplicaciones</a></li>
                        <li><a href="#" @click.prevent>Portal de usuario</a></li>
                    </ul>
                </div>
            </nav>
        </div>

        <!-- Contenido principal -->
        <div class="main-content">
            <div class="header">
                <h1>Operaciones Activas admin</h1>
                <button @click="cargarDatos" class="btn-refresh" :disabled="cargando">
                    {{ cargando ? 'Cargando...' : '⟳ Actualizar' }}
                </button>
            </div>

            <!-- Mensaje de error -->
            <div v-if="mensajeError" class="alert error">
                {{ mensajeError }}
                <button @click="mensajeError = null">×</button>
            </div>

            <!-- KPI Cards según imagen -->
            <div class="kpi-section">
                <div class="kpi-main-card">
                    <div class="kpi-number">{{ estadisticas.operaciones_activas || 0 }}</div>
                    <div class="kpi-label">Operaciones Activas</div>
                </div>

                <div class="kpi-stats-grid">
                    <div class="stat-item">
                        <span class="stat-value">{{ estadisticas.trabajos_activos || 0 }}</span>
                        <span class="stat-label">De Trabajo</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">{{ estadisticas.proyectos_activos || 0 }}</span>
                        <span class="stat-label">Proyectos activos</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">{{ estadisticas.proyectos_curso || 0 }}</span>
                        <span class="stat-label">Proyectos en curso</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">{{ estadisticas.proyectos_pendientes || 0 }}</span>
                        <span class="stat-label">Proyectos pendientes</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">{{ estadisticas.proyectos_cancelados || 0 }}</span>
                        <span class="stat-label">Proyectos cancelados</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">{{ estadisticas.proyectos_abiertos || 0 }}</span>
                        <span class="stat-label">Proyectos abiertos</span>
                    </div>
                </div>
            </div>

            <!-- Distribución por Medio de Transporte -->
            <div class="distribucion-section">
                <h3>Distribución por Medio de Transporte</h3>
                <div class="distribucion-header">
                    <span class="medida-label">Medidas</span>
                    <span class="cantidad-label">Cantidad</span>
                </div>
                <div v-for="modo in distribucionModos" :key="modo.nombre" class="distribucion-item">
                    <div class="medida-info">
                        <span class="medida-nombre">{{ modo.nombre }}</span>
                        <div class="progress-bar">
                            <div class="progress-fill"
                                :style="{ width: modo.porcentaje + '%', backgroundColor: modo.color }"></div>
                        </div>
                    </div>
                    <div class="cantidad-info">
                        <span class="cantidad-valor">{{ modo.cantidad }}</span>
                        <span class="cantidad-total">Total de trabajos</span>
                        <span class="cantidad-proyectos">Total de proyectos</span>
                    </div>
                </div>
            </div>

            <!-- Tiempo de distribución -->
            <div class="tiempo-section">
                <h3>Tiempo de distribución</h3>
                <div class="tiempo-grid">
                    <div v-for="tiempo in tiemposDistribucion" :key="tiempo.nombre" class="tiempo-card">
                        <div class="tiempo-valor">{{ tiempo.valor }}</div>
                        <div class="tiempo-porcentaje">{{ tiempo.porcentaje }}%</div>
                        <div class="tiempo-nombre">{{ tiempo.nombre }}</div>
                    </div>
                </div>
            </div>

            <!-- Operaciones en Curso -->
            <div class="operaciones-curso-section">
                <div class="section-header">
                    <h3>Operaciones en Curso</h3>
                    <span class="servicio-info">El servicio es activo</span>
                </div>

                <!-- Filtros -->
                <div class="filtros-operaciones">
                    <button v-for="tab in tabsOperaciones" :key="tab.key" @click="tabActivo = tab.key"
                        :class="['tab-btn', { active: tabActivo === tab.key }]">
                        {{ tab.nombre }}
                    </button>
                </div>

                <!-- Tabla de operaciones -->
                <div class="table-wrapper">
                    <table class="operaciones-table">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Código</th>
                                <th>Expediente</th>
                                <th>Modelo</th>
                                <th>Ruta</th>
                                <th>CPV</th>
                                <th>ISIN</th>
                                <th>Hora-Activo</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="cargando && !operacionesFiltradas.length">
                                <td colspan="9" class="text-center">
                                    <div class="spinner"></div>
                                    Cargando operaciones...
                                </td>
                            </tr>
                            <tr v-else-if="!cargando && !operacionesFiltradas.length">
                                <td colspan="9" class="text-center">
                                    No se encontraron operaciones
                                </td>
                            </tr>
                            <tr v-for="operacion in operacionesPaginadas" :key="operacion.id">
                                <td>{{ formatFecha(operacion.fecha) }}</td>
                                <td class="codigo-cell">{{ operacion.codigo }}</td>
                                <td>{{ operacion.expediente }}</td>
                                <td>{{ operacion.modelo }}</td>
                                <td>{{ operacion.ruta }}</td>
                                <td>{{ formatMoneda(operacion.cpv) }}</td>
                                <td>{{ formatMoneda(operacion.isin) }}</td>
                                <td>{{ operacion.hora_activo }}</td>
                                <td>
                                    <span :class="['status-badge', getStatusClass(operacion.status)]">
                                        {{ operacion.status }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="pagination-section">
                    <div class="pagination-info">
                        Manejo de {{ (paginaActual - 1) * itemsPorPagina + 1 }} -
                        {{ Math.min(paginaActual * itemsPorPagina, totalItems) }} de {{ totalItems }} operaciones
                    </div>
                    <div class="pagination">
                        <button @click="cambiarPagina(paginaActual - 1)" :disabled="paginaActual === 1"
                            class="page-btn">
                            ◀ Anterior
                        </button>
                        <button v-for="pag in paginasVisibles" :key="pag" @click="cambiarPagina(pag)"
                            :class="['page-btn', { active: pag === paginaActual }]">
                            {{ pag }}
                        </button>
                        <button @click="cambiarPagina(paginaActual + 1)" :disabled="paginaActual === totalPaginas"
                            class="page-btn">
                            Siguiente ▶
                        </button>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <button class="btn-calificar" @click="calificarServicio">
                    Califica Más Servicio ⭐
                </button>
            </div>
        </div>

        <!-- Toast de notificación -->
        <div v-if="toast.show" :class="['toast-notification', toast.type]">
            {{ toast.message }}
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import axios from 'axios'

// Configuración
const API_BASE_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api/v1'

// Estado
const cargando = ref(false)
const mensajeError = ref(null)
const tabActivo = ref('activas')

// Toast notification
const toast = ref({
    show: false,
    message: '',
    type: 'success'
})

// Datos
const operaciones = ref([])
const distribucionModos = ref([])
const tiemposDistribucion = ref([
    { nombre: 'Metrónica', valor: '31 minutos', porcentaje: 52 },
    { nombre: 'Marea', valor: '12 minutos', porcentaje: 39 },
    { nombre: 'Ferrocarril', valor: '4 minutos', porcentaje: 11 },
    { nombre: 'Multimodal', valor: '1 minuto', porcentaje: 3 }
])

const estadisticas = ref({
    operaciones_activas: 38,
    trabajos_activos: 21,
    proyectos_activos: 15,
    proyectos_curso: 28,
    proyectos_pendientes: 12,
    proyectos_cancelados: 5,
    proyectos_abiertos: 8
})

// Tabs de operaciones
const tabsOperaciones = ref([
    { key: 'activas', nombre: 'Operaciones Activas' },
    { key: 'canceladas', nombre: 'Operaciones Canceladas' },
    { key: 'abiertas', nombre: 'Operaciones Abiertas' }
])

// Paginación
const paginaActual = ref(1)
const itemsPorPagina = ref(8)
const totalItems = ref(0)
const totalPaginas = ref(0)

// Métodos
const mostrarToast = (message, type = 'success') => {
    toast.value = { show: true, message, type }
    setTimeout(() => { toast.value.show = false }, 3000)
}

const cargarOperaciones = async () => {
    cargando.value = true
    mensajeError.value = null

    try {
        const response = await axios.get(`${API_BASE_URL}/operaciones`, {
            params: {
                estado: tabActivo.value.toUpperCase(),
                page: paginaActual.value,
                per_page: itemsPorPagina.value
            }
        })

        if (response.data.success) {
            operaciones.value = response.data.data
            totalItems.value = response.data.total || response.data.data.length
            totalPaginas.value = response.data.last_page || Math.ceil(totalItems.value / itemsPorPagina.value)
        }
    } catch (error) {
        console.error('Error:', error)
        // Datos mock para desarrollo
        operaciones.value = [
            { id: 1, fecha: '2024-01-15', codigo: '02-2022-001', expediente: 'Nada Genia', modelo: 'Textil GT', ruta: 'Materiales', cpv: 0, isin: 15000, hora_activo: 'G. Transporte Solidaria', status: 'FINALIZADO' },
            { id: 2, fecha: '2024-01-16', codigo: '02-2022-002', expediente: 'Pedro Lozza', modelo: 'Vuelo Equipos', ruta: 'Aviación', cpv: 0, isin: 17000, hora_activo: 'Santiago Punto', status: 'FINALIZADO' },
            { id: 3, fecha: '2024-01-17', codigo: '02-2022-003', expediente: 'Maria Gavina', modelo: 'Textil TK', ruta: 'Aviación', cpv: 0, isin: 5000, hora_activo: 'Adama Transportación', status: 'FINALIZADO' },
            { id: 4, fecha: '2024-01-18', codigo: '02-2022-004', expediente: 'Lukas Galindo', modelo: 'Textil marcos', ruta: 'Materiales', cpv: 0, isin: 25000, hora_activo: 'Cargo a domicilio', status: 'FINALIZADO' },
            { id: 5, fecha: '2024-01-19', codigo: '02-2022-005', expediente: 'Pedro Lozza', modelo: 'Vuelo Equipos', ruta: 'Teñizado', cpv: 0, isin: 13000, hora_activo: 'Transporte Telecom', status: 'FINALIZADO' },
            { id: 6, fecha: '2024-01-20', codigo: '02-2022-006', expediente: 'Sara Ruiz', modelo: 'Product Detail', ruta: 'Materiales', cpv: 0, isin: 13000, hora_activo: 'Ruberto Caceres', status: 'FINALIZADO' },
            { id: 7, fecha: '2024-01-21', codigo: '02-2022-007', expediente: 'María García', modelo: 'Textil LA', ruta: 'Airea', cpv: 11000, isin: 10000, hora_activo: 'Ferrocarril Enrique', status: 'FINALIZADO' },
            { id: 8, fecha: '2024-01-22', codigo: '02-2022-008', expediente: 'Rocío Salgado', modelo: 'Product Sales', ruta: 'Materiales', cpv: 50000, isin: 19000, hora_activo: 'Propiedad', status: 'FINALIZADO' }
        ]
        totalItems.value = operaciones.value.length
        totalPaginas.value = Math.ceil(totalItems.value / itemsPorPagina.value)
    } finally {
        cargando.value = false
    }
}

const cargarDistribucion = async () => {
    try {
        const response = await axios.get(`${API_BASE_URL}/operaciones/distribucion-modos`)
        if (response.data.success) {
            distribucionModos.value = response.data.data
        } else {
            // Datos mock
            distribucionModos.value = [
                { nombre: 'Metrónica', cantidad: 21, porcentaje: 45, color: '#3498db' },
                { nombre: 'Marea', cantidad: 15, porcentaje: 32, color: '#2ecc71' },
                { nombre: 'Ferrocarril', cantidad: 8, porcentaje: 17, color: '#e74c3c' },
                { nombre: 'Multimodal', cantidad: 3, porcentaje: 6, color: '#f39c12' }
            ]
        }
    } catch (error) {
        console.error('Error cargando distribución:', error)
    }
}

const cargarEstadisticas = async () => {
    try {
        const response = await axios.get(`${API_BASE_URL}/operaciones/estadisticas`)
        if (response.data.success) {
            estadisticas.value = response.data.data
        }
    } catch (error) {
        console.error('Error cargando estadísticas:', error)
    }
}

const cargarDatos = async () => {
    await Promise.all([
        cargarOperaciones(),
        cargarDistribucion(),
        cargarEstadisticas()
    ])
}

// Computed
const operacionesFiltradas = computed(() => {
    return operaciones.value.filter(op => {
        if (tabActivo.value === 'activas') return op.status === 'EN_CURSO' || op.status === 'FINALIZADO'
        if (tabActivo.value === 'canceladas') return op.status === 'CANCELADO'
        if (tabActivo.value === 'abiertas') return op.status === 'PENDIENTE'
        return true
    })
})

const operacionesPaginadas = computed(() => {
    const inicio = (paginaActual.value - 1) * itemsPorPagina.value
    const fin = inicio + itemsPorPagina.value
    return operacionesFiltradas.value.slice(inicio, fin)
})

const paginasVisibles = computed(() => {
    const maxVisibles = 5
    const paginas = []

    if (totalPaginas.value <= maxVisibles) {
        for (let i = 1; i <= totalPaginas.value; i++) paginas.push(i)
    } else {
        if (paginaActual.value <= 3) {
            paginas.push(1, 2, 3, 4, '...', totalPaginas.value)
        } else if (paginaActual.value >= totalPaginas.value - 2) {
            paginas.push(1, '...', totalPaginas.value - 3, totalPaginas.value - 2, totalPaginas.value - 1, totalPaginas.value)
        } else {
            paginas.push(1, '...', paginaActual.value - 1, paginaActual.value, paginaActual.value + 1, '...', totalPaginas.value)
        }
    }
    return paginas
})

// Métodos de utilidad
const formatFecha = (fecha) => {
    if (!fecha) return ''
    const [year, month, day] = fecha.split('-')
    return `${day}/${month}/${year}`
}

const formatMoneda = (valor) => {
    if (valor === 0) return '0,00 €'
    return new Intl.NumberFormat('es-ES', {
        style: 'currency',
        currency: 'EUR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(valor)
}

const getStatusClass = (status) => {
    const clases = {
        'FINALIZADO': 'status-finalizado',
        'EN_CURSO': 'status-curso',
        'PENDIENTE': 'status-pendiente',
        'CANCELADO': 'status-cancelado'
    }
    return clases[status] || 'status-default'
}

const cambiarPagina = (pagina) => {
    if (pagina >= 1 && pagina <= totalPaginas.value) {
        paginaActual.value = pagina
        cargarOperaciones()
    }
}

const calificarServicio = () => {
    mostrarToast('Gracias por calificar nuestro servicio ⭐', 'success')
}

// Watch para cambios de tab
watch(tabActivo, () => {
    paginaActual.value = 1
    cargarOperaciones()
})

// Lifecycle
onMounted(() => {
    cargarDatos()
})
</script>

<style scoped>
.operaciones-container {
    display: flex;
    min-height: 100vh;
    background: #f0f2f5;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Sidebar */
.sidebar {
    width: 260px;
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    color: white;
    position: fixed;
    height: 100vh;
    overflow-y: auto;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
}

.logo {
    padding: 25px 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.logo h2 {
    margin: 0;
    font-size: 20px;
    font-weight: 600;
    background: linear-gradient(135deg, #e67e22, #f39c12);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.nav-menu {
    padding: 20px 0;
}

.nav-section {
    margin-bottom: 25px;
}

.nav-title {
    padding: 10px 20px;
    font-size: 11px;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.5);
    letter-spacing: 1.5px;
    font-weight: 600;
}

.nav-menu ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.nav-menu li a {
    display: block;
    padding: 10px 20px;
    color: rgba(255, 255, 255, 0.7);
    text-decoration: none;
    transition: all 0.3s;
    font-size: 14px;
}

.nav-menu li a:hover {
    background: rgba(255, 255, 255, 0.1);
    color: white;
    padding-left: 25px;
}

/* Main Content */
.main-content {
    flex: 1;
    margin-left: 260px;
    padding: 25px 30px;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.header h1 {
    margin: 0;
    color: #2c3e50;
    font-size: 24px;
    font-weight: 600;
}

.btn-refresh {
    padding: 8px 18px;
    background: #e67e22;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 13px;
}

.btn-refresh:hover:not(:disabled) {
    background: #d35400;
    transform: translateY(-1px);
}

/* Alert */
.alert {
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.alert.error {
    background: #fee;
    color: #c0392b;
    border-left: 4px solid #c0392b;
}

/* KPI Section */
.kpi-section {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 25px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.kpi-main-card {
    text-align: center;
    padding: 15px;
    background: linear-gradient(135deg, #e67e22, #f39c12);
    border-radius: 10px;
    color: white;
    margin-bottom: 20px;
}

.kpi-number {
    font-size: 48px;
    font-weight: bold;
}

.kpi-label {
    font-size: 14px;
    opacity: 0.9;
}

.kpi-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
}

.stat-item {
    text-align: center;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 8px;
}

.stat-value {
    font-size: 28px;
    font-weight: bold;
    color: #e67e22;
    display: block;
}

.stat-label {
    font-size: 12px;
    color: #666;
    margin-top: 5px;
}

/* Distribución Section */
.distribucion-section {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 25px;
}

.distribucion-section h3 {
    margin: 0 0 15px 0;
    color: #2c3e50;
    font-size: 18px;
}

.distribucion-header {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 2px solid #ecf0f1;
    margin-bottom: 15px;
    font-weight: 600;
    color: #7f8c8d;
    font-size: 13px;
}

.distribucion-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #ecf0f1;
}

.medida-info {
    flex: 2;
}

.medida-nombre {
    font-weight: 500;
    color: #2c3e50;
    margin-bottom: 5px;
    display: block;
}

.progress-bar {
    background: #ecf0f1;
    height: 6px;
    border-radius: 3px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    transition: width 0.3s;
    border-radius: 3px;
}

.cantidad-info {
    flex: 1;
    text-align: right;
}

.cantidad-valor {
    font-size: 24px;
    font-weight: bold;
    color: #e67e22;
    display: block;
}

.cantidad-total,
.cantidad-proyectos {
    font-size: 10px;
    color: #95a5a6;
    display: block;
}

/* Tiempo Section */
.tiempo-section {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 25px;
}

.tiempo-section h3 {
    margin: 0 0 15px 0;
    color: #2c3e50;
    font-size: 18px;
}

.tiempo-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.tiempo-card {
    text-align: center;
    padding: 15px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    color: white;
}

.tiempo-valor {
    font-size: 28px;
    font-weight: bold;
}

.tiempo-porcentaje {
    font-size: 20px;
    margin: 5px 0;
}

.tiempo-nombre {
    font-size: 12px;
    opacity: 0.9;
}

/* Operaciones en Curso */
.operaciones-curso-section {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 25px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.section-header h3 {
    margin: 0;
    color: #2c3e50;
    font-size: 18px;
}

.servicio-info {
    font-size: 12px;
    color: #27ae60;
    background: #e8f8f5;
    padding: 4px 12px;
    border-radius: 20px;
}

/* Filtros */
.filtros-operaciones {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.tab-btn {
    padding: 8px 20px;
    background: #ecf0f1;
    border: none;
    border-radius: 25px;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 13px;
}

.tab-btn:hover {
    background: #bdc3c7;
}

.tab-btn.active {
    background: #e67e22;
    color: white;
}

/* Tabla */
.table-wrapper {
    overflow-x: auto;
}

.operaciones-table {
    width: 100%;
    border-collapse: collapse;
}

.operaciones-table th {
    background: #f8f9fa;
    color: #2c3e50;
    padding: 12px;
    text-align: left;
    font-weight: 600;
    font-size: 12px;
    border-bottom: 2px solid #ecf0f1;
}

.operaciones-table td {
    padding: 12px;
    border-bottom: 1px solid #ecf0f1;
    font-size: 13px;
}

.codigo-cell {
    font-weight: 600;
    color: #e67e22;
}

.status-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}

.status-finalizado {
    background: #d5f5e3;
    color: #27ae60;
}

.status-curso {
    background: #d6eaf8;
    color: #2980b9;
}

.status-pendiente {
    background: #fdebd0;
    color: #e67e22;
}

.status-cancelado {
    background: #fadbd8;
    color: #c0392b;
}

/* Paginación */
.pagination-section {
    margin-top: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.pagination-info {
    font-size: 13px;
    color: #7f8c8d;
}

.pagination {
    display: flex;
    gap: 5px;
}

.page-btn {
    padding: 6px 12px;
    border: 1px solid #ecf0f1;
    background: white;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 12px;
}

.page-btn:hover:not(:disabled):not(.active) {
    background: #ecf0f1;
}

.page-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.page-btn.active {
    background: #e67e22;
    color: white;
    border-color: #e67e22;
}

/* Footer */
.footer {
    text-align: center;
    padding: 20px;
}

.btn-calificar {
    padding: 12px 30px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 25px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-calificar:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

/* Toast */
.toast-notification {
    position: fixed;
    bottom: 20px;
    right: 20px;
    padding: 12px 20px;
    border-radius: 8px;
    color: white;
    font-size: 14px;
    z-index: 2000;
    animation: slideInRight 0.3s ease;
}

.toast-notification.success {
    background: #27ae60;
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

/* Spinner */
.spinner {
    border: 3px solid #ecf0f1;
    border-top: 3px solid #e67e22;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    animation: spin 1s linear infinite;
    margin: 0 auto 10px;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}

.text-center {
    text-align: center;
}

/* Responsive */
@media (max-width: 768px) {
    .sidebar {
        width: 100%;
        position: relative;
        height: auto;
    }

    .main-content {
        margin-left: 0;
    }

    .kpi-stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .tiempo-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .pagination-section {
        flex-direction: column;
        align-items: center;
    }
}
</style>