<template>
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Crear Usuario</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->

    <div class="content container-fluid">
      <div class="card">
        <div class="card-header">
          <div class="card-tools">
            <router-link class="btn btn-info btn-sm" :to="'/usuario'">
              <i class="fas fa-arrow-left"></i> Regresar
            </router-link>
          </div>
        </div>
        <div class="card-body">
          <div class="container-fluid">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Formulario Registrar Usuario</h3>
              </div>
              <div class="card-body">
                <form role="form">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Nombre</label>
                        <div class="col-md-9">
                          <input type="text" class="form-control" v-model="Usuario.Nombre" @keyup.enter="setRegistrarUsuario()">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Apellido</label>
                        <div class="col-md-9">
                          <input type="text" class="form-control" v-model="Usuario.Apellido" @keyup.enter="setRegistrarUsuario()">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Usuario</label>
                        <div class="col-md-9">
                          <input type="text" class="form-control" v-model="Usuario.Usuario" @keyup.enter="setRegistrarUsuario()">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Contraseña</label>
                        <div class="col-md-9">
                          <el-input placeholder="Ingrese una Contraseña" v-model="Usuario.Contrasena" @keyup.enter="setRegistrarUsuario" show-password></el-input>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Rol</label>
                        <div class="col-md-9">
                          <template>
                            <el-select v-model="Usuario.Rol" placeholder="Seleccione un rol">
                              <el-option
                                v-for="item in listaRoles"
                                :key="item.id"
                                :label="item.name"
                                :value="item.id">
                              </el-option>
                            </el-select>
                          </template>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Fotografía</label>
                        <div class="col-md-9">
                          <input type="file" @change="getFile">
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>                
              <div class="card-footer">
                <div class="row">
                  <div class="col-md-4 offset-4">
                    <button class="btn btn-default btnWidth" @click.prevent="limpiarCampos">Limpiar</button>
                    <button class="btn btn-info btnWidth" @click.prevent="setRegistrarUsuario" v-loading.fullscreen.lock="fullscreenLoading">Registrar</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" :class="{ show: modalShow }" :style=" modalShow ? mostrarModal : ocultarModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">SisCambio</h5>
                    <button class="close" @click="abrirModal"></button>
                </div>
                <div class="modal-body">
                  <div class="callout callout-danger" style="padding: 5px" v-for="(item, index) in mensajeError" :key="index" v-text="item"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="abrirModal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
  </div>
</template>

<script>
  export default {
    data(){
      return {
        Usuario: {
          Nombre: '',
          Apellido: '',
          Usuario: '',
          Contrasena: '',
          Rol: '',
          Imagen: ''
        },
        listaRoles: [],
        form: new FormData,
        fullscreenLoading: false,
        modalShow: false,
        mostrarModal: {
            display: 'block',
            background: '#0000006b'
        },
        ocultarModal: {
            display: 'none'
        },
        error: 0,
        mensajeError: [],
      }
    },
    mounted(){
      this.getListaRoles();
    },
    methods: {
      abrirModal(){
        this.modalShow = !this.modalShow;
      },
      limpiarCampos(){
        this.Usuario.Nombre = '';
        this.Usuario.Apellido = '';
        this.Usuario.Usuario = '';
        this.Usuario.Contrasena = '';
        this.Usuario.Rol = '';
        this.Usuario.Imagen = '';
      }, 
      getListaRoles(){
        var url = '/rol/getListaRoles';

        axios.get(url).then(response => {
          this.listaRoles = response.data;
        }).catch(error => {
          if(error.response.status == 401){
            this.$router.push({name: 'login'});
            location.reload();
            sessionStorage.clear();
            this.fullscreenLoading = false;
          }
        })
      },
      getFile(e){
        this.Usuario.Imagen = e.target.files[0];
      },
      setRegistrarUsuario(){
        if(this.validarRegistrarUsuario()){
          this.modalShow = true;
          return;
        }

        this.fullscreenLoading = true;
        this.setGuardarUsuario();
      },
      setGuardarUsuario(){
        this.form.append("nombre", this.Usuario.Nombre);
        this.form.append("apellido", this.Usuario.Apellido);
        this.form.append("usuario", this.Usuario.Usuario);
        this.form.append("password", this.Usuario.Contrasena);
        this.form.append('imagen', this.Usuario.Imagen)

        const config = { headers: { 'Content-Type': 'multipart/form-data' }}; 
        var url = '/usuario/setRegistrarUsuario';

        axios.post(url, this.form, config).then(response => {
          this.setGuardarRolUsuario(response.data);
        }).catch(error => {
          if(error.response.status == 401){
            this.$router.push({name: 'login'});
            location.reload();
            sessionStorage.clear();
            this.fullscreenLoading = false;
          }
        })
      },
      setGuardarRolUsuario(id){
        var url = '/usuario/setGuardarRolUsuario';
    	  
        axios.post(url, {
          'user_id' : id,
          'rol_id'  : this.Usuario.Rol
        }).then(response => {
          this.fullscreenLoading = false;
          this.$router.push('/usuario');
        }).catch(error => {
          if(error.response.status == 401){
            this.$router.push({name: 'login'});
            location.reload();
            sessionStorage.clear();
            this.fullscreenLoading = false;
          }
        })
      },
      validarRegistrarUsuario(){
        this.error = 0;
        this.mensajeError = [];

        if(!this.Usuario.Nombre){
          this.mensajeError.push("El Nombre es un campo obligatorio")
        }
        if(!this.Usuario.Usuario){
          this.mensajeError.push("El Usuario es un campo obligatorio")
        }
        if(!this.Usuario.Contrasena){
          this.mensajeError.push("La Contraseña es un campo obligatorio")
        }
        if(!this.Usuario.Rol){
          this.mensajeError.push("Debe seleccionar un rol para el usuario")
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