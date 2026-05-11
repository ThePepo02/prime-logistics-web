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
                        @click="local.tipus_transport_id = t.id"
                        class="border-2 rounded-xl p-4 text-center cursor-pointer transition"
                        :class="local.tipus_transport_id == t.id
                            ? 'border-orange-400 bg-orange-50'
                            : 'border-gray-200 hover:border-gray-300'">
                        <p class="text-2xl mb-1">{{ iconTransport(t.tipus) }}</p>
                        <p class="text-sm font-medium text-gray-700">{{ t.tipus }}</p>
                    </div>
                </div>
                <p v-if="errors.tipus_transport_id" class="text-xs text-red-500 mt-1">
                    {{ errors.tipus_transport_id }}
                </p>
            </div>

            <!-- Flujo — Importación o Exportación -->
            <div class="col-span-2">
                <label class="text-xs text-gray-500 mb-2 block">Flujo *</label>
                <div class="grid grid-cols-2 gap-3">
                    <div
                        @click="local.tipus_fluxe_id = 1"
                        class="border-2 rounded-xl p-4 cursor-pointer transition"
                        :class="local.tipus_fluxe_id == 1
                            ? 'border-orange-400 bg-orange-50'
                            : 'border-gray-200 hover:border-gray-300'"
                    >
                        <p class="text-sm font-medium text-gray-700">📥 Importación</p>
                        <p class="text-xs text-gray-400">Mercancía entra hacia España</p>
                    </div>
                    <div
                        @click="local.tipus_fluxe_id = 2"
                        class="border-2 rounded-xl p-4 cursor-pointer transition"
                        :class="local.tipus_fluxe_id == 2
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

            <!-- Tipo de carga — select con datos reales de la BD -->
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Tipo de Carga</label>
                <select
                    v-model="local.tipus_carrega_id"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
                >
                    <option value="">Selecciona un tipo</option>
                    <option v-for="t in tipusCarrega" :key="t.id" :value="t.id">
                        {{ t.tipus }}
                    </option>
                </select>
            </div>

            <!-- Peso bruto -->
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Peso Bruto (kg)</label>
                <input
                    type="number"
                    placeholder="0"
                    v-model="local.pes_brut"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
                />
            </div>

            <!-- Volumen -->
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Volumen (m³)</label>
                <input
                    type="number"
                    placeholder="0"
                    v-model="local.volum"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
                />
            </div>

            <!-- Número de bultos -->
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Nº Bultos</label>
                <input
                    type="number"
                    placeholder="1"
                    v-model="local.num_bultos"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
                />
            </div>

        </div>

        <!-- Botón guardar paso -->
        <div class="flex justify-end mt-6">
            <button
                @click="guardarPaso"
                class="bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium px-6 py-2 rounded-lg transition"
            >
                Guardar y continuar →
            </button>
        </div>
    </div>
</template>

<script>
import { reactive } from 'vue'

export default {
    name: 'PasoEspecificaciones',

    props: {
        datos:        { type: Object, required: true },
        transports:   { type: Array,  required: true },
        tipusCarrega: { type: Array,  required: true },
        errors:       { type: Object, required: true },
    },

    setup(props, { emit }) {

        // Estado local — copia de los datos del padre
        // El hijo trabaja con su propia copia y solo avisa al padre cuando se pulsa el botón
        const local = reactive({
            tipus_transport_id: props.datos.tipus_transport_id || null,
            tipus_fluxe_id:     props.datos.tipus_fluxe_id     || null,
            tipus_carrega_id:   props.datos.tipus_carrega_id   || '',
            pes_brut:           props.datos.pes_brut           || '',
            volum:              props.datos.volum               || '',
            num_bultos:         props.datos.num_bultos          || '',
        })

        // Solo emite cuando el usuario pulsa el botón
        function guardarPaso() {
            emit('actualizar', {
                tipus_transport_id: local.tipus_transport_id,
                tipus_fluxe_id:     local.tipus_fluxe_id,
                tipus_carrega_id:   local.tipus_carrega_id,
                pes_brut:           local.pes_brut,
                volum:              local.volum,
                num_bultos:         local.num_bultos,
            })
        }

        // Devuelve el icono según el tipo de transporte
        function iconTransport(tipus) {
            const iconos = {
                'Marítimo':   '🚢',
                'Aéreo':      '✈️',
                'Terrestre':  '🚛',
                'Multimodal': '🔄',
            }
            return iconos[tipus] ?? '📦'
        }

        return { local, guardarPaso, iconTransport }
    }
}
</script>
