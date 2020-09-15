<template>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Clientes</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid-->

      <div class="content container-fluid">
        <div class="card">
          <div class="card-header">
            <!-- <div class="card-tools"> -->
              <div class="row">
                <div class="col-md-2" style="padding: 0%">
                  <template>
                    <el-select v-model="filtro" placeholder="Filtro" clearable>
                      <el-option
                        v-for="item in opciones"
                        :key="item.value"
                        :label="item.label"
                        :value="item.value">
                      </el-option>
                    </el-select>
                  </template>
                </div>                  
                <el-input 
                  class="col-md-3"
                  placeholder="Criterio"
                  v-model="criterio"
                  @keyup.enter="getListaCliente(filtro, criterio, 1)"
                  clearable>
                </el-input>
                <div class="col-md-2">
                  <button class="btn btn-info" @click.prevent="getListaCliente(filtro, criterio, filtro ? 1 : 0)" v-loading.fullscreen.lock="fullscreenLoading">Buscar</button>
                </div>               
              </div>
          </div>
          <div class="card-body table-responsive">
            <div class="container-fluid">
              <template v-if="listarClientesPaginados.length">
                <table class="table table-hover table-head-fixed text-nowrap projects table-striped">
                  <thead> 
                    <tr>
                      <th>Fotografía</th>
                      <th>Nombre</th>
                      <th>Teléfono</th>
                      <th>Celular</th>
                      <th>Dirección</th>
                      <th>Estado</th>
                      <th>Acción</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in listarClientesPaginados" :key="index">
                      <td>
                        <template v-if="!item.imagen">
                          <li class="user-block">
                            <img src="/img/user.png" :alt="'Imagen'" class="profile-avatar-img img-fluid img-circle">
                          </li>
                        </template>
                        <template v-else>
                          <li class="user-block">
                            <img :src="'storage/' + item.imagen" :alt="'Imagen'" class="profile-avatar-img img-fluid img-circle">
                          </li>
                        </template>
                      </td>
                      <td v-text="item.nombre + ' ' + item.apellido"></td>
                      <td v-text="item.telefono"></td>
                      <td v-text="item.celular"></td>
                      <td v-text="item.direccion"></td>
                      <td>
                        <template v-if="item.estado == 1">
                          <span class="badge badge-success" v-text="'ACTIVO'"></span>
                        </template>
                        <template v-else>
                          <span class="badge badge-danger" v-text="'INACTIVO'"></span>
                        </template>
                      </td>
                      <td>
                        <template v-if="item.estado == 1">
                          <template v-if="listaPermisosByRol.includes('cliente.editar')">
                            <router-link class="btn btn-flat btn-info btn-sm" :to="{name:'cliente.editar', params: { id: item.id }}">
                              <i class="fas fa-pencil-alt"></i> Editar
                            </router-link>
                          </template>

                          <template v-if="listaPermisosByRol.includes('cliente.desactivar')">
                            <button class="btn btn-flat btn-danger btn-sm" @click.prevent="setCambiarEstadoCliente(1, item.id, filtro, criterio)">
                              <i class="fas fa-trash"></i> Desactivar
                            </button>
                          </template>
                        </template> 
                        <template v-else>
                          <template v-if="listaPermisosByRol.includes('cliente.activar')">
                            <button class="btn btn-flat btn-success btn-sm" @click.prevent="setCambiarEstadoCliente(2, item.id, filtro, criterio)">
                              <i class="fas fa-check"></i> Activar
                            </button>
                          </template>
                        </template>                           
                        
                      </td>
                    </tr>
                  </tbody>
                </table>
                <div class="clearfix d-flex justify-content-start">
                  <ul class="pagination pagination-sm m-0 float-right">
                    <li class="page-item" v-if="pageNumber > 0">
                      <a href="#" class="page-link" @click.prevent="paginaAnterior">Anterior</a>
                    </li>
                    <template v-if="listaClientes.length > perPage">
                      <li class="page-item" v-for="(page, index) in pagesList" :key="index" :class="[page == pageNumber ? 'active' : '']">
                      <a href="#" class="page-link" @click.prevent="paginaSeleccionada(page)"> {{ page+1 }} </a>
                    </li>
                    </template>                    
                    <li class="page-item" v-if="pageNumber < pageCount - 1">
                      <a href="#" class="page-link" @click.prevent="paginaSiguiente">Siguiente</a>
                    </li>
                  </ul>
                </div>
              </template>
            </div>
          </div>
          
          <template v-if="listaPermisosByRol.includes('cliente.crear')">
            <div id="container-floating" v-tooltip.left="'Crear nuevo cliente'">
              <router-link :to="{ name: 'cliente.crear' }">
                <div id="floating-button">
                  <p class="plus">+</p>
                  <div class="plusH">
                    <i class="fas fa-user-plus"></i>
                  </div>
                </div>
              </router-link>
            </div>
          </template>
        </div>
      </div>
    </div>
</template>

<script>
  export default {
    data(){
      return {
        listaClientes: [],
        listaPermisosByRol: JSON.parse(sessionStorage.getItem('listaPermisosByRol')),
        opciones: [{
          value: 'nombre',
          label: 'Nombre'
        }, {
          value: 'apellido',
          label: 'Apellido'
        }, {
          value: 'A',
          label: 'Activo'
        }, {
          value: 'I',
          label: 'Inactivo'
        },],
        filtro: '',
        criterio: '',
        pageNumber: 0, //Numero de la pagina actual, que siempre se inicializa en 0
        perPage: 15, //Cantidad de registros por pagina
        fullscreenLoading: false
      }
    },
    mounted(){
      this.getListaCliente('', '', 0);
    },
    computed: {
      //Obtiene el numero de paginas
      pageCount(){
        let a = this.listaClientes.length,
            b = this.perPage;
        return Math.ceil(a / b);
      },
      //Obtiene registros paginados
      listarClientesPaginados(){
        let inicio = this.pageNumber * this.perPage,
            fin = inicio + this.perPage;
            
        return this.listaClientes.slice(inicio, fin);
      },
      //Obtiene un arreglo de numeros con la numeracion de cada pagina
      pagesList(){
        let a = this.listaClientes.length,
            b = this.perPage;
        let pageCount = Math.ceil(a / b);
        let count = 0,
            pagesArray = [];

        // 0 < 4 => 0
        // 1 < 4 => 0, 1
        // 2 < 4 => 0, 1, 2
        // 3 < 4 => 0, 1, 2, 3  
        while (count < pageCount) {
          pagesArray.push(count);
          count++;
        }

        return pagesArray;
      }
    },
    methods: {
      getListaCliente(filtro, criterio, filtroaplicado){
        this.fullscreenLoading = true;
        this.listaClientes = [];
        var url = '/cliente/getListaClientes?filtro=' + filtro + '&criterio=' + criterio + '&filtroaplicado=' + filtroaplicado;
        axios.get(url).then(response => {
          this.inicializarPaginacion();
          console.log(response);
          this.listaClientes = response.data;
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
      paginaSiguiente(){
        this.pageNumber++;
      },
      paginaAnterior(){
        this.pageNumber--;
      },
      paginaSeleccionada(pages){
        this.pageNumber = pages;
      },
      inicializarPaginacion(){
        this.pageNumber = 0;
      },
      setCambiarEstadoCliente(opc, id, filtro, criterio){
        Swal.fire({
          title: '¿Está seguro de ' + ((opc == 1) ? 'desactivar' : 'activar') + ' el cliente?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: ((opc == 1) ? 'Sí, desactivar' : 'Sí, activar')
        }).then((result) => {
          if (result.value) {
            //Aqui ira la confirmacion del boton, es decir, la peticion al servidor
            this.fullscreenLoading = true;
            
            var url = '/cliente/setCambiarEstadoCliente'
            axios.put(url, {
              'id': id
            }).then(response => {
              Swal.fire({
                icon: 'success',
                title: 'Se ' + ((opc == 1) ? 'desactivó' : 'activó') + ' el cliente',
                showConfirmButton: false,
                timer: 1530
              })

              this.getListaCliente(filtro, criterio, 0);
            }).catch(error => {
              if(error.response.status == 401){
                this.$router.push({name: 'login'});
                location.reload();
                sessionStorage.clear();
                this.fullscreenLoading = false;
              }
            })
          }
        })
      }
    }
  }
</script>

<style>
  
</style>