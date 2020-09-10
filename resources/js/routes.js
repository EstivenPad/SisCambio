import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

export default new VueRouter({
    routes: [
        /************** Login *****************/
        { 
            path: '/login', 
            name: 'login',
            component: require('./components/modulos/autenticacion/login').default
        },

        /************** Inicio *****************/
        { 
            path: '/',
            name: 'index',
            component: require('./components/modulos/index').default 
        },

        /************** Usuario *****************/
        { 
            path: '/usuario', 
            name: 'usuario.index',
            component: require('./components/modulos/usuario/index').default 
        },
        { 
            path: '/usuario/crear',
            name: 'usuario.crear', 
            component: require('./components/modulos/usuario/crear').default 
        },
        {
            path: '/usuario/editar/:id',
            name: 'usuario.editar',
            component: require('./components/modulos/usuario/editar').default,
            props: true
        },

        /************** Cliente *****************/
        { 
            path: '/cliente', 
            name: 'cliente.index',
            component: require('./components/modulos/cliente/index').default 
        },
        { 
            path: '/cliente/crear', 
            name: 'cliente.crear',
            component: require('./components/modulos/cliente/crear').default 
        },
        {
            path: '/cliente/editar/:id',
            name: 'cliente.editar',
            component: require('./components/modulos/cliente/editar').default,
            props: true
        },

        /************** Moneda *****************/
        { 
            path: '/moneda', 
            name: 'moneda.index',
            component: require('./components/modulos/moneda/index').default 
        },
        { 
            path: '/moneda/crear', 
            name: 'moneda.crear',
            component: require('./components/modulos/moneda/crear').default 
        },
        {
            path: '/moneda/editar/:id',
            name: 'moneda.editar',
            component: require('./components/modulos/moneda/editar').default,
            props: true
        },

        /************** Almacen *****************/
        { 
            path: '/almacen', 
            name: 'almacen.index',
            component: require('./components/modulos/almacen/index').default 
        },
        { 
            path: '/almacen/crear', 
            name: 'almacen.crear',
            component: require('./components/modulos/almacen/crear').default 
        },
        {
            path: '/almacen/editar/:id',
            name: 'almacen.editar',
            component: require('./components/modulos/almacen/editar').default,
            props: true
        },

        /************** Rol *****************/
        { 
            path: '/rol', 
            name: 'rol.index',
            component: require('./components/modulos/rol/index').default 
        },
        { 
            path: '/rol/crear',
            name: 'rol.crear',
            component: require('./components/modulos/rol/crear').default 
        },
        {
            path: '/rol/editar/:id',
            name: 'rol.editar',
            component: require('./components/modulos/rol/editar').default,
            props: true
        },
        
        /************** Permiso *****************/
        { 
            path: '/permiso', 
            name: 'permiso.index',
            component: require('./components/modulos/permiso/index').default 
        },
        { 
            path: '/permiso/crear', 
            name: 'permiso.crear',
            component: require('./components/modulos/permiso/crear').default
        },
        {
            path: '/permiso/editar/:id',
            name: 'permiso.editar',
            component: require('./components/modulos/permiso/editar').default,
            props: true
        },
    ],
    mode: 'history',
    linkActiveClass: 'active'
})
