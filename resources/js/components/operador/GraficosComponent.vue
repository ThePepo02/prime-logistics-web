<template>
<div class="p-6">
        <div class="bg-white rounded-xl shadow-sm p-6 mb-4">
            <h2 class="text-base font-semibold text-blue-700 border-l-4 border-blue-700 pl-3 mb-6">
                Actividad Semanal
            </h2>
            <!-- Aquí irá el gráfico de barras -->
            <div class="h-64 flex items-center justify-center text-gray-400 text-sm border-2 border-dashed border-gray-200 rounded-lg">
                Gráfico de barras — pendiente
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-base font-semibold text-blue-700 border-l-4 border-blue-700 pl-3 mb-6">
                Distribución de Operaciones
            </h2>
            <!-- Aquí irá el gráfico de dona -->
            <div class="h-64 flex items-center justify-center text-gray-400 text-sm border-2 border-dashed border-gray-200 rounded-lg">
                Gráfico de dona — pendiente
            </div>
        </div>
    </div>
</template>

<script>
import GraficosComponent from './GraficosComponent.vue'

export default {
    name: 'GraficosComponent',
}
</script>

<style lang="scss" scoped>

</style>
