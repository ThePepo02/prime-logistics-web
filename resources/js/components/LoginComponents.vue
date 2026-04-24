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
/* ===== Fondo general tipo dashboard oscuro ===== */
.login-wrapper {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    background: radial-gradient(circle at top, #0f172a, #020617);
}

/* ===== Card principal ===== */
.login-card {
    width: 100%;
    max-width: 420px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(12px);
    border-radius: 18px;
    padding: 40px;
    box-shadow: 0 25px 70px rgba(0, 0, 0, 0.4);
    border: 1px solid rgba(255, 255, 255, 0.2);
    animation: fadeIn 0.5s ease-in-out;
}

/* ===== Logo ===== */
.login-logo {
    height: 36px;
    margin-bottom: 16px;
}

/* ===== Título ===== */
.login-title {
    font-size: 26px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 6px;
}

/* ===== Subtítulo ===== */
.login-subtitle {
    font-size: 13px;
    color: #64748b;
    margin-bottom: 28px;
}

/* ===== Inputs ===== */
.login-input {
    width: 100%;
    padding: 14px 16px;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    font-size: 14px;
    outline: none;
    transition: all 0.2s ease;
    margin-bottom: 14px;
    background: #fff;
}

.login-input:focus {
    border-color: #f97316;
    box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.15);
}

/* ===== Error ===== */
.error-box {
    background: #fee2e2;
    color: #b91c1c;
    font-size: 13px;
    padding: 10px 14px;
    border-radius: 10px;
    margin-bottom: 14px;
}

/* ===== Forgot password ===== */
.forgot {
    text-align: right;
    margin-bottom: 18px;
}

.forgot a {
    font-size: 12px;
    color: #f97316;
    text-decoration: none;
    transition: 0.2s;
}

.forgot a:hover {
    color: #ea580c;
    text-decoration: underline;
}

/* ===== Botón ===== */
.login-btn {
    width: 100%;
    padding: 14px;
    border: none;
    border-radius: 12px;
    background: #f97316;
    color: white;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.login-btn:hover {
    background: #ea580c;
    transform: translateY(-1px);
    box-shadow: 0 10px 25px rgba(249, 115, 22, 0.3);
}

.login-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* ===== Divider ===== */
.divider {
    display: flex;
    align-items: center;
    margin: 24px 0;
    font-size: 11px;
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

/* ===== Footer ===== */
.footer {
    margin-top: 24px;
    padding-top: 16px;
    border-top: 1px solid #f1f5f9;
    text-align: center;
    font-size: 11px;
    color: #94a3b8;
}

/* ===== Animación ===== */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>