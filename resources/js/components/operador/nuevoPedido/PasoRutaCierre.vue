<template>
    <div class="bg-white rounded-xl shadow-sm p-6 mb-4">
        <h2 class="text-base font-semibold text-blue-700 border-l-4 border-blue-700 pl-3 mb-6">
            Planificación de Ruta
        </h2>

        <div class="grid grid-cols-2 gap-6">

            <!-- Puerto Origen — select con datos reales de la BD -->
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Puerto Origen</label>
                <select v-model="local.port_origen_id"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                    <option value="">Selecciona un puerto</option>
                    <option v-for="p in ports" :key="p.id" :value="p.id">
                        {{ p.nom }}
                    </option>
                </select>
            </div>

            <!-- Puerto Destino — select con datos reales de la BD -->
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Puerto Destino</label>
                <select v-model="local.port_desti_id"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                    <option value="">Selecciona un puerto</option>
                    <option v-for="p in ports" :key="p.id" :value="p.id">
                        {{ p.nom }}
                    </option>
                </select>
            </div>

            <!-- Agente Aduanal — select con datos reales de la BD -->
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Agente Aduanal</label>
                <select v-model="local.transportista_id"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                    <option value="">Selecciona un agente</option>
                    <option v-for="t in transportistes" :key="t.id" :value="t.id">
                        {{ t.nom }}
                    </option>
                </select>
            </div>

            <!-- Fecha Emisión -->
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Fecha Emisión</label>
                <input type="date" v-model="local.data_validessa_inicial"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400" />
            </div>

        </div>

        <!-- Cierre Financiero -->
        <h2 class="text-base font-semibold text-blue-700 border-l-4 border-blue-700 pl-3 mt-8 mb-6">
            Cierre Financiero
        </h2>

        <div class="grid grid-cols-2 gap-6">

            <!-- Vigencia -->
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Vigencia</label>
                <input type="date" v-model="local.data_validessa_final"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400" />
            </div>

            <!-- Incoterm — select con datos reales de la BD -->
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Incoterm</label>
                <select v-model="local.incoterm_id"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                    <option value="">Selecciona un incoterm</option>
                    <option v-for="i in tipusIncoterms" :key="i.id" :value="i.id">
                        {{ i.codi }} — {{ i.nom }}
                    </option>
                </select>
            </div>

        </div>

        <!-- Instrucciones y Observaciones -->
        <h2 class="text-base font-semibold text-blue-700 border-l-4 border-blue-700 pl-3 mt-8 mb-6">
            Instrucciones y Observaciones
        </h2>

        <div class="grid grid-cols-2 gap-6">

            <!-- Instrucciones especiales -->
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Instrucciones Especiales</label>
                <textarea placeholder="Instrucciones para el operador..." v-model="local.instruccions" rows="3"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"></textarea>
            </div>

            <!-- Observaciones internas -->
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Observaciones Internas</label>
                <textarea placeholder="Notas internas..." v-model="local.observacions" rows="3"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"></textarea>
            </div>

            <!-- Motivo de rechazo — solo visible si existe el campo -->
            <div v-if="datos.rao_rebuig !== undefined" class="col-span-2">
                <div class="border border-orange-200 bg-orange-50 rounded-xl p-4">
                    <p class="text-xs text-orange-600 font-medium mb-2">⚠️ Motivo de Rechazo</p>
                    <textarea placeholder="Motivo del rechazo..." v-model="local.rao_rebuig" rows="3"
                        class="w-full border border-orange-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 bg-white"></textarea>
                </div>
            </div>

        </div>

        <!-- Botón guardar paso -->
        <div class="flex justify-end mt-6">
            <button @click="guardarPaso"
                class="bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium px-6 py-2 rounded-lg transition">
                Guardar oferta ✓
            </button>
        </div>
    </div>
</template>

<script>
import { reactive } from 'vue'

export default {
    name: 'PasoRutaCierre',

    props: {
        datos: { type: Object, required: true },
        errors: { type: Object, required: true },
        ports: { type: Array, required: true },
        transportistes: { type: Array, required: true },
        tipusIncoterms: { type: Array, required: true },
    },

    setup(props, { emit }) {

        // Estado local — copia de los datos del padre
        const local = reactive({
            port_origen_id: props.datos.port_origen_id || null,
            port_desti_id: props.datos.port_desti_id || null,
            transportista_id: props.datos.transportista_id || null,
            data_validessa_inicial: props.datos.data_validessa_inicial || '',
            data_validessa_final: props.datos.data_validessa_final || '',
            incoterm_id: props.datos.incoterm_id || null,
            instruccions: props.datos.instruccions || '',
            observacions: props.datos.observacions || '',
            rao_rebuig: props.datos.rao_rebuig || '',
        })

        // Solo emite cuando el usuario pulsa el botón
        function guardarPaso() {
            emit('actualizar', {
                port_origen_id: local.port_origen_id,
                port_desti_id: local.port_desti_id,
                transportista_id: local.transportista_id,
                data_validessa_inicial: local.data_validessa_inicial,
                data_validessa_final: local.data_validessa_final,
                incoterm_id: local.incoterm_id,
                instruccions: local.instruccions,
                observacions: local.observacions,
                rao_rebuig: local.rao_rebuig,
            })
        }

        return { local, guardarPaso }
    }
}
</script>
