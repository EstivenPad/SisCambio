<template>
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Crear Transaccion</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->

    <div class="content container-fluid">
      <div class="card">
        <div class="card-header">
          <div class="card-tools">
            <router-link class="btn btn-info btn-sm" :to="'/transaccion'">
              <i class="fas fa-arrow-left"></i> Regresar
            </router-link>
          </div>
        </div>
        <div class="card-body">
          <div class="container-fluid">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Formulario Registrar Transaccion</h3>
              </div>
              <div class="card-body">
                <el-tabs type="border-card">
                  
                <!-- Pestaña de Compra y Venta -->
                <el-tab-pane label="Compra y Venta">
                  <form role="form">
                    <div class="row">
                      <div class="col-md-12">
                        <h3 class="card-title">Compra y Venta</h3>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-md-3 col-form-label">Tipo de transacción</label>
                          <div class="col-md-9">
                            <RockerSwitch :size="0.8" backgroundColorOn="#bd5757" backgroundColorOff="#0084d0" labelOn="Compra" labelOff="Venta" :value="Transaccion.Tipo"/>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-md-3 col-form-label">Moneda</label>
                          <div class="col-md-9">
                            <el-select v-model="Transaccion.Moneda" filterable placeholder="Seleccione una Moneda">
                              <el-option
                                v-for="item in Monedas"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                              </el-option>
                            </el-select>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group row">
                          <label class="col-md-2 col-form-label">Monto</label>
                          <div class="col-md-10">
                            <el-input placeholder="" v-model="Transaccion.CantidadPeso">
                              <template slot="prepend">Pesos Dominicanos</template>
                            </el-input>
                            <el-input placeholder="" v-model="Transaccion.CantidadDivisa">
                              <template slot="prepend">Moneda Extranjera</template>
                            </el-input>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-md-3 col-form-label">Total</label>
                          <div class="col-md-9">
                            <input type="text" class="form-control" v-model="Transaccion.Total" placeholder="Total">
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                </el-tab-pane>

                <!-- Pestaña de Cheques -->
                <el-tab-pane label="Cheques">
                
                </el-tab-pane>
              </el-tabs>
              </div>                
              <div class="card-footer">
                <div class="row">
                  <div class="col-md-4 offset-4">
                    <button class="btn btn-info btnWidth" @click.prevent="setRegistrarTransaccion" v-loading.fullscreen.lock="fullscreenLoading">Registrar</button>
                    <button class="btn btn-default btnWidth" @click.prevent="limpiarCampos">Limpiar</button>
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
  // Import package
  import RockerSwitch from "vue-rocker-switch";
  // Import styles
  import "vue-rocker-switch/dist/vue-rocker-switch.css";
  
  export default {
    components: {
      RockerSwitch
    },
    data(){
      return {
        Transaccion: {
          Tipo: false,
          Moneda: '',
          CantidadPeso: '',
          CantidadDivisa: '',
          Total: '',
        },
        Monedas: [{
          value: 'Dolares',
          label: 'Dolares'
        }, {
          value: 'Euros',
          label: 'Euros'
        }, {
          value: 'Pesos Dominicanos',
          label: 'Pesos Dominicanos'
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
        this.Transaccion.Tipo = false;
        this.Transaccion.Moneda = 'Seleccione una moneda';
        this.Transaccion.PrecioCompra = '';
        this.Transaccion.PrecioVenta = '';
        this.Transaccion.Total = '';
      },
      setRegistrarTransaccion(){
        if(this.validarCampos()){
          this.modalShow = true;
          return;
        }

        this.fullscreenLoading = true;
        this.setGuardarMoneda();
      },
      setGuardarMoneda(){
        this.form.append("tipo", this.Transaccion.Tipo);
        this.form.append("moneda", this.Transaccion.Moneda);
        this.form.append("precioCompra", this.Transaccion.PrecioCompra);
        this.form.append("precioVenta", this.Transaccion.PrecioVenta);
        this.form.append("total", this.Transaccion.Total);

        const config = { headers: { 'Content-Type':'multipart/form-data' }};
        var url = '/transaccion/setRegistrarTransaccion';

        axios.post(url, this.form, config).then(response => {
          console.log('Se registró la transacción exitosamente');
          this.fullscreenLoading = false;
          this.$router.push('/transaccion');//Redirecciona al index
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

        if(!this.Transaccion.Moneda){
          this.mensajeError.push("La Moneda es un campo obligatorio")
        }
        if(!this.Transaccion.PrecioCompra){
          this.mensajeError.push("El Precio de Compra es un campo obligatorio")
        }
        if(!this.Transaccion.PrecioVenta){
          this.mensajeError.push("El Precio de Venta es un campo obligatorio")
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