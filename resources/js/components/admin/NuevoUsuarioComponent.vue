<template>
    <div class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <h3>Nuevo Usuario</h3>
                <button class="close-btn" @click="$emit('close')">X</button>
            </div>

            <p class="subtitle">Completa los datos para crear el acceso</p>

            <form @submit.prevent="onSubmit">
                <div class="grid">
                    <!-- Nombre del usuario -->
                     <div>
                        <label>Nombre completo*</label>
                        <input v-model="form.name" placeholder="Ej: Ana López" required/>
                     </div>

                     <!-- Email del usuario -->
                      <div>
                        <label>Correo electrónico*</label>
                        <input v-model="form.email" type="email" placeholder="ana@empresa.com" required />
                      </div>

                      <!-- Empresa -->
                       <div>
                        <label>Empresa*</label>
                        <select v-model="form.company">
                            <option disabled value="">--Selecciona--</option>
                            <option value="1">Empresa 1</option>
                            <option value="2">Empresa 2</option>
                        </select>
                       </div>

                       <!-- Password -->
                        <div>
                            <label>Contraseña inicial</label>
                            <input v-model="form.password" placeholder="Vacío = auto-generar" />
                        </div>

                        <!-- Roles -->
                         <div class="roles">
                            <label>Rol del usuario*</label>
                            <div class="role-options">
                                <div class="role" :class="{ active: form.role === 'admin' }" @click="form.role = 'admin'">Administrador</div>
                                <div class="role" :class=" { active: form.role === 'operador' } " @click="form.role = 'operador'">Operador</div>
                                <div class="role" :class=" { active: form.role === 'cliente' } " @click="form.role = 'cliente'">Cliente</div>
                            </div>
                         </div>

                        <!-- Enviar credenciales por correo electrónico al crear el usuario -->
                         <div class="checkbox">
                            <input type="checkbox" v-model="form.sendEmail" />
                            <label>Enviar credenciales por email</label>
                         </div>

                        <!-- Botones crear usuario y cancelar -->
                        <footer class="footer">
                            <button type="button" class="cancel" @click="$emit('close')">Cancelar</button>
                            <button type="submit" class="submit">Crear Usuario</button>
                        </footer>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { reactive } from 'vue'

const emit = defineEmits(['create-user', 'close'])

const form = reactive({
    name: '',
    email: '',
    company: '',
    password: '',
    role: '',
    sendEmail: true
})

const onSubmit = () => {
    if (form.name === '' ||form.email == '' || form.company === '' || form.role === '') {
        alert('Formulario incompleto')
    } else {
        emit('create-user', { ...form })
    }
}
</script>

<style lang="scss" scoped>
.overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.4);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal {
  width: 600px;
  background: white;
  border-radius: 12px;
  padding: 20px;
}

.header {
  display: flex;
  justify-content: space-between;
}

.subtitle {
  font-size: 13px;
  color: #777;
  margin-bottom: 15px;
}

.grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
}

input,
select {
  width: 100%;
  padding: 8px;
  border: 1px solid #ddd;
  border-radius: 6px;
}

.roles {
  margin-top: 15px;
}

.role-options {
  display: flex;
  gap: 10px;
}

.role {
  flex: 1;
  padding: 10px;
  border: 1px solid #ddd;
  text-align: center;
  border-radius: 6px;
  cursor: pointer;
}

.role.active {
  border: 2px solid orange;
  background: #fff3e8;
}

.checkbox {
  margin-top: 10px;
}

.footer {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 20px;
}

.cancel {
  background: #eee;
  padding: 8px 12px;
}

.submit {
  background: orange;
  color: white;
  padding: 8px 12px;
}
</style>