<template>
    <div class="chatbot-wrapper">

        <!-- Ventana del chat -->
        <Transition name="chat-slide">
            <div v-if="abierto" class="chat-window">

                <!-- Header -->
                <div class="chat-header">
                    <div class="chat-header-info">
                        <div class="chat-avatar">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path
                                    d="M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 1 2 2v4M9 3v18m0 0h10a2 2 0 0 0 2-2V9M9 21H5a2 2 0 0 1-2-2V9m0 0h18" />
                            </svg>
                        </div>
                        <div>
                            <p class="chat-nombre">Prime Analytics</p>
                            <p class="chat-estado">
                                <span class="estado-dot"></span>
                                Conectado a la BD
                            </p>
                        </div>
                    </div>
                    <button @click="cerrar" class="chat-close-btn" title="Cerrar">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M18 6L6 18M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Mensajes -->
                <div class="chat-messages" ref="mensajesRef">

                    <!-- Bienvenida -->
                    <div class="msg-burbuja msg-bot">
                        <div class="msg-texto">
                            👋 Hola, soy tu asistente de análisis logístico.<br>
                            Puedo responder preguntas sobre <strong>commodities, países, rentabilidad y flujos
                                comerciales</strong>.<br><br>
                            Prueba con:
                            <span class="ejemplo-query"
                                @click="usarEjemplo('¿Qué categoría tiene mayor valor por kg?')">
                                → ¿Qué categoría tiene mayor valor por kg?
                            </span>
                            <span class="ejemplo-query" @click="usarEjemplo('¿Qué país exporta más en dólares?')">
                                → ¿Qué país exporta más en dólares?
                            </span>
                            <span class="ejemplo-query"
                                @click="usarEjemplo('¿Cuáles son las 5 categorías más rentables?')">
                                → ¿Cuáles son las 5 categorías más rentables?
                            </span>
                        </div>
                        <span class="msg-hora">{{ horaActual }}</span>
                    </div>

                    <!-- Historial -->
                    <template v-for="(msg, idx) in historial" :key="idx">
                        <div class="msg-burbuja" :class="msg.rol === 'usuario' ? 'msg-usuario' : 'msg-bot'">
                            <div class="msg-texto"
                                v-html="msg.rol === 'bot' ? formatearRespuesta(msg.texto) : msg.texto"></div>
                            <span class="msg-hora">{{ msg.hora }}</span>
                        </div>
                    </template>

                    <!-- Typing indicator -->
                    <div v-if="cargando" class="msg-burbuja msg-bot msg-typing">
                        <div class="typing-dots">
                            <span></span><span></span><span></span>
                        </div>
                    </div>

                </div>

                <!-- Input -->
                <div class="chat-input-area">
                    <input ref="inputRef" v-model="pregunta" @keydown.enter.prevent="enviar" :disabled="cargando"
                        type="text" placeholder="Pregunta sobre los datos logísticos..." class="chat-input"
                        maxlength="300" />
                    <button @click="enviar" :disabled="cargando || !pregunta.trim()" class="chat-send-btn">
                        <svg v-if="!cargando" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" />
                        </svg>
                        <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="spin">
                            <path
                                d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83" />
                        </svg>
                    </button>
                </div>

                <Transition name="fade">
                    <div v-if="error" class="chat-error">⚠️ {{ error }}</div>
                </Transition>

            </div>
        </Transition>

        <!-- FAB -->
        <button @click="toggleChat" class="chat-fab" :class="{ 'fab-abierto': abierto }">
            <Transition name="icon-swap" mode="out-in">
                <svg v-if="!abierto" key="chat" viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z" />
                </svg>
                <svg v-else key="close" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M18 6L6 18M6 6l12 12" />
                </svg>
            </Transition>
            <span v-if="!abierto && mensajesSinLeer > 0" class="fab-badge">{{ mensajesSinLeer }}</span>
        </button>

    </div>
</template>

<script setup>
// ─── Imports de Vue 3 Composition API ────────────────────────────────────────
// ref()      → hace una variable reactiva (equivale a data() en Options API)
// computed() → valor calculado que se actualiza automáticamente
// nextTick() → ejecuta código DESPUÉS de que Vue actualice el DOM
import { ref, computed, nextTick } from 'vue'

// ─── Constante de configuración ──────────────────────────────────────────────
// Cambia según entorno:
//   Local:      'http://localhost:5678/webhook/chatbot'
//   VPS:        'http://vps-5d4cfa08.vps.ovh.net:5678/webhook/chatbot'
const WEBHOOK_URL = 'http://localhost:5678/webhook/chatbot'

// ─── Estado reactivo ──────────────────────────────────────────────────────────
// ref(valorInicial) crea una variable reactiva.
// Para leerla: variable.value   Para escribirla: variable.value = nuevoValor
// En el template se usa directamente sin .value (Vue lo hace automático)
const abierto = ref(false)
const pregunta = ref('')
const historial = ref([])    // Array de { rol: 'usuario'|'bot', texto, hora }
const cargando = ref(false)
const error = ref(null)
const mensajesSinLeer = ref(0)

// ─── Referencias al DOM ───────────────────────────────────────────────────────
// ref(null) + el atributo ref="nombre" en el template dan acceso directo al elemento HTML.
// Equivale a this.$refs.nombre en Options API.
const mensajesRef = ref(null)   // El div scrollable de mensajes
const inputRef = ref(null)   // El campo de texto

// ─── Computed ─────────────────────────────────────────────────────────────────
// Se recalcula automáticamente. Como la hora cambia cada minuto,
// cada vez que Vue vuelva a renderizar este computed mostrará la hora correcta.
const horaActual = computed(() =>
    new Date().toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' })
)

// ─── Funciones ────────────────────────────────────────────────────────────────

function toggleChat() {
    abierto.value = !abierto.value
    if (abierto.value) {
        mensajesSinLeer.value = 0
        // nextTick: Vue actualiza el DOM de forma asíncrona.
        // Sin nextTick, el div .chat-messages aún no existe cuando intentamos hacer scroll.
        nextTick(() => {
            scrollAbajo()
            inputRef.value?.focus()   // ?. = optional chaining: no falla si inputRef es null
        })
    }
}

function cerrar() {
    abierto.value = false
}

function usarEjemplo(texto) {
    pregunta.value = texto
    nextTick(() => inputRef.value?.focus())
}

function scrollAbajo() {
    if (mensajesRef.value) {
        mensajesRef.value.scrollTop = mensajesRef.value.scrollHeight
    }
}

// Convierte texto plano de Ollama/LLM a HTML básico
// Se aplica solo en mensajes del bot (v-html)
function formatearRespuesta(texto) {
    if (!texto) return ''

    // Paso 1: Escapar HTML → evita XSS si el LLM devuelve etiquetas maliciosas
    let html = texto
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')

    // Paso 2: **negrita** → <strong>
    html = html.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')

    // Paso 3: Saltos de línea \n → <br>
    html = html.replace(/\n/g, '<br>')

    // Paso 4: Listas "- item" → "• item"
    html = html.replace(/(^|<br>)- (.+)/g, '$1<span class="list-item">• $2</span>')

    return html
}

async function enviar() {
    const textoPregunta = pregunta.value.trim()
    if (!textoPregunta || cargando.value) return

    error.value = null
    pregunta.value = ''

    historial.value.push({
        rol: 'usuario',
        texto: textoPregunta,
        hora: horaActual.value
    })

    cargando.value = true
    await nextTick()
    scrollAbajo()

    try {
        const response = await fetch(WEBHOOK_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ pregunta: textoPregunta })
        })

        if (!response.ok) throw new Error(`HTTP ${response.status}`)

        const data = await response.json()
        console.log('[N8N raw response]', JSON.stringify(data, null, 2)) // ← temporal para debug

        const textoRespuesta =
            data?.respuesta ||
            data?.output ||
            data?.message ||
            data?.text ||
            data?.reply ||
            data?.[0]?.respuesta ||
            data?.[0]?.output ||
            data?.[0]?.message ||
            data?.[0]?.text ||
            (() => {
                console.warn('[Chatbot] Respuesta inesperada de N8N:', data)
                return '⚠️ El asistente devolvió un formato inesperado. Revisa el nodo Code de N8N.'
            })()

        historial.value.push({ rol: 'bot', texto: textoRespuesta, hora: horaActual.value })

        if (!abierto.value) mensajesSinLeer.value++

    } catch (err) {
        console.error('[ChatbotFlotante]', err)
        error.value = 'No se pudo conectar con el asistente. ¿Está N8N corriendo?'
        historial.value.push({
            rol: 'bot',
            texto: '❌ Error de conexión. Verifica que N8N esté activo en el puerto 5678.',
            hora: horaActual.value
        })
    } finally {
        cargando.value = false
        await nextTick()
        scrollAbajo()
        inputRef.value?.focus()
    }
}
</script>

<style scoped>
.chatbot-wrapper {
    --azul: #1766B5;
    --azul-dark: #1255a0;
    --naranja: #FD7121;
    --navy: #0d1e35;
    --blanco: #ffffff;
    --gris-bg: #f4f6f9;
    --gris-borde: #e0e6ef;
    --texto: #1a2940;
    --texto-soft: #6b7a99;
    --sombra: 0 8px 32px rgba(23, 102, 181, 0.18);
    --radio: 16px;
    font-family: 'Roboto', sans-serif;

    position: fixed;
    bottom: 28px;
    right: 28px;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 12px;
}

.chat-window {
    width: 360px;
    max-height: 560px;
    background: var(--blanco);
    border-radius: var(--radio) var(--radio) 0 var(--radio);
    box-shadow: var(--sombra), 0 2px 8px rgba(0, 0, 0, 0.08);
    border: 1px solid var(--gris-borde);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.chat-header {
    background: linear-gradient(135deg, var(--azul) 0%, #1a5fa8 100%);
    padding: 14px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-shrink: 0;
}

.chat-header-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.chat-avatar {
    width: 38px;
    height: 38px;
    background: rgba(255, 255, 255, 0.18);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    flex-shrink: 0;
}

.chat-avatar svg {
    width: 20px;
    height: 20px;
}

.chat-nombre {
    color: white;
    font-weight: 700;
    font-size: 14px;
    margin: 0;
}

.chat-estado {
    color: rgba(255, 255, 255, 0.8);
    font-size: 11px;
    margin: 2px 0 0;
    display: flex;
    align-items: center;
    gap: 5px;
}

.estado-dot {
    width: 7px;
    height: 7px;
    background: #4ade80;
    border-radius: 50%;
    box-shadow: 0 0 6px rgba(74, 222, 128, 0.7);
    animation: pulso 2s infinite;
}

@keyframes pulso {

    0%,
    100% {
        opacity: 1
    }

    50% {
        opacity: 0.5
    }
}

.chat-close-btn {
    width: 30px;
    height: 30px;
    background: rgba(255, 255, 255, 0.15);
    border: none;
    border-radius: 8px;
    cursor: pointer;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
}

.chat-close-btn:hover {
    background: rgba(255, 255, 255, 0.28);
}

.chat-close-btn svg {
    width: 16px;
    height: 16px;
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 16px 12px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    background: var(--gris-bg);
    scroll-behavior: smooth;
}

.chat-messages::-webkit-scrollbar {
    width: 4px;
}

.chat-messages::-webkit-scrollbar-track {
    background: transparent;
}

.chat-messages::-webkit-scrollbar-thumb {
    background: var(--gris-borde);
    border-radius: 4px;
}

.msg-burbuja {
    display: flex;
    flex-direction: column;
    max-width: 88%;
    animation: msg-in 0.25s ease;
}

@keyframes msg-in {
    from {
        opacity: 0;
        transform: translateY(8px)
    }

    to {
        opacity: 1;
        transform: translateY(0)
    }
}

.msg-bot {
    align-self: flex-start;
    align-items: flex-start;
}

.msg-usuario {
    align-self: flex-end;
    align-items: flex-end;
}

.msg-texto {
    padding: 10px 13px;
    border-radius: 12px;
    font-size: 13px;
    line-height: 1.55;
    color: var(--texto);
}

.msg-bot .msg-texto {
    background: var(--blanco);
    border: 1px solid var(--gris-borde);
    border-radius: 4px 12px 12px 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.msg-usuario .msg-texto {
    background: var(--azul);
    color: white;
    border-radius: 12px 4px 12px 12px;
}

.msg-hora {
    font-size: 10px;
    color: var(--texto-soft);
    margin-top: 3px;
    padding: 0 3px;
}

.ejemplo-query {
    display: block;
    margin-top: 5px;
    color: var(--azul);
    cursor: pointer;
    font-size: 12px;
    text-decoration: underline;
    text-underline-offset: 2px;
    transition: color 0.15s;
}

.ejemplo-query:hover {
    color: var(--naranja);
}

:deep(.list-item) {
    display: block;
    padding-left: 8px;
}

.msg-typing .msg-texto {
    padding: 12px 16px;
}

.typing-dots {
    display: flex;
    gap: 5px;
    align-items: center;
}

.typing-dots span {
    width: 8px;
    height: 8px;
    background: var(--azul);
    border-radius: 50%;
    opacity: 0.4;
    animation: dot-bounce 1.4s infinite;
}

.typing-dots span:nth-child(2) {
    animation-delay: 0.2s;
}

.typing-dots span:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes dot-bounce {

    0%,
    80%,
    100% {
        transform: translateY(0);
        opacity: 0.4
    }

    40% {
        transform: translateY(-6px);
        opacity: 1
    }
}

.chat-input-area {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 12px;
    border-top: 1px solid var(--gris-borde);
    background: var(--blanco);
    flex-shrink: 0;
}

.chat-input {
    flex: 1;
    border: 1px solid var(--gris-borde);
    border-radius: 10px;
    padding: 9px 13px;
    font-size: 13px;
    font-family: 'Roboto', sans-serif;
    color: var(--texto);
    background: var(--gris-bg);
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.chat-input:focus {
    border-color: var(--azul);
    box-shadow: 0 0 0 3px rgba(23, 102, 181, 0.12);
    background: var(--blanco);
}

.chat-input:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.chat-send-btn {
    width: 38px;
    height: 38px;
    background: var(--azul);
    border: none;
    border-radius: 10px;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: background 0.2s, transform 0.1s;
}

.chat-send-btn:hover:not(:disabled) {
    background: var(--azul-dark);
    transform: scale(1.05);
}

.chat-send-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.chat-send-btn svg {
    width: 18px;
    height: 18px;
}

.spin {
    animation: girar 1s linear infinite;
}

@keyframes girar {
    to {
        transform: rotate(360deg);
    }
}

.chat-error {
    padding: 8px 12px;
    background: #fff3cd;
    border-top: 1px solid #ffc107;
    font-size: 12px;
    color: #856404;
    flex-shrink: 0;
}

.chat-fab {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, var(--azul) 0%, #1a5fa8 100%);
    border: none;
    border-radius: 50%;
    color: white;
    cursor: pointer;
    box-shadow: 0 4px 18px rgba(23, 102, 181, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    flex-shrink: 0;
    transition: transform 0.25s cubic-bezier(.34, 1.56, .64, 1), box-shadow 0.2s;
}

.chat-fab:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 24px rgba(23, 102, 181, 0.5);
}

.chat-fab.fab-abierto {
    background: linear-gradient(135deg, var(--navy) 0%, #1a2940 100%);
}

.chat-fab svg {
    width: 24px;
    height: 24px;
}

.fab-badge {
    position: absolute;
    top: -3px;
    right: -3px;
    background: var(--naranja);
    color: white;
    font-size: 10px;
    font-weight: 700;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid white;
    animation: pop 0.3s cubic-bezier(.34, 1.56, .64, 1);
}

@keyframes pop {
    from {
        transform: scale(0)
    }

    to {
        transform: scale(1)
    }
}

.chat-slide-enter-active {
    transition: all 0.3s cubic-bezier(.34, 1.56, .64, 1);
}

.chat-slide-leave-active {
    transition: all 0.2s ease;
}

.chat-slide-enter-from {
    opacity: 0;
    transform: translateY(20px) scale(0.95);
}

.chat-slide-leave-to {
    opacity: 0;
    transform: translateY(12px) scale(0.97);
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.icon-swap-enter-active,
.icon-swap-leave-active {
    transition: all 0.2s;
}

.icon-swap-enter-from {
    opacity: 0;
    transform: rotate(-90deg) scale(0.5);
}

.icon-swap-leave-to {
    opacity: 0;
    transform: rotate(90deg) scale(0.5);
}
</style>
