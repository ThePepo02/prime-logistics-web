<template>
  <div class="tracking-oferta-container">
    <div class="card">
      <div class="card-header">
        <h2>🚀 Tracking de Oferta</h2>
        <p class="subtitle">Visualiza y controla el progreso del envío</p>
      </div>

      <!-- Selector de oferta -->
      <div class="selector-section">
        <div class="form-field">
          <label for="oferta-select">Selecciona una oferta:</label>
          <select 
            id="oferta-select"
            v-model="ofertaSeleccionada"
            @change="cargarTracking"
          >
            <option value="">-- Seleccionar oferta --</option>
            <option v-for="oferta in ofertas" :key="oferta.id" :value="oferta.id">
              Oferta #{{ oferta.id }} - {{ oferta.comentaris || 'Sin descripción' }}
            </option>
          </select>
        </div>
      </div>

      <!-- Información de la oferta -->
      <div v-if="ofertaActual" class="info-section">
        <h3>📦 Información de la Oferta</h3>
        <div class="info-grid">
          <div class="info-item">
            <span class="label">ID Oferta:</span>
            <span class="value">#{{ ofertaActual.id }}</span>
          </div>
          <div class="info-item">
            <span class="label">Cliente:</span>
            <span class="value">{{ ofertaActual.client?.name || 'N/A' }}</span>
          </div>
          <div class="info-item">
            <span class="label">Estado:</span>
            <span class="value">{{ ofertaActual.estatOferta?.nom || 'N/A' }}</span>
          </div>
          <div class="info-item">
            <span class="label">Tipo Transporte:</span>
            <span class="value">{{ ofertaActual.tipusTransport?.nom || 'N/A' }}</span>
          </div>
        </div>
      </div>

      <!-- Timeline del tracking -->
      <div v-if="trackingSteps.length > 0" class="tracking-section">
        <h3>📍 Pasos del Tracking</h3>
        
        <div class="timeline">
          <div 
            v-for="(step, index) in trackingSteps" 
            :key="step.id"
            class="timeline-item"
            :class="{ 
              'actual': step.ordre === pasoActual,
              'completado': step.ordre < pasoActual,
              'pendiente': step.ordre > pasoActual
            }"
          >
            <!-- Conector visual -->
            <div v-if="index < trackingSteps.length - 1" class="timeline-connector"></div>

            <!-- Punto en la línea -->
            <div class="timeline-dot">
              <span v-if="step.ordre < pasoActual" class="icon">✅</span>
              <span v-else-if="step.ordre === pasoActual" class="icon">📍</span>
              <span v-else class="icon">⭕</span>
            </div>

            <!-- Contenido del paso -->
            <div class="timeline-content">
              <h4>Paso {{ step.ordre }}: {{ step.nom || 'Sin nombre' }}</h4>
              <p v-if="step.descripcio" class="descripcion">
                {{ step.descripcio }}
              </p>
              
              <!-- Mostrar información si es el paso actual -->
              <div v-if="step.ordre === pasoActual" class="paso-actual-info">
                <p><strong>📍 PASO ACTUAL</strong></p>
                <button 
                  @click="abrirEditorPaso(step)"
                  class="btn btn-edit"
                >
                  ✏️ Modificar este paso
                </button>
              </div>

              <!-- Botones para cambiar paso -->
              <div v-if="editandoPaso?.id === step.id" class="editor-paso">
                <div class="form-field">
                  <label>Nueva descripción:</label>
                  <textarea 
                    v-model="pasoEditando.descripcio"
                    rows="3"
                    placeholder="Añade una descripción..."
                  ></textarea>
                </div>
                <div class="button-group">
                  <button 
                    @click="guardarCambiosPaso"
                    class="btn btn-success"
                  >
                    💾 Guardar cambios
                  </button>
                  <button 
                    @click="cancelarEdicionPaso"
                    class="btn btn-secondary"
                  >
                    ❌ Cancelar
                  </button>
                </div>
              </div>
            </div>

            <!-- Botón para cambiar a este paso -->
            <button 
              v-if="step.ordre > pasoActual"
              @click="cambiarPaso(step)"
              class="btn-cambiar-paso"
              title="Cambiar a este paso"
            >
              ▶️ Cambiar aquí
            </button>
          </div>
        </div>
      </div>

      <!-- Sin tracking -->
      <div v-else-if="ofertaSeleccionada && !cargando" class="empty-state">
        <p>Esta oferta no tiene pasos de tracking configurados.</p>
      </div>

      <!-- Cargando -->
      <div v-if="cargando" class="loading">
        <p>⏳ Cargando información...</p>
      </div>

      <!-- Mensajes -->
      <div v-if="mensajeSuceso" class="alert alert-success">
        ✅ {{ mensajeSuceso }}
      </div>
      <div v-if="mensajeError" class="alert alert-error">
        ❌ {{ mensajeError }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'TrackingOfertaComponent',
  
  data() {
    return {
      ofertas: [],
      ofertaSeleccionada: '',
      ofertaActual: null,
      trackingSteps: [],
      pasoActual: 1,
      cargando: false,
      mensajeSuceso: '',
      mensajeError: '',
      editandoPaso: null,
      pasoEditando: {
        descripcio: ''
      }
    };
  },

  mounted() {
    this.cargarOfertas();
  },

  methods: {
    /**
     * Carga la lista de ofertas disponibles
     */
    async cargarOfertas() {
      this.cargando = true;
      try {
        const response = await fetch('/api/ofertes', {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        });
        const resultado = await response.json();
        
        if (resultado.data) {
          this.ofertas = resultado.data;
        }
      } catch (error) {
        console.error('Error al cargar ofertas:', error);
        this.mensajeError = 'Error al cargar las ofertas';
      } finally {
        this.cargando = false;
      }
    },

    /**
     * Carga el tracking de la oferta seleccionada
     */
    async cargarTracking() {
      if (!this.ofertaSeleccionada) {
        this.ofertaActual = null;
        this.trackingSteps = [];
        this.pasoActual = 1;
        return;
      }

      this.cargando = true;
      this.mensajeError = '';
      try {
        const response = await fetch(
          `/api/tracking-oferta/${this.ofertaSeleccionada}`,
          {
            headers: {
              'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
          }
        );
        const resultado = await response.json();

        if (resultado.success) {
          this.ofertaActual = resultado.data.oferta;
          this.trackingSteps = resultado.data.tracking_steps;
          
          // Establecer el paso actual (por defecto el primero)
          // En una app real, esto vendría de la BD
          this.pasoActual = this.ofertaActual.tracking_actual || 1;
        } else {
          this.mensajeError = resultado.message;
        }
      } catch (error) {
        console.error('Error al cargar tracking:', error);
        this.mensajeError = 'Error de conexión al servidor';
      } finally {
        this.cargando = false;
      }
    },

    /**
     * Cambia el paso actual del tracking
     */
    async cambiarPaso(step) {
      if (!confirm(`¿Deseas cambiar el tracking al paso ${step.ordre}?`)) {
        return;
      }

      try {
        const response = await fetch('/api/tracking-oferta/update-step', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          },
          body: JSON.stringify({
            oferta_id: this.ofertaSeleccionada,
            tracking_step_id: step.id
          })
        });

        const resultado = await response.json();

        if (resultado.success) {
          this.pasoActual = step.ordre;
          this.mensajeSuceso = resultado.message;
          setTimeout(() => {
            this.mensajeSuceso = '';
          }, 3000);
        } else {
          this.mensajeError = resultado.message;
        }
      } catch (error) {
        console.error('Error al cambiar paso:', error);
        this.mensajeError = 'Error de conexión al servidor';
      }
    },

    /**
     * Abre el editor para modificar un paso
     */
    abrirEditorPaso(step) {
      this.editandoPaso = step;
      this.pasoEditando = {
        descripcio: step.descripcio || ''
      };
    },

    /**
     * Guarda los cambios del paso
     */
    async guardarCambiosPaso() {
      // En una aplicación real, aquí haríamos una llamada al servidor
      // Para este ejemplo, solo actualizamos localmente
      if (this.editandoPaso) {
        this.editandoPaso.descripcio = this.pasoEditando.descripcio;
        this.mensajeSuceso = 'Cambios guardados correctamente';
        setTimeout(() => {
          this.mensajeSuceso = '';
          this.cancelarEdicionPaso();
        }, 2000);
      }
    },

    /**
     * Cancela la edición
     */
    cancelarEdicionPaso() {
      this.editandoPaso = null;
      this.pasoEditando = {
        descripcio: ''
      };
    }
  }
};
</script>

<style scoped>
.tracking-oferta-container {
  padding: 20px;
  background-color: #f5f5f5;
  min-height: 100vh;
}

.card {
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  overflow: hidden;
}

.card-header {
  background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
  color: white;
  padding: 30px;
  text-align: center;
}

.card-header h2 {
  margin: 0;
  font-size: 28px;
  font-weight: 600;
}

.subtitle {
  margin: 10px 0 0 0;
  opacity: 0.9;
  font-size: 14px;
}

.selector-section {
  padding: 20px;
  border-bottom: 1px solid #e0e0e0;
  background: #f9f9f9;
}

.form-field {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.form-field label {
  font-weight: 600;
  color: #333;
  font-size: 14px;
}

.form-field select {
  padding: 10px;
  border: 2px solid #ddd;
  border-radius: 4px;
  font-size: 14px;
  cursor: pointer;
}

.form-field select:focus {
  outline: none;
  border-color: #f5576c;
  box-shadow: 0 0 0 3px rgba(245, 87, 108, 0.1);
}

.info-section {
  padding: 20px;
  background: #f9f9f9;
  border-bottom: 1px solid #e0e0e0;
}

.info-section h3 {
  margin-top: 0;
  color: #333;
}

.info-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 15px;
}

.info-item {
  display: flex;
  justify-content: space-between;
  padding: 10px;
  background: white;
  border-radius: 4px;
  border: 1px solid #e0e0e0;
}

.info-item .label {
  font-weight: 600;
  color: #666;
  min-width: 120px;
}

.info-item .value {
  color: #333;
  font-weight: 500;
}

.tracking-section {
  padding: 30px;
}

.tracking-section h3 {
  margin-top: 0;
  color: #333;
}

.timeline {
  position: relative;
  padding: 20px 0;
}

.timeline-item {
  position: relative;
  padding-left: 80px;
  margin-bottom: 30px;
  display: flex;
  flex-direction: column;
}

.timeline-item:last-child .timeline-connector {
  display: none;
}

.timeline-connector {
  position: absolute;
  left: 30px;
  top: 50px;
  width: 2px;
  height: calc(100% + 30px);
  background: #e0e0e0;
}

.timeline-item.completado .timeline-connector {
  background: #27ae60;
}

.timeline-item.actual .timeline-connector {
  background: #f5576c;
}

.timeline-dot {
  position: absolute;
  left: 0;
  top: 0;
  width: 50px;
  height: 50px;
  border-radius: 50%;
  background: #f0f0f0;
  border: 2px solid #e0e0e0;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  z-index: 1;
}

.timeline-item.completado .timeline-dot {
  background: #27ae60;
  border-color: #27ae60;
}

.timeline-item.actual .timeline-dot {
  background: #f5576c;
  border-color: #f5576c;
  box-shadow: 0 0 0 8px rgba(245, 87, 108, 0.2);
}

.timeline-content {
  background: #f9f9f9;
  padding: 15px;
  border-radius: 4px;
  border-left: 4px solid #e0e0e0;
}

.timeline-item.completado .timeline-content {
  border-left-color: #27ae60;
  background: #f0fdf4;
}

.timeline-item.actual .timeline-content {
  border-left-color: #f5576c;
  background: #fff5f7;
}

.timeline-content h4 {
  margin: 0 0 10px 0;
  color: #333;
  font-size: 16px;
}

.timeline-content .descripcion {
  margin: 5px 0;
  color: #666;
  font-size: 14px;
}

.paso-actual-info {
  margin-top: 15px;
  padding: 10px;
  background: #fff3cd;
  border: 1px solid #ffc107;
  border-radius: 4px;
  color: #333;
}

.paso-actual-info p {
  margin: 0 0 10px 0;
  font-weight: 600;
  color: #856404;
}

.editor-paso {
  margin-top: 15px;
  padding: 15px;
  background: white;
  border: 1px solid #ddd;
  border-radius: 4px;
}

.editor-paso .form-field textarea {
  padding: 8px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-family: inherit;
  resize: vertical;
}

.editor-paso .form-field textarea:focus {
  outline: none;
  border-color: #f5576c;
  box-shadow: 0 0 0 3px rgba(245, 87, 108, 0.1);
}

.button-group {
  display: flex;
  gap: 10px;
  margin-top: 10px;
}

.btn {
  padding: 8px 12px;
  border: none;
  border-radius: 4px;
  font-size: 13px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s;
}

.btn-edit {
  background-color: #3498db;
  color: white;
}

.btn-edit:hover {
  background-color: #2980b9;
}

.btn-success {
  background-color: #27ae60;
  color: white;
}

.btn-success:hover {
  background-color: #229954;
}

.btn-secondary {
  background-color: #95a5a6;
  color: white;
}

.btn-secondary:hover {
  background-color: #7f8c8d;
}

.btn-cambiar-paso {
  align-self: flex-start;
  margin-top: 10px;
  padding: 8px 12px;
  background-color: #f5576c;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: 500;
  transition: background-color 0.3s;
}

.btn-cambiar-paso:hover {
  background-color: #f23c52;
}

.alert {
  padding: 15px;
  border-radius: 4px;
  margin: 20px;
  margin-bottom: 0;
}

.alert-success {
  background-color: #d4edda;
  color: #155724;
  border: 1px solid #c3e6cb;
}

.alert-error {
  background-color: #f8d7da;
  color: #721c24;
  border: 1px solid #f5c6cb;
}

.loading {
  text-align: center;
  padding: 40px;
  color: #666;
  font-size: 16px;
}

.empty-state {
  text-align: center;
  padding: 40px;
  color: #999;
  font-size: 16px;
}

@media (max-width: 768px) {
  .card-header h2 {
    font-size: 20px;
  }

  .timeline-item {
    padding-left: 60px;
  }

  .timeline-dot {
    width: 40px;
    height: 40px;
    font-size: 18px;
  }

  .info-grid {
    grid-template-columns: 1fr;
  }

  .button-group {
    flex-direction: column;
  }

  .button-group .btn {
    width: 100%;
  }
}
</style>
