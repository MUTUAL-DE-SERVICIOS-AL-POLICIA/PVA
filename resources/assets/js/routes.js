import Home from './components/Home.vue'
import Login from './components/auth/Login.vue'
import Employee from './components/employee/EmployeeIndex.vue'

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
    path: '/employee', 
    component: Employee, 
    name: 'employeeIndex',
    meta: {
      requiresAuth: true
    }
  }
]