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
                  @keyup.enter="getListaUsuarios(filtro, criterio)"
                  clearable>
                </el-input>
                <div class="col-md-2">
                  <button class="btn btn-info" @click.prevent="getListaUsuarios(filtro, criterio)" v-loading.fullscreen.lock="fullscreenLoading">Buscar</button>
                </div>
              </div>
          </div>
          <div class="card-body table-responsive">
            <div class="container-fluid">
              <template v-if="listarUsuariosPaginated.length">
                <table class="table table-hover table-head-fixed text-nowrap projects table-striped">
                  <thead>
                    <tr>
                      <th>Fotografia</th>
                      <th>Nombre</th>
                      <th>Usuario</th>
                      <th>Estado</th>
                      <th>Acción</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in listarUsuariosPaginated" :key="index">
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
                      <td v-text="item.usuario"></td>
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
                          <router-link class="btn btn-flat btn-info btn-sm" :to="{name:'usuario.editar', params: { id: item.id }}">
                            <i class="fas fa-pencil-alt"></i> Editar
                          </router-link>
                          <router-link class="btn btn-flat btn-secondary btn-sm" :to="'/'">
                            <i class="fas fa-key"></i> Permiso
                          </router-link>
                          <button class="btn btn-flat btn-danger btn-sm" @click.prevent="setCambiarEstadoUsuario(1, item.id, filtro, criterio)">
                            <i class="fas fa-trash"></i> Desactivar
                          </button>
                        </template>
                        <template v-else>
                          <button class="btn btn-flat btn-success btn-sm" @click.prevent="setCambiarEstadoUsuario(2, item.id, filtro, criterio)">
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
                      <a href="#" class="page-link" @click.prevent="prevPage">Ant</a>
                    </li>
                    <li class="page-item" v-for="(page, index) in pagesList" :key="index" :class="[page == pageNumber ? 'active' : '']">
                      <a href="#" class="page-link" @click.prevent="selectPage(page)"> {{ page+1 }} </a>
                    </li>
                    <li class="page-item" v-if="pageNumber < pageCount - 1">
                      <a href="#" class="page-link" @click.prevent="nextPage">Post</a>
                    </li>
                  </ul>
                </div>
              </template>
            </div>
          </div>

        <div id="container-floating" v-tooltip.left="'Crear nuevo usuario'">
            <router-link :to="'/usuario/crear'">
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
        listUsuario: [],
        opciones: [{
          value: 'nombre',
          label: 'Nombre'
        }, {
          value: 'apellido',
          label: 'Apellido'
        }, {
          value: 'usuario',
          label: 'Usuario'
        }, {
          value: 'A',
          label: 'Activo'
        }, {
          value: 'I',
          label: 'Inactivo'
        },],
        filtro: '',
        criterio: '',
        pageNumber: 0,
        perPage: 15,
        fullscreenLoading: false
      }
    },
    mounted(){
      this.getListaUsuarios('', '', 0);
    },
    computed: {
      //Obtener el numero de pagina
      pageCount(){
        let a = this.listUsuario.length,
            b = this.perPage;
        return Math.ceil(a / b);
      },
      //Obtener registros paginados
      listarUsuariosPaginated(){
        let inicio = this.pageNumber * this.perPage,
            fin = inicio + this.perPage;
        return this.listUsuario.slice(inicio, fin);
      },
      pagesList(){
        let a = this.listUsuario.length,
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
      limpiarBandejaUsuarios(){
        this.listUsuario = [];
      },
      getListaUsuarios(filtro, criterio, filtroaplicado){
        this.fullscreenLoading = true;

        var url = '/usuario/getListaUsuarios?filtro=' + filtro +'&criterio=' + criterio + '&filtroaplicado=' + filtroaplicado;
        axios.get(url).then(response => {
          this.inicializarPaginacion();
          console.log(response);
          this.listUsuario = response.data;
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
      setCambiarEstadoUsuario(opc, id, filtro, criterio){
        Swal.fire({
          title: '¿Está seguro de ' + ((opc == 1) ? 'desactivar' : 'activar') + ' el usuario?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: ((opc == 1) ? 'Sí, desactivar' : 'Sí, activar')
        }).then((result) => {
          if (result.value) {
            //Aqui ira la confirmacion del boton, es decir, la peticion al servidor
            this.fullscreenLoading = true;

            var url = '/usuario/setCambiarEstadoUsuario'
            axios.put(url, {
              'id': id
            }).then(response => {
              Swal.fire({
                icon: 'success',
                title: 'Se ' + ((opc == 1) ? 'desactivó' : 'activó') + ' el usuario',
                showConfirmButton: false,
                timer: 1500
              })

              this.getListaUsuarios(filtro, criterio, 0);
            })
          }
        })
      }
    }
  }
</script>

<style>

</style>
