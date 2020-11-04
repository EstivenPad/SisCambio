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
                  <div class="row">
                  <div class="col-md-6" style="text-align:right;">
                      <h2 v-text="labelCompraVenta"></h2>
                  </div>
                   <div class="col-md-6" style="text-align:right;">
                        <h2 style="font-weight:300;">Total:<span v-if="calcularCambio == null">00.0</span><span v-if="calcularCambio > 0" style="font-weight:500;">${{calcularCambio}} <span v-if="Transaccion.Tipo == true">Pesos.</span><span v-if="Transaccion.Tipo == false && Transaccion.CantidadPeso > 0">{{Transaccion.Moneda.nombre}}.</span><span v-if="Transaccion.Tipo == false && Transaccion.CantidadDivisa > 0">Pesos.</span></span></h2>
                    </div>
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
                          <div class="col-md-5" style="padding-right: 0px;" v-if="labelCompraVenta == 'Venta'">
                            <el-input  @input="changeMonto('pesos')"   min="5" type="number" placeholder="" id="union" v-model="Transaccion.CantidadPeso">
                              <template slot="prepend">Pesos</template>
                            </el-input>
                          </div>
                          <div class="col-md-5" style="padding-left: 0px;">
                            <el-input autofocus="true" type="number" min="5" @input="changeMonto('divisas')" placeholder="" v-model="Transaccion.CantidadDivisa">
                              <template slot="prepend">{{Transaccion.Moneda.nombre}}</template>
                            </el-input>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-5">
                        <div class="form-group row">
                          <label class="col-md-3 col-form-label">Moneda</label>
                          <div class="col-md-9">
                            <select v-model="Transaccion.Moneda" class="form-control" @change="getPrecioMoneda()">
                              <option
                                v-for="item in Monedas"
                                :key="item.id"
                                :label="item.nombre"
                                :value="{ id: item.id, nombre: item.nombre}">
                              </option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-7">
                          <div class="form-group row">
                            <label class="col-md-2 col-form-label">Precio</label>
                            <div class="col-md-5">
                              <input ref="precio" v-currency="optionCurrency" class="form-control" v-model="PrecioString">
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
                      <div class="col-md-6" style="display: flex;">
                            <h2 style="font-weight:300;">Total:<span v-if="calcularCambio == null">00.0</span></h2>
                            <h2 v-if="calcularCambio > 0">${{calcularCambio}} <span v-if="Transaccion.Tipo == true">Pesos.</span></h2>
                      </div>
                    </div>
                  </form>
                </el-tab-pane>

                <!-- Pestaña de Cheques -->
                <el-tab-pane label="Cheques">
                  <form role="form" >
                    <div class="row">

                      <div class="col-md-12">
                        <div class="form-group row">
                          <label class="col-md-2 col-form-label">Fecha de emisión</label>
                          <div class="col-md-3">
                            <input type="date" v-model="Cheque.FechaEmision">
                          </div>
                        </div>
                      </div>

                      <div class="col-md-12">
                        <div class="form-group row">
                          <label class="col-md-2 col-form-label">Banco</label>
                          <div class="col-md-3">
                            <el-select v-model="Cheque.Banco" placeholder="Seleccione un banco">
                              <el-option
                                v-for="item in Bancos"
                                :key="item.id"
                                :label="item.nombre"
                                :value="item.id">
                              </el-option>
                            </el-select>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-12">
                        <div class="form-group row">
                          <label class="col-md-2 col-form-label">Cliente</label>
                          <div class="col-md-3">
                            <el-select v-model="Cheque.Cliente" placeholder="Seleccione un cliente" filterable>
                              <el-option
                                v-for="item in Clientes"
                                :key="item.id"
                                :label="item.nombre + ' ' + item.apellido"
                                :value="item.id">
                              </el-option>
                            </el-select>
                            <button type="button" @click="modalShowCliente = !modalShowCliente" class="btn btn-outline-primary"><i class="fas fa-user-plus"></i></button>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-12">
                        <div class="form-group row">
                          <label class="col-md-2 col-form-label">Moneda</label>
                          <div class="col-md-3">
                            <select v-model="Cheque.Moneda" class="form-control">
                              <option
                                v-for="item in Monedas"
                                :key="item.id"
                                :label="item.nombre"
                                :value="{ id: item.id, nombre: item.nombre }">
                              </option>
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-12">
                          <div class="form-group row">
                            <label class="col-md-2 col-form-label">Número de Cheque</label>
                            <div class="col-md-3">
                              <input type="text" class="form-control" v-model="Cheque.NumeroCheque" placeholder="Ej. 1234">
                            </div>
                          </div>
                      </div>

                      <div class="col-md-12">
                        <div class="form-group row">
                          <label class="col-md-2 col-form-label">Concepto</label>
                          <div class="col-md-3">
                            <el-input
                              type="textarea"
                              :rows="4"
                              placeholder="Concepto del cheque"
                              v-model="Cheque.Concepto">
                            </el-input>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-12">
                        <div class="form-group row">
                          <label class="col-md-2 col-form-label">Monto de Cheque</label>
                          <div class="col-md-3">
                            <input type="number" class="form-control" v-model="Cheque.Monto">
                          </div>
                        </div>
                      </div>

                      <div class="col-md-12">
                        <div class="form-group row">
                          <label class="col-md-2 col-form-label">Porcentaje de Comisión</label>
                          <div class="col-md-3">
                            <div class="input-group mb-3">
                              <input type="number" step="0.01" class="form-control" v-model="Cheque.PorcentajeComision">
                              <div class="input-group-append">
                                <span class="input-group-text">%</span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-12">
                        <div class="form-group row">
                          <label class="col-md-2 col-form-label">Monto de Comisión</label>
                          <div class="col-md-3">
                            <input type="number" class="form-control" v-model="Cheque.Comision">
                          </div>
                        </div>
                      </div>

                      <div class="col-md-6" style="display: flex;">
                        <h2 style="font-weight:300;">Total: <span v-if="calcularCambio == ''">00.0</span></h2>
                      </div>
                    </div>
                  </form>
                </el-tab-pane>
              </el-tabs>
              </div>

              <div class="card-footer">
                <div class="row">
                  <div class="col-md-4 offset-4">
                    <button class="btn btn-default btnWidth" @click.prevent="limpiarCampos">Limpiar</button>
                    <button class="btn btn-info btnWidth" @click.prevent="setRegistrarTransaccion" v-loading.fullscreen.lock="fullscreenLoading">Registrar</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal para mostrar un pequeño registro de cliente -->
    <div class="modal fade" aria-hidden="true" :class="{ show: modalShowCliente }" :style=" modalShowCliente ? mostrarModal : ocultarModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title">Registro de Cliente</h5>
                    <button class="close" @click="abrirModalCliente"></button>
                </div>
                <div class="modal-body">
                  <div class="col-md-10">
                      <div class="form-group row">
                        <label class="col-md-4 col-form-label">Nombre</label>
                        <div class="col-md-8">
                          <input type="text" class="form-control" v-model="Cliente.Nombre" @keyup.enter="setGuardarCliente()" placeholder="Nombre">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-10">
                      <div class="form-group row">
                        <label class="col-md-4 col-form-label">Apellido</label>
                        <div class="col-md-8">
                          <input type="text" class="form-control" v-model="Cliente.Apellido" @keyup.enter="setGuardarCliente()" placeholder="Apellido">
                        </div>
                      </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button class="btn btn-danger" @click="abrirModalCliente">Cerrar</button>
                    <button class="btn btn-secondary" @click="limpiarCamposRegistroCliente">Limpiar</button>
                    <button class="btn btn-info" @click="setGuardarCliente">Registrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para mostrar los errores de validacion -->
    <div class="modal fade" :class="{ show: modalShow }" :style=" modalShow ? mostrarModal : ocultarModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Errores de validación</h5>
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
   import { getValue,setValue } from "vue-currency-input";

   import { CurrencyDirective } from 'vue-currency-input'
  // Import package
  import RockerSwitch from "vue-rocker-switch";
  // Import styles
  import "vue-rocker-switch/dist/vue-rocker-switch.css";

  export default {
    components: {
      RockerSwitch
    },
     directives: {
    currency: CurrencyDirective
  },
    data(){
      return {
        Transaccion: {
          Tipo: true,
          Moneda: {id:1, nombre:'Dolares'},
          CantidadPeso: 0,
          CantidadDivisa: 0,
          Precio: '',
          Total: ''
        },
        Cheque: {
          FechaEmision: '',
          Banco: '',
          Cliente: '',
          Moneda: {id:1, nombre:'Dolares'},
          NumeroCheque: '',
          Concepto: '',
          Monto: '',
          PorcentajeComision: 2.5,
          Comision: '',
          Total: 0,
        },
        optionCurrency:{
            currency: 'USD',
            locale: 'en',
            distractionFree: false,
            allowNegative: false
        },
        Cliente: {
          Nombre: '',
          Apellido: ''
        },
        Monedas: [],
        Bancos: [],
        Clientes: [],
        form: new FormData,
        fullscreenLoading: false,
        modalShow: false,
        modalShowCliente: false,
        mostrarModal: {
          display: 'block',
          background: '#0000006b'
        },
        ocultarModal: {
          display: 'none'
        },
        error: 0,
        mensajeError: [],
        labelCompraVenta:"Compra",
        currentValue:true,
        saberTab: false, // True = Compra y Venta | False = Cheques
        PrecioString:"",
      }
    },
    mounted(){
      this.getMonedas();

      this.getBancos();

      this.getClientes();
    },
    watch:{
        PrecioString() {

         this.Transaccion.Precio = getValue(this.$refs.precio);

            }
    },
    computed: {

          calcularCambio : function(){
          //venta
          var total = 0;
          if(this.Transaccion.Tipo == false){
              if ((this.Transaccion.CantidadDivisa > 0 || this.Transaccion.CantidadPeso > 0 ) && this.Transaccion.Precio > 0) {
                  if(this.Transaccion.CantidadDivisa > 0){
                    total = (this.Transaccion.CantidadDivisa * this.Transaccion.Precio).toFixed(2);

                  }
                  if(this.Transaccion.CantidadPeso > 0){
                    total = (this.Transaccion.CantidadPeso /  this.Transaccion.Precio).toFixed(2);

                  }
              }

          }
          //compra
          if(this.Transaccion.Tipo ==true){
            if(this.Transaccion.CantidadDivisa > 0 && this.Transaccion.Precio > 0) {
               total = (this.Transaccion.CantidadDivisa *  this.Transaccion.Precio).toFixed(2);

            }
          }
          this.Transaccion.Total = total;
          return total;
      }
    },
    methods: {
      abrirModal(){
        this.modalShow = !this.modalShow;
      },
      abrirModalCliente(){
        this.modalShowCliente = !this.modalShowCliente;

        this.Cliente.Nombre = '';
        this.Cliente.Apellido = '';
      },
      limpiarCampos(){
        this.Transaccion.Tipo = false;
        this.Transaccion.CantidadPeso = '';
        this.Transaccion.CantidadDivisa = '';
        this.Transaccion.Precio = '';
        this.PrecioString = '';
        this.Transaccion.Total = '';
        this.Transaccion.Moneda = {id:1, nombre:'Dolares'};
        this.getPrecioMoneda();
      },
      limpiarCamposRegistroCliente(){
        this.Cliente.Nombre = '';
        this.Cliente.Apellido = '';
      },
      setRegistrarTransaccion(){
        if(this.saberTab == true){//True = Compra y Venta

          if(this.validarCamposCompraVenta()){
            this.modalShow = true;
            return;
          }

          this.fullscreenLoading = true;
          this.setGuardarTransaccion();

        }else{//False = Cheques

          if(this.validarCamposCheque()){
            this.modalShow = true;
            return;
          }

          this.fullscreenLoading = true;
          this.setGuardarCheque();

        }
      },
      setGuardarTransaccion(){
        this.form.append("tipo", this.labelCompraVenta);
        this.form.append("moneda_id", this.Transaccion.Moneda.id);
        this.form.append("cantidadPeso", this.Transaccion.CantidadPeso);
        this.form.append("cantidadDivisa", this.Transaccion.CantidadDivisa);
        this.form.append("precio", this.Transaccion.Precio);
        this.form.append("total", this.Transaccion.Total);

        const config = { headers: { 'Content-Type':'multipart/form-data' }};
        var url = '/transaccion/setRegistrarTransaccionCompraVenta';

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
      setGuardarCheque(){
        this.form.append("fecha_emision", this.Cheque.FechaEmision);
        this.form.append("banco_id", this.Cheque.Banco);
        this.form.append("persona_id", this.Cheque.Cliente);
        this.form.append("moneda_id", this.Cheque.Moneda.id);
        this.form.append("numeroCheque", this.Cheque.NumeroCheque);
        this.form.append("concepto", this.Cheque.Concepto);
        this.form.append("monto", this.Cheque.Monto);
        this.form.append("porcentajeComision", this.Cheque.PorcentajeComision);
        this.form.append("comision", this.Cheque.Comision);
        this.form.append("totalEntregar", this.Cheque.Total);

        const config = { headers: { 'Content-Type':'multipart/form-data' }};
        var url = '/transaccion/setRegistrarTransaccionCheque';

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
      validarCamposCompraVenta(){
        this.error = 0;
        this.mensajeError = [];

        if(!this.Transaccion.Moneda){
          this.mensajeError.push("La Moneda es un campo obligatorio")
        }

        if(this.Transaccion.Tipo == true){
          if(this.Transaccion.CantidadDivisa <= 0){
              this.mensajeError.push("El Monto en " + this.Transaccion.Moneda.nombre + " no puede ser menor o igual a cero")
          }
        }else{
          if(this.Transaccion.CantidadPeso <= 0 && this.Transaccion.CantidadDivisa <= 0){
            this.mensajeError.push("Ambos Montos no pueden ser menor o igual a cero")
          }
        }

        if(this.Transaccion.Precio <= 0){
          this.mensajeError.push("El Precio no puede ser menor o igual a cero")
        }

        if(this.mensajeError.length){
          this.error = 1;
        }

        return this.error;
      },
      validarCamposCheque(){
        this.error = 0;
        this.mensajeError = [];

        if(!this.Cheque.FechaEmision){
          this.mensajeError.push("La Fecha de Emisión del Cheque es un campo obligatorio")
        }

        if(!this.Cheque.Banco){
          this.mensajeError.push("El Banco es un campo obligatorio")
        }

        if(!this.Cheque.Cliente){
          this.mensajeError.push("Debe seleccionar un Cliente")
        }

        if(!this.Cheque.NumeroCheque){
          this.mensajeError.push("El Número de Cheque es un campo obligatorio")
        }

        if(!this.Cheque.Concepto){
          this.mensajeError.push("El Concepto del Cheque es un campo obligatorio")
        }

        if(!this.Cheque.Monto){
          this.mensajeError.push("El Monto del Cheque es un campo obligatorio")
        }

        if(!this.Cheque.PorcentajeComision){
          this.mensajeError.push("El Porcentaje de Comisión es un campo obligatorio")
        }

        if(this.mensajeError.length){
          this.error = 1;
        }

        return this.error;
      },
      validarCamposRegistroCliente(){
        this.error = 0;
        this.mensajeError = [];

        if(!this.Cliente.Nombre){
          this.mensajeError.push("El nombre es un campo obligatorio")
        }

        if(!this.Cliente.Apellido){
          this.mensajeError.push("El apellido es un campo obligatorio")
        }

        if(this.mensajeError.length){
          this.error = 1;
        }

        return this.error;
      },
      getMonedas(){
        this.fullscreenLoading = true;

        var url = '/transaccion/getMonedas';

        axios.get(url).then(response => {
          var Monedas = response.data;

          Monedas.forEach((item, index) => {
            this.Monedas.push({
              'id'  	        :   item.id,
              'nombre'         :   item.nombre,
              'precioVenta'   :   item.precioVenta,
              'precioCompra'  :   item.precioCompra
            });
          });

          this.getPrecioMoneda();

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
      getPrecioMoneda(){
        this.Monedas.forEach((item, index) => {
          if(item.id == this.Transaccion.Moneda.id){
            if (this.labelCompraVenta == 'Venta') {
              setValue(this.$refs.precio,item.precioVenta.toString());
            }else{
                 setValue(this.$refs.precio,item.precioCompra.toString());

            }
          }
        });
      },
      getBancos(){
        this.fullscreenLoading = true;

        var url = '/transaccion/getBancos';

        axios.get(url).then(response => {
          var Bancos = response.data;

          Bancos.forEach((item, index) => {
            this.Bancos.push({
              'id'  	   :   item.id,
              'nombre'   :   item.nombre
            });
          });

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
      getClientes(){
        this.fullscreenLoading = true;

        var url = '/transaccion/getClientes';

        axios.get(url).then(response => {
          var Clientes = response.data;

          Clientes.forEach((item, index) => {
            this.Clientes.push({
              'id'  	   :   item.id,
              'nombre'   :   item.nombre,
              'apellido' :   item.apellido
            });
          });

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
      setGuardarCliente(){
        if(this.validarCamposRegistroCliente()){
          this.modalShow = true;
          return;
        }

        this.fullscreenLoading = true;
        var url = '/transaccion/setRegistrarCliente';

        axios.post(url, {
          'nombre' : this.Cliente.Nombre,
          'apellido' : this.Cliente.Apellido
        }).then(response => {
          this.Clientes = [];//Vacio el select de Clientes para volverlo a llenar con el nuevo cliente que acabo de crear

          this.getClientes();//Vuelvo a cargar el select de Clientes para que aparezca el nuevo cliente que acabo de crear

          this.fullscreenLoading = false;
          this.modalShowCliente = !this.modalShowCliente;//Cierro el modal del Registro de Cliente al terminar de guardar el Cliente
        }).catch(error => {
          if(error.response.status == 401){
            this.$router.push({name: 'login'});
            location.reload();
            sessionStorage.clear();
            this.fullscreenLoading = false;
          }
        });
      },
      //codigo de lewis

      //asignar valor Compra O Venta
      chageCompraVenta(){

        if(this.Transaccion.Tipo == false){
          this.labelCompraVenta = 'Venta';
          $('#union').focus();
          this.getPrecioMoneda();
        }else{
          this.labelCompraVenta = 'Compra';
          $('#union').focus();
          this.getPrecioMoneda();

        }
      },
      changeMonto(valor){
          if (valor == "pesos") {

              this.Transaccion.CantidadDivisa = 0;
          } else {

              this.Transaccion.CantidadPeso = 0;
          }
      }

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
