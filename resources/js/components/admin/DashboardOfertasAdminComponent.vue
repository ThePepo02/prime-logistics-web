<template>
    <div class="todas-ofertas-container">
        <!-- HEADER con título -->
        <div class="page-header">
            <h1>Todas las ofertas admin</h1>
        </div>

        <!-- TABS horizontales (exactamente como en la imagen) -->
        <div class="tabs-container">
            <div class="tabs-wrapper">
                <button v-for="tab in tabs" :key="tab" class="tab-button" :class="{ active: activeTab === tab }"
                    @click="activeTab = tab">
                    {{ tab }}
                </button>
            </div>
        </div>

        <!-- Mensaje de error -->
        <div v-if="mensajeError" class="alert error">
            {{ mensajeError }}
            <button @click="mensajeError = null">×</button>
        </div>

        <!-- TARJETAS DE ESTADÍSTICAS -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ formatNumber(stats.total_ofertas) }}</div>
                <div class="stat-label">Total de ofertas</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ formatNumber(stats.todas_ofertas) }}</div>
                <div class="stat-label">Todas las ofertas</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ formatNumber(stats.completadas) }}</div>
                <div class="stat-label">Todas las ofertas completadas</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ formatNumber(stats.pendientes) }}</div>
                <div class="stat-label">Todas las ofertas pendientes</div>
            </div>
        </div>

        <!-- TABLA DE OFERTAS -->
        <div class="table-container">
            <div class="table-header">
                <h2>Ofertas</h2>
                <button @click="cargarOfertas" class="btn-refresh" :disabled="cargando">
                    {{ cargando ? 'Cargando...' : '⟳ Actualizar' }}
                </button>
            </div>

            <!-- Loading -->
            <div v-if="cargando && !ofertas.length" class="loading-state">
                <div class="spinner"></div>
                <p>Cargando ofertas...</p>
            </div>

            <!-- Tabla -->
            <div v-else class="table-responsive">
                <table class="ofertas-table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Valor unitario</th>
                            <th>Valor total</th>
                            <th>Acciones</th>
                        </tr>
                        <tr class="filter-row">
                            <th><input v-model="filters.nombre" placeholder="Filtrar nombre..." class="filter-input"
                                    @input="handleFilter"></th>
                            <th><input v-model="filters.descripcion" placeholder="Filtrar descripción..."
                                    class="filter-input" @input="handleFilter"></th>
                            <th><input v-model="filters.valor_unitario" placeholder="Filtrar valor..."
                                    class="filter-input" @input="handleFilter"></th>
                            <th><input v-model="filters.valor_total" placeholder="Filtrar valor..." class="filter-input"
                                    @input="handleFilter"></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="oferta in ofertasFiltradas" :key="oferta.id">
                            <td data-label="Nombre">{{ oferta.nombre }}</td>
                            <td data-label="Descripción">{{ oferta.descripcion }}</td>
                            <td data-label="Valor unitario">{{ formatMoney(oferta.valor_unitario) }}</td>
                            <td data-label="Valor total">{{ formatMoney(oferta.valor_total) }}</td>
                            <td data-label="Acciones" class="acciones-cell">
                                <button @click="verDetalles(oferta)" class="btn-accion btn-ver" title="Ver detalles">
                                    👁️ Ver detalles
                                </button>
                                <button @click="editarOferta(oferta)" class="btn-accion btn-editar" title="Editar">
                                    ✏️ Editar
                                </button>
                                <button @click="eliminarOferta(oferta.id)" class="btn-accion btn-eliminar"
                                    title="Eliminar">
                                    🗑️ Eliminar
                                </button>
                            </td>
                        </tr>
                        <tr v-if="ofertasFiltradas.length === 0 && !cargando">
                            <td colspan="5" class="empty-state">
                                No se encontraron ofertas
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- FOOTER (exactamente como en la imagen) -->
        <footer class="footer">
            <div class="footer-links">
                <a href="#">Navegación</a>
                <a href="#">Inicio</a>
                <a href="#">Contacto</a>
                <a href="#">Sobre nosotros</a>
                <a href="#">Página principal</a>
            </div>
            <div class="footer-copy">
                © {{ currentYear }} - Sistema de Gestión de Ofertas
            </div>
        </footer>

        <!-- MODAL DETALLES -->
        <div v-if="modalDetallesVisible" class="modal" @click.self="modalDetallesVisible = false">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Detalles de la oferta</h3>
                    <button class="modal-close" @click="modalDetallesVisible = false">×</button>
                </div>
                <div class="modal-body" v-if="ofertaSeleccionada">
                    <p><strong>Nombre:</strong> {{ ofertaSeleccionada.nombre }}</p>
                    <p><strong>Descripción:</strong> {{ ofertaSeleccionada.descripcion }}</p>
                    <p><strong>Valor unitario:</strong> {{ formatMoney(ofertaSeleccionada.valor_unitario) }}</p>
                    <p><strong>Valor total:</strong> {{ formatMoney(ofertaSeleccionada.valor_total) }}</p>
                    <p><strong>Estado:</strong> {{ ofertaSeleccionada.estado || 'Activa' }}</p>
                </div>
                <div class="modal-footer">
                    <button @click="modalDetallesVisible = false" class="btn-cerrar">Cerrar</button>
                </div>
            </div>
        </div>

        <!-- MODAL EDITAR -->
        <div v-if="modalEditarVisible" class="modal" @click.self="modalEditarVisible = false">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Editar oferta</h3>
                    <button class="modal-close" @click="modalEditarVisible = false">×</button>
                </div>
                <div class="modal-body" v-if="ofertaEditando">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input v-model="ofertaEditando.nombre" class="form-input">
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea v-model="ofertaEditando.descripcion" class="form-input" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Valor unitario</label>
                        <input type="number" v-model="ofertaEditando.valor_unitario" class="form-input">
                    </div>
                    <div class="form-group">
                        <label>Valor total</label>
                        <input type="number" v-model="ofertaEditando.valor_total" class="form-input">
                    </div>
                </div>
                <div class="modal-footer">
                    <button @click="guardarEdicion" class="btn-guardar">Guardar cambios</button>
                    <button @click="modalEditarVisible = false" class="btn-cancelar">Cancelar</button>
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
const ofertas = ref([])

// Tabs (exactamente como en la imagen)
const tabs = ref([
    'Administración', 'Servicios', 'Contratación', 'Recursos Humanos',
    'Finanzas', 'Marketing', 'Tecnología', 'Gestión de Proyectos',
    'Informática', 'Comunicaciones', 'Contabilidad', 'Seguridad y Salud',
    'Medio Ambiente', 'Desarrollo Sostenible', 'Empresarial', 'Pymes', 'Otros'
])
const activeTab = ref('Administración')

// Estadísticas
const stats = ref({
    total_ofertas: 4200,
    todas_ofertas: 3308,
    completadas: 35,
    pendientes: 284
})

// Filtros para la tabla
const filters = ref({
    nombre: '',
    descripcion: '',
    valor_unitario: '',
    valor_total: ''
})

// Modales
const modalDetallesVisible = ref(false)
const modalEditarVisible = ref(false)
const ofertaSeleccionada = ref(null)
const ofertaEditando = ref(null)

// URL de la API
const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api/v1'

// Año actual para el footer
const currentYear = ref(new Date().getFullYear())

// Ofertas filtradas
const ofertasFiltradas = computed(() => {
    if (!ofertas.value.length) return []

    return ofertas.value.filter(oferta => {
        const matchNombre = oferta.nombre?.toLowerCase().includes(filters.value.nombre.toLowerCase())
        const matchDescripcion = oferta.descripcion?.toLowerCase().includes(filters.value.descripcion.toLowerCase())
        const matchValorUnitario = filters.value.valor_unitario === '' ||
            oferta.valor_unitario?.toString().includes(filters.value.valor_unitario)
        const matchValorTotal = filters.value.valor_total === '' ||
            oferta.valor_total?.toString().includes(filters.value.valor_total)

        return matchNombre && matchDescripcion && matchValorUnitario && matchValorTotal
    })
})

// Cargar ofertas desde la API
const cargarOfertas = async () => {
    cargando.value = true
    mensajeError.value = null

    try {
        const response = await fetch(`${API_URL}/ofertas`)
        const result = await response.json()

        if (result.success || Array.isArray(result)) {
            ofertas.value = Array.isArray(result) ? result : result.data || []

            // Actualizar estadísticas basadas en los datos reales
            if (ofertas.value.length) {
                stats.value.total_ofertas = ofertas.value.length
                stats.value.todas_ofertas = ofertas.value.length
                stats.value.completadas = ofertas.value.filter(o => o.estado === 'completada' || o.completada).length
                stats.value.pendientes = ofertas.value.filter(o => o.estado === 'pendiente' || !o.completada).length
            }
        } else {
            throw new Error(result.message || 'Error al cargar ofertas')
        }
    } catch (error) {
        console.error('Error:', error)
        mensajeError.value = 'Error al cargar las ofertas'
        // Datos de ejemplo para demostración
        ofertas.value = [
            { id: 1, nombre: 'Oferta Premium', descripcion: 'Servicio de primera clase', valor_unitario: 1500, valor_total: 15000, estado: 'activa' },
            { id: 2, nombre: 'Oferta Básica', descripcion: 'Servicio estándar', valor_unitario: 500, valor_total: 5000, estado: 'pendiente' },
            { id: 3, nombre: 'Oferta Express', descripcion: 'Entrega rápida 24h', valor_unitario: 2500, valor_total: 25000, estado: 'completada' },
            { id: 4, nombre: 'Oferta Corporativa', descripcion: 'Para empresas grandes', valor_unitario: 5000, valor_total: 50000, estado: 'activa' },
        ]
    } finally {
        cargando.value = false
    }
}

// CRUD de ofertas
const verDetalles = (oferta) => {
    ofertaSeleccionada.value = oferta
    modalDetallesVisible.value = true
}

const editarOferta = (oferta) => {
    ofertaEditando.value = { ...oferta }
    modalEditarVisible.value = true
}

const guardarEdicion = async () => {
    if (!ofertaEditando.value) return

    try {
        const response = await fetch(`${API_URL}/ofertas/${ofertaEditando.value.id}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(ofertaEditando.value)
        })

        if (response.ok) {
            await cargarOfertas()
            modalEditarVisible.value = false
        }
    } catch (error) {
        console.error('Error al guardar:', error)
        mensajeError.value = 'Error al guardar los cambios'
    }
}

const eliminarOferta = async (id) => {
    if (!confirm('¿Estás seguro de que quieres eliminar esta oferta?')) return

    try {
        const response = await fetch(`${API_URL}/ofertas/${id}`, {
            method: 'DELETE'
        })

        if (response.ok) {
            await cargarOfertas()
        } else {
            throw new Error('Error al eliminar')
        }
    } catch (error) {
        console.error('Error al eliminar:', error)
        mensajeError.value = 'Error al eliminar la oferta'
    }
}

// Filtros con debounce
let filterTimeout
const handleFilter = () => {
    clearTimeout(filterTimeout)
    // No necesita debounce para filtros locales, pero lo dejo por si acaso
}

// Utilitarios
const formatNumber = (num) => {
    return num?.toLocaleString('es-ES') ?? '0'
}

const formatMoney = (value) => {
    if (value === undefined || value === null) return '€0'
    return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(value)
}

// Watchers
watch(activeTab, (newTab) => {
    console.log('Tab seleccionada:', newTab)
    // Aquí se podría cargar ofertas diferentes según la pestaña
    cargarOfertas()
})

// Lifecycle
onMounted(() => {
    cargarOfertas()
})
</script>

<style scoped>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.todas-ofertas-container {
    min-height: 100vh;
    background: #f5f7fa;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* HEADER */
.page-header {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    padding: 24px 32px;
    border-bottom: 3px solid #3b82f6;
}

.page-header h1 {
    margin: 0;
    color: white;
    font-size: 28px;
    font-weight: 600;
}

/* TABS */
.tabs-container {
    background: white;
    border-bottom: 1px solid #e2e8f0;
    overflow-x: auto;
    white-space: nowrap;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.tabs-wrapper {
    display: inline-flex;
    padding: 0 20px;
}

.tab-button {
    background: none;
    border: none;
    padding: 16px 24px;
    font-size: 14px;
    font-weight: 500;
    color: #64748b;
    cursor: pointer;
    transition: all 0.2s;
    position: relative;
}

.tab-button:hover {
    color: #3b82f6;
    background: #f8fafc;
}

.tab-button.active {
    color: #3b82f6;
}

.tab-button.active::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: #3b82f6;
}

/* Mensaje de error */
.alert {
    margin: 20px 32px;
    padding: 12px 16px;
    border-radius: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.alert.error {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

.alert button {
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    color: #991b1b;
}

/* TARJETAS DE ESTADÍSTICAS */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
    padding: 32px;
}

.stat-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    text-align: center;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s, box-shadow 0.2s;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.stat-number {
    font-size: 42px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 8px;
}

.stat-label {
    font-size: 14px;
    color: #64748b;
    font-weight: 500;
}

/* TABLA */
.table-container {
    background: white;
    margin: 0 32px 32px 32px;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid #e2e8f0;
}

.table-header h2 {
    margin: 0;
    font-size: 18px;
    color: #1e293b;
}

.btn-refresh {
    background: #3b82f6;
    border: none;
    padding: 8px 20px;
    border-radius: 8px;
    color: white;
    cursor: pointer;
    font-weight: 500;
    transition: background 0.2s;
}

.btn-refresh:hover:not(:disabled) {
    background: #2563eb;
}

.btn-refresh:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.loading-state {
    text-align: center;
    padding: 60px;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 3px solid #e2e8f0;
    border-top-color: #3b82f6;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin: 0 auto 16px;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.table-responsive {
    overflow-x: auto;
}

.ofertas-table {
    width: 100%;
    border-collapse: collapse;
}

.ofertas-table th,
.ofertas-table td {
    padding: 14px 16px;
    text-align: left;
    border-bottom: 1px solid #e2e8f0;
}

.ofertas-table th {
    background: #f8fafc;
    font-weight: 600;
    color: #1e293b;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.filter-row th {
    background: #ffffff;
    padding: 8px 16px;
}

.filter-input {
    width: 100%;
    padding: 8px 10px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    font-size: 12px;
    transition: border-color 0.2s;
}

.filter-input:focus {
    outline: none;
    border-color: #3b82f6;
}

.ofertas-table tbody tr:hover {
    background: #f8fafc;
}

.ofertas-table td {
    color: #334155;
    font-size: 14px;
}

.acciones-cell {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.btn-accion {
    padding: 6px 12px;
    border: none;
    border-radius: 6px;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.2s;
    font-weight: 500;
}

.btn-ver {
    background: #e0e7ff;
    color: #3730a3;
}

.btn-ver:hover {
    background: #c7d2fe;
}

.btn-editar {
    background: #fef3c7;
    color: #92400e;
}

.btn-editar:hover {
    background: #fde68a;
}

.btn-eliminar {
    background: #fee2e2;
    color: #991b1b;
}

.btn-eliminar:hover {
    background: #fecaca;
}

.empty-state {
    text-align: center;
    padding: 48px !important;
    color: #94a3b8;
}

/* FOOTER */
.footer {
    background: #1e293b;
    color: #cbd5e1;
    padding: 32px;
    text-align: center;
    margin-top: 32px;
}

.footer-links {
    display: flex;
    justify-content: center;
    gap: 32px;
    flex-wrap: wrap;
    margin-bottom: 20px;
}

.footer-links a {
    color: #cbd5e1;
    text-decoration: none;
    font-size: 14px;
    transition: color 0.2s;
}

.footer-links a:hover {
    color: white;
}

.footer-copy {
    font-size: 12px;
    color: #94a3b8;
}

/* MODALES */
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
    border-radius: 16px;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    animation: modalIn 0.2s ease;
}

@keyframes modalIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }

    to {
        opacity: 1;
        transform: scale(1);
    }
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid #e2e8f0;
}

.modal-header h3 {
    margin: 0;
    color: #1e293b;
}

.modal-close {
    background: none;
    border: none;
    font-size: 28px;
    cursor: pointer;
    color: #94a3b8;
    line-height: 1;
}

.modal-close:hover {
    color: #1e293b;
}

.modal-body {
    padding: 24px;
}

.modal-body p {
    margin: 12px 0;
    line-height: 1.5;
}

.form-group {
    margin-bottom: 16px;
}

.form-group label {
    display: block;
    margin-bottom: 6px;
    font-weight: 500;
    color: #334155;
    font-size: 13px;
}

.form-input {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    font-family: inherit;
}

.form-input:focus {
    outline: none;
    border-color: #3b82f6;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    padding: 20px 24px;
    border-top: 1px solid #e2e8f0;
}

.btn-guardar {
    background: #10b981;
    color: white;
    border: none;
    padding: 8px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
}

.btn-guardar:hover {
    background: #059669;
}

.btn-cancelar,
.btn-cerrar {
    background: #e2e8f0;
    color: #334155;
    border: none;
    padding: 8px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
}

.btn-cancelar:hover,
.btn-cerrar:hover {
    background: #cbd5e1;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
        padding: 20px;
    }

    .table-container {
        margin: 0 20px 20px 20px;
    }

    .footer-links {
        gap: 20px;
    }

    .acciones-cell {
        flex-direction: column;
    }

    .btn-accion {
        width: 100%;
        text-align: center;
    }

    .ofertas-table th,
    .ofertas-table td {
        padding: 10px 12px;
    }
}
</style>