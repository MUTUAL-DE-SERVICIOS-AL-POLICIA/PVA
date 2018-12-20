<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="closeDialog">
    <v-tooltip slot="activator" top v-if="$store.getters.options.includes('new')">
      <v-icon large slot="activator" dark color="primary">add_circle</v-icon>
      <span>Nuevo Contrato</span>
    </v-tooltip>
    <v-card>
      <v-toolbar dark color="secondary">
        <v-toolbar-title class="white--text">{{ formTitle }}</v-toolbar-title>
      </v-toolbar>
      <v-card-text>
        <v-container grid-list-md layout>
          <v-layout wrap>
            <v-flex xs12 sm6 md6>
              <v-form ref="form">
                <v-autocomplete
                  clearable
                  v-model="selectedItem.employee_id"
                  :items="employees"
                  item-text="identity_card"
                  item-value="id"
                  label="Empleado"
                  v-validate="'required'"
                  v-on:change="onSelectEmployee"
                  name="Empleado"
                  :error-messages="errors.collect('Empleado')"
                  :disabled="!selectedItem.edit"
                ></v-autocomplete>
                <v-combobox
                  clearable
                  v-model="selectedItem.consultant_position"
                  :items="positions"
                  item-text="name"
                  item-value="id"
                  label="Puesto"
                  v-on:change="onSelectPosition"
                  v-validate="'required'"
                  name="Puesto"
                  :error-messages="errors.collect('Puesto')"
                  :disabled="!selectedItem.edit"
                ></v-combobox>
                <v-autocomplete
                  clearable
                  v-model="selectedItem.charge_id"
                  :items="charges"
                  :item-text="chargeSelected"
                  item-value="id"
                  label="Haber básico"
                  v-validate="'required'"
                  v-on:change="onSelectCharge"
                  name="Haber básico"
                  :error-messages="errors.collect('Haber básico')"
                  :disabled="!selectedItem.edit"
                ></v-autocomplete>
                <v-autocomplete
                  clearable
                  v-model="selectedItem.position_group_id"
                  :items="positionGroups"
                  item-text="name"
                  item-value="id"
                  label="Unidad"
                  v-validate="'required'"
                  name="Unidad"
                  :error-messages="errors.collect('Unidad')"
                  :disabled="!selectedItem.edit"
                ></v-autocomplete>
                <v-layout wrap>
                  <v-flex xs12 sm6 md6>
                    <v-menu
                      :close-on-content-click="false"
                      v-model="datePicker.start.display"
                      offset-y
                      full-width
                      max-width="290px"
                      min-width="290px"
                      :disabled="!selectedItem.edit"
                    >
                      <v-text-field
                        :clearable="selectedItem.edit"
                        slot="activator"
                        v-model="datePicker.start.formattedDate"
                        label="Inicio"
                        prepend-icon="event"
                        v-validate="'required'"
                        name="Fecha de inicio"
                        :error-messages="errors.collect('Fecha de inicio')"
                        readonly
                      ></v-text-field>
                      <v-date-picker locale="es-bo" v-model="selectedItem.start_date" no-title @input="datePicker.start.display = false"></v-date-picker>
                    </v-menu>
                  </v-flex>
                  <v-flex xs12 sm6 md6>
                    <v-menu
                      :close-on-content-click="false"
                      v-model="datePicker.end.display"
                      offset-y
                      full-width
                      max-width="290px"
                      min-width="290px"
                      v-show="datePicker.start.formattedDate"
                    >
                      <v-text-field
                        clearable
                        slot="activator"
                        v-model="datePicker.end.formattedDate"
                        label="Conclusión"
                        prepend-icon="event"
                        v-validate="'required'"
                        name="Fecha de conclusión"
                        :error-messages="errors.collect('Fecha de conclusión')"
                        readonly
                      ></v-text-field>
                      <v-date-picker locale="es-bo" :min="datePicker.end.min" v-model="selectedItem.end_date" no-title @input="datePicker.end.display = false"></v-date-picker>
                    </v-menu>
                  </v-flex>
                </v-layout>
                <v-text-field
                  v-model="selectedItem.contract_number"
                  label="Número de contrato"
                  autocomplete='cc-number'
                  clearable
                ></v-text-field>
                <v-layout wrap>
                  <v-flex xs12 sm6 md6>
                    <v-text-field
                      v-model="selectedItem.rrhh_cite"
                      label="Cert. de Equivalencia RRHH"
                      clearable
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs12 sm6 md6>
                    <v-menu
                      :close-on-content-click="false"
                      v-model="datePicker.rrhh.display"
                      offset-y
                      full-width
                      max-width="290px"
                      min-width="290px"
                      v-show="selectedItem.rrhh_cite"
                    >
                      <v-text-field
                        clearable
                        slot="activator"
                        v-model="datePicker.rrhh.formattedDate"
                        label="Fecha de cite"
                        prepend-icon="event"
                        readonly
                      ></v-text-field>
                      <v-date-picker locale="es-bo" v-model="selectedItem.rrhh_cite_date" no-title @input="datePicker.rrhh.display = false"></v-date-picker>
                    </v-menu>
                  </v-flex>
                </v-layout>
                <v-textarea
                  v-model="selectedItem.description"
                  label="Descripción/Observaciones"
                  rows="2"
                ></v-textarea>
                <v-radio-group
                  v-model="selectedItem.schedules"
                  v-validate="'required'"
                  name="Horario"
                  :error-messages="errors.collect('Horario')">
                  <v-radio
                    v-for="schedule in jobSchedules"
                    label="Horario  (08:00-12:00 | 14:30-18:30)"
                    :key="schedule.id"
                    :value="[1,2]"
                    color="primary"
                    v-if="schedule.id==1"
                  ></v-radio>
                  <v-radio
                    v-for="schedule in jobSchedules"
                    :label="`Horario (${schedule.start_hour}:${schedule.start_minutes}0 - ${schedule.end_hour}:${schedule.end_minutes}0)`"
                    :key="schedule.id"
                    :value="[schedule.id]"
                    color="primary"
                    v-if="schedule.id!=1 && schedule.id!=2"
                  ></v-radio>
                </v-radio-group>
              </v-form>
              <v-divider class="mb-3"></v-divider>
              <v-card v-if="!selectedItem.new && selectedItem.edit">
                <v-form ref="form">
                  <v-select
                    :items="retirementReasons"
                    item-text="name"
                    item-value="id"
                    label="Motivo de retiro"
                    v-model="selectedItem.retirement_reason_id"
                    clearable
                  ></v-select>
                  <v-menu
                    :close-on-content-click="false"
                    v-model="datePicker.retirement.display"
                    offset-y
                    full-width
                    max-width="290px"
                    min-width="290px"
                    v-if="selectedItem.retirement_reason_id"
                  >
                    <v-text-field
                      clearable
                      slot="activator"
                      v-model="datePicker.retirement.formattedDate"
                      label="Fecha de retiro"
                      prepend-icon="event"
                      v-validate="selectedItem.retirement_reason_id ? 'required' : ''"
                      name="Fecha de retiro"
                      :error-messages="errors.collect('Fecha de retiro')"
                      readonly
                    ></v-text-field>
                    <v-date-picker locale="es-bo" v-model="selectedItem.retirement_date" no-title @input="datePicker.retirement.display = false" :min="$moment(selectedItem.start_date).add(1, 'days').toISOString().split('T')[0]" :max="$moment(selectedItem.end_date).toISOString().split('T')[0]"></v-date-picker>
                  </v-menu>
                </v-form>
              </v-card>
            </v-flex>
            <v-flex xs12 sm6 md6>
              <v-card>
                <v-card-text>
                  <p><strong>Empleado: </strong> {{ table.employee.last_name }} {{ table.employee.mothers_last_name }} {{ table.employee.first_name }} {{ table.employee.second_name }}</p>
                  <p>
                    <strong>Puesto: </strong> {{ table.position.name }}
                    <v-chip v-if="!table.position.free" small color="red" text-color="white">Ocupado</v-chip>
                  </p>
                  <p><strong>Haber Basico: </strong> {{ table.charge.base_wage }} Bs. </p>
                  <table class="v-datatable v-table">
                    <thead>
                      <tr>
                        <th>Mes</th>
                        <th>Dias</th>
                        <th>Salario</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-for="item in table.months"
                        :key="item.name"
                      >
                        <td> {{ (item.name).toUpperCase() }} </td>
                        <td class="text-xs-center">{{ item.count }}</td>
                        <td class="text-xs-right">{{ item.salary }} Bs.</td>
                      </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                          <td colspan="2"><span>Total </span></td>
                          <td class="text-xs-right font-weight-bold">{{ table.months.reduce((total, o) => parseFloat(o.salary) + total,0).toFixed(2) }} Bs.</td>
                        </tr>
                    </tfoot>
                  </table>
                </v-card-text>
              </v-card>
            </v-flex>
          </v-layout>
        </v-container>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="error" @click.native="closeDialog"><v-icon>close</v-icon> Cancelar</v-btn>
        <v-btn color="success" @click.native="saveContract()"><v-icon>check</v-icon> Guardar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: "ConsultantForm",
  props: ["item", "bus"],
  data() {
    return {
      dialog: false,
      datePicker: {
        start: {
          formattedDate: null,
          display: false
        },
        end: {
          formattedDate: null,
          display: false,
          min: null
        },
        retirement: {
          formattedDate: null,
          display: false
        },
        rrhh: {
          formattedDate: null,
          display: false
        }
      },
      selectedItem: {
        edit: true,
        start_date: null,
        end_date: null,
        retirement_date: null,
        retirement_reason_id: null,
        consultant_position: null,
        consultant_position_id: null,
        new: true
      },
      formTitle: 'Nuevo contrato de consultoría',
      employees: [],
      positions: [],
      positionGroups: [],
      charges: [],
      jobSchedules: [],
      retirementReasons: [],
      table: {
        employee: {
          last_name: '',
          mothers_last_name: '',
          first_name: '',
          second_name: ''
        },
        position: {
          name: '',
          free: true
        },
        charge: {
          name: '',
          base_wage: ''
        },
        months: []
      },
      position_id: null,
    }
  },
  created() {
    this.getEmployees()
    this.getPositions()
    this.getCharges()
    this.getJobSchedules()
    this.getPositionGroups()
    this.getRetirementReasons()
  },
  mounted() {
    this.bus.$on("openDialog", item => {
      if (item) {
        this.selectedItem = item
        this.formTitle = item.edit ? 'Editar contrato de consultoría' : 'Recontratar consultor'
        this.dialog = true
        this.onSelectEmployee(this.selectedItem.employee.id)
        this.onSelectPosition(this.selectedItem.consultant_position)
        this.selectedItem.schedules = item.job_schedules.map(o => o.id)
        this.position_id = item.consultant_position_id

        if (!item.edit) {
          this.selectedItem.start_date = this.onlyWeekdays(this.$moment(item.retirement_date ? item.retirement_date : item.end_date)).startOf('day').toISOString().split('T')[0]
          this.selectedItem.end_date = null
          this.selectedItem.rrhh_cite = null
          this.selectedItem.rrhh_cite_date = null
          this.selectedItem.contract_number = null
          this.position_id = null
        }
      }
    })
  },
  watch: {
    'selectedItem.start_date': function(value) {
      if (value) {
        let date = this.$moment(value)
        this.datePicker.start.formattedDate = date.format('L')
        if (date.date() == 1) {
          this.datePicker.end.min = date.endOf('month').startOf('day').toISOString().split('T')[0]
        } else if (date.date() > 1) {
          this.datePicker.end.min = date.add(1, 'month').subtract(1, 'days').toISOString().split('T')[0]
        }

        if (this.$moment(this.selectedItem.end_date).diff(this.$moment(this.selectedItem.start_date), 'days') < 30) {
          this.selectedItem.end_date = this.datePicker.end.min
        }
        this.monthSalaryCalc()
      }
    },
    'selectedItem.end_date': function(value) {
      if (value) {
        this.datePicker.end.formattedDate = this.$moment(value).format('L')
        this.monthSalaryCalc()
      }
    },
    'selectedItem.retirement_date': function(value) {
      if (value) this.datePicker.retirement.formattedDate = this.$moment(value).format('L')
    },
    'selectedItem.rrhh_cite_date': function(value) {
      if (value) this.datePicker.rrhh.formattedDate = this.$moment(value).format('L')
    },
    'selectedItem.retirement_reason_id': function(value) {
      if (!value) {
        this.selectedItem.retirement_reason_id = null
        this.selectedItem.retirement_date = null
      }
    }
  },
  methods: {
    onlyWeekdays(momentDate) {
      do {
        momentDate = momentDate.add(1, 'days')
      }
      while (momentDate.isoWeekday() > 5)
      return momentDate
    },
    clearForm() {
      this.datePicker = {
        start: {
          formattedDate: null,
          display: false
        },
        end: {
          formattedDate: null,
          display: false,
          min: null
        },
        retirement: {
          formattedDate: null,
          display: false
        },
        rrhh: {
          formattedDate: null,
          display: false
        }
      },
      this.selectedItem = {
        employee_id: null,
        edit: true,
        start_date: null,
        end_date: null,
        retirement_date: null,
        retirement_reason_id: null,
        position: null,
        consultant_position_id: null,
        new: true
      },
      this.table = {
        employee: {
          last_name: '',
          mothers_last_name: '',
          first_name: '',
          second_name: ''
        },
        position: {
          name: '',
          free: true
        },
        charge: {
          name: '',
          base_wage: ''
        },
        months: []
      }
    },
    formatDate (date) {
      if (!date) return null
      const [year, month, day] = date.split('-')
      return `${day}/${month}/${year}`
    },
    chargeSelected: charge => `${charge.base_wage} Bs. - ${charge.name}`,
    closeDialog() {
      this.dialog = false
      this.$validator.reset()
      this.bus.$emit("closeDialog")
      this.clearForm()
    },
    async getEmployees() {
      try {
        let res = await axios.get("/employee")
        this.employees = res.data
      } catch (e) {
        console.log(e)
      }
    },
    onSelectEmployee(id) {
      if (id) {
        this.table.employee = this.employees.find(o => o.id == id)
      } else {
        this.table.employee = {
          last_name: '',
          mothers_last_name: '',
          first_name: '',
          second_name: ''
        }
      }
    },
    async getPositions() {
      try {
        let res = await axios.get("/consultant_position")
        this.positions = res.data
      } catch (e) {
        console.log(e)
      }
    },
    async getJobSchedules() {
      try {
        let res = await axios.get("/jobs_chedule")
        this.jobSchedules = res.data
      } catch (e) {
        console.log(e)
      }
    },
    async onSelectPosition(position) {
      try {
        if (position) {
          let search = this.positions.find(o => o.id == position.id)
          if (search) {
            this.selectedItem.consultant_position_id = search.id
            this.table.position = search
            this.selectedItem.charge_id = search.charge_id
            this.selectedItem.position_group_id = search.position_group_id
            this.onSelectCharge(search.charge.id)
            let res = await axios.get(`/consultant_contract/position_free/${position.id}`)            
            if (this.position_id == position.id) {
              this.table.position.free = true
            } else {
              this.table.position.free = res.data.free
            }
          } else {
            this.table.position.free = true
          }
          this.getPositions()
        }
      } catch (e) {
        console.log(e)
      }
    },
    async getCharges() {
      try {
        let res = await axios.get("/charge")
        this.charges = res.data
      } catch (e) {
        console.log(e)
      }
    },
    async getPositionGroups() {
      try {
        let res = await axios.get("/position_group")
        this.positionGroups = res.data
      } catch (e) {
        console.log(e)
      }
    },
    async getRetirementReasons() {
      try {
        let res = await axios.get("/retirement_reason")
        this.retirementReasons = res.data
      } catch (e) {
        console.log(e)
      }
    },
    onSelectCharge(id) {
      if (id) {
        this.table.charge = this.charges.find(o => o.id == id)
        this.monthSalaryCalc()
      } else {
        this.table.charge = {
          name: '',
          base_wage: ''
        }
      }
    },
    async saveContract() {
      try {
        let valid = await this.$validator.validateAll();
        if (valid) {
          if (Number.isInteger(this.selectedItem.consultant_position.id)) {
            let position = this.positions.find(o => o.id == this.selectedItem.consultant_position_id)
            if (position.charge.id != this.selectedItem.charge_id && position.position_group.id != this.selectedItem.position_group_id) {
              this.selectedItem.name = position.name
              let res = await axios.post(`/consultant_position`, this.selectedItem)
              this.toastr.success('Cargo creado exitosamente')
            }
          } else {
            let res = await axios.post(`/consultant_position`, {
              name: this.selectedItem.consultant_position,
              charge_id: this.selectedItem.charge_id,
              position_group_id: this.selectedItem.position_group_id
            })
            this.selectedItem.consultant_position_id = res.data.id
            this.toastr.success('Cargo creado exitosamente')
          }
          if (this.selectedItem.new) {
            await axios.post(`/consultant_contract`, this.selectedItem)
          } else if (!this.selectedItem.edit && !this.selectedItem.new) {
            await axios.patch(`/consultant_contract/${this.selectedItem.id}`, {
              active: false
            })
            await axios.post(`/consultant_contract`, this.selectedItem)
          } else {
            if ((this.selectedItem.retirement_date && this.selectedItem.retirement_reason_id)) {
              this.selectedItem.active = false
            } else {
              this.selectedItem.active = true
            }
            await axios.patch(`/consultant_contract/${this.selectedItem.id}`, this.selectedItem)
          }
          this.toastr.success('Consultor agregado exitosamente')
          this.closeDialog()
        }
      } catch (e) {
        console.log(e)
      }
    },
    monthSalaryCalc() {
      if (this.selectedItem.start_date && this.selectedItem.end_date && this.table.charge.base_wage) {
        this.table.months = []

        let startDate = this.$moment(this.selectedItem.start_date)
        let endDate = this.$moment(this.selectedItem.end_date)
        let diary = this.table.charge.base_wage / 30

        let count = 30 - startDate.format('D') + 1
        this.table.months.push({
          name: startDate.format('MMMM'),
          count: count,
          salary: (diary * count).toFixed(2)
        })

        while (!endDate.isSame(startDate, 'month', 'year')) {
          if (!endDate.isSame(startDate.add(1, 'month'), 'month', 'year')) {
            this.table.months.push({
              name: startDate.format('MMMM'),
              count: 30,
              salary: Number(this.table.charge.base_wage).toFixed(2)
            })
          } else {
            count = Number(endDate.format('D'))

            let lastDayOfMonth = Number(endDate.endOf('month').format('D'))
            if (lastDayOfMonth != 30 && count == lastDayOfMonth) {
              count = 30
            }

            this.table.months.push({
              name: endDate.format('MMMM'),
              count: count,
              salary: (diary * count).toFixed(2)
            })
          }
        }
      }
    }
  }
}
</script>