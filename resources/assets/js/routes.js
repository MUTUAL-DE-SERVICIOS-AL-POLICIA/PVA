import Home from './components/Home.vue'
import Login from './components/auth/Login.vue'
import About from './components/about/AboutIndex.vue'

export const routes = [
  {
    name: 'login',
    path: '/login',
    component: Login
  }, {
    name: 'home',
    path: '/home',
    component: Home,
    meta: {
      requiresAuth: true
    }
  }, {
    path: '*',
    redirect: {
      name: 'home'
    }
  },{
    path: '/about', 
    component: About, 
    name: 'aboutIndex',
    meta: {
      requiresAuth: true
    }
  }
]