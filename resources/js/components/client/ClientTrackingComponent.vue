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
                <li class="active">Tracking</li>
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
                    <p>OC-2024-021 - Valencia -> Shanghai</p>
                </div>
                <div class="actions">
                    <a class="new-order" href="/cliente/nuevo-pedido">+ Nuevo Pedido</a>
                    <button class="icon-btn" type="button">🔔</button>
                    <span class="mini-avatar">MG</span>
                </div>
            </header>

            <section class="panel search-row">
                <input v-model="trackingCode" type="text" placeholder="OC-2024-021" />
                <button class="btn-track" type="button">Rastrear</button>
            </section>

            <section class="grid-main">
                <article class="panel left-panel">
                    <div class="shipment-head">
                        <div>
                            <h2>{{ trackingCode }}</h2>
                            <small>Valencia -> Shanghai</small>
                        </div>
                        <span class="pill in-transit">EN TRANSITO</span>
                    </div>

                    <div class="progress-wrap">
                        <div class="progress-line"><span :style="{ width: '50%' }"></span></div>
                        <small>50%</small>
                    </div>

                    <div class="timeline">
                        <div
                            v-for="(step, index) in timeline"
                            :key="index"
                            class="timeline-item"
                            :class="step.state"
                        >
                            <div class="dot-icon">{{ step.icon }}</div>
                            <div class="step-text">
                                <strong>{{ step.title }}</strong>
                                <small>{{ step.status }}</small>
                                <small>{{ step.date }}</small>
                            </div>
                            <span v-if="step.state === 'current'" class="pulse"></span>
                        </div>
                    </div>
                </article>

                <aside class="right-col">
                    <article class="panel info-card">
                        <h3>Datos del Envio</h3>
                        <div class="data-grid">
                            <span>Naviera</span><strong>MSC Mediterranean</strong>
                            <span>Buque</span><strong>MSC Gulsin</strong>
                            <span>Contenedor</span><strong>MSCU1234567 - 40 HC Dry</strong>
                            <span>Incoterm</span><strong>FOB Valencia</strong>
                            <span>ETD</span><strong>12 Ene 2025</strong>
                            <span>ETA</span><strong>15 Feb 2025</strong>
                            <span>Dias en transito</span><strong>34 dias estimados</strong>
                        </div>
                    </article>

                    <article class="panel info-card">
                        <h3>Agente Aduanal</h3>
                        <p class="agent">Aduana Express SL</p>
                        <small>+34 96 123 4567 - info@aduanaexpress.es</small>
                    </article>

                    <article class="panel info-card">
                        <h3>Documentos</h3>
                        <ul class="docs">
                            <li><span>B/L (Bill of Lading)</span><button>⬇</button></li>
                            <li><span>Factura Comercial</span><button>⬇</button></li>
                            <li><span>Packing List</span><button>⬇</button></li>
                        </ul>
                    </article>

                    <button class="contact-btn" type="button">Contactar con Operador</button>
                </aside>
            </section>
        </main>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const logoSrc = '/prime-logistics-logo.png';
const trackingCode = ref('OC-2024-021');

const timeline = [
    { icon: '📦', title: 'Preparacion Mercancia', status: 'COMPLETADA', date: 'Completado 05 Ene', state: 'done' },
    { icon: '🚚', title: 'Transporte Interior Origen', status: 'COMPLETADA', date: 'Completado 08 Ene', state: 'done' },
    { icon: '⚓', title: 'Terminal / Puerto Origen', status: 'COMPLETADA', date: 'Completado 10 Ene', state: 'done' },
    { icon: '🛳', title: 'Carga a Bordo', status: 'COMPLETADA', date: 'Completado 12 Ene', state: 'done' },
    { icon: '🚢', title: 'Transporte Maritimo', status: 'EN CURSO', date: 'En curso - ETA 15 Feb', state: 'current' },
    { icon: '⚓', title: 'Puerto Destino', status: 'PENDIENTE', date: 'Pendiente', state: 'pending' },
    { icon: '📄', title: 'Aduana Importacion', status: 'PENDIENTE', date: 'Pendiente', state: 'pending' },
    { icon: '🚛', title: 'Transporte Interior Destino', status: 'PENDIENTE', date: 'Pendiente', state: 'pending' },
    { icon: '📍', title: 'Entrega Final', status: 'PENDIENTE', date: 'Pendiente', state: 'pending' }
];
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
    padding: 1rem 1.1rem;
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

.panel {
    background: #f7f9fc;
    border: 1px solid #d6dee8;
    border-radius: 9px;
    padding: 0.8rem;
}

.search-row {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 0.6rem;
    margin-bottom: 0.7rem;
}

.search-row input {
    border: 1px solid #d6dee8;
    border-radius: 8px;
    background: #f6f8fb;
    font-size: 0.8rem;
    color: #1b2f4c;
    padding: 0.56rem 0.8rem;
}

.btn-track {
    border: 0;
    background: #ff7e26;
    color: #fff;
    padding: 0.56rem 1rem;
    border-radius: 8px;
    font-size: 0.74rem;
    font-weight: 700;
}

.grid-main {
    display: grid;
    grid-template-columns: 2fr 1.15fr;
    gap: 0.7rem;
}

.shipment-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.6rem;
}

.shipment-head h2 {
    margin: 0;
    font-size: 1.06rem;
}

.shipment-head small {
    color: #6d809a;
    font-size: 0.64rem;
}

.pill {
    display: inline-flex;
    align-items: center;
    border-radius: 999px;
    padding: 0.17rem 0.6rem;
    font-size: 0.56rem;
    font-weight: 800;
}

.in-transit {
    background: #dbe8ff;
    color: #2f69bd;
}

.progress-wrap {
    margin-bottom: 0.9rem;
}

.progress-line {
    height: 4px;
    background: #d6deea;
    border-radius: 999px;
    overflow: hidden;
}

.progress-line span {
    display: block;
    height: 100%;
    border-radius: 999px;
    background: linear-gradient(90deg, #2f69bd 0%, #ff7e26 100%);
}

.progress-wrap small {
    margin-top: 0.2rem;
    display: block;
    text-align: right;
    color: #6d809a;
    font-size: 0.6rem;
}

.timeline {
    display: flex;
    flex-direction: column;
    gap: 0.34rem;
}

.timeline-item {
    border: 1px solid #dde5f0;
    border-radius: 10px;
    background: #f8fafd;
    padding: 0.46rem 0.5rem;
    display: grid;
    grid-template-columns: auto 1fr auto;
    align-items: center;
    gap: 0.55rem;
}

.timeline-item.done {
    border-color: #c9ecd6;
    background: #f0faf4;
}

.timeline-item.current {
    border-color: #ffcfac;
    background: #fff6ef;
}

.timeline-item.pending {
    opacity: 0.8;
}

.dot-icon {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: #e9f7ef;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
}

.timeline-item.current .dot-icon {
    background: #ff7e26;
    color: #fff;
}

.step-text strong {
    display: block;
    font-size: 0.72rem;
}

.step-text small {
    display: block;
    font-size: 0.58rem;
    color: #6d809a;
}

.pulse {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #ff7e26;
}

.right-col {
    display: flex;
    flex-direction: column;
    gap: 0.7rem;
}

.info-card h3 {
    margin: 0 0 0.55rem;
    font-size: 0.78rem;
}

.data-grid {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 0.34rem 0.7rem;
}

.data-grid span {
    color: #72849d;
    font-size: 0.62rem;
}

.data-grid strong {
    font-size: 0.62rem;
}

.agent {
    font-size: 0.74rem;
    font-weight: 700;
    margin: 0 0 0.2rem;
}

.info-card small {
    color: #72849d;
    font-size: 0.6rem;
}

.docs {
    list-style: none;
    margin: 0;
    padding: 0;
    display: grid;
    gap: 0.4rem;
}

.docs li {
    background: #eef2f8;
    border: 1px solid #dae2ee;
    border-radius: 8px;
    padding: 0.43rem 0.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 0.66rem;
}

.docs button {
    border: 0;
    border-radius: 7px;
    width: 25px;
    height: 25px;
    background: #0a2243;
    color: #fff;
}

.contact-btn {
    border: 0;
    border-radius: 8px;
    background: #2f69bd;
    color: #fff;
    font-size: 0.68rem;
    font-weight: 700;
    padding: 0.55rem;
}

@media (max-width: 1200px) {
    .tracking-shell {
        grid-template-columns: 1fr;
    }

    .grid-main {
        grid-template-columns: 1fr;
    }
}
</style>
