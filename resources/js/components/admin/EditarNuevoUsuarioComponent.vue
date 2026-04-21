<template>
    <div v-if="visible" class="modal-overlay">
        <div class="modal">
            <!-- Header -->
            <div class="modal-header">
                <h3>Editar usuario — {{ form.name || 'Usuario' }}</h3>
                <button class="close-btn" @click="$emit('close')">×</button>
            </div>

            <!-- Tabs -->
            <div class="tabs">
                <button 
                    v-for="tab in tabs" 
                    :key="tab.key" 
                    :class="{ active: activeTab === tab.key }" 
                    @click="activeTab = tab.key"
                >
                    {{ tab.label }}
                </button>
            </div>

            <!-- Contenido del modal -->
            <div class="modal-body">
                <!-- TAB: Datos personales -->
                <div v-if="activeTab === 'personal'" class="personal-data">
                    <div class="two-columns">
                        <!-- Columna izquierda: Avatar -->
                        <div class="avatar-section">
                            <div class="avatar-circle">
                                {{ initials }}
                            </div>
                            <button type="button" class="btn-change-photo">Cambiar foto</button>
                        </div>

                        <!-- Columna derecha: Campos -->
                        <div class="fields-section">
                            <div class="field">
                                <label>Nombre completo</label>
                                <input v-model="form.name" type="text" placeholder="María García" />
                            </div>

                            <div class="field">
                                <label>Teléfono</label>
                                <input v-model="form.phone" type="tel" placeholder="+34 961 234 567" />
                            </div>

                            <div class="field">
                                <label>Email</label>
                                <input v-model="form.email" type="email" placeholder="email@example.com" />
                            </div>

                            <div class="field">
                                <label>Correo electrónico</label>
                                <input v-model="form.email2" type="email" placeholder="maría@textil.com" />
                            </div>

                            <div class="field">
                                <label>Cargo</label>
                                <input v-model="form.position" type="text" placeholder="Responsable de Compras" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB: Acceso y rol -->
                <div v-if="activeTab === 'access'" class="tab-content">
                    <div class="field">
                        <label>Rol de usuario</label>
                        <select v-model="form.role">
                            <option value="admin">Administrador</option>
                            <option value="operator">Operador</option>
                            <option value="client">Cliente</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>Estado de cuenta</label>
                        <select v-model="form.status">
                            <option value="active">Activo</option>
                            <option value="inactive">Inactivo</option>
                            <option value="suspended">Suspendido</option>
                        </select>
                    </div>
                </div>

                <!-- TAB: Empresa -->
                <div v-if="activeTab === 'company'" class="tab-content">
                    <div class="field">
                        <label>Nombre de empresa</label>
                        <input v-model="form.companyName" type="text" />
                    </div>
                    <div class="field">
                        <label>NIF/CIF</label>
                        <input v-model="form.cif" type="text" />
                    </div>
                    <div class="field">
                        <label>Dirección</label>
                        <input v-model="form.address" type="text" />
                    </div>
                </div>

                <!-- TAB: Permisos -->
                <div v-if="activeTab === 'permissions'" class="tab-content">
                    <div class="permissions-list">
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

const tabs = [
    { key: 'personal', label: 'Datos personales' },
    { key: 'access', label: 'Acceso y rol' },
    { key: 'company', label: 'Empresa' },
    { key: 'permissions', label: 'Permisos' }
]

const activeTab = ref('personal')

// Estructura completa del formulario
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
        // Validación básica
        if (!form.value.name) {
            alert('El nombre completo es requerido')
            return
        }

        // Ejemplo con axios a Laravel
        // if (form.value.id) {
        //     await axios.put(`/api/users/${form.value.id}`, form.value)
        // } else {
        //     await axios.post('/api/users', form.value)
        // }

        emit('saved', { ...form.value })
        
        // Opcional: cerrar modal después de guardar
        // emit('close')
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
        // Ejemplo con axios
        // await axios.delete(`/api/users/${form.value.id}`)

        emit('deleted', form.value.id)
        emit('close') // Cerrar modal después de eliminar
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
    width: 750px;
    max-width: 90%;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    display: flex;
    flex-direction: column;
    max-height: 90vh;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid #e9ecef;

    h3 {
        margin: 0;
        font-size: 1.1rem;
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

.tabs {
    display: flex;
    gap: 4px;
    padding: 0 24px;
    border-bottom: 1px solid #e9ecef;
    background: #f8f9fa;

    button {
        padding: 12px 20px;
        border: none;
        background: none;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        color: #6c757d;
        transition: all 0.2s;
        border-bottom: 2px solid transparent;

        &:hover {
            color: #fd7e14;
        }

        &.active {
            color: #fd7e14;
            border-bottom-color: #fd7e14;
            background: white;
        }
    }
}

.modal-body {
    padding: 24px;
    overflow-y: auto;
    flex: 1;
}

.two-columns {
    display: grid;
    grid-template-columns: 120px 1fr;
    gap: 24px;
}

.avatar-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
}

.avatar-circle {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    font-weight: 600;
}

.btn-change-photo {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 6px 12px;
    font-size: 12px;
    cursor: pointer;
    color: #495057;

    &:hover {
        background: #e9ecef;
    }
}

.fields-section {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.field {
    display: flex;
    flex-direction: column;
    gap: 6px;

    label {
        font-size: 13px;
        font-weight: 500;
        color: #495057;
    }

    input, select {
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

.tab-content {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.permissions-list {
    display: flex;
    flex-direction: column;
    gap: 12px;

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
}

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
</style>