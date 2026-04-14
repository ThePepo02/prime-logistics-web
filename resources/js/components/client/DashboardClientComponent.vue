<template>
    <section class="client-dashboard">
        <header class="topbar">
            <div>
                <p class="eyebrow">Prime Logistics</p>
                <h1>Dashboard Cliente</h1>
            </div>
            <a class="cta" href="#">Crear nueva solicitud</a>
        </header>

        <div class="cards-grid">
            <article class="card">
                <p class="card-label">Envios activos</p>
                <p class="card-value">18</p>
                <small>3 en seguimiento prioritario</small>
            </article>

            <article class="card">
                <p class="card-label">Entregas completadas</p>
                <p class="card-value">124</p>
                <small>Ultimos 30 dias</small>
            </article>

            <article class="card">
                <p class="card-label">Incidencias abiertas</p>
                <p class="card-value">2</p>
                <small>Tiempo promedio de resolucion: 4h</small>
            </article>
        </div>

        <section class="panel">
            <h2>Actividad reciente</h2>
            <ul>
                <li>
                    <span>Pedido #PL-4021</span>
                    <strong>En transito</strong>
                </li>
                <li>
                    <span>Pedido #PL-4018</span>
                    <strong>Entregado</strong>
                </li>
                <li>
                    <span>Pedido #PL-4016</span>
                    <strong>En revision</strong>
                </li>
            </ul>
        </section>
    </section>
</template>

<style scoped>
.client-dashboard {
    min-height: 100vh;
    padding: 2rem;
    background: radial-gradient(circle at 20% 20%, #f6e3b8 0%, #f1f5f9 50%, #dce8f7 100%);
    color: #1e293b;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

.topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
}

.eyebrow {
    text-transform: uppercase;
    letter-spacing: 0.1em;
    font-size: 0.75rem;
    margin: 0;
}

h1 {
    margin: 0.2rem 0 0;
    font-size: 2rem;
}

.cta {
    background: #0f172a;
    color: #fff;
    padding: 0.75rem 1rem;
    border-radius: 0.7rem;
    text-decoration: none;
}

.cards-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 1rem;
    margin-bottom: 1.2rem;
}

.card {
    background: rgba(255, 255, 255, 0.8);
    border: 1px solid rgba(30, 41, 59, 0.12);
    border-radius: 1rem;
    padding: 1rem;
    backdrop-filter: blur(3px);
}

.card-label {
    margin: 0;
    color: #334155;
}

.card-value {
    margin: 0.4rem 0;
    font-size: 2rem;
    font-weight: 700;
}

.panel {
    background: #ffffffcc;
    border-radius: 1rem;
    border: 1px solid rgba(30, 41, 59, 0.12);
    padding: 1rem;
}

.panel h2 {
    margin-top: 0;
}

.panel ul {
    padding: 0;
    margin: 0;
    list-style: none;
}

.panel li {
    display: flex;
    justify-content: space-between;
    border-bottom: 1px dashed rgba(30, 41, 59, 0.2);
    padding: 0.75rem 0;
}

.panel li:last-child {
    border-bottom: 0;
}

@media (max-width: 900px) {
    .cards-grid {
        grid-template-columns: 1fr;
    }

    .topbar {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>
