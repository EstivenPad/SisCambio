<template>
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Editar Rol</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->

    <div class="content container-fluid">
      <div class="card">
        <div class="card-header">
          <div class="card-tools">
            <router-link class="btn btn-info btn-sm" :to="'/rol'">
              <i class="fas fa-arrow-left"></i> Regresar
            </router-link>
          </div>
        </div>
        <div class="card-body">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-5">                
                <div class="card card-info">
                  <div class="card-header">
                    <h3 class="card-title">Formulario Editar Roles</h3>
                  </div>
                  <div class="card-body">
                    <form role="form">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group row">
                            <label class=" col-md-3 col-form-label">Nombre</label>
                            <div class="col-md-9">
                              <input type="text" class="form-control" v-model="Rol.Nombre" @keyup.enter="setEditarRol()">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group row">
                            <label class="col-md-3 col-form-label">Url Amigable</label>
                            <div class="col-md-9">
                              <input type="text" class="form-control" v-model="Rol.Slug" @keyup.enter="setEditarRol()">
                            </div>
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="card-footer">
                    <div class="row">
                      <button class="btn btn-default btnWidth" @click.prevent="limpiarCampos">Limpiar</button>
                      <button class="btn btn-info btnWidth" @click.prevent="setEditarRol" v-loading.fullscreen.lock="fullscreenLoading">Registrar</button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-7">                
                <div class="card card-info">
                  <div class="card-header">
                    <h3 class="card-title">Lista de Permisos</h3>
                  </div>
                  <div class="card-body">
                    <div id="accordion">
                      <div class="card" v-for="(modulo, index1) in Modulos" :key="index1">
                        <div class="card-header" id="headingOne"  data-toggle="collapse" :data-target="'#' + modulo.modulo" aria-expanded="true" >
                          <h5 class="mb-0">
                            <button class="btn btn-link" data-toggle="collapse" :data-target="'#' + modulo.modulo" aria-expanded="true" :aria-controls="modulo.modulo" v-text="modulo.modulo">                            
                            </button>
                          </h5>
                        </div>

                        <div :id="modulo.modulo" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                          <div class="card-body">
                            <div v-for="(permiso, index2) in listaCheck" :key="index2">
                              <el-checkbox v-model="permiso.checked" v-if="permiso.modulo == modulo.modulo" >
                                <p v-if="permiso.modulo == modulo.modulo" v-text="permiso.name"></p>
                              </el-checkbox>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
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
      Rol: {
        Id: this.$attrs.id,
        Nombre: '',
        Slug: ''
      },
      listaCheck: [],
      Permisos: [],
      Modulos: [],
      PermisosChecked: [],
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
    this.getPermisosByRol();
    this.getRolPorId();
  },
  methods: {
    abrirModal(){
      this.modalShow = !this.modalShow;
    },
    limpiarCampos(){
      this.Rol.Nombre = '';
      this.Rol.Slug = '';
      // this.listaCheck.forEach((item, index) => {
      //   console.log(item.checked = false;
      // });
    },
    setEditarRol(){
      if(this.validarCampos()){
        this.modalShow = true;
        return;
      }

      this.fullscreenLoading = true;
      this.setGuardarRol();
    },
    setGuardarRol(){
      var url = '/rol/setEditarRolPermiso';

      axios.post(url, {
        'id' : this.Rol.Id,
        'nombre' : this.Rol.Nombre,
        'slug' : this.Rol.Slug,
        'permisos' : this.listaCheck
      }).then(response => {
        this.fullscreenLoading = false;

        Swal.fire({
          icon: 'success',
          title: 'Se actualizó el rol correctamente',
          showConfirmButton: false,
          timer: 1530
        });

        this.$router.push('/rol');
      })
    },
    getRolPorId(){
      var url = '/rol/getRolPermisoEditar'
      axios.get(url, {
        params: {
          'id': this.Rol.Id,
        }
      }).then(response => {
        this.Rol.Nombre = response.data.name;
        this.Rol.Slug = response.data.slug;
        
        this.fullscreenLoading = false;
      })
    },
    getPermisosByRol(){
      var url = '/rol/getPermisosByRol';

      axios.get(url, {
        params:{
          'id' : this.Rol.Id 
        }
      }).then(response => {
        this.Permisos = response.data.permisos;
        this.Modulos = response.data.modulos;
        this.PermisosChecked = response.data.permisosChecked;
        this.setListaPermisoCheck();
      })
    },
    setListaPermisoCheck(){
      this.Permisos.forEach((permiso, index) => {
       
        this.listaCheck.push({
            'id'      :   permiso.id,
            'name'    :   permiso.name,
            'slug'    :   permiso.slug,
            'modulo'  :   permiso.modulo,
            'checked' :   false
          });
        
      });

      this.listaCheck.forEach((permiso, index) => {
        this.PermisosChecked.forEach((item, index) => {
          
          if(permiso.id == item.permission_id){
            permiso.checked = true;
          }

        });
      });
    },
    validarCampos(){
      this.error = 0;
      this.mensajeError = [];

      if(!this.Rol.Nombre){
        this.mensajeError.push("El Nombre es un campo obligatorio")
      }
      if(!this.Rol.Slug){
        this.mensajeError.push("La Url Amigable es obligatorio")
      }

      let contador = 0;

      this.listaCheck.forEach((item, index) => {
        if(item.checked == true){
          contador++;
        }
      })

      if(contador == 0){
        this.mensajeError.push("Se debe seleccionar al menos un permiso");
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
/* Style the buttons that are used to open and close the accordion panel */
.accordion {
  background-color: #eee;
  color: #444;
  cursor: pointer;
  padding: 18px;
  width: 100%;
  text-align: left;
  border: none;
  outline: none;
  transition: 0.4s;
}

/* Add a background color to the button if it is clicked on (add the .active class with JS), and when you move the mouse over it (hover) */
#acordion .active, .accordion:hover {
  background-color: #ccc;
}

/* Style the accordion panel. Note: hidden by default */
#acordion .panel {
  padding: 0 18px;
  background-color: white;
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.2s ease-out;
}

.accordion:after {
  content: '\02795'; /* Unicode character for "plus" sign (+) */
  font-size: 13px;
  color: #777;
  float: right;
  margin-left: 5px;
}

#acordion .active:after {
  content: "\2796"; /* Unicode character for "minus" sign (-) */
}
</style>
