<template>
    <div class="app-layout">
        <!-- Menú lateral izquierdo (idéntico a la imagen) -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>Datos Maestros</h2>
                <span class="admin-badge">admin</span>
            </div>

            <nav class="sidebar-nav">
                <!-- Inicio -->
                <div class="nav-item">
                    <span class="nav-icon">🏠</span>
                    <span>Inicio</span>
                </div>

                <!-- Administración (desplegable) -->
                <div class="nav-group">
                    <div class="nav-group-header" @click="toggleAdminMenu">
                        <span class="nav-icon">⚙️</span>
                        <span>Administración</span>
                        <span class="chevron">{{ adminOpen ? '▼' : '▶' }}</span>
                    </div>
                    <div v-if="adminOpen" class="nav-group-content">
                        <!-- Ventas (subgrupo) -->
                        <div class="nav-subgroup">
                            <div class="nav-subgroup-header" @click="toggleVentasMenu">
                                <span>📊 Ventas</span>
                                <span class="chevron">{{ ventasOpen ? '▼' : '▶' }}</span>
                            </div>
                            <div v-if="ventasOpen" class="nav-subgroup-content">
                                <div class="nav-subitem">Ventas de productos</div>
                                <div class="nav-subitem">Ventas de servicios</div>
                                <div class="nav-subitem">Ventas de empleo</div>
                                <div class="nav-subitem">Ventas de proyectos</div>
                                <div class="nav-subitem">Ventas de marketing</div>
                                <div class="nav-subitem">Ventas de personal</div>
                                <div class="nav-subitem">Ventas de ventas a clientes</div>
                            </div>
                        </div>

                        <!-- Productos (subgrupo) -->
                        <div class="nav-subgroup">
                            <div class="nav-subgroup-header" @click="toggleProductosMenu">
                                <span>📦 Productos</span>
                                <span class="chevron">{{ productosOpen ? '▼' : '▶' }}</span>
                            </div>
                            <div v-if="productosOpen" class="nav-subgroup-content">
                                <div class="nav-subitem">Productos de producto</div>
                                <div class="nav-subitem">Productos de servicio</div>
                                <div class="nav-subitem">Productos de empleo</div>
                                <div class="nav-subitem">Productos de proyectos</div>
                                <div class="nav-subitem">Productos de marketing</div>
                                <div class="nav-subitem">Productos de personal</div>
                                <div class="nav-subitem">Productos de ventas a clientes</div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </aside>

        <!-- Contenido principal -->
        <main class="main-content">
            <!-- Header con título y botón actualizar -->
            <div class="content-header">
                <h1>Datos Maestros Admin</h1>
                <button @click="cargarDatos" class="btn-refresh" :disabled="cargando">
                    {{ cargando ? 'Cargando...' : '⟳ Actualizar' }}
                </button>
            </div>

            <!-- Mensaje de error -->
            <div v-if="mensajeError" class="alert error">
                {{ mensajeError }}
                <button @click="mensajeError = null">×</button>
            </div>

            <!-- ÁREAS ADMINISTRATIVAS (exactamente como en la imagen) -->
            <div class="areas-section">
                <h2>Áreas administrativas</h2>
                <div class="areas-grid">
                    <div class="area-card" v-for="area in areasAdministrativas" :key="area">
                        {{ area }}
                    </div>
                </div>
            </div>

            <!-- CONTENIDO DINÁMICO (manteniendo funcionalidad original) -->
            <div class="datos-section">
                <div class="datos-group">
                    <h3>📋 Empresas</h3>
                    <button @click="mostrarModalEmpresa = true" class="btn-small">+ Agregar</button>
                    <div class="datos-lista">
                        <div v-for="emp in empresas" :key="emp.id" class="dato-item">
                            <strong>{{ emp.nombre }}</strong> – {{ emp.email }}
                            <button @click="eliminarEmpresa(emp.id)" class="btn-icon-sm">🗑️</button>
                        </div>
                    </div>
                </div>

                <div class="datos-group">
                    <h3>🚢 Navieras & Carriers</h3>
                    <button @click="mostrarModalNaviera = true" class="btn-small">+ Agregar</button>
                    <div class="datos-lista">
                        <div v-for="nav in navieras" :key="nav.id" class="dato-item">
                            {{ nav.nombre }} ({{ nav.total_operaciones || 0 }} ops)
                        </div>
                    </div>
                </div>

                <div class="datos-group">
                    <h3>⚓ Puertos / Aeropuertos</h3>
                    <button @click="mostrarModalPuerto = true" class="btn-small">+ Agregar</button>
                    <div class="datos-lista">
                        <div v-for="p in puertosAeropuertos" :key="p.id" class="dato-item">
                            {{ p.nombre }} ({{ p.codigo_iata }}) – {{ p.ciudad }}
                        </div>
                    </div>
                </div>

                <div class="datos-group">
                    <h3>📦 Agentes Aduanales</h3>
                    <button @click="mostrarModalAgente = true" class="btn-small">+ Agregar</button>
                    <div class="datos-lista">
                        <div v-for="a in agentesAduanales" :key="a.id" class="dato-item">
                            {{ a.nombre }} – {{ a.ubicacion }}
                        </div>
                    </div>
                </div>

                <div class="datos-group">
                    <h3>📜 Incoterms Activos</h3>
                    <button @click="mostrarModalIncoterm = true" class="btn-small">+ Agregar</button>
                    <div class="datos-lista incoterms-lista">
                        <span v-for="inc in incoterms" :key="inc.id" class="incoterm-badge">
                            {{ inc.codigo }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- PIE DE PÁGINA (idéntico a la imagen) -->
            <footer class="footer">
                <div class="footer-links">
                    <span>Pie de página</span>
                    <span>Información adicional</span>
                    <span>Noticias</span>
                    <span>Otras noticias</span>
                    <span>Más información</span>
                </div>
                <div class="footer-copy">
                    Datos Maestros - Sistema de Gestión
                </div>
            </footer>
        </main>

        <!-- Modales (igual que antes, simplificados) -->
        <div v-if="mostrarModalEmpresa" class="modal">
            <div class="modal-content">
                <h3>Nueva Empresa</h3>
                <input v-model="empresaForm.nombre" placeholder="Nombre" class="modal-input">
                <input v-model="empresaForm.email" placeholder="Email" class="modal-input">
                <button @click="guardarEmpresa" class="btn-guardar">Guardar</button>
                <button @click="mostrarModalEmpresa = false" class="btn-cancelar">Cancelar</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

// Estado general
const cargando = ref(false)
const mensajeError = ref(null)

// Datos maestros (igual que original)
const empresas = ref([])
const navieras = ref([])
const puertosAeropuertos = ref([])
const agentesAduanales = ref([])
const incoterms = ref([])

// Estado del menú lateral
const adminOpen = ref(true)
const ventasOpen = ref(false)
const productosOpen = ref(false)

// Áreas administrativas (según imagen)
const areasAdministrativas = ref([
    'Ventas', 'Productos', 'Empleo', 'Proyectos', 'Marketing', 'Personal', 'Ventas a clientes'
])

// Modales
const mostrarModalEmpresa = ref(false)
const mostrarModalNaviera = ref(false)
const mostrarModalPuerto = ref(false)
const mostrarModalAgente = ref(false)
const mostrarModalIncoterm = ref(false)

// Form empresa
const empresaForm = ref({ nombre: '', email: '', cif: '' })

const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api/v1'

// Funciones del menú lateral
const toggleAdminMenu = () => { adminOpen.value = !adminOpen.value }
const toggleVentasMenu = () => { ventasOpen.value = !ventasOpen.value }
const toggleProductosMenu = () => { productosOpen.value = !productosOpen.value }

// Cargar datos de la API
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
        } else {
            throw new Error(result.message)
        }
    } catch (error) {
        console.error(error)
        mensajeError.value = 'Error al cargar datos maestros'
    } finally {
        cargando.value = false
    }
}

// CRUD empresas (simplificado)
const guardarEmpresa = async () => {
    try {
        await fetch(`${API_URL}/empresas`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(empresaForm.value)
        })
        await cargarDatos()
        mostrarModalEmpresa.value = false
        empresaForm.value = { nombre: '', email: '', cif: '' }
    } catch (error) {
        console.error(error)
    }
}

const eliminarEmpresa = async (id) => {
    if (!confirm('¿Eliminar empresa?')) return
    try {
        await fetch(`${API_URL}/empresas/${id}`, { method: 'DELETE' })
        await cargarDatos()
    } catch (error) {
        console.error(error)
    }
}

onMounted(() => {
    cargarDatos()
})
</script>

<style scoped>
/* LAYOUT PRINCIPAL: menú lateral + contenido */
.app-layout {
    display: flex;
    min-height: 100vh;
    background: #f0f2f5;
    font-family: 'Segoe UI', system-ui, sans-serif;
}

/* ========== SIDEBAR (idéntica a imagen) ========== */
.sidebar {
    width: 280px;
    background: #1e293b;
    color: #e2e8f0;
    display: flex;
    flex-direction: column;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
}

.sidebar-header {
    padding: 24px 20px;
    border-bottom: 1px solid #334155;
    text-align: center;
}

.sidebar-header h2 {
    margin: 0;
    font-size: 20px;
    color: white;
}

.admin-badge {
    display: inline-block;
    background: #3b82f6;
    font-size: 10px;
    padding: 2px 8px;
    border-radius: 20px;
    margin-top: 6px;
    color: white;
}

.sidebar-nav {
    flex: 1;
    padding: 20px 0;
}

.nav-item {
    padding: 10px 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    transition: background 0.2s;
}

.nav-item:hover {
    background: #334155;
}

.nav-group {
    margin-top: 8px;
}

.nav-group-header {
    padding: 10px 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    font-weight: 500;
}

.nav-group-header:hover {
    background: #334155;
}

.nav-group-content {
    padding-left: 20px;
}

.nav-subgroup-header {
    padding: 8px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    font-size: 14px;
    color: #cbd5e1;
}

.nav-subgroup-header:hover {
    background: #334155;
}

.nav-subgroup-content {
    padding-left: 35px;
}

.nav-subitem {
    padding: 6px 20px;
    font-size: 13px;
    color: #94a3b8;
    cursor: pointer;
}

.nav-subitem:hover {
    color: white;
    background: #334155;
}

.chevron {
    font-size: 10px;
    margin-left: auto;
}

/* ========== MAIN CONTENT ========== */
.main-content {
    flex: 1;
    overflow-y: auto;
    padding: 24px 32px;
    background: #f8fafc;
}

.content-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.content-header h1 {
    margin: 0;
    font-size: 28px;
    color: #0f172a;
}

.btn-refresh {
    background: #10b981;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    color: white;
    cursor: pointer;
    font-weight: 500;
}

/* Áreas administrativas (grid exacto de la imagen) */
.areas-section {
    background: white;
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 32px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.areas-section h2 {
    font-size: 18px;
    margin-top: 0;
    margin-bottom: 16px;
    color: #1e293b;
}

.areas-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 12px;
}

.area-card {
    background: #f1f5f9;
    padding: 12px;
    border-radius: 12px;
    text-align: center;
    font-weight: 500;
    color: #0f172a;
    transition: all 0.2s;
}

.area-card:hover {
    background: #e2e8f0;
    transform: translateY(-2px);
}

/* Datos dinámicos (empresas, navieras, etc) */
.datos-section {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 24px;
    margin-bottom: 40px;
}

.datos-group {
    background: white;
    border-radius: 16px;
    padding: 18px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.datos-group h3 {
    margin: 0 0 12px 0;
    font-size: 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.btn-small {
    background: #3b82f6;
    border: none;
    color: white;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    cursor: pointer;
}

.datos-lista {
    max-height: 200px;
    overflow-y: auto;
}

.dato-item {
    padding: 8px 0;
    border-bottom: 1px solid #e2e8f0;
    font-size: 13px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.btn-icon-sm {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 16px;
    opacity: 0.6;
}

.incoterms-lista {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.incoterm-badge {
    background: #e0e7ff;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
    color: #1e40af;
}

/* Footer exacto de la imagen */
.footer {
    margin-top: 32px;
    padding-top: 24px;
    border-top: 1px solid #cbd5e1;
    text-align: center;
}

.footer-links {
    display: flex;
    justify-content: center;
    gap: 32px;
    flex-wrap: wrap;
    margin-bottom: 16px;
    font-size: 13px;
    color: #475569;
}

.footer-links span {
    cursor: default;
}

.footer-copy {
    font-size: 12px;
    color: #94a3b8;
}

/* Modales (igual que antes) */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-content {
    background: white;
    padding: 24px;
    border-radius: 16px;
    width: 400px;
}

.modal-input {
    width: 100%;
    padding: 8px;
    margin: 8px 0;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
}

.btn-guardar,
.btn-cancelar {
    padding: 6px 12px;
    margin: 8px 4px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}

.btn-guardar {
    background: #10b981;
    color: white;
}

.btn-cancelar {
    background: #e2e8f0;
}

.alert {
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.alert.error {
    background: #fee2e2;
    color: #b91c1c;
}
</style>