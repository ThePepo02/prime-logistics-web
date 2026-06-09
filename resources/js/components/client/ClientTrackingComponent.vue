<template>
    <div class="tracking-shell">
        <aside class="sidebar">
            <div class="brand">
                <img :src="logoSrc" alt="Prime Logistics" />
            </div>

            <p class="section-title">Principal</p>
            <ul class="menu">
                <li><a href="/cliente/dashboard">Dashboard</a></li>
                <li><a href="/cliente/nuevo-pedido">Nuevo Pedido</a></li>
                <li><a href="/cliente/mis-pedidos">Mis Pedidos</a> <span class="dot"></span></li>
                <li class="active"><a href="/cliente/tracking">Tracking</a></li>
                <li><a href="/cliente/notificaciones">Notificaciones</a> <span class="dot"></span></li>
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
                    <h1>Tracking</h1>
                    <p>Rastreo de envíos y ofertas</p>
                </div>
                <div class="actions">
                    <button class="icon-btn" type="button">🔔</button>
                    <span class="mini-avatar">MG</span>
                </div>
            </header>

            <section class="search-section">
                <div class="search-box">
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Buscar por código de oferta (ej: OFR-2025-0041)"
                        @keyup.enter="searchTracking"
                        class="search-input"
                    />
                    <button @click="searchTracking" class="search-btn">Buscar</button>
                </div>
                <div v-if="error" class="error-message">{{ error }}</div>
                <div v-if="loading" class="loading">Cargando tracking...</div>
            </section>

            <section v-if="tracking && !loading" class="tracking-content">
                <article class="panel tracking-header">
                    <div class="header-info">
                        <h2>{{ tracking.code }}</h2>
                        <p class="status" :class="tracking.status.toLowerCase().replace(/ /g, '_')">{{ tracking.status }}</p>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" :style="{ width: tracking.progress + '%' }"></div>
                        <span class="progress-text">{{ tracking.progress }}%</span>
                    </div>
                    <div style="display: flex; gap: 0.5rem; margin-top: 0.8rem">
                        <!-- Mostrar anterior solo si no estamos en el primer paso -->
                        <button
                            v-if="pasoActual > 1"
                            @click="previousTracking"
                            class="search-btn"
                            style="background: #607089"
                        >
                            ← Paso anterior
                        </button>
                        <!-- Mostrar siguiente solo si no estamos en el último paso -->
                        <button
                            v-if="pasoActual < totalPasos"
                            @click="advanceTracking"
                            class="search-btn"
                        >
                            Siguiente paso →
                        </button>
                        <!-- Completado solo cuando estamos exactamente en el último paso -->
                        <p v-if="pasoActual >= totalPasos" class="completed-text">✓ Envío completado</p>
                    </div>
                </article>

                <div class="grid-tracking">
                    <article class="panel timeline-section">
                        <h3>Timeline de envío</h3>
                        <div class="timeline">
                            <div
                                v-for="(step, index) in tracking.timeline"
                                :key="index"
                                class="timeline-item"
                                :class="step.state"
                            >
                                <div class="timeline-marker">{{ step.icon }}</div>
                                <div class="timeline-content">
                                    <h4>{{ step.title }}</h4>
                                    <p class="status-text">{{ step.status }}</p>
                                    <small>{{ step.date }}</small>
                                </div>
                            </div>
                        </div>
                    </article>

                    <aside class="right-panel">
                        <article class="panel details-section">
                            <h3>Detalles del envío</h3>
                            <div class="detail-item">
                                <span class="label">Línea de transporte</span>
                                <strong>{{ tracking.details.shipping_line }}</strong>
                            </div>
                            <div class="detail-item">
                                <span class="label">Buque/Vuelo</span>
                                <strong>{{ tracking.details.vessel }}</strong>
                            </div>
                            <div class="detail-item">
                                <span class="label">Contenedor</span>
                                <strong>{{ tracking.details.container }}</strong>
                            </div>
                            <div class="detail-item">
                                <span class="label">Incoterm</span>
                                <strong>{{ tracking.details.incoterm }}</strong>
                            </div>
                            <div class="detail-item">
                                <span class="label">ETD</span>
                                <strong>{{ tracking.details.etd }}</strong>
                            </div>
                            <div class="detail-item">
                                <span class="label">ETA</span>
                                <strong>{{ tracking.details.eta }}</strong>
                            </div>
                            <div class="detail-item">
                                <span class="label">Días en tránsito</span>
                                <strong>{{ tracking.details.days_in_transit }}</strong>
                            </div>
                        </article>

                        <article class="panel agent-section">
                            <h3>Agente de aduana</h3>
                            <div class="agent-info">
                                <p class="agent-name">{{ tracking.agent.name }}</p>
                                <p class="agent-contact">📧 {{ tracking.agent.contact }}</p>
                            </div>
                        </article>

                        <article class="panel route-section">
                            <h3>Ruta</h3>
                            <div class="route-info">
                                <div class="route-point">
                                    <strong>Origen</strong>
                                    <p>{{ tracking.route.origin }}</p>
                                </div>
                                <div class="route-arrow">→</div>
                                <div class="route-point">
                                    <strong>Destino</strong>
                                    <p>{{ tracking.route.destination }}</p>
                                </div>
                            </div>
                        </article>
                    </aside>
                </div>
            </section>

            <section v-else-if="!loading && !tracking" class="empty-state">
                <div class="empty-box">
                    <p class="empty-icon">📦</p>
                    <p class="empty-text">Ingresa un código de oferta para ver el tracking</p>
                </div>
            </section>
        </main>
    </div>
</template>

<script setup>
import { onMounted, ref, computed } from 'vue';

const logoSrc = '/images/prime-logistics-logo.svg';
const searchQuery = ref('');
const tracking = ref(null);
const loading = ref(false);
const error = ref('');
const currentOfferId = ref(null);

// Calculamos el paso actual y total desde el timeline
// para no depender del progreso en porcentaje
const pasoActual = computed(() => {
    if (!tracking.value) return 0;
   //Devuelve la posicion donde esta current
    const idx = tracking.value.timeline.findIndex(s => s.state === 'current');
    if (idx !== -1) return idx + 1;
    // Si todos están completados estamos en el último
    return tracking.value.timeline.length;
});

const totalPasos = computed(() => {
    if (!tracking.value) return 0;
    return tracking.value.timeline.length;
});

const searchTracking = async () => {
    if (!searchQuery.value.trim()) {
        error.value = 'Por favor ingresa un código o ID';
        return;
    }

    error.value = '';
    loading.value = true;

    try {
        const query = searchQuery.value.trim();
        const params = { code: query };
        const { data } = await window.axios.get('/client/tracking', { params });

        tracking.value = data;
        currentOfferId.value = data.id;
        error.value = '';
    } catch (err) {
        tracking.value = null;
        error.value = err.response?.data?.error || 'No se encontró el tracking. Verifica el código o ID.';
    } finally {
        loading.value = false;
    }
};

// Recarga el tracking sin tocar el searchQuery
const recargarTracking = async () => {
    if (!currentOfferId.value) return;
    try {
        const params = { code: currentOfferId.value };
        const { data } = await window.axios.get('/client/tracking', { params });
        tracking.value = data;
    } catch (err) {
        error.value = err.response?.data?.error || 'Error al recargar';
    }
};

const advanceTracking = async () => {
    try {
        await window.axios.post('/client/tracking/advance', { offer_id: currentOfferId.value });
        await recargarTracking();
    } catch (err) {
        error.value = err.response?.data?.error || 'Error al avanzar el paso';
    }
};

const previousTracking = async () => {
    try {
        await window.axios.post('/client/tracking/previous', { offer_id: currentOfferId.value });
        await recargarTracking();
    } catch (err) {
        error.value = err.response?.data?.error || 'Error al retroceder el paso';
    }
};

onMounted(() => {
    const params = new URLSearchParams(window.location.search);
    const offerId = params.get('offer_id');
    const code = params.get('code');

    if (offerId || code) {
        searchQuery.value = offerId || code;
        searchTracking();
    }
});
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700;800&display=swap');

.tracking-shell {
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
.dot { width: 8px; height: 8px; border-radius: 50%; background: #ff7e26; }
.user-card { margin-top: auto; padding-top: 0.9rem; border-top: 1px solid rgba(198,216,251,0.17); display: flex; align-items: center; gap: 0.65rem; }
.avatar { width: 34px; height: 34px; border-radius: 50%; background: #2d65b0; display: inline-flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 700; }
.user-card strong { font-size: 0.75rem; display: block; }
.user-card small { opacity: 0.75; font-size: 0.68rem; }

.content { padding: 1rem 1.1rem; }
.topbar { background: #f3f5f8; border: 1px solid #d6dee8; border-radius: 12px; padding: 0.8rem 1rem; display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.8rem; }
h1 { margin: 0; font-size: 1rem; font-weight: 800; }
.topbar p { margin: 0.12rem 0 0; font-size: 0.72rem; color: #607089; }
.actions { display: flex; align-items: center; gap: 0.5rem; }
.icon-btn { border: 1px solid #d2dae6; background: #fff; width: 30px; height: 30px; border-radius: 50%; }
.mini-avatar { width: 30px; height: 30px; border-radius: 50%; background: #dce8fb; color: #2d65b0; display: inline-flex; align-items: center; justify-content: center; font-size: 0.65rem; font-weight: 700; }

.search-section { margin-bottom: 1rem; }
.search-box { display: flex; gap: 0.5rem; margin-bottom: 0.5rem; }
.search-input { flex: 1; border: 1px solid #d6dee8; border-radius: 8px; padding: 0.7rem; font-size: 0.82rem; background: #fff; color: #152238; }
.search-input::placeholder { color: #a0b0c5; }
.search-btn { border: 0; background: #ff7e26; color: #fff; padding: 0.7rem 1.5rem; border-radius: 8px; font-weight: 700; cursor: pointer; }
.search-btn:hover { background: #e56d0f; }

.error-message { color: #c41e3a; background: #ffe0e0; border: 1px solid #ffb8c1; border-radius: 8px; padding: 0.6rem; font-size: 0.82rem; }
.loading { color: #2d65b0; background: #e8f0ff; border: 1px solid #cfe0f8; border-radius: 8px; padding: 0.6rem; font-size: 0.82rem; text-align: center; }

.panel { background: #f7f9fc; border: 1px solid #d6dee8; border-radius: 9px; padding: 0.8rem; }
.panel h3 { margin: 0 0 0.6rem; font-size: 0.88rem; }
.tracking-header { margin-bottom: 0.8rem; }
.header-info { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.8rem; }
.header-info h2 { margin: 0; font-size: 1.1rem; }
.header-info .status { display: inline-block; padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.73rem; font-weight: 700; }
.status.en_preparación { background: #fff1e7; color: #dd742d; }
.status.en_trànsit_internacional { background: #eaf2ff; color: #2f69bd; }
.status.lliurat_al_client_final { background: #e8f7ec; color: #2d8a54; }
.status.arribada_al_port\/aeroport_de_destí { background: #e8f7ec; color: #2d8a54; }
.status.en_repartiment_\(last_mile\) { background: #fff8e1; color: #b45309; }

.progress-bar { width: 100%; height: 8px; background: #e0e8f0; border-radius: 10px; overflow: hidden; position: relative; }
.progress-fill { height: 100%; background: linear-gradient(90deg, #ff7e26, #ffa850); border-radius: 10px; transition: width 0.3s; }
.progress-text { position: absolute; right: 0.5rem; top: 50%; transform: translateY(-50%); font-size: 0.7rem; font-weight: 700; color: #152238; }

.completed-text { margin: 0; color: #2d8a54; font-weight: 700; font-size: 0.85rem; display: flex; align-items: center; }

.grid-tracking { display: grid; grid-template-columns: 2fr 1fr; gap: 0.8rem; }
.timeline { display: grid; gap: 0.8rem; }
.timeline-item { display: flex; gap: 0.8rem; }
.timeline-item.completed .timeline-marker { color: #2d8a54; background: #e8f7ec; }
.timeline-item.current .timeline-marker { color: #2f69bd; background: #eaf2ff; }
.timeline-item.pending .timeline-marker { color: #a0b0c5; background: #f0f3f8; }
.timeline-marker { display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 50%; flex-shrink: 0; font-size: 1.2rem; }
.timeline-content { flex: 1; }
.timeline-content h4 { margin: 0; font-size: 0.82rem; font-weight: 700; }
.timeline-content .status-text { color: #7a8aa2; font-size: 0.73rem; margin: 0.15rem 0; }
.timeline-content small { color: #a0b0c5; font-size: 0.68rem; }

.right-panel { display: grid; gap: 0.8rem; align-content: start; }
.detail-item { display: grid; grid-template-columns: 1fr auto; gap: 0.5rem; padding-bottom: 0.6rem; border-bottom: 1px solid #e0e8f0; }
.detail-item:last-child { border-bottom: 0; }
.detail-item .label { font-size: 0.73rem; color: #7a8aa2; }
.detail-item strong { font-size: 0.82rem; text-align: right; }
.agent-name { margin: 0; font-size: 0.85rem; font-weight: 700; }
.agent-contact { margin: 0.4rem 0 0; font-size: 0.73rem; color: #7a8aa2; }
.route-info { display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; }
.route-point { text-align: center; flex: 1; }
.route-point strong { display: block; font-size: 0.73rem; color: #7a8aa2; margin-bottom: 0.3rem; }
.route-point p { margin: 0; font-size: 0.82rem; font-weight: 700; }
.route-arrow { color: #ff7e26; font-size: 1.2rem; }

.empty-state { display: flex; align-items: center; justify-content: center; min-height: 400px; }
.empty-box { text-align: center; }
.empty-icon { font-size: 4rem; margin: 0 0 1rem; }
.empty-text { font-size: 1rem; color: #7a8aa2; margin: 0; }

@media (max-width: 1200px) {
    .tracking-shell { grid-template-columns: 1fr; }
    .grid-tracking { grid-template-columns: 1fr; }
    .right-panel { grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); }
}
</style>