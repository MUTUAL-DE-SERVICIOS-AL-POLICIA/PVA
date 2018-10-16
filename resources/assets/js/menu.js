export default {
  admin: [
    {
      href: 'employeeIndex',
      title: 'Empleados',
      icon: 'assignment_ind',
      options: ['new', 'edit', 'delete', 'print']
    }, {
      href: 'contractIndex',
      params: {},
      title: 'Contratos',
      icon: 'work',
      options: ['new', 'edit', 'delete', 'renew', 'printInsurance', 'printContract']
    }, {
      href: 'procedureIndex',
      params: {
        active: true
      },
      title: 'Planillas',
      icon: 'attach_file',
      options: ['new', 'edit', 'ticket', 'bank', 'ovt', 'afp', 'payroll']
    }, {
      href: 'userIndex',
      title: 'Usuarios',
      icon: 'person',
      options: ['edit']
    }, {
      href: 'userActionIndex',
      title: 'Actividad',
      icon: 'timeline',
      options: ['edit']
    }
  ],
  rrhh: [
    {
      href: 'employeeIndex',
      title: 'Empleados',
      icon: 'assignment_ind',
      options: ['new', 'edit', 'delete', 'print']
    }, {
      href: 'contractIndex',
      params: {},
      title: 'Contratos',
      icon: 'work',
      options: ['new', 'edit', 'delete', 'renew', 'printInsurance']
    }, {
      href: 'procedureIndex',
      params: {
        active: true
      },
      title: 'Planillas',
      icon: 'attach_file',
      options: ['new', 'edit', 'ticket', 'bank', 'ovt', 'payroll']
    }
  ],
  juridica: [
    {
      href: 'employeeIndex',
      title: 'Empleados',
      icon: 'assignment_ind',
      options: []
    }, {
      href: 'contractIndex',
      params: {},
      title: 'Contratos',
      icon: 'work',
      options: ['printContract', 'edit']
    }
  ],
  financiera: [
    {
      href: 'employeeIndex',
      title: 'Empleados',
      icon: 'assignment_ind',
      options: []
    }, {
      href: 'contractIndex',
      params: {},
      title: 'Contratos',
      icon: 'work',
      options: []
    }, {
      href: 'procedureIndex',
      params: {
        active: true
      },
      title: 'Planillas',
      icon: 'attach_file',
      options: ['afp', 'payroll']
    }
  ]
}