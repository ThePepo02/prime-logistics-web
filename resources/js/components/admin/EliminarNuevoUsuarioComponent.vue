<template>
    <div v-if="show" class="delete-overlay" @click.self="$emit('close')">
        <div class="delete-modal">
            <!-- Icono de advertencia/eliminación -->
            <div class="delete-icon">
                <svg class="icon-trash" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 7H20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    <path d="M10 11V16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    <path d="M14 11V16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    <path d="M5 7L6 19C6 20.1046 6.89543 21 8 21H16C17.1046 21 18 20.1046 18 19L19 7"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    <path d="M9 7V4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V7" stroke="currentColor"
                        stroke-width="1.5" stroke-linecap="round" />
                </svg>
            </div>

            <!-- Título principal -->
            <h3 class="delete-title">Eliminar usuario admin</h3>

            <!-- Mensaje de confirmación -->
            <p class="delete-message">
                ¿Estás seguro de que deseas eliminar a
                <strong class="user-name">{{ userName }}</strong>?
            </p>

            <p class="delete-warning">
                ⚠️ Esta acción eliminará permanentemente al usuario y todos sus datos asociados.
                No se puede deshacer.
            </p>

            <!-- Botones de acción -->
            <div class="delete-actions">
                <button class="btn-cancel" @click="$emit('close')">
                    Cancelar
                </button>
                <button class="btn-delete" @click="confirmDelete">
                    Sí, eliminar usuario
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import axios from 'axios'

const props = defineProps({
    show: Boolean,
    userId: Number,
    userName: String
})

const emit = defineEmits(['close', 'deleted'])

const confirmDelete = async () => {
    try {
        // API call to delete user
        const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api/v1'
        await axios.delete(`${API_URL}/users/${props.userId}`)

        // Emit success event
        emit('deleted', props.userId)
        emit('close')
    } catch (error) {
        console.error('Error al eliminar usuario:', error)
        // Mostrar mensaje de error si es necesario
        alert('Error al eliminar el usuario. Inténtalo de nuevo.')
    }
}
</script>

<style scoped>
.delete-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(2px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    animation: fadeIn 0.2s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

.delete-modal {
    background: white;
    border-radius: 20px;
    width: 450px;
    max-width: 90%;
    padding: 32px 28px;
    text-align: center;
    box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.3);
    animation: slideUp 0.3s ease;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Icono de eliminar */
.delete-icon {
    margin-bottom: 20px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    border-radius: 50%;
    color: #dc2626;
}

.icon-trash {
    width: 32px;
    height: 32px;
}

/* Título */
.delete-title {
    font-size: 22px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 12px 0;
    letter-spacing: -0.3px;
}

/* Mensaje principal */
.delete-message {
    font-size: 16px;
    color: #334155;
    margin: 0 0 12px 0;
    line-height: 1.5;
}

.user-name {
    color: #dc2626;
    font-weight: 600;
    background: #fee2e2;
    padding: 2px 8px;
    border-radius: 6px;
    display: inline-block;
}

/* Advertencia */
.delete-warning {
    font-size: 13px;
    color: #64748b;
    background: #f8fafc;
    padding: 12px 16px;
    border-radius: 12px;
    margin: 16px 0 0 0;
    border-left: 3px solid #dc2626;
    text-align: left;
    line-height: 1.4;
}

/* Botones */
.delete-actions {
    display: flex;
    gap: 12px;
    margin-top: 28px;
}

.btn-cancel,
.btn-delete {
    flex: 1;
    padding: 12px 20px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    border: none;
    font-family: inherit;
}

.btn-cancel {
    background: #f1f5f9;
    color: #475569;
}

.btn-cancel:hover {
    background: #e2e8f0;
    transform: translateY(-1px);
}

.btn-delete {
    background: #dc2626;
    color: white;
    box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3);
}

.btn-delete:hover {
    background: #b91c1c;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.4);
}

.btn-cancel:active,
.btn-delete:active {
    transform: translateY(0);
}

/* Responsive */
@media (max-width: 480px) {
    .delete-modal {
        padding: 24px 20px;
    }

    .delete-title {
        font-size: 20px;
    }

    .delete-message {
        font-size: 14px;
    }

    .delete-actions {
        flex-direction: column;
        gap: 10px;
    }

    .btn-cancel,
    .btn-delete {
        padding: 10px 16px;
    }
}
</style>