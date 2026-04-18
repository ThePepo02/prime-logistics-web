<template>
    <div class="datos-maestros-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">Admin</div>
            <nav class="nav-menu">
                <div class="nav-section">
                    <div class="nav-title">Aprendizaje</div>
                    <ul>
                        <li><a href="#" class="active">Datos Maestros</a></li>
                    </ul>
                </div>
            </nav>
        </div>

        <!-- Contenido principal -->
        <div class="main-content">
            <h1>Datos Maestros</h1>

            <!-- Grid de categorías -->
            <div class="categorias-grid">
                <!-- Empresas -->
                <div class="categoria-card">
                    <h2>Empresas</h2>
                    <div class="badge">4 programas ejecutables</div>
                    <ul class="lista-items">
                        <li>
                            <strong>Tecni5 SA</strong>
                            <span class="detalle">2.0 para Intel® Core™ i3</span>
                        </li>
                        <li>
                            <strong>Modus Express SL</strong>
                            <span class="detalle">Línea: 1,6 GHz</span>
                        </li>
                        <li>
                            <strong>Impart Global</strong>
                            <span class="detalle">Línea: 2 unidades</span>
                        </li>
                        <li>
                            <strong>Tech Imports SA</strong>
                            <span class="detalle">Línea: 2 unidades</span>
                        </li>
                    </ul>
                </div>

                <!-- Navieras & Carriers -->
                <div class="categoria-card">
                    <h2>Navieras & Carriers</h2>
                    <div class="badge">1 gran línea única</div>
                    <ul class="lista-items">
                        <li>
                            <strong>MSC Mediterranean</strong>
                            <span class="detalle">Área: 10,000 toneladas</span>
                        </li>
                        <li>
                            <strong>Merax</strong>
                            <span class="detalle">Metales: 12 operaciones</span>
                        </li>
                        <li>
                            <strong>CMA OGM</strong>
                            <span class="detalle">Metales: 7 operaciones</span>
                        </li>
                        <li>
                            <strong>Rerla Cargo</strong>
                            <span class="detalle">Metales: 11 operaciones</span>
                        </li>
                        <li>
                            <strong>DHL Express</strong>
                            <span class="detalle">Área: 8 operaciones</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Incoterms -->
            <div class="incoterms-section">
                <h2>Incoterms Activos</h2>
                <div class="badge">7 operaciones</div>
                <div class="incoterms-grid">
                    <div class="incoterm-card">
                        <div class="incoterm-code">FOB</div>
                        <div class="incoterm-desc">Free in Force</div>
                    </div>
                    <div class="incoterm-card">
                        <div class="incoterm-code">CIF</div>
                        <div class="incoterm-desc">Cost estimate Project</div>
                    </div>
                    <div class="incoterm-card">
                        <div class="incoterm-code">EXW</div>
                        <div class="incoterm-desc">Ex Notes</div>
                    </div>
                    <div class="incoterm-card">
                        <div class="incoterm-code">DDP</div>
                        <div class="incoterm-desc">Delivery Duty Price</div>
                    </div>
                    <div class="incoterm-card">
                        <div class="incoterm-code">DAP</div>
                        <div class="incoterm-desc">Delivered to Port</div>
                    </div>
                    <div class="incoterm-card">
                        <div class="incoterm-code">CFR</div>
                        <div class="incoterm-desc">Chai and Hangar</div>
                    </div>
                    <div class="incoterm-card">
                        <div class="incoterm-code">FCA</div>
                        <div class="incoterm-desc">Free Carrier</div>
                    </div>
                </div>
            </div>

            <!-- Puertos / Aeropuertos -->
            <div class="puertos-section">
                <h2>Puertos / Aeropuertos</h2>
                <div class="badge">5 aeropuertos activos</div>
                <div class="puertos-grid">
                    <div class="puerto-card">
                        <div class="puerto-code">Valencia (VLC)</div>
                        <div class="puerto-desc">Surge monto: 50 aeropuertos</div>
                    </div>
                    <div class="puerto-card">
                        <div class="puerto-code">Barcelona (BCN)</div>
                        <div class="puerto-desc">Punto + Antenas: 24 aeropuertos</div>
                    </div>
                    <div class="puerto-card">
                        <div class="puerto-code">Dibao (DBO)</div>
                        <div class="puerto-desc">Punto máximo: 10 aeropuertos</div>
                    </div>
                    <div class="puerto-card">
                        <div class="puerto-code">Madrid-Granja (MAD)</div>
                        <div class="puerto-desc">Aeropuerto: 8 aeropuertos</div>
                    </div>
                    <div class="puerto-card">
                        <div class="puerto-code">Algeciras (AGH)</div>
                        <div class="puerto-desc">Punto máximo: 6 aeropuertos</div>
                    </div>
                    <div class="puerto-card">
                        <div class="puerto-code">Palma (PMI)</div>
                        <div class="puerto-desc">Almacenado: 3 almacenes</div>
                    </div>
                </div>
            </div>

            <!-- Agentes -->
            <div class="agentes-section">
                <h2>Agentes Abandonados</h2>
                <div class="badge">2 agentes activos</div>
                <div class="agentes-lista">
                    <div class="agente-item">
                        <div class="agente-nombre">Adsuara Express SL</div>
                        <div class="agente-stats">Mando: 7 aeropuertos</div>
                        <div class="subagente">
                            <span>→ Global Customs SA</span>
                            <span class="sub-detail">Gestión: 9 billetes</span>
                            <div class="sub-sub">
                                <span>→ Isabella Brekens</span>
                                <span class="sub-detail">Vendedor: 16 operaciones</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
// No se necesita lógica adicional para este componente
</script>

<style scoped>
.datos-maestros-container {
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
    margin-bottom: 30px;
}

h2 {
    color: #2c3e50;
    font-size: 18px;
    margin-bottom: 15px;
}

.categorias-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.categoria-card {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.badge {
    display: inline-block;
    background: #ecf0f1;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    color: #7f8c8d;
    margin-bottom: 15px;
}

.lista-items {
    list-style: none;
    padding: 0;
    margin: 0;
}

.lista-items li {
    padding: 10px 0;
    border-bottom: 1px solid #ecf0f1;
}

.lista-items li strong {
    display: block;
    color: #2c3e50;
    margin-bottom: 5px;
}

.detalle {
    font-size: 12px;
    color: #7f8c8d;
}

.incoterms-section,
.puertos-section,
.agentes-section {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

.incoterms-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 15px;
    margin-top: 15px;
}

.incoterm-card {
    background: #f8f9fa;
    padding: 12px;
    border-radius: 6px;
    text-align: center;
    transition: all 0.3s;
}

.incoterm-card:hover {
    background: #3498db;
    color: white;
    transform: translateY(-2px);
}

.incoterm-code {
    font-weight: bold;
    font-size: 16px;
    margin-bottom: 5px;
}

.incoterm-desc {
    font-size: 11px;
    color: #7f8c8d;
}

.incoterm-card:hover .incoterm-desc {
    color: white;
}

.puertos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 15px;
    margin-top: 15px;
}

.puerto-card {
    background: #f8f9fa;
    padding: 12px;
    border-radius: 6px;
    border-left: 3px solid #3498db;
}

.puerto-code {
    font-weight: bold;
    margin-bottom: 5px;
}

.puerto-desc {
    font-size: 12px;
    color: #7f8c8d;
}

.agentes-lista {
    margin-top: 15px;
}

.agente-item {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 6px;
}

.agente-nombre {
    font-weight: bold;
    color: #2c3e50;
    margin-bottom: 5px;
}

.agente-stats {
    font-size: 13px;
    color: #7f8c8d;
    margin-bottom: 10px;
}

.subagente {
    margin-left: 20px;
    padding-left: 15px;
    border-left: 2px solid #3498db;
}

.subagente span {
    display: block;
    margin: 5px 0;
}

.sub-sub {
    margin-left: 20px;
    padding-left: 15px;
    border-left: 2px solid #e74c3c;
    margin-top: 8px;
}

.sub-detail {
    font-size: 12px;
    color: #95a5a6;
    margin-left: 10px;
}
</style>