<template>
    <div class="p-6">
        <div class="bg-white rounded-xl shadow-sm p-6 mb-4">
            <h2 class="text-base font-semibold text-blue-700 border-l-4 border-blue-700 pl-3 mb-6">
                GlobalPath Analytics — Datos ONU
            </h2>
            <div id="superset-container" class="w-full rounded-lg overflow-hidden" style="height: 1200px;">
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
            supersetUrl: 'http://localhost:8088',
            dashboardId: '5594a17e-7668-4c28-9f67-56456b54c063',
        }
    },
    mounted() {
        this.cargarDashboard()
    },
    methods: {
        async cargarDashboard() {
            if (!this.dashboardId) return
            try {
                await embedDashboard({
                    id: this.dashboardId,
                    supersetDomain: this.supersetUrl,
                    mountPoint: document.getElementById('superset-container'),
                    fetchGuestToken: () => this.obtenerGuestToken(),
                    dashboardUiConfig: {
                        hideTitle: true,
                        hideChartControls: false,
                        hideFilters: false,
                    },
                })
            } catch (e) {
                console.error('Error cargando dashboard de Superset:', e)
            }
        },
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

<style>
#superset-container iframe {
    width: 100% !important;
    height: 1200px !important;
    border: none !important;
    min-height: 1200px !important;
}
</style>
