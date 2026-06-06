<template>
    <div class="incoterms-shell">
        <aside class="sidebar">
            <div class="brand">
                <img :src="logoSrc" alt="Prime Logistics" />
            </div>

            <p class="section-title">Principal</p>
            <ul class="menu">
                <li><a href="/cliente/dashboard">Dashboard</a></li>
                <li><a href="/cliente/nuevo-pedido">Nuevo Pedido</a></li>
                <li><a href="/cliente/mis-pedidos">Mis Pedidos</a></li>
                <li><a href="/cliente/tracking">Tracking</a></li>
                <li><a href="/cliente/notificaciones">Notificaciones</a></li>
                <li class="active"><a href="/cliente/incoterms">Incoterms</a></li>
            </ul>

            <p class="section-title muted">Cuenta</p>
            <ul class="menu">
                <li>Ajustes</li>
            </ul>

            <div class="user-card">
                <span class="avatar">MG</span>
                <div>
                    <strong>Maria Garcia</strong>
                    <small>Cliente</small>
                </div>
            </div>
        </aside>

        <main class="content">
            <header class="topbar">
                <div>
                    <h1>Mantenimiento de Incoterms</h1>
                    <p>Asigna los pasos de tracking a cada incoterm</p>
                </div>
            </header>

            <div v-if="loading" class="loading">Cargando...</div>
            <div v-if="successMsg" class="success-message">{{ successMsg }}</div>

            <section v-if="!loading" class="incoterms-list">
                <!-- HIJO 1: una tarjeta por cada incoterm -->
                <IncotermsForm
                    v-for="incoterm in incoterms"
                    :key="incoterm.id"
                    :incoterm="incoterm"
                    @guardar="guardar"
                    @abrir-steps="abrirPopup"
                />
            </section>

            <!-- HIJO 2: popup de steps, solo visible cuando popupIncoterm no es null -->
            <IncotermsStepsPopup
                v-if="popupIncoterm"
                :incoterm="popupIncoterm"
                :steps="steps"
                @cerrar="cerrarPopup"
                @guardar="guardar"
            />
        </main>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import IncotermsForm from './IncotermsForm.vue';
import IncotermsStepsPopup from './IncotermsStepsPopup.vue';

const logoSrc = '/images/prime-logistics-logo.svg';
const incoterms = ref([]);
const steps = ref([]);
const loading = ref(true);
const successMsg = ref('');
const popupIncoterm = ref(null); // el incoterm que tiene el popup abierto

// Carga los datos de la API
const loadData = async () => {
    const { data } = await window.axios.get('/client/incoterms');
    incoterms.value = data.incoterms;
    steps.value = data.steps;
    loading.value = false;
};

// Abre el popup para un incoterm concreto
const abrirPopup = (incoterm) => {
    popupIncoterm.value = incoterm;
};

// Cierra el popup
const cerrarPopup = () => {
    popupIncoterm.value = null;
};

// Guarda los pasos del incoterm — lo hace el PADRE
const guardar = async (incoterm) => {
    try {
        await window.axios.put(`/client/incoterms/${incoterm.id}`, {
            pasos: incoterm.pasos,
        });
        successMsg.value = `Incoterm ${incoterm.codi} actualizado correctamente`;
        cerrarPopup();
        setTimeout(() => successMsg.value = '', 3000);
    } catch (err) {
        console.error('Error al guardar:', err);
    }
};

onMounted(loadData);
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700;800&display=swap');

.incoterms-shell {
    min-height: 100vh;
    background: #e9edf4;
    display: grid;
    grid-template-columns: 240px 1fr;
    font-family: 'Manrope', sans-serif;
    color: #152238;
}

.sidebar { background: linear-gradient(180deg, #0a2243 0%, #071a33 100%); color: #dbe7ff; padding: 1.2rem 1rem; display: flex; flex-direction: column; gap: 0.7rem; }
.brand { padding: 0.2rem 0.5rem 0.9rem; }
.brand img { height: 30px; object-fit: contain; }
.section-title { margin: 0.5rem 0 0.2rem; text-transform: uppercase; font-size: 0.67rem; letter-spacing: 0.08em; opacity: 0.8; }
.section-title.muted { margin-top: 0.9rem; opacity: 0.58; }
.menu { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 0.26rem; }
.menu li { padding: 0.6rem 0.72rem; border-radius: 9px; font-size: 0.82rem; display: flex; align-items: center; justify-content: space-between; color: #c6d8fb; }
.menu li a { color: inherit; text-decoration: none; }
.menu li.active { background: #ff7e26; color: #fff; font-weight: 700; }
.user-card { margin-top: auto; padding-top: 0.9rem; border-top: 1px solid rgba(198,216,251,0.17); display: flex; align-items: center; gap: 0.65rem; }
.avatar { width: 34px; height: 34px; border-radius: 50%; background: #2d65b0; display: inline-flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 700; }
.user-card strong { font-size: 0.75rem; display: block; }
.user-card small { opacity: 0.75; font-size: 0.68rem; }

.content { padding: 1rem 1.1rem; }
.topbar { background: #f3f5f8; border: 1px solid #d6dee8; border-radius: 12px; padding: 0.8rem 1rem; display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.8rem; }
h1 { margin: 0; font-size: 1rem; font-weight: 800; }
.topbar p { margin: 0.12rem 0 0; font-size: 0.72rem; color: #607089; }
.loading { text-align: center; padding: 2rem; color: #607089; }
.success-message { background: #e8f7ec; border: 1px solid #a8d5b5; color: #2d8a54; border-radius: 8px; padding: 0.6rem 1rem; margin-bottom: 0.8rem; font-size: 0.82rem; font-weight: 700; }
.incoterms-list { display: grid; gap: 0.8rem; }
</style>