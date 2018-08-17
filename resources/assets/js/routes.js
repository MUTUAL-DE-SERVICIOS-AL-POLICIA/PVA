import Home from './components/Home.vue'
import Login from './components/auth/Login.vue'
import Employee from './components/employee/EmployeeIndex.vue'
import Company from './components/company/CompanyIndex.vue'
import Contract from './components/contract/ContractIndex.vue'
export const routes = [{
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
}, {
  path: '/employee/:active',
  component: Employee,
  name: 'employeeIndex',
  meta: {
    requiresAuth: true
  }
}, {
  path: '/company',
  component: Company,
  name: 'companyIndex',
  meta: {
    requiresAuth: true
  }
}, {
  path: '/contract',
  component: Contract,
  name: 'contractIndex',
  meta: {
    requiresAuth: true
  }
}]