<template >
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
                  <div style="text-align:center;">
                      <h2 v-text="labelCompaVenta"></h2>
                  </div>
                <el-tabs type="border-card">

                <!-- Pestaña de Compra y Venta -->
                <el-tab-pane label="Compra y Venta">

                  <form role="form" >
                    <div class="row">

                      <div class="col-md-5">
                        <div class="form-group row">
                          <label class="col-md-4 col-form-label">Tipo de transacción</label>
                          <div class="col-md-8">

                        <div class="rocker-switch-container" style="--onColor:#bd5757; --offColor:#0084d0; --borderColor:#eee; --activeColorLabel:#fff; --inactiveColorLabel:#333;">
                             <label class="rocker" style="font-size: 0.8em;">
                                <input type="checkbox" v-model="Transaccion.Tipo" @change="chageCompraVenta">
                                <span class="switch-left">
                                    <span>Compra</span>
                                </span>
                                <span class="switch-right">
                                    <span>Venta</span>
                                </span>
                            </label>
                        </div>
                          </div>
                        </div>
                      </div>
                         <div class="col-md-7">
                        <div class="form-group row">
                          <label class="col-md-2 col-form-label">Monto</label>
                          <div class="col-md-5" style="padding-right: 0px;" v-if="labelCompaVenta == 'Venta'">
                            <el-input placeholder="" id="union" v-model="Transaccion.CantidadPeso">
                              <template slot="prepend">Pesos</template>
                            </el-input>
                          </div>
                              <div class="col-md-5" style="padding-left: 0px;">
                            <el-input placeholder="" v-model="Transaccion.CantidadDivisa">
                              <template slot="prepend">{{Transaccion.Moneda}}</template>
                            </el-input>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-5">
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
                     <div class="col-md-7">
                        <div class="form-group row">
                          <label class="col-md-2 col-form-label">Precio</label>
                          <div class="col-md-5">
                            <input type="number"  class="form-control" v-model="Transaccion.Precio">
                          </div>
                        </div>
                     </div>
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-md-3 col-form-label">Total</label>
                          <div class="col-md-9">
                            <input type="text" class="form-control" v-model="Transaccion.Total" placeholder="Total">
                            <h2 v-if="calcularCambio > 0">${{calcularCambio}} <span v-if="Transaccion.Tipo == true">Pesos.</span></h2>
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
          Tipo:false,
          Moneda: 'Dolares',
          CantidadPeso: '',
          CantidadDivisa: '',
          Precio:50.6,
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
        labelCompaVenta:"Venta",
        currentValue:true,


      }
    },
    computed: {
          calcularCambio : function(){
          //venta
          var total = 0;
          if(this.Transaccion.Tipo == false){

          }
          //compra
          if(this.Transaccion.Tipo ==true){
            if(this.Transaccion.CantidadDivisa > 0 && this.Transaccion.Precio > 0) {
               total = (this.Transaccion.CantidadDivisa *  this.Transaccion.Precio).toFixed(2);
               return total;
            }
          }
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
      },

      //codigo de lewis

      //asignar valor Compra O Venta
      chageCompraVenta(){

       if( this.Transaccion.Tipo == false)
            this.labelCompaVenta = 'Venta';
          else
             this.labelCompaVenta = 'Compra';
      },


    }
  }
</script>

<style>
.rocker-switch-container .rocker {
     width: 11.6em !important;
     overflow: inherit !important;
    border-bottom: .5em solid #ddd !important;
}

.rocker-switch-container .switch-left {
  width: 6em !important;
    height: 2.68em !important;
}

.rocker-switch-container .switch-right {
  width: 5em !important;
}
.rocker-switch-container input:checked+.switch-left+.switch-right, .rocker-switch-container .switch-left:before{

    height: 2.83em !important;
}

.el-input-group__append, .el-input-group__prepend{
    color: black !important;
}
#union.el-input__inner{
    border-radius: 0px;
   border-right: transparent;
}
#union .el-input__inner, .el-input-group__prepend {
    border-top-left-radius: 0 !important;
    border-bottom-left-radius: 0 !important;
}


</style>
