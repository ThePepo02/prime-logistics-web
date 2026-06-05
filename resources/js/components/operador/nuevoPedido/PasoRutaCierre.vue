<template>
    <div class="bg-white rounded-xl shadow-sm p-6 mb-4">
        <h2 class="text-base font-semibold text-blue-700 border-l-4 border-blue-700 pl-3 mb-6">
            Planificación de Ruta
        </h2>

        <div class="grid grid-cols-2 gap-6">

            <!-- Puerto Origen -->
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

            <!-- Puerto Destino -->
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

            <!-- Agente Aduanal -->
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

            <!-- Margen Rentabilidad -->
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Margen Rentabilidad (EUR)</label>
                <select v-model="local.incoterm_id"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                    <option value="">Seleciona un incoterm</option>
                    <option v-for="i in tipusIncoterms" :key="i.id" :value="i.id">
                        {{ i.codi }} - {{ i.nom }}
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

            <!-- Motivo de rechazo — solo visible si el estado es Rechazada -->
            <div v-if="datos.rao_rebuig !== undefined" class="col-span-2">
                <div class="border border-orange-200 bg-orange-50 rounded-xl p-4">
                    <p class="text-xs text-orange-600 font-medium mb-2">⚠️ Motivo de Rechazo</p>
                    <textarea placeholder="Motivo del rechazo..." v-model="local.rao_rebuig" rows="3"
                        class="w-full border border-orange-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 bg-white"></textarea>
                </div>
            </div>

        </div>
        <!-- Botón guardar paso -->
        <div class="flex justify-end mt-4">
            <button @click="guardarPaso"
                class="px-5 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                Guardar datos del paso ✓
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: 'PasoRutaCierre',

    props: {
        datos: { type: Object, required: true },
        errors: { type: Object, required: true },
        ports: { type: Array, required: true },
        transportistes: { type: Array, required: true },
        tipusIncoterms: { type: Array, required: true },
    },

    data() {
        return {
            local: {
                port_origen_id: this.datos.port_origen_id,
                port_desti_id: this.datos.port_desti_id,
                transportista_id: this.datos.transportista_id,
                data_validessa_inicial: this.datos.data_validessa_inicial,
                data_validessa_final: this.datos.data_validessa_final,
                incoterm_id: this.datos.incoterm_id,
                instruccions: this.datos.instruccions,
                observacions: this.datos.observacions,
                rao_rebuig: this.datos.rao_rebuig,
            }
        }
    },

    methods: {
        guardarPaso() {
            this.$emit('actualizar', { ...this.local })
        }
    }
}
</script>
