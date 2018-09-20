require('./bootstrap')

import Vue from 'vue'
import VueRouter from 'vue-router'
import Vuex from 'vuex'
import VeeValidate, { Validator } from 'vee-validate'
import { routes } from './routes'
import StoreData from './store'
import AppMain from './components/AppMain'
import es from 'vee-validate/dist/locale/es'

import toastr from 'toastr'
import 'toastr/toastr.min.css'
Vue.prototype.toastr = toastr

import print from 'print-js'
import 'vuetify/dist/vuetify.min.css'
import 'material-design-icons-iconfont/dist/material-design-icons.css'
import ess from './es.js'
import Vuetify from 'vuetify'

Vue.use(Vuetify, {
  lang: {
    locales: { ess },
    current: 'ess'
  },
  theme: {
    primary: "#005D53",
    secondary: "#009686",
    tertiary: '#42B2A6',
    accent: "#1043A0",
    error: "#F46F0D",
    danger: '#FA9347',
    warning: "#FABB47",
    info: "#0B347F",
    success: "#006157",
    normal: '#F5F5F5'
  }
})

import { createSimpleTransition } from 'vuetify/es5/util/helpers'
const myTransition = createSimpleTransition('my-transition')
Vue.component('my-transition', myTransition)

Vue.config.productionTip = false
Vue.use(VueRouter)
Vue.use(Vuex)
const moment = require('moment')
require('moment/locale/es')
Vue.use(require('vue-moment'), {
  moment
});

Validator.localize({
  es: es
})
Vue.use(VeeValidate, {
  locale: 'es',
})

const store = new Vuex.Store(StoreData)

const router = new VueRouter({
  routes,
  // hashbang: false,
  mode: 'history',
})

axios.defaults.headers.common['Accept'] = 'application/json'
axios.defaults.headers.common['Content-Type'] = 'application/json'
if (localStorage.getItem('token_type') && localStorage.getItem('token')) {
  axios.defaults.headers.common['Authorization'] = `${localStorage.getItem('token_type')} ${localStorage.getItem('token')}`
}

axios.interceptors.response.use(response => {
  return response;
}, error => {
  if (error.response) {
    if (error.response.status == 401) {
      store.dispatch('logout')
      router.push('login')
    }
  }
  return Promise.reject(error)
});

router.beforeEach((to, from, next) => {
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth)
  const currentUser = store.state.currentUser

  if (requiresAuth && !currentUser) {
    next({
      path: '/login'
    })
  } else if (to.path == '/login' && currentUser) {
    next({
      path: '/'
    })
  } else {
    next()
  }
})

new Vue({
  el: '#app',
  router,
  store,
  components: {
    AppMain
  },
  locale: 'es',
})