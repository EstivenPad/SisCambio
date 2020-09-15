<template>
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Editar Usuario</h1>
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
                <h3 class="card-title">Formulario Editar Usuario</h3>
              </div>
              <div class="card-body">
                <form role="form">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Nombre</label>
                        <div class="col-md-9">
                          <input type="text" class="form-control" v-model="Usuario.Nombre" @keyup.enter="setEditarUsuario()">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Apellido</label>
                        <div class="col-md-9">
                          <input type="text" class="form-control" v-model="Usuario.Apellido" @keyup.enter="setEditarUsuario()">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Usuario</label>
                        <div class="col-md-9">
                          <input type="text" class="form-control" v-model="Usuario.Usuario" @keyup.enter="setEditarUsuario()">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Contraseña</label>
                        <div class="col-md-9">
                          <input type="text" class="form-control" v-model="Usuario.Contrasena" @keyup.enter="setEditarUsuario()">
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
                    <button class="btn btn-flat btn-info btnWidth" @click.prevent="setEditarUsuario" v-loading.fullscreen.lock="fullscreenLoading">Editar</button>
                    <button class="btn btn-flat btn-default btnWidth" @click.prevent="limpiarCampos">Limpiar</button>
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
          Id: this.$attrs.id,
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
    mounted() {   
      this.getUsuarioById();
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
      getUsuarioById(){
        this.fullscreenLoading = true;

        var url = '/usuario/getUsuarioEditar'
        axios.get(url, {
          params: {
            'id': this.Usuario.Id,
          }
        }).then(response => {
          this.Usuario.Nombre = response.data.nombre;
          this.Usuario.Apellido = response.data.apellido;
          this.Usuario.Usuario = response.data.usuario;
        }).catch(error => {
          if(error.response.status == 401){
            this.$router.push({name: 'login'});
            location.reload();
            sessionStorage.clear();
            this.fullscreenLoading = false;
          }
        })
      },
      getListaRoles(){
        var url = '/rol/getListaRoles';

        axios.get(url).then(response => {
          this.listaRoles = response.data;
          this.getRolByUsuario();
        }).catch(error => {
          if(error.response.status == 401){
            this.$router.push({name: 'login'});
            location.reload();
            sessionStorage.clear();
            this.fullscreenLoading = false;
          }
        })
      },
      getRolByUsuario(){
        var url = '/usuario/getRolByUsuario';

        axios.get(url, {
          params: {
            'id' : this.Usuario.Id
          }
        }).then(response => {
          this.Usuario.Rol = (response.data.length == 0) ? '' : response.data[0].role_id;
          this.fullscreenLoading = false;
        }).catch(error => {
          if(error.response.status == 401){
            this.$router.push({name: 'login'});
            location.reload();
            sessionStorage.clear();
            this.fullscreenLoading = false;
          }
        })
      },
      getRefrescarUsuarioEditado(){
        var url = '/autenticacion/getRefrescarUsuarioEditado';

        axios.get(url).then(response => {
          EventBus.$emit('verificarUsuarioAutenticado', response.data);

          this.fullscreenLoading = false;

          Swal.fire({
            icon: 'success',
            title: 'Se actualizó el usuario correctamente',
            showConfirmButton: false,
            timer: 1530
          });

          this.$router.push('/usuario');//Redirecciona al index
        });
      },
      getFile(e){
        this.Usuario.Imagen = e.target.files[0];
      },
      setEditarUsuario(){
        if(this.validarRegistrarUsuario()){
          this.modalShow = true;
          return;
        }

        this.fullscreenLoading = true;
        this.setGuardarUsuario();
      },
      setGuardarUsuario(){
        this.form.append("id", this.Usuario.Id);
        this.form.append("nombre", this.Usuario.Nombre);
        this.form.append("apellido", this.Usuario.Apellido);
        this.form.append("usuario", this.Usuario.Usuario);
        this.form.append("imagen", this.Usuario.Imagen);

        const config = { headers: { 'Content-Type':'multipart/form-data' }};
        var url = '/usuario/setEditarUsuario';

        axios.post(url, this.form, config).then(response => {
          this.setEditarRolUsuario();
        }).catch(error => {
          if(error.response.status == 401){
            this.$router.push({name: 'login'});
            location.reload();
            sessionStorage.clear();
            this.fullscreenLoading = false;
          }
        });
      },
      setEditarRolUsuario(){
        var url = '/usuario/setGuardarRolUsuario';
    	  
        axios.post(url, {
          'user_id' : this.Usuario.Id,
          'rol_id'  : this.Usuario.Rol
        }).then(response => {
          this.getRefrescarUsuarioEditado();
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