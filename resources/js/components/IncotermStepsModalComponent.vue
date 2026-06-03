<template>
  <div v-if="mostrar" class="modal-overlay" @click.self="cerrar">
    <div class="modal-content">
      <div class="modal-header">
        <h2>📍 Asignar Pasos de Tracking</h2>
        <button @click="cerrar" class="close-btn">✕</button>
      </div>

      <div class="modal-body">
        <div class="incoterm-info">
          <p><strong>Incoterm:</strong> {{ incoterm.codi }} - {{ incoterm.nom }}</p>
          <p v-if="incoterm.descripcio" class="descripcion">
            <strong>Descripción:</strong> {{ incoterm.descripcio }}
          </p>
        </div>

        <!-- Lista de pasos predefinidos -->
        <div class="pasos-section">
          <h3>📋 Pasos Disponibles</h3>
          <p class="info-text">
            Selecciona los pasos que aplican a este incoterm. El orden es importante.
          </p>

          <div class="pasos-list">
            <div 
              v-for="(paso, index) in pasosPredefinidos"
              :key="index"
              class="paso-item"
              @click="togglePaso(index)"
              :class="{ 'seleccionado': pasoSeleccionados.includes(index) }"
            >
              <input 
                type="checkbox"
                :checked="pasoSeleccionados.includes(index)"
                @change="togglePaso(index)"
              />
              <div class="paso-info">
                <strong>{{ index + 1 }}. {{ paso.nom }}</strong>
                <p>{{ paso.descripcio }}</p>
              </div>
              <span class="paso-orden">Paso {{ index + 1 }}</span>
            </div>
          </div>
        </div>

        <!-- Preview del orden -->
        <div v-if="pasoSeleccionados.length > 0" class="preview-section">
          <h3>🔍 Preview del Tracking</h3>
          <ol class="preview-list">
            <li v-for="(idx, order) in pasoSeleccionados" :key="order">
              <span class="orden">{{ order + 1 }}.</span>
              <span class="nombre">{{ pasosPredefinidos[idx].nom }}</span>
            </li>
          </ol>
        </div>

        <!-- Advertencia si no hay pasos -->
        <div v-else class="alert alert-info">
          ℹ️ Selecciona al menos un paso para continuar
        </div>
      </div>

      <div class="modal-footer">
        <button @click="guardar" class="btn btn-success" :disabled="pasoSeleccionados.length === 0">
          💾 Guardar Pasos
        </button>
        <button @click="cerrar" class="btn btn-secondary">
          ❌ Cancelar
        </button>
      </div>
    </div>
  </div>
</template>

<script>
/**
 * IncotermStepsModalComponent - Componente hijo modal para seleccionar pasos
 * 
 * Este modal permite seleccionar y ordenar los pasos de tracking
 * que aplican a un incoterm específico.
 * 
 * Props:
 * - mostrar: boolean para mostrar/ocultar el modal
 * - incoterm: objeto con datos del incoterm
 * 
 * Events:
 * - guardar: emite { pasosSeleccionados }
 * - cerrar: sin datos
 */
export default {
  name: 'IncotermStepsModalComponent',

  props: {
    mostrar: {
      type: Boolean,
      default: false
    },
    incoterm: {
      type: Object,
      default: () => ({
        id: null,
        codi: '',
        nom: ''
      })
    }
  },

  data() {
    return {
      pasoSeleccionados: [],
      // Pasos predefinidos disponibles
      pasosPredefinidos: [
        {
          orden: 1,
          nom: 'Recolección',
          descripcio: 'Recogida de la mercancía en el almacén del vendedor'
        },
        {
          orden: 2,
          nom: 'Inspección y Documentación',
          descripcio: 'Verificación de la carga y preparación de documentos aduanales'
        },
        {
          orden: 3,
          nom: 'Transporte a Puerto',
          descripcio: 'Envío desde el almacén hasta el puerto de origen'
        },
        {
          orden: 4,
          nom: 'Carga en Buque',
          descripcio: 'Carga de la mercancía en el barco'
        },
        {
          orden: 5,
          nom: 'Tránsito Marítimo',
          descripcio: 'La mercancía se encuentra en tránsito por el océano'
        },
        {
          orden: 6,
          nom: 'Atraque en Puerto Destino',
          descripcio: 'Llegada del buque al puerto de destino'
        },
        {
          orden: 7,
          nom: 'Trámites Aduanales',
          descripcio: 'Presentación de documentos aduanales en el puerto destino'
        },
        {
          orden: 8,
          nom: 'Descarga',
          descripcio: 'Descarga de la mercancía del buque'
        },
        {
          orden: 9,
          nom: 'Transporte Local',
          descripcio: 'Transporte desde el puerto al lugar de destino final'
        },
        {
          orden: 10,
          nom: 'Entrega Final',
          descripcio: 'Entrega de la mercancía al comprador'
        }
      ]
    };
  },

  methods: {
    /**
     * Toggle selección de un paso
     */
    togglePaso(index) {
      const posicion = this.pasoSeleccionados.indexOf(index);
      if (posicion > -1) {
        this.pasoSeleccionados.splice(posicion, 1);
      } else {
        this.pasoSeleccionados.push(index);
      }
      // Mantener orden
      this.pasoSeleccionados.sort((a, b) => a - b);
    },

    /**
     * Guarda los pasos seleccionados
     */
    guardar() {
      if (this.pasoSeleccionados.length === 0) {
        alert('Selecciona al menos un paso');
        return;
      }

      const pasosGuardados = this.pasoSeleccionados.map(idx => ({
        ordem: idx + 1,
        nom: this.pasosPredefinidos[idx].nom,
        descripcio: this.pasosPredefinidos[idx].descripcio
      }));

      this.$emit('guardar', {
        pasosSeleccionados: pasosGuardados,
        incotermId: this.incoterm.id
      });

      this.limpiar();
    },

    /**
     * Cierra el modal
     */
    cerrar() {
      this.limpiar();
      this.$emit('cerrar');
    },

    /**
     * Limpia el estado
     */
    limpiar() {
      this.pasoSeleccionados = [];
    }
  }
};
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  padding: 20px;
}

.modal-content {
  background: white;
  border-radius: 8px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
  max-width: 600px;
  width: 100%;
  max-height: 80vh;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
  border-bottom: 2px solid #f0f0f0;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border-radius: 8px 8px 0 0;
}

.modal-header h2 {
  margin: 0;
  font-size: 20px;
}

.close-btn {
  background: none;
  border: none;
  color: white;
  font-size: 24px;
  cursor: pointer;
  padding: 0;
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 4px;
  transition: background 0.2s;
}

.close-btn:hover {
  background: rgba(255, 255, 255, 0.2);
}

.modal-body {
  padding: 25px;
  flex: 1;
  overflow-y: auto;
}

.incoterm-info {
  background: #f9f9f9;
  padding: 15px;
  border-left: 4px solid #667eea;
  border-radius: 4px;
  margin-bottom: 25px;
}

.incoterm-info p {
  margin: 5px 0;
  font-size: 14px;
}

.incoterm-info .descripcion {
  color: #666;
  font-style: italic;
}

.pasos-section h3 {
  margin: 0 0 10px 0;
  color: #333;
  font-size: 16px;
}

.info-text {
  color: #666;
  font-size: 13px;
  margin-bottom: 15px;
}

.pasos-list {
  display: grid;
  gap: 8px;
  margin-bottom: 20px;
}

.paso-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px;
  border: 2px solid #e0e0e0;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.2s;
}

.paso-item:hover {
  border-color: #667eea;
  background: #f9f9f9;
}

.paso-item.seleccionado {
  border-color: #27ae60;
  background: #f0fdf4;
}

.paso-item input[type="checkbox"] {
  width: 18px;
  height: 18px;
  cursor: pointer;
  flex-shrink: 0;
}

.paso-info {
  flex: 1;
}

.paso-info strong {
  display: block;
  color: #333;
  font-size: 14px;
  margin-bottom: 3px;
}

.paso-info p {
  color: #666;
  font-size: 12px;
  margin: 0;
}

.paso-orden {
  color: #999;
  font-size: 12px;
  flex-shrink: 0;
}

.preview-section {
  background: #f0f0f0;
  padding: 15px;
  border-radius: 6px;
  margin-bottom: 20px;
}

.preview-section h3 {
  margin: 0 0 10px 0;
  color: #333;
  font-size: 16px;
}

.preview-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.preview-list li {
  display: flex;
  align-items: center;
  padding: 8px 0;
  color: #333;
  font-size: 13px;
  border-bottom: 1px solid #e0e0e0;
}

.preview-list li:last-child {
  border-bottom: none;
}

.orden {
  font-weight: 600;
  color: #667eea;
  min-width: 25px;
}

.nombre {
  flex: 1;
}

.alert {
  padding: 12px;
  border-radius: 4px;
  margin: 15px 0;
}

.alert-info {
  background-color: #d1ecf1;
  color: #0c5460;
  border: 1px solid #bee5eb;
}

.modal-footer {
  padding: 15px 25px;
  border-top: 2px solid #f0f0f0;
  display: flex;
  gap: 10px;
  justify-content: flex-end;
  background: #f9f9f9;
  border-radius: 0 0 8px 8px;
}

.btn {
  padding: 10px 16px;
  border: none;
  border-radius: 4px;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s;
}

.btn-success {
  background-color: #27ae60;
  color: white;
}

.btn-success:hover:not(:disabled) {
  background-color: #229954;
}

.btn-success:disabled {
  background-color: #a0a0a0;
  cursor: not-allowed;
  opacity: 0.6;
}

.btn-secondary {
  background-color: #95a5a6;
  color: white;
}

.btn-secondary:hover {
  background-color: #7f8c8d;
}

@media (max-width: 768px) {
  .modal-overlay {
    padding: 10px;
  }

  .modal-content {
    max-height: 90vh;
  }

  .modal-header h2 {
    font-size: 16px;
  }

  .modal-body {
    padding: 15px;
  }

  .modal-footer {
    flex-direction: column;
  }

  .btn {
    width: 100%;
  }
}
</style>
