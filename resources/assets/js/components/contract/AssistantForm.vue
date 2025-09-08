<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="closeDialog">
    <v-tooltip slot="activator" top v-if="$store.getters.permissions.includes('create-consultant')">
      <v-icon large slot="activator" dark color="primary">add_circle</v-icon>
      <span>Nuevo Registro</span>
    </v-tooltip>
    <v-card>
      <v-toolbar dark color="secondary">
        <v-toolbar-title class="white--text">{{ formTitle }}</v-toolbar-title>
      </v-toolbar>
      <v-card-text>
        <v-container grid-list-md layout>
          <v-layout wrap>
            <v-flex xs12 sm12 md12>
              <v-form ref="form">
                <v-autocomplete
                  clearable
                  v-model="selectedItem.employee_id"
                  :items="employees"
                  item-text="identity_card"
                  item-value="id"
                  label="Persona"
                  v-validate="'required'"
                  v-on:change="onSelectEmployee"
                  name="Persona"
                  :error-messages="errors.collect('Persona')"
                  :readonly="selectedItem.edit === true && !selectedItem.new"
                ></v-autocomplete>
                <p>Nombre: {{ table.employee.last_name }} {{ table.employee.mothers_last_name }} {{ table.employee.first_name }} {{ table.employee.second_name }}</p>

                <v-autocomplete
                  clearable
                  v-model="selectedItem.position_group_id"
                  :items="positionGroups"
                  item-text="name"
                  item-value="id"
                  label="Area organizacional que pertenece"
                  v-validate="'required'"
                  name="Area organizacional"
                  :error-messages="errors.collect('Area organizacional')"
                  :readonly="selectedItem.edit === true && !selectedItem.new"
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
                      :disabled="selectedItem.edit === true && !selectedItem.new"
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
                      ></v-text-field>
                      <v-date-picker
                        locale="es-bo"
                        v-model="selectedItem.start_date"
                        no-title
                        @input="datePicker.start.display = false"
                        first-day-of-week="1"
                      ></v-date-picker>
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
                      <v-date-picker
                        locale="es-bo"
                        :min="datePicker.end.min"
                        v-model="selectedItem.end_date"
                        no-title
                        @input="datePicker.end.display = false"
                        first-day-of-week="1"
                      ></v-date-picker>
                    </v-menu>
                  </v-flex>
                </v-layout>
                <v-text-field
                  v-model="selectedItem.register_number"
                  label="Número de registro"
                  autocomplete='cc-number'
                  clearable
                ></v-text-field>
                <v-text-field
                  v-model="selectedItem.university"
                  label="Universidad"
                  autocomplete='cc-number'
                  clearable
                ></v-text-field>
                <p>Modalidad que pertenece</p>
                <v-radio-group
                  v-model="selectedItem.assistant_position"                  
                >
                  <v-radio
                    label="Pasantía"
                    value="Pasantía"
                  ></v-radio>
                  <v-radio
                    label="Trabajo Dirigido"
                    value="Trabajo Dirigido"
                  ></v-radio>
                </v-radio-group>
                
                <v-textarea
                  v-model="selectedItem.description"
                  label="Descripción/Observaciones"
                  rows="2"
                ></v-textarea>
                <v-radio-group
                  v-model="selectedItem.job_schedule_id"
                  v-validate="'required'"
                  name="Horario"
                  :error-messages="errors.collect('Horario')"
                >
                  <template v-for="schedule in jobSchedules">
                    <v-radio
                      :label="`Horario (${schedule.start_hour}:${formatMinutes(schedule.start_minutes)} - ${schedule.end_hour}:${formatMinutes(schedule.end_minutes)})`"
                      :key="schedule.id"
                      :value="schedule.id"
                      color="primary"
                    ></v-radio>
                  </template>
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
                    <v-date-picker
                      locale="es-bo"
                      v-model="selectedItem.retirement_date"
                      no-title
                      @input="datePicker.retirement.display = false"
                      :min="$moment(selectedItem.start_date).add(1, 'days').toISOString().split('T')[0]"
                      :max="$moment(selectedItem.end_date).toISOString().split('T')[0]"
                      first-day-of-week="1"
                    ></v-date-picker>
                  </v-menu>
                </v-form>
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
import { log } from 'util';
export default {
  name: "AssistantForm",
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
      },
      selectedItem: {
        edit: true,
        start_date: null,
        end_date: null,
        retirement_date: null,
        retirement_reason_id: null,
        consultant_position: null,
        register_number: null,
        new: true,
        employee: {
          last_name: '',
          mothers_last_name: '',
          first_name: '',
          second_name: ''
        },
      },
      formTitle: 'Nueva modalidad académica',
      employees: [],
      positionGroups: [],
      jobSchedules: [],
      retirementReasons: [],
      table: {
        employee: {
          last_name: '',
          mothers_last_name: '',
          first_name: '',
          second_name: ''
        },
      },
      position_id: null,
      oldDate: {
        start: null,
        end: null
      }
    }
  },
  created() {
    this.getEmployees()
    this.getJobSchedules()
    this.getPositionGroups()
    this.getRetirementReasons()
  },
  mounted() {
    this.bus.$on("openDialog", item => {
      console.log(item)
      if (item) {
        this.selectedItem = item
        this.formTitle = item.edit ? 'Editar modalidad académica' : 'Prorrogar modalidad academica'
        this.dialog = true
        this.onSelectEmployee(this.selectedItem.employee.id)
        this.getPositionGroups()

        if (!item.edit) {
          this.oldDate = {
            start: item.start_date,
            end: item.end_date
          }
          this.selectedItem.start_date = this.onlyWeekdays(this.$moment(item.retirement_date ? item.retirement_date : item.end_date)).startOf('day').toISOString().split('T')[0]
          this.selectedItem.end_date = null
          this.selectedItem.register_number = null
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
        this.datePicker.end.min = date.add(1, 'days').toISOString().split('T')[0]
        this.selectedItem.end_date = date.endOf('month').startOf('day').toISOString().split('T')[0]
      }
    },
    'selectedItem.end_date': function(value) {
      if (value) {
        this.datePicker.end.formattedDate = this.$moment(value).format('L')
      }
    },
    'selectedItem.retirement_date': function(value) {
      if (value) this.datePicker.retirement.formattedDate = this.$moment(value).format('L')
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
      this.oldDate = {
        start: null,
        end: null
      }
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
        university: null,
        start_date: null,
        end_date: null,
        assistant_position: null,
        position_group_id: null,
        retirement_date: null,
        retirement_reason_id: null,
        register_number: null,
        description: null,
        new: true
      }
      this.table = {
        employee: {
          last_name: '',
          mothers_last_name: '',
          first_name: '',
          second_name: ''
        },
      }
    },
    formatDate (date) {
      if (!date) return null
      const [year, month, day] = date.split('-')
      return `${day}/${month}/${year}`
    },
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
    async getJobSchedules() {
      try {
        let res = await axios.get("/job_schedule")
        this.jobSchedules = res.data
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

    async saveContract() {
      try {
        let valid = await this.$validator.validateAll();
        if (valid) {
         
          if (this.selectedItem.new) {
            await axios.post(`/assistant_contract`, this.selectedItem)
          } else if (!this.selectedItem.edit && !this.selectedItem.new) {
            await axios.patch(`/assistant_contract/${this.selectedItem.id}`, {
              start_date: this.oldDate.start,
              end_date: this.oldDate.end,
              active: false
            })
            delete this.selectedItem['id']
            await axios.post(`/assistant_contract`, this.selectedItem)
          } else {
            if ((this.selectedItem.retirement_date && this.selectedItem.retirement_reason_id)) {
              this.selectedItem.active = false
            } else {
              this.selectedItem.active = true
            }
            await axios.patch(`/assistant_contract/${this.selectedItem.id}`, this.selectedItem)
          }
          this.closeDialog()
        }
      } catch (e) {
        console.log(e)
      }
    },
    formatMinutes(minutes) {
      return minutes === 0 ? '00' : minutes;
    }
    
  }
}
</script>