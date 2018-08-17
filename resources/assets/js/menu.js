
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
    icon: 'work'
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
  }
]