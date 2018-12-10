export default {
  admin: [
    {
      href: 'dashboard',
      title: 'Inicio',
      icon: 'home'
    }, {
      href: 'employeeIndex',
      title: 'Personas',
      icon: 'group',
      params: {
        options: ['new', 'edit', 'inactiveEdit', 'delete', 'print']
      }
    }, {
      title: 'Eventuales',
      icon: 'person',
      group: [
        {
          href: 'contractIndex',
          title: 'Contratos Eventuales',
          icon: 'gavel',
          params: {
            options: ['new', 'edit', 'inactiveEdit', 'delete', 'renew', 'printInsurance', 'printContract']
          }
        }, {
          href: 'procedureIndex',
          title: 'Planillas Eventuales',
          icon: 'storage',
          params: {
            active: true,
            options: ['new', 'edit', 'ticket', 'bank', 'ovt', 'afp', 'payroll']
          }
        }
      ]
    }, {
      title: 'Consultores',
      icon: 'work',
      group: [
        {
          href: 'consultantIndex',
          title: 'Contratos Consultores',
          icon: 'gavel',
          params: {
            options: ['new', 'edit', 'delete', 'renew']
          }
        }, {
          href: 'consultantProcedureIndex',
          title: 'Planillas Consultores',
          icon: 'storage',
          params: {
            active: true,
            options: ['new', 'edit', 'ticket', 'bank', 'payroll']
          }
        }
      ]
    }, {
      href: 'userIndex',
      title: 'Usuarios',
      icon: 'security',
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
      title: 'Salidas y Licencias',
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
      icon: 'home'
    }, {
      href: 'employeeIndex',
      title: 'Personas',
      icon: 'group',
      params: {
        options: ['new', 'edit', 'delete', 'print']
      }
    }, {
      title: 'Eventuales',
      icon: 'settings_backup_restore',
      group: [
        {
          href: 'contractIndex',
          title: 'Contratos Eventuales',
          icon: 'work',
          params: {
            options: ['new', 'edit', 'delete', 'renew', 'printInsurance']
          }
        }, {
          href: 'procedureIndex',
          title: 'Planillas Eventuales',
          icon: 'attach_file',
          params: {
            active: true,
            options: ['new', 'edit', 'ticket', 'bank', 'ovt', 'payroll']
          }
        }
      ]
    }, {
      title: 'Consultores',
      icon: 'swap_horiz',
      group: [
        {
          href: 'consultantIndex',
          title: 'Contratos Consultores',
          icon: 'work',
          params: {
            options: ['new', 'edit', 'delete', 'renew'],
          }
        }, {
          href: 'consultantProcedureIndex',
          title: 'Planillas Consultores',
          icon: 'attach_file',
          params: {
            active: true,
            options: ['new', 'edit', 'ticket', 'bank', 'payroll'],
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
        options: []
      }
    }
  ],
  juridica: [
    {
      href: 'dashboard',
      title: 'Inicio',
      icon: 'home'
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
      group: [
        {
          href: 'contractIndex',
          title: 'Contratos Eventuales',
          icon: 'work',
          params: {
            options: ['printContract', 'edit']
          }
        }
      ]
    }, {
      title: 'Consultores',
      icon: 'swap_horiz',
      group: [
        {
          href: 'consultantIndex',
          title: 'Contratos Consultores',
          icon: 'work',
          params: {
            options: ['printContract', 'edit']
          }
        }
      ]
    }, {
      href: 'departureIndex',
      title: 'Salidas/Licencias',
      icon: 'directions_run',
      params: {
        options: []
      }
    }
  ],
  financiera: [
    {
      href: 'dashboard',
      title: 'Inicio',
      icon: 'home'
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
      group: [
        {
          href: 'contractIndex',
          title: 'Contratos Eventuales',
          icon: 'work',
          params: {
            options: []
          }
        }, {
          href: 'procedureIndex',
          title: 'Planillas Eventuales',
          icon: 'attach_file',
          params: {
            active: true,
            options: ['afp', 'payroll']
          }
        }
      ]
    }, {
      title: 'Consultores',
      icon: 'swap_horiz',
      group: [
        {
          href: 'consultantIndex',
          title: 'Contratos Consultores',
          icon: 'work',
          params: {
            options: []
          }
        }, {
          href: 'consultantProcedureIndex',
          title: 'Planillas Consultores',
          icon: 'attach_file',
          params: {
            active: true,
            options: ['afp', 'payroll']
          }
        }
      ]
    }
  ],
  empleado: [
    {
      href: 'dashboard',
      title: 'Inicio',
      icon: 'home'
    }, {
      href: 'departureIndex',
      title: 'Salidas/Licencias',
      icon: 'directions_run',
      params: {
        options: []
      }
    }
  ],
}