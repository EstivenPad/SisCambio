<template>
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Editar Almacen</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->

    <div class="content container-fluid">
      <div class="card">
        <div class="card-header">
          <div class="card-tools">
            <router-link class="btn btn-info btn-sm" :to="'/almacen'">
              <i class="fas fa-arrow-left"></i> Regresar
            </router-link>
          </div>
        </div>
        <div class="card-body">
          <div class="container-fluid">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Formulario Editar Almacen</h3>
              </div>
              <div class="card-body">
                <form role="form">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Nombre</label>
                        <div class="col-md-9">
                          <input type="text" class="form-control" v-model="Almacen.Nombre" @keyup.enter="setEditarAlmacen()" placeholder="Nombre">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Descripción</label>
                        <div class="col-md-9">
                        <input type="text" class="form-control" v-model="Almacen.Descripcion" @keyup.enter="setEditarAlmacen()" placeholder="Descripción">
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>                
              <div class="card-footer">
                <div class="row">
                  <div class="col-md-4 offset-4">
                    <button class="btn btn-flat btn-info btnWidth" @click.prevent="setEditarAlmacen" v-loading.fullscreen.lock="fullscreenLoading">Editar</button>
                    <button class="btn btn-flat btn-default btnWidth" @click.prevent="limpiarCampos">Limpiar</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" :class="{ show: modalShow }" :style="modalShow ? mostrarModal : ocultarModal">
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
        Almacen: {
          Id: this.$attrs.id,
          Nombre: '',
          Descripcion: ''
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
      this.getAlmacenPorId();
    },
    methods: {
      getAlmacenPorId(){
        this.fullscreenLoading = true;

        var url = '/almacen/getAlmacenEditar'
        axios.get(url, {
          params: {
            'id': this.Almacen.Id,
          }
        }).then(response => {
          this.Almacen.Nombre = response.data.nombre;
          this.Almacen.Descripcion = response.data.descripcion;
          this.fullscreenLoading = false;
        })
      },
      abrirModal(){
        this.modalShow = !this.modalShow;
      },
      limpiarCampos(){
        this.Almacen.Nombre = '';
        this.Almacen.Descripcion = '';
      },
      setEditarAlmacen(){
        if(this.validarCampos()){
          this.modalShow = true;
          return;
        }

        this.fullscreenLoading = true;
        this.setGuardarAlmacen();
      },
      setGuardarAlmacen(){
        this.form.append("id", this.Almacen.Id);
        this.form.append("nombre", this.Almacen.Nombre);
        this.form.append("descripcion", this.Almacen.Descripcion);

        const config = { headers: { 'Content-Type':'multipart/form-data' }};
        var url = '/almacen/setEditarAlmacen';

        axios.post(url, this.form, config).then(response => {
          this.fullscreenLoading = false;

          Swal.fire({
            icon: 'success',
            title: 'Se actualizó el almacen correctamente',
            showConfirmButton: false,
            timer: 1530
          });

          this.$router.push('/almacen');//Redirecciona al index
        });
      },
      validarCampos(){
        this.error = 0;
        this.mensajeError = [];

        if(!this.Almacen.Nombre){
          this.mensajeError.push("El Nombre es un campo obligatorio")
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