<template>
    <div class="form-shell">
        <aside class="sidebar">
            <div class="brand">
                <img :src="logoSrc" alt="Prime Logistics" />
            </div>

            <p class="section-title">Principal</p>
            <ul class="menu">
                <li><a href="/cliente/dashboard">Dashboard</a></li>
                <li class="active">Nuevo Pedido</li>
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
                    <h1>Nuevo Pedido</h1>
                    <p>Crear nueva operacion logistica</p>
                </div>
                <div class="top-actions">
                    <button class="icon-btn" type="button">🔔</button>
                    <span class="mini-avatar">MO</span>
                </div>
            </header>

            <section class="wizard panel">
                <div class="stepper">
                    <div class="step" :class="stepClass(1)">
                        <span class="bubble">{{ bubbleLabel(1) }}</span>
                        <div>
                            <strong>Identificacion</strong>
                            <small>Datos basicos</small>
                        </div>
                    </div>
                    <div class="line" :class="{ on: currentStep >= 2 }"></div>
                    <div class="step" :class="stepClass(2)">
                        <span class="bubble">{{ bubbleLabel(2) }}</span>
                        <div>
                            <strong>Especificaciones</strong>
                            <small>Carga y terminos</small>
                        </div>
                    </div>
                    <div class="line" :class="{ on: currentStep >= 3 }"></div>
                    <div class="step" :class="stepClass(3)">
                        <span class="bubble">{{ bubbleLabel(3) }}</span>
                        <div>
                            <strong>Ruta y Cierre</strong>
                            <small>Destino y financiero</small>
                        </div>
                    </div>
                </div>

                <div v-if="currentStep === 1" class="step-body">
                    <h2>Identificacion y Registro de la Oferta</h2>
                    <div class="grid two">
                        <label>ID Oferta Comercial *
                            <input v-model="form.offerId" type="text" />
                            <small>Asignado automaticamente</small>
                        </label>
                        <label>Estado *
                            <input v-model="form.status" type="text" />
                        </label>
                    </div>
                    <div class="grid two">
                        <label>Cliente / Razon Social *
                            <input v-model="form.clientName" type="text" />
                            <small>Asignado a tu empresa</small>
                        </label>
                        <label>Referencia Externa
                            <input v-model="form.externalRef" type="text" placeholder="EJ: TSA-2025-001" />
                        </label>
                    </div>
                    <div class="grid two">
                        <label>Gestor Comercial *
                            <input v-model="form.salesManager" type="text" />
                        </label>
                        <label>Creado por *
                            <input v-model="form.createdBy" type="text" />
                        </label>
                    </div>
                </div>

                <div v-if="currentStep === 2" class="step-body">
                    <h2>Modalidad de Transporte *</h2>
                    <div class="transport-grid">
                        <button
                            v-for="item in transportOptions"
                            :key="item.name"
                            type="button"
                            class="transport-card"
                            :class="{ selected: form.transportMode === item.name }"
                            @click="form.transportMode = item.name"
                        >
                            <strong>{{ item.name }}</strong>
                            <small>{{ item.route }}</small>
                        </button>
                    </div>

                    <div class="grid two">
                        <label>Flujo *
                            <div class="segmented">
                                <button type="button" :class="{ selected: form.flow === 'Importacion' }" @click="form.flow = 'Importacion'">Importacion</button>
                                <button type="button" :class="{ selected: form.flow === 'Exportacion' }" @click="form.flow = 'Exportacion'">Exportacion</button>
                            </div>
                        </label>
                    </div>

                    <h3>Especificaciones de Carga</h3>
                    <div class="grid three">
                        <label>Tipo de Carga
                            <input v-model="form.cargoType" type="text" />
                        </label>
                        <label>Contenedor
                            <input v-model="form.container" type="text" />
                        </label>
                        <label>Incoterm
                            <input v-model="form.incoterm" type="text" />
                        </label>
                    </div>
                    <div class="grid three">
                        <label>Peso Bruto (kg)
                            <input v-model="form.weight" type="number" min="0" />
                        </label>
                        <label>Volumen (m3)
                            <input v-model="form.volume" type="number" min="0" />
                        </label>
                        <label>N Bultos
                            <input v-model="form.packages" type="number" min="0" />
                        </label>
                    </div>
                    <div class="grid two">
                        <label>Descripcion Mercancia
                            <textarea v-model="form.description" placeholder="Descripcion detallada..."></textarea>
                        </label>
                        <label>Naviera
                            <input v-model="form.shippingLine" type="text" />
                        </label>
                    </div>
                </div>

                <div v-if="currentStep === 3" class="step-body">
                    <h2>Planificacion de Ruta</h2>
                    <div class="grid two">
                        <label>Puerto Origen
                            <input v-model="form.originPort" type="text" />
                        </label>
                        <label>Puerto Destino
                            <input v-model="form.destinationPort" type="text" />
                        </label>
                    </div>
                    <div class="grid two">
                        <label>Agente Aduanal
                            <input v-model="form.customAgent" type="text" />
                        </label>
                        <label>Fecha Emision
                            <input v-model="form.issueDate" type="date" />
                        </label>
                    </div>

                    <h3>Cierre Financiero</h3>
                    <div class="grid two">
                        <label>Vigencia
                            <input v-model="form.validityDate" type="date" />
                        </label>
                        <label>Margen Rentabilidad (EUR)
                            <input v-model="form.margin" type="number" min="0" step="0.01" />
                        </label>
                    </div>

                    <h3>Instrucciones y Observaciones</h3>
                    <div class="grid two">
                        <label>Instrucciones Especiales
                            <textarea v-model="form.instructions" placeholder="Instrucciones para el operador..."></textarea>
                        </label>
                        <label>Observaciones Internas
                            <textarea v-model="form.internalNotes" placeholder="Notas internas..."></textarea>
                        </label>
                    </div>

                    <div class="warning-box">
                        <p>Motivo de Rechazo (solo si estado = Rechazada)</p>
                        <textarea v-model="form.rejectReason" placeholder="Motivo del rechazo..."></textarea>
                    </div>
                </div>

                <footer class="wizard-footer">
                    <div class="progress-info">
                        <span>Paso {{ currentStep }} de 3</span>
                        <div class="progress-track">
                            <div class="progress-bar" :style="{ width: `${(currentStep / 3) * 100}%` }"></div>
                        </div>
                    </div>

                    <div class="footer-actions">
                        <button v-if="currentStep > 1" class="btn ghost" type="button" @click="currentStep--">Anterior</button>
                        <button v-if="currentStep < 3" class="btn primary" type="button" @click="currentStep++">Siguiente: {{ nextStepLabel }}</button>
                        <button v-if="currentStep === 3" class="btn ghost" type="button" :disabled="isSubmitting" @click="submitOrder('Borrador')">Guardar Borrador</button>
                        <button v-if="currentStep === 3" class="btn primary" type="button" :disabled="isSubmitting" @click="submitOrder('En preparación')">{{ isSubmitting ? 'Guardando...' : 'Enviar Pedido' }}</button>
                    </div>
                </footer>

                <p v-if="submitMessage" class="submit-message" :class="{ error: submitError }">{{ submitMessage }}</p>
            </section>
        </main>
    </div>
</template>

<script setup>
import { computed, reactive, ref } from 'vue';

const currentStep = ref(1);
const logoSrc = '/prime-logistics-logo.png';
const isSubmitting = ref(false);
const submitMessage = ref('');
const submitError = ref(false);

const transportOptions = [
    { name: 'Maritimo', route: 'FCL - LCL - Breakbulk' },
    { name: 'Aereo', route: 'Express - Consolidado' },
    { name: 'Terrestre', route: 'FTL - LTL - Aduana' },
    { name: 'Multimodal', route: 'Air - Sea - Intermodal' }
];

const form = reactive({
    offerId: 'OC-2025-',
    status: 'Borrador',
    clientName: 'Textil SA',
    externalRef: '',
    salesManager: 'Carlos Martinez',
    createdBy: 'Maria Garcia',
    transportMode: 'Maritimo',
    flow: 'Exportacion',
    cargoType: 'Carga General',
    container: "40' HC Dry",
    incoterm: 'FOB',
    weight: 0,
    volume: 0,
    packages: 1,
    description: '',
    shippingLine: 'MSC',
    originPort: 'Valencia (VLC)',
    destinationPort: 'Shanghai (SHA)',
    customAgent: 'Aduana Express SL',
    issueDate: '',
    validityDate: '',
    margin: 0,
    instructions: '',
    internalNotes: '',
    rejectReason: ''
});

const nextStepLabel = computed(() => {
    if (currentStep.value === 1) {
        return 'Especificaciones';
    }

    return 'Ruta y Cierre';
});

const stepClass = (step) => ({
    active: currentStep.value === step,
    done: currentStep.value > step
});

const bubbleLabel = (step) => {
    if (currentStep.value > step) {
        return '✓';
    }

    return String(step);
};

const submitOrder = async (status) => {
    submitMessage.value = '';
    submitError.value = false;
    isSubmitting.value = true;

    try {
        const payload = {
            offer_id: form.offerId,
            status,
            client_name: form.clientName,
            transport_mode: form.transportMode,
            origin: form.originPort,
            destination: form.destinationPort,
            cargo_type: form.cargoType,
            description: form.description,
            weight_kg: form.weight,
            incoterm: form.incoterm,
            urgency: 'Media',
            company: form.clientName,
        };

        const { data } = await window.axios.post('/api/client/orders', payload);
        submitMessage.value = `Pedido creado correctamente: ${data.offer_id}`;

        if (status !== 'Borrador') {
            window.location.href = '/cliente/mis-pedidos';
        }
    } catch (error) {
        submitError.value = true;
        submitMessage.value = error?.response?.data?.message || 'No se pudo crear el pedido. Revisa los campos.';
    } finally {
        isSubmitting.value = false;
    }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700;800&display=swap');

.form-shell {
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

.top-actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
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
    padding: 0.9rem;
}

.stepper {
    background: #0a2243;
    border-radius: 8px;
    padding: 0.65rem 0.8rem;
    display: flex;
    align-items: center;
}

.step {
    display: flex;
    align-items: center;
    gap: 0.45rem;
    color: #8ea8cb;
    min-width: 0;
}

.step strong {
    display: block;
    font-size: 0.66rem;
    color: #edf3ff;
}

.step small {
    display: block;
    font-size: 0.56rem;
}

.bubble {
    width: 21px;
    height: 21px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.62rem;
    font-weight: 800;
    background: #f8fbff;
    color: #13253e;
}

.step.active .bubble {
    background: #ff7e26;
    color: #fff;
}

.step.done .bubble {
    background: #2ac86f;
    color: #fff;
}

.line {
    height: 1px;
    background: #264d7b;
    flex: 1;
    margin: 0 0.45rem;
}

.line.on {
    background: #2ac86f;
}

.step-body {
    padding: 0.8rem 0.2rem 0;
}

.step-body h2,
.step-body h3 {
    margin: 0.35rem 0 0.7rem;
    font-size: 0.76rem;
    font-weight: 800;
}

.grid {
    display: grid;
    gap: 0.65rem;
    margin-bottom: 0.65rem;
}

.grid.two {
    grid-template-columns: repeat(2, minmax(0, 1fr));
}

.grid.three {
    grid-template-columns: repeat(3, minmax(0, 1fr));
}

label {
    display: flex;
    flex-direction: column;
    gap: 0.2rem;
    font-size: 0.62rem;
    color: #6c7f98;
    font-weight: 700;
}

input,
textarea {
    border: 1px solid #d6dee8;
    border-radius: 8px;
    background: #f6f8fb;
    font-size: 0.78rem;
    color: #1b2f4c;
    padding: 0.56rem 0.66rem;
}

textarea {
    min-height: 72px;
    resize: vertical;
}

label small {
    font-size: 0.56rem;
    color: #8ea0b8;
    font-weight: 600;
}

.transport-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 0.6rem;
    margin-bottom: 0.75rem;
}

.transport-card {
    border: 1px solid #d6dee8;
    background: #f6f8fb;
    border-radius: 9px;
    padding: 0.72rem;
    text-align: left;
}

.transport-card.selected {
    border-color: #ff7e26;
    box-shadow: 0 0 0 1px #ff7e26 inset;
    background: #fff4eb;
}

.transport-card strong {
    display: block;
    font-size: 0.73rem;
    color: #1c3150;
}

.transport-card small {
    font-size: 0.58rem;
    color: #6f829c;
}

.segmented {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.55rem;
}

.segmented button {
    border: 1px solid #d6dee8;
    background: #f6f8fb;
    border-radius: 8px;
    padding: 0.56rem;
    font-size: 0.68rem;
    color: #1f3556;
    text-align: left;
    font-weight: 700;
}

.segmented button.selected {
    border-color: #2f69bd;
    box-shadow: 0 0 0 1px #2f69bd inset;
}

.warning-box {
    margin-top: 0.45rem;
    border: 1px solid #ff934f;
    border-radius: 8px;
    padding: 0.62rem;
    background: #fff8f2;
}

.warning-box p {
    margin: 0 0 0.35rem;
    font-size: 0.63rem;
    color: #df6f2a;
    font-weight: 700;
}

.wizard-footer {
    margin-top: 0.8rem;
    padding-top: 0.7rem;
    border-top: 1px solid #dee6f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.7rem;
}

.progress-info span {
    display: block;
    font-size: 0.62rem;
    color: #7d8fa8;
    margin-bottom: 0.2rem;
    font-weight: 700;
}

.progress-track {
    width: 120px;
    height: 4px;
    background: #d3dce8;
    border-radius: 999px;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #2f69bd 0%, #ff7e26 100%);
}

.footer-actions {
    display: flex;
    gap: 0.45rem;
}

.btn {
    border-radius: 8px;
    font-size: 0.68rem;
    font-weight: 700;
    border: 1px solid #d2dae6;
    padding: 0.45rem 0.8rem;
    background: #f9fbff;
    color: #1f3556;
}

.btn.primary {
    background: #ff7e26;
    color: #fff;
    border-color: #ff7e26;
}

.submit-message {
    margin: 0.65rem 0 0;
    font-size: 0.7rem;
    font-weight: 700;
    color: #2b7a4e;
}

.submit-message.error {
    color: #be4d43;
}

@media (max-width: 1100px) {
    .form-shell {
        grid-template-columns: 1fr;
    }

    .transport-grid,
    .grid.two,
    .grid.three {
        grid-template-columns: 1fr;
    }

    .wizard-footer {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>
