<template>
    <div class="bg-white rounded-xl shadow-sm p-6 mb-4">
        <h2 class="text-base font-semibold text-blue-700 border-l-4 border-blue-700 pl-3 mb-6">
            Modalidad de Transporte
        </h2>

        <div class="grid grid-cols-2 gap-6">

            <!-- Tipo de transporte — tarjetas seleccionables -->
            <div class="col-span-2">
                <label class="text-xs text-gray-500 mb-2 block">Modo de Transporte *</label>
                <div class="grid grid-cols-4 gap-3">
                    <div
                        v-for="t in transports"
                        :key="t.id"
                        @click="emitir('tipus_transport_id', t.id)"
                        class="border-2 rounded-xl p-4 text-center cursor-pointer transition"
                        :class="datos.tipus_transport_id == t.id
                            ? 'border-orange-400 bg-orange-50'
                            : 'border-gray-200 hover:border-gray-300'">
                        <p class="text-2xl mb-1">{{ iconTransport(t.tipus) }}</p>
                        <p class="text-sm font-medium text-gray-700">{{ t.tipus }}</p>
                    </div>
                </div>
                <!-- Error si no selecciona transporte -->
                <p v-if="errors.tipus_transport_id" class="text-xs text-red-500 mt-1">
                    {{ errors.tipus_transport_id }}
                </p>
            </div>

            <!-- Flujo — Importación o Exportación -->
            <div class="col-span-2">
                <label class="text-xs text-gray-500 mb-2 block">Flujo *</label>
                <div class="grid grid-cols-2 gap-3">
                    <div
                        @click="emitir('tipus_fluxe_id', 1)"
                        class="border-2 rounded-xl p-4 cursor-pointer transition"
                        :class="datos.tipus_fluxe_id == 1
                            ? 'border-orange-400 bg-orange-50'
                            : 'border-gray-200 hover:border-gray-300'"
                    >
                        <p class="text-sm font-medium text-gray-700">📥 Importación</p>
                        <p class="text-xs text-gray-400">Mercancía entra hacia España</p>
                    </div>
                    <div
                        @click="emitir('tipus_fluxe_id', 2)"
                        class="border-2 rounded-xl p-4 cursor-pointer transition"
                        :class="datos.tipus_fluxe_id == 2
                            ? 'border-orange-400 bg-orange-50'
                            : 'border-gray-200 hover:border-gray-300'"
                    >
                        <p class="text-sm font-medium text-gray-700">📤 Exportación</p>
                        <p class="text-xs text-gray-400">Mercancía sale desde España</p>
                    </div>
                </div>
                <p v-if="errors.tipus_fluxe_id" class="text-xs text-red-500 mt-1">
                    {{ errors.tipus_fluxe_id }}
                </p>
            </div>

            <!-- Tipo de carga -->
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Tipo de Carga</label>
                <input
                    type="text"
                    placeholder="Ej: Carga General"
                    :value="datos.tipus_carrega_id"
                    @input="emitir('tipus_carrega_id', $event.target.value)"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
                />
            </div>

            <!-- Peso bruto -->
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Peso Bruto (kg)</label>
                <input
                    type="number"
                    placeholder="0"
                    :value="datos.pes_brut"
                    @input="emitir('pes_brut', $event.target.value)"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
                />
            </div>

            <!-- Volumen -->
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Volumen (m³)</label>
                <input
                    type="number"
                    placeholder="0"
                    :value="datos.volum"
                    @input="emitir('volum', $event.target.value)"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
                />
            </div>

            <!-- Número de bultos -->
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Nº Bultos</label>
                <input
                    type="number"
                    placeholder="1"
                    :value="datos.num_bultos"
                    @input="emitir('num_bultos', $event.target.value)"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
                />
            </div>

        </div>
    </div>
</template>

<script>
export default {
    name: 'PasoEspecificaciones',

    // Props — datos que recibe del padre, no se modifican aquí
    props: {
        datos:     { type: Object, required: true }, // formulario completo
        transports: { type: Array, required: true }, // lista de transportes de la API
        errors:    { type: Object, required: true }, // errores de validación
    },

    setup(props, { emit }) {

        // Avisa al padre qué campo cambió y con qué valor
        function emitir(campo, valor) {
            emit('actualizar', { [campo]: valor })
        }

        // Devuelve el icono según el tipo de transporte
        function iconTransport(tipus) {
            const iconos = {
                'Marítimo':  '🚢',
                'Aéreo':     '✈️',
                'Terrestre': '🚛',
                'Multimodal':'🔄',
            }
            return iconos[tipus] ?? '📦'
        }

        return { emitir, iconTransport }
    }
}
</script>
