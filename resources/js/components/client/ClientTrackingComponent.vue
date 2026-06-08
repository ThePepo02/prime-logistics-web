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
                        <button v-if="pasoActual > 1" @click="previousTracking" class="search-btn" style="background: #607089">← Paso anterior</button>
                        <button v-if="pasoActual < totalPasos" @click="advanceTracking" class="search-btn">Siguiente paso →</button>
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

const pasoActual = computed(() => {
    if (!tracking.value) return 0;
    const idx = tracking.value.timeline.findIndex(s => s.state === 'current');
    if (idx !== -1) return idx + 1;
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
        const { data } = await window.axios.get('/client/tracking', { params: { code: searchQuery.value.trim() } });
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

const recargarTracking = async () => {
    if (!currentOfferId.value) return;
    try {
        const { data } = await window.axios.get('/client/tracking', { params: { code: currentOfferId.value } });
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
    const code = params.get('code');
    if (code) {
        searchQuery.value = code;
        searchTracking();
    }
});
</script>