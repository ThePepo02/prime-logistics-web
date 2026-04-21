<template>
  <div class="p-6">

    <!-- CABECERA -->
    <div class="flex items-center justify-between mb-6">
      <div>
        <h1 class="text-xl font-bold text-gray-900">Notificaciones</h1>
        <p class="text-sm text-gray-500">Solicitudes y alertas de clientes</p>
      </div>
      <button
        @click="marcarTotes"
        class="text-sm text-gray-600 border border-gray-300 rounded-lg px-4 py-2 hover:bg-gray-50 transition"
      >
        Marcar todo leído
      </button>
    </div>

    <!-- PANEL PRINCIPAL -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">

      <!-- TÍTULO + TABS -->
      <div class="border-b border-gray-200 px-5 pt-5">
        <h2 class="text-base font-bold text-gray-900 mb-4">Bandeja del Operador</h2>
        <div class="flex gap-6">
          <button
            v-for="tab in tabs" :key="tab.value"
            @click="canviarTab(tab.value)"
            :class="[
              'pb-3 text-sm font-medium transition border-b-2',
              filtreActual === tab.value
                ? 'border-orange-500 text-gray-900 font-bold'
                : 'border-transparent text-gray-400 hover:text-gray-600'
            ]"
          >
            {{ tab.label }}
            <span
              v-if="tab.value === 'no_llegides' && noLlegides > 0"
              class="ml-1 bg-orange-500 text-white text-xs rounded-full px-1.5 py-0.5"
            >{{ noLlegides }}</span>
          </button>
        </div>
      </div>

      <!-- LOADING -->
      <div v-if="carregant" class="py-16 text-center text-gray-400">
        <p>Cargando notificaciones...</p>
      </div>

      <!-- LISTA VACÍA -->
      <div v-else-if="notificacions.length === 0" class="py-16 text-center text-gray-400">
        <p class="text-4xl mb-3">🔔</p>
        <p class="font-medium">No hay notificaciones</p>
      </div>

      <!-- LISTA -->
      <div v-else>
        <div
          v-for="notif in notificacions" :key="notif.id"
          @click="marcarLlegida(notif)"
          :class="[
            'border-b border-gray-200 px-5 py-5 cursor-pointer transition border-l-4',
            notif.llegida
              ? 'bg-white border-l-transparent'
              : 'bg-blue-50 border-l-blue-600'
          ]"
        >
          <div class="flex items-start gap-3">

            <!-- Punto no leída / leída -->
            <div class="mt-2 shrink-0">
              <div :class="[
                'w-2 h-2 rounded-full',
                notif.llegida ? 'border border-gray-300' : 'bg-blue-600'
              ]"></div>
            </div>

            <div class="flex-1">
              <!-- Título -->
              <p :class="[
                'text-sm mb-1',
                notif.llegida ? 'font-medium text-gray-700' : 'font-bold text-gray-900'
              ]">{{ notif.titol }}</p>

              <!-- Mensaje -->
              <p class="text-xs text-gray-500 mb-3 leading-relaxed">
                {{ notif.missatge }}
              </p>

              <!-- Badges -->
              <div class="flex flex-wrap gap-2 mb-3">
                <span
                  v-if="notif.entitat_id"
                  class="text-xs bg-white border border-gray-200 rounded px-2 py-1 text-gray-700"
                >📋 {{ entitatLabel(notif) }}</span>
                <span :class="['text-xs rounded px-2 py-1 font-medium', badgeClass(notif.tipus)]">
                  {{ badgeLabel(notif.tipus) }}
                </span>
              </div>

              <!-- Footer: tiempo + botón -->
              <div class="flex items-center justify-between">
                <span class="text-xs text-gray-400">
                  {{ tempsRelatiu(notif.data_creacio) }}
                </span>
                <button
                  @click.stop="accioNotificacio(notif)"
                  :class="['text-xs px-3 py-1.5 rounded-lg border transition', botoClass(notif.tipus)]"
                >{{ botoLabel(notif.tipus) }}</button>
              </div>
            </div>

          </div>
        </div>
      </div>

      <!-- PAGINACIÓN -->
      <div
        v-if="totalPagines > 1"
        class="flex items-center justify-between px-5 py-3 border-t border-gray-200"
      >
        <span class="text-xs text-gray-400">
          Mostrando {{ notificacions.length }} de {{ total }} notificaciones
        </span>
        <div class="flex items-center gap-1">
          <button
            @click="canviarPagina(paginaActual - 1)"
            :disabled="paginaActual === 1"
            class="px-2 py-1 text-sm text-gray-400 disabled:opacity-30"
          >←</button>
          <button
            v-for="p in totalPagines" :key="p"
            @click="canviarPagina(p)"
            :class="[
              'w-7 h-7 text-xs rounded',
              p === paginaActual
                ? 'bg-orange-500 text-white font-bold'
                : 'text-gray-700 hover:bg-gray-100'
            ]"
          >{{ p }}</button>
          <button
            @click="canviarPagina(paginaActual + 1)"
            :disabled="paginaActual === totalPagines"
            class="px-2 py-1 text-sm text-gray-400 disabled:opacity-30"
          >→</button>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
export default {
  name: 'NotificacionsComponent',

  data() {
    return {
      notificacions: [],
      total:         0,
      totalPagines:  1,
      noLlegides:    0,
      paginaActual:  1,
      filtreActual:  'totes',
      carregant:     true,
      tabs: [
        { label: 'Todas',       value: 'totes'      },
        { label: 'No leídas',   value: 'no_llegides' },
        { label: 'Solicitudes', value: 'ofertes'     },
        { label: 'Sistema',     value: 'sistema'     },
      ],
    };
  },

  mounted() {
    this.carregarNotificacions();
  },

  methods: {

    async carregarNotificacions() {
      this.carregant = true;
      const token = localStorage.getItem('token');
      try {
        const res = await fetch(
          `/api/notificacions?filtre=${this.filtreActual}&pagina=${this.paginaActual}`,
          { headers: { Authorization: `Bearer ${token}` } }
        );
        const data = await res.json();
        this.notificacions = data.notificacions;
        this.total         = data.total;
        this.totalPagines  = data.total_pagines;
        this.noLlegides    = data.no_llegides;
      } catch (e) {
        console.error('Error cargando notificaciones:', e);
      } finally {
        this.carregant = false;
      }
    },

    canviarTab(valor) {
      this.filtreActual = valor;
      this.paginaActual = 1;
      this.carregarNotificacions();
    },

    canviarPagina(p) {
      if (p < 1 || p > this.totalPagines) return;
      this.paginaActual = p;
      this.carregarNotificacions();
    },

    async marcarLlegida(notif) {
      if (notif.llegida) return;
      const token = localStorage.getItem('token');
      await fetch(`/api/notificacions/${notif.id}/llegir`, {
        method:  'PUT',
        headers: { Authorization: `Bearer ${token}` },
      });
      notif.llegida   = true;
      this.noLlegides = Math.max(0, this.noLlegides - 1);
    },

    async marcarTotes() {
      const token = localStorage.getItem('token');
      await fetch('/api/notificacions/marcar-totes', {
        method:  'PUT',
        headers: { Authorization: `Bearer ${token}` },
      });
      this.notificacions.forEach(n => (n.llegida = true));
      this.noLlegides = 0;
    },

    accioNotificacio(notif) {
      // Navegación futura hacia la página correspondiente
      console.log('Acción:', notif.tipus, notif.entitat_id);
    },

    entitatLabel(notif) {
      const prefix = notif.entitat_tipus === 'oferta' ? 'OFR' : 'OC';
      return `${prefix}-${String(notif.entitat_id).padStart(4, '0')}`;
    },

    badgeLabel(tipus) {
      const map = {
        nova_solicitud:    '🔵 Nueva solicitud',
        oferta_acceptada:  '✅ Aceptada',
        oferta_rebutjada:  '✖️ Rechazada',
        document_revisio:  '📄 Revisión',
        alerta_operacio:   '⚠️ Alerta',
        fase_actualitzada: '🔄 Seguimiento',
      };
      return map[tipus] ?? tipus;
    },

    badgeClass(tipus) {
      const map = {
        nova_solicitud:    'bg-blue-50 text-blue-700 border border-blue-200',
        oferta_acceptada:  'bg-green-50 text-green-700 border border-green-200',
        oferta_rebutjada:  'bg-red-50 text-red-700 border border-red-200',
        document_revisio:  'bg-yellow-50 text-yellow-700 border border-yellow-200',
        alerta_operacio:   'bg-red-50 text-red-700 border border-red-200',
        fase_actualitzada: 'bg-blue-50 text-blue-600 border border-blue-200',
      };
      return map[tipus] ?? 'bg-gray-100 text-gray-600';
    },

    botoLabel(tipus) {
      const map = {
        nova_solicitud:    'Crear Oferta →',
        oferta_acceptada:  'Ver Operación →',
        oferta_rebutjada:  'Ver Oferta →',
        document_revisio:  'Revisar documento →',
        alerta_operacio:   'Ver Operación →',
        fase_actualitzada: 'Ver Operación →',
      };
      return map[tipus] ?? 'Ver →';
    },

    botoClass(tipus) {
      if (tipus === 'nova_solicitud') {
        return 'bg-orange-500 text-white border-orange-500 hover:bg-orange-600';
      }
      return 'text-gray-700 border-gray-200 hover:bg-gray-50';
    },

    tempsRelatiu(dataCreacio) {
      const diff = Math.floor((new Date() - new Date(dataCreacio)) / 60000);
      if (diff < 60)   return `Hace ${diff} min`;
      if (diff < 1440) return `Hace ${Math.floor(diff / 60)} horas`;
      if (diff < 2880) return 'Ayer';
      return `Hace ${Math.floor(diff / 1440)} días`;
    },
  },
};
</script>
