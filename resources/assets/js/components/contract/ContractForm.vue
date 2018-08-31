<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="close">
    <v-tooltip slot="activator" top>
      <v-icon large slot="activator" dark color="primary">add_circle</v-icon>
      <span>Nuevo Contrato</span>
    </v-tooltip>
    <v-card>
      <v-toolbar dark color="primary" dense flat>
        <v-toolbar-title class="white--text">{{ formTitle }}</v-toolbar-title>
      </v-toolbar>
      <v-card-text>
        <v-container grid-list-md layout>
          <v-layout wrap>
            <v-flex xs12 sm6 md6>
              <v-form ref="form">
                <v-autocomplete
                  v-model="selectedItem.employee_id"
                  :items="employees"
                  item-text="identity_card"
                  item-value="id"
                  label="Empleado"
                  v-on:change="onSelectEmployee"
                  v-validate="'required'"
                  name="Empleado"
                  :error-messages="errors.collect('Empleado')"
                  :disabled="recontract==true">
                </v-autocomplete>
                <v-autocomplete
                  v-model="selectedItem.position_id"
                  :items="positions"
                  item-text="name" 
                  item-value="id"
                  label="Puesto"
                  v-on:change="onSelectPosition"
                  v-validate="'required'"
                  name="Puesto"
                  :error-messages="errors.collect('Puesto')"
                  :disabled="recontract==true">
                </v-autocomplete>
                <v-select
                  v-model="selectedItem.contract_type_id"
                  :items="contractTypes"
                  item-text="name" 
                  item-value="id"                    
                  label="Tipo de contratación"
                  v-validate="'required'"
                  name="Tipo de contratacion"
                  :error-messages="errors.collect('Tipo de contratacion')"
                ></v-select>
                <v-select
                  v-model="selectedItem.contract_mode_id"
                  :items="contractModes"
                  item-text="name" 
                  item-value="id"                    
                  label="Modalidad de contratación"
                  v-validate="'required'"
                  name="Modalidad de contratacion"
                  :error-messages="errors.collect('Modalidad de contratacion')"
                ></v-select>
                <v-menu
                  :close-on-content-click="true"
                  v-model="menuDate"
                  :nudge-right="40"
                  lazy
                  transition="scale-transition"
                  offset-y
                  full-width
                  max-width="290px"
                  min-width="290px"
                >
                  <v-text-field
                    slot="activator"
                    v-model="formatDateStart"
                    label="Fecha de inicio"
                    prepend-icon="event"
                    v-validate="'required'"
                    name="Fecha de inicio"
                    :error-messages="errors.collect('Fecha de inicio')"
                    readonly
                  ></v-text-field>
                  <v-date-picker v-model="date" no-title @input="menuDate = false" @change="monthSalaryCalc"></v-date-picker>
                </v-menu>
                <v-menu
                  :close-on-content-click="true"
                  v-model="menuDate2"
                  :nudge-right="40"
                  lazy
                  transition="scale-transition"
                  offset-y
                  full-width
                  max-width="290px"
                  min-width="290px"
                >
                  <v-text-field
                    slot="activator"
                    v-model="formatDateEnd"
                    label="Fecha de conslusión"
                    prepend-icon="event"                    
                  ></v-text-field>
                  <v-date-picker v-model="date2" no-title @input="menuDate2 = false" @change="monthSalaryCalc"></v-date-picker>
                </v-menu>
                <v-menu v-if="selectedIndex!=-1"
                  :close-on-content-click="true"
                  v-model="menuDate3"
                  :nudge-right="40"
                  lazy
                  transition="scale-transition"
                  offset-y
                  full-width
                  max-width="290px"
                  min-width="290px"
                >
                  <v-text-field
                    slot="activator"
                    v-model="formatDateRetirement"
                    prepend-icon="event"
                    label="Fecha de retiro"                    
                  ></v-text-field>
                  <v-date-picker v-model="date3" no-title @input="menuDate3 = false"></v-date-picker>
                </v-menu>
                <v-select v-if="selectedIndex!=-1"
                  v-model="selectedItem.retirement_reason_id"
                  :items="retirementReasons"
                  item-text="name" 
                  item-value="id"                    
                  label="Razón del retiro"
                ></v-select>
                <v-text-field
                  v-model="selectedItem.rrhh_cite"
                  label="Cite de Recursos Humanos"
                ></v-text-field>
                <v-menu
                  :close-on-content-click="true"
                  v-model="menuDate4"
                  :nudge-right="40"
                  lazy
                  transition="scale-transition"
                  offset-y
                  full-width
                  max-width="290px"
                  min-width="290px"
                >
                  <v-text-field
                    slot="activator"
                    v-model="formatDateCite"
                    label="Fecha de cite de Recursos Humanos"
                    prepend-icon="event"
                    readonly
                  ></v-text-field>
                  <v-date-picker v-model="date4" no-title @input="menuDate4 = false"></v-date-picker>
                </v-menu>
                <v-text-field
                  v-model="selectedItem.performance_cite"
                  label="Cite de evaluacion"
                ></v-text-field>
                <v-select
                  v-model="selectedItem.insurance_company_id"
                  :items="insuranceCompanies"
                  item-text="name" 
                  item-value="id"                    
                  label="Compañia de seguro"
                  v-validate="'required'"
                  name="Seguro"
                  :error-messages="errors.collect('Seguro')"
                ></v-select>
                <v-text-field
                  v-model="selectedItem.insurance_number"
                  label="Numero de asegurado"
                ></v-text-field>
                <v-text-field
                  v-model="selectedItem.hiring_reference_number"
                  label="Referencia de contratación"
                ></v-text-field>
                <v-textarea
                  v-model="selectedItem.description"
                  label="Descripción/Observaciones"
                ></v-textarea>
                <v-radio-group 
                  v-model="selectedSchedule.id"
                  v-validate="'required'"
                  name="Horario"
                  :error-messages="errors.collect('Horario')">
                  <v-radio
                    v-for="n in jobSchedules"
                    label="Horario  (08:00-12:00 | 14:30-18:30)"
                    :key="n.id"
                    :value="n.id"
                    color="primary"
                    v-if="n.id==1"
                  ></v-radio>
                  <v-radio 
                    v-for="n in jobSchedules"
                    :label="`Horario (${n.start_hour}:${n.start_minutes}0 - ${n.end_hour}:${n.end_minutes}0)`"
                    :key="n.id"
                    :value="n.id"
                    color="primary"
                    v-if="n.id!=1 && n.id!=2"
                  ></v-radio>
                </v-radio-group>
                <v-checkbox                    
                  v-model="selectedItem.active"
                  label="Vigente"
                  input-value="true" 
                  color="primary"
                  value                
                ></v-checkbox>
              </v-form>
            </v-flex>
            <v-spacer></v-spacer>
            <v-flex xs12 sm6 md6>
              <v-card>
                <v-card-text>
                  <p><strong>Empleado: </strong> {{ fullName(tableEmployee) }} </p>
                  <p><strong>Puesto: </strong> {{ tablePosition }} </p>
                  <p><strong>Haber Basico: </strong> Bs. {{ tableSalary }} </p>
                  <table class="v-datatable v-table">
                    <thead>
                      <tr>
                        <th>Mes</th>
                        <th>Dias</th>
                        <th>Salario</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="item in tableData">
                        <td> {{ (item.month).toUpperCase() }} </td>
                        <td class="text-xs-center"> {{ item.day }} <p class="red">{{ item.obs }}</p> </td>
                        <td class="text-xs-left"> Bs.{{ item.salary }} </td>
                      </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"><span>Total </span></td>
                            <td> Bs.{{ tableSalaryTotal }} </td>
                        </tr>
                    </tfoot>
                  </table>
                  </v-data-table>
                </v-card-text>
              </v-card>
            </v-flex>
          </v-layout>
        </v-container>
      </v-card-text>  
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="error" @click.native="close"><v-icon>close</v-icon> Cancelar</v-btn>
        <v-btn color="success" :disabled="!valid" @click="save()" v-if="recontract==false"><v-icon>check</v-icon> Guardar</v-btn>
        <v-btn color="success" :disabled="!valid" @click="saveRecontract()" v-if="recontract==true"><v-icon>done_all</v-icon> Recontratar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: "ContractForm",
  props: ["item", "bus"],
  data() {
    return {
      employees:    [],
      positions:    [],
      contractTypes:    [],
      contractModes:    [],
      retirementReasons:    [],
      insuranceCompanies:    [],
      jobSchedules: [],
      valid:        true,
      menu:         false,
      date:         null,
      date2:        null,
      date3:        null,
      date4:        null,
      menuDate:     false,
      menuDate2:    false,
      menuDate3:    false,
      menuDate4:    false,
      recontract: false,
      dialog: false,
      selectedIndex:  -1,
      tableEmployee: '',
      tablePosition: '',
      tableSalary: '',
      tableSalaryTotal: 0,
      tableData: [],
      selectedItem:   {
                    start_date: '',
                    end_date: '',
                    retirement_date: '',
                    rrhh_cite_date: '',
                  },
      selectedSchedule: {}
    };
  },
  computed: {
    formTitle() {
      return this.selectedIndex === -1 ? 'Nuevo contrato' : this.recontract == true?'Recontratar':'Editar contrato'
    },
    formatDateStart() {
      if (this.$moment(this.selectedItem.start_date).isValid()) {
        return this.$moment(this.selectedItem.start_date).format('DD/MM/YYYY')
      }
    },
    formatDateEnd() {
      if (this.$moment(this.selectedItem.end_date).isValid()) {
        return this.$moment(this.selectedItem.end_date).format('DD/MM/YYYY')
      }
    },
    formatDateRetirement() {
      if (this.$moment(this.selectedItem.retirement_date).isValid()) {
        return this.$moment(this.selectedItem.retirement_date).format('DD/MM/YYYY')
      }
    },
    formatDateCite() {
      if (this.$moment(this.selectedItem.rrhh_cite_date).isValid()) {
        return this.$moment(this.selectedItem.rrhh_cite_date).format('DD/MM/YYYY')
      }
    }
  },
  watch: {
    date (val) {
      this.selectedItem.start_date = this.date
    },
    date2 (val) {
      this.selectedItem.end_date = this.date2
    },
    date3 (val) {
      this.selectedItem.retirement_date = this.date3
    },
    date4 (val) {
      this.selectedItem.rrhh_cite_date = this.date4
    }
  },
  methods: {
    async initialize() {
      try {        
        let employees = await axios.get('/api/v1/employee')
        this.employees = employees.data
        let positions = await axios.get('/api/v1/position')
        this.positions = positions.data
        let contractTypes = await axios.get('/api/v1/contract_type')
        this.contractTypes = contractTypes.data
        let contractModes = await axios.get('/api/v1/contract_mode')
        this.contractModes = contractModes.data
        let retirementReasons = await axios.get('/api/v1/retirement_reason')
        this.retirementReasons = retirementReasons.data
        let insuranceCompanies = await axios.get('/api/v1/insurance_company')
        this.insuranceCompanies = insuranceCompanies.data
        let jobSchedules = await axios.get('/api/v1/jobs_chedule')
        this.jobSchedules = jobSchedules.data
      } catch(e) {
        console.log(e)
      }
    },
    close() {
      this.dialog = false;
      this.$validator.reset();
      this.bus.$emit("closeDialog");
      this.selectedItem = {start_date: '',
                    end_date: '',
                    retirement_date: '',
                    rrhh_cite_date: '',}
      this.selectedSchedule = {},
      this.tableEmployee= '',
      this.tablePosition= '',
      this.tableSalary= '',
      this.tableSalaryTotal= 0,
      this.tableData= [],
      this.recontract = false
    },
    async save() {
        try {
          await this.$validator.validateAll()
          if (this.selectedIndex != -1) {
            let res = await axios.put('/api/v1/contract/' + this.selectedItem.id, $.extend({}, this.selectedItem, {'schedule': this.selectedSchedule}))
            this.close()
            this.toastr.success('Editado correctamente')
          } else {             
            let res = await axios.post('/api/v1/contract', $.extend({}, this.selectedItem, {'schedule': this.selectedSchedule}))
            this.close()
            this.toastr.success('Registrado correctamente')

          }
        } catch(e) {
          console.log(e)
            for (let key in e.data.errors) {
              e.data.errors[key].forEach(error => {
                this.toastr.error(error)
              });
            }
        }
    },
    async saveRecontract() {
        try {
          await this.$validator.validateAll()
            let newres = await axios.post('/api/v1/contract', $.extend({}, this.selectedItem, {'schedule': this.selectedSchedule}))
            let editres = await axios.put('/api/v1/contract/' + this.selectedItem.id, {"active":false})
            this.close()
            this.toastr.success('Recontratado correctamente')
        } catch(e) {
          console.log(e)
            for (let key in e.data.errors) {
              e.data.errors[key].forEach(error => {
                this.toastr.error(error)
              });
            }
        }
    },
    async saveDate(date) {
      this.$refs.menu.save(date);
    },
    async onSelectEmployee(v) {
      if (v) {
        let employee = await axios.get('/api/v1/employee/' + v)
        this.tableEmployee = employee.data
      }
    },
    async onSelectPosition(v) {
      if (v) {
        let position = await axios.get('/api/v1/position/' + v)
        this.tablePosition = position.data.name
        let charge = await axios.get('/api/v1/charge/' + position.data.charge_id)
        this.tableSalary = charge.data.base_wage
      }
    },
    monthSalaryCalc(){
      let cont = 0
      let data = {}
      let month = ''
      let total = 0
      var d1=this.$moment(this.date);
      var d2=this.$moment(this.date2);
      var diff = d2.diff(d1,"month");
      if (d2.date() < d1.date()) {
        diff++
      }
      for (var i = 0; i <= diff; i++) {
        let day = 30
        let salary = this.tableSalary
        let salary_day = this.tableSalary / 30
        let obs = ''
        if(d1.month() + i == d1.month()) {
            if(d1.date() >= 30) {
                day = 1
            } else {
                day = 30 - d1.date() + 1
            }
        }
        if(this.$moment().month(d1.month() + i).month() == d2.month()) {
            if(d2.date() >= 30) {
                day = 30
            } else {
                day = d2.date()
            }
            
        }
        if(d2.diff(d1,"month") == 0){
            if((d2.date() - d1.date()) < 27) {
              obs = 'Debe ser mayor a un mes'
            }
        }
        salary = salary_day * day;
        salary = (Math.round( salary * 100 )/100 ).toFixed(2)
        total = total + parseInt(salary)
        month = this.$moment().month(d1.month() + i).format('MMMM')
        data[i] = { month : month, day : day, salary: salary, obs: obs}
      }
      this.tableSalaryTotal = (Math.round( total * 100 )/100 ).toFixed(2)      
      this.tableData = data
    },
    fullName(employee){
          let names = `${employee.last_name || ''} ${employee.mothers_last_name || ''} ${employee.surname_husband || ''} ${employee.first_name || ''} ${employee.second_name || ''} `
          names = names.replace(/\s+/gi, ' ').trim().toUpperCase();
          return names;
    }
  },
  mounted() {
    this.bus.$on("openDialog", item => {
      this.selectedItem = item;
      this.date = item.start_date
      this.date2 = item.end_date
      this.tableSalary = item.position.charge.base_wage
      this.monthSalaryCalc()
      this.dialog = true;
      this.selectedIndex = item
      if (item.mode == 'recontract') {
        this.recontract = true
      }
      if (item.job_schedules[0]) {
        this.selectedSchedule = item.job_schedules[0]
      }
    });
    this.initialize()
  },
};
</script>