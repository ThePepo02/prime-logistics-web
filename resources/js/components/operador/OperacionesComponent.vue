<template>
  <div class="p-6 bg-gray-50 min-h-screen">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-800">Operaciones Activas</h1>
        <p class="text-sm text-gray-500">Envíos en curso — tiempo real</p>
      </div>
      <button class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-100">
        ⬇ Exportar
      </button>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-3 gap-4 mb-6">
      <div class="bg-white rounded-xl p-5 border border-gray-200">
        <div class="text-blue-500 text-xl mb-2">🔄</div>
        <div class="text-3xl font-bold text-gray-800">{{ stats.total }}</div>
        <div class="text-sm text-gray-500">En Tránsito</div>
        <div class="text-xs text-blue-500 mt-1">Operaciones activas ahora</div>
      </div>
      <div class="bg-white rounded-xl p-5 border border-gray-200">
        <div class="text-blue-500 text-xl mb-2">✈️</div>
        <div class="text-3xl font-bold text-gray-800">{{ stats.aerea }}</div>
        <div class="text-sm text-gray-500">Vía Aérea</div>
        <div class="text-xs text-gray-400 mt-1">{{ stats.pct_aerea }}% del total activo</div>
      </div>
      <div class="bg-white rounded-xl p-5 border border-gray-200">
        <div class="text-blue-500 text-xl mb-2">🚢</div>
        <div class="text-3xl font-bold text-gray-800">{{ stats.maritima }}</div>
        <div class="text-sm text-gray-500">Vía Marítima</div>
        <div class="text-xs text-gray-400 mt-1">{{ stats.pct_maritima }}% del total activo</div>
      </div>
    </div>

    <!-- Distribución por Modo -->
    <div class="bg-white rounded-xl p-5 border border-gray-200 mb-6">
      <h2 class="text-base font-semibold text-gray-800 mb-1">Distribución por Modo de Transporte</h2>
      <p class="text-xs text-gray-400 mb-4">Operaciones activas en curso</p>
      <div v-for="modo in distribucio" :key="modo.modo" class="flex items-center gap-3 mb-3">
        <div class="w-24 text-sm text-gray-600">{{ modo.modo }}</div>
        <div class="flex-1 bg-gray-100 rounded-full h-2">
          <div
            class="h-2 rounded-full"
            :class="modo.modo === 'Terrestre' ? 'bg-orange-400' : modo.modo === 'Multimodal' ? 'bg-gray-400' : 'bg-blue-600'"
            :style="{ width: modo.pct + '%' }"
          ></div>
        </div>
        <div class="text-xs text-gray-500 w-28 text-right">{{ modo.total }} envíos · {{ modo.pct }}%</div>
      </div>
    </div>

    <!-- Tabla Operaciones en Curso -->
    <div class="bg-white rounded-xl border border-gray-200">
      <div class="p-5 border-b border-gray-100">
        <h2 class="text-base font-semibold text-gray-800">Operaciones en Curso</h2>
        <p class="text-xs text-gray-400">{{ paginacion.total }} envíos activos</p>
      </div>

      <!-- Filtros -->
      <div class="flex gap-3 p-4 border-b border-gray-100">
        <input
          v-model="filtros.cerca"
          @input="cargarOperacions(1)"
          type="text"
          placeholder="Buscar operación..."
          class="border border-gray-200 rounded-lg px-3 py-2 text-sm flex-1 focus:outline-none focus:ring-2 focus:ring-blue-300"
        />
        <select
          v-model="filtros.modo"
          @change="cargarOperacions(1)"
          class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300"
        >
          <option value="">Todos los modos</option>
          <option value="Marítimo">Marítimo</option>
          <option value="Aéreo">Aéreo</option>
          <option value="Terrestre">Terrestre</option>
          <option value="Multimodal">Multimodal</option>
        </select>
      </div>

      <!-- Tabla -->
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
            <tr>
              <th class="px-4 py-3 text-left">ID Oferta</th>
              <th class="px-4 py-3 text-left">Cliente</th>
              <th class="px-4 py-3 text-left">Modo</th>
              <th class="px-4 py-3 text-left">Ruta</th>
              <th class="px-4 py-3 text-left">Fecha Pedido</th>
              <th class="px-4 py-3 text-left">Estado</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="cargando">
              <td colspan="6" class="text-center py-8 text-gray-400">Cargando...</td>
            </tr>
            <tr
              v-else
              v-for="op in operacions"
              :key="op.id"
              class="border-t border-gray-100 hover:bg-gray-50"
            >
              <td class="px-4 py-3 font-medium text-gray-800">{{ op.oferta_id }}</td>
              <td class="px-4 py-3 text-gray-600">{{ op.cliente }}</td>
              <td class="px-4 py-3 text-gray-600">{{ op.metodo_transporte }}</td>
              <td class="px-4 py-3 text-gray-600">{{ op.ruta }}</td>
              <td class="px-4 py-3 text-gray-600">{{ op.fecha_pedido }}</td>
              <td class="px-4 py-3">
                <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                  {{ op.estado_envio }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Paginación -->
      <div class="flex justify-between items-center px-5 py-3 border-t border-gray-100 text-sm text-gray-500">
        <span>Mostrando {{ operacions.length }} de {{ paginacion.total }} operaciones</span>
        <div class="flex gap-1">
          <button
            v-for="p in paginacion.last_page"
            :key="p"
            @click="cargarOperacions(p)"
            class="w-8 h-8 rounded-lg text-sm"
            :class="p === paginacion.current_page ? 'bg-blue-600 text-white' : 'text-gray-500 hover:bg-gray-100'"
          >
            {{ p }}
          </button>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
export default {
  name: 'OperacionesComponent',
  data() {
    return {
      stats: { total: 0, aerea: 0, maritima: 0, pct_aerea: 0, pct_maritima: 0 },
      distribucio: [],
      operacions: [],
      paginacion: { total: 0, current_page: 1, last_page: 1 },
      filtros: { cerca: '', modo: '' },
      cargando: false,
    }
  },
  mounted() {
    this.cargarTodo()
  },
  methods: {
    token() {
      return localStorage.getItem('token')
    },
    async cargarTodo() {
      await Promise.all([
        this.cargarStats(),
        this.cargarDistribucio(),
        this.cargarOperacions(1),
      ])
    },
    async cargarStats() {
      const res = await fetch('/api/operaciones/stats', {
        headers: { Authorization: `Bearer ${this.token()}` }
      })
      this.stats = await res.json()
    },
    async cargarDistribucio() {
      const res = await fetch('/api/operaciones/distribucio', {
        headers: { Authorization: `Bearer ${this.token()}` }
      })
      this.distribucio = await res.json()
    },
    async cargarOperacions(page) {
      this.cargando = true
      const params = new URLSearchParams({
        page,
        ...(this.filtros.cerca && { cerca: this.filtros.cerca }),
        ...(this.filtros.modo  && { modo:  this.filtros.modo  }),
      })
      const res = await fetch(`/api/operaciones/operacions?${params}`, {
        headers: { Authorization: `Bearer ${this.token()}` }
      })
      const json = await res.json()
      this.operacions  = json.data
      this.paginacion  = json
      this.cargando    = false
    },
  }
}
</script>
