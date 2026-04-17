<template>
    <div v-if="visible" class="modal-overlay">
        <div class="modal">
            <!-- Header -->
            <div class="modal-header">
                <h3>Editar usuario {{ form.name }}</h3>
                <button class="close" @click="$emit('close')">x</button>
            </div>

            <!-- Tabs -->
            <div class="tabs">
                <button v-for="tab in tabs" :key="tab" :class="{ active: activeTab === tab }" @click="activeTab = tab">{{
                    tab }}</button>
            </div>
            <!-- Contenido -->
            <div class="modeal-body">
                <div v-if="activeTab === 'Datos personales'" class="grid">
                    <div class="avatar">
                        <div class="circle">{{ initials }}</div>
                        <button class="btn-light">Cambiar foto</button>
                    </div>

                    <div class="field">
                        <label>Nombre completo*</label>
                        <input v-model="form.name" type="text" />
                    </div>

                    <div class="field">
                        <label>Correo electrónico*</label>
                        <input v-model="form.email" type="text" />
                    </div>

                    <div class="field">
                        <label>Teléfono*</label>
                        <input v-model="form.phone" type="text" />
                    </div>

                    <div class="field">
                        <label>Cargo*</label>
                        <input v-model="form.role" type="text" />
                    </div>

                    <div v-if="activeTab === 'Acceso y rol'"></div>

                    <div v-if="activeTab === 'Empresa'"></div>

                    <div v-if="activeTab === 'Permisos'"></div>

                    <!-- Footer -->
                    <div class="modal-footer">
                        <button class="danger" @click="deleteUser">Eliminar usuario</button>
                    </div>

                    <div class="actions">
                        <button @click="$emit('close')">Cancelar</button>
                        <button class="primary" @click="save">Guardar cambios</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'

const propiedades = defineProps({
    visible: Boolean,
    user: Object
})

const emit = defineEmits(['close', 'saved', 'deleted'])

const tabs = ['Datos personales', 'Acceso y rol', 'Empresa', 'Permisos']
const activeTab = ref('Datos personales')

const form = ref({
    name: '',
    email: '',
    phone: '',
    role: ''
})

/* identificar cambios en la propiedad user, y copiar los datos
de forma local en el formulario
*/
watch(() => propiedades.user, (u) => {
    if (u) {
        form.value = { ...u }
    }
}, { inmediate: true })

const initials = computed(() => {
    if (!form.value.name) return ''
    return form.value.name
        .split(' ')
        .map(n => n[0])
        .join('')
        .toUpperCase()
})

/* relación JS + BD */
const save = async () => {
    try {
        // ejemplo con axios hacia Laravel
        // await axios.put(`/api/users/${props.user.id}`, form.value)

        emit('saved', form.value)
    } catch (e) {
        console.error(e)
    }
}

/* relación JS + BD */
const deleteUser = async () => {
    if (!confirm('¿Eliminar usuario?')) return

    // await axios.delete(`/api/users/${props.user.id}`)

    emit('deleted', props.user.id)
}
</script>

<style lang="scss" scoped>
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal {
    width: 700px;
    background: white;
    border-radius: 12px;
    overflow: hidden;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    padding: 16px;
    font-weight: bold;
}

.tabs {
    display: flex;
    gap: 10px;
    padding: 0 16px;
    border-bottom: 1px solid #eee;

    button {
        padding: 10px;
        border: none;
        background: none;
        cursor: pointer;

        &.active {
            border-bottom: 2px solid orange;
            font-weight: bold;
        }
    }
}

.modal-body {
    padding: 16px;
}

.grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

.avatar {
    grid-column: span 2;
    display: flex;
    align-items: center;
    gap: 10px;

    .circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #2c6bed;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
    }
}

.field {
    display: flex;
    flex-direction: column;

    input {
        padding: 8px;
        border-radius: 6px;
        border: 1px solid #ccc;
    }
}

.modal-footer {
    display: flex;
    justify-content: space-between;
    padding: 16px;
}

.primary {
    background: orange;
    color: white;
    padding: 8px 12px;
    border: none;
    border-radius: 6px;
}

.danger {
    color: red;
    background: none;
    border: none;
}

.actions {
    display: flex;
    gap: 10px;
}
</style>