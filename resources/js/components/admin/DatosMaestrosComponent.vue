<template>
    <div class="datos-maestros-container">
        <div class="main-content">
            <div class="header">
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

            <!-- Resumen cards -->
            <div class="resumen-grid">
                <div class="resumen-card">
                    <div class="resumen-numero">{{ resumen.total_empresas || 0 }}</div>
                    <div class="resumen-label">Empresas Registradas</div>
                </div>
                <div class="resumen-card">
                    <div class="resumen-numero">{{ resumen.total_navieras_activas || 0 }}</div>
                    <div class="resumen-label">Navieras Activas</div>
                </div>
                <div class="resumen-card">
                    <div class="resumen-numero">{{ resumen.total_puertos_activos || 0 }}</div>
                    <div class="resumen-label">Puertos/Aeropuertos</div>
                </div>
                <div class="resumen-card">
                    <div class="resumen-numero">{{ resumen.total_incoterms || 0 }}</div>
                    <div class="resumen-label">Incoterms Activos</div>
                </div>
            </div>

            <!-- Empresas -->
            <div class="seccion">
                <div class="seccion-header">
                    <h2>Empresas</h2>
                    <button @click="mostrarModalEmpresa = true" class="btn-agregar">+ Agregar Empresa</button>
                </div>
                <div class="badge">{{ empresas.length }} empresas registradas</div>
                <div class="lista-empresas">
                    <div v-for="empresa in empresas" :key="empresa.id" class="empresa-card">
                        <div class="empresa-info">
                            <div class="empresa-nombre">{{ empresa.nombre }}</div>
                            <div class="empresa-stats">{{ empresa.total_usuarios }} usuarios - {{ empresa.total_pedidos }} pedidos</div>
                            <div class="empresa-contacto">{{ empresa.email }}</div>
                        </div>
                        <div class="acciones">
                            <button @click="editarEmpresa(empresa)" class="btn-icon" title="Editar">✏️</button>
                            <button @click="eliminarEmpresa(empresa.id)" class="btn-icon" title="Eliminar">🗑️</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navieras & Carriers -->
            <div class="seccion">
                <div class="seccion-header">
                    <h2>Navieras & Carriers</h2>
                    <button @click="mostrarModalNaviera = true" class="btn-agregar">+ Agregar Naviera</button>
                </div>
                <div class="badge">{{ navieras.length }} carriers activas</div>
                <div class="navieras-grid">
                    <div v-for="naviera in navieras" :key="naviera.id" class="naviera-card">
                        <div class="naviera-info">
                            <div class="naviera-nombre">{{ naviera.nombre }}</div>
                            <div class="naviera-tipo">{{ getTipoTexto(naviera.tipo) }} - {{ naviera.total_operaciones }} operaciones</div>
                        </div>
                        <div class="acciones">
                            <button @click="editarNaviera(naviera)" class="btn-icon">✏️</button>
                            <button @click="eliminarNaviera(naviera.id)" class="btn-icon">🗑️</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Puertos / Aeropuertos -->
            <div class="seccion">
                <div class="seccion-header">
                    <h2>Puertos / Aeropuertos</h2>
                    <button @click="mostrarModalPuerto = true" class="btn-agregar">+ Agregar Ubicación</button>
                </div>
                <div class="badge">{{ puertosAeropuertos.length }} orígenes activos</div>
                <div class="puertos-grid">
                    <div v-for="lugar in puertosAeropuertos" :key="lugar.id" class="puerto-card">
                        <div class="puerto-nombre">{{ lugar.nombre }} ({{ lugar.codigo_iata }})</div>
                        <div class="puerto-tipo">{{ getTipoPuertoTexto(lugar.tipo) }} - {{ lugar.total_operaciones }} operaciones</div>
                        <div class="puerto-ubicacion">{{ lugar.ciudad }}, {{ lugar.pais }}</div>
                    </div>
                </div>
            </div>

            <!-- Agentes Aduanales -->
            <div class="seccion">
                <div class="seccion-header">
                    <h2>Agentes Aduanales</h2>
                    <button @click="mostrarModalAgente = true" class="btn-agregar">+ Agregar Agente</button>
                </div>
                <div class="badge">{{ agentesAduanales.length }} agentes activos</div>
                <div class="agentes-grid">
                    <div v-for="agente in agentesAduanales" :key="agente.id" class="agente-card">
                        <div class="agente-nombre">{{ agente.nombre }}</div>
                        <div class="agente-detalle">{{ agente.ubicacion }} - {{ agente.total_expedientes }} expedientes</div>
                    </div>
                </div>
            </div>

            <!-- Incoterms Activos -->
            <div class="seccion">
                <div class="seccion-header">
                    <h2>Incoterms Activos</h2>
                    <button @click="mostrarModalIncoterm = true" class="btn-agregar">+ Agregar Incoterm</button>
                </div>
                <div class="badge">{{ incoterms.length }} configuraciones</div>
                <div class="incoterms-grid">
                    <div v-for="incoterm in incoterms" :key="incoterm.id" class="incoterm-card">
                        <div class="incoterm-code">{{ incoterm.codigo }}</div>
                        <div class="incoterm-desc">{{ incoterm.nombre_completo }}</div>
                    </div>
                </div>
            </div>

            <footer class="dashboard-footer">
                <span>Datos Maestros - Sistema de Gestión</span>
            </footer>
        </div>

        <!-- Modales (simplificados, puedes expandir) -->
        <div v-if="mostrarModalEmpresa" class="modal">
            <div class="modal-content">
                <h3>{{ editando ? 'Editar' : 'Nueva' }} Empresa</h3>
                <input v-model="empresaForm.nombre" placeholder="Nombre" class="modal-input">
                <input v-model="empresaForm.email" placeholder="Email" class="modal-input">
                <input v-model="empresaForm.cif" placeholder="CIF" class="modal-input">
                <div class="modal-acciones">
                    <button @click="guardarEmpresa" class="btn-guardar">Guardar</button>
                    <button @click="cerrarModalEmpresa" class="btn-cancelar">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

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
    } finally {
        cargando.value = false
    }
}

// Empresas CRUD
const guardarEmpresa = async () => {
    try {
        const method = editando.value ? 'PUT' : 'POST'
        const url = editando.value 
            ? `${API_URL}/empresas/${empresaForm.value.id}`
            : `${API_URL}/empresas`
        
        const response = await fetch(url, {
            method,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(empresaForm.value)
        })
        
        if (response.ok) {
            await cargarDatos()
            cerrarModalEmpresa()
        }
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

// Utilidades
const getTipoTexto = (tipo) => {
    const tipos = { 'MARITIMO': 'Marítimo', 'AEREO': 'Aéreo', 'TERRESTRE': 'Terrestre', 'MULTIMODAL': 'Multimodal' }
    return tipos[tipo] || tipo
}

const getTipoPuertoTexto = (tipo) => {
    const tipos = { 'PUERTO': 'Puerto marítimo', 'AEROPUERTO': 'Aeropuerto', 'AMBOS': 'Puerto + Aeropuerto' }
    return tipos[tipo] || tipo
}

const editarNaviera = (naviera) => {
    console.log('Editar naviera:', naviera)
}

// Lifecycle
onMounted(() => {
    cargarDatos()
})
</script>

<style scoped>
.datos-maestros-container {
    min-height: 100vh;
    background: #f5f7fa;
    font-family: 'Segoe UI', system-ui, sans-serif;
}

.main-content {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px 30px;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.header h1 {
    margin: 0;
    color: #1a202c;
    font-size: 24px;
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

.resumen-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.resumen-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
}

.resumen-numero {
    font-size: 36px;
    font-weight: bold;
    margin-bottom: 10px;
}

.resumen-label {
    font-size: 14px;
    opacity: 0.9;
}

.seccion {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 25px;
}

.seccion-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.seccion-header h2 {
    margin: 0;
    color: #1a202c;
    font-size: 18px;
}

.btn-agregar {
    padding: 6px 12px;
    background: #4299e1;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 12px;
}

.btn-agregar:hover {
    background: #3182ce;
}

.badge {
    display: inline-block;
    background: #e2e8f0;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    color: #4a5568;
    margin-bottom: 20px;
}

.lista-empresas,
.navieras-grid,
.puertos-grid,
.agentes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 15px;
}

.empresa-card,
.naviera-card,
.puerto-card,
.agente-card {
    background: #f7fafc;
    padding: 15px;
    border-radius: 8px;
    transition: all 0.3s;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.empresa-card:hover,
.naviera-card:hover,
.puerto-card:hover,
.agente-card:hover {
    background: #edf2f7;
    transform: translateY(-2px);
}

.empresa-nombre,
.naviera-nombre,
.puerto-nombre,
.agente-nombre {
    font-weight: bold;
    color: #2d3748;
    margin-bottom: 5px;
    font-size: 16px;
}

.empresa-stats,
.naviera-tipo,
.puerto-tipo,
.agente-detalle {
    font-size: 13px;
    color: #718096;
}

.empresa-contacto {
    font-size: 12px;
    color: #a0aec0;
    margin-top: 5px;
}

.puerto-ubicacion {
    font-size: 12px;
    color: #48bb78;
    margin-top: 5px;
}

.incoterms-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 12px;
}

.incoterm-card {
    background: #f7fafc;
    padding: 12px;
    border-radius: 8px;
    text-align: center;
    transition: all 0.3s;
    cursor: pointer;
}

.incoterm-card:hover {
    background: #4299e1;
    color: white;
    transform: translateY(-2px);
}

.incoterm-code {
    font-weight: bold;
    font-size: 18px;
    margin-bottom: 5px;
}

.incoterm-desc {
    font-size: 11px;
    color: #718096;
}

.incoterm-card:hover .incoterm-desc {
    color: white;
}

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

.dashboard-footer {
    margin-top: 30px;
    padding: 15px;
    text-align: center;
    color: #718096;
    border-top: 1px solid #e2e8f0;
}

/* Modal */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-content {
    background: white;
    padding: 30px;
    border-radius: 10px;
    width: 90%;
    max-width: 500px;
}

.modal-content h3 {
    margin: 0 0 20px 0;
    color: #1a202c;
}

.modal-input {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
}

.modal-acciones {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

.btn-guardar {
    padding: 8px 16px;
    background: #48bb78;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

.btn-cancelar {
    padding: 8px 16px;
    background: #e2e8f0;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

@media (max-width: 768px) {
    .main-content {
        padding: 15px;
    }
    
    .resumen-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .lista-empresas,
    .navieras-grid,
    .puertos-grid,
    .agentes-grid {
        grid-template-columns: 1fr;
    }
}
</style>