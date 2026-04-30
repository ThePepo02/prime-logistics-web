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

                        <!-- Operaciones -->
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
                            <h4 class="mb-0 text-dark">Datos Maestros</h4>
                            <small class="text-muted">Administración de entidades del sistema</small>
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

                    <!-- Mensaje de error -->
                    <div v-if="mensajeError" class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        {{ mensajeError }}
                        <button type="button" class="btn-close" @click="mensajeError = null"></button>
                    </div>

                    <!-- Resumen cards / KPIs -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm h-100 border-top border-primary">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="text-muted mb-2">Empresas Registradas</h6>
                                            <h2 class="mb-0">{{ formatNumber(resumen.total_empresas) }}</h2>
                                        </div>
                                        <div class="bg-primary bg-opacity-10 rounded p-2">
                                            <i class="bi bi-building text-primary fs-4"></i>
                                        </div>
                                    </div>
                                    <small class="text-muted mt-2 d-block">
                                        <i class="bi bi-arrow-up text-success"></i> +{{
                                            formatNumber(Math.floor(resumen.total_empresas * 0.12)) }} vs mes anterior
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm h-100 border-top border-success">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="text-muted mb-2">Navieras Activas</h6>
                                            <h2 class="mb-0">{{ formatNumber(resumen.total_navieras_activas) }}</h2>
                                        </div>
                                        <div class="bg-success bg-opacity-10 rounded p-2">
                                            <i class="bi bi-ship text-success fs-4"></i>
                                        </div>
                                    </div>
                                    <small class="text-muted mt-2 d-block">
                                        <i class="bi bi-arrow-up text-success"></i> +{{
                                            formatNumber(Math.floor(resumen.total_navieras_activas * 0.08)) }} vs mes
                                        anterior
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm h-100 border-top border-info">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="text-muted mb-2">Puertos/Aeropuertos</h6>
                                            <h2 class="mb-0">{{ formatNumber(resumen.total_puertos_activos) }}</h2>
                                        </div>
                                        <div class="bg-info bg-opacity-10 rounded p-2">
                                            <i class="bi bi-geo-alt-fill text-info fs-4"></i>
                                        </div>
                                    </div>
                                    <small class="text-muted mt-2 d-block">
                                        <i class="bi bi-arrow-up text-success"></i> +{{
                                            formatNumber(Math.floor(resumen.total_puertos_activos * 0.05)) }} vs mes
                                        anterior
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm h-100 border-top border-warning">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="text-muted mb-2">Incoterms Activos</h6>
                                            <h2 class="mb-0">{{ formatNumber(resumen.total_incoterms) }}</h2>
                                        </div>
                                        <div class="bg-warning bg-opacity-10 rounded p-2">
                                            <i class="bi bi-tags-fill text-warning fs-4"></i>
                                        </div>
                                    </div>
                                    <small class="text-muted mt-2 d-block">
                                        <i class="bi bi-arrow-up text-success"></i> +{{
                                            formatNumber(Math.floor(resumen.total_incoterms * 0.1)) }} vs mes anterior
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Empresas -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div
                            class="card-header bg-white border-0 pt-4 pb-0 d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <h5 class="mb-0">Empresas</h5>
                                <small class="text-muted">{{ empresas.length }} empresas registradas</small>
                            </div>
                            <button @click="mostrarModalEmpresa = true" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-lg me-1"></i> Agregar Empresa
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div v-for="empresa in empresas" :key="empresa.id" class="col-md-6 col-lg-4">
                                    <div class="card h-100 border rounded-3 shadow-sm">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">{{ empresa.nombre }}</h6>
                                                    <p class="small text-muted mb-2">{{ empresa.email }}</p>
                                                    <div class="d-flex gap-3 small">
                                                        <span class="text-primary">
                                                            <i class="bi bi-people-fill"></i> {{ empresa.total_usuarios
                                                            || 0 }} usuarios
                                                        </span>
                                                        <span class="text-success">
                                                            <i class="bi bi-cart-fill"></i> {{ empresa.total_pedidos ||
                                                            0 }} pedidos
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="btn-group btn-group-sm">
                                                    <button @click="editarEmpresa(empresa)"
                                                        class="btn btn-outline-warning" title="Editar">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button @click="eliminarEmpresa(empresa.id)"
                                                        class="btn btn-outline-danger" title="Eliminar">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navieras & Carriers -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div
                            class="card-header bg-white border-0 pt-4 pb-0 d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <h5 class="mb-0">Navieras & Carriers</h5>
                                <small class="text-muted">{{ navieras.length }} carriers activas</small>
                            </div>
                            <button @click="mostrarModalNaviera = true" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-lg me-1"></i> Agregar Naviera
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div v-for="naviera in navieras" :key="naviera.id" class="col-md-6 col-lg-4">
                                    <div class="card h-100 border rounded-3 shadow-sm">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">{{ naviera.nombre }}</h6>
                                                    <p class="small mb-2">
                                                        <span :class="getModoClass(naviera.tipo)" class="badge">
                                                            {{ getTipoTexto(naviera.tipo) }}
                                                        </span>
                                                    </p>
                                                    <div class="small text-muted">
                                                        <i class="bi bi-truck"></i> {{ naviera.total_operaciones || 0 }}
                                                        operaciones
                                                    </div>
                                                </div>
                                                <div class="btn-group btn-group-sm">
                                                    <button @click="editarNaviera(naviera)"
                                                        class="btn btn-outline-warning" title="Editar">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button @click="eliminarNaviera(naviera.id)"
                                                        class="btn btn-outline-danger" title="Eliminar">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Puertos / Aeropuertos -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div
                            class="card-header bg-white border-0 pt-4 pb-0 d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <h5 class="mb-0">Puertos / Aeropuertos</h5>
                                <small class="text-muted">{{ puertosAeropuertos.length }} orígenes activos</small>
                            </div>
                            <button @click="mostrarModalPuerto = true" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-lg me-1"></i> Agregar Ubicación
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div v-for="lugar in puertosAeropuertos" :key="lugar.id" class="col-md-6 col-lg-4">
                                    <div class="card h-100 border rounded-3 shadow-sm">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">{{ lugar.nombre }}</h6>
                                                    <p class="small text-muted mb-1">Código: {{ lugar.codigo_iata }}</p>
                                                    <p class="small mb-2">
                                                        <span :class="getPuertoClass(lugar.tipo)" class="badge">
                                                            {{ getTipoPuertoTexto(lugar.tipo) }}
                                                        </span>
                                                    </p>
                                                    <div class="small text-success">
                                                        <i class="bi bi-geo-alt-fill"></i> {{ lugar.ciudad }}, {{
                                                        lugar.pais }}
                                                    </div>
                                                    <div class="small text-muted mt-1">
                                                        <i class="bi bi-truck"></i> {{ lugar.total_operaciones || 0 }}
                                                        operaciones
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Agentes Aduanales -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div
                            class="card-header bg-white border-0 pt-4 pb-0 d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <h5 class="mb-0">Agentes Aduanales</h5>
                                <small class="text-muted">{{ agentesAduanales.length }} agentes activos</small>
                            </div>
                            <button @click="mostrarModalAgente = true" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-lg me-1"></i> Agregar Agente
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div v-for="agente in agentesAduanales" :key="agente.id" class="col-md-6 col-lg-4">
                                    <div class="card h-100 border rounded-3 shadow-sm">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1">{{ agente.nombre }}</h6>
                                                    <p class="small text-muted mb-1">
                                                        <i class="bi bi-geo-alt-fill"></i> {{ agente.ubicacion }}
                                                    </p>
                                                    <div class="small text-info">
                                                        <i class="bi bi-file-text-fill"></i> {{ agente.total_expedientes
                                                        || 0 }} expedientes
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Incoterms Activos -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div
                            class="card-header bg-white border-0 pt-4 pb-0 d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <h5 class="mb-0">Incoterms Activos</h5>
                                <small class="text-muted">{{ incoterms.length }} configuraciones</small>
                            </div>
                            <button @click="mostrarModalIncoterm = true" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-lg me-1"></i> Agregar Incoterm
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div v-for="incoterm in incoterms" :key="incoterm.id" class="col-md-3 col-lg-2">
                                    <div class="card text-center border rounded-3 shadow-sm incoterm-card">
                                        <div class="card-body p-3">
                                            <div class="incoterm-code fw-bold text-primary mb-1">{{ incoterm.codigo }}
                                            </div>
                                            <small class="text-muted incoterm-desc">{{
                                                truncateText(incoterm.nombre_completo, 50) }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer del contenido -->
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
                                <h6 class="text-muted mb-3">Operaciones</h6>
                                <ul class="list-unstyled small">
                                    <li><a href="#" class="text-decoration-none text-muted">Todas las Ofertas</a></li>
                                    <li class="mt-2"><a href="#" class="text-decoration-none text-muted">Operaciones
                                            Activas</a></li>
                                    <li class="mt-2"><a href="#"
                                            class="text-decoration-none text-muted">Estadísticas</a></li>
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <h6 class="text-muted mb-3">Datos Maestros</h6>
                                <ul class="list-unstyled small">
                                    <li><a href="#" class="text-decoration-none text-muted">Empresas</a></li>
                                    <li class="mt-2"><a href="#" class="text-decoration-none text-muted">Navieras</a>
                                    </li>
                                    <li class="mt-2"><a href="#" class="text-decoration-none text-muted">Puertos</a>
                                    </li>
                                    <li class="mt-2"><a href="#" class="text-decoration-none text-muted">Incoterms</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <h6 class="text-muted mb-3">Sistema</h6>
                                <ul class="list-unstyled small">
                                    <li><a href="#" class="text-decoration-none text-muted">Configuración</a></li>
                                    <li class="mt-2"><a href="#" class="text-decoration-none text-muted">Respaldo</a>
                                    </li>
                                    <li class="mt-2"><a href="#" class="text-decoration-none text-muted">Logs</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
        </div>

        <!-- Modal Empresa -->
        <div v-if="mostrarModalEmpresa" class="modal fade show d-block" tabindex="-1"
            style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ editando ? 'Editar' : 'Nueva' }} Empresa</h5>
                        <button type="button" class="btn-close" @click="cerrarModalEmpresa"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input v-model="empresaForm.nombre" type="text" class="form-control"
                                placeholder="Nombre de la empresa">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input v-model="empresaForm.email" type="email" class="form-control"
                                placeholder="correo@empresa.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">CIF</label>
                            <input v-model="empresaForm.cif" type="text" class="form-control" placeholder="CIF/NIF">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModalEmpresa">Cancelar</button>
                        <button type="button" class="btn btn-primary" @click="guardarEmpresa">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'

const logoPrimeLogistics = '/images/logo-empresa.png'

// Estado de navegación
const activeSection = ref('datos-maestros')

// Estado
const cargando = ref(false)
const mensajeError = ref(null)

// Datos
const empresas = ref([])
const navieras = ref([])
const puertosAeropuertos = ref([])
const agentesAduanales = ref([])
const incoterms = ref([])
const resumen = ref({})

// Modales
const mostrarModalEmpresa = ref(false)
const mostrarModalNaviera = ref(false)
const mostrarModalPuerto = ref(false)
const mostrarModalAgente = ref(false)
const mostrarModalIncoterm = ref(false)
const editando = ref(false)

// Formularios
const empresaForm = ref({ nombre: '', email: '', cif: '' })

const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api/v1'

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

// Utilidades
const formatNumber = (num) => {
    return num?.toLocaleString('es-ES') ?? '0'
}

const truncateText = (text, length) => {
    if (!text) return ''
    return text.length > length ? text.substring(0, length) + '...' : text
}

const getTipoTexto = (tipo) => {
    const tipos = {
        'MARITIMO': 'Marítimo',
        'AEREO': 'Aéreo',
        'TERRESTRE': 'Terrestre',
        'MULTIMODAL': 'Multimodal'
    }
    return tipos[tipo] || tipo
}

const getModoClass = (tipo) => {
    const classes = {
        'MARITIMO': 'bg-primary',
        'AEREO': 'bg-info text-dark',
        'TERRESTRE': 'bg-success',
        'MULTIMODAL': 'bg-warning text-dark'
    }
    return classes[tipo] || 'bg-secondary'
}

const getPuertoClass = (tipo) => {
    const classes = {
        'PUERTO': 'bg-primary',
        'AEROPUERTO': 'bg-info text-dark',
        'AMBOS': 'bg-success'
    }
    return classes[tipo] || 'bg-secondary'
}

const getTipoPuertoTexto = (tipo) => {
    const tipos = {
        'PUERTO': 'Puerto marítimo',
        'AEROPUERTO': 'Aeropuerto',
        'AMBOS': 'Puerto + Aeropuerto'
    }
    return tipos[tipo] || tipo
}

// Cargar datos
const cargarDatos = async () => {
    cargando.value = true
    mensajeError.value = null

    try {
        const response = await fetch(`${API_URL}/datos-maestros/dashboard`)
        const result = await response.json()

        if (result.success) {
            empresas.value = result.data.empresas
            navieras.value = result.data.navieras
            puertosAeropuertos.value = result.data.puertos_aeropuertos
            agentesAduanales.value = result.data.agentes_aduanales
            incoterms.value = result.data.incoterms
            resumen.value = result.data.resumen
        } else {
            throw new Error(result.message)
        }
    } catch (error) {
        console.error('Error:', error)
        mensajeError.value = 'Error al cargar datos maestros'
        // Datos de ejemplo
        cargarDatosEjemplo()
    } finally {
        cargando.value = false
    }
}

// Datos de ejemplo para desarrollo
const cargarDatosEjemplo = () => {
    empresas.value = [
        { id: 1, nombre: 'Logistics Corp', email: 'contacto@logisticscorp.com', cif: 'B12345678', total_usuarios: 15, total_pedidos: 234 },
        { id: 2, nombre: 'Fast Ship', email: 'info@fastship.com', cif: 'B87654321', total_usuarios: 8, total_pedidos: 89 }
    ]
    navieras.value = [
        { id: 1, nombre: 'Maersk Line', tipo: 'MARITIMO', total_operaciones: 234 },
        { id: 2, nombre: 'DHL Aviation', tipo: 'AEREO', total_operaciones: 567 }
    ]
    puertosAeropuertos.value = [
        { id: 1, nombre: 'Puerto de Valencia', codigo_iata: 'VLC', tipo: 'PUERTO', ciudad: 'Valencia', pais: 'España', total_operaciones: 123 },
        { id: 2, nombre: 'Aeropuerto de Madrid', codigo_iata: 'MAD', tipo: 'AEROPUERTO', ciudad: 'Madrid', pais: 'España', total_operaciones: 456 }
    ]
    agentesAduanales.value = [
        { id: 1, nombre: 'Agentes Aduanales SL', ubicacion: 'Barcelona', total_expedientes: 234 },
        { id: 2, nombre: 'Customs Experts', ubicacion: 'Valencia', total_expedientes: 123 }
    ]
    incoterms.value = [
        { id: 1, codigo: 'FOB', nombre_completo: 'Free On Board' },
        { id: 2, codigo: 'CIF', nombre_completo: 'Cost, Insurance and Freight' },
        { id: 3, codigo: 'EXW', nombre_completo: 'Ex Works' },
        { id: 4, codigo: 'DAP', nombre_completo: 'Delivered At Place' }
    ]
    resumen.value = {
        total_empresas: empresas.value.length,
        total_navieras_activas: navieras.value.length,
        total_puertos_activos: puertosAeropuertos.value.length,
        total_incoterms: incoterms.value.length
    }
}

// Empresas CRUD
const guardarEmpresa = async () => {
    try {
        const method = editando.value ? 'PUT' : 'POST'
        const url = editando.value
            ? `${API_URL}/empresas/${empresaForm.value.id}`
            : `${API_URL}/empresas`

        await fetch(url, {
            method,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(empresaForm.value)
        })

        await cargarDatos()
        cerrarModalEmpresa()
    } catch (error) {
        console.error('Error guardando empresa:', error)
    }
}

const eliminarEmpresa = async (id) => {
    if (!confirm('¿Eliminar esta empresa?')) return

    try {
        await fetch(`${API_URL}/empresas/${id}`, { method: 'DELETE' })
        await cargarDatos()
    } catch (error) {
        console.error('Error eliminando empresa:', error)
    }
}

const editarEmpresa = (empresa) => {
    empresaForm.value = { ...empresa }
    editando.value = true
    mostrarModalEmpresa.value = true
}

const cerrarModalEmpresa = () => {
    mostrarModalEmpresa.value = false
    editando.value = false
    empresaForm.value = { nombre: '', email: '', cif: '' }
}

const eliminarNaviera = async (id) => {
    if (!confirm('¿Eliminar esta naviera?')) return
    try {
        await fetch(`${API_URL}/navieras/${id}`, { method: 'DELETE' })
        await cargarDatos()
    } catch (error) {
        console.error('Error:', error)
    }
}

const editarNaviera = (naviera) => {
    console.log('Editar naviera:', naviera)
    alert(`Editar naviera: ${naviera.nombre}`)
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

// Lifecycle
onMounted(() => {
    cargarDatos()
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

.incoterm-card {
    transition: all 0.3s;
    cursor: pointer;
}

.incoterm-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
}

.incoterm-card:hover .incoterm-code {
    color: #0d6efd !important;
}

.btn-group .btn {
    transition: all 0.2s;
}

.btn-group .btn:hover {
    transform: scale(1.05);
}

/* Animaciones para las cards */
.card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1) !important;
}

/* Modal overlay */
.modal.fade.show {
    background: rgba(0, 0, 0, 0.5);
    z-index: 1050;
}
</style>