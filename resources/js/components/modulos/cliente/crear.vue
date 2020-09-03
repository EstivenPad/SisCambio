<template>
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Crear Cliente</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->

    <div class="content container-fluid">
      <div class="card">
        <div class="card-header">
          <div class="card-tools">
            <router-link class="btn btn-info btn-sm" :to="'/cliente'">
              <i class="fas fa-arrow-left"></i> Regresar
            </router-link>
          </div>
        </div>
        <div class="card-body">
          <div class="container-fluid">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Formulario Registrar Cliente</h3>
              </div>
              <div class="card-body">
                <form role="form">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Nombre</label>
                        <div class="col-md-9">
                          <input type="text" class="form-control" v-model="Cliente.Nombre" @keyup.enter="setRegistrarCliente()" placeholder="Nombre">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Apellido</label>
                        <div class="col-md-9">
                          <input type="text" class="form-control" v-model="Cliente.Apellido" @keyup.enter="setRegistrarCliente()" placeholder="Apellido">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Teléfono</label>
                        <div class="col-md-9">
                          <input type="text" class="form-control" v-model="Cliente.Telefono" @keyup.enter="setRegistrarCliente()" placeholder="Teléfono">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Celular</label>
                        <div class="col-md-9">
                          <input type="text" class="form-control" v-model="Cliente.Celular" @keyup.enter="setRegistrarCliente()" placeholder="Celular">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Correo Electrónico</label>
                        <div class="col-md-9">
                          <input type="email" class="form-control" v-model="Cliente.Email" @keyup.enter="setRegistrarCliente()" placeholder="Correo Electrónico">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Dirección</label>
                        <div class="col-md-9">
                          <input type="text" class="form-control" v-model="Cliente.Direccion" @keyup.enter="setRegistrarCliente()" placeholder="Dirección">
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
                    <button class="btn btn-info btnWidth" @click.prevent="setRegistrarCliente" v-loading.fullscreen.lock="fullscreenLoading">Registrar</button>
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
  export default {
    data(){
      return {
        Cliente: {
          Nombre: '',
          Apellido: '',
          Telefono: '',
          Celular: '',
          Email: '',
          Direccion: '',
          Imagen: ''
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
        this.Cliente.Nombre = '';
        this.Cliente.Apellido = '';
        this.Cliente.Telefono = '';
        this.Cliente.Celular = '';
        this.Cliente.Email = '';
        this.Cliente.Direccion = '';
        this.Cliente.Imagen = '';
      },
      getFile(e){//Captura la imagen seleccionada en un objeto de "Cliente"
        this.Cliente.Imagen = e.target.files[0];
      },
      setRegistrarCliente(){
        if(this.validarCampos()){
          this.modalShow = true;
          return;
        }

        this.fullscreenLoading = true;
        this.setGuardarCliente();
      },
      setGuardarCliente(){
        this.form.append("nombre", this.Cliente.Nombre);
        this.form.append("apellido", this.Cliente.Apellido);
        this.form.append("telefono", this.Cliente.Telefono);
        this.form.append("celular", this.Cliente.Celular);
        this.form.append("email", this.Cliente.Email);
        this.form.append("direccion", this.Cliente.Direccion);
        this.form.append('imagen', this.Cliente.Imagen);

        const config = { headers: { 'Content-Type':'multipart/form-data' }};
        var url = '/cliente/setRegistrarCliente';

        axios.post(url, this.form, config).then(response => {
          console.log('Se registró el cliente exitosamente');
          this.fullscreenLoading = false;
          this.$router.push('/cliente');//Redirecciona al index
        });
      },
      validarCampos(){
        this.error = 0;
        this.mensajeError = [];

        if(!this.Cliente.Nombre){
          this.mensajeError.push("El Nombre es un campo obligatorio")
        }
        if(!this.Cliente.Apellido){
          this.mensajeError.push("El Apellido es un campo obligatorio")
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