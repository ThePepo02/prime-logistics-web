<template>
    <div class="p-6">
        <div class="bg-white rounded-xl shadow-sm p-6 mb-4">
            <h2 class="text-base font-semibold text-blue-700 border-l-4 border-blue-700 pl-3 mb-6">
                GlobalPath Analytics — Datos ONU
            </h2>

            <!-- Contenedor donde se embebe el dashboard de Superset -->
            <div id="superset-container" class="w-full rounded-lg overflow-hidden" style="height: 600px;">
                <div class="h-full flex items-center justify-center text-gray-400 text-sm">
                    Cargando dashboard...
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { embedDashboard } from '@superset-ui/embedded-sdk'

export default {
    name: 'GraficosComponent',

    data() {
        return {
            // URL de Superset — cambiar por la URL real cuando estés en el cole
            supersetUrl: 'http://localhost:8088',

            // ID del dashboard embebido — se obtiene en Superset:
            // Dashboard → ··· → Embed dashboard → Copy embed ID
            dashboardId: '', // ← Pegar aquí el ID cuando estés en el cole
        }
    },

    mounted() {
        this.cargarDashboard()
    },

    methods: {
        async cargarDashboard() {
            // Si no hay dashboardId configurado, no hacemos nada
            if (!this.dashboardId) return

            try {
                await embedDashboard({
                    id: this.dashboardId,
                    supersetDomain: this.supersetUrl,
                    mountPoint: document.getElementById('superset-container'),
                    fetchGuestToken: () => this.obtenerGuestToken(),
                    dashboardUiConfig: {
                        hideTitle: false,
                        hideChartControls: false,
                        hideFilters: false,
                    },
                })
            } catch (e) {
                console.error('Error cargando dashboard de Superset:', e)
            }
        },

        // Obtiene el guest token de Superset via Laravel
        // Laravel actúa de intermediario para no exponer credenciales en el frontend
        async obtenerGuestToken() {
            const token = localStorage.getItem('token')
            const response = await fetch('/api/superset/guest-token', {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                }
            })
            const data = await response.json()
            return data.token
        }
    }
}
</script>
