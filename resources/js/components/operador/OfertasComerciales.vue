<template>
    <div class="p-6">

        <!-- Tarjetas resumen — muestra conteo por estado -->
        <div class="grid grid-cols-5 gap-4 mb-6">
            <div v-for="stat in stats" :key="stat.label" class="bg-white rounded-xl p-4 shadow-sm border-t-4"
                :style="{ borderColor: stat.color }">
                <p class="text-3xl font-bold text-gray-800">{{ stat.count }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ stat.label }}</p>
                <p v-if="stat.sub" class="text-xs mt-1" :style="{ color: stat.color }">{{ stat.sub }}</p>
            </div>
        </div>

        <!-- Filtros — búsqueda por texto, estado y transporte -->
        <div class="bg-white rounded-xl shadow-sm p-4 mb-4">
            <div class="flex flex-wrap gap-3">
                <input v-model="search" type="text" placeholder="Buscar..."
                    class="border border-gray-200 rounded-lg px-3 py-2 text-sm w-48 focus:outline-none focus:ring-2 focus:ring-orange-400" />
                <select v-model="filtreEstat"
                    class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                    <option value="">Estado</option>
                    <option v-for="estat in estats" :key="estat.id" :value="estat.id">{{ estat.estat }}</option>
                </select>
                <select v-model="filtreTransport"
                    class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                    <option value="">Modo transporte</option>
                    <option v-for="t in transports" :key="t.id" :value="t.id">{{ t.tipus }}</option>
                </select>
            </div>
        </div>

        <!-- Tabla de ofertas -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">ID Oferta</th>
                        <th class="px-4 py-3 text-left">Cliente</th>
                        <th class="px-4 py-3 text-left">Empresa</th>
                        <th class="px-4 py-3 text-left">Modo</th>
                        <th class="px-4 py-3 text-left">Ruta</th>
                        <th class="px-4 py-3 text-left">Peso (kg)</th>
                        <th class="px-4 py-3 text-left">Validez</th>
                        <th class="px-4 py-3 text-left">Estado</th>
                        <th class="px-4 py-3 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <!-- Mientras carga -->
                    <tr v-if="loading">
                        <td colspan="9" class="text-center py-8 text-gray-400">Cargando ofertas...</td>
                    </tr>
                    <!-- Sin resultados -->
                    <tr v-else-if="ofertasFiltrades.length === 0">
                        <td colspan="9" class="text-center py-8 text-gray-400">No se encontraron ofertas.</td>
                    </tr>
                    <!-- Filas de datos -->
                    <tr v-for="oferta in ofertasFiltrades" :key="oferta.id" class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 font-semibold text-gray-800">OC-{{ String(oferta.id).padStart(4, '0') }}
                        </td>
                        <td class="px-4 py-3 text-gray-700">{{ oferta.client ? oferta.client.nom + ' ' +
                            oferta.client.cognoms : '—' }}</td>
                        <td class="px-4 py-3 text-orange-500 font-medium">{{ oferta.client ? oferta.client.empresa : '—'
                        }}</td>
                        <td class="px-4 py-3">
                            <span class="flex items-center gap-1 text-gray-600">
                                <span>{{ iconTransport(oferta.tipus_transport?.tipus) }}</span>
                                {{ oferta.tipus_transport?.tipus ?? '—' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-700">{{ oferta.comentaris ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ oferta.pes_brut ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ formatData(oferta.data_validessa_fina) }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs font-semibold uppercase"
                                :class="colorEstat(oferta.estat_oferta?.estat)">
                                {{ oferta.estat_oferta?.estat ?? '—' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <button class="text-gray-400 hover:text-gray-600 mr-2" title="Ver">👁</button>
                            <button class="text-gray-400 hover:text-gray-600" title="Editar">✏️</button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Paginación -->
            <div class="flex items-center justify-between px-4 py-3 border-t border-gray-100 text-sm text-gray-500">
                <span>Mostrando {{ ofertes.from }}–{{ ofertes.to }} de {{ ofertes.total }} ofertas</span>
                <div class="flex gap-1">
                    <button v-for="page in ofertes.last_page" :key="page" @click="carregarOfertes(page)"
                        class="px-3 py-1 rounded"
                        :class="page === ofertes.current_page ? 'bg-orange-500 text-white' : 'hover:bg-gray-100'">
                        {{ page }}
                    </button>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    name: 'OfertasComerciales',

    data() {
        return {
            // Objeto de paginación que devuelve Laravel con paginate()
            ofertes: { data: [], from: 0, to: 0, total: 0, last_page: 1, current_page: 1 },
            estats: [],     // Lista de estados para el filtro
            transports: [], // Lista de transportes para el filtro
            loading: true,
            search: '',
            filtreEstat: '',
            filtreTransport: '',
        };
    },

    computed: {
        // Filtra las ofertas según búsqueda, estado y transporte
        ofertasFiltrades() {
            return this.ofertes.data.filter(o => {
                const textMatch = this.search === '' ||
                    (o.client?.nom + ' ' + o.client?.cognoms).toLowerCase().includes(this.search.toLowerCase()) ||
                    o.comentaris?.toLowerCase().includes(this.search.toLowerCase());

                const estatMatch = this.filtreEstat === '' || o.estat_oferta_id == this.filtreEstat;
                const transportMatch = this.filtreTransport === '' || o.tipus_transport_id == this.filtreTransport;

                return textMatch && estatMatch && transportMatch;
            });
        },

        // Cuenta ofertas por estado para las tarjetas resumen
        stats() {
            const data = this.ofertes.data;
            return [
                { label: 'Borrador', count: data.filter(o => o.estat_oferta?.estat === 'Borrador').length, color: '#6366f1' },
                { label: 'Enviadas', count: data.filter(o => o.estat_oferta?.estat === 'Enviada').length, color: '#3b82f6', sub: 'Sin respuesta' },
                { label: 'Aceptadas', count: data.filter(o => o.estat_oferta?.estat === 'Acceptada').length, color: '#22c55e' },
                { label: 'Rechazadas', count: data.filter(o => o.estat_oferta?.estat === 'Rebutjada').length, color: '#ef4444' },
                { label: 'Caducadas', count: data.filter(o => o.estat_oferta?.estat === 'Caducada').length, color: '#f97316' },
            ];
        },
    },

    methods: {
        // Carga las ofertas paginadas desde la API usando fetch nativo (igual que el Dashboard)
        async carregarOfertes(page = 1) {
            this.loading = true;
            try {
                const token = localStorage.getItem('token');
                const res = await fetch(`/api/ofertes?page=${page}`, {
                    headers: { Authorization: `Bearer ${token}` }
                });
                this.ofertes = await res.json();
            } catch (e) {
                console.error('Error carregant ofertes:', e);
            } finally {
                this.loading = false;
            }
        },

        // Carga estados y transportes para los desplegables de filtro
        async carregarFiltres() {
            try {
                const token = localStorage.getItem('token');
                const headers = { Authorization: `Bearer ${token}` };
                const [estatsRes, transportsRes] = await Promise.all([
                    fetch('/api/estats-ofertes', { headers }),
                    fetch('/api/tipus-transports', { headers }),
                ]);
                this.estats = await estatsRes.json();
                this.transports = await transportsRes.json();
            } catch (e) {
                console.error('Error carregant filtres:', e);
            }
        },

        // Formatea fecha a formato legible (ej: 10 may)
        formatData(data) {
            if (!data) return '—';
            return new Date(data).toLocaleDateString('es-ES', { day: '2-digit', month: 'short' });
        },

        // Devuelve la clase CSS del badge según el estado
        colorEstat(estat) {
            const mapa = {
                'Enviada': 'bg-blue-100 text-blue-700',
                'Acceptada': 'bg-green-100 text-green-700',
                'Rebutjada': 'bg-red-100 text-red-700',
                'Borrador': 'bg-gray-100 text-gray-600',
                'Caducada': 'bg-orange-100 text-orange-600',
            };
            return mapa[estat] ?? 'bg-gray-100 text-gray-500';
        },

        // Devuelve el icono según el tipo de transporte
        iconTransport(tipus) {
            const mapa = { 'Marítimo': '🚢', 'Aéreo': '✈️', 'Terrestre': '🚛' };
            return mapa[tipus] ?? '📦';
        },
    },

    // Se ejecuta al montar el componente — carga datos y filtros
    mounted() {
        this.carregarOfertes();
        this.carregarFiltres();
    },
};
</script>
