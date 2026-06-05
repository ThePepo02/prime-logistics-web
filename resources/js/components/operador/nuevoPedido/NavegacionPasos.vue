<template>
    <div class="flex items-center justify-between px-2 py-4 mt-4">

        <!-- Indicador de paso — Ej: "Paso 1 de 3" -->
        <div class="flex items-center gap-3">
            <span class="text-sm text-gray-500">
                Paso {{ pasoActual }} de {{ totalPasos }}
            </span>
            <!-- Barra de progreso -->
            <div class="w-32 h-1.5 bg-gray-200 rounded-full">
                <div
                    class="h-1.5 bg-orange-500 rounded-full transition-all"
                    :style="{ width: progreso + '%' }"
                ></div>
            </div>
        </div>

        <!-- Botones de navegación -->
        <div class="flex items-center gap-3">

            <!-- Botón Anterior — solo visible si no estamos en el paso 1 -->
            <button
                v-if="pasoActual > 1"
                @click="$emit('anterior')"
                class="px-4 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition"
            >
                ← Anterior
            </button>

            <!-- Botón Guardar Borrador — visible en todos los pasos -->
            <button
                @click="$emit('guardarBorrador')"
                :disabled="guardando"
                class="px-4 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition disabled:opacity-50"
            >
                Guardar Borrador
            </button>

            <!-- Botón Siguiente — visible si no estamos en el último paso -->
            <button
                v-if="pasoActual < totalPasos"
                @click="$emit('siguiente')"
                class="px-5 py-2 text-sm font-medium text-white bg-orange-500 rounded-lg hover:bg-orange-600 transition"
            >
                Siguiente → {{ labelSiguiente }}
            </button>

            <!-- Botón Enviar Pedido — solo visible en el último paso -->
            <button
                v-if="pasoActual === totalPasos"
                @click="$emit('enviarPedido')"
                :disabled="guardando"
                class="px-5 py-2 text-sm font-medium text-white bg-orange-500 rounded-lg hover:bg-orange-600 transition disabled:opacity-50 flex items-center gap-2"
            >
                <span v-if="guardando">Enviando...</span>
                <span v-else>Enviar Pedido ✓</span>
            </button>

        </div>
    </div>
</template>

<script>
import { computed } from 'vue'

export default {
    name: 'NavegacionPasos',

    // Props — recibe el estado de navegación del padre
    props: {
        pasoActual:  { type: Number, required: true }, // paso en el que estamos
        totalPasos:  { type: Number, required: true }, // total de pasos
        guardando:   { type: Boolean, default: false }, // true mientras guarda
    },

    // Eventos que emite hacia el padre
    emits: ['anterior', 'siguiente', 'guardarBorrador', 'enviarPedido'],

    setup(props) {

        // Calcula el porcentaje de progreso para la barra
        const progreso = computed(() => {
            return (props.pasoActual / props.totalPasos) * 100
        })

        // Texto del botón Siguiente según el paso
        const labelSiguiente = computed(() => {
            const labels = {
                1: 'Especificaciones',
                2: 'Ruta y Cierre',
            }
            return labels[props.pasoActual] ?? 'Siguiente'
        })

        return { progreso, labelSiguiente }
    }
}
</script>
