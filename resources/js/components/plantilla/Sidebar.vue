<template>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="#" class="brand-link">
        <img :src="ruta + '/img/AdminLTELogo.png'"
            alt="Sistema Laravel y Vue"
            class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">Sis Cambio</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
            <img :src="ruta + '/img/avatar5.png'" class="img-circle elevation-2" alt="Juan Ramón Gómez">
            </div>
            <div class="info">
            <a href="#" class="d-block">Juan Ramón Gómez</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
                <router-link class="nav-link" :to="'/'" >
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                    Dashboard
                </p>
                </router-link>
            </li>
            <li class="nav-header">OPERACIONES</li>
            <li class="nav-item">
                <router-link class="nav-link" :to="'#'" >
                <i class="nav-icon fas fa-cash-register"></i>
                <p>
                    Pedidos
                </p>
                </router-link>
            </li>
            <li class="nav-item">
                <router-link class="nav-link" :to="'#'" >
                <i class="nav-icon fas fa-user-friends"></i>
                <p>
                    Clientes
                </p>
                </router-link>
            </li>
            <li class="nav-header">CONFIGURACIONES</li>
            <li class="nav-item">
                <router-link class="nav-link" :to="'#'" >
                <i class="nav-icon fas fa-sitemap"></i>
                <p>Categorias</p>
                </router-link>
            </li>
            <li class="nav-item">
                <router-link class="nav-link" :to="'#'" >
                <i class="nav-icon fas fa-apple-alt"></i>
                <p>Productos</p>
                </router-link>
            </li>
            <li class="nav-header">ADMINISTRACIÓN</li>
            <li class="nav-item">
                <router-link class="nav-link" :to="'#'" >
                <i class="nav-icon fas fa-users"></i>
                <p>Usuarios</p>
                </router-link>
            </li>
            <li class="nav-item">
                <router-link class="nav-link" :to="'#'" >
                <i class="nav-icon fas fa-unlock-alt"></i>
                <p>Roles</p>
                </router-link>
            </li>
            <li class="nav-item">
                <router-link class="nav-link" :to="'#'" >
                <i class="nav-icon fas fa-key"></i>
                <p>Permisos</p>
                </router-link>
            </li>
            <li class="nav-header">REPORTES</li>
            <li class="nav-item">
                <router-link class="nav-link" :to="'#'" >
                <i class="nav-icon fas fa-file-export"></i>
                <p>Pedidos</p>
                </router-link>
            </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>
</template>

<script>
    export default {
        props: ['ruta']
    }
</script>

<style>

</style>