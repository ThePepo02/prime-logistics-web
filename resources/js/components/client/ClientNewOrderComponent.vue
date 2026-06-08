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
const logoSrc = '/images/prime-logistics-logo.svg';
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
    if (currentStep.value === 1) return 'Especificaciones';
    return 'Ruta y Cierre';
});

const stepClass = (step) => ({
    active: currentStep.value === step,
    done: currentStep.value > step
});

const bubbleLabel = (step) => {
    if (currentStep.value > step) return '✓';
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
        const { data } = await window.axios.post('/client/orders', payload);
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