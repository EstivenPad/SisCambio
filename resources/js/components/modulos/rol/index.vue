<template>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Rol</h1>
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
                  @keyup.enter="getListaRoles(filtro, criterio)"
                  clearable>
                </el-input>
                <div class="col-md-2">
                  <button class="btn btn-info" @click.prevent="getListaRoles(filtro, criterio)" v-loading.fullscreen.lock="fullscreenLoading">Buscar</button>
                </div>
              </div>
          </div>
          <div class="card-body table-responsive">
            <div class="container-fluid">
              <template v-if="listarRolesPaginados.length">
                <table class="table table-hover table-head-fixed text-nowrap projects table-striped">
                  <thead>
                    <tr>
                      <th>Nombre</th>
                      <th>Url Amigable</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in listarRolesPaginados" :key="index">

                      <td v-text="item.name"></td>
                      <td v-text="item.slug"></td>

                      <td>
                        <template>
                          <router-link class="btn btn-flat btn-info btn-sm" :to="{name:'rol.editar', params: { id: item.id }}">
                            <i class="fas fa-pencil-alt"></i> Editar
                          </router-link>
                        </template>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <div class="clearfix d-flex justify-content-start">
                  <ul class="pagination pagination-sm m-0 float-right">
                    <li class="page-item" v-if="pageNumber > 0">
                      <a href="#" class="page-link" @click.prevent="prevPage">Anterior</a>
                    </li>
                    <li class="page-item" v-for="(page, index) in pagesList" :key="index" :class="[page == pageNumber ? 'active' : '']">
                      <a href="#" class="page-link" @click.prevent="selectPage(page)"> {{ page+1 }} </a>
                    </li>
                    <li class="page-item" v-if="pageNumber < pageCount - 1">
                      <a href="#" class="page-link" @click.prevent="nextPage">Siguiente</a>
                    </li>
                  </ul>
                </div>
              </template>
            </div>
          </div>

        <div id="container-floating" v-tooltip.left="'Crear nuevo rol'">
            <router-link :to="'/rol/crear'">
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
        listaRol: [],
        filtro: '',
        criterio: '',
        pageNumber: 0,
        perPage: 15,
        fullscreenLoading: false,
        opciones: [{
          value: 'name',
          label: 'Nombre'
        }, {
          value: 'slug',
          label: 'Url'
        }],
      }
    },
    mounted(){
      this.getListaRoles('', '');
    },
    computed: {
      //Obtener el numero de pagina
      pageCount(){
        let a = this.listaRol.length,
            b = this.perPage;
        return Math.ceil(a / b);
      },
      //Obtener registros paginados
      listarRolesPaginados(){
        let inicio = this.pageNumber * this.perPage,
            fin = inicio + this.perPage;
        return this.listaRol.slice(inicio, fin);
      },
      pagesList(){
        let a = this.listaRol.length,
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
      getListaRoles(filtro, criterio){
        this.fullscreenLoading = true;

        var url = '/rol/getListaRoles?filtro=' + filtro +'&criterio=' + criterio ;
        axios.get(url).then(response => {
          this.inicializarPaginacion();
          console.log(response);
          this.listaRol = response.data;
          this.fullscreenLoading = false;
        })
      },
      nextPage(){
        this.pageNumber++;
      },
      prevPage(){
        this.pageNumber--;
      },
      selectPage(pages){
        this.pageNumber = pages;
      },
      inicializarPaginacion(){
        this.pageNumber = 0;
      },
    }
  }
</script>

<style>

</style>
