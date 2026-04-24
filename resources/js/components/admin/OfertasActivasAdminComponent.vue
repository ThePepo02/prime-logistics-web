<template>
    <div class="operaciones-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <h2>Admin Panel</h2>
            </div>
            <nav class="nav-menu">
                <div class="nav-section">
                    <div class="nav-title">Información</div>
                    <ul>
                        <li><a href="#" @click.prevent="cambiarVista('dashboard')">Dashboard</a></li>
                        <li><a href="#" @click.prevent="cambiarVista('usuarios')">Gestión de Usuarios</a></li>
                        <li><a href="#" class="active">Operaciones Activas</a></li>
                    </ul>
                </div>
                <div class="nav-section">
                    <div class="nav-title">Comunicaciones</div>
                    <ul>
                        <li><a href="#" @click.prevent="cambiarVista('ofertas')">Todas las Ofertas</a></li>
                    </ul>
                </div>
            </nav>
        </div>

        <!-- Contenido principal -->
        <div class="main-content">
            <div class="header">
                <h1>Operaciones Activas</h1>
                <button @click="cargarDatos" class="btn-refresh" :disabled="cargando">
                    {{ cargando ? 'Cargando...' : '⟳ Actualizar' }}
                </button>
            </div>

            <!-- Mensaje de error -->
            <div v-if="mensajeError" class="alert error">
                {{ mensajeError }}
                <button @click="mensajeError = null">×</button>
            </div>

            <!-- KPIs -->
            <div class="kpi-grid">
                <div class="kpi-card">
                    <div class="kpi-number">{{ estadisticas.total_activas || 0 }}</div>
                    <div class="kpi-label">Operaciones Activas</div>
                    <div class="kpi-sub">En curso actualmente</div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-number">{{ estadisticas.total_entradas || 0 }}</div>
                    <div class="kpi-label">Entradas</div>
                    <div class="kpi-sub">Operaciones de importación</div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-number">{{ estadisticas.total_salidas || 0 }}</div>
                    <div class="kpi-label">Salidas</div>
                    <div class="kpi-sub">Operaciones de exportación</div>
                </div>
            </div>

            <!-- Distribución por Modo -->
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Distribución por Modo de Transporte</h3>
                    <div v-for="modo in distribucionModos" :key="modo.nombre" class="stat-item">
                        <span class="stat-label">{{ modo.nombre }}</span>
                        <span class="stat-value">{{ modo.cantidad }} envíos - {{ modo.porcentaje }}%</span>
                        <div class="progress-bar">
                            <div class="progress-fill" :style="{ width: modo.porcentaje + '%', backgroundColor: modo.color }"></div>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <h3>Estadísticas Rápidas</h3>
                    <div class="stat-item">
                        <span class="stat-label">Total Operaciones</span>
                        <span class="stat-value">{{ estadisticas.total_general || 0 }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Valor Total</span>
                        <span class="stat-value">${{ formatoMoneda(valorTotalOperaciones) }}</span>
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="filters-bar">
                <input 
                    v-model="filtros.busqueda" 
                    type="text" 
                    placeholder="Buscar por código, cliente o empresa..."
                    class="search-input"
                    @input="aplicarFiltros"
                />
                <select v-model="filtros.modo" class="filter-select" @change="aplicarFiltros">
                    <option value="">Todos los modos</option>
                    <option value="MARITIMO">Marítimo</option>
                    <option value="AEREO">Aéreo</option>
                    <option value="TERRESTRE">Terrestre</option>
                    <option value="MULTIMODAL">Multimodal</option>
                </select>
                <select v-model="filtros.estado" class="filter-select" @change="aplicarFiltros">
                    <option value="">Todos los estados</option>
                    <option value="EN_CURSO">En Curso</option>
                    <option value="FINALIZADO">Finalizado</option>
                    <option value="PENDIENTE">Pendiente</option>
                </select>
            </div>

            <!-- Tabla -->
            <div class="table-wrapper">
                <table class="operaciones-table">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Cliente</th>
                            <th>Empresa</th>
                            <th>Tipo</th>
                            <th>Origen</th>
                            <th>Destino</th>
                            <th>Modo</th>
                            <th>Fase Actual</th>
                            <th>Estado</th>
                            <th v-if="esAdmin">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="cargando && !operaciones.length">
                            <td colspan="10" class="text-center">
                                <div class="spinner"></div>
                                Cargando operaciones...
                            </td>
                        </tr>
                        <tr v-else-if="!cargando && !operaciones.length">
                            <td colspan="10" class="text-center">
                                No se encontraron operaciones
                            </td>
                        </tr>
                        <tr v-for="operacion in operaciones" :key="operacion.id">
                            <td class="codigo-cell">{{ operacion.codigo }}</td>
                            <td>{{ operacion.cliente_nombre }}</td>
                            <td>{{ operacion.empresa_nombre }}</td>
                            <td>{{ operacion.tipo_servicio }}</td>
                            <td>{{ operacion.ruta_origen }}</td>
                            <td>{{ operacion.ruta_destino }}</td>
                            <td>
                                <span :class="['modo-badge', getModoClass(operacion.modo_envio)]">
                                    {{ getModoTexto(operacion.modo_envio) }}
                                </span>
                            </td>
                            <td>{{ operacion.fase_actual }}</td>
                            <td>
                                <span :class="['estado-badge', getEstadoClass(operacion.estado_operacion)]">
                                    {{ getEstadoTexto(operacion.estado_operacion) }}
                                </span>
                            </td>
                            <td v-if="esAdmin">
                                <button @click="editarOperacion(operacion)" class="btn-icon" title="Editar">✏️</button>
                                <button @click="eliminarOperacion(operacion.id)" class="btn-icon" title="Eliminar">🗑️</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="pagination-section" v-if="totalPaginas > 1">
                <div class="pagination-info">
                    Mostrando {{ (paginaActual - 1) * itemsPorPagina + 1 }} - 
                    {{ Math.min(paginaActual * itemsPorPagina, totalItems) }} de {{ totalItems }} operaciones
                </div>
                <div class="pagination">
                    <button @click="cambiarPagina(paginaActual - 1)" :disabled="paginaActual === 1" class="page-btn">
                        Anterior
                    </button>
                    <button 
                        v-for="pag in paginasVisibles" 
                        :key="pag"
                        @click="cambiarPagina(pag)"
                        :class="['page-btn', { active: pag === paginaActual }]"
                        :disabled="pag === '...'"
                    >
                        {{ pag }}
                    </button>
                    <button @click="cambiarPagina(paginaActual + 1)" :disabled="paginaActual === totalPaginas" class="page-btn">
                        Siguiente
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'

// Estado
const cargando = ref(false)
const mensajeError = ref(null)
const esAdmin = ref(true) // Cambiar según roles

// Datos
const operaciones = ref([])
const distribucionModos = ref([])
const estadisticas = ref({
    total_activas: 0,
    total_entradas: 0,
    total_salidas: 0,
    total_general: 0
})

// Filtros y paginación
const paginaActual = ref(1)
const itemsPorPagina = ref(10)
const totalItems = ref(0)
const totalPaginas = ref(0)

const filtros = ref({
    busqueda: '',
    modo: '',
    estado: ''
})

// URL base de la API
const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api/v1'

// Métodos
const cargarOperaciones = async () => {
    cargando.value = true
    mensajeError.value = null
    
    try {
        const params = new URLSearchParams({
            page: paginaActual.value,
            por_pagina: itemsPorPagina.value
        })
        
        if (filtros.value.busqueda) params.append('busqueda', filtros.value.busqueda)
        if (filtros.value.modo) params.append('modo', filtros.value.modo)
        if (filtros.value.estado) params.append('estado', filtros.value.estado)
        
        const response = await fetch(`${API_URL}/operaciones?${params}`)
        const data = await response.json()
        
        if (data.success) {
            operaciones.value = data.data
            totalItems.value = data.total
            totalPaginas.value = data.ultima_pagina
        } else {
            throw new Error(data.message || 'Error al cargar operaciones')
        }
    } catch (error) {
        console.error('Error:', error)
        mensajeError.value = 'Error al cargar las operaciones'
        operaciones.value = []
    } finally {
        cargando.value = false
    }
}

const cargarDistribucion = async () => {
    try {
        const response = await fetch(`${API_URL}/operaciones-estadisticas/distribucion`)
        const data = await response.json()
        if (data.success) {
            distribucionModos.value = data.data
        }
    } catch (error) {
        console.error('Error cargando distribución:', error)
    }
}

const cargarEstadisticas = async () => {
    try {
        const response = await fetch(`${API_URL}/operaciones-estadisticas/totales`)
        const data = await response.json()
        if (data.success) {
            estadisticas.value = data
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

const aplicarFiltros = () => {
    paginaActual.value = 1
    cargarOperaciones()
}

const cambiarPagina = (pagina) => {
    if (pagina >= 1 && pagina <= totalPaginas.value) {
        paginaActual.value = pagina
        cargarOperaciones()
    }
}

const eliminarOperacion = async (id) => {
    if (!confirm('¿Estás seguro de eliminar esta operación?')) return
    
    try {
        const response = await fetch(`${API_URL}/operaciones/${id}`, {
            method: 'DELETE'
        })
        const data = await response.json()
        
        if (data.success) {
            await cargarDatos()
        } else {
            throw new Error(data.message)
        }
    } catch (error) {
        mensajeError.value = 'Error al eliminar la operación'
    }
}

const editarOperacion = (operacion) => {
    console.log('Editar operación:', operacion)
    // Implementar modal de edición
}

// Computed
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

const valorTotalOperaciones = computed(() => {
    return operaciones.value.reduce((total, op) => total + (op.valor_total || 0), 0)
})

// Utils
const formatoMoneda = (valor) => {
    return new Intl.NumberFormat('es-ES', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(valor)
}

const getModoTexto = (modo) => {
    const modos = {
        'MARITIMO': 'Marítimo',
        'AEREO': 'Aéreo',
        'TERRESTRE': 'Terrestre',
        'MULTIMODAL': 'Multimodal'
    }
    return modos[modo] || modo
}

const getModoClass = (modo) => {
    const clases = {
        'MARITIMO': 'modo-maritimo',
        'AEREO': 'modo-aereo',
        'TERRESTRE': 'modo-terrestre',
        'MULTIMODAL': 'modo-multimodal'
    }
    return clases[modo] || ''
}

const getEstadoTexto = (estado) => {
    const estados = {
        'EN_CURSO': 'En Curso',
        'FINALIZADO': 'Finalizado',
        'PENDIENTE': 'Pendiente',
        'CANCELADO': 'Cancelado'
    }
    return estados[estado] || estado
}

const getEstadoClass = (estado) => {
    const clases = {
        'EN_CURSO': 'estado-curso',
        'FINALIZADO': 'estado-finalizado',
        'PENDIENTE': 'estado-pendiente',
        'CANCELADO': 'estado-cancelado'
    }
    return clases[estado] || ''
}

const cambiarVista = (vista) => {
    console.log('Cambiar a vista:', vista)
    // Implementar navegación
}

// Watch
watch(filtros, () => {
    aplicarFiltros()
}, { deep: true })

// Lifecycle
onMounted(() => {
    cargarDatos()
})
</script>

<style scoped>
/* Estilos actualizados sin conflictos */
.operaciones-container {
    display: flex;
    min-height: 100vh;
    background: #f5f7fa;
    font-family: 'Segoe UI', system-ui, sans-serif;
}

.sidebar {
    width: 260px;
    background: #1a202c;
    color: white;
    position: fixed;
    height: 100vh;
    overflow-y: auto;
}

.logo {
    padding: 20px;
    border-bottom: 1px solid #2d3748;
}

.logo h2 {
    margin: 0;
    font-size: 20px;
    color: #48bb78;
}

.nav-menu ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.nav-section {
    margin-bottom: 20px;
}

.nav-title {
    padding: 10px 20px;
    font-size: 12px;
    text-transform: uppercase;
    color: #a0aec0;
    letter-spacing: 1px;
}

.nav-menu li a {
    display: block;
    padding: 10px 20px;
    color: #cbd5e0;
    text-decoration: none;
    transition: all 0.3s;
    cursor: pointer;
}

.nav-menu li a:hover,
.nav-menu li a.active {
    background: #2d3748;
    color: #48bb78;
}

.main-content {
    flex: 1;
    margin-left: 260px;
    padding: 20px 30px;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.header h1 {
    margin: 0;
    color: #1a202c;
}

.btn-refresh {
    padding: 8px 16px;
    background: #48bb78;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-refresh:hover:not(:disabled) {
    background: #38a169;
}

.btn-refresh:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.alert {
    padding: 12px 16px;
    border-radius: 6px;
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.alert.error {
    background: #fed7d7;
    color: #9b2c2c;
    border: 1px solid #fc8181;
}

.alert button {
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    color: #9b2c2c;
}

.kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.kpi-card {
    background: white;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.kpi-number {
    font-size: 42px;
    font-weight: bold;
    color: #48bb78;
}

.kpi-label {
    font-size: 14px;
    color: #4a5568;
    margin-top: 5px;
    font-weight: 600;
}

.kpi-sub {
    font-size: 12px;
    color: #a0aec0;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.stat-card h3 {
    margin: 0 0 15px 0;
    color: #1a202c;
    font-size: 16px;
}

.stat-item {
    margin-bottom: 12px;
}

.stat-label {
    display: block;
    font-weight: 500;
    color: #4a5568;
    margin-bottom: 4px;
}

.stat-value {
    display: block;
    color: #718096;
    font-size: 14px;
    margin-bottom: 5px;
}

.progress-bar {
    background: #e2e8f0;
    height: 6px;
    border-radius: 3px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    transition: width 0.3s;
}

.filters-bar {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.search-input {
    flex: 1;
    min-width: 250px;
    padding: 10px 12px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    font-size: 14px;
}

.filter-select {
    padding: 10px 12px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    background: white;
    cursor: pointer;
}

.table-wrapper {
    background: white;
    border-radius: 10px;
    overflow-x: auto;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.operaciones-table {
    width: 100%;
    border-collapse: collapse;
}

.operaciones-table th {
    background: #f7fafc;
    color: #1a202c;
    padding: 12px 15px;
    text-align: left;
    font-weight: 600;
    font-size: 13px;
    border-bottom: 2px solid #e2e8f0;
}

.operaciones-table td {
    padding: 12px 15px;
    border-bottom: 1px solid #e2e8f0;
    font-size: 14px;
}

.codigo-cell {
    font-weight: 600;
    color: #48bb78;
}

.modo-badge, .estado-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.modo-maritimo { background: #bee3f8; color: #2c5282; }
.modo-aereo { background: #c6f6d5; color: #22543d; }
.modo-terrestre { background: #feebc8; color: #7b341e; }
.modo-multimodal { background: #e9d8fd; color: #44337a; }

.estado-curso { background: #c6f6d5; color: #22543d; }
.estado-finalizado { background: #bee3f8; color: #2c5282; }
.estado-pendiente { background: #feebc8; color: #7b341e; }
.estado-cancelado { background: #fed7d7; color: #9b2c2c; }

.btn-icon {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 18px;
    padding: 5px;
    margin: 0 3px;
    transition: transform 0.2s;
}

.btn-icon:hover {
    transform: scale(1.1);
}

.pagination-section {
    margin-top: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.pagination-info {
    color: #718096;
    font-size: 14px;
}

.pagination {
    display: flex;
    gap: 8px;
}

.page-btn {
    padding: 8px 12px;
    border: 1px solid #e2e8f0;
    background: white;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s;
}

.page-btn:hover:not(:disabled):not(.active) {
    background: #f7fafc;
}

.page-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.page-btn.active {
    background: #48bb78;
    color: white;
    border-color: #48bb78;
}

.text-center {
    text-align: center;
}

.spinner {
    border: 3px solid #e2e8f0;
    border-top: 3px solid #48bb78;
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

@media (max-width: 768px) {
    .sidebar {
        width: 100%;
        position: relative;
        height: auto;
    }
    
    .main-content {
        margin-left: 0;
    }
    
    .kpi-grid,
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .filters-bar {
        flex-direction: column;
    }
    
    .pagination-section {
        flex-direction: column;
        align-items: center;
    }
}
</style>