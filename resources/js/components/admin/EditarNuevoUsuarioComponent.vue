<template>
    <div v-if="visible" class="modal-overlay">
        <div class="modal">
            <!-- Header -->
            <div class="modal-header">
                <h3>Editar Usuario admin</h3>
                <button class="close-btn" @click="$emit('close')">×</button>
            </div>

            <!-- Tabs horizontales con scroll (como en la imagen) -->
            <div class="tabs-container">
                <div class="tabs-wrapper">
                    <!-- Tab Administrador -->
                    <button class="tab-button" :class="{ active: activeMainTab === 'administrador' }"
                        @click="activeMainTab = 'administrador'">
                        Administrador
                    </button>

                    <!-- Todas las opciones de Contas (como en la imagen) -->
                    <button v-for="tab in contasTabs" :key="tab" class="tab-button"
                        :class="{ active: activeMainTab === tab }" @click="activeMainTab = tab">
                        {{ tab }}
                    </button>
                </div>
            </div>

            <!-- Contenido del modal -->
            <div class="modal-body">
                <!-- Para el tab Administrador: mostrar el formulario de edición -->
                <div v-if="activeMainTab === 'administrador'" class="admin-content">
                    <!-- Información del usuario -->
                    <div class="user-info-section">
                        <div class="user-avatar">
                            <div class="avatar-circle">
                                {{ initials }}
                            </div>
                        </div>
                        <div class="user-fields">
                            <div class="field-row">
                                <div class="field">
                                    <label>Nombre completo</label>
                                    <input v-model="form.name" type="text" />
                                </div>
                                <div class="field">
                                    <label>Email</label>
                                    <input v-model="form.email" type="email" />
                                </div>
                            </div>
                            <div class="field-row">
                                <div class="field">
                                    <label>Teléfono</label>
                                    <input v-model="form.phone" type="tel" />
                                </div>
                                <div class="field">
                                    <label>Cargo</label>
                                    <input v-model="form.position" type="text" />
                                </div>
                            </div>
                            <div class="field-row">
                                <div class="field">
                                    <label>Rol</label>
                                    <select v-model="form.role">
                                        <option value="admin">Administrador</option>
                                        <option value="operator">Operador</option>
                                        <option value="client">Cliente</option>
                                    </select>
                                </div>
                                <div class="field">
                                    <label>Estado</label>
                                    <select v-model="form.status">
                                        <option value="active">Activo</option>
                                        <option value="inactive">Inactivo</option>
                                        <option value="suspended">Suspendido</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Datos de empresa -->
                    <div class="company-section">
                        <h4>Datos de empresa</h4>
                        <div class="field-row">
                            <div class="field">
                                <label>Nombre de empresa</label>
                                <input v-model="form.companyName" type="text" />
                            </div>
                            <div class="field">
                                <label>NIF/CIF</label>
                                <input v-model="form.cif" type="text" />
                            </div>
                        </div>
                        <div class="field">
                            <label>Dirección</label>
                            <input v-model="form.address" type="text" />
                        </div>
                    </div>

                    <!-- Permisos -->
                    <div class="permissions-section">
                        <h4>Permisos</h4>
                        <div class="permissions-grid">
                            <label class="checkbox-label">
                                <input type="checkbox" v-model="form.permissions.manageUsers" />
                                Gestionar usuarios
                            </label>
                            <label class="checkbox-label">
                                <input type="checkbox" v-model="form.permissions.manageRoles" />
                                Gestionar roles
                            </label>
                            <label class="checkbox-label">
                                <input type="checkbox" v-model="form.permissions.viewReports" />
                                Ver reportes
                            </label>
                            <label class="checkbox-label">
                                <input type="checkbox" v-model="form.permissions.editSettings" />
                                Editar configuración
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Para los tabs de Contas: mostrar configuración de cuentas -->
                <div v-else class="contas-content">
                    <div class="contas-config">
                        <h4>Configuración de {{ activeMainTab }}</h4>
                        <div class="config-options">
                            <label class="checkbox-label">
                                <input type="checkbox" v-model="contasConfig[activeMainTab]" />
                                Habilitar cuenta
                            </label>
                            <div class="field">
                                <label>Límite de crédito</label>
                                <input type="number" v-model="contasLimits[activeMainTab]" placeholder="0.00 €" />
                            </div>
                            <div class="field">
                                <label>Días de pago</label>
                                <input type="number" v-model="contasPaymentDays[activeMainTab]" placeholder="30" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer con botones -->
            <div class="modal-footer">
                <button class="btn-danger" @click="deleteUser">Eliminar usuario</button>
                <div class="actions-right">
                    <button class="btn-secondary" @click="$emit('close')">Cancelar</button>
                    <button class="btn-primary" @click="save">Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'

const props = defineProps({
    visible: Boolean,
    user: Object
})

const emit = defineEmits(['close', 'saved', 'deleted'])

// Tabs principales (Administrador + todas las opciones de Contas)
const activeMainTab = ref('administrador')

// Generar todas las opciones de Contas como aparecen en la imagen
const contasTabs = ref([
    'Contas de Pagamento',
    'Contas de Vendas',
    'Contas de Pagamentos',
    'Contas de Vendas',
    'Contas de Pagamentos e Vendas',
    'Contas de Pagamentos e Vendas (2)',
    'Contas de Pagamentos e Vendas (3)',
    'Contas de Pagamentos e Vendas (4)',
    'Contas de Pagamentos e Vendas (5)',
    'Contas de Pagamentos e Vendas (6)',
    'Contas de Pagamentos e Vendas (7)',
    'Contas de Pagamentos e Vendas (8)',
    'Contas de Pagamentos e Vendas (9)',
    'Contas de Pagamentos e Vendas (10)',
    'Contas de Pagamentos e Vendas (11)',
    'Contas de Pagamentos e Vendas (12)',
    'Contas de Pagamentos e Vendas (13)',
    'Contas de Pagamentos e Vendas (14)',
    'Contas de Pagamentos e Vendas (15)',
    'Contas de Pagamentos e Vendas (16)',
    'Contas de Pagamentos e Vendas (17)',
    'Contas de Pagamentos e Vendas (18)',
    'Contas de Pagamentos e Vendas (19)',
    'Contas de Pagamentos e Vendas (20)',
    'Contas de Pagamentos e Vendas (21)',
    'Contas de Pagamentos e Vendas (22)',
    'Contas de Pagamentos e Vendas (23)',
    'Contas de Pagamentos e Vendas (24)'
])

// Configuración de cuentas
const contasConfig = ref({})
const contasLimits = ref({})
const contasPaymentDays = ref({})

// Inicializar configuraciones para cada tab
contasTabs.value.forEach(tab => {
    contasConfig.value[tab] = false
    contasLimits.value[tab] = 0
    contasPaymentDays.value[tab] = 30
})

// Estructura del formulario
const form = ref({
    id: null,
    name: '',
    phone: '',
    email: '',
    email2: '',
    position: '',
    role: 'client',
    status: 'active',
    companyName: '',
    cif: '',
    address: '',
    permissions: {
        manageUsers: false,
        manageRoles: false,
        viewReports: false,
        editSettings: false
    }
})

// Inicializar datos cuando cambia el usuario
watch(() => props.user, (userData) => {
    if (userData) {
        form.value = {
            id: userData.id || null,
            name: userData.name || '',
            phone: userData.phone || '',
            email: userData.email || '',
            email2: userData.email2 || userData.email || '',
            position: userData.position || '',
            role: userData.role || 'client',
            status: userData.status || 'active',
            companyName: userData.companyName || '',
            cif: userData.cif || '',
            address: userData.address || '',
            permissions: {
                manageUsers: userData.permissions?.manageUsers || false,
                manageRoles: userData.permissions?.manageRoles || false,
                viewReports: userData.permissions?.viewReports || false,
                editSettings: userData.permissions?.editSettings || false
            }
        }
    }
}, { immediate: true, deep: true })

// Iniciales del avatar
const initials = computed(() => {
    if (!form.value.name) return '?'
    return form.value.name
        .split(' ')
        .map(word => word[0])
        .join('')
        .toUpperCase()
        .slice(0, 2)
})

// Guardar cambios
const save = async () => {
    try {
        if (!form.value.name) {
            alert('El nombre completo es requerido')
            return
        }

        // Preparar datos completos incluyendo configuración de cuentas
        const fullData = {
            ...form.value,
            contas_config: contasConfig.value,
            contas_limits: contasLimits.value,
            contas_payment_days: contasPaymentDays.value
        }

        emit('saved', fullData)
    } catch (error) {
        console.error('Error al guardar:', error)
        alert('Error al guardar los cambios')
    }
}

// Eliminar usuario
const deleteUser = async () => {
    if (!confirm(`¿Estás seguro de que quieres eliminar a "${form.value.name}"?\nEsta acción no se puede deshacer.`)) {
        return
    }

    try {
        emit('deleted', form.value.id)
        emit('close')
    } catch (error) {
        console.error('Error al eliminar:', error)
        alert('Error al eliminar el usuario')
    }
}
</script>

<style lang="scss" scoped>
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal {
    width: 900px;
    max-width: 90vw;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    display: flex;
    flex-direction: column;
    max-height: 85vh;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid #e9ecef;

    h3 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 600;
        color: #212529;
    }

    .close-btn {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #6c757d;
        line-height: 1;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;

        &:hover {
            background: #f8f9fa;
            color: #000;
        }
    }
}

/* Tabs con scroll horizontal (como en la imagen) */
.tabs-container {
    border-bottom: 1px solid #e9ecef;
    background: #f8f9fa;
    overflow-x: auto;
    white-space: nowrap;

    &::-webkit-scrollbar {
        height: 4px;
    }

    &::-webkit-scrollbar-track {
        background: #e9ecef;
    }

    &::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
}

.tabs-wrapper {
    display: inline-flex;
    padding: 0 12px;
}

.tab-button {
    padding: 12px 20px;
    border: none;
    background: none;
    cursor: pointer;
    font-size: 13px;
    font-weight: 500;
    color: #6c757d;
    transition: all 0.2s;
    border-bottom: 2px solid transparent;
    white-space: nowrap;

    &:hover {
        color: #fd7e14;
    }

    &.active {
        color: #fd7e14;
        border-bottom-color: #fd7e14;
        background: white;
    }
}

.modal-body {
    padding: 24px;
    overflow-y: auto;
    flex: 1;
}

/* Contenido del Administrador */
.admin-content {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.user-info-section {
    display: flex;
    gap: 24px;
    padding-bottom: 20px;
    border-bottom: 1px solid #e9ecef;
}

.user-avatar {
    flex-shrink: 0;
}

.avatar-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    font-weight: 600;
}

.user-fields {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.field-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.field {
    display: flex;
    flex-direction: column;
    gap: 6px;

    label {
        font-size: 12px;
        font-weight: 500;
        color: #495057;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    input,
    select {
        padding: 8px 12px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.2s;

        &:focus {
            outline: none;
            border-color: #fd7e14;
            box-shadow: 0 0 0 3px rgba(253, 126, 20, 0.1);
        }
    }
}

.company-section,
.permissions-section {
    h4 {
        margin: 0 0 16px 0;
        font-size: 16px;
        font-weight: 600;
        color: #212529;
    }
}

.permissions-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}

/* Contenido de Contas */
.contas-content {
    padding: 8px 0;
}

.contas-config {
    h4 {
        margin: 0 0 20px 0;
        font-size: 16px;
        font-weight: 600;
        color: #fd7e14;
    }
}

.config-options {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    font-size: 14px;

    input {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }
}

/* Modal Footer */
.modal-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 24px;
    border-top: 1px solid #e9ecef;
    background: #f8f9fa;
    border-radius: 0 0 12px 12px;
}

.actions-right {
    display: flex;
    gap: 12px;
}

.btn-primary {
    background: #fd7e14;
    color: white;
    border: none;
    padding: 8px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    transition: background 0.2s;

    &:hover {
        background: #e96b0a;
    }
}

.btn-secondary {
    background: white;
    color: #6c757d;
    border: 1px solid #dee2e6;
    padding: 8px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.2s;

    &:hover {
        background: #f8f9fa;
        border-color: #adb5bd;
    }
}

.btn-danger {
    background: none;
    color: #dc3545;
    border: 1px solid #dc3545;
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.2s;

    &:hover {
        background: #dc3545;
        color: white;
    }
}

/* Responsive */
@media (max-width: 768px) {
    .user-info-section {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .field-row {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .permissions-grid {
        grid-template-columns: 1fr;
    }

    .modal-footer {
        flex-direction: column-reverse;
        gap: 12px;

        .btn-danger {
            width: 100%;
        }

        .actions-right {
            width: 100%;

            button {
                flex: 1;
            }
        }
    }
}
</style>