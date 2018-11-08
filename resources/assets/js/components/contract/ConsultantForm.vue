<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="closeDialog">
    <v-tooltip slot="activator" top>
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
                    >
                      <v-text-field
                        clearable
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
                      label="Cite de RRHH"
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
        rrhh: {
          formattedDate: null,
          display: false
        }
      },
      selectedItem: {
        edit: true,
        start_date: null,
        end_date: null,
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
        }
      }
    }
  },
  created() {
    this.getEmployees()
    this.getPositions()
    this.getCharges()
    this.getJobSchedules()
    this.getPositionGroups()
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

        if (!item.edit) {
          this.selectedItem.start_date = this.onlyWeekdays(this.$moment(item.retirement_date ? item.retirement_date : item.end_date)).startOf('day').toISOString().split('T')[0]
          this.selectedItem.end_date = null
          this.selectedItem.rrhh_cite = null
          this.selectedItem.rrhh_cite_date = null
          this.selectedItem.contract_number = null
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
        if (this.$moment(this.datePicker.end.min).diff(this.$moment(this.datePicker.start), 'days') < 30) {
          this.selectedItem.end_date = this.datePicker.end.min
        }
      }
    },
    'selectedItem.end_date': function(value) {
      if (value) this.datePicker.end.formattedDate = this.$moment(value).format('L')
    },
    'selectedItem.rrhh_cite_date': function(value) {
      if (value) this.datePicker.rrhh.formattedDate = this.$moment(value).format('L')
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
        position: null,
        consultant_position_id: null
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
        }
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
            this.table.position.free = res.data.free
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
    onSelectCharge(id) {
      if (id) {
        this.table.charge = this.charges.find(o => o.id == id)
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
          if (!this.selectedItem.edit) {
            await axios.patch(`/consultant_contract/${this.selectedItem.id}`, {
              active: false
            })
          }
          if (this.selectedItem.new) {
            await axios.post(`/consultant_contract`, this.selectedItem)
          } else {
            await axios.patch(`/consultant_contract/${this.selectedItem.id}`, this.selectedItem)
          }
          this.toastr.success('Consultor agregado exitosamente')
          this.closeDialog()
        }
      } catch (e) {
        console.log(e)
      }
    }
  }
}
</script>