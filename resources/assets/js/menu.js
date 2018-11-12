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
<<<<<<< HEAD
    }, {
      href: 'departureIndex',
      title: 'Salidas/Licencias',
      icon: 'directions_run',
      options: ['new', 'edit', 'delete', 'active', 'print']
    }, {
      href: 'departureAdmin',
      title: 'Administrador de Salidas y Licencias',
      icon: 'directions_walk',
      options: ['new', 'edit', 'delete', 'active', 'print']
=======
>>>>>>> c88936e2ca04e9b12009a67413647d6a729e6248
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
  ],
  employee: [
    {
      href: 'departureIndex',
      title: 'Solicitud de salidas',
      icon: 'directions_run',
      options: ['new', 'edit', 'print']
    }
  ],
}