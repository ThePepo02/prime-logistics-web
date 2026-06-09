<template>
    <article class="panel">
        <div class="incoterm-header">
            <div>
                <span class="badge">{{ incoterm.codi }}</span>
                <strong>{{ incoterm.nom }}</strong>
            </div>
            <button class="steps-btn" @click="$emit('abrir-steps', incoterm)">
                Editar steps ({{ incoterm.pasos.length }})
            </button>
        </div>

        <div v-if="incoterm.pasos.length > 0" class="steps-asignados">
            <span v-for="stepId in incoterm.pasos" :key="stepId" class="step-tag">
                {{ nombreStep(stepId) }}
            </span>
        </div>
        <div v-else class="sin-steps">
            Sin steps asignados
        </div>
    </article>
</template>

<script setup>
const props = defineProps({
    incoterm: Object,
    steps: Array,
});

defineEmits(['abrir-steps']);

const nombreStep = (stepId) => {
    const step = props.steps.find(s => s.id == stepId);
    return step.nom;
};
</script>

<style scoped>
.panel { background: #f7f9fc; border: 1px solid #d6dee8; border-radius: 9px; padding: 0.8rem; font-family: 'Manrope', sans-serif; color: #152238; }
.incoterm-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.8rem; }
.incoterm-header div { display: flex; align-items: center; gap: 0.6rem; }
.badge { background: #0a2243; color: #fff; padding: 0.2rem 0.6rem; border-radius: 6px; font-size: 0.7rem; font-weight: 800; }
.steps-btn { background: #2d65b0; color: #fff; border: 0; border-radius: 8px; padding: 0.4rem 1rem; font-size: 0.72rem; font-weight: 700; cursor: pointer; }
.steps-btn:hover { background: #1e4d8c; }
.steps-asignados { display: flex; flex-wrap: wrap; gap: 0.4rem; }
.step-tag { background: #fff4eb; border: 1px solid #ff7e26; color: #c05a10; border-radius: 6px; padding: 0.2rem 0.6rem; font-size: 0.7rem; font-weight: 700; }
.sin-steps { font-size: 0.75rem; color: #a0b0c5; font-style: italic; }
</style>