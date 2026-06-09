<template>
    <div class="orders-shell">
        <aside class="sidebar">
            <div class="brand">
                <img :src="logoSrc" alt="Prime Logistics" />
            </div>
            <p class="section-title">Principal</p>
            <ul class="menu">
                <li><a href="/cliente/dashboard">Dashboard</a></li>
                <li><a href="/cliente/nuevo-pedido">Nuevo Pedido</a></li>
                <li class="active">Mis Pedidos <span class="dot"></span></li>
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
                    <h1>Mis Pedidos</h1>
                    <p>Historial y gestion de operaciones</p>
                </div>
                <div class="actions">
                    <a class="new-order" href="/cliente/nuevo-pedido">+ Nuevo Pedido</a>
                    <button class="icon-btn" type="button">🔔</button>
                    <span class="mini-avatar">MG</span>
                </div>
            </header>

            <section class="panel filters">
                <input v-model="filters.q" type="text" placeholder="Buscar por ID, ruta..." @keyup.enter="loadOrders" />
                <select v-model="filters.status" @change="loadOrders(1)">
                    <option value="">Todos los estados</option>
                    <option value="EN TRANSITO">En transito</option>
                    <option value="COMPLETADA">Completada</option>
                    <option value="EN PREPARACION">En preparacion</option>
                </select>
                <select v-model="filters.mode" @change="loadOrders(1)">
                    <option value="">Todos los modos</option>
                    <option value="Maritimo">Maritimo</option>
                    <option value="Aereo">Aereo</option>
                    <option value="Terrestre">Terrestre</option>
                </select>
                <select>
                    <option>Cualquier fecha</option>
                </select>
                <small>{{ meta.total }} pedidos</small>
            </section>

            <section class="panel table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID Oferta</th>
                            <th>Ref. Cliente</th>
                            <th>Modo</th>
                            <th>Ruta</th>
                            <th>Peso/Vol</th>
                            <th>Fecha</th>
                            <th>ETD</th>
                            <th>Estado</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="order in orders" :key="order.id">
                            <td class="id">{{ order.id }}</td>
                            <td>{{ order.ref }}</td>
                            <td>{{ order.mode }}</td>
                            <td>{{ order.route }}</td>
                            <td>{{ order.weight }}</td>
                            <td>{{ order.date }}</td>
                            <td>{{ order.etd }}</td>
                            <td>
                                <span class="pill" :class="statusClass(order.status)">{{ order.status }}</span>
                            </td>
                            <td><a class="tracking" :href="`/cliente/tracking?code=${order.id}`">Tracking</a></td>
                        </tr>
                    </tbody>
                </table>

                <div class="table-footer">
                    <small>Mostrando <strong>{{ orders.length }}</strong> de <strong>{{ meta.total }}</strong> pedidos</small>
                    <div class="pagination">
                        <button type="button" @click="goPage(meta.current_page - 1)">&lt;</button>
                        <button type="button" class="active">{{ meta.current_page }}</button>
                        <button type="button">{{ meta.last_page }}</button>
                        <button type="button" @click="goPage(meta.current_page + 1)">&gt;</button>
                    </div>
                </div>
            </section>
        </main>
    </div>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue';

const logoSrc = '/images/prime-logistics-logo.svg';
const orders = ref([]);
const meta = reactive({ current_page: 1, last_page: 1, total: 0 });
const filters = reactive({ q: '', status: '', mode: '' });

const loadOrders = async (page = 1) => {
    const { data } = await window.axios.get('/client/orders', {
        params: {
            page,
            q: filters.q || undefined,
            status: filters.status || undefined,
            mode: filters.mode || undefined,
        },
    });
    orders.value = data.data;
    Object.assign(meta, data.meta);
};

const goPage = async (page) => {
    if (page < 1 || page > meta.last_page) return;
    await loadOrders(page);
};

const statusClass = (status) => {
    if (status === 'EN TRANSITO') return 'in-transit';
    if (status === 'ACEPTADA') return 'accepted';
    if (status === 'COMPLETADA') return 'completed';
    return 'rejected';
};

onMounted(async () => {
    try {
        await loadOrders();
    } catch (error) {
        console.error('No se pudieron cargar pedidos', error);
    }
});
</script>