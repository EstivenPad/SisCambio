<template>
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Crear Moneda</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->

    <div class="content container-fluid">
      <div class="card">
        <div class="card-header">
          <div class="card-tools">
            <router-link class="btn btn-info btn-sm" :to="'/moneda'">
              <i class="fas fa-arrow-left"></i> Regresar
            </router-link>
          </div>
        </div>
        <div class="card-body">
          <div class="container-fluid">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Formulario Registrar Moneda</h3>
              </div>
              <div class="card-body">
                <form role="form">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Nombre</label>
                        <div class="col-md-9">
                          <input type="text" class="form-control" v-model="Moneda.Nombre" @keyup.enter="setRegistrarMoneda()" placeholder="Nombre">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Precio Compra</label>
                        <div class="col-md-9">
                          <input type="text" class="form-control" v-model="Moneda.PrecioCompra" @keyup.enter="setRegistrarMoneda()" placeholder="Precio Compra">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Precio Venta</label>
                        <div class="col-md-9">
                          <input type="text" class="form-control" v-model="Moneda.PrecioVenta" @keyup.enter="setRegistrarMoneda()" placeholder="Precio Venta">
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
                    <button class="btn btn-info btnWidth" @click.prevent="setRegistrarMoneda" v-loading.fullscreen.lock="fullscreenLoading">Registrar</button>
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
        Moneda: {
          Nombre: '',
          PrecioCompra: '',
          PrecioVenta: ''
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
    methods: {
      abrirModal(){
        this.modalShow = !this.modalShow;
      },
      limpiarCampos(){
        this.Moneda.Nombre = '';
        this.Moneda.PrecioCompra = '';
        this.Moneda.PrecioVenta = '';
      },
      setRegistrarMoneda(){
        if(this.validarCampos()){
          this.modalShow = true;
          return;
        }

        this.fullscreenLoading = true;
        this.setGuardarMoneda();
      },
      setGuardarMoneda(){
        this.form.append("nombre", this.Moneda.Nombre);
        this.form.append("precioCompra", this.Moneda.PrecioCompra);
        this.form.append("precioVenta", this.Moneda.PrecioVenta);

        const config = { headers: { 'Content-Type':'multipart/form-data' }};
        var url = '/moneda/setRegistrarMoneda';

        axios.post(url, this.form, config).then(response => {
          console.log('Se registró la moneda exitosamente');
          this.fullscreenLoading = false;
          this.$router.push('/moneda');//Redirecciona al index
        }).catch(error => {
          if(error.response.status == 401){
            this.$router.push({name: 'login'});
            location.reload();
            sessionStorage.clear();
            this.fullscreenLoading = false;
          }
        });
      },
      validarCampos(){
        this.error = 0;
        this.mensajeError = [];

        if(!this.Moneda.Nombre){
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