export default {
  admin: [
    {
      href: 'dashboard',
      title: 'Inicio',
      icon: 'home',
      params: {
        options: []
      }
    }, {
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
            options: ['new', 'edit', 'delete', 'renew', 'printInsurance', 'printContract'],
            color: 'blue lighten-4'
          }
        }, {
          href: 'procedureIndex',
          title: 'Planillas Eventuales',
          icon: 'attach_file',
          params: {
            active: true,
            options: ['new', 'edit', 'ticket', 'bank', 'ovt', 'afp', 'payroll'],
            color: 'blue lighten-4'
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
            options: ['new', 'edit', 'delete', 'renew'],
            color: 'yellow lighten-5'
          }
        }, {
          href: 'consultantProcedureIndex',
          title: 'Planillas Consultores',
          icon: 'attach_file',
          params: {
            active: true,
            options: ['new', 'edit', 'ticket', 'bank', 'payroll'],
            color: 'yellow lighten-5'
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
    }, {
      href: 'departureAdmin',
      title: 'Administrador de Salidas y Licencias',
      icon: 'directions_walk',
      params: {
        options: ['edit', 'active', 'print']
      }
    },
  ],
  rrhh: [
    {
      href: 'dashboard',
      title: 'Inicio',
      icon: 'home',
      params: {
        options: []
      }
    }, {
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
            options: ['new', 'edit', 'delete', 'renew', 'printInsurance'],
            color: 'blue lighten-4'
          }
        }, {
          href: 'procedureIndex',
          title: 'Planillas Eventuales',
          icon: 'attach_file',
          params: {
            active: true,
            options: ['new', 'edit', 'ticket', 'bank', 'ovt', 'payroll'],
            color: 'blue lighten-4'
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
            options: ['new', 'edit', 'delete', 'renew'],
            color: 'yellow lighten-5'
          }
        }, {
          href: 'consultantProcedureIndex',
          title: 'Planillas Consultores',
          icon: 'attach_file',
          params: {
            active: true,
            options: ['new', 'edit', 'ticket', 'bank', 'payroll'],
            color: 'yellow lighten-5'
          }
        }
      ]
    }, {
      href: 'departureAdmin',
      title: 'Administrador de Salidas y Licencias',
      icon: 'directions_walk',
      params: {
        options: ['edit', 'active', 'print']
      }
    }, 
    {
      href: 'departureIndex',
      title: 'Salidas/Licencias',
      icon: 'directions_run',
      params: {
        options: ['new', 'edit', 'delete', 'active', 'print']
      }
    }
  ],
  juridica: [
    {
      href: 'dashboard',
      title: 'Inicio',
      icon: 'home',
      params: {
        options: []
      }
    }, {
      href: 'employeeIndex',
      title: 'Empleados',
      icon: 'assignment_ind',
      params: {
        options: []
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
            options: ['printContract', 'edit'],
            color: 'blue lighten-4'
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
            options: ['printContract', 'edit'],
            color: 'yellow lighten-5'
          }
        }
      ]
    }, {
      href: 'departureIndex',
      title: 'Salidas/Licencias',
      icon: 'directions_run',
      params: {
        options: ['new', 'edit', 'delete', 'active', 'print']
      }
    }
  ],
  financiera: [
    {
      href: 'dashboard',
      title: 'Inicio',
      icon: 'home',
      params: {
        options: []
      }
    }, {
      href: 'employeeIndex',
      title: 'Empleados',
      icon: 'assignment_ind',
      params: {
        options: []
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
            options: [],
            color: 'blue lighten-4'
          }
        }, {
          href: 'procedureIndex',
          title: 'Planillas Eventuales',
          icon: 'attach_file',
          params: {
            active: true,
            options: ['afp', 'payroll'],
            color: 'blue lighten-4'
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
            options: [],
            color: 'yellow lighten-5'
          }
        }, {
          href: 'consultantProcedureIndex',
          title: 'Planillas Consultores',
          icon: 'attach_file',
          params: {
            active: true,
            options: ['afp', 'payroll'],
            color: 'yellow lighten-5'
          }
        }
      ]
    }
  ],
  empleado: [
    {
      href: 'dashboard',
      title: 'Inicio',
      icon: 'home',
      params: {
        options: []
      }
    }, {
      href: 'departureIndex',
      title: 'Salidas/Licencias',
      icon: 'directions_run',
      params: {
        options: ['new', 'edit', 'delete', 'active', 'print']
      }
    }
  ],
}