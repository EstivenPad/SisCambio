<template>
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Crear Permiso</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->

    <div class="content container-fluid">
      <div class="card">
        <div class="card-header">
          <div class="card-tools">
            <router-link class="btn btn-info btn-sm" :to="'/permiso'">
              <i class="fas fa-arrow-left"></i> Regresar
            </router-link>
          </div>
        </div>
        <div class="card-body">
          <div class="container-fluid">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Formulario Registrar Permisos</h3>
              </div>
              <div class="card-body">
                <form role="form">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group row">
                        <label class=" col-md-2 col-form-label">Nombre</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control" v-model="Permiso.Nombre" @keyup.enter="setRegistrarPermiso()">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group row">
                        <label class="col-md-2 col-form-label">Url Amigable</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control" v-model="Permiso.Slug" @keyup.enter="setRegistrarPermiso()">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group row">
                        <label class="col-md-2 col-form-label">Módulo</label>
                        <div class="col-md-6">
                          <template>
                            <el-select v-model="Permiso.Modulo" placeholder="Módulo" clearable>
                              <el-option
                                v-for="item in opciones"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                              </el-option>
                            </el-select>
                          </template>
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
                    <button class="btn btn-info btnWidth" @click.prevent="setRegistrarPermiso" v-loading.fullscreen.lock="fullscreenLoading">Registrar</button>
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
        Permiso: {
          Nombre: '',
          Slug: '',
          Modulo: ''
        },
        listPermiso: [],
        opciones: [{
          value: 'Usuarios',
          label: 'Usuarios'
        }, {
          value: 'Roles',
          label: 'Roles'
        }, {
          value: 'Permisos',
          label: 'Permisos'
        }, {
          value: 'Clientes',
          label: 'Clientes'
        }, {
          value: 'Monedas',
          label: 'Monedas'
        }, {
          value: 'Almacenes',
          label: 'Almacenes'
        }],
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
    methods: {
      abrirModal(){
        this.modalShow = !this.modalShow;
      },
      limpiarCampos(){
        this.Permiso.Nombre = '';
        this.Permiso.Slug = '';
        this.Permiso.Modulo = '';
      },
      setRegistrarPermiso(){
        if(this.validarRegistrarPermiso()){
          this.modalShow = true;
          return;
        }

        this.fullscreenLoading = true;
        this.setGuardarPermiso();
      },
      setGuardarPermiso(){
        this.form.append("nombre", this.Permiso.Nombre);
        this.form.append("slug", this.Permiso.Slug);
        this.form.append("modulo", this.Permiso.Modulo);

        const config = { headers: { 'Content-Type': 'multipart/form-data' }};
        var url = '/permiso/setRegistrarPermiso';

        axios.post(url, this.form, config).then(response => {
          this.$router.push('/permiso');
        }).catch(error => {
          if(error.response.status == 401){
            this.$router.push({name: 'login'});
            location.reload();
            sessionStorage.clear();
            this.fullscreenLoading = false;
          }
        })
      },
      validarRegistrarPermiso(){
        this.error = 0;
        this.mensajeError = [];

        if(!this.Permiso.Nombre){
          this.mensajeError.push("El Nombre es un campo obligatorio")
        }
        if(!this.Permiso.Slug){
          this.mensajeError.push("La Url Amigable es obligatorio")
        }
        if(!this.Permiso.Modulo){
          this.mensajeError.push("El Módulo es obligatorio")
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
