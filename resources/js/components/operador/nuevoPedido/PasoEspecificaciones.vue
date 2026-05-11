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
                    <div v-for="t in transports" :key="t.id" @click="seleccionar('tipus_transport_id', t.id)"
                        class="border-2 rounded-xl p-4 text-center cursor-pointer transition" :class="local.tipus_transport_id == t.id
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
                    <div @click="seleccionar('tipus_fluxe_id', 1)"
                        class="border-2 rounded-xl p-4 cursor-pointer transition" :class="local.tipus_fluxe_id == 1
                            ? 'border-orange-400 bg-orange-50'
                            : 'border-gray-200 hover:border-gray-300'">
                        <p class="text-sm font-medium text-gray-700">📥 Importación</p>
                        <p class="text-xs text-gray-400">Mercancía entra hacia España</p>
                    </div>
                    <div @click="seleccionar('tipus_fluxe_id', 2)"
                        class="border-2 rounded-xl p-4 cursor-pointer transition" :class="local.tipus_fluxe_id == 2
                            ? 'border-orange-400 bg-orange-50'
                            : 'border-gray-200 hover:border-gray-300'">
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
                <select v-model="local.tipus_carrega_id"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                    <option value="">Seleciona un tipo</option>
                    <option v-for="t in tipusCarrega" :key="t.id" :value="t.id">
                        {{ t.tipus }}
                    </option>
                </select>
            </div>

            <!-- Peso bruto -->
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Peso Bruto (kg)</label>
                <input type="number" placeholder="0" v-model="local.pes_brut"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400" />
            </div>

            <!-- Volumen -->
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Volumen (m³)</label>
                <input type="number" placeholder="0" v-model="local.volum"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400" />
            </div>

            <!-- Número de bultos -->
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Nº Bultos</label>
                <input type="number" placeholder="1" v-model="local.num_bultos"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400" />
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
    name: 'PasoEspecificaciones',

    props: {
        datos: { type: Object, required: true },
        transports: { type: Array, required: true },
        tipusCarrega: { type: Array, required: true },
        errors: { type: Object, required: true },
    },

    data() {
        return {
            local: {
                tipus_transport_id: this.datos.tipus_transport_id,
                tipus_fluxe_id: this.datos.tipus_fluxe_id,
                tipus_carrega_id: this.datos.tipus_carrega_id,
                pes_brut: this.datos.pes_brut,
                volum: this.datos.volum,
                num_bultos: this.datos.num_bultos,
            }
        }
    },

    methods: {
        seleccionar(campo, valor) {
            this.local[campo] = valor
        },
        guardarPaso() {
            this.$emit('actualizar', { ...this.local })
        },

        // Devuelve el icono según el tipo de transporte
        iconTransport(tipus) {
            const iconos = {
                'Marítimo': '🚢',
                'Aéreo': '✈️',
                'Terrestre': '🚛',
                'Multimodal': '🔄',
            }
            return iconos[tipus] ?? '📦'
        }

    }
}
</script>
