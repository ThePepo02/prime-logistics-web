<template>
    <div>
        <!-- encabezado -->
        <header>
            <div class="logo"></div>
            <title>Dashboard Admin</title>
        </header>
        <main>
            <h1>Dashboard</h1>
            <nav>
                <div class="administracion">
                    <h2>Administración</h2>
                    <ul>
                        <li><a href="">Dashboard</a></li>
                        <li><a href="">Gestión de Usuarios</a></li>
                    </ul>
                </div>
                <div class="operaciones">
                    <h2>Operaciones</h2>
                    <ul>
                        <li><a href="">Todas las Ofertas</a></li>
                        <li><a href="">Operaciones Activas</a></li>
                    </ul>
                </div>
                <div class="sistema">
                    <h2>Sistema</h2>
                    <ul>
                        <li><a href="">Datos Maestros</a></li>
                        <li><a href="">Configuración</a></li>
                    </ul>
                </div>
            </nav>
            <button type="button" class="btn_exportarDatos">
                <img src="/public/iconoExportarDatos.png" alt="Icono exportar datos">Exportar datos
            </button>
            <button type="button">
                <a href="">
                    <img src="/public/notificaciones-logo.png" alt="Notificaciones logo">
                </a>
            </button>
            <div id="iconoPerfilUsuarioAdmin">
                <img src="/public/perfilUsuarioAdmin.png" alt="Perfil Usuario administrador">
            </div>
        </main>
    </div>
</template>

<script setup>

</script>

<style lang="scss" scoped></style>