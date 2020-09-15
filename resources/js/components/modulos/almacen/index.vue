<template>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Almacen</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid-->

      <div class="content container-fluid">
        <div class="card">
          <div class="card-header">
            <!-- <div class="card-tools"> -->
              <div class="row">                 
                <el-input 
                  class="col-md-3"
                  placeholder="Almacen"
                  v-model="criterio"
                  @keyup.enter="getListaAlmacen(criterio)"
                  clearable>
                </el-input>
                <div class="col-md-2">
                  <button class="btn btn-info" @click.prevent="getListaAlmacen(criterio)" v-loading.fullscreen.lock="fullscreenLoading">Buscar</button>
                </div>               
              </div>
          </div>
          <div class="card-body table-responsive">
            <div class="container-fluid">
              <template v-if="listarAlmacenesPaginados.length">
                <table class="table table-hover table-head-fixed text-nowrap projects table-striped">
                  <thead> 
                    <tr>
                      <th>Nombre</th>
                      <th>Estado</th>
                      <th>Acción</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in listarAlmacenesPaginados" :key="index">
                      <td v-text="item.nombre"></td>
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
                          <template v-if="listaPermisosByRol.includes('almacen.editar')">
                            <router-link class="btn btn-flat btn-info btn-sm" :to="{name:'almacen.editar', params: { id: item.id }}">
                              <i class="fas fa-pencil-alt"></i> Editar
                            </router-link>
                          </template>

                          <template v-if="listaPermisosByRol.includes('almacen.desactivar')">
                            <button class="btn btn-flat btn-danger btn-sm" @click.prevent="setCambiarEstadoAlmacen(1, item.id, criterio)">
                              <i class="fas fa-trash"></i> Desactivar
                            </button>
                          </template>
                        </template> 
                        <template v-else>
                          <template v-if="listaPermisosByRol.includes('almacen.activar')">
                            <button class="btn btn-flat btn-success btn-sm" @click.prevent="setCambiarEstadoAlmacen(2, item.id, criterio)">
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
                    <template v-if="listaAlmacenes.length > perPage">
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
          
          <template v-if="listaPermisosByRol.includes('almacen.crear')">
            <div id="container-floating" v-tooltip.left="'Crear nuevo almacen'">
              <router-link :to="{ name: 'almacen.crear' }">
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
        listaAlmacenes: [],
        listaPermisosByRol: JSON.parse(sessionStorage.getItem('listaPermisosByRol')),
        criterio: '',
        pageNumber: 0, //Numero de la pagina actual, que siempre se inicializa en 0
        perPage: 15, //Cantidad de registros por pagina
        fullscreenLoading: false
      }
    },
    mounted(){
      this.getListaAlmacen('');
    },
    computed: {
      //Obtiene el numero de paginas
      pageCount(){
        let a = this.listaAlmacenes.length,
            b = this.perPage;
        return Math.ceil(a / b);
      },
      //Obtiene registros paginados
      listarAlmacenesPaginados(){
        let inicio = this.pageNumber * this.perPage,
            fin = inicio + this.perPage;
            
        return this.listaAlmacenes.slice(inicio, fin);
      },
      //Obtiene un arreglo de numeros con la numeracion de cada pagina
      pagesList(){
        let a = this.listaAlmacenes.length,
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
      getListaAlmacen(criterio){
        this.fullscreenLoading = true;
        this.listaAlmacenes = [];
        
        var url = '/almacen/getListaAlmacenes?criterio=' + criterio;

        axios.get(url).then(response => {
          this.inicializarPaginacion();
          console.log(response);
          this.listaAlmacenes = response.data;
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
      setCambiarEstadoAlmacen(opc, id, criterio){
        Swal.fire({
          title: '¿Está seguro de ' + ((opc == 1) ? 'desactivar' : 'activar') + ' el almacen?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: ((opc == 1) ? 'Sí, desactivar' : 'Sí, activar')
        }).then((result) => {
          if (result.value) {
            //Aqui ira la confirmacion del boton, es decir, la peticion al servidor
            this.fullscreenLoading = true;
            
            var url = '/almacen/setCambiarEstadoAlmacen'
            axios.put(url, {
              'id': id
            }).then(response => {
              Swal.fire({
                icon: 'success',
                title: 'Se ' + ((opc == 1) ? 'desactivó' : 'activó') + ' el almacen',
                showConfirmButton: false,
                timer: 1530
              })

              this.getListaAlmacen(criterio);
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