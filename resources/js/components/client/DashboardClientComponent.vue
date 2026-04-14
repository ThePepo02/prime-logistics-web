<template>
    <div class="dashboard-shell">
        <aside class="sidebar">
            <div class="brand">
                <img :src="logoSrc" alt="Prime Logistics" />
            </div>

            <p class="section-title">Principal</p>
            <ul class="menu">
                <li class="active">Dashboard</li>
                <li><a href="/cliente/nuevo-pedido">Nuevos Pedidos</a></li>
                <li><a href="/cliente/mis-pedidos">Mis Pedidos</a> <span class="dot"></span></li>
                <li><a href="/cliente/tracking">Tracking</a></li>
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
                    <h1>Dashboard</h1>
                    <p>Bienvenido, {{ userLabel }}</p>
                </div>
                <div class="actions">
                    <a class="new-order" href="/cliente/nuevo-pedido">+ Nuevo Pedido</a>
                    <button class="icon-btn">🔔</button>
                    <span class="mini-avatar">MO</span>
                </div>
            </header>

            <section class="kpi-grid">
                <article class="kpi blue">
                    <h3>Pedidos activos</h3>
                    <p>{{ dashboard.kpis.active }}</p>
                    <small>2 esta semana</small>
                </article>
                <article class="kpi green">
                    <h3>Entregados</h3>
                    <p>{{ dashboard.kpis.delivered }}</p>
                    <small>Sin reclamaciones</small>
                </article>
                <article class="kpi red">
                    <h3>Retrasados</h3>
                    <p>{{ dashboard.kpis.delayed }}</p>
                    <small>1 en ruta critica</small>
                </article>
                <article class="kpi orange">
                    <h3>Incidencias</h3>
                    <p>{{ dashboard.kpis.incidents }}</p>
                    <small>1 nueva hoy</small>
                </article>
            </section>

            <section class="middle-grid">
                <article class="panel compact">
                    <h4>Operaciones activas</h4>
                    <p class="big-number">{{ dashboard.kpis.active }}</p>
                    <small>2 esta semana</small>
                </article>

                <article class="panel">
                    <h4>Ultimo pedido activo</h4>
                    <p class="shipment">{{ highlightedOrder.id }} - {{ highlightedOrder.mode }}</p>
                    <small>{{ highlightedOrder.route }} | Fecha {{ highlightedOrder.date }}</small>
                    <div class="inline-actions">
                        <span class="pill">{{ highlightedOrder.status }}</span>
                        <a href="/cliente/tracking">Ver Tracking</a>
                    </div>
                </article>
            </section>

            <section class="panel chart-panel">
                <div class="panel-head">
                    <h4>Actividad de pedidos</h4>
                    <small>Ultima 8 semanas</small>
                </div>
                <div class="fake-chart">
                    <div v-for="(bar, index) in chartData" :key="index" class="bar-group">
                        <span class="bar blue-bar" :style="{ height: `${bar.pedidos}px` }"></span>
                        <span class="bar orange-bar" :style="{ height: `${bar.incidencias}px` }"></span>
                    </div>
                </div>
            </section>

            <section class="panel table-panel">
                <div class="table-head">
                    <h4>Mis pedidos recientes</h4>
                    <a href="#">Ver todos</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>ID Orden</th>
                            <th>Modo</th>
                            <th>Ruta</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="order in recentOrders" :key="order.id">
                            <td>{{ order.id }}</td>
                            <td>{{ order.mode }}</td>
                            <td>{{ order.route }}</td>
                            <td>{{ order.date }}</td>
                            <td><span class="pill" :class="statusClass(order.status)">{{ order.status }}</span></td>
                            <td><a class="tracking-btn" href="/cliente/tracking">Tracking</a></td>
                        </tr>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</template>

<script setup>
import { computed, onMounted, reactive, ref } from 'vue';

const logoSrc = '/prime-logistics-logo.png';

const dashboard = reactive({
    kpis: {
        active: 0,
        delivered: 0,
        delayed: 0,
        incidents: 0,
    },
});

const chartData = ref([]);
const recentOrders = ref([]);

const userLabel = computed(() => {
    if (recentOrders.value.length > 0) {
        return 'Cliente';
    }

    return 'Cliente';
});

const highlightedOrder = computed(() => {
    return recentOrders.value[0] ?? {
        id: 'N/A',
        mode: 'Sin datos',
        route: 'Sin ruta',
        date: '-',
        status: 'PENDIENTE',
    };
});

const statusClass = (status) => {
    if (status === 'EN TRANSITO') {
        return 'pending';
    }

    if (status === 'ACEPTADA') {
        return 'done';
    }

    return 'complete';
};

const loadDashboard = async () => {
    const { data } = await window.axios.get('/api/client/dashboard');
    dashboard.kpis = data.kpis;
    chartData.value = data.chartData;
    recentOrders.value = data.recentOrders.slice(0, 3);
};

onMounted(async () => {
    try {
        await loadDashboard();
    } catch (error) {
        console.error('No se pudo cargar dashboard', error);
    }
});
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700;800&display=swap');

.dashboard-shell {
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

.brand {
    padding: 0.2rem 0.5rem 0.9rem;
}

.brand img {
    height: 30px;
    object-fit: contain;
}

.section-title {
    margin: 0.5rem 0 0.2rem;
    text-transform: uppercase;
    font-size: 0.67rem;
    letter-spacing: 0.08em;
    opacity: 0.8;
}

.section-title.muted {
    margin-top: 0.9rem;
    opacity: 0.58;
}

.menu {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 0.26rem;
}

.menu li {
    padding: 0.6rem 0.72rem;
    border-radius: 9px;
    font-size: 0.82rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: #c6d8fb;
}

.menu li a {
    color: inherit;
    text-decoration: none;
}

.menu li.active {
    background: #ff7e26;
    color: #fff;
    font-weight: 700;
}

.dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #ff7e26;
}

.user-card {
    margin-top: auto;
    padding-top: 0.9rem;
    border-top: 1px solid rgba(198, 216, 251, 0.17);
    display: flex;
    align-items: center;
    gap: 0.65rem;
}

.avatar {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: #2d65b0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    font-weight: 700;
}

.user-card strong {
    font-size: 0.75rem;
    display: block;
}

.user-card small {
    opacity: 0.75;
    font-size: 0.68rem;
}

.content {
    padding: 1rem 1.1rem 1.2rem;
}

.topbar {
    background: #f3f5f8;
    border: 1px solid #d6dee8;
    border-radius: 12px;
    padding: 0.8rem 1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.8rem;
}

h1 {
    margin: 0;
    font-size: 1rem;
    font-weight: 800;
}

.topbar p {
    margin: 0.12rem 0 0;
    font-size: 0.72rem;
    color: #607089;
}

.actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.new-order {
    border: 0;
    background: #ff7e26;
    color: #fff;
    padding: 0.45rem 0.8rem;
    border-radius: 8px;
    font-size: 0.72rem;
    font-weight: 700;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
}

.icon-btn {
    border: 1px solid #d2dae6;
    background: #fff;
    width: 30px;
    height: 30px;
    border-radius: 50%;
}

.mini-avatar {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #dce8fb;
    color: #2d65b0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.65rem;
    font-weight: 700;
}

.kpi-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 0.6rem;
    margin-bottom: 0.7rem;
}

.kpi,
.panel {
    background: #f7f9fc;
    border: 1px solid #d6dee8;
    border-radius: 9px;
    padding: 0.72rem;
}

.kpi {
    border-top-width: 3px;
}

.kpi h3 {
    margin: 0;
    font-size: 0.69rem;
    color: #5e6e87;
    font-weight: 700;
}

.kpi p {
    margin: 0.2rem 0 0;
    font-size: 1.65rem;
    font-weight: 800;
}

.kpi small {
    font-size: 0.66rem;
}

.blue {
    border-top-color: #1f6fcc;
}

.green {
    border-top-color: #3ea86e;
}

.red {
    border-top-color: #e24f4c;
}

.orange {
    border-top-color: #ff7e26;
}

.middle-grid {
    display: grid;
    grid-template-columns: 1fr 2.2fr;
    gap: 0.6rem;
    margin-bottom: 0.7rem;
}

.compact {
    display: grid;
    align-content: center;
}

.big-number {
    font-size: 2.25rem;
    line-height: 1;
    margin: 0.1rem 0;
    font-weight: 800;
}

.panel h4 {
    margin: 0;
    font-size: 0.74rem;
    font-weight: 800;
}

.shipment {
    margin: 0.35rem 0 0.2rem;
    font-size: 0.8rem;
    font-weight: 700;
}

.panel small {
    color: #607089;
    font-size: 0.67rem;
}

.inline-actions {
    margin-top: 0.5rem;
    display: flex;
    gap: 0.7rem;
    align-items: center;
}

.inline-actions a {
    color: #1f6fcc;
    text-decoration: none;
    font-size: 0.69rem;
    font-weight: 700;
}

.pill {
    display: inline-flex;
    align-items: center;
    border-radius: 999px;
    padding: 0.17rem 0.6rem;
    font-size: 0.58rem;
    font-weight: 800;
    letter-spacing: 0.02em;
}

.pending {
    background: #dbe8ff;
    color: #2f69bd;
}

.done {
    background: #d8f3de;
    color: #257e4f;
}

.complete {
    background: #e9f5df;
    color: #3e7a3d;
}

.chart-panel {
    margin-bottom: 0.7rem;
}

.panel-head {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    margin-bottom: 0.35rem;
}

.fake-chart {
    display: flex;
    align-items: flex-end;
    gap: 1.4rem;
    height: 90px;
    padding: 0.3rem 0.7rem;
}

.bar-group {
    display: flex;
    align-items: flex-end;
    gap: 5px;
}

.bar {
    width: 23px;
    border-radius: 4px 4px 0 0;
}

.blue-bar {
    background: #1f6fcc;
}

.orange-bar {
    background: #ff7e26;
}

.table-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.4rem;
}

.table-head a {
    color: #73849d;
    text-decoration: none;
    font-size: 0.68rem;
    font-weight: 700;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th,
td {
    text-align: left;
    padding: 0.45rem 0.35rem;
    font-size: 0.68rem;
    border-top: 1px solid #e0e6ef;
}

th {
    color: #7a8aa2;
    font-weight: 700;
    border-top: 0;
}

.tracking-btn {
    border: 1px solid #d3dbe8;
    background: #fff;
    border-radius: 8px;
    font-size: 0.63rem;
    padding: 0.2rem 0.5rem;
    text-decoration: none;
    color: #1f3556;
    display: inline-flex;
}

@media (max-width: 1100px) {
    .dashboard-shell {
        grid-template-columns: 1fr;
    }

    .sidebar {
        position: static;
    }

    .kpi-grid,
    .middle-grid {
        grid-template-columns: 1fr;
    }

    .fake-chart {
        gap: 0.8rem;
        overflow-x: auto;
    }

    .bar {
        width: 16px;
    }

    th,
    td {
        font-size: 0.64rem;
    }
}
</style>
