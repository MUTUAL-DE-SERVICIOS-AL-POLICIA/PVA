export default {
    admin: [{
        href: 'employeeIndex',
        title: 'Empleados',
        icon: 'assignment_ind',
        options: ['new', 'edit', 'delete', 'active', 'print']
    }, {
        href: 'contractIndex',
        params: {},
        title: 'Contratos',
        icon: 'work',
        options: ['new', 'edit', 'delete', 'renew', 'print']
    }, {
        href: 'procedureIndex',
        params: {
            active: true
        },
        title: 'Planillas',
        icon: 'attach_file',
        options: ['new', 'edit', 'ticket', 'bank', 'ovt', 'payroll']
    }, ],
    rrhh: [{
        href: 'employeeIndex',
        title: 'Empleados',
        icon: 'assignment_ind',
        options: ['new', 'edit', 'delete', 'active', 'print']
    }, {
        href: 'contractIndex',
        params: {},
        title: 'Contratos',
        icon: 'work',
        options: ['new', 'edit', 'delete', 'renew', 'print']
    }, {
        href: 'procedureIndex',
        params: {
            active: true
        },
        title: 'Planillas',
        icon: 'attach_file',
        options: ['new', 'edit', 'ticket', 'bank', 'ovt', 'payroll']
    }, ],
    juridica: [{
        href: 'employeeIndex',
        title: 'Empleados',
        icon: 'assignment_ind',
        options: []
    }, {
        href: 'contractIndex',
        params: {},
        title: 'Contratos',
        icon: 'work',
        options: ['print', 'edit']
    }]
}