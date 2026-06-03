<template>
  <div class="incoterm-form">
    <h3>{{ editando ? '✏️ Editar Incoterm' : '➕ Crear Nuevo Incoterm' }}</h3>
    
    <form @submit.prevent="guardar" class="form-group">
      <!-- Campo: Código -->
      <div class="form-field">
        <label for="codi">Código (máx. 10 caracteres):</label>
        <input
          id="codi"
          :value="formData.codi"
          @input="actualizarCampo('codi', $event.target.value)"
          type="text"
          maxlength="10"
          placeholder="Ej: CIF, FOB, DDP"
          required
        />
        <small v-if="errores.codi" class="error">{{ errores.codi[0] }}</small>
      </div>

      <!-- Campo: Nombre -->
      <div class="form-field">
        <label for="nom">Nombre:</label>
        <input
          id="nom"
          :value="formData.nom"
          @input="actualizarCampo('nom', $event.target.value)"
          type="text"
          placeholder="Ej: Cost, Insurance and Freight"
          required
        />
        <small v-if="errores.nom" class="error">{{ errores.nom[0] }}</small>
      </div>

      <!-- Campo: Descripción -->
      <div class="form-field">
        <label for="descripcio">Descripción:</label>
        <textarea
          id="descripcio"
          :value="formData.descripcio"
          @input="actualizarCampo('descripcio', $event.target.value)"
          rows="3"
          placeholder="Detalles sobre el incoterm..."
        ></textarea>
      </div>

      <!-- Botones de acción -->
      <div class="button-group">
        <button type="submit" class="btn btn-success" :disabled="guardando">
          {{ guardando ? '⏳ Guardando...' : (editando ? '💾 Actualizar' : '💾 Crear') }}
        </button>
        <button 
          type="button" 
          @click="cancelar"
          class="btn btn-secondary"
        >
          ❌ Cancelar
        </button>
      </div>

      <!-- Mensajes de error general -->
      <div v-if="mensajeError" class="alert alert-error">
        {{ mensajeError }}
      </div>
    </form>
  </div>
</template>

<script>
/**
 * IncotermFormComponent - Componente hijo para formulario de incoterm
 * 
 * Este es un componente presentacional que recibe datos a través de props
 * y emite eventos al padre para manejar el CRUD.
 * 
 * Props:
 * - initialData: datos del incoterm (para edición)
 * - editando: boolean indicando si estamos editando
 * 
 * Events:
 * - guardar: emite { codi, nom, descripcio }
 * - cancelar: sin datos
 */
export default {
  name: 'IncotermFormComponent',
  
  props: {
    initialData: {
      type: Object,
      default: () => ({
        codi: '',
        nom: '',
        descripcio: ''
      })
    },
    editando: {
      type: Boolean,
      default: false
    }
  },

  data() {
    return {
      formData: {
        codi: '',
        nom: '',
        descripcio: ''
      },
      errores: {},
      mensajeError: '',
      guardando: false
    };
  },

  watch: {
    // Cuando las props cambien, actualizar el formulario
    initialData(newVal) {
      this.formData = JSON.parse(JSON.stringify(newVal));
    }
  },

  mounted() {
    this.formData = JSON.parse(JSON.stringify(this.initialData));
  },

  methods: {
    /**
     * Actualiza un campo del formulario
     */
    actualizarCampo(campo, valor) {
      this.formData[campo] = valor;
      this.errores[campo] = null; // Limpiar error del campo
    },

    /**
     * Envía datos al padre
     */
    async guardar() {
      this.errores = {};
      this.mensajeError = '';
      this.guardando = true;

      try {
        // Emitir evento al padre con los datos
        this.$emit('guardar', {
          ...this.formData
        });
      } catch (error) {
        this.mensajeError = error.message;
      } finally {
        this.guardando = false;
      }
    },

    /**
     * Cancela la edición
     */
    cancelar() {
      this.$emit('cancelar');
    },

    /**
     * Muestra errores de validación
     */
    mostrarErrores(erroresDelServidor) {
      this.errores = erroresDelServidor;
    }
  }
};
</script>

<style scoped>
.incoterm-form {
  background: #f9f9f9;
  padding: 25px;
  border-radius: 8px;
  margin-bottom: 20px;
}

.incoterm-form h3 {
  margin-top: 0;
  color: #333;
  font-size: 18px;
  margin-bottom: 15px;
}

.form-group {
  display: grid;
  gap: 15px;
}

.form-field {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.form-field label {
  font-weight: 600;
  color: #333;
  font-size: 14px;
}

.form-field input,
.form-field textarea {
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 14px;
  font-family: inherit;
}

.form-field input:focus,
.form-field textarea:focus {
  outline: none;
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-field small.error {
  color: #e74c3c;
  font-size: 12px;
}

.button-group {
  display: flex;
  gap: 10px;
  justify-content: flex-start;
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
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-secondary {
  background-color: #95a5a6;
  color: white;
}

.btn-secondary:hover {
  background-color: #7f8c8d;
}

.alert {
  padding: 12px;
  border-radius: 4px;
  margin-top: 15px;
}

.alert-error {
  background-color: #fadbd8;
  color: #c0392b;
  border: 1px solid #e74c3c;
}

@media (max-width: 768px) {
  .incoterm-form {
    padding: 15px;
  }

  .button-group {
    flex-direction: column;
  }

  .button-group .btn {
    width: 100%;
  }
}
</style>
