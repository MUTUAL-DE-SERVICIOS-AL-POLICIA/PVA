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
        options: ['delete']
      }
    }, {
      href: 'departureAdmin',
      title: 'Salidas y Licencias',
      icon: 'directions_walk',
      params: {
        options: ['edit', 'active', 'print']
      }
    }, {
      href: 'phonebookIndex',
      title: 'Agenda Telefonica',
      icon: 'contact_phone',
      params: {
        options: ['new', 'edit', 'delete', 'print']
      }
    }, {
      href: 'subarticleIndex',
      title: 'Almacén',
      icon: 'receipt',
      params: {
        options: []
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
      icon: 'person',
      group: [
        {
          href: 'contractIndex',
          title: 'Contratos Eventuales',
          icon: 'gavel',
          params: {
            options: ['new', 'edit', 'delete', 'renew', 'printInsurance']
          }
        }, {
          href: 'procedureIndex',
          title: 'Planillas Eventuales',
          icon: 'storage',
          params: {
            active: true,
            options: ['new', 'edit', 'ticket', 'bank', 'ovt', 'payroll']
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
            options: ['new', 'edit', 'delete', 'renew'],
          }
        }, {
          href: 'consultantProcedureIndex',
          title: 'Planillas Consultores',
          icon: 'storage',
          params: {
            active: true,
            options: ['new', 'edit', 'ticket', 'bank', 'payroll'],
          }
        }
      ]
    }, {
      href: 'userActionIndex',
      title: 'Actividad',
      icon: 'timeline',
      params: {
        options: []
      }
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
    }, {
      href: 'subarticleIndex',
      title: 'Almacén',
      icon: 'receipt',
      params: {
        options: []
      }
    },
  ],
  juridica: [
    {
      href: 'dashboard',
      title: 'Inicio',
      icon: 'home'
    }, {
      href: 'employeeIndex',
      title: 'Empleados',
      icon: 'group',
      params: {
        options: []
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
            options: ['printContract', 'edit']
          }
        }
      ]
    }, {
      href: 'userActionIndex',
      title: 'Actividad',
      icon: 'timeline',
      params: {
        options: []
      }
    }, {
      href: 'departureIndex',
      title: 'Salidas/Licencias',
      icon: 'directions_run',
      params: {
        options: []
      }
    }, {
      href: 'subarticleIndex',
      title: 'Almacén',
      icon: 'receipt',
      params: {
        options: []
      }
    },
  ],
  financiera: [
    {
      href: 'dashboard',
      title: 'Inicio',
      icon: 'home'
    }, {
      href: 'employeeIndex',
      title: 'Empleados',
      icon: 'group',
      params: {
        options: []
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
            options: []
          }
        }, {
          href: 'procedureIndex',
          title: 'Planillas Eventuales',
          icon: 'storage',
          params: {
            active: true,
            options: ['afp', 'payroll']
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
            options: []
          }
        }, {
          href: 'consultantProcedureIndex',
          title: 'Planillas Consultores',
          icon: 'storage',
          params: {
            active: true,
            options: ['afp', 'payroll']
          }
        }
      ]
    }, {
      href: 'userActionIndex',
      title: 'Actividad',
      icon: 'timeline',
      params: {
        options: []
      }
    }, {
      href: 'subarticleIndex',
      title: 'Almacén',
      icon: 'receipt',
      params: {
        options: []
      }
    },
  ]
}