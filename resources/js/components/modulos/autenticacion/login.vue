<template>
  <div class="login-box">
    <div class="login-logo">
      <router-link :to="{name: 'login'}">
        <b>Iniciar Sesión</b>
      </router-link>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Ingresa tus datos para iniciar sesión</p>

        <form method="post">
          <div class="input-group mb-3">
            <input type="text" v-model="Login.Usuario" class="form-control" placeholder="Usuario">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" v-model="Login.Contrasena" class="form-control" placeholder="Contraseña">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
        </form>

        <div class="row">
          <div class="col-md-12">
            <span v-if="error">
              <div v-for="(e, index) in mensajeError" :key="index" v-text="e" class="callout callout-danger" style="padding:2%; !important"></div>
            </span>
          </div>
        </div>

        <div class="social-auth-links text-center mb-3">          
          <button class="btn btn-flat btn-block btn-danger" @click.prevent="login" v-loading.fullscreen.lock="fullscreenLoading">
            Iniciar Sesión
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data(){
    return {
      Login: {
        Usuario: '',
        Contrasena: ''
      },
      fullscreenLoading: false,
      error: 0,
      mensajeError: []
    }
  },
  methods: {
    login(){
      if(this.validarCampos()){
        return;
      }

      var url = "/autenticacion/login"

      axios.post(url, {
        "usuario" : this.Login.Usuario,
        "contrasena" : this.Login.Contrasena
      }).then(response => {
        console.log(response.data);
        this.fullscreenLoading = false;
      });
    },
    validarCampos(){
      this.error = 0;
      this.mensajeError = [];

      if(!this.Login.Usuario){
        this.mensajeError.push("El Usuario es un campo obligatorio")
      }
      if(!this.Login.Contrasena){
        this.mensajeError.push("La Contraseña es un campo obligatorio")
      }

      if(this.mensajeError.length){
        this.error = 1;
      }

      return this.error;
      }
  }
}
</script>

<style>

</style>