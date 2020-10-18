<template>
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Editar Permiso</h1>
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
                <h3 class="card-title">Formulario Editar Permiso</h3>
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
                  </div>
                </form>
              </div>
              <div class="card-footer">
                <div class="row">
                  <div class="col-md-4 offset-4">
                    <button class="btn btn-flat btn-default btnWidth" @click.prevent="limpiarCampos">Limpiar</button>
                    <button class="btn btn-flat btn-info btnWidth" @click.prevent="setEditarPermiso" v-loading.fullscreen.lock="fullscreenLoading">Editar</button>
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
          Id: this.$attrs.id,
          Nombre: '',
          Slug: '',
        },
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
      this.getPermisoById();
    },
    methods: {
      getPermisoById(){
        this.fullscreenLoading = true;

        var url = '/permiso/getPermisoEditar'
        axios.get(url, {
          params: {
            'id': this.Permiso.Id,
          }
        }).then(response => {
          this.Permiso.Nombre = response.data.name;
          this.Permiso.Slug = response.data.slug;
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
      abrirModal(){
        this.modalShow = !this.modalShow;
      },
      limpiarCampos(){
        this.Permiso.Nombre = '';
        this.Permiso.Slug = '';
      },
      setEditarPermiso(){
        if(this.validarRegistrarPermiso()){
          this.modalShow = true;
          return;
        }

        this.fullscreenLoading = true;
        this.setGuardarPermiso();
      },
      setGuardarPermiso(){
        this.form.append("id", this.Permiso.Id);
        this.form.append("nombre", this.Permiso.Nombre);
        this.form.append("slug", this.Permiso.Slug);

        const config = { headers: { 'Content-Type':'multipart/form-data' }};
        var url = '/permiso/setEditarPermiso';

        axios.post(url, this.form, config).then(response => {
          Swal.fire({
            icon: 'success',
            title: 'Se actualizó el Permiso correctamente',
            showConfirmButton: false,
            timer: 1530
          });

          this.$router.push('/permiso');//Redirecciona al index
        }).catch(error => {
          if(error.response.status == 401){
            this.$router.push({name: 'login'});
            location.reload();
            sessionStorage.clear();
            this.fullscreenLoading = false;
          }
        });
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
