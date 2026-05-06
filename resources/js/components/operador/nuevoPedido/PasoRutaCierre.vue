<template>
    <div class="bg-white rounded-xl shadow-sm p-6 mb-4">
        <h2 class="text-base font-semibold text-blue-700 border-l-4 border-blue-700 pl-3 mb-6">
            Planificación de Ruta
        </h2>

        <div class="grid grid-cols-2 gap-6">

            <!-- Puerto Origen -->
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Puerto Origen</label>
                <input
                    type="text"
                    placeholder="Ej: Valencia (VLC)"
                    :value="datos.port_origen_id"
                    @input="emitir('port_origen_id', $event.target.value)"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
                />
            </div>

            <!-- Puerto Destino -->
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Puerto Destino</label>
                <input
                    type="text"
                    placeholder="Ej: Shanghai (SHA)"
                    :value="datos.port_desti_id"
                    @input="emitir('port_desti_id', $event.target.value)"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
                />
            </div>

            <!-- Agente Aduanal -->
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Agente Aduanal</label>
                <input
                    type="text"
                    placeholder="Ej: Aduana Express SL"
                    :value="datos.transportista_id"
                    @input="emitir('transportista_id', $event.target.value)"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
                />
            </div>

            <!-- Fecha Emisión -->
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Fecha Emisión</label>
                <input
                    type="date"
                    :value="datos.data_validessa_inicial"
                    @input="emitir('data_validessa_inicial', $event.target.value)"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
                />
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
                <input
                    type="date"
                    :value="datos.data_validessa_final"
                    @input="emitir('data_validessa_final', $event.target.value)"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
                />
            </div>

            <!-- Margen Rentabilidad -->
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Margen Rentabilidad (EUR)</label>
                <input
                    type="number"
                    placeholder="0.00"
                    :value="datos.incoterm_id"
                    @input="emitir('incoterm_id', $event.target.value)"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
                />
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
                <textarea
                    placeholder="Instrucciones para el operador..."
                    :value="datos.instruccions"
                    @input="emitir('instruccions', $event.target.value)"
                    rows="3"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
                ></textarea>
            </div>

            <!-- Observaciones internas -->
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Observaciones Internas</label>
                <textarea
                    placeholder="Notas internas..."
                    :value="datos.observacions"
                    @input="emitir('observacions', $event.target.value)"
                    rows="3"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
                ></textarea>
            </div>

            <!-- Motivo de rechazo — solo visible si el estado es Rechazada -->
            <div v-if="datos.rao_rebuig !== undefined" class="col-span-2">
                <div class="border border-orange-200 bg-orange-50 rounded-xl p-4">
                    <p class="text-xs text-orange-600 font-medium mb-2">⚠️ Motivo de Rechazo</p>
                    <textarea
                        placeholder="Motivo del rechazo..."
                        :value="datos.rao_rebuig"
                        @input="emitir('rao_rebuig', $event.target.value)"
                        rows="3"
                        class="w-full border border-orange-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 bg-white"
                    ></textarea>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
export default {
    name: 'PasoRutaCierre',

    // Props — datos que recibe del padre, no se modifican aquí
    props: {
        datos:  { type: Object, required: true }, // formulario completo
        errors: { type: Object, required: true }, // errores de validación
    },

    setup(props, { emit }) {

        // Avisa al padre qué campo cambió y con qué valor
        function emitir(campo, valor) {
            emit('actualizar', { [campo]: valor })
        }

        return { emitir }
    }
}
</script>
