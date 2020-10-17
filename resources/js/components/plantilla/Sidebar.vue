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
            <div class="user-panel mt-3 pb-3 mb-1 d-flex">
                <div class="image">
                    <template v-if="!usuario.imagen">
                        <img :src="ruta + '/img/user.png'" class="img-circle elevation-2" alt="Imagen">
                    </template>
                    <template v-else>
                        <img :src="'storage/' + usuario.imagen" class="img-circle elevation-2" alt="Imagen" style="height: 34px !important; width: 34px !important">
                    </template>
                </div>
                <div class="info">
                    <span class="text-light">
                        {{ usuario.nombre + ' ' + usuario.apellido }}
                    </span>
                </div>
            </div>

            <div class="user-panel d-flex justify-content-center" @click.prevent="logout" v-loading.fullscreen.lock="fullscreenLoading">
                <div class="info">
                    <a href="#" class="d-block">
                        <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                    </a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <template v-if="listaPermisos.includes('index')">
                        <router-link class="nav-link" :to="'/'" >
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Inicio</p>
                        </router-link>
                    </template>
                </li>
                <template v-if="listaPermisos.includes('cliente.index', 'moneda.index', 'almacen.index', 'transaccion.index')">
                    <li class="nav-header">OPERACIONES</li>
                    <li class="nav-item">
                        <template v-if="listaPermisos.includes('cliente.index')">
                            <router-link class="nav-link" :to="'/cliente'" >
                            <i class="nav-icon fas fa-user-friends"></i>
                            <p>Clientes</p>
                            </router-link>
                        </template>
                    </li>
                    <li class="nav-item">
                        <template v-if="listaPermisos.includes('moneda.index')">
                            <router-link class="nav-link" :to="'/moneda'" >
                            <i class="nav-icon fas fa-coins"></i>
                            <p>Monedas</p>
                            </router-link>
                        </template>
                    </li>
                    <li class="nav-item">
                        <template v-if="listaPermisos.includes('almacen.index')">
                            <router-link class="nav-link" :to="'/almacen'" >
                            <i class="nav-icon fas fa-warehouse"></i>
                            <p>Almacenes</p>
                            </router-link>
                        </template>
                    </li>
                    <li class="nav-item">
                        <template v-if="listaPermisos.includes('transaccion.index')">
                            <router-link class="nav-link" :to="'/transaccion'" >
                            <i class="nav-icon fas fa-comments-dollar"></i>
                            <p>Transacciones</p>
                            </router-link>
                        </template>
                    </li>
                </template>
                
                <template v-if="listaPermisos.includes('categoria.index', 'producto.index')">
                    <li class="nav-header">CONFIGURACIONES</li>
                    <li class="nav-item">
                        <template v-if="listaPermisos.includes('categoria.index')">
                            <router-link class="nav-link" :to="'#'" >
                            <i class="nav-icon fas fa-sitemap"></i>
                            <p>Categorias</p>
                            </router-link>
                        </template>
                    </li>
                    <li class="nav-item">
                        <template v-if="listaPermisos.includes('producto.index')">
                            <router-link class="nav-link" :to="'#'" >
                            <i class="nav-icon fas fa-apple-alt"></i>
                            <p>Productos</p>
                            </router-link>
                        </template>
                    </li>
                </template>

                <template v-if="listaPermisos.includes('usuario.index', 'rol.index', 'permiso.index')">
                    <li class="nav-header">ADMINISTRACIÓN</li>
                    <li class="nav-item">
                        <template v-if="listaPermisos.includes('usuario.index')">
                            <router-link class="nav-link" :to="'/usuario'" >
                            <i class="nav-icon fas fa-users"></i>
                            <p>Usuarios</p>
                            </router-link>
                        </template>
                    </li>
                    <li class="nav-item">
                        <template v-if="listaPermisos.includes('rol.index')">
                            <router-link class="nav-link" :to="'/rol'" >
                            <i class="nav-icon fas fa-unlock-alt"></i>
                            <p>Roles</p>
                            </router-link>
                        </template>
                    </li>
                    <li class="nav-item">
                        <template v-if="listaPermisos.includes('permiso.index')">
                            <router-link class="nav-link" :to="'/permiso'" >
                            <i class="nav-icon fas fa-key"></i>
                            <p>Permisos</p>
                            </router-link>
                        </template>
                    </li>
                </template>

                <template v-if="listaPermisos.includes('reporte.pedido.index')">
                    <li class="nav-header">REPORTES</li>
                    <li class="nav-item">
                        <template v-if="listaPermisos.includes('reporte.pedido.index')">
                            <router-link class="nav-link" :to="'#'" >
                            <i class="nav-icon fas fa-file-export"></i>
                            <p>Pedidos</p>
                            </router-link>
                        </template>
                    </li>
                </template>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>
</template>

<script>
    export default {
        props: ['ruta', 'usuario', 'listaPermisos'],
        data(){
            return{
                fullscreenLoading: false
            }
        },
        methods: {
            logout(){
                this.fullscreenLoading = true;
                
                var url = '/autenticacion/logout';

                axios.post(url).then(response => {
                    if(response.data.code == 204){
                        this.$router.push({name: 'login'});
                        location.reload();
                        sessionStorage.clear();
                        this.fullscreenLoading = false;
                    }
                });
            }
        }
    }
</script>

<style>

</style>
