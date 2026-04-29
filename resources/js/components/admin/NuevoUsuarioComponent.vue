<template>
    <div class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <h3>Nuevo Usuario</h3>
                <button class="close-btn" @click="$emit('close')">X</button>
            </div>

            <p class="subtitle">Completa los datos para crear el acceso</p>

            <form @submit.prevent="onSubmit">
                <div class="grid">
                    <!-- Nombre completo -->
                    <div class="full-width">
                        <label>Nombre completo *</label>
                        <input v-model="form.name" placeholder="Ej: Ana López" required />
                    </div>

                    <!-- Empresa -->
                    <div class="full-width">
                        <label>Empresa *</label>
                        <select v-model="form.company" required>
                            <option disabled value="">-- Selecciona --</option>
                            <option value="empresa1">Empresa 1</option>
                            <option value="empresa2">Empresa 2</option>
                        </select>
                    </div>

                    <!-- Rol del usuario -->
                    <div class="full-width">
                        <label>Rol del usuario *</label>
                        <div class="role-options">
                            <div class="role" :class="{ active: form.role === 'admin' }" @click="form.role = 'admin'">
                                Administrador
                            </div>
                            <div class="role" :class="{ active: form.role === 'operador' }" @click="form.role = 'operador'">
                                Operador
                            </div>
                            <div class="role" :class="{ active: form.role === 'cliente' }" @click="form.role = 'cliente'">
                                Cliente
                            </div>
                        </div>
                    </div>

                    <!-- Operador (Electivo-agrícola) - según diseño -->
                    <div class="full-width">
                        <label>Operador</label>
                        <select v-model="form.operador">
                            <option value="">Selecciona un operador</option>
                            <option value="electivo-agricola">Electivo-agrícola</option>
                        </select>
                        <small class="help-text">Sólo se puede cerrar</small>
                    </div>

                    <!-- Enviar credenciales -->
                    <div class="full-width checkbox">
                        <label class="checkbox-label">
                            <input type="checkbox" v-model="form.sendEmail" />
                            <span>Enviar credenciales de acceso por email al crear</span>
                        </label>
                    </div>

                    <!-- Botones -->
                    <div class="full-width footer">
                        <button type="button" class="cancel" @click="$emit('close')">Cancelar</button>
                        <button type="submit" class="submit">Crear Usuario</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { reactive } from 'vue'

const emit = defineEmits(['create-user', 'close'])

const form = reactive({
    name: '',
    company: '',
    role: '',
    operador: '',
    sendEmail: true
})

const onSubmit = () => {
    if (!form.name || !form.company || !form.role) {
        alert('Por favor completa todos los campos obligatorios (*)')
        return
    }
    
    emit('create-user', { ...form })
    
    // Opcional: resetear formulario después de crear
    // Object.assign(form, { name: '', company: '', role: '', operador: '', sendEmail: true })
}
</script>

<style lang="scss" scoped>
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal {
    width: 550px;
    max-width: 90%;
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;

    h3 {
        margin: 0;
        font-size: 1.25rem;
    }

    .close-btn {
        background: none;
        border: none;
        font-size: 1.25rem;
        cursor: pointer;
        color: #999;
        
        &:hover {
            color: #333;
        }
    }
}

.subtitle {
    font-size: 13px;
    color: #777;
    margin-bottom: 20px;
}

.grid {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.full-width {
    width: 100%;
}

label {
    display: block;
    margin-bottom: 6px;
    font-weight: 500;
    font-size: 14px;
}

input, select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    box-sizing: border-box;

    &:focus {
        outline: none;
        border-color: orange;
    }
}

.role-options {
    display: flex;
    gap: 12px;
}

.role {
    flex: 1;
    padding: 10px;
    border: 1px solid #ddd;
    text-align: center;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
    background: white;

    &:hover {
        border-color: orange;
    }

    &.active {
        border: 2px solid orange;
        background: #fff3e8;
        font-weight: 500;
    }
}

.checkbox {
    margin: 8px 0;

    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        font-weight: normal;

        input {
            width: auto;
            cursor: pointer;
        }

        span {
            cursor: pointer;
        }
    }
}

.help-text {
    display: block;
    font-size: 11px;
    color: #999;
    margin-top: 4px;
}

.footer {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 8px;
}

.cancel {
    background: #f0f0f0;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;

    &:hover {
        background: #e0e0e0;
    }
}

.submit {
    background: orange;
    color: white;
    border: none;
    padding: 10px 24px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;

    &:hover {
        background: #e69500;
    }
}
</style>