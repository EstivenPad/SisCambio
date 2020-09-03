import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

export default new VueRouter({
    routes: [
        /************** Inicio *****************/
        { path: '/', component: require('./components/modulos/index').default },

        /************** Cliente *****************/
        { path: '/cliente', component: require('./components/modulos/cliente/index').default },
        { path: '/cliente/crear', component: require('./components/modulos/cliente/crear').default },
        { 
            path: '/cliente/editar/:id', 
            name: 'cliente.editar',
            component: require('./components/modulos/cliente/editar').default,
            props: true 
        },
    ],
    mode: 'history',
    linkActiveClass: 'active'
})