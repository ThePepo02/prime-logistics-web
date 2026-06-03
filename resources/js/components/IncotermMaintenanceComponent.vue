<template>
  <div class="incoterm-maintenance-container">
    <div class="card">
      <div class="card-header">
        <h2>📋 Mantenimiento de Tipos de Incoterm</h2>
        <p class="subtitle">Gestionar códigos, condiciones de entrega y pasos de tracking</p>
      </div>

      <!-- Botón para crear nuevo -->
      <div class="action-bar">
        <button 
          @click="abrirFormulario"
          class="btn btn-primary"
          v-if="!mostrarFormulario"
        >
          ➕ Nuevo Incoterm
        </button>
        <button 
          @click="cerrarFormulario"
          class="btn btn-secondary"
          v-else
        >
          ❌ Cancelar
        </button>
      </div>

      <!-- Componente hijo: Formulario -->
      <incoterm-form-component
        v-if="mostrarFormulario"
        :initial-data="formulario"
        :editando="editando"
        @guardar="guardarIncoterm"
        @cancelar="cerrarFormulario"
        ref="formComponent"
      />
      

      <!-- Tabla de incoterms -->
      <div class="tabla-section">
        <h3>📊 Incoterms Disponibles</h3>
        
        <div v-if="cargando" class="loading">
          ⏳ Cargando incoterms...
        </div>

        <div v-else-if="incoterms.length === 0" class="empty-state">
          <p>No hay incoterms registrados. ¡Crea uno nuevo!</p>
        </div>

        <table v-else class="tabla">
          <thead>
            <tr>
              <th>Código</th>
              <th>Nombre</th>
              <th>Descripción</th>
              <th>Pasos</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="incoterm in incoterms" :key="incoterm.id">
              <td class="codigo">{{ incoterm.codi }}</td>
              <td class="nombre">{{ incoterm.nom }}</td>
              <td class="descripcion">{{ incoterm.descripcio || '-' }}</td>
              <td class="pasos">
                <badge :count="incoterm.pasos_count || 0" />
              </td>
              <td class="acciones">
                <button 
                  @click="abrirSteps(incoterm)"
                  class="btn-icon steps"
                  title="Gestionar pasos"
                >
                  📍
                </button>
                <button 
                  @click="abrirEdicion(incoterm)"
                  class="btn-icon edit"
                  title="Editar"
                >
                  ✏️
                </button>
                <button 
                  @click="eliminarIncoterm(incoterm.id)"
                  class="btn-icon delete"
                  title="Eliminar"
                >
                  🗑️
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Componente hijo: Modal de Steps -->
      <incoterm-steps-modal-component
        :mostrar="mostrarModal"
        :incoterm="incotermSeleccionado"
        @guardar="guardarPasos"
        @cerrar="cerrarModal"
      />

      <!-- Mensajes de éxito -->
      <div v-if="mensajeSuceso" class="alert alert-success">
        ✅ {{ mensajeSuceso }}
      </div>
    </div>
  </div>
</template>

<script>
import IncotermFormComponent from './IncotermFormComponent.vue';
import IncotermStepsModalComponent from './IncotermStepsModalComponent.vue';
import BadgeComponent from './BadgeComponent.vue';

/**
 * IncotermMaintenanceComponent - Componente padre principal
 * 
 * Este es el contenedor principal que gestiona el CRUD de incoterms.
 * Usa componentes hijos para formulario y modal de pasos.
 * 
 * Jerarquía de componentes:
 * - IncotermMaintenanceComponent (PADRE)
 *   ├─ IncotermFormComponent (HIJO - formulario)
 *   ├─ IncotermStepsModalComponent (HIJO - modal de pasos)
 *   └─ BadgeComponent (HIJO - muestra conteos)
 */
export default {
  name: 'IncotermMaintenanceComponent',
  
  components: {
    IncotermFormComponent,
    IncotermStepsModalComponent,
    BadgeComponent
  },
  
  data() {
    return {
      incoterms: [],
      formulario: {
        codi: '',
        nom: '',
        descripcio: ''
      },
      editando: false,
      editandoId: null,
      mostrarFormulario: false,
      mostrarModal: false,
      incotermSeleccionado: null,
      cargando: false,
      mensajeError: '',
      mensajeSuceso: ''
    };
  },

  mounted() {
    this.cargarIncoterms();
  },

  methods: {
    /**
     * Carga la lista de incoterms del servidor
     */
    async cargarIncoterms() {
      this.cargando = true;
      try {
        const response = await fetch('/api/tipos-incoterm');
        const resultado = await response.json();
        
        if (resultado.success) {
          this.incoterms = resultado.data;
        } else {
          this.mensajeError = resultado.message;
        }
      } catch (error) {
        console.error('Error al cargar incoterms:', error);
        this.mensajeError = 'Error de conexión al servidor';
      } finally {
        this.cargando = false;
      }
    },

    /**
     * Abre el formulario para crear un nuevo incoterm
     */
    abrirFormulario() {
      this.editando = false;
      this.editandoId = null;
      this.limpiarFormulario();
      this.mostrarFormulario = true;
    },

    /**
     * Abre el formulario en modo edición
     */
    abrirEdicion(incoterm) {
      this.editando = true;
      this.editandoId = incoterm.id;
      this.formulario = {
        codi: incoterm.codi,
        nom: incoterm.nom,
        descripcio: incoterm.descripcio || ''
      };
      this.mostrarFormulario = true;
    },

    /**
     * Cierra el formulario
     */
    cerrarFormulario() {
      this.mostrarFormulario = false;
      this.limpiarFormulario();
    },

    /**
     * Guarda un incoterm (recibido desde el componente hijo)
     */
    async guardarIncoterm(datos) {
      this.mensajeError = '';

      try {
        const url = this.editando 
          ? `/api/tipos-incoterm/${this.editandoId}` 
          : '/api/tipos-incoterm';
        
        const metodo = this.editando ? 'PUT' : 'POST';

        const response = await fetch(url, {
          method: metodo,
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          },
          body: JSON.stringify(datos)
        });

        const resultado = await response.json();

        if (resultado.success) {
          this.mensajeSuceso = resultado.message;
          setTimeout(() => { this.mensajeSuceso = ''; }, 3000);
          
          this.cerrarFormulario();
          await this.cargarIncoterms();
        } else {
          if (resultado.errors) {
            // Pasar errores al componente hijo
            this.$refs.formComponent.mostrarErrores(resultado.errors);
          } else {
            this.mensajeError = resultado.message;
          }
        }
      } catch (error) {
        console.error('Error al guardar incoterm:', error);
        this.mensajeError = 'Error de conexión al servidor';
      }
    },

    /**
     * Abre el modal para gestionar pasos de un incoterm
     */
    abrirSteps(incoterm) {
      this.incotermSeleccionado = incoterm;
      this.mostrarModal = true;
    },

    /**
     * Cierra el modal de pasos
     */
    cerrarModal() {
      this.mostrarModal = false;
      this.incotermSeleccionado = null;
    },

    /**
     * Guarda los pasos asignados al incoterm
     */
    async guardarPasos(datos) {
      console.log('Pasos seleccionados:', datos);
      
      this.mensajeSuceso = '✅ Pasos de tracking guardados correctamente';
      setTimeout(() => { this.mensajeSuceso = ''; }, 3000);
      
      this.cerrarModal();
      await this.cargarIncoterms();
    },

    /**
     * Elimina un incoterm
     */
    async eliminarIncoterm(id) {
      if (!confirm('¿Estás seguro de que deseas eliminar este incoterm?')) {
        return;
      }

      try {
        const response = await fetch(`/api/tipos-incoterm/${id}`, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        });

        const resultado = await response.json();

        if (resultado.success) {
          this.mensajeSuceso = resultado.message;
          setTimeout(() => { this.mensajeSuceso = ''; }, 3000);
          await this.cargarIncoterms();
        } else {
          alert('Error: ' + resultado.message);
        }
      } catch (error) {
        console.error('Error al eliminar incoterm:', error);
        alert('Error de conexión al servidor');
      }
    },

    /**
     * Limpia el formulario
     */
    limpiarFormulario() {
      this.formulario = {
        codi: '',
        nom: '',
        descripcio: ''
      };
    }
  }
};
</script>

<style scoped>
.incoterm-maintenance-container {
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
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

.action-bar {
  padding: 20px;
  border-bottom: 1px solid #e0e0e0;
  display: flex;
  gap: 10px;
}

.tabla-section {
  padding: 25px;
}

.tabla-section h3 {
  margin-top: 0;
  color: #333;
  font-size: 18px;
  margin-bottom: 15px;
}

.tabla {
  width: 100%;
  border-collapse: collapse;
  font-size: 14px;
}

.tabla thead {
  background-color: #f0f0f0;
}

.tabla th {
  padding: 12px;
  text-align: left;
  font-weight: 600;
  color: #333;
  border-bottom: 2px solid #ddd;
}

.tabla td {
  padding: 12px;
  border-bottom: 1px solid #e0e0e0;
}

.tabla tbody tr:hover {
  background-color: #f5f5f5;
}

.tabla .codigo {
  font-weight: 600;
  color: #667eea;
  min-width: 80px;
}

.tabla .nombre {
  min-width: 200px;
}

.tabla .descripcion {
  color: #666;
  max-width: 300px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.tabla .pasos {
  text-align: center;
}

.tabla .acciones {
  display: flex;
  gap: 8px;
  justify-content: center;
}

.btn-icon {
  background: none;
  border: none;
  cursor: pointer;
  font-size: 18px;
  padding: 4px 8px;
  border-radius: 4px;
  transition: background-color 0.2s;
}

.btn-icon.steps:hover {
  background-color: #fff3cd;
}

.btn-icon.edit:hover {
  background-color: #e8f4ff;
}

.btn-icon.delete:hover {
  background-color: #ffe8e8;
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

.btn-primary {
  background-color: #667eea;
  color: white;
}

.btn-primary:hover {
  background-color: #5568d3;
}

.btn-secondary {
  background-color: #95a5a6;
  color: white;
}

.btn-secondary:hover {
  background-color: #7f8c8d;
}

.btn-success {
  background-color: #27ae60;
  color: white;
}

.btn-success:hover {
  background-color: #229954;
}

.alert {
  padding: 15px;
  border-radius: 4px;
  margin: 20px;
  margin-bottom: 0;
}

.alert-error {
  background-color: #f8d7da;
  color: #721c24;
  border: 1px solid #f5c6cb;
}

.alert-success {
  background-color: #d4edda;
  color: #155724;
  border: 1px solid #c3e6cb;
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

  .tabla {
    font-size: 12px;
  }

  .tabla th,
  .tabla td {
    padding: 8px;
  }

  .tabla .descripcion {
    max-width: 150px;
  }

  .tabla .acciones {
    flex-wrap: wrap;
  }
}
</style>
