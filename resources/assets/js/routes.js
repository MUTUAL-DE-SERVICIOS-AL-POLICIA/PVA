import Login from './components/auth/Login'
import Profile from './components/auth/Profile'
import Employee from './components/employee/EmployeeIndex'
import Company from './components/company/CompanyIndex'
import Contract from './components/contract/ContractIndex'
import Consultant from './components/contract/ConsultantIndex'
import Procedure from './components/procedure/ProcedureIndex'
import ConsultantProcedure from './components/procedure/ConsultantProcedureIndex'
import ProcedureEdit from './components/procedure/ProcedureEdit'
import ConsultantProcedureEdit from './components/procedure/ConsultantProcedureEdit'
import UserAction from './components/userAction/UserActionIndex'
import UserIndex from './components/user/UserIndex'
import Dashboard from './components/dashboard/Dashboard'
import PhonebookIndex from './components/phonebook/PhonebookIndex'
import SuppliesIndex from './components/supply/SuppliesIndex'
import SupplyRequestIndex from './components/supply/SupplyRequestIndex'
import DepartureConfig from './components/configuration/DepartureConfig'
import AttendanceConfig from './components/configuration/AttendanceConfig'
import CompanyConfig from './components/configuration/CompanyConfig'
import DepartureIndex from './components/departure/DepartureIndex'
import AttendanceIndex from './components/attendance/AttendanceIndex'
import AttendanceDeviceConfig from './components/configuration/AttendanceDeviceConfig'

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
      name: 'dashboard'
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
    path: '/consultant',
    component: Consultant,
    name: 'consultantIndex',
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
    path: '/consultant_procedure',
    component: ConsultantProcedure,
    name: 'consultantProcedureIndex',
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
    path: '/consultantProcedure/:id',
    component: ConsultantProcedureEdit,
    name: 'consultantProcedureEdit',
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
  }, {
    path: '/dashboard',
    component: Dashboard,
    name: 'dashboard',
    meta: {
      requiresAuth: true
    }
  }, {
    path: '/phonebook',
    component: PhonebookIndex,
    name: 'phonebookIndex',
    meta: {
      requiresAuth: true
    }
  }, {
    path: '/supplies',
    component: SuppliesIndex,
    name: 'suppliesIndex',
    meta: {
      requiresAuth: true
    }
  }, {
    path: '/supply_requests',
    component: SupplyRequestIndex,
    name: 'supplyRequestIndex',
    meta: {
      requiresAuth: true
    }
  }, {
    path: '/departure_config',
    component: DepartureConfig,
    name: 'departureConfig',
    meta: {
      requiresAuth: true
    }
  }, {
    path: '/attendance_config',
    component: AttendanceConfig,
    name: 'attendanceConfig',
    meta: {
      requiresAuth: true
    }
  }, {
    path: '/attendance',
    component: AttendanceIndex,
    name: 'attendanceIndex',
    meta: {
      requiresAuth: true
    }
  }, {
    path: '/company_config',
    component: CompanyConfig,
    name: 'companyConfig',
    meta: {
      requiresAuth: true
    }
  }, {
    path: '/attendace_device',
    component: AttendanceDeviceConfig,
    name: 'attendanceDeviceConfig',
    meta: {
      requiresAuth: true
    }
  }
]