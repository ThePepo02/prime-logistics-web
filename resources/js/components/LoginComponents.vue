<template>
    <div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl p-10">

        <!-- Logo -->
        <div class="mb-6">
            <img :src="logoUrl" alt="Prime Logistics" class="h-8" />
        </div>

        <!-- Títol -->
        <h1 class="text-3xl font-bold text-gray-900 mb-2">
            Bienvenido/a
        </h1>
        <p class="text-gray-500 text-sm mb-8">
            Accede con las credenciales proporcionadas por tu administrador.
        </p>

        <!-- Error -->
        <div v-if="error" class="bg-red-100 text-red-700 text-sm px-4 py-3 rounded-lg mb-4">
            {{ error }}
        </div>

        <!-- Correu -->
        <div class="mb-4">
            <input v-model="form.correu" type="email" placeholder="Correo electrónico"
                class="border border-gray-300 text-gray-900 placeholder-gray-400 rounded-lg block w-full p-4 focus:outline-none focus:ring-2 focus:ring-orange-400 text-sm" />
        </div>

        <!-- Contrasenya -->
        <div class="mb-2">
            <input v-model="form.contrasenya" type="password" placeholder="Contraseña"
                class="border border-gray-300 text-gray-900 placeholder-gray-400 rounded-lg block w-full p-4 focus:outline-none focus:ring-2 focus:ring-orange-400 text-sm" />
        </div>

        <!-- Olvidaste contraseña -->
        <div class="text-right mb-6">
            <a href="#" class="text-sm text-orange-500 hover:underline">¿Olvidaste tu contraseña?</a>
        </div>

        <!-- Botó -->
        <button @click="handleLogin" :disabled="loading"
            class="w-full text-white bg-orange-500 hover:bg-orange-600 font-medium rounded-lg text-sm px-5 py-4 text-center disabled:opacity-50 cursor-pointer transition duration-200 flex items-center justify-center gap-2">
            {{ loading ? 'Accediendo...' : 'Acceder' }}
            <span v-if="!loading">›</span>
        </button>

        <!-- Footer -->
        <div class="mt-8 pt-6 border-t border-gray-100 text-center">
            <p class="text-xs text-gray-400 flex items-center justify-center gap-1">
                🔒 El acceso es proporcionado por el administrador.
            </p>
        </div>

    </div>
</template>

<script>
export default {
    name: 'LoginComponents',
    data() {
        return {
            logoUrl: window.location.origin + '/images/Prime-Logistics.png',
            form: {
                correu: '',
                contrasenya: '',
            },
            error: null,
            loading: false,
        };
    },
    methods: {
        async handleLogin() {
            this.error = null;
            this.loading = true;

            try {
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify(this.form),
                });

                const data = await response.json();

                if (!response.ok) {
                    this.error = data.message || 'Error al iniciar sessió';
                    return;
                }

                localStorage.setItem('token', data.token);
                localStorage.setItem('user', JSON.stringify(data.user));
                const rolId = data.user.rol_id;

                if (rolId == 1) {
                    window.location.href = '/dashboard';
                } else if (rolId == 2) {
                    window.location.href = '/operador';
                } else if (rolId == 3) {
                    window.location.href = '/cliente';
                } else if (rolId === 4) {
                    window.location.href = '/admin';
                } else {
                    this.error = 'Rol de usuario desconocido';
                }

            } catch (e) {
                this.error = 'Error de connexió amb el servidor';
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>
