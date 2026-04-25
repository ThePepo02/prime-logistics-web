<template>
    <div v-if="show" class="overlay">
        <div class="modal">
            <div class="icon">
                🗑️
            </div>

            <h3>¿Eliminar usuario?</h3>

            <p>
                Esto eliminará permanentemente a
                <strong>{{ userName }}</strong>.
                Esta acción no se puede deshacer.
            </p>

            <div class="actions">
                <button class="btn cancel" @click="$emit('close')">
                    Cancelar
                </button>

                <button class="btn delete" @click="confirmDelete">
                    Sí, eliminar
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
        await axios.delete(`/api/users/${props.userId}`)
        emit('deleted')
        emit('close')
    } catch (error) {
        console.error(error)
    }
}
</script>

<style scoped>
.overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal {
    background: white;
    padding: 24px;
    border-radius: 12px;
    width: 350px;
    text-align: center;
}

.icon {
    font-size: 30px;
    margin-bottom: 10px;
}

.actions {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
}

.btn {
    padding: 8px 16px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
}

.cancel {
    background: #eee;
}

.delete {
    background: #e74c3c;
    color: white;
}
</style>