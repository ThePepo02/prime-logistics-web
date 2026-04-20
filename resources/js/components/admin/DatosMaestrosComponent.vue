<template>
    <div class="datos-maestros-container">
        <!-- Contenido principal sin sidebar -->
        <div class="main-content">
            <h1>Datos Maestros admin</h1>

            <!-- Empresas -->
            <div class="seccion">
                <h2>Empresas</h2>
                <div class="badge">Empresas regionales</div>
                <div class="lista-empresas">
                    <div class="empresa-card">
                        <div class="empresa-nombre">Textil SA</div>
                        <div class="empresa-stats">3 usuarios: 26 pedidos</div>
                    </div>
                    <div class="empresa-card">
                        <div class="empresa-nombre">Moda Express SL</div>
                        <div class="empresa-stats">2 usuarios: 19 pedidos</div>
                    </div>
                    <div class="empresa-card">
                        <div class="empresa-nombre">Import Global</div>
                        <div class="empresa-stats">1 usuario: 9 pedidos</div>
                    </div>
                    <div class="empresa-card">
                        <div class="empresa-nombre">Tech Imports SA</div>
                        <div class="empresa-stats">1 usuario: 6 pedidos</div>
                    </div>
                </div>
            </div>

            <!-- Puertos / Aeropuertos -->
            <div class="seccion">
                <h2>Puertos / Aeropuertos</h2>
                <div class="badge">6 cajeros activos</div>
                <div class="puertos-grid">
                    <div class="puerto-card">
                        <div class="puerto-nombre">Valencia (VLC)</div>
                        <div class="puerto-tipo">Puerto marítimo: 33 operaciones</div>
                    </div>
                    <div class="puerto-card">
                        <div class="puerto-nombre">Barcelona (BCN)</div>
                        <div class="puerto-tipo">Puerto + Aeropuerto: 24 operaciones</div>
                    </div>
                    <div class="puerto-card">
                        <div class="puerto-nombre">Bilbao (BIO)</div>
                        <div class="puerto-tipo">Puerto marítimo: 12 operaciones</div>
                    </div>
                    <div class="puerto-card">
                        <div class="puerto-nombre">Madrid-Barajas (MAD)</div>
                        <div class="puerto-tipo">Aeropuerto: 10 operaciones</div>
                    </div>
                    <div class="puerto-card">
                        <div class="puerto-nombre">Algeciras (AGP)</div>
                        <div class="puerto-tipo">Puerto marítimo: 8 operaciones</div>
                    </div>
                    <div class="puerto-card">
                        <div class="puerto-nombre">Palma (PMI)</div>
                        <div class="puerto-tipo">Aeropuerto: 2 operaciones</div>
                    </div>
                </div>
            </div>

            <!-- Navieras & Carriers -->
            <div class="seccion">
                <h2>Navieras & Carriers</h2>
                <div class="badge">5 carteras activas</div>
                <div class="navieras-grid">
                    <div class="naviera-card">
                        <div class="naviera-nombre">MSC Mediterraneum</div>
                        <div class="naviera-tipo">Marítimo: 10 operaciones</div>
                    </div>
                    <div class="naviera-card">
                        <div class="naviera-nombre">Maersk</div>
                        <div class="naviera-tipo">Marítimo: 12 operaciones</div>
                    </div>
                    <div class="naviera-card">
                        <div class="naviera-nombre">CMA CGM</div>
                        <div class="naviera-tipo">Marítimo: 7 operaciones</div>
                    </div>
                    <div class="naviera-card">
                        <div class="naviera-nombre">Iberia Cargo</div>
                        <div class="naviera-tipo">Aéreo: 11 operaciones</div>
                    </div>
                    <div class="naviera-card">
                        <div class="naviera-nombre">DHL Express</div>
                        <div class="naviera-tipo">Aéreo: 8 operaciones</div>
                    </div>
                </div>
            </div>

            <!-- Agentes Aduanales -->
            <div class="seccion">
                <h2>Agentes Aduanales</h2>
                <div class="badge">3 agentes activos</div>
                <div class="agentes-grid">
                    <div class="agente-card">
                        <div class="agente-nombre">Aduana Express SL</div>
                        <div class="agente-detalle">Madrid + Valencia: 28 operaciones</div>
                    </div>
                    <div class="agente-card">
                        <div class="agente-nombre">Global Customs SA</div>
                        <div class="agente-detalle">Sanciones + Bitaco: 15 expedientes</div>
                    </div>
                    <div class="agente-card">
                        <div class="agente-nombre">Iberian Brokers</div>
                        <div class="agente-detalle">Vientre: 10 expedientes</div>
                    </div>
                </div>
            </div>

            <!-- Incoterms Activos -->
            <div class="seccion">
                <h2>Incoterms Activos</h2>
                <div class="badge">7 configuraciones</div>
                <div class="incoterms-grid">
                    <div class="incoterm-card">
                        <div class="incoterm-code">FOB</div>
                        <div class="incoterm-desc">Free On Board</div>
                    </div>
                    <div class="incoterm-card">
                        <div class="incoterm-code">CIF</div>
                        <div class="incoterm-desc">Cost Insurance Freight</div>
                    </div>
                    <div class="incoterm-card">
                        <div class="incoterm-code">EXW</div>
                        <div class="incoterm-desc">Ex Works</div>
                    </div>
                    <div class="incoterm-card">
                        <div class="incoterm-code">DDP</div>
                        <div class="incoterm-desc">Delivered Duty Paid</div>
                    </div>
                    <div class="incoterm-card">
                        <div class="incoterm-code">DAP</div>
                        <div class="incoterm-desc">Delivered At Place</div>
                    </div>
                    <div class="incoterm-card">
                        <div class="incoterm-code">CFR</div>
                        <div class="incoterm-desc">Cost and Freight</div>
                    </div>
                    <div class="incoterm-card">
                        <div class="incoterm-code">FCA</div>
                        <div class="incoterm-desc">Free Carrier</div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="dashboard-footer">
                <span>Carlos Martínez</span>
            </footer>
        </div>
    </div>
</template>

<script setup>
// Sin lógica adicional
</script>

<style scoped>
.datos-maestros-container {
    min-height: 100vh;
    background: #f5f5f5;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.main-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px 30px;
}

h1 {
    color: #2c3e50;
    margin-bottom: 30px;
    font-size: 24px;
}

h2 {
    color: #2c3e50;
    font-size: 18px;
    margin-bottom: 10px;
}

.seccion {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
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

.lista-empresas,
.navieras-grid,
.agentes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 15px;
}

.empresa-card,
.naviera-card,
.agente-card {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 6px;
    transition: all 0.3s;
}

.empresa-card:hover,
.naviera-card:hover,
.agente-card:hover {
    background: #e9ecef;
    transform: translateY(-2px);
}

.empresa-nombre,
.naviera-nombre,
.agente-nombre {
    font-weight: bold;
    color: #2c3e50;
    margin-bottom: 5px;
    font-size: 16px;
}

.empresa-stats,
.naviera-tipo,
.agente-detalle {
    font-size: 13px;
    color: #7f8c8d;
}

.puertos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 15px;
}

.puerto-card {
    background: #f8f9fa;
    padding: 12px 15px;
    border-radius: 6px;
    border-left: 3px solid #3498db;
}

.puerto-nombre {
    font-weight: bold;
    color: #2c3e50;
    margin-bottom: 5px;
}

.puerto-tipo {
    font-size: 12px;
    color: #7f8c8d;
}

.incoterms-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 12px;
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
    font-size: 18px;
    margin-bottom: 5px;
}

.incoterm-desc {
    font-size: 11px;
    color: #7f8c8d;
}

.incoterm-card:hover .incoterm-desc {
    color: white;
}

.dashboard-footer {
    margin-top: 30px;
    padding: 15px;
    text-align: center;
    color: #7f8c8d;
    border-top: 1px solid #ecf0f1;
    font-size: 14px;
}
</style>