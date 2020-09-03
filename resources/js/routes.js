import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

export default new VueRouter({
    routes: [
        /************** Inicio *****************/
        { path: '/', component: require('./components/modulos/index').default },

        /************** Usuario *****************/
        { path: '/usuario', component: require('./components/modulos/usuario/index').default },
        { path: '/usuario/crear', component: require('./components/modulos/usuario/crear').default },
        { 
            path: '/usuario/editar/:id', 
            name: 'usuario.editar', 
            component: require('./components/modulos/usuario/editar').default,
            props: true
        },

        /************** Cliente *****************/
        { path: '/cliente', component: require('./components/modulos/cliente/index').default },
        { path: '/cliente/crear', component: require('./components/modulos/cliente/crear').default },
        { 
            path: '/cliente/editar/:id', 
            name: 'cliente.editar',
            component: require('./components/modulos/cliente/editar').default,
            props: true 
        },

        /************** Moneda *****************/
        { path: '/moneda', component: require('./components/modulos/moneda/index').default },
        { path: '/moneda/crear', component: require('./components/modulos/moneda/crear').default },
        { 
            path: '/moneda/editar/:id', 
            name: 'moneda.editar',
            component: require('./components/modulos/moneda/editar').default,
            props: true 
        },
    ],
    mode: 'history',
    linkActiveClass: 'active'
})