<template>
    <div class="clientes-page">

        <!-- CABECERA -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Clientes</h1>
                <p class="page-subtitle">Gestión de empresas y contactos</p>
            </div>
            <button class="btn-primary" @click="abrirModalNuevo">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                Nuevo Cliente
            </button>
        </div>

        <!-- TARJETAS RESUMEN -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon stat-icon--blue">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8"
                        viewBox="0 0 24 24">
                        <rect x="2" y="7" width="20" height="14" rx="2" />
                        <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2" />
                    </svg>
                </div>
                <div>
                    <p class="stat-value">{{ totalEmpresas }}</p>
                    <p class="stat-label">Empresas</p>
                    <p class="stat-sub">Clientes activos</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon stat-icon--green">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8"
                        viewBox="0 0 24 24">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                </div>
                <div>
                    <p class="stat-value">{{ totalContactos }}</p>
                    <p class="stat-label">Contactos</p>
                    <p class="stat-sub">Usuarios registrados</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon stat-icon--orange">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8"
                        viewBox="0 0 24 24">
                        <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                        <rect x="9" y="3" width="6" height="4" rx="1" />
                        <path d="M9 12h6M9 16h4" />
                    </svg>
                </div>
                <div>
                    <p class="stat-value">{{ totalOfertas }}</p>
                    <p class="stat-label">Ofertas activas</p>
                    <p class="stat-sub">En gestión</p>
                </div>
            </div>
        </div>

        <!-- FILTROS -->
        <div class="filters-bar">
            <div class="search-wrapper">
                <svg class="search-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.35-4.35" />
                </svg>
                <input v-model="busqueda" type="text" placeholder="Buscar empresa o contacto..." class="search-input" />
            </div>
            <div class="select-wrapper">
                <select v-model="filtroEstado" class="estado-select">
                    <option value="">Todos los estados</option>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
                <svg class="select-arrow" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                    viewBox="0 0 24 24">
                    <path d="m6 9 6 6 6-6" />
                </svg>
            </div>
        </div>

        <!-- TABLA -->
        <div class="table-wrapper">
            <table class="clientes-table">
                <thead>
                    <tr>
                        <th>Empresa</th>
                        <th>CIF/NIF</th>
                        <th>Contacto</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Ofertas</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="clientesFiltrados.length === 0">
                        <td colspan="8" class="empty-row">No se encontraron clientes</td>
                    </tr>
                    <tr v-for="cliente in clientesFiltrados" :key="cliente.id" class="table-row">
                        <td>
                            <div class="empresa-cell">
                                <div class="empresa-icon">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8"
                                        viewBox="0 0 24 24">
                                        <rect x="2" y="7" width="20" height="14" rx="2" />
                                        <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2" />
                                    </svg>
                                </div>
                                <span class="empresa-nombre">{{ cliente.empresa }}</span>
                            </div>
                        </td>
                        <td class="text-muted">{{ cliente.cif }}</td>
                        <td>{{ cliente.contacto }}</td>
                        <td class="text-muted">{{ cliente.email }}</td>
                        <td class="text-muted">{{ cliente.telefono }}</td>
                        <td>{{ cliente.ofertas }} ofertas</td>
                        <td>
                            <button class="toggle-btn" :class="{ 'toggle-btn--on': cliente.activo }"
                                @click="toggleEstado(cliente)">
                                <span class="toggle-thumb"></span>
                            </button>
                        </td>
                        <td>
                            <div class="acciones-cell">
                                <button class="btn-ver" @click="verCliente(cliente)">Ver</button>
                                <button class="btn-editar" :disabled="!cliente.activo"
                                    :class="{ 'btn-editar--disabled': !cliente.activo }"
                                    @click="editarCliente(cliente)">Editar</button>
                                <button class="btn-nuevo-pedido" @click="nuevoPedido(cliente)">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5"
                                        viewBox="0 0 24 24">
                                        <path d="M12 5v14M5 12h14" />
                                    </svg>
                                    Nuevo pedido
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="table-footer">
                <span class="table-count">{{ clientesFiltrados.length }} clientes registrados</span>
                <button class="btn-primary" @click="abrirModalNuevo">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                        viewBox="0 0 24 24">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                    Nuevo Cliente
                </button>
            </div>
        </div>

        <!-- MODAL VER CLIENTE -->
        <div v-if="modalVer" class="modal-overlay" @click.self="cerrarModal">
            <div class="modal">
                <div class="modal-header">
                    <h2 class="modal-title">Detalle del cliente</h2>
                    <button class="modal-close" @click="cerrarModal">✕</button>
                </div>
                <div class="modal-body" v-if="clienteSeleccionado">
                    <div class="modal-field"><span class="modal-label">Empresa</span><span>{{
                        clienteSeleccionado.empresa }}</span></div>
                    <div class="modal-field"><span class="modal-label">CIF/NIF</span><span>{{ clienteSeleccionado.cif
                            }}</span></div>
                    <div class="modal-field"><span class="modal-label">Contacto</span><span>{{
                        clienteSeleccionado.contacto }}</span></div>
                    <div class="modal-field"><span class="modal-label">Email</span><span>{{ clienteSeleccionado.email
                            }}</span></div>
                    <div class="modal-field"><span class="modal-label">Teléfono</span><span>{{
                        clienteSeleccionado.telefono }}</span></div>
                    <div class="modal-field"><span class="modal-label">Ofertas</span><span>{{
                        clienteSeleccionado.ofertas }}</span></div>
                    <div class="modal-field">
                        <span class="modal-label">Estado</span>
                        <span :class="clienteSeleccionado.activo ? 'badge-activo' : 'badge-inactivo'">
                            {{ clienteSeleccionado.activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn-secondary" @click="cerrarModal">Cerrar</button>
                </div>
            </div>
        </div>

        <!-- MODAL NUEVO / EDITAR -->
        <div v-if="modalForm" class="modal-overlay" @click.self="cerrarModal">
            <div class="modal">
                <div class="modal-header">
                    <h2 class="modal-title">{{ modoEdicion ? 'Editar cliente' : 'Nuevo cliente' }}</h2>
                    <button class="modal-close" @click="cerrarModal">✕</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Empresa *</label>
                        <input v-model="form.empresa" type="text" class="form-input"
                            placeholder="Nombre de la empresa" />
                    </div>
                    <div class="form-group">
                        <label class="form-label">CIF/NIF *</label>
                        <input v-model="form.cif" type="text" class="form-input" placeholder="B-12345678" />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Contacto *</label>
                        <input v-model="form.contacto" type="text" class="form-input"
                            placeholder="Nombre del contacto" />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email *</label>
                        <input v-model="form.email" type="email" class="form-input"
                            placeholder="contacto@empresa.com" />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Teléfono</label>
                        <input v-model="form.telefono" type="text" class="form-input" placeholder="+34 600 000 000" />
                    </div>
                    <p v-if="errorForm" class="form-error">{{ errorForm }}</p>
                </div>
                <div class="modal-footer">
                    <button class="btn-secondary" @click="cerrarModal">Cancelar</button>
                    <button class="btn-primary" @click="guardarCliente">
                        {{ modoEdicion ? 'Guardar cambios' : 'Crear cliente' }}
                    </button>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    name: 'ClientesComponent',

    data() {
        return {
            clientes: [],
            busqueda: '',
            filtroEstado: '',
            modalVer: false,
            modalForm: false,
            clienteSeleccionado: null,
            modoEdicion: false,
            form: { empresa: '', cif: '', contacto: '', email: '', telefono: '' },
            errorForm: null,
        }
    },

    computed: {
        clientesFiltrados() {
            return this.clientes.filter(cliente => {
                const texto = this.busqueda.toLowerCase()
                const coincideTexto = !texto ||
                    cliente.empresa.toLowerCase().includes(texto) ||
                    cliente.contacto.toLowerCase().includes(texto) ||
                    cliente.email.toLowerCase().includes(texto)
                const coincideEstado = !this.filtroEstado ||
                    (this.filtroEstado === 'activo' && cliente.activo) ||
                    (this.filtroEstado === 'inactivo' && !cliente.activo)
                return coincideTexto && coincideEstado
            })
        },
        totalEmpresas() { return this.clientes.length },
        totalContactos() { return this.clientes.length },
        totalOfertas() { return this.clientes.reduce((suma, c) => suma + c.ofertas, 0) },
    },

    mounted() {
        this.cargarClientes()
    },

    methods: {
        async cargarClientes() {
            try {
                const token = localStorage.getItem('token');
                const response = await fetch('/api/clientes', {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                    }
                });
                const data = await response.json();
                this.clientes = data;
            } catch (e) {
                console.error('Error cargando clientes:', e);
            }
        },

        async toggleEstado(cliente) {
            try {
                const token = localStorage.getItem('token');
                const response = await fetch(`/api/clientes/${cliente.id}/estado`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`,
                    },
                });
                const data = await response.json();
                cliente.activo = data.activo;
            } catch (e) {
                console.error('Error actualizando estado:', e);
            }
        },

        verCliente(cliente) {
            this.clienteSeleccionado = cliente
            this.modalVer = true
        },
        editarCliente(cliente) {
            this.modoEdicion = true
            this.clienteSeleccionado = cliente
            this.form = { ...cliente }
            this.modalForm = true
        },
        abrirModalNuevo() {
            this.modoEdicion = false
            this.clienteSeleccionado = null
            this.form = { empresa: '', cif: '', contacto: '', email: '', telefono: '' }
            this.errorForm = null
            this.modalForm = true
        },
        guardarCliente() {
            if (!this.form.empresa || !this.form.cif || !this.form.contacto || !this.form.email) {
                this.errorForm = 'Por favor rellena todos los campos obligatorios.'
                return
            }
            this.errorForm = null
            if (this.modoEdicion) {
                const idx = this.clientes.findIndex(c => c.id === this.clienteSeleccionado.id)
                if (idx !== -1) Object.assign(this.clientes[idx], this.form)
            } else {
                const nuevoId = Math.max(...this.clientes.map(c => c.id)) + 1
                this.clientes.push({ ...this.form, id: nuevoId, ofertas: 0, activo: true })
            }
            this.cerrarModal()
        },
        nuevoPedido(cliente) {
            alert(`Nuevo pedido para: ${cliente.empresa}\n(Funcionalidad pendiente)`)
        },
        cerrarModal() {
            this.modalVer = false
            this.modalForm = false
            this.clienteSeleccionado = null
            this.errorForm = null
        },
    },
}
</script>

<style scoped>
.clientes-page {
    padding: 32px 36px;
    background: #F3F4F6;
    min-height: 100%;
    font-family: 'Segoe UI', system-ui, sans-serif;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 28px;
}

.page-title {
    font-size: 24px;
    font-weight: 700;
    color: #1E2A3B;
    margin: 0;
}

.page-subtitle {
    font-size: 13px;
    color: #6B7280;
    margin: 4px 0 0;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}

.stat-card {
    background: #fff;
    border-radius: 10px;
    padding: 20px 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, .07);
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.stat-icon--blue {
    background: #EFF6FF;
    color: #3B82F6;
}

.stat-icon--green {
    background: #F0FDF4;
    color: #22C55E;
}

.stat-icon--orange {
    background: #FFF7ED;
    color: #F97316;
}

.stat-value {
    font-size: 26px;
    font-weight: 700;
    color: #1E2A3B;
    line-height: 1;
}

.stat-label {
    font-size: 14px;
    font-weight: 600;
    color: #1E2A3B;
    margin-top: 4px;
}

.stat-sub {
    font-size: 12px;
    color: #6B7280;
    margin-top: 2px;
}

.filters-bar {
    display: flex;
    gap: 12px;
    margin-bottom: 16px;
}

.search-wrapper {
    position: relative;
    flex: 1;
    max-width: 340px;
}

.search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #6B7280;
    pointer-events: none;
}

.search-input {
    width: 100%;
    padding: 9px 12px 9px 36px;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    font-size: 13px;
    background: #fff;
    outline: none;
    box-sizing: border-box;
}

.search-input:focus {
    border-color: #F97316;
    box-shadow: 0 0 0 3px rgba(249, 115, 22, .12);
}

.select-wrapper {
    position: relative;
}

.estado-select {
    appearance: none;
    padding: 9px 36px 9px 14px;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    font-size: 13px;
    background: #fff;
    cursor: pointer;
    outline: none;
}

.select-arrow {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
    color: #6B7280;
}

.table-wrapper {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, .07);
    overflow: hidden;
}

.clientes-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13.5px;
}

.clientes-table thead tr {
    border-bottom: 1px solid #E5E7EB;
}

.clientes-table th {
    padding: 12px 16px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: .04em;
    white-space: nowrap;
}

.table-row {
    border-bottom: 1px solid #F9FAFB;
    transition: background .15s;
}

.table-row:hover {
    background: #FAFAFA;
}

.table-row:last-child {
    border-bottom: none;
}

.clientes-table td {
    padding: 14px 16px;
    vertical-align: middle;
}

.empty-row {
    text-align: center;
    color: #6B7280;
    padding: 40px !important;
}

.text-muted {
    color: #6B7280;
}

.empresa-cell {
    display: flex;
    align-items: center;
    gap: 10px;
}

.empresa-icon {
    width: 30px;
    height: 30px;
    background: #F3F4F6;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6B7280;
    flex-shrink: 0;
}

.empresa-nombre {
    font-weight: 600;
    color: #1E2A3B;
}

.toggle-btn {
    width: 42px;
    height: 24px;
    border-radius: 12px;
    background: #D1D5DB;
    border: none;
    cursor: pointer;
    position: relative;
    transition: background .25s;
    padding: 0;
}

.toggle-btn--on {
    background: #22C55E;
}

.toggle-thumb {
    position: absolute;
    top: 3px;
    left: 3px;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: white;
    box-shadow: 0 1px 3px rgba(0, 0, 0, .2);
    transition: transform .25s;
}

.toggle-btn--on .toggle-thumb {
    transform: translateX(18px);
}

.acciones-cell {
    display: flex;
    gap: 8px;
    align-items: center;
}

.btn-ver {
    padding: 6px 14px;
    border: 1px solid #E5E7EB;
    border-radius: 7px;
    background: #fff;
    font-size: 13px;
    cursor: pointer;
    color: #1E2A3B;
    font-weight: 500;
}

.btn-ver:hover {
    background: #F9FAFB;
}

.btn-editar {
    padding: 6px 14px;
    border: 1px solid #E5E7EB;
    border-radius: 7px;
    background: #fff;
    font-size: 13px;
    cursor: pointer;
    color: #1E2A3B;
    font-weight: 500;
}

.btn-editar:hover:not(:disabled) {
    background: #F9FAFB;
}

.btn-editar--disabled {
    opacity: .4;
    cursor: not-allowed;
}

.btn-nuevo-pedido {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 6px 14px;
    border: none;
    border-radius: 7px;
    background: #F97316;
    color: white;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    white-space: nowrap;
}

.btn-nuevo-pedido:hover {
    background: #EA6C0A;
}

.table-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 20px;
    border-top: 1px solid #E5E7EB;
}

.table-count {
    font-size: 13px;
    color: #6B7280;
}

.btn-primary {
    display: flex;
    align-items: center;
    gap: 7px;
    padding: 9px 18px;
    background: #F97316;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 13.5px;
    font-weight: 600;
    cursor: pointer;
}

.btn-primary:hover {
    background: #EA6C0A;
}

.btn-secondary {
    padding: 9px 18px;
    background: #fff;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    font-size: 13.5px;
    font-weight: 500;
    cursor: pointer;
    color: #1E2A3B;
}

.btn-secondary:hover {
    background: #F9FAFB;
}

.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, .45);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal {
    background: #fff;
    border-radius: 14px;
    width: 480px;
    max-width: 94vw;
    box-shadow: 0 20px 60px rgba(0, 0, 0, .18);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px 16px;
    border-bottom: 1px solid #E5E7EB;
}

.modal-title {
    font-size: 16px;
    font-weight: 700;
    color: #1E2A3B;
    margin: 0;
}

.modal-close {
    background: none;
    border: none;
    cursor: pointer;
    color: #6B7280;
    font-size: 18px;
    padding: 4px 8px;
    border-radius: 6px;
}

.modal-close:hover {
    background: #F3F4F6;
}

.modal-body {
    padding: 20px 24px;
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.modal-field {
    display: flex;
    justify-content: space-between;
    font-size: 14px;
}

.modal-label {
    color: #6B7280;
    font-weight: 500;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 16px 24px;
    border-top: 1px solid #E5E7EB;
}

.badge-activo {
    color: #16A34A;
    font-weight: 600;
}

.badge-inactivo {
    color: #DC2626;
    font-weight: 600;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.form-label {
    font-size: 13px;
    font-weight: 600;
    color: #1E2A3B;
}

.form-input {
    padding: 9px 12px;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    font-size: 13.5px;
    color: #1E2A3B;
    outline: none;
    background: #fff;
}

.form-input:focus {
    border-color: #F97316;
    box-shadow: 0 0 0 3px rgba(249, 115, 22, .12);
}

.form-error {
    color: #DC2626;
    font-size: 13px;
    margin: 0;
}
</style>
