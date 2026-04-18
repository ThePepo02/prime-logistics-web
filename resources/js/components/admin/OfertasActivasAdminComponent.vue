<template>
    <div class="operaciones-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">Admin</div>
            <nav class="nav-menu">
                <div class="nav-section">
                    <div class="nav-title">Información</div>
                    <ul>
                        <li><a href="#">Administradores</a></li>
                        <li><a href="#">Dashboard</a></li>
                        <li><a href="#">Gestión de Usuarios</a></li>
                    </ul>
                </div>
                <div class="nav-section">
                    <div class="nav-title">Comunicaciones</div>
                    <ul>
                        <li><a href="#">Todas las Ofertas</a></li>
                        <li><a href="#" class="active">Operaciones Activas</a></li>
                    </ul>
                </div>
            </nav>
        </div>

        <!-- Contenido principal -->
        <div class="main-content">
            <h1>Operaciones Activas</h1>

            <!-- Tarjetas de KPIs -->
            <div class="kpi-grid">
                <div class="kpi-card">
                    <div class="kpi-number">38</div>
                    <div class="kpi-label">Operaciones activas</div>
                    <div class="kpi-sub">Operaciones activas en curso</div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-number">12</div>
                    <div class="kpi-label">Entradas</div>
                    <div class="kpi-sub">Entradas totales</div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-number">21</div>
                    <div class="kpi-label">Salidas</div>
                    <div class="kpi-sub">Salidas totales</div>
                </div>
            </div>

            <!-- Distribución por Modo de Transporte -->
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Distribución por Modo de Transporte</h3>
                    <div class="stat-item">
                        <span class="stat-label">Materiales</span>
                        <span class="stat-value">2 entradas - 55%</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Airevo</span>
                        <span class="stat-value">12 entradas - 37%</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Terrestre</span>
                        <span class="stat-value">4 entradas - 11%</span>
                    </div>
                </div>

                <div class="stat-card">
                    <h3>Operaciones en Carro</h3>
                    <div class="stat-item">
                        <span class="stat-label">Máquinas</span>
                        <span class="stat-value">1 entrada - 3%</span>
                    </div>
                </div>
            </div>

            <!-- Tabla de operaciones -->
            <div class="table-wrapper">
                <table class="operaciones-table">
                    <thead>
                        <tr v-for="(group, idx) in tableGroups" :key="idx">
                            <th>Código</th>
                            <th>Cliente</th>
                            <th>Empresa</th>
                            <th>Modelo</th>
                            <th>Tipo</th>
                            <th>CTD</th>
                            <th>CPV</th>
                            <th>Fase Anual</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="operacion in todasOperaciones" :key="operacion.codigo">
                            <td>{{ operacion.codigo }}</td>
                            <td>{{ operacion.cliente }}</td>
                            <td>{{ operacion.empresa }}</td>
                            <td>{{ operacion.modelo }}</td>
                            <td>{{ operacion.tipo }}</td>
                            <td>{{ operacion.ctd }}</td>
                            <td>{{ operacion.cpv }}</td>
                            <td>{{ operacion.faseAnual }}</td>
                            <td><span class="estado-inactivo">{{ operacion.estado }}</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="footer-note">
                <strong>Importante: 5 de 30 operaciones</strong>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const todasOperaciones = ref([
    {
        codigo: 'CDC-2020-021',
        cliente: 'Marta García',
        empresa: 'Totti S.A.',
        modelo: 'Martina',
        tipo: 'Veterinaria-Telemarque',
        ctd: '17 Pte',
        cpv: '18 Pte',
        faseAnual: 'La Transporte Madrileña',
        estado: 'Inactiva'
    },
    {
        codigo: 'CDC-2022-019',
        cliente: 'Marta García',
        empresa: 'Totti S.A.',
        modelo: 'Aferro',
        tipo: 'Deportistas-Humans York',
        ctd: '09 Pte',
        cpv: '10 Pte',
        faseAnual: 'El Energía Final',
        estado: 'Inactiva'
    },
    {
        codigo: 'CDC-2022-020',
        cliente: 'Marta García',
        empresa: 'Totti S.A.',
        modelo: 'Aferro',
        tipo: 'Madrid-Millard',
        ctd: '05 Pte',
        cpv: '06 Pte',
        faseAnual: 'Adaura Importación',
        estado: 'Inactiva'
    },
    {
        codigo: 'CDC-2022-022',
        cliente: 'Marta García',
        empresa: 'Totti Hipotecos',
        modelo: 'Ventana',
        tipo: 'Veterinaria-Doula',
        ctd: '15 CPte',
        cpv: '30 Pte',
        faseAnual: '1 Carga a Bordo',
        estado: 'Inactiva'
    },
    {
        codigo: 'CDC-2022-023',
        cliente: 'Pedro López',
        empresa: 'Mica Express',
        modelo: 'Terrasa',
        tipo: 'Madrid-Hark',
        ctd: '13 CPte',
        cpv: '15 CPte',
        faseAnual: 'Transporte Interior',
        estado: 'Inactiva'
    },
    {
        codigo: 'CDC-2022-024',
        cliente: 'Sara Ruiz',
        empresa: 'Tarjeta Obsoleta',
        modelo: 'Martina',
        tipo: 'Rastreador-Alejandrina',
        ctd: '10 CPte',
        cpv: '16 CPte',
        faseAnual: 'Puerta Obsoleta',
        estado: 'Inactiva'
    },
    {
        codigo: 'CDC-2022-025',
        cliente: 'Marta García',
        empresa: 'Totti S.A.',
        modelo: 'Aferro',
        tipo: 'Salvaguarda-Madrid',
        ctd: '11 CPte',
        cpv: '12 CPte',
        faseAnual: 'Terminal de Línea',
        estado: 'Inactiva'
    },
    {
        codigo: 'CDC-2022-027',
        cliente: 'Pedro López',
        empresa: 'Mica Express',
        modelo: 'Madrid-Madrid',
        tipo: 'Venta-Viaja',
        ctd: '13 CPte',
        cpv: '14 CPte',
        faseAnual: 'Preparación',
        estado: 'Inactiva'
    }
])

const tableGroups = computed(() => {
    return [{}]
})
</script>

<style scoped>
.operaciones-container {
    display: flex;
    min-height: 100vh;
    background: #f5f5f5;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.sidebar {
    width: 260px;
    background: #2c3e50;
    color: white;
    padding: 20px 0;
}

.logo {
    font-size: 24px;
    font-weight: bold;
    padding: 0 20px 20px;
    border-bottom: 1px solid #34495e;
    margin-bottom: 20px;
}

.nav-section {
    margin-bottom: 20px;
}

.nav-title {
    padding: 10px 20px;
    font-size: 12px;
    text-transform: uppercase;
    color: #7f8c8d;
    letter-spacing: 1px;
}

.nav-menu ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.nav-menu li a {
    display: block;
    padding: 10px 20px;
    color: #ecf0f1;
    text-decoration: none;
    transition: all 0.3s;
}

.nav-menu li a:hover,
.nav-menu li a.active {
    background: #34495e;
    color: #3498db;
}

.main-content {
    flex: 1;
    padding: 20px 30px;
}

h1 {
    color: #2c3e50;
    margin-bottom: 20px;
}

.kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.kpi-card {
    background: white;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.kpi-number {
    font-size: 36px;
    font-weight: bold;
    color: #3498db;
}

.kpi-label {
    font-size: 14px;
    color: #7f8c8d;
    margin-top: 5px;
}

.kpi-sub {
    font-size: 12px;
    color: #95a5a6;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.stat-card h3 {
    margin: 0 0 15px 0;
    color: #2c3e50;
    font-size: 16px;
}

.stat-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #ecf0f1;
}

.table-wrapper {
    background: white;
    border-radius: 8px;
    overflow-x: auto;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.operaciones-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 800px;
}

.operaciones-table th {
    background: #34495e;
    color: white;
    padding: 12px 15px;
    text-align: left;
    font-size: 13px;
}

.operaciones-table td {
    padding: 12px 15px;
    border-bottom: 1px solid #ecf0f1;
    font-size: 14px;
}

.operaciones-table tr:hover {
    background: #f8f9fa;
}

.estado-inactivo {
    background: #f8d7da;
    color: #721c24;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.footer-note {
    margin-top: 20px;
    padding: 15px;
    background: #fff3cd;
    border-radius: 8px;
    text-align: center;
    color: #856404;
}
</style>