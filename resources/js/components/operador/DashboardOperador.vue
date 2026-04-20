<template>
    <div class="flex h-screen bg-gray-100 font-sans overflow-hidden">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-gray-900 text-white flex flex-col flex-shrink-0">
            <div class="flex items-center justify-center px-6 py-5 border-b border-gray-700">
                <img :src="logoUrl" alt="Prime Logistics" class="w-40 h-auto mx-auto" />
            </div>
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">

                <p class="text-xs text-gray-500 uppercase tracking-widest mb-3 px-2">Principal</p>

                <a href="#" @click.prevent="paginaActual = 'dashboard'"
                    :class="paginaActual === 'dashboard' ? 'bg-orange-500 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800'"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="7" height="7" rx="1" />
                        <rect x="14" y="3" width="7" height="7" rx="1" />
                        <rect x="3" y="14" width="7" height="7" rx="1" />
                        <rect x="14" y="14" width="7" height="7" rx="1" />
                    </svg>
                    Dashboard
                </a>

                <a href="#" @click.prevent="paginaActual = 'clientes'"
                    :class="paginaActual === 'clientes' ? 'bg-orange-500 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800'"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m6 5.87a4 4 0 10-8 0m8 0V10a4 4 0 10-8 0v10" />
                    </svg>
                    Clientes
                </a>

                <a href="#" @click.prevent="paginaActual = 'ofertas'"
                    :class="paginaActual === 'ofertas' ? 'bg-orange-500 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800'"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-3-3v6M4 6h16M4 18h16" />
                    </svg>
                    Ofertas Comerciales
                </a>

                <a href="#" @click.prevent="paginaActual = 'operaciones'"
                    :class="paginaActual === 'operaciones' ? 'bg-orange-500 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800'"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Operaciones
                </a>

                <a href="#" @click.prevent="paginaActual = 'notificaciones'"
                    :class="paginaActual === 'notificaciones' ? 'bg-orange-500 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800'"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0" />
                    </svg>
                    Notificaciones
                    <span
                        class="ml-auto bg-orange-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">5</span>
                </a>

                <p class="text-xs text-gray-500 uppercase tracking-widest mt-6 mb-3 px-2">Gestión</p>

                <a href="#" @click.prevent="paginaActual = 'seguimiento'"
                    :class="paginaActual === 'seguimiento' ? 'bg-orange-500 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800'"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l5.447 2.724A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4" />
                    </svg>
                    Seguimiento
                </a>

                <a href="#" @click.prevent="paginaActual = 'documentacion'"
                    :class="paginaActual === 'documentacion' ? 'bg-orange-500 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800'"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2z" />
                    </svg>
                    Documentación
                </a>

                <p class="text-xs text-gray-500 uppercase tracking-widest mt-6 mb-3 px-2">Sistema</p>

                <a href="#" @click.prevent="paginaActual = 'configuracion'"
                    :class="paginaActual === 'configuracion' ? 'bg-orange-500 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800'"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                    Configuración
                </a>

            </nav>

            <!-- PERFIL USUARIO -->
            <div class="border-t border-gray-700 px-4 py-4 flex items-center gap-3">
                <div
                    class="w-8 h-8 rounded-full bg-green-600 text-white flex items-center justify-center font-bold text-xs flex-shrink-0">
                    {{ inicialesUsuario }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-white font-medium truncate">{{ nombreUsuario }}</p>
                    <p class="text-xs text-gray-500 uppercase">Operadora</p>
                </div>
                <button @click="logout" title="Cerrar sesión" class="text-gray-500 hover:text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </div>
        </aside>

        <!-- ÁREA PRINCIPAL -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- TOPBAR -->
            <header class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between flex-shrink-0">
                <div>
                    <h1 class="text-xl font-bold text-gray-800">{{ tituloActual }}</h1>
                    <p class="text-sm text-gray-500">{{ subtituloActual }}</p>
                </div>
                <div class="flex items-center gap-4">
                    <button v-if="paginaActual === 'dashboard' || paginaActual === 'ofertas'"
                        class="flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white font-medium px-4 py-2 rounded-lg transition text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Nueva Oferta
                    </button>
                    <button class="text-gray-500 hover:text-gray-700" @click="paginaActual = 'notificaciones'">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0" />
                        </svg>
                    </button>
                    <div
                        class="w-9 h-9 rounded-full bg-green-600 text-white flex items-center justify-center font-bold text-sm">
                        {{ inicialesUsuario }}
                    </div>
                </div>
            </header>

            <!-- CONTENIDO — v-if cambia la vista según paginaActual -->
            <main class="flex-1 overflow-y-auto">

                <!-- ── VISTA: DASHBOARD ── -->
                <div v-if="paginaActual === 'dashboard'" class="p-6 space-y-5">

                    <div class="grid grid-cols-4 gap-4">
                        <div v-for="kpi in kpis" :key="kpi.label" class="bg-white rounded-xl p-5 shadow-sm"
                            :class="'border-t-4 ' + kpi.border">
                            <div :class="kpi.iconColor" class="mb-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    v-html="kpi.icon"></svg>
                            </div>
                            <p class="text-3xl font-bold text-gray-800">{{ kpi.value }}</p>
                            <p class="text-sm text-gray-500 mt-1">{{ kpi.label }}</p>
                            <p class="text-xs mt-2 font-medium" :class="kpi.subColor">{{ kpi.sub }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-2 bg-white rounded-xl p-6 shadow-sm">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="font-semibold text-gray-800">Actividad semanal</h2>
                                <div class="flex gap-4 text-xs text-gray-500">
                                    <span class="flex items-center gap-1"><span
                                            class="w-3 h-3 rounded-sm bg-blue-500 inline-block"></span>Enviadas</span>
                                    <span class="flex items-center gap-1"><span
                                            class="w-3 h-3 rounded-sm bg-green-500 inline-block"></span>Aceptadas</span>
                                    <span class="flex items-center gap-1"><span
                                            class="w-3 h-3 rounded-sm bg-orange-400 inline-block"></span>Incidentes</span>
                                </div>
                            </div>
                            <div class="flex items-end gap-2 h-36">
                                <div v-for="(w, i) in weeklyData" :key="i"
                                    class="flex-1 flex flex-col items-center gap-1">
                                    <div class="w-full flex items-end gap-px h-28">
                                        <div class="flex-1 bg-blue-500 rounded-t" :style="{ height: w.sent + '%' }">
                                        </div>
                                        <div class="flex-1 bg-green-500 rounded-t"
                                            :style="{ height: w.accepted + '%' }"></div>
                                        <div class="flex-1 bg-orange-400 rounded-t"
                                            :style="{ height: w.incidents + '%' }"></div>
                                    </div>
                                    <span class="text-xs text-gray-400">{{ w.label }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl p-5 shadow-sm">
                            <div class="flex items-center justify-between mb-3">
                                <h2 class="text-sm font-semibold text-orange-500">⚠ Solicitudes sin respuesta</h2>
                                <span class="text-xs text-gray-400">2 pendientes</span>
                            </div>
                            <div class="space-y-3">
                                <div v-for="req in pendingRequests" :key="req.id"
                                    class="border border-gray-100 rounded-lg p-3">
                                    <div class="flex items-start gap-2 mb-2">
                                        <div
                                            class="w-8 h-8 rounded-full bg-blue-600 text-white text-xs flex items-center justify-center font-bold flex-shrink-0">
                                            {{ req.initials }}</div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-800 truncate">{{ req.name }}</p>
                                            <p class="text-xs text-gray-400">{{ req.route }} · {{ req.type }} · {{
                                                req.weight }}</p>
                                        </div>
                                        <span class="text-xs text-gray-400 flex-shrink-0">{{ req.time }}</span>
                                    </div>
                                    <button
                                        class="w-full bg-orange-500 hover:bg-orange-600 text-white text-xs font-medium py-1.5 rounded-lg transition">Crear
                                        oferta</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white rounded-xl p-5 shadow-sm">
                            <div class="mb-4"><span
                                    class="bg-red-50 text-red-500 text-xs font-semibold px-2 py-1 rounded">⚠ Requieren
                                    atención</span></div>
                            <div class="space-y-2">
                                <div v-for="alert in alerts" :key="alert.id"
                                    class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                                    <span class="text-sm font-medium text-gray-700 w-32 flex-shrink-0">{{ alert.id
                                    }}</span>
                                    <span class="text-sm text-gray-500 flex-1">{{ alert.issue }}</span>
                                    <button
                                        class="text-xs border border-gray-300 text-gray-600 hover:bg-gray-50 px-3 py-1 rounded-lg transition ml-2">Ver</button>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl p-5 shadow-sm">
                            <h2 class="font-semibold text-gray-800 mb-4">Distribución de operaciones activas</h2>
                            <div class="space-y-4">
                                <div v-for="op in operations" :key="op.type" class="flex items-center gap-3">
                                    <span class="text-sm text-gray-600 w-24 flex-shrink-0">{{ op.type }}</span>
                                    <span class="text-xs text-gray-400 w-12 flex-shrink-0">{{ op.count }} ops</span>
                                    <div class="flex-1 bg-gray-100 rounded-full h-2">
                                        <div class="h-2 rounded-full transition-all" :class="op.color"
                                            :style="{ width: op.percent + '%' }"></div>
                                    </div>
                                    <span class="text-xs text-gray-500 w-8 text-right flex-shrink-0">{{ op.percent
                                    }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-5 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="font-semibold text-gray-800">Últimas ofertas gestionadas</h2>
                            <button class="text-orange-500 text-sm hover:underline"
                                @click="paginaActual = 'ofertas'">Ver todas →</button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="text-xs text-gray-400 uppercase border-b border-gray-100">
                                        <th class="text-left pb-3 font-medium">ID</th>
                                        <th class="text-left pb-3 font-medium">Cliente</th>
                                        <th class="text-left pb-3 font-medium">Empresa</th>
                                        <th class="text-left pb-3 font-medium">Modo</th>
                                        <th class="text-left pb-3 font-medium">Ruta</th>
                                        <th class="text-left pb-3 font-medium">Enviada</th>
                                        <th class="text-left pb-3 font-medium">Estado</th>
                                        <th class="text-left pb-3 font-medium">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="offer in offers" :key="offer.id"
                                        class="border-b border-gray-50 hover:bg-gray-50 transition">
                                        <td class="py-3 font-medium text-gray-700">{{ offer.id }}</td>
                                        <td class="py-3 text-gray-600">{{ offer.client }}</td>
                                        <td class="py-3 text-gray-400">{{ offer.company }}</td>
                                        <td class="py-3 text-gray-600">{{ offer.mode }}</td>
                                        <td class="py-3 text-gray-600">{{ offer.route }}</td>
                                        <td class="py-3 text-gray-400">{{ offer.sent }}</td>
                                        <td class="py-3">
                                            <span class="text-xs font-semibold px-2 py-1 rounded"
                                                :class="estadoClase(offer.status)">{{ offer.status }}</span>
                                        </td>
                                        <td class="py-3">
                                            <div class="flex gap-2">
                                                <button class="text-gray-400 hover:text-blue-500 transition"
                                                    title="Ver">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </button>
                                                <button class="text-gray-400 hover:text-orange-500 transition"
                                                    title="Editar">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <!-- ── FIN VISTA: DASHBOARD ── -->

                <!-- ── VISTA: CLIENTES ── -->
                <!-- v-if muestra este componente solo cuando paginaActual === 'clientes' -->
                <!-- Es el mismo patrón que usamos con v-if="error" en el LoginComponent -->
                <clientes-component v-if="paginaActual === 'clientes'" />
                <ofertas-comerciales v-if="paginaActual === 'ofertas'" />
                <operaciones-component v-if="paginaActual === 'operaciones'" />
                <notificacions-component v-if="paginaActual === 'notificaciones'" />
                <!-- ── FIN VISTA: CLIENTES ── -->


                <!-- ── VISTAS PENDIENTES ── -->
                <div v-if="paginaActual !== 'dashboard' && paginaActual !== 'clientes' && paginaActual !== 'ofertas' && paginaActual !== 'operaciones' && paginaActual !== 'notificaciones'"
                    class="flex flex-col items-center justify-center h-full text-gray-400 gap-3 p-12">
                    <svg class="w-14 h-14 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <p class="text-lg font-semibold capitalize">{{ paginaActual }}</p>
                    <p class="text-sm">Página en construcción</p>
                </div>

            </main>
        </div>
    </div>
</template>

<script>
// Importamos ClientesComponent igual que importamos LoginComponent en app.js
// La diferencia es que aquí lo registramos LOCALMENTE solo para este componente
import ClientesComponent from './ClientesComponent.vue'
import OfertasComerciales from './OfertasComerciales.vue'
import OperacionesComponent from './OperacionesComponent.vue'
import NotificacionsComponent from './NotificacionsComponent.vue';

export default {
    name: 'DashboardOperador',

    // components: → registra los componentes hijos que usa este componente
    // Así Vue sabe que <clientes-component> existe cuando lo usa en el template
    components: {
        ClientesComponent,
        OfertasComerciales,
        OperacionesComponent,
        NotificacionsComponent,
    },

    data() {
        return {
            // Variable que controla qué página se muestra
            // Igual que en LoginComponent teníamos loading: false para controlar el estado
            paginaActual: 'dashboard',

            logoUrl: '/logo-empresa.png',


            // Datos del dashboard
            kpis: [
                { value: 0, label: 'Ofertas pendientes', sub: 'Por responder al cliente', border: 'border-orange-400', iconColor: 'text-orange-400', subColor: 'text-orange-500', icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>' },
                { value: 0, label: 'Aceptadas este mes', sub: '↑ vs mes anterior', border: 'border-green-500', iconColor: 'text-green-500', subColor: 'text-green-500', icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>' },
                { value: 0, label: 'Operaciones en curso', sub: 'Total registradas', border: 'border-blue-500', iconColor: 'text-blue-500', subColor: 'text-blue-500', icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>' },
                { value: 0, label: 'Con incidencias', sub: 'Requieren atención', border: 'border-red-400', iconColor: 'text-red-400', subColor: 'text-red-500', icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>' },
            ],
            offers: [],
            alerts: [],
            operations: [],

            weeklyData: [
                { label: 'Sem45', sent: 70, accepted: 50, incidents: 15 },
                { label: 'Sem46', sent: 55, accepted: 65, incidents: 10 },
                { label: 'Sem47', sent: 80, accepted: 45, incidents: 20 },
                { label: 'Sem48', sent: 60, accepted: 70, incidents: 8 },
                { label: 'Sem49', sent: 90, accepted: 60, incidents: 12 },
                { label: 'Sem50', sent: 75, accepted: 80, incidents: 18 },
                { label: 'Sem51', sent: 85, accepted: 55, incidents: 10 },
                { label: 'Sem52', sent: 65, accepted: 75, incidents: 5 },
            ],

            pendingRequests: [
                { id: 1, initials: 'MG', name: 'María García — Textil SA', route: 'Valencia→Shanghai', type: 'FCL', weight: '12.400kg', time: 'Hace 1h' },
                { id: 2, initials: 'PL', name: 'Pedro López — Moda Express', route: 'Madrid→París', type: 'Terrestre', weight: '2.100kg', time: 'Hace 3h' },
            ],
        }
    },

    computed: {
        // Calcula el nombre del usuario desde localStorage (guardado en el login)
        nombreUsuario() {
            try {
                const usuario = JSON.parse(localStorage.getItem('user') || localStorage.getItem('usuario') || '{}')
                return usuario.nom || usuario.name || 'Operador'
            } catch (e) {
                return 'Operador'
            }
        },

        // Genera las iniciales del nombre para el avatar
        inicialesUsuario() {
            const partes = this.nombreUsuario.split(' ')
            if (partes.length >= 2) return (partes[0][0] + partes[1][0]).toUpperCase()
            return this.nombreUsuario.substring(0, 2).toUpperCase()
        },

        // Título dinámico del topbar según la página activa
        tituloActual() {
            const titulos = {
                dashboard: 'Dashboard',
                clientes: 'Clientes',
                ofertas: 'Ofertas Comerciales',
                operaciones: 'Operaciones',
                notificaciones: 'Notificaciones',
                seguimiento: 'Seguimiento',
                documentacion: 'Documentación',
                configuracion: 'Configuración',
            }
            return titulos[this.paginaActual] || 'Dashboard'
        },

        // Subtítulo dinámico del topbar
        subtituloActual() {
            const subs = {
                dashboard: 'Buenos días, ' + this.nombreUsuario + ' — Prime Logistics',
                clientes: 'Gestión de empresas y contactos',
                ofertas: 'Gestión de ofertas comerciales',
                operaciones: 'Estado de operaciones activas',
            }
            return subs[this.paginaActual] || ''
        },
    },

    // mounted() → se ejecuta automáticamente cuando el componente se carga en la página
    // Igual que teníamos handleLogin que se ejecutaba al hacer click, mounted se ejecuta al cargar
    mounted() {
        this.cargarDatosDashboard()
    },

    methods: {
        // Llama a las 4 APIs del dashboard en paralelo
        async cargarDatosDashboard() {
            try {
                const token = localStorage.getItem('token');
                const headers = {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                };

                const [statsRes, ofertesRes, alertesRes, distribucioRes] = await Promise.all([
                    fetch('/api/dashboard/stats', { headers }),
                    fetch('/api/dashboard/ofertes', { headers }),
                    fetch('/api/dashboard/alertes', { headers }),
                    fetch('/api/dashboard/distribucio', { headers }),
                ]);

                const stats = await statsRes.json();
                const ofertes = await ofertesRes.json();
                const alertesData = await alertesRes.json();
                const distribucio = await distribucioRes.json();

                this.kpis[0].value = stats.pendents;
                this.kpis[1].value = stats.acceptades;
                this.kpis[2].value = stats.en_curs;
                this.kpis[3].value = stats.incidencies;

                this.offers = ofertes;
                this.alerts = alertesData;
                this.operations = distribucio;

            } catch (e) {
                console.error('Error cargando datos del dashboard:', e);
            }
        },

        // Devuelve la clase CSS del badge según el estado de la oferta
        estadoClase(status) {
            if (status === 'ENVIADA') return 'bg-blue-100 text-blue-600'
            if (status === 'BORRADOR') return 'bg-gray-100 text-gray-500'
            if (status === 'ACEPTADA') return 'bg-green-100 text-green-600'
            if (status === 'PENDIENTE') return 'bg-yellow-100 text-yellow-600'
            return 'bg-gray-100 text-gray-400'
        },

        // Cierra sesión: borra el token del localStorage y redirige al login
        // Igual que en LoginComponent guardábamos el token, aquí lo eliminamos
        async logout() {
            try {
                const token = localStorage.getItem('token')
                await fetch('/api/logout', {
                    method: 'POST',
                    headers: { 'Authorization': `Bearer ${token}` }
                })
            } catch (e) {
                // Si falla la llamada al servidor, borramos igualmente
            } finally {
                localStorage.removeItem('token')
                localStorage.removeItem('user')
                localStorage.removeItem('usuario')
                window.location.href = '/'
            }
        },
    },
}
</script>
