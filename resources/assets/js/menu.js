
export const guest = [
  {
    header: 'Admin' 
  }, {
    href: 'home',
    params: {},
    title: 'Inicio',
    icon: 'fa fa-home'
  },
]

export const admin = [
  {
    href: 'home',
    params: {},
    title: 'Inicio',
    icon: 'dashboard'
  }, {
    href: 'employeeIndex',
    params: {
      active: true
    },
    title: 'Empleados',
    icon: 'assignment_ind'
  }, {
    href: 'companyIndex',
    params: {},
    title: 'Compañia',
    icon: 'account_balance'
  }, {
    href: 'contractIndex',
    params: {},
    title: 'Contratos',
    icon: 'work'
  }, {
    href: 'procedureIndex',
    params: {
      active: true
    },
    title: 'Planillas',
    icon: 'attach_file'
  },
]