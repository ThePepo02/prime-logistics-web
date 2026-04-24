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
                    window.location.href = '/admin';
                } else if (rolId == 2) {
                    window.location.href = '/operador';
                } else if (rolId == 3) {
                    window.location.href = '/cliente';
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
<style scoped>
/* Fondo general */
.login-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: radial-gradient(circle at top left, #1e293b, #0f172a);
    padding: 20px;
}

/* Tarjeta */
.login-card {
    width: 100%;
    max-width: 420px;
    background: #ffffff;
    border-radius: 16px;
    padding: 32px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
    text-align: center;
}

/* Logo */
.login-logo {
    width: 90px;
    margin-bottom: 20px;
}

/* Título */
.login-title {
    font-size: 22px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 6px;
}

/* Subtítulo */
.login-subtitle {
    font-size: 13px;
    color: #64748b;
    margin-bottom: 24px;
}

/* Inputs */
.input-group {
    margin-bottom: 14px;
}

.input-group input {
    width: 100%;
    padding: 12px 14px;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    font-size: 14px;
    outline: none;
    transition: all 0.2s ease;
}

.input-group input:focus {
    border-color: #f97316;
    box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.15);
}

/* Forgot password */
.forgot {
    text-align: right;
    font-size: 12px;
    margin-top: -8px;
    margin-bottom: 18px;
}

.forgot a {
    color: #64748b;
    text-decoration: none;
}

.forgot a:hover {
    color: #f97316;
}

/* Botón */
.btn-login {
    width: 100%;
    padding: 12px;
    background: #f97316;
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s ease;
}

.btn-login:hover {
    background: #ea580c;
}

/* Divider */
.divider {
    display: flex;
    align-items: center;
    margin: 22px 0;
    font-size: 12px;
    color: #94a3b8;
}

.divider::before,
.divider::after {
    content: "";
    flex: 1;
    height: 1px;
    background: #e2e8f0;
}

.divider::before {
    margin-right: 10px;
}

.divider::after {
    margin-left: 10px;
}

/* Accesos rápidos */
.quick-access {
    display: grid;
    gap: 10px;
}

.user-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 12px;
    border-radius: 10px;
    background: #0f172a;
    color: white;
    cursor: pointer;
    transition: transform 0.2s ease;
}

.user-card:hover {
    transform: translateY(-2px);
}

.user-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.avatar {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 12px;
    color: white;
}

.avatar.orange {
    background: #f97316;
}

.avatar.green {
    background: #22c55e;
}

.avatar.blue {
    background: #3b82f6;
}

.role {
    font-size: 11px;
    color: #94a3b8;
}

/* Footer */
.footer {
    margin-top: 18px;
    font-size: 11px;
    color: #94a3b8;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}
</style>