import Login from './components/auth/Login'
import Profile from './components/auth/Profile'
import Employee from './components/employee/EmployeeIndex'
import Company from './components/company/CompanyIndex'
import Contract from './components/contract/ContractIndex'
import Procedure from './components/procedure/ProcedureIndex'
import ProcedureEdit from './components/procedure/ProcedureEdit'
import UserAction from './components/userAction/UserActionIndex'
import UserIndex from './components/user/UserIndex'
import DepartureIndex from './components/departure/DepartureIndex'

export const routes = [
  {
    name: 'login',
    path: '/login',
    component: Login
  }, {
    name: 'profile',
    path: '/profile',
    component: Profile,
    meta: {
      requiresAuth: true
    }
  }, {
    path: '*',
    redirect: {
      name: 'employeeIndex',
      params: {
        active: true
      },
    },
    meta: {
      requiresAuth: true
    }
  }, {
    path: '/employee',
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
  }, {
    path: '/procedure/:id',
    component: ProcedureEdit,
    name: 'procedureEdit',
    meta: {
      requiresAuth: true
    }
  }, {
    path: '/user',
    component: UserIndex,
    name: 'userIndex',
    meta: {
      requiresAuth: true
    }
  }, {
    path: '/userAction',
    component: UserAction,
    name: 'userActionIndex',
    meta: {
      requiresAuth: true
    }
  }, {
    path: '/departure',
    component: DepartureIndex,
    name: 'departureIndex',
    meta: {
      requiresAuth: true
    }
  }
]