import Login from './components/auth/Login'
import Profile from './components/auth/Profile'
import Employee from './components/employee/EmployeeIndex'
import Company from './components/company/CompanyIndex'
import Contract from './components/contract/ContractIndex'
import Procedure from './components/procedure/ProcedureIndex'
import ProcedureEdit from './components/procedure/ProcedureEdit'
import Positiongroup from './components/positiongroup/PositiongroupIndex'
import Charge from './components/charge/ChargeIndex'
import Position from './components/position/PositionIndex'
export const routes = [{
    name: 'login',
    path: '/login',
    component: Login
    // }, {
    //   name: 'home',
    //   path: '/home',
    //   component: Home,
    //   meta: {
    //     requiresAuth: true
    //   }
//}, {
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
    path: '/positiongroup',
    component: Positiongroup,
    name: 'positiongroupIndex',
    meta: {
        requiresAuth: true
    }
}, {
    path: '/charge',
    component: Charge,
    name: 'chargeIndex',
    meta: {
        requiresAuth: true
    }
}, {
    path: '/position',
    component: Position,
    name: 'positionIndex',
    meta: {
        requiresAuth: true
    }
}
]