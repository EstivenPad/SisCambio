<template>
  <div>
    <!-- Navbar -->
    <Navbar :ruta="ruta"></Navbar>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <Sidebar :ruta="ruta" :usuario="authUser" :listaPermisos="listaPermisosByRol"></Sidebar>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <transition name="slide-fade" mode="out-in">
        <router-view></router-view>
      </transition>
    </div>    
    <!-- /.content-wrapper -->
    
    <Footer></Footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
</template>

<script>
  import Navbar from './plantilla/Navbar'
  import Sidebar from './plantilla/Sidebar'
  import Footer from './plantilla/Footer'

  export default {
    props: ['ruta', 'usuario'],
    components: {Navbar, Sidebar, Footer},
    data(){
      return{
        authUser: this.usuario,
        listaPermisosByRol: []
      }
    },
    mounted(){
      this.listaPermisosByRol = JSON.parse(sessionStorage.getItem('listaPermisosByRol'));
      
      EventBus.$on('verificarUsuarioAutenticado', data => {
        this.authUser = data;
      });

      EventBus.$on('notificarPermisosByRol', data => {
        this.listaPermisosByRol = data;
      });
    }
  }
</script>

<style>

</style>