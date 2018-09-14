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
      options: ['new', 'edit', 'ticket', 'bank', 'ovt', 'payroll']
    },
    {
      href: '',
      title: 'ORGANIZADOR',
      icon: '',
      options: []
    },
    {
      href: 'companyIndex',
      title: 'Compañia',
      icon: 'domain',
      options: ['new', 'edit']
    },
    {
      href: 'chargeIndex',
      title: 'Cargos',
      icon: 'group_work',
      options: ['new', 'edit', 'delete']
    },
    {
      href: 'positiongroupIndex',
      title: 'Direcciones/Unidades',
      icon: 'sort',
      options: ['new', 'edit', 'delete']
    },
    {
      href: 'positionIndex',
      title: 'Posiciones',
      icon: 'subject',
      options: ['new', 'edit', 'delete']
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
    },
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
      href: 'contractIndex',
      params: {},
      title: 'Contratos',
      icon: 'work',
      options: []
    }
  ]
}