<template>
    <div class="notifications-shell">
        <aside class="sidebar">
            <div class="brand">
                <img :src="logoSrc" alt="Prime Logistics" />
            </div>

            <p class="section-title">Principal</p>
            <ul class="menu">
                <li><a href="/cliente/dashboard">Dashboard</a></li>
                <li><a href="/cliente/nuevo-pedido">Nuevo Pedido</a></li>
                <li><a href="/cliente/mis-pedidos">Mis Pedidos</a> <span class="dot"></span></li>
                <li><a href="/cliente/tracking">Tracking</a></li>
                <li class="active">Notificaciones <span class="dot"></span></li>
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
                    <h1>Notificaciones</h1>
                    <p>Centro de mensajes y alertas</p>
                </div>
                <div class="actions">
                    <button class="mark-read" type="button">Marcar todo como leido</button>
                    <button class="icon-btn" type="button">🔔</button>
                    <span class="mini-avatar">MG</span>
                </div>
            </header>

            <section class="grid-main">
                <article class="panel inbox">
                    <div class="inbox-head">
                        <h2>Bandeja de entrada</h2>
                        <div class="tabs">
                            <button class="active">Todas</button>
                            <button>No leidas - {{ stats.unread }}</button>
                            <button>Ofertas</button>
                            <button>Sistema</button>
                        </div>
                    </div>

                    <ul class="notice-list">
                        <li v-for="notice in notices" :key="notice.id" class="notice-item" :class="{ featured: notice.featured, selected: priority.id === notice.id }" @click="selectNotice(notice)">
                            <div class="bullet"></div>
                            <div class="notice-content">
                                <h3>{{ notice.title }}</h3>
                                <p>{{ notice.message }}</p>
                                <div class="chips">
                                    <span class="chip">{{ notice.code }}</span>
                                    <span class="chip status" :class="notice.typeClass">{{ notice.type }}</span>
                                </div>
                                <small>{{ notice.time }}</small>
                            </div>
                            <div class="notice-actions">
                                <button v-if="notice.primary" type="button" class="action-btn" @click="handlePrimaryAction(notice)">{{ notice.primary }}</button>
                                <button v-if="notice.secondary" type="button" class="action-btn ghost">{{ notice.secondary }}</button>
                            </div>
                        </li>
                    </ul>

                    <div class="list-footer">
                        <small>Mostrando {{ notices.length }} de {{ stats.total }} notificaciones</small>
                        <div class="pagination">
                            <button type="button">&lt;</button>
                            <button type="button" class="active">1</button>
                            <button type="button">2</button>
                            <button type="button">3</button>
                            <button type="button">&gt;</button>
                        </div>
                    </div>
                </article>

                <aside class="right-col">
                    <article class="panel priority-card">
                        <small class="tag">Accion requerida</small>
                        <h3>{{ priority.title }}</h3>
                        <p>{{ priority.message }}</p>
                        <div class="offer-grid">
                            <span>Codigo</span><strong>{{ priority.code }}</strong>
                            <span>Tipo</span><strong class="highlight">{{ priority.type }}</strong>
                            <span>Fecha</span><strong>{{ priority.time }}</strong>
                        </div>
                        <textarea readonly>{{ priority.message }}</textarea>
                        <button v-if="priority.typeClass === 'sent'" class="accept" type="button" @click="acceptOffer(priority.id)">Aceptar Contraoferta</button>
                        <button v-if="priority.typeClass === 'sent'" class="reject" type="button" @click="rejectOffer(priority.id)">Rechazar</button>
                    </article>

                    <article class="panel status-card">
                        <h3>Estado de notificaciones</h3>
                        <ul>
                            <li><span>No leidas</span><strong>{{ stats.unread }}</strong></li>
                            <li><span>Pendientes accion</span><strong>{{ stats.pending_action }}</strong></li>
                            <li><span>Resueltas este mes</span><strong>{{ stats.resolved_month }}</strong></li>
                            <li><span>Total historico</span><strong>{{ stats.total }}</strong></li>
                        </ul>
                    </article>
                </aside>
            </section>
        </main>
    </div>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue';

const logoSrc = '/prime-logistics-logo.png';

const notices = ref([]);
const stats = reactive({
    unread: 0,
    pending_action: 0,
    resolved_month: 0,
    total: 0,
});

const priority = reactive({
    title: '-',
    message: '-',
    code: '-',
    type: '-',
    time: '-',
});

const loadNotifications = async () => {
    const { data } = await window.axios.get('/api/client/notifications');
    notices.value = data.notices;
    Object.assign(stats, data.stats);

    if (data.priority) {
        Object.assign(priority, data.priority);
    }
};

const selectNotice = (notice) => {
    Object.assign(priority, notice);
};

const handlePrimaryAction = (notice) => {
    if (notice.primary === 'Ver Tracking') {
        window.location.href = `/cliente/tracking?offer_id=${notice.id}`;
    } else if (notice.primary === 'Ver Oferta') {
        // Perhaps select the notice or redirect to offer details
        selectNotice(notice);
    }
};

const acceptOffer = async (offerId) => {
    try {
        await window.axios.post('/api/client/accept-offer', { offer_id: offerId });
        await loadNotifications();
    } catch (error) {
        console.error('Error accepting offer', error);
    }
};

const rejectOffer = async (offerId) => {
    try {
        await window.axios.post('/api/client/reject-offer', { offer_id: offerId });
        await loadNotifications();
    } catch (error) {
        console.error('Error rejecting offer', error);
    }
};

onMounted(async () => {
    try {
        await loadNotifications();
    } catch (error) {
        console.error('No se pudieron cargar notificaciones', error);
    }
});
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700;800&display=swap');

.notifications-shell {
    min-height: 100vh;
    background: #e9edf4;
    display: grid;
    grid-template-columns: 240px 1fr;
    font-family: 'Manrope', sans-serif;
    color: #152238;
}

.sidebar {
    background: linear-gradient(180deg, #0a2243 0%, #071a33 100%);
    color: #dbe7ff;
    padding: 1.2rem 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.7rem;
}

.brand { padding: 0.2rem 0.5rem 0.9rem; }
.brand img { height: 30px; object-fit: contain; }

.section-title { margin: 0.5rem 0 0.2rem; text-transform: uppercase; font-size: 0.67rem; letter-spacing: 0.08em; opacity: 0.8; }
.section-title.muted { margin-top: 0.9rem; opacity: 0.58; }

.menu { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 0.26rem; }
.menu li { padding: 0.6rem 0.72rem; border-radius: 9px; font-size: 0.82rem; display: flex; align-items: center; justify-content: space-between; color: #c6d8fb; }
.menu li a { color: inherit; text-decoration: none; }
.menu li.active { background: #ff7e26; color: #fff; font-weight: 700; }

.dot { width: 8px; height: 8px; border-radius: 50%; background: #ff7e26; }

.user-card { margin-top: auto; padding-top: 0.9rem; border-top: 1px solid rgba(198, 216, 251, 0.17); display: flex; align-items: center; gap: 0.65rem; }
.avatar { width: 34px; height: 34px; border-radius: 50%; background: #2d65b0; display: inline-flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 700; }
.user-card strong { font-size: 0.75rem; display: block; }
.user-card small { opacity: 0.75; font-size: 0.68rem; }

.content { padding: 1rem 1.1rem; }
.topbar { background: #f3f5f8; border: 1px solid #d6dee8; border-radius: 12px; padding: 0.8rem 1rem; display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.8rem; }
h1 { margin: 0; font-size: 1rem; font-weight: 800; }
.topbar p { margin: 0.12rem 0 0; font-size: 0.72rem; color: #607089; }

.actions { display: flex; align-items: center; gap: 0.5rem; }
.mark-read { border: 1px solid #d2dae6; background: #fff; color: #1f3556; padding: 0.45rem 0.8rem; border-radius: 8px; font-size: 0.7rem; font-weight: 700; }
.icon-btn { border: 1px solid #d2dae6; background: #fff; width: 30px; height: 30px; border-radius: 50%; }
.mini-avatar { width: 30px; height: 30px; border-radius: 50%; background: #dce8fb; color: #2d65b0; display: inline-flex; align-items: center; justify-content: center; font-size: 0.65rem; font-weight: 700; }

.grid-main { display: grid; grid-template-columns: 2fr 1fr; gap: 0.7rem; }
.panel { background: #f7f9fc; border: 1px solid #d6dee8; border-radius: 9px; padding: 0.8rem; }

.inbox-head h2 { margin: 0 0 0.45rem; font-size: 0.9rem; }
.tabs { display: flex; gap: 0.35rem; margin-bottom: 0.65rem; border-bottom: 1px solid #dde5f0; padding-bottom: 0.45rem; }
.tabs button { border: 0; background: transparent; color: #7a8aa2; font-size: 0.63rem; font-weight: 700; }
.tabs button.active { color: #1f3556; }

.notice-list { list-style: none; margin: 0; padding: 0; display: grid; gap: 0.42rem; }
.notice-item { display: grid; grid-template-columns: auto 1fr auto; gap: 0.55rem; border: 1px solid #e1e8f1; border-radius: 10px; padding: 0.55rem; background: #fff; }
.notice-item.featured { background: #f0f6ff; border-color: #cfe0f7; }
.notice-item.selected { background: #e0f7fa; border-color: #b2ebf2; }
.bullet { width: 7px; height: 7px; border-radius: 50%; background: #2f69bd; margin-top: 0.3rem; }
.notice-content h3 { margin: 0; font-size: 0.74rem; }
.notice-content p { margin: 0.2rem 0 0.35rem; font-size: 0.62rem; color: #687b95; line-height: 1.35; }
.notice-content small { color: #7f90a8; font-size: 0.59rem; }

.chips { display: flex; gap: 0.35rem; margin-bottom: 0.35rem; }
.chip { border: 1px solid #d8e0ec; border-radius: 999px; padding: 0.14rem 0.5rem; font-size: 0.55rem; font-weight: 700; color: #586c87; }
.status.counter { background: #fff1e7; border-color: #ffd6bc; color: #dd742d; }
.status.sent { background: #eaf2ff; border-color: #cfe0f8; color: #2f69bd; }
.status.docs { background: #eef3f9; border-color: #d8e2ef; color: #5f738f; }
.status.ok { background: #e8f7ec; border-color: #caecd5; color: #2d8a54; }
.status.track { background: #eaf2ff; border-color: #cfe0f8; color: #2f69bd; }
.status.no { background: #ffe8e4; border-color: #ffcbc2; color: #bf5046; }

.notice-actions { display: flex; flex-direction: column; gap: 0.35rem; justify-content: center; }
.action-btn { border: 0; border-radius: 8px; background: #ff7e26; color: #fff; font-size: 0.6rem; font-weight: 700; padding: 0.3rem 0.55rem; }
.action-btn.ghost { border: 1px solid #ffd1b2; color: #df742d; background: #fff5ef; }

.list-footer { margin-top: 0.6rem; display: flex; justify-content: space-between; align-items: center; }
.list-footer small { font-size: 0.62rem; color: #73849d; }
.pagination { display: flex; gap: 0.35rem; }
.pagination button { width: 24px; height: 24px; border: 1px solid #d5deea; border-radius: 7px; background: #fff; font-size: 0.62rem; }
.pagination button.active { background: #ff7e26; color: #fff; border-color: #ff7e26; }

.right-col { display: grid; gap: 0.7rem; align-content: start; }
.priority-card .tag { display: inline-block; color: #df742d; background: #fff2e8; border: 1px solid #ffd7be; border-radius: 999px; padding: 0.14rem 0.5rem; font-size: 0.56rem; font-weight: 700; }
.priority-card h3 { margin: 0.5rem 0 0.18rem; font-size: 0.82rem; }
.priority-card p { margin: 0 0 0.5rem; font-size: 0.63rem; color: #6d809a; }
.offer-grid { display: grid; grid-template-columns: 1fr auto; gap: 0.25rem 0.5rem; margin-bottom: 0.45rem; }
.offer-grid span { font-size: 0.61rem; color: #7689a2; }
.offer-grid strong { font-size: 0.63rem; }
.offer-grid .highlight { color: #e7742f; }
.priority-card textarea { width: 100%; border: 1px solid #d8e0ec; border-radius: 8px; background: #f6f8fb; color: #59708f; font-size: 0.62rem; padding: 0.45rem; min-height: 55px; resize: none; margin-bottom: 0.45rem; }
.accept { width: 100%; border: 0; border-radius: 8px; background: #ff7e26; color: #fff; padding: 0.5rem; font-size: 0.68rem; font-weight: 700; }
.reject { width: 100%; border: 1px solid #ffd1b2; border-radius: 8px; background: #fff5ef; color: #df742d; padding: 0.45rem; font-size: 0.66rem; font-weight: 700; margin-top: 0.35rem; }

.status-card h3 { margin: 0 0 0.45rem; font-size: 0.78rem; }
.status-card ul { list-style: none; margin: 0; padding: 0; display: grid; gap: 0.34rem; }
.status-card li { display: flex; justify-content: space-between; border-bottom: 1px solid #e4ebf4; padding-bottom: 0.26rem; font-size: 0.63rem; }
.status-card li:last-child { border-bottom: 0; }
.status-card span { color: #71839d; }

@media (max-width: 1200px) {
    .notifications-shell { grid-template-columns: 1fr; }
    .grid-main { grid-template-columns: 1fr; }
}
</style>
