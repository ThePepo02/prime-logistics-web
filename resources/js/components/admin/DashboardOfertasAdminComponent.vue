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
            </div>

            <!-- Distribución por Modo de Transporte -->
            <div class="distribucion-section">
                <h3>Distribución por Modo de Transporte</h3>
                <p class="subtitulo">Operaciones activas en curso</p>
                <div class="modos-grid">
                    <div class="modo-item" v-for="modo in distribucionModos" :key="modo.nombre">
                        <div class="modo-nombre">{{ modo.nombre }}</div>
                        <div class="modo-stats">
                            <span class="modo-cantidad">{{ modo.cantidad }} envíos</span>
                            <span class="modo-porcentaje">- {{ modo.porcentaje }}%</span>
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
                <input v-model="filters.search" type="text" placeholder="Buscar operación:" class="search-input" />
                <select v-model="filters.modo" class="filter-select">
                    <option value="">Todos los modos</option>
                    <option value="Marítimo">Marítimo</option>
                    <option value="Aéreo">Aéreo</option>
                    <option value="Terrestre">Terrestre</option>
                    <option value="Multimodal">Multimodal</option>
                </select>
                <select v-model="filters.ruta" class="filter-select">
                    <option value="">Todas las rutas</option>
                    <option value="Valencia-Shanghai">Valencia-Shanghai</option>
                    <option value="Barcelona-Nueva York">Barcelona-Nueva York</option>
                    <option value="Madrid-Miami">Madrid-Miami</option>
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
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="operacion in paginatedOperaciones" :key="operacion.id">
                            <td class="id-cell">{{ operacion.id }}</td>
                            <td>{{ operacion.cliente }}</td>
                            <td>{{ operacion.empresa }}</td>
                            <td><span class="modo-badge">{{ operacion.modo }}</span></td>
                            <td>{{ operacion.ruta }}</td>
                            <td>{{ operacion.etd }}</td>
                            <td>{{ operacion.eta }}</td>
                            <td>{{ operacion.faseActual }}</td>
                            <td>
                                <span class="estado-transito-badge">EN TRANSITO</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="pagination-info">
                Movimiento de {{ paginationInfo.from }} de {{ totalItems }} operaciones
            </div>

            <div class="pagination">
                <button class="page-btn" :disabled="currentPage === 1" @click="currentPage--">
                    Anterior
                </button>
                <button v-for="page in visiblePages" :key="page" :class="['page-btn', { active: page === currentPage }]"
                    @click="goToPage(page)">
                    {{ page }}
                </button>
                <button class="page-btn" :disabled="currentPage === totalPages" @click="currentPage++">
                    Siguiente
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import logotipo from '../../../img/logo-empresa.png'

const logoPrimeLogistics = logotipo

// Estado
const currentPage = ref(1)
const itemsPerPage = ref(6)
const totalItems = ref(30)

const filters = ref({
    search: '',
    modo: '',
    ruta: ''
})

// Distribución de modos de transporte
const distribucionModos = ref([
    { nombre: 'Marítimo', cantidad: 21, porcentaje: 56, color: '#3498db' },
    { nombre: 'Aéreo', cantidad: 12, porcentaje: 33, color: '#2ecc71' },
    { nombre: 'Terrestre', cantidad: 4, porcentaje: 11, color: '#f39c12' },
    { nombre: 'Multimodal', cantidad: 1, porcentaje: 2, color: '#9b59b6' }
])

const totalEnviosActivos = ref(38)

// Datos de operaciones basados en la imagen
const operaciones = ref([
    {
        id: 'OC-2024-021',
        cliente: 'María García',
        empresa: 'Textil SA',
        modo: 'Marítimo',
        ruta: 'Valencia-Shanghai',
        etd: '12 Ene',
        eta: '15 Feb',
        faseActual: 'En Transporte Marítimo'
    },
    {
        id: 'OC-2024-019',
        cliente: 'Pedro López',
        empresa: 'Moda Express',
        modo: 'Aéreo',
        ruta: 'Barcelona-Nueva York',
        etd: '9 Ene',
        eta: '11 Feb',
        faseActual: 'En Aduana'
    },
    {
        id: 'OC-2024-020',
        cliente: 'María García',
        empresa: 'Textil SA',
        modo: 'Aéreo',
        ruta: 'Madrid-Miami',
        etd: '5 Ene',
        eta: '5 Feb',
        faseActual: 'En Importación'
    },
    {
        id: 'OC-2024-023',
        cliente: 'Laura Gómez',
        empresa: 'Tech Impresiones',
        modo: 'Marítimo',
        ruta: 'Valencia-Quibai',
        etd: '13 Ene',
        eta: '28 Feb',
        faseActual: 'Carga a Bordo'
    },
    {
        id: 'OC-2024-024',
        cliente: 'Pedro López',
        empresa: 'Moda Express',
        modo: 'Terrestre',
        ruta: 'Madrid-París',
        etd: '13 Ene',
        eta: '15 Feb',
        faseActual: 'Transporte Interior'
    },
    {
        id: 'OC-2024-025',
        cliente: 'Sara Ruiz',
        empresa: 'Import Global',
        modo: 'Marítimo',
        ruta: 'Rotterdam-Barcelona',
        etd: '10 Ene',
        eta: '18 Feb',
        faseActual: 'Puerto Destino'
    },
    {
        id: 'OC-2024-026',
        cliente: 'María García',
        empresa: 'Textil SA',
        modo: 'Aéreo',
        ruta: 'Shanghai-Madrid',
        etd: '11 Ene',
        eta: '12 Feb',
        faseActual: 'Terminal Origen'
    },
    {
        id: 'OC-2024-027',
        cliente: 'Pedro López',
        empresa: 'Moda Express',
        modo: 'Multimodal',
        ruta: 'Valencia-Toledo',
        etd: '14 Ene',
        eta: '10 Feb',
        faseActual: 'Preparación'
    }
])

// Computed
const filteredOperaciones = computed(() => {
    let result = [...operaciones.value]

    if (filters.value.search) {
        const term = filters.value.search.toLowerCase()
        result = result.filter(o =>
            o.id.toLowerCase().includes(term) ||
            o.cliente.toLowerCase().includes(term) ||
            o.ruta.toLowerCase().includes(term)
        )
    }

    if (filters.value.modo) {
        result = result.filter(o => o.modo === filters.value.modo)
    }

    if (filters.value.ruta) {
        result = result.filter(o => o.ruta === filters.value.ruta)
    }

    return result
})

const totalPages = computed(() => Math.ceil(filteredOperaciones.value.length / itemsPerPage.value))

const paginatedOperaciones = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value
    return filteredOperaciones.value.slice(start, start + itemsPerPage.value)
})

const paginationInfo = computed(() => {
    const from = filteredOperaciones.value.length
    return { from }
})

const visiblePages = computed(() => {
    const maxVisible = 5
    const pages = []

    if (totalPages.value <= maxVisible) {
        for (let i = 1; i <= totalPages.value; i++) pages.push(i)
    } else {
        pages.push(1, 2, 3, '...', totalPages.value)
    }

    return pages
})

const goToPage = (page) => {
    if (page === '...') return
    currentPage.value = page
}
</script>

<style scoped>
.operaciones-container {
    display: flex;
    min-height: 100vh;
    background: #f5f5f5;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.sidebar {
    width: 260px;
    background: #2c3e50;
    color: white;
    padding: 20px 0;
}

.logo {
    padding: 0 20px 20px;
    border-bottom: 1px solid #34495e;
    margin-bottom: 20px;
}

.nav-section {
    margin-bottom: 20px;
}

.nav-title {
    padding: 10px 20px;
    font-size: 12px;
    text-transform: uppercase;
    color: #7f8c8d;
    letter-spacing: 1px;
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
    color: #ecf0f1;
    text-decoration: none;
    transition: all 0.3s;
    font-size: 14px;
}

.nav-menu li a:hover,
.nav-menu li a.active {
    background: #34495e;
    color: #3498db;
}

.main-content {
    flex: 1;
    padding: 20px 30px;
}

.header h1 {
    margin: 0 0 20px 0;
    color: #2c3e50;
    font-size: 24px;
}

.distribucion-section {
    background: white;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 25px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.distribucion-section h3 {
    margin: 0 0 5px 0;
    color: #2c3e50;
    font-size: 16px;
}

.subtitulo {
    color: #7f8c8d;
    font-size: 13px;
    margin: 0 0 15px 0;
}

.modos-grid {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.modo-item {
    width: 100%;
}

.modo-nombre {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 5px;
    font-size: 14px;
}

.modo-stats {
    display: flex;
    justify-content: space-between;
    margin-bottom: 5px;
    font-size: 12px;
}

.modo-cantidad {
    color: #7f8c8d;
}

.modo-porcentaje {
    color: #2c3e50;
    font-weight: 600;
}

.progress-bar {
    background: #ecf0f1;
    height: 8px;
    border-radius: 4px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    border-radius: 4px;
    transition: width 0.3s;
}

.operaciones-resumen {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    margin-bottom: 15px;
}

.operaciones-resumen h3 {
    margin: 0;
    color: #2c3e50;
    font-size: 18px;
}

.total-envios {
    color: #7f8c8d;
    font-size: 14px;
}

.filters-bar {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
}

.search-input {
    flex: 1;
    max-width: 300px;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.filter-select {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    background: white;
    cursor: pointer;
}

.table-wrapper {
    background: white;
    border-radius: 8px;
    overflow-x: auto;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.operaciones-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 1000px;
}

.operaciones-table th {
    background: #34495e;
    color: white;
    padding: 12px 15px;
    text-align: left;
    font-weight: 600;
    font-size: 13px;
}

.operaciones-table td {
    padding: 12px 15px;
    border-bottom: 1px solid #ecf0f1;
    font-size: 14px;
}

.operaciones-table tr:hover {
    background: #f8f9fa;
}

.id-cell {
    font-weight: 600;
    color: #3498db;
}

.modo-badge {
    background: #ecf0f1;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
}

.estado-transito-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    background: #fff3cd;
    color: #856404;
}

.pagination-info {
    text-align: center;
    margin-top: 15px;
    color: #7f8c8d;
    font-size: 13px;
}

.pagination {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-top: 15px;
}

.page-btn {
    padding: 8px 12px;
    border: 1px solid #ddd;
    background: white;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s;
}

.page-btn:hover:not(:disabled) {
    background: #ecf0f1;
}

.page-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.page-btn.active {
    background: #3498db;
    color: white;
    border-color: #3498db;
}
</style>
