<template>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Moneda</h1>
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
                  placeholder="Moneda"
                  v-model="criterio"
                  @keyup.enter="getListaMoneda(criterio)"
                  clearable>
                </el-input>
                <div class="col-md-2">
                  <button class="btn btn-info" @click.prevent="getListaMoneda(criterio)" v-loading.fullscreen.lock="fullscreenLoading">Buscar</button>
                </div>               
              </div>
          </div>
          <div class="card-body table-responsive">
            <div class="container-fluid">
              <template v-if="listarMonedasPaginadas.length">
                <table class="table table-hover table-head-fixed text-nowrap projects table-striped">
                  <thead> 
                    <tr>
                      <th>Nombre</th>
                      <th>Precio Compra</th>
                      <th>Precio Venta</th>
                      <th>Estado</th>
                      <th>Acción</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in listarMonedasPaginadas" :key="index">
                      <td v-text="item.nombre"></td>
                      <td v-text="item.precioCompra"></td>
                      <td v-text="item.precioVenta"></td>
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
                          <router-link class="btn btn-flat btn-info btn-sm" :to="{name:'cliente.editar', params: { id: item.id }}">
                            <i class="fas fa-pencil-alt"></i> Editar
                          </router-link>
                          <button class="btn btn-flat btn-danger btn-sm" @click.prevent="setCambiarEstadoMoneda(1, item.id, criterio)">
                            <i class="fas fa-trash"></i> Desactivar
                          </button>
                        </template> 
                        <template v-else>
                          <button class="btn btn-flat btn-success btn-sm" @click.prevent="setCambiarEstadoMoneda(2, item.id, criterio)">
                            <i class="fas fa-check"></i> Activar
                          </button>
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
                    <template v-if="listaMonedas.length > perPage">
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
          
        <div id="container-floating" v-tooltip.left="'Crear nueva moneda'">
            <router-link :to="'/moneda/crear'">
                <div id="floating-button">
                    <p class="plus">+</p>
                    <div class="plusH">
                        <i class="fas fa-user-plus"></i>
                    </div>
                </div>
            </router-link>
        </div>
        </div>
      </div>
    </div>
</template>

<script>
  export default {
    data(){
      return {
        listaMonedas: [],
        opciones: [{
          value: 'nombre',
          label: 'Nombre'
        }, {
          value: 'A',
          label: 'Activos'
        }, {
          value: 'I',
          label: 'Inactivos'
        },],
        criterio: '',
        pageNumber: 0, //Numero de la pagina actual, que siempre se inicializa en 0
        perPage: 15, //Cantidad de registros por pagina
        fullscreenLoading: false
      }
    },
    mounted(){
      this.getListaMoneda('');
    },
    computed: {
      //Obtiene el numero de paginas
      pageCount(){
        let a = this.listaMonedas.length,
            b = this.perPage;
        return Math.ceil(a / b);
      },
      //Obtiene registros paginados
      listarMonedasPaginadas(){
        let inicio = this.pageNumber * this.perPage,
            fin = inicio + this.perPage;
            
        return this.listaMonedas.slice(inicio, fin);
      },
      //Obtiene un arreglo de numeros con la numeracion de cada pagina
      pagesList(){
        let a = this.listaMonedas.length,
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
      getListaMoneda(criterio){
        this.fullscreenLoading = true;
        this.listaMonedas = [];
        var url = '/moneda/getListaMonedas?criterio=' + criterio;
        axios.get(url).then(response => {
          this.inicializarPaginacion();
          console.log(response);
          this.listaMonedas = response.data;
          this.fullscreenLoading = false;
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
      setCambiarEstadoMoneda(opc, id, criterio){
        Swal.fire({
          title: '¿Está seguro de ' + ((opc == 1) ? 'desactivar' : 'activar') + ' la moneda?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: ((opc == 1) ? 'Sí, desactivar' : 'Sí, activar')
        }).then((result) => {
          if (result.value) {
            //Aqui ira la confirmacion del boton, es decir, la peticion al servidor
            this.fullscreenLoading = true;
            
            var url = '/moneda/setCambiarEstadoMoneda'
            axios.put(url, {
              'id': id
            }).then(response => {
              Swal.fire({
                icon: 'success',
                title: 'Se ' + ((opc == 1) ? 'desactivó' : 'activó') + ' la moneda',
                showConfirmButton: false,
                timer: 1530
              })

              this.getListaMoneda(criterio);
            })
          }
        })
      }
    }
  }
</script>

<style>
  
</style>