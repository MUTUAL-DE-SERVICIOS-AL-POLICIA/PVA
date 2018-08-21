import Home from './components/Home'
import Login from './components/auth/Login'
import Employee from './components/employee/EmployeeIndex'
import Company from './components/company/CompanyIndex'
import Contract from './components/contract/ContractIndex'
import Procedure from './components/procedure/ProcedureIndex'

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
  }, {
    path: '/procedure',
    component: Procedure,
    name: 'procedureIndex',
    meta: {
      requiresAuth: true
    }
  }
]