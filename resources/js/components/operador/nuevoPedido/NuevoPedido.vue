<template>
    <div class="p-6">


        <div class="flex items-center justify-center mb-8">
            <div v-for="(paso, index) in pasos" :key="index" class="flex items-center">
                <!-- Círculo del paso -->
                <div class="flex flex-col items-center">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold" :class="pasoActual > index + 1
                        ? 'bg-green-500 text-white'
                        : pasoActual === index + 1
                            ? 'bg-orange-500 text-white'
                            : 'bg-gray-200 text-gray-500'">
                        <span v-if="pasoActual > index + 1">✓</span>
                        <span v-else>{{ index + 1 }}</span>
                    </div>
                    <span class="text-xs mt-1 text-gray-500">{{ paso }}</span>
                </div>
                <!-- Línea entre pasos -->
                <div v-if="index < pasos.length - 1" class="w-24 h-0.5 mx-2 mb-4"
                    :class="pasoActual > index + 1 ? 'bg-green-500' : 'bg-gray-200'"></div>
            </div>
        </div>

        <!-- Paso 1 — Identificación -->
        <PasoIdentificacion v-if="pasoActual === 1" :datos="formulario" :clientes="clientes" :errors="errors"
            @actualizar="actualizarDatos" />

        <!-- Paso 2 — Especificaciones -->
        <PasoEspecificaciones v-if="pasoActual === 2" :datos="formulario" :transports="transports" :errors="errors"
            @actualizar="actualizarDatos" />

        <!-- Paso 3 — Ruta y Cierre -->
        <PasoRutaCierre v-if="pasoActual === 3" :datos="formulario" :errors="errors" @actualizar="actualizarDatos" />

        <!-- Botones de navegación -->
        <NavegacionPasos :pasoActual="pasoActual" :totalPasos="pasos.length" :guardando="guardando"
            @anterior="pasoAnterior" @siguiente="pasoSiguiente" @guardarBorrador="guardarBorrador"
            @enviarPedido="enviarPedido" />

        <!-- Mensaje de éxito o error -->
        <div v-if="mensaje.texto" class="mt-4 p-3 rounded-lg text-sm font-medium"
            :class="mensaje.tipo === 'ok' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'">
            {{ mensaje.texto }}
        </div>

    </div>
</template>

<script>
import { ref, reactive, onMounted } from 'vue'
import axios from '../../axios.js' // importar nuestro axios configurado
import PasoIdentificacion from './nuevoPedido/PasoIdentificacion.vue'
import PasoEspecificaciones from './nuevoPedido/PasoEspecificaciones.vue'
import PasoRutaCierre from './nuevoPedido/PasoRutaCierre.vue'
import NavegacionPasos from './nuevoPedido/NavegacionPasos.vue'

export default {
    name: 'NuevoPedido',
    components: {
        PasoIdentificacion,
        PasoEspecificaciones,
        PasoRutaCierre,
        NavegacionPasos
    },
    setup() {

        // --- ESTADO ---
        const pasoActual = ref(1)
        const pasos = ['Identificación', 'Especificaciones', 'Ruta y Cierre']
        const guardando = ref(false)
        const clientes = ref([])
        const transports = ref([])

        // Todos los datos del formulario en un solo objeto reactivo
        const formulario = reactive({
            client_id: null,
            agent_comercial_id: null,
            tipus_transport_id: null,
            tipus_fluxes_id: null,
            tipus_carrega_id: null,
            incoterm_id: null,
            tipus_contenidor_id: null,
            transportista_id: null,
            port_origen_id: null,
            port_desti_id: null,
            pes_brut: null,
            volum: null,
            comentaris: '',
            data_validessa_inicial: '',
            data_validessa_final: '',
            estat_oferta_id: null,
            instruccions: '',
            observacions: '',
            rao_rebuig: '',
        })


        // Errores de validación por campo
        const errors = reactive({})

        // Mensaje de feedback para el usuario
        const mensaje = reactive({ texto: '', tipo: '' })

        // --- MÉTODOS ---

        // Carga clientes con rol_id = 3 desde la API
        async function carregarClientes() {
            try {
                const res = await axios.get('/clientes-rol')
                clientes.value = res.data
            } catch (error) {
                console.error('Error al cargar clientes:', error)
            }
        }

        // Carga tipos de transport desde la API
        async function carregarTransports() {
            try {
                const res = await axios.get('/tipus-transports')
                transports.value = res.data
            } catch (error) {
                console.error('Error al cargar tipos de transport:', error)
            }
        }

        // Recibe cambios del hijo y los guarda en formulario
        function actualizarDatos(nuevosDatos) {
            Object.assign(formulario, nuevosDatos)
        }

        // Valida campos obligatorios del paso actual
        function validarPaso() {
            Object.keys(errors).forEach(k => delete errors[k]) // Limpiar errores previos

            if (pasoActual.value === 1) {
                if (!formulario.client_id) errors.client_id = 'El cliente es obligatorio'
            }
            // Estos son campos ibligatorios random o que solo sean estos obligatorios??
            if (pasoActual.value === 2) {
                if (!formulario.tipus_transport_id) errors.tipus_transport_id = 'El tipo de transporte es obligatorio'
                if (!formulario.tipus_fluxes_id) errors.tipus_fluxes_id = 'El flujo es obligatorio'
            }

            return Object.keys(errors).length === 0
        }

        // Avanzar al siguiente paso si la validación pasa
        function pasoSiguiente() {
            if (validarPaso()) pasoActual.value++
        }

        // Retroceder al paso anterior. Solo retrocede si no estamos en el paso 1
        function pasoAnterior() {
            if (pasoActual.value > 1) pasoActual.value--
        }

        // Hace el INSERT en la BD via API (Hace un post) es el ultimo paso
        async function enviarFormulario(estatId) {
            guardando.value = true
            mensaje.texto = ''

            try {
                await axios.post('/ofertes', { ...formulario, estat_oferta_id: estatId })

                mensaje.texto = estatId === 1
                    ? 'Borrador guardado correctamente'
                    : 'Pedido enviado correctamente'
                mensaje.tipo = 'ok'

            } catch (e) {
                const data = e.response?.data
                mensaje.texto = data?.message || 'Error al guardar'
                mensaje.tipo = 'error'
                if (data?.errors) Object.assign(errors, data.errors)
            } finally {
                guardando.value = false
            }
        }


        // Guardar con estado borrador (id = 1)
        function guardarBorrador() {
            enviarFormulario(1)
        }

        // Envía el pedido (id = 2) con validación extra
        function enviarPedido() {
            if (validarPaso()) enviarFormulario(2)
        }

        // Al montar el componenete carga los datos necesarios
        onMounted(() => {
            carregarClientes()
            carregarTransports()
        })

        return { pasoActual, pasos, formulario, errors, mensaje, clientes, transports, guardando, actualizarDatos, pasoSiguiente, pasoAnterior, guardarBorrador, enviarPedido }
    }
}
</script>

<style lang="scss" scoped></style>
