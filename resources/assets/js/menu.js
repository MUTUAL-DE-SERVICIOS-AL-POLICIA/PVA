export default {
  admin: [
    {
      href: 'employeeIndex',
      title: 'Empleados',
      icon: 'assignment_ind',
      params: {
        options: ['new', 'edit', 'delete', 'print']
      }
    }, {
      title: 'Eventuales',
      icon: 'settings_backup_restore',
      color: 'blue',
      group: [
        {
          href: 'contractIndex',
          title: 'Contratos Eventuales',
          icon: 'work',
          params: {
            options: ['new', 'edit', 'delete', 'renew', 'printInsurance', 'printContract']
          }
        }, {
          href: 'procedureIndex',
          title: 'Planillas Eventuales',
          icon: 'attach_file',
          params: {
            active: true,
            options: ['new', 'edit', 'ticket', 'bank', 'ovt', 'afp', 'payroll']
          }
        }
      ]
    }, {
      title: 'Consultores',
      icon: 'swap_horiz',
      color: 'yellow',
      group: [
        {
          href: 'consultantIndex',
          title: 'Contratos Consultores',
          icon: 'work',
          params: {
            options: ['new', 'edit', 'delete', 'renew', 'printInsurance']
          }
        }, {
          href: 'consultantProcedureIndex',
          title: 'Planillas Consultores',
          icon: 'attach_file',
          params: {
            active: true,
            options: ['new', 'edit', 'ticket', 'bank', 'ovt', 'afp', 'payroll']
          }
        }
      ]
    }, {
      href: 'userIndex',
      title: 'Usuarios',
      icon: 'person',
      params: {
        options: ['edit']
      }
    }, {
      href: 'userActionIndex',
      title: 'Actividad',
      icon: 'timeline',
      params: {
        options: ['edit']
      }
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
      title: 'Contratos Eventuales',
      icon: 'work',
      options: ['new', 'edit', 'delete', 'renew', 'printInsurance']
    }, {
      href: 'consultantIndex',
      params: {},
      title: 'Contratos Consultores',
      icon: 'pie_chart',
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
      title: 'Contratos Eventuales',
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
      title: 'Contratos Eventuales',
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