# 🏗️ ARQUITECTURA JERÁRQUICA DE COMPONENTES

## Concepto: Parent-Child Components

En Vue.js, los componentes pueden tener relaciones **padre-hijo** (parent-child). Esto permite:

✅ **Separación de responsabilidades**  
✅ **Reutilización de código**  
✅ **Fácil mantenimiento**  
✅ **Props hacia abajo, Events hacia arriba**  

---

## 📊 Diagrama de Arquite ctura

```
┌─────────────────────────────────────────────────────┐
│     IncotermMaintenanceComponent (PADRE)            │
│                                                     │
│  Responsabilidades:                                 │
│  - Gestiona estado CRUD                             │
│  - Comunicación con servidor                        │
│  - Lógica de negocio                                │
│  - Coordina componentes hijos                       │
│                                                     │
│  ┌──────────────────┐  ┌──────────────────┐       │
│  │ IncotermForm     │  │ IncotermSteps    │       │
│  │ Component        │  │ ModalComponent   │       │
│  │ (HIJO 1)         │  │ (HIJO 2)         │       │
│  │                  │  │                  │       │
│  │ Responsabilidad: │  │ Responsabilidad: │       │
│  │ - Mostrar form   │  │ - Mostrar modal  │       │
│  │ - Validar UI     │  │ - Seleccionar    │       │
│  │ - Emitir evento  │  │   pasos          │       │
│  │   guardar        │  │ - Emitir evento  │       │
│  │                  │  │   guardar        │       │
│  └──────────────────┘  └──────────────────┘       │
│                                                     │
│  ┌──────────────────────────────────────────┐      │
│  │ BadgeComponent (HIJO 3)                  │      │
│  │ Responsabilidad: Mostrar conteos         │      │
│  └──────────────────────────────────────────┘      │
└─────────────────────────────────────────────────────┘
```

---

## 🔄 Flujo de Datos

### Props (De Padre a Hijo)

```
PADRE                           HIJO
┌─────────────────┐            ┌─────────────────┐
│ initialData     │ ---------> │ @props           │
│ editando        │            │ initialData      │
│                 │            │ editando         │
└─────────────────┘            └─────────────────┘
```

**Ejemplo:**
```vue
<!-- En el PADRE -->
<incoterm-form-component
  :initial-data="formulario"        <!-- Prop: datos -->
  :editando="editando"              <!-- Prop: booleano -->
  @guardar="guardarIncoterm"        <!-- Event: guardar -->
  @cancelar="cerrarFormulario"      <!-- Event: cancelar -->
/>
```

### Events (De Hijo a Padre)

```
HIJO                            PADRE
┌─────────────────┐            ┌─────────────────┐
│ $emit('guardar',│ ---------> │ @guardar        │
│   { datos })    │            │ guardarIncoterm │
└─────────────────┘            └─────────────────┘
```

**Ejemplo:**
```vue
<!-- En el HIJO -->
<button @click="guardar">Guardar</button>

<!-- En script -->
guardar() {
  this.$emit('guardar', { codi, nom, descripcio });
}
```

---

## 📝 Estructura de Archivos

```
resources/js/components/
├── IncotermMaintenanceComponent.vue     (PADRE)
├── IncotermFormComponent.vue            (HIJO - Formulario)
├── IncotermStepsModalComponent.vue      (HIJO - Modal)
└── BadgeComponent.vue                   (HIJO - Badge)
```

---

## 💻 Componente Padre: IncotermMaintenanceComponent

**Responsabilidades:**

```javascript
export default {
  components: {
    IncotermFormComponent,
    IncotermStepsModalComponent,
    BadgeComponent
  },

  data() {
    return {
      incoterms: [],          // Lista de BD
      formulario: {},         // Datos del form
      mostrarFormulario: false,
      mostrarModal: false,
      incotermSeleccionado: null
    };
  },

  methods: {
    // CRUD Operations
    async cargarIncoterms() { /* ... */ }
    async guardarIncoterm(datos) { /* ... */ }
    async eliminarIncoterm(id) { /* ... */ }
    
    // UI Control
    abrirFormulario() { /* ... */ }
    cerrarFormulario() { /* ... */ }
    abrirSteps(incoterm) { /* ... */ }
    cerrarModal() { /* ... */ }
  }
};
```

---

## 👶 Componente Hijo 1: IncotermFormComponent

**Responsabilidades:**

```javascript
export default {
  props: {
    initialData: Object,    // Recibe datos del padre
    editando: Boolean       // Recibe estado del padre
  },

  data() {
    return {
      formData: {},         // Copia local de datos
      errores: {}
    };
  },

  methods: {
    actualizarCampo(campo, valor) {
      // Actualiza el campo local
      this.formData[campo] = valor;
    },

    guardar() {
      // Emite al padre con los datos
      this.$emit('guardar', this.formData);
    },

    cancelar() {
      // Emite al padre
      this.$emit('cancelar');
    }
  }
};
```

**Características:**

✅ Solo es responsable de mostrar el form  
✅ Recibe datos a través de `props`  
✅ Valida UI (no lógica)  
✅ Emite eventos al padre  
✅ NO se comunica directamente con el servidor  
✅ NO accede al estado del padre directamente  

---

## 👶 Componente Hijo 2: IncotermStepsModalComponent

**Responsabilidades:**

```javascript
export default {
  props: {
    mostrar: Boolean,       // Muestra/oculta modal
    incoterm: Object        // Incoterm seleccionado
  },

  data() {
    return {
      pasoSeleccionados: [],
      pasosPredefinidos: [
        { nom: 'Recolección', ... },
        { nom: 'Transporte', ... },
        // ... más pasos
      ]
    };
  },

  methods: {
    togglePaso(index) {
      // Toggle selección local
    },

    guardar() {
      // Emite al padre
      this.$emit('guardar', {
        pasosSeleccionados: this.pasoSeleccionados,
        incotermId: this.incoterm.id
      });
    }
  }
};
```

**Características:**

✅ Modal presentacional  
✅ Recibe `incoterm` a través de props  
✅ Mantiene estado local de selecciones  
✅ Emite evento al guardar  
✅ NO modifica el incoterm directamente  

---

## 👶 Componente Hijo 3: BadgeComponent

**Responsabilidades:**

```javascript
export default {
  props: {
    count: Number      // Recibe el número a mostrar
  }
};
```

**Características:**

✅ Componente presentacional puro  
✅ Solo recibe props  
✅ Solo muestra datos  
✅ Muy reutilizable  
✅ Sin lógica ni estado complejo  

---

## 🔄 Ejemplo de Flujo Completo

### 1. Usuario hace clic en "Nuevo Incoterm"

```
Usuario hace clic
         ↓
Padre: abrirFormulario()
         ↓
mostrarFormulario = true
         ↓
Renderiza <IncotermFormComponent /> (HIJO)
```

### 2. Usuario completa el formulario y hace clic en "Guardar"

```
Usuario completa form y hace clic
         ↓
Hijo: guardar()
         ↓
this.$emit('guardar', { codi, nom, descripcio })
         ↓
Evento llega al padre
```

### 3. Padre recibe el evento

```
Padre: @guardar="guardarIncoterm"
         ↓
guardarIncoterm(datos)
         ↓
Valida y envía al servidor
         ↓
Si es exitoso:
  - Recarga lista
  - Cierra formulario
  - Muestra mensaje
```

---

## 🎯 Ventajas de Esta Arquitectura

### 1. **Separación de Responsabilidades**
```
Padre: Lógica y estado
Hijo: Presentación
```

### 2. **Fácil Testing**
```javascript
// Puedes testear el hijo sin el padre
test('Form emite guardar con datos correctos', () => {
  // Testear IncotermFormComponent sin necesidad del padre
});
```

### 3. **Reutilización**
```javascript
// Puedes usar BadgeComponent en muchos lugares
<badge :count="10" />
<badge :count="5" />
<badge :count="0" />
```

### 4. **Fácil Mantenimiento**
```
Si necesitas cambiar el formulario:
  - Edita solo IncotermFormComponent
  - El padre no necesita cambios
```

### 5. **Data Flow Claro**
```
Props   → De padre a hijo (hacia abajo)
Events  → De hijo a padre (hacia arriba)
```

---

## 🚫 Anti-Patterns (Lo que NO hacer)

### ❌ NO accedas a $parent desde el hijo

```javascript
// ❌ MALO
this.$parent.incoterms = [];

// ✅ BIEN
this.$emit('actualizar-lista', nuevaLista);
```

### ❌ NO modifiques props directamente

```javascript
// ❌ MALO
this.initialData.nom = 'nuevo nombre';

// ✅ BIEN
this.formData.nom = 'nuevo nombre';
this.$emit('guardar', this.formData);
```

### ❌ NO llames al servidor desde el hijo

```javascript
// ❌ MALO - en IncotermFormComponent
async guardar() {
  const response = await fetch('/api/tipos-incoterm', ...);
}

// ✅ BIEN - en IncotermFormComponent
guardar() {
  this.$emit('guardar', this.formData);
}

// ✅ El padre maneja la petición al servidor
// en IncotermMaintenanceComponent
async guardarIncoterm(datos) {
  const response = await fetch('/api/tipos-incoterm', ...);
}
```

---

## 📚 Resumen de Props y Events

### IncotermFormComponent

**Props recibidas:**
```
- initialData: Object ({ codi, nom, descripcio })
- editando: Boolean
```

**Events emitidos:**
```
- guardar: { codi, nom, descripcio }
- cancelar: (sin datos)
```

### IncotermStepsModalComponent

**Props recibidas:**
```
- mostrar: Boolean
- incoterm: Object ({ id, codi, nom, descripcio })
```

**Events emitidos:**
```
- guardar: { pasosSeleccionados, incotermId }
- cerrar: (sin datos)
```

### BadgeComponent

**Props recibidas:**
```
- count: Number
```

**Events emitidos:**
```
(Ninguno - componente presentacional puro)
```

---

## 💡 Patrones de Vue.js Utilizados

### 1. Props Validation

```javascript
props: {
  initialData: {
    type: Object,
    default: () => ({})
  },
  editando: {
    type: Boolean,
    default: false
  }
}
```

### 2. Emitting Events

```javascript
this.$emit('guardar', payload);
this.$emit('cancelar');
```

### 3. Event Handling

```vue
<incoterm-form-component
  @guardar="guardarIncoterm"
  @cancelar="cerrarFormulario"
/>
```

### 4. Two-Way Binding Local

```javascript
// En el hijo, copia los datos en un data local
data() {
  return {
    formData: JSON.parse(JSON.stringify(this.initialData))
  };
}

// Así no modificas directamente las props
```

---

## 🎓 Para el Estudiante

### Esto demuestra:

✅ Comprensión de **jerarquía de componentes**  
✅ Uso correcto de **props y events**  
✅ **Separación de responsabilidades**  
✅ Buenas prácticas en **arquitectura Vue.js**  
✅ Componentes **reutilizables y mantenibles**  

### Es un patrón profesional que:

✅ Se usa en apps empresariales  
✅ Facilita el testing  
✅ Facilita la mantenibilidad  
✅ Es fácil de escalar  
✅ Sigue los estándares de Vue.js  

---

## 🚀 Próximos Pasos

Aplicar el mismo patrón a:

1. **TrackingOfertaComponent**
   - Padre: Gestiona lista de ofertas
   - Hijo: Componente del selector
   - Hijo: Componente del timeline

2. **Otros módulos**
   - Mantenimiento de usuarios
   - Gestión de transportistas
   - Configuración de rutas

---

**¡Arquitectura de componentes dominada!** 🎉
