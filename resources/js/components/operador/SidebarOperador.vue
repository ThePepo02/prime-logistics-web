<template>
    <aside class="sidebar-operador">

        <!-- LOGO -->
        <div class="sidebar-logo">
            <!-- DESPUÉS (ruta pública sin binding de Vue) -->
            <img :src="logoSrc" alt="Prime Logistics" />
        </div>

        <!-- NAVEGACIÓN -->
        <nav class="sidebar-nav">

            <!-- PRINCIPAL -->
            <div class="sidebar-section">
                <span class="sidebar-section-title">PRINCIPAL</span>
                <ul>
                    <li>
                        <button class="sidebar-btn" :class="{ active: activeRoute === 'dashboard' }"
                            @click="navigate('dashboard')">
                            <svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                            Dashboard
                        </button>
                    </li>
                    <li>
                        <button class="sidebar-btn" :class="{ active: activeRoute === 'clientes' }"
                            @click="navigate('clientes')">
                            <svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Clientes
                        </button>
                    </li>
                    <li>
                        <button class="sidebar-btn" :class="{ active: activeRoute === 'ofertas' }"
                            @click="navigate('ofertas')">
                            <svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Ofertas Comerciales
                        </button>
                    </li>
                    <li>
                        <button class="sidebar-btn" :class="{ active: activeRoute === 'operaciones' }"
                            @click="navigate('operaciones')">
                            <svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            Operaciones
                        </button>
                    </li>
                    <li>
                        <button class="sidebar-btn" :class="{ active: activeRoute === 'notificaciones' }"
                            @click="navigate('notificaciones')">
                            <svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            Notificaciones
                            <span v-if="notificacionesCount > 0" class="sidebar-badge">{{ notificacionesCount }}</span>
                        </button>
                    </li>
                </ul>
            </div>

            <!-- GESTIÓN -->
            <div class="sidebar-section">
                <span class="sidebar-section-title">GESTIÓN</span>
                <ul>
                    <li>
                        <button class="sidebar-btn" :class="{ active: activeRoute === 'seguimiento' }"
                            @click="navigate('seguimiento')">
                            <svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            Seguimiento
                        </button>
                    </li>
                    <li>
                        <button class="sidebar-btn" :class="{ active: activeRoute === 'documentacion' }"
                            @click="navigate('documentacion')">
                            <svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            Documentación
                        </button>
                    </li>
                </ul>
            </div>

            <!-- SISTEMA -->
            <div class="sidebar-section">
                <span class="sidebar-section-title">SISTEMA</span>
                <ul>
                    <li>
                        <button class="sidebar-btn" :class="{ active: activeRoute === 'configuracion' }"
                            @click="navigate('configuracion')">
                            <svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Configuración
                        </button>
                    </li>
                </ul>
            </div>

        </nav>

        <!-- PERFIL USUARIO -->
        <div class="sidebar-user">
            <div class="sidebar-user-avatar">{{ inicialesUsuario }}</div>
            <div class="sidebar-user-info">
                <span class="sidebar-user-name">{{ nombreUsuario }}</span>
                <span class="sidebar-user-role">OPERADORA</span>
            </div>
            <button class="sidebar-logout-btn" @click="logout" title="Cerrar sesión">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16"
                    height="16">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </button>
        </div>

    </aside>
</template>

<script>
import axios from 'axios'

export default {
    name: 'SidebarOperador',

    props: {
        activeRoute: {
            type: String,
            default: 'dashboard'
        },
        notificacionesCount: {
            type: Number,
            default: 0
        }
    },

    data() {
        return {
            logoSrc: window.location.origin + '/images/Prime-Logistics.png'
        }
    },

    computed: {
        nombreUsuario() {
            const usuario = JSON.parse(localStorage.getItem('usuario') || '{}')
            return usuario.nom || 'Operador'
        },
        inicialesUsuario() {
            const nombre = this.nombreUsuario
            const partes = nombre.split(' ')
            if (partes.length >= 2) {
                return (partes[0][0] + partes[1][0]).toUpperCase()
            }
            return nombre.substring(0, 2).toUpperCase()
        }
    },

    methods: {
        navigate(ruta) {
            this.$emit('navigate', ruta)
        },
        async logout() {
            try {
                const token = localStorage.getItem('token')
                await axios.post('/api/logout', {}, {
                    headers: { Authorization: `Bearer ${token}` }
                })
            } catch (e) {
                // Si falla la llamada, borramos igualmente
            } finally {
                localStorage.removeItem('token')
                localStorage.removeItem('usuario')
                window.location.href = '/'
            }
        }
    }
}
</script>

<style scoped>
.sidebar-operador {
    width: 216px;
    min-height: 100vh;
    background-color: #0d1e35;
    border-right: 1px solid #0d1e35;
    display: flex;
    flex-direction: column;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 100;
}

.sidebar-logo {
    height: 56px;
    display: flex;
    align-items: center;
    padding: 0 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.sidebar-logo img {
    height: 32px;
    object-fit: contain;
}

.sidebar-nav {
    flex: 1;
    padding: 24px 12px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 32px;
}

.sidebar-nav ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.sidebar-section {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.sidebar-section-title {
    font-size: 6.8px;
    font-weight: 700;
    color: white;
    letter-spacing: 0.05em;
    padding: 0 12px;
    text-transform: uppercase;
}

.sidebar-btn {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px 16px;
    background: transparent;
    border: none;
    border-left: 4px solid transparent;
    border-radius: 8px;
    color: white;
    font-size: 9.35px;
    font-weight: 500;
    cursor: pointer;
    text-align: left;
    transition: background 0.15s ease;
    position: relative;
}

.sidebar-btn:hover {
    background: rgba(255, 255, 255, 0.05);
}

.sidebar-btn.active {
    background: rgba(253, 113, 33, 0.1);
    border-left-color: #fd7121;
}

.sidebar-icon {
    width: 18px;
    height: 18px;
    flex-shrink: 0;
}

.sidebar-badge {
    margin-left: auto;
    background: #fd7121;
    color: white;
    font-size: 6.8px;
    font-weight: 700;
    width: 17px;
    height: 16px;
    border-radius: 9999px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.sidebar-user {
    height: 73px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    align-items: center;
    padding: 0 12px;
    gap: 8px;
}

.sidebar-user-avatar {
    width: 32px;
    height: 32px;
    border-radius: 9999px;
    background: linear-gradient(135deg, rgb(74, 222, 128) 25%, rgb(22, 163, 74) 95%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 7.65px;
    font-weight: 700;
    color: white;
    flex-shrink: 0;
}

.sidebar-user-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.sidebar-user-name {
    font-size: 9.35px;
    font-weight: 500;
    color: white;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.sidebar-user-role {
    font-size: 6.8px;
    font-weight: 700;
    color: rgba(255, 255, 255, 0.5);
}

.sidebar-logout-btn {
    background: transparent;
    border: none;
    cursor: pointer;
    color: rgba(255, 255, 255, 0.5);
    display: flex;
    align-items: center;
    padding: 4px;
    border-radius: 4px;
    transition: color 0.15s;
}

.sidebar-logout-btn:hover {
    color: white;
}
</style>
