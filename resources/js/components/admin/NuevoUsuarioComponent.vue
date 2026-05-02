<template>
    <div class="modal-overlay" @click.self="$emit('close')">
        <div class="modal-container">
            <!-- Modal Principal -->
            <div class="modal">
                <div class="modal-header">
                    <h3>Nuevo Usuario admin</h3>
                    <button class="close-btn" @click="$emit('close')">×</button>
                </div>

                <p class="subtitle">Completa los datos para crear el usuario</p>

                <form @submit.prevent="crearUsuario">
                    <!-- Nombre completo -->
                    <div class="form-group">
                        <label>Nombre completo:</label>
                        <input v-model="form.nombre_completo" type="text" placeholder="C. Arri Lúdice" required />
                    </div>

                    <!-- Correo electrónico -->
                    <div class="form-group">
                        <label>Correo electrónico:</label>
                        <input v-model="form.correo" type="email" placeholder="c.ludice@example.com" required />
                    </div>

                    <!-- Empresa -->
                    <div class="form-group">
                        <label>Empresa:</label>
                        <select v-model="form.empresa_id" required>
                            <option disabled value="">Seleccione una empresa</option>
                            <option v-for="empresa in empresas" :key="empresa.id" :value="empresa.id">
                                {{ empresa.nombre }}
                            </option>
                        </select>
                    </div>

                    <!-- Contraseña inicial -->
                    <div class="form-group">
                        <label>Contraseña inicial:</label>
                        <div class="password-field">
                            <input :type="mostrarPassword ? 'text' : 'password'" v-model="form.password"
                                placeholder="********" required />
                            <button type="button" class="toggle-password" @click="mostrarPassword = !mostrarPassword">
                                {{ mostrarPassword ? '👁️' : '👁️‍🗨️' }}
                            </button>
                        </div>
                    </div>

                    <!-- Nota del usuario -->
                    <div class="form-group">
                        <label>Nota del usuario:</label>
                        <div class="note-actions">
                            <button type="button" class="note-btn" @click="atestiguarPassword">
                                Atestiguar contraseña con el código
                            </button>
                            <button type="button" class="note-btn" @click="generarPassword">
                                Generar contraseña
                            </button>
                        </div>
                    </div>

                    <!-- Enviar correo electrónico -->
                    <div class="form-group checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" v-model="form.enviar_correo" />
                            <span>Enviar correo electrónico por correo con el error</span>
                        </label>
                    </div>

                    <!-- Botones de acción -->
                    <div class="form-actions">
                        <button type="button" class="btn-cancelar" @click="$emit('close')">
                            Cerrar
                        </button>
                        <button type="submit" class="btn-crear" :disabled="creando">
                            {{ creando ? 'Creando...' : 'Crear Usuario' }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Menú Lateral (según diseño de la imagen) -->
            <div class="side-panel">
                <div class="side-menu">
                    <h4>Menu Principal</h4>
                    <ul>
                        <li><a href="#" @click.prevent>Administración</a></li>
                        <li><a href="#" @click.prevent>Servicios</a></li>
                        <li><a href="#" @click.prevent>Contabilidad</a></li>
                        <li><a href="#" @click.prevent>Recursos Humanos</a></li>
                        <li><a href="#" @click.prevent>Empresas</a></li>
                        <li><a href="#" @click.prevent>Datos Personales</a></li>
                        <li><a href="#" @click.prevent>Noticias</a></li>
                        <li><a href="#" @click.prevent>Aplicaciones</a></li>
                        <li><a href="#" @click.prevent>Portal de usuario</a></li>
                    </ul>
                </div>

                <div class="side-actions">
                    <button class="side-btn" @click="$emit('close')">Voltar a inicio</button>
                    <button class="side-btn logout" @click="cerrarSesion">Salir</button>
                </div>

                <!-- Notificaciones -->
                <div class="notifications-panel">
                    <h4>Notificaciones</h4>
                    <div class="notification-numbers">
                        <span class="notif-number">10</span>
                        <span class="notif-number">11</span>
                        <span class="notif-number">12</span>
                        <span class="notif-number">13</span>
                        <span class="notif-number">14</span>
                    </div>
                    <div class="notification-list">
                        <p class="empty-notifications">No hay notificaciones disponibles.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toast de notificación -->
        <div v-if="toast.show" :class="['toast-notification', toast.type]">
            {{ toast.message }}
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import axios from 'axios'

// Props y Emits
const emit = defineEmits(['close', 'usuario-creado'])

// Estado reactivo
const creando = ref(false)
const mostrarPassword = ref(false)
const empresas = ref([])
const cargandoEmpresas = ref(false)

// Toast notification
const toast = ref({
    show: false,
    message: '',
    type: 'success'
})

// Formulario
const form = reactive({
    nombre_completo: '',
    correo: '',
    empresa_id: '',
    password: '',
    enviar_correo: false
})

// Métodos de notificación
const mostrarToast = (message, type = 'success') => {
    toast.value = {
        show: true,
        message,
        type
    }
    setTimeout(() => {
        toast.value.show = false
    }, 3000)
}

// Cargar empresas desde API
const cargarEmpresas = async () => {
    cargandoEmpresas.value = true
    try {
        const response = await axios.get(`${API_BASE_URL}/empresas`)
        if (response.data.success) {
            empresas.value = response.data.data
        }
    } catch (error) {
        console.error('Error al cargar empresas:', error)
        // Datos mock para desarrollo
        empresas.value = [
            { id: 1, nombre: 'Empresa Alpha' },
            { id: 2, nombre: 'Empresa Beta' },
            { id: 3, nombre: 'Empresa Gamma' }
        ]
    } finally {
        cargandoEmpresas.value = false
    }
}

// Generar contraseña aleatoria
const generarPassword = () => {
    const caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*'
    let password = ''
    for (let i = 0; i < 12; i++) {
        password += caracteres.charAt(Math.floor(Math.random() * caracteres.length))
    }
    form.password = password
    mostrarToast('Contraseña generada exitosamente', 'success')
}

// Atestiguar contraseña (mostrar confirmación)
const atestiguarPassword = () => {
    if (form.password) {
        alert(`Contraseña actual: ${form.password}\n\nPor favor, comparte esta contraseña de forma segura con el usuario.`)
    } else {
        alert('Primero debe generar o ingresar una contraseña')
    }
}

// Crear usuario
const crearUsuario = async () => {
    // Validaciones
    if (!form.nombre_completo.trim()) {
        mostrarToast('El nombre completo es requerido', 'error')
        return
    }
    if (!form.correo.trim()) {
        mostrarToast('El correo electrónico es requerido', 'error')
        return
    }
    if (!form.empresa_id) {
        mostrarToast('Debe seleccionar una empresa', 'error')
        return
    }
    if (!form.password.trim()) {
        mostrarToast('La contraseña es requerida', 'error')
        return
    }

    creando.value = true
    try {
        const response = await axios.post(`${API_BASE_URL}/usuarios`, {
            nombre: form.nombre_completo.split(' ')[0],
            apellido_paterno: form.nombre_completo.split(' ')[1] || '',
            apellido_materno: form.nombre_completo.split(' ')[2] || '',
            correo: form.correo,
            empresa_id: form.empresa_id,
            password: form.password,
            enviar_correo: form.enviar_correo,
            estado: 'Active',
            tipo_usuario: 'Usuario',
            nivel_acceso: 1
        })

        if (response.data.success) {
            mostrarToast('Usuario creado exitosamente', 'success')
            emit('usuario-creado', response.data.data)

            // Resetear formulario
            form.nombre_completo = ''
            form.correo = ''
            form.empresa_id = ''
            form.password = ''
            form.enviar_correo = false

            // Cerrar modal después de 1.5 segundos
            setTimeout(() => {
                emit('close')
            }, 1500)
        }
    } catch (error) {
        console.error('Error al crear usuario:', error)
        if (error.response?.data?.errors) {
            const errors = error.response.data.errors
            const firstError = Object.values(errors)[0]
            mostrarToast(firstError[0], 'error')
        } else {
            mostrarToast(error.response?.data?.message || 'Error al crear el usuario', 'error')
        }
    } finally {
        creando.value = false
    }
}

// Cerrar sesión
const cerrarSesion = () => {
    if (confirm('¿Estás seguro de que deseas cerrar sesión?')) {
        // Limpiar tokens de autenticación
        localStorage.removeItem('token')
        localStorage.removeItem('user')
        // Redirigir al login o recargar
        window.location.href = '/login'
    }
}

// Lifecycle
onMounted(() => {
    cargarEmpresas()
})
</script>

<style lang="scss" scoped>
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.6);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

.modal-container {
    display: flex;
    gap: 0;
    max-width: 1200px;
    width: 95%;
    max-height: 90vh;
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: slideUp 0.3s ease;
}

@keyframes slideUp {
    from {
        transform: translateY(30px);
        opacity: 0;
    }

    to {
        transform: translateY(0);
        opacity: 1;
    }
}

/* Modal Principal (Derecha) */
.modal {
    flex: 2;
    padding: 24px;
    overflow-y: auto;
    background: white;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
    padding-bottom: 12px;
    border-bottom: 2px solid #f0f0f0;

    h3 {
        margin: 0;
        font-size: 1.4rem;
        color: #2c3e50;
        font-weight: 600;
    }

    .close-btn {
        background: none;
        border: none;
        font-size: 1.8rem;
        cursor: pointer;
        color: #999;
        transition: all 0.2s;
        line-height: 1;

        &:hover {
            color: #e74c3c;
            transform: scale(1.1);
        }
    }
}

.subtitle {
    font-size: 0.85rem;
    color: #7f8c8d;
    margin-bottom: 24px;
    padding-left: 4px;
}

.form-group {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    font-size: 0.85rem;
    color: #2c3e50;
}

input,
select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 0.9rem;
    transition: all 0.2s;
    box-sizing: border-box;

    &:focus {
        outline: none;
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
    }
}

.password-field {
    position: relative;
    display: flex;
    align-items: center;

    input {
        flex: 1;
        padding-right: 40px;
    }

    .toggle-password {
        position: absolute;
        right: 10px;
        background: none;
        border: none;
        cursor: pointer;
        font-size: 1.1rem;
        padding: 4px;

        &:hover {
            opacity: 0.7;
        }
    }
}

.note-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;

    .note-btn {
        background: #ecf0f1;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.85rem;
        transition: all 0.2s;

        &:hover {
            background: #3498db;
            color: white;
            transform: translateY(-1px);
        }
    }
}

.checkbox-group {
    margin: 16px 0;

    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        font-weight: normal;

        input {
            width: auto;
            cursor: pointer;
        }

        span {
            cursor: pointer;
            font-size: 0.85rem;
            color: #2c3e50;
        }
    }
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 24px;
    padding-top: 16px;
    border-top: 1px solid #ecf0f1;
}

.btn-cancelar {
    background: #ecf0f1;
    border: none;
    padding: 10px 24px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.9rem;
    transition: all 0.2s;

    &:hover {
        background: #bdc3c7;
        transform: translateY(-1px);
    }
}

.btn-crear {
    background: #e67e22;
    color: white;
    border: none;
    padding: 10px 28px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.9rem;
    font-weight: 600;
    transition: all 0.2s;

    &:hover:not(:disabled) {
        background: #d35400;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(230, 126, 34, 0.3);
    }

    &:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
}

/* Panel Lateral Izquierdo */
.side-panel {
    width: 280px;
    background: #2c3e50;
    color: #ecf0f1;
    display: flex;
    flex-direction: column;
    overflow-y: auto;
}

.side-menu {
    padding: 20px;
    border-bottom: 1px solid #34495e;

    h4 {
        margin: 0 0 15px 0;
        font-size: 1rem;
        color: #3498db;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    ul {
        list-style: none;
        padding: 0;
        margin: 0;

        li {
            margin-bottom: 8px;

            a {
                color: #bdc3c7;
                text-decoration: none;
                font-size: 0.85rem;
                display: block;
                padding: 6px 0;
                transition: all 0.2s;

                &:hover {
                    color: white;
                    padding-left: 8px;
                }
            }
        }
    }
}

.side-actions {
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    border-bottom: 1px solid #34495e;

    .side-btn {
        background: #34495e;
        color: #ecf0f1;
        border: none;
        padding: 10px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.85rem;
        transition: all 0.2s;

        &:hover {
            background: #3d5a73;
            transform: translateX(4px);
        }

        &.logout:hover {
            background: #c0392b;
        }
    }
}

.notifications-panel {
    padding: 20px;
    flex: 1;

    h4 {
        margin: 0 0 15px 0;
        font-size: 1rem;
        color: #3498db;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
}

.notification-numbers {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 20px;

    .notif-number {
        background: #34495e;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        color: #3498db;
        cursor: pointer;
        transition: all 0.2s;

        &:hover {
            background: #3d5a73;
            transform: scale(1.05);
        }
    }
}

.notification-list {
    .empty-notifications {
        color: #7f8c8d;
        font-size: 0.8rem;
        text-align: center;
        padding: 20px 0;
        font-style: italic;
    }
}

/* Toast Notification */
.toast-notification {
    position: fixed;
    bottom: 20px;
    right: 20px;
    padding: 12px 20px;
    border-radius: 8px;
    color: white;
    font-size: 0.85rem;
    font-weight: 500;
    z-index: 2000;
    animation: slideInRight 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.toast-notification.success {
    background: #27ae60;
}

.toast-notification.error {
    background: #e74c3c;
}

@keyframes slideInRight {
    from {
        transform: translateX(100px);
        opacity: 0;
    }

    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Scrollbar personalizada */
.modal::-webkit-scrollbar,
.side-panel::-webkit-scrollbar {
    width: 6px;
}

.modal::-webkit-scrollbar-track,
.side-panel::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.modal::-webkit-scrollbar-thumb,
.side-panel::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}

.modal::-webkit-scrollbar-thumb:hover,
.side-panel::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Responsive */
@media (max-width: 768px) {
    .modal-container {
        flex-direction: column;
        width: 95%;
        max-height: 85vh;
    }

    .side-panel {
        width: 100%;
        flex-direction: row;
        flex-wrap: wrap;

        .side-menu,
        .side-actions,
        .notifications-panel {
            flex: 1;
            min-width: 200px;
        }
    }

    .note-actions {
        flex-direction: column;

        .note-btn {
            width: 100%;
        }
    }

    .form-actions {
        flex-direction: column;

        button {
            width: 100%;
        }
    }
}
</style>