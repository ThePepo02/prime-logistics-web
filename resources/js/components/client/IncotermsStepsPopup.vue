<template>
    <div class="overlay" @click.self="$emit('cerrar')">
        <div class="popup">
            <div class="popup-header">
                <div>
                    <span class="badge">{{ incoterm.codi }}</span>
                    <strong>{{ incoterm.nom }}</strong>
                </div>
                <button class="cerrar-btn" @click="$emit('cerrar')">✕</button>
            </div>

            <p class="popup-subtitle">Marca los pasos de tracking para este incoterm</p>

            <div class="steps-grid">
                <label
                    v-for="step in steps"
                    :key="step.id"
                    class="step-check"
                    :class="{ active: pasosLocales.includes(String(step.id)) }"
                >
                    <input
                        type="checkbox"
                        :value="step.id"
                        :checked="pasosLocales.includes(String(step.id))"
                        @change="toggleStep(step.id)"
                    />
                    {{ step.ordre }}. {{ step.nom }}
                </label>
            </div>

            <div class="popup-footer">
                <button class="cancelar-btn" @click="$emit('cerrar')">Cancelar</button>
                <button class="guardar-btn" @click="guardar">Guardar</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
    incoterm: Object,
    steps: Array,
});

const emit = defineEmits(['cerrar', 'guardar']);

const pasosIniciales = [];

for (const p of props.incoterm.pasos) {
    pasosIniciales.push(String(p));
}

const pasosLocales = ref(pasosIniciales);


const toggleStep = (stepId) => {
    const id = String(stepId);
    const idx = pasosLocales.value.indexOf(id);
    if (idx === -1) {
        pasosLocales.value.push(id);
    } else {
        pasosLocales.value.splice(idx, 1);
    }
};

const guardar = () => {
    // Actualiza los pasos y avisa al padre para que haga el PUT
    props.incoterm.pasos = pasosLocales.value;
    emit('guardar', props.incoterm);
};
</script>

<style scoped>
.overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 100; }
.popup { background: #fff; border-radius: 12px; padding: 1.5rem; width: 560px; max-width: 95vw; max-height: 90vh; overflow-y: auto; font-family: 'Manrope', sans-serif; color: #152238; }
.popup-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; }
.popup-header div { display: flex; align-items: center; gap: 0.6rem; }
.badge { background: #0a2243; color: #fff; padding: 0.2rem 0.6rem; border-radius: 6px; font-size: 0.7rem; font-weight: 800; }
.cerrar-btn { background: none; border: 1px solid #d6dee8; border-radius: 6px; width: 28px; height: 28px; cursor: pointer; font-size: 0.8rem; color: #607089; }
.cerrar-btn:hover { background: #f0f3f8; }
.popup-subtitle { font-size: 0.78rem; color: #607089; margin: 0 0 1rem; }
.steps-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.5rem; margin-bottom: 1.2rem; }
.step-check { display: flex; align-items: center; gap: 0.4rem; font-size: 0.75rem; padding: 0.5rem 0.7rem; border-radius: 7px; border: 1px solid #d6dee8; background: #f7f9fc; cursor: pointer; }
.step-check.active { border-color: #ff7e26; background: #fff4eb; color: #c05a10; font-weight: 700; }
.step-check input { accent-color: #ff7e26; }
.popup-footer { display: flex; justify-content: flex-end; gap: 0.6rem; border-top: 1px solid #d6dee8; padding-top: 1rem; }
.cancelar-btn { background: #f0f3f8; border: 1px solid #d6dee8; border-radius: 8px; padding: 0.5rem 1.2rem; font-size: 0.78rem; font-weight: 700; cursor: pointer; color: #607089; }
.cancelar-btn:hover { background: #e0e8f0; }
.guardar-btn { background: #ff7e26; color: #fff; border: 0; border-radius: 8px; padding: 0.5rem 1.2rem; font-size: 0.78rem; font-weight: 700; cursor: pointer; }
.guardar-btn:hover { background: #e56d0f; }
</style>