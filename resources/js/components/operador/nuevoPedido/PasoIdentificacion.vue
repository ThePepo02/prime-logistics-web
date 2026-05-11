<template>
    <div class="bg-white rounded-xl shadow-sm p-6 mb-4">
        <h2 class="text-base font-semibold text-blue-700 border-l-4 border-blue-700 pl-3 mb-6">
            Identificación y Registro de la Oferta
        </h2>

        <div class="grid grid-cols-2 gap-6">

            <!-- ID Oferta — solo lectura, lo asigna la BD -->
            <div>
                <label class="text-xs text-gray-500 mb-1 block">ID Oferta Comercial *</label>
                <input type="text" value="OC-2025-" disabled
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 text-gray-400" />
                <p class="text-xs text-gray-400 mt-1">Asignado automáticamente</p>
            </div>

            <!-- Estado — siempre Borrador en el paso 1 -->
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Estado *</label>
                <input type="text" value="📋 Borrador" disabled
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 text-gray-400" />
            </div>

            <!-- Cliente — select con los clientes de la API -->
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Cliente / Razón Social *</label>
                <select
                    v-model="local.client_id"
                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
                    :class="errors.client_id ? 'border-red-400' : 'border-gray-200'"
                >
                    <option value="">Selecciona un cliente</option>
                    <option v-for="c in clientes" :key="c.id" :value="c.id">
                        {{ c.nom }} {{ c.cognoms }} — {{ c.empresa }}
                    </option>
                </select>
                <p v-if="errors.client_id" class="text-xs text-red-500 mt-1">{{ errors.client_id }}</p>
                <p v-else class="text-xs text-orange-500 mt-1">Asignado a tu empresa</p>
            </div>

            <!-- Referencia externa — campo libre opcional -->
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Referencia Externa</label>
                <input
                    type="text"
                    placeholder="Ej: TSA-2025-001"
                    v-model="local.comentaris"
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
    name: 'PasoIdentificacion',

    props: {
        datos:    { type: Object, required: true },
        clientes: { type: Array,  required: true },
        errors:   { type: Object, required: true },
    },

    setup(props, { emit }) {

        // Estado local — copia de los datos del padre
        // El hijo trabaja con su propia copia y solo avisa al padre cuando pulsa el botón
        const local = reactive({
            client_id:  props.datos.client_id  || null,
            comentaris: props.datos.comentaris || '',
        })

        // Cuando el usuario selecciona un cliente, limpia el error de ese campo
        // para que desaparezca el borde rojo sin tener que pulsar el botón
        function limpiarError(campo) {
            emit('limpiarError', campo)
        }

        // Solo emite cuando el usuario pulsa "Guardar y continuar"
        function guardarPaso() {
            emit('actualizar', {
                client_id:  local.client_id,
                comentaris: local.comentaris,
            })
        }

        return { local, guardarPaso, limpiarError }
    }
}
</script>
