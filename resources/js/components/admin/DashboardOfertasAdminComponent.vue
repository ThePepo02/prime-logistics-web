<template>
    <div class="ofertas-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">Admin</div>
            <nav class="nav-menu">
                <div class="nav-section">
                    <div class="nav-title">Información</div>
                    <ul>
                        <li><a href="#">Administradores</a></li>
                        <li><a href="#">Dashboard</a></li>
                        <li><a href="#">Gestión de Usuarios</a></li>
                    </ul>
                </div>
                <div class="nav-section">
                    <div class="nav-title">Comunicaciones</div>
                    <ul>
                        <li><a href="#" class="active">Todas las Ofertas</a></li>
                        <li><a href="#">Operaciones Activas</a></li>
                    </ul>
                </div>
            </nav>
        </div>

        <!-- Contenido principal -->
        <div class="main-content">
            <div class="header">
                <h1>TODAS LAS OFERTAS</h1>
                <div class="info-badge">Mostrando {{ paginationInfo.from }}-{{ paginationInfo.to }} de {{ totalItems }}
                    ofertas</div>
            </div>

            <!-- Filtros -->
            <div class="filters-bar">
                <input v-model="filters.search" type="text" placeholder="Buscar por ID, cliente, ruta..."
                    class="search-input" />
                <select v-model="filters.estado" class="filter-select">
                    <option value="">Todos los estados</option>
                    <option value="EN TRANSITO">En Tránsito</option>
                    <option value="ACEPTADA">Aceptada</option>
                    <option value="COMPLETADA">Completada</option>
                    <option value="RECHAZADA">Rechazada</option>
                </select>
            </div>

            <!-- Tabla -->
            <div class="table-wrapper">
                <table class="ofertas-table">
                    <thead>
                        <tr>
                            <th @click="sortBy('id')" class="sortable">
                                ID Oferta
                                <span class="sort-icon">{{ getSortIcon('id') }}</span>
                            </th>
                            <th @click="sortBy('cliente')" class="sortable">
                                Cliente
                                <span class="sort-icon">{{ getSortIcon('cliente') }}</span>
                            </th>
                            <th>Empresa</th>
                            <th>Modo</th>
                            <th>Ruta</th>
                            <th @click="sortBy('fecha')" class="sortable">
                                Fecha
                                <span class="sort-icon">{{ getSortIcon('fecha') }}</span>
                            </th>
                            <th>Estado</th>
                            <th>Acondicionado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="oferta in paginatedOfertas" :key="oferta.id">
                            <td class="id-cell">{{ oferta.id }}</td>
                            <td>{{ oferta.cliente }}</td>
                            <td>{{ oferta.empresa }}</td>
                            <td><span class="modo-badge">{{ oferta.modo }}</span></td>
                            <td>{{ oferta.ruta }}</td>
                            <td>{{ formatDate(oferta.fecha) }}</td>
                            <td>
                                <span :class="['estado-badge', getEstadoClass(oferta.estado)]">
                                    {{ oferta.estado }}
                                </span>
                            </td>
                            <td>
                                <button class="action-btn view-btn" @click="verAcondicionado(oferta)">
                                    Ver
                                </button>
                            </td>
                            <td>
                                <button class="action-btn edit-btn" @click="editarOferta(oferta)">
                                    Editar
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
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

// Estado
const currentPage = ref(1)
const itemsPerPage = ref(10)
const totalItems = ref(4200)
const sortField = ref('id')
const sortOrder = ref('asc')
const filters = ref({
    search: '',
    estado: ''
})

// Datos
const ofertas = ref([
    {
        id: 'OC-2024-021',
        cliente: 'María García',
        empresa: 'Testi SA',
        modo: 'Marlimo',
        ruta: 'Valencia → Shanghai',
        fecha: '2025-01-10',
        estado: 'EN TRANSITO'
    },
    {
        id: 'OC-2024-020',
        cliente: 'Pedro López',
        empresa: 'Moda Express SL',
        modo: 'Adeno',
        ruta: 'Barcelona → Nueva York',
        fecha: '2025-01-08',
        estado: 'ACEPTADA'
    },
    {
        id: 'OC-2024-019',
        cliente: 'María García',
        empresa: 'Testi SA',
        modo: 'Adeno',
        ruta: 'Madrid → Miami',
        fecha: '2025-01-02',
        estado: 'ACEPTADA'
    },
    {
        id: 'OC-2024-018',
        cliente: 'Sara Ruiz',
        empresa: 'Import Global',
        modo: 'Temiste',
        ruta: 'Madrid → Lisboa',
        fecha: '2024-12-26',
        estado: 'COMPLETADA'
    },
    {
        id: 'OC-2024-017',
        cliente: 'Pedro López',
        empresa: 'Moda Express SL',
        modo: 'Marlimo',
        ruta: 'Valencia → Rostredum',
        fecha: '2024-12-22',
        estado: 'COMPLETADA'
    },
    {
        id: 'OC-2024-016',
        cliente: 'Laura Gómez',
        empresa: 'Tech Imports SA',
        modo: 'Adeno',
        ruta: 'Bilbao → Chicago',
        fecha: '2024-12-16',
        estado: 'RECHAZADA'
    },
    {
        id: 'OC-2024-015',
        cliente: 'María García',
        empresa: 'Testi SA',
        modo: 'Marlimo',
        ruta: 'Bilbao → Rotterdam',
        fecha: '2024-12-15',
        estado: 'RECHAZADA'
    },
    {
        id: 'OC-2024-014',
        cliente: 'Sara Ruiz',
        empresa: 'Import Global',
        modo: 'Temiste',
        ruta: 'Barcelona → Lyon',
        fecha: '2024-12-10',
        estado: 'COMPLETADA'
    },
    {
        id: 'OC-2024-013',
        cliente: 'Pedro López',
        empresa: 'Moda Express SL',
        modo: 'Marlimo',
        ruta: 'Valencia → Singapore',
        fecha: '2024-12-05',
        estado: 'COMPLETADA'
    },
    {
        id: 'OC-2024-012',
        cliente: 'María García',
        empresa: 'Testi SA',
        modo: 'Adeno',
        ruta: 'Valencia → Nueva York',
        fecha: '2024-12-01',
        estado: 'COMPLETADA'
    }
])

// Computed
const filteredOfertas = computed(() => {
    let result = [...ofertas.value]

    // Búsqueda
    if (filters.value.search) {
        const term = filters.value.search.toLowerCase()
        result = result.filter(o =>
            o.id.toLowerCase().includes(term) ||
            o.cliente.toLowerCase().includes(term) ||
            o.ruta.toLowerCase().includes(term)
        )
    }

    // Filtro por estado
    if (filters.value.estado) {
        result = result.filter(o => o.estado === filters.value.estado)
    }

    // Ordenamiento
    result.sort((a, b) => {
        let aVal = a[sortField.value]
        let bVal = b[sortField.value]

        if (sortField.value === 'fecha') {
            aVal = new Date(aVal)
            bVal = new Date(bVal)
        }

        if (aVal < bVal) return sortOrder.value === 'asc' ? -1 : 1
        if (aVal > bVal) return sortOrder.value === 'asc' ? 1 : -1
        return 0
    })

    return result
})

const totalPages = computed(() => Math.ceil(totalItems.value / itemsPerPage.value))

const paginatedOfertas = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value
    return filteredOfertas.value.slice(start, start + itemsPerPage.value)
})

const paginationInfo = computed(() => {
    const from = (currentPage.value - 1) * itemsPerPage.value + 1
    const to = Math.min(from + itemsPerPage.value - 1, totalItems.value)
    return { from, to }
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

// Métodos
const formatDate = (dateStr) => {
    const date = new Date(dateStr)
    const months = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic']
    return `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`
}

const getEstadoClass = (estado) => {
    const classes = {
        'EN TRANSITO': 'estado-transito',
        'ACEPTADA': 'estado-aceptada',
        'COMPLETADA': 'estado-completada',
        'RECHAZADA': 'estado-rechazada'
    }
    return classes[estado] || ''
}

const getSortIcon = (field) => {
    if (sortField.value !== field) return '↕️'
    return sortOrder.value === 'asc' ? '↑' : '↓'
}

const sortBy = (field) => {
    if (sortField.value === field) {
        sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc'
    } else {
        sortField.value = field
        sortOrder.value = 'asc'
    }
}

const goToPage = (page) => {
    if (page === '...') return
    currentPage.value = page
}

const verAcondicionado = (oferta) => {
    console.log('Ver acondicionado:', oferta.id)
    alert(`Ver acondicionado de la oferta ${oferta.id}`)
}

const editarOferta = (oferta) => {
    console.log('Editar oferta:', oferta.id)
    alert(`Editar oferta ${oferta.id}`)
}
</script>

<style scoped>
/* Estilos similares al componente anterior pero con mejoras */
.ofertas-container {
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
    font-size: 24px;
    font-weight: bold;
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

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.header h1 {
    margin: 0;
    color: #2c3e50;
    font-size: 24px;
}

.info-badge {
    background: #ecf0f1;
    padding: 8px 15px;
    border-radius: 20px;
    font-size: 14px;
    color: #7f8c8d;
}

.filters-bar {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
}

.search-input {
    flex: 1;
    max-width: 400px;
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
}

.filter-select {
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
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

.ofertas-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 800px;
}

.ofertas-table th {
    background: #34495e;
    color: white;
    padding: 12px 15px;
    text-align: left;
    font-weight: 600;
    font-size: 13px;
}

.sortable {
    cursor: pointer;
    user-select: none;
}

.sortable:hover {
    background: #3d5a6b;
}

.sort-icon {
    margin-left: 5px;
    font-size: 12px;
}

.ofertas-table td {
    padding: 12px 15px;
    border-bottom: 1px solid #ecf0f1;
    font-size: 14px;
}

.ofertas-table tr:hover {
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

.estado-aceptada {
    background: #d4edda;
    color: #155724;
}

.estado-completada {
    background: #d1ecf1;
    color: #0c5460;
}

.estado-rechazada {
    background: #f8d7da;
    color: #721c24;
}

.action-btn {
    padding: 5px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px;
    transition: all 0.3s;
    margin: 0 2px;
}

.view-btn {
    background: #3498db;
    color: white;
}

.view-btn:hover {
    background: #2980b9;
}

.edit-btn {
    background: #f39c12;
    color: white;
}

.edit-btn:hover {
    background: #e67e22;
}

.pagination {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-top: 20px;
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