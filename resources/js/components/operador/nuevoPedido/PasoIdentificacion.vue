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
                <select :value="datos.client_id" @change="emitir('client_id', $event.target.value)"
                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
                    :class="errors.client_id ? 'border-red-400' : 'border-gray-200'">
                    <option value="">Selecciona un cliente</option>
                    <option v-for="c in clientes" :key="c.id" :value="c.id">
                        {{ c.nom }} {{ c.cognoms }} — {{ c.empresa }}
                    </option>
                </select>
                <!-- Mensaje de error si no se selecciona cliente -->
                <p v-if="errors.client_id" class="text-xs text-red-500 mt-1">{{ errors.client_id }}</p>
                <p v-else class="text-xs text-orange-500 mt-1">Asignado a tu empresa</p>
            </div>

            <!-- Referencia externa — campo libre opcional -->
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Referencia Externa</label>
                <input type="text" placeholder="Ej: TSA-2025-001" :value="datos.comentaris"
                    @input="emitir('comentaris', $event.target.value)"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400" />
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'PasoIdentificacion',

    // Props que recibe del padre NuevoPedido.vue
    props: {
        datos: { type: Object, required: true },  // formulario completo
        clientes: { type: Array, required: true },  // lista de clientes
        errors: { type: Object, required: true },  // errores de validación
    },

    setup(props, { emit }) {

        // Emite al padre el campo que cambió y su nuevo valor
        function emitir(campo, valor) {
            emit('actualizar', { [campo]: valor })
        }

        return { emitir }
    }
}
</script>
