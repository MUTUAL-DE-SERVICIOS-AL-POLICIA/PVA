<template>
  <v-dialog
    v-model="dialog"
    max-width="900px"
    @keydown.esc="dialog = false"
    persistent
  >
    <v-tooltip slot="activator" top>
      <v-icon large slot="activator" dark color="primary">add_circle</v-icon>
      <span>Nueva Solicitud</span>
    </v-tooltip>

    <v-card>
      <v-toolbar dark color="secondary">
        <v-toolbar-title class="white--text">Solicitud de Salida<span v-if="reasonSelected.name"> - {{ reasonSelected.name }}</span></v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn icon dark @click.native="closeDialog">
          <v-icon>close</v-icon>
        </v-btn>
      </v-toolbar>

      <v-stepper v-model="step">
        <v-stepper-items>
          <v-stepper-content step="1">
            <v-card>
              <v-card-text>
                <v-layout>
                  <v-flex xs5 pr-5>
                    <v-select
                      :items="groups"
                      item-text="name"
                      item-value="id"
                      v-model="departure.group"
                      label="Tipo de permiso"
                      :hint="groupDescription"
                      persistent-hint
                      v-validate="'required'"
                      name="Tipo de permiso"
                      :error-messages="errors.collect('Tipo de permiso')"
                    ></v-select>
                  </v-flex>
                  <v-flex xs7>
                    <v-select
                      :items="reasons"
                      item-text="name"
                      item-value="id"
                      v-model="departure.departure_reason_id"
                      label="Razón"
                      :hint="reasonDescription"
                      persistent-hint
                      v-validate="'required'"
                      name="Razón"
                      :error-messages="errors.collect('Razón')"
                      :disabled="!departure.group"
                    ></v-select>
                  </v-flex>
                </v-layout>
                <v-textarea
                  v-show="reasonSelected.description_needed"
                  label="Detalle"
                  v-model="departure.description"
                  class="mt-4"
                  rows="3"
                  v-validate="reasonSelected.description_needed ? 'required' : ''"
                  name="Detalle"
                  :error-messages="errors.collect('Detalle')"
                  :disabled="!departure.departure_reason_id"
                ></v-textarea>
              </v-card-text>

              <v-alert
                :value="error.value"
                type="error"
              >
                {{ error.text }}
              </v-alert>
            </v-card>
          </v-stepper-content>

          <v-stepper-content step="2">
            <v-layout>
              <v-flex xs4>
                <v-card v-show="reasonSelected.options" class="text-xs-center">
                  <v-toolbar-title>Opciones</v-toolbar-title>
                  <v-card-actions>
                    <v-radio-group class="justify-center" column v-model="departure.timeToAdd">
                      <v-radio
                        v-for="option in reasonSelected.options"
                        :key="option.value"
                        :label="option.text"
                        :value="option.value"
                      ></v-radio>
                    </v-radio-group>
                  </v-card-actions>
                </v-card>
                <v-card v-show="reasonSelected.records" class="text-xs-center">
                  <v-toolbar-title>Marcado</v-toolbar-title>
                  <v-card-actions>
                    <v-radio-group
                      class="justify-center"
                      column
                      v-model="departure.record"
                    >
                      <v-radio
                        v-for="record in reasonSelected.records"
                        :key="record.value"
                        :label="record.text"
                        :value="record.value"
                      ></v-radio>
                    </v-radio-group>
                  </v-card-actions>
                </v-card>
              </v-flex>
              <v-flex xs8>
                <v-layout>
                  <v-flex xs6 v-show="reasonSelected.period && departure.record < 3">
                    <v-card class="text-xs-center" height="150px">
                      <v-toolbar-title>Período</v-toolbar-title>
                      <v-card-actions>
                        <v-radio-group
                          class="justify-center"
                          column
                          v-model="departure.period"
                          v-validate="(step == 2 && departure.timeToAdd == -1) ? 'required' : ''"
                          name="Período"
                          :error-messages="errors.collect('Período')"
                        >
                          <v-radio
                            v-for="period in periods"
                            :key="period.value"
                            :label="period.text"
                            :value="period.value"
                          ></v-radio>
                        </v-radio-group>
                      </v-card-actions>
                    </v-card>
                  </v-flex>
                  <v-flex :class="(departure.record!=3 && departure.record) ? 'xs6' : 'xs12'" v-show="reasonSelected.date.start.visible || reasonSelected.date.end.visible">
                    <v-card class="text-xs-center" height="150px">
                      <v-toolbar-title>Fecha</v-toolbar-title>
                      <v-card-actions>
                        <v-menu
                          v-show="reasonSelected.date.start.visible"
                          v-model="showStartDate"
                          :close-on-content-click="false"
                          :nudge-right="40"
                          lazy
                          transition="scale-transition"
                          offset-y
                          full-width
                          max-width="290px"
                          min-width="290px"
                          :disabled="!reasonSelected.date.start.editable"
                        >
                          <template v-slot:activator="{ on }">
                            <v-text-field
                              v-model="startDateFormatted"
                              label="Fecha de Solicitud"
                              hint="día/mes/año"
                              persistent-hint
                              prepend-icon="event"
                              readonly
                              v-on="on"
                            ></v-text-field>
                          </template>
                          <v-date-picker v-model="departure.departure" no-title @input="showStartDate = false" locale="es-bo"></v-date-picker>
                        </v-menu>
                        <v-menu
                          v-if="reasonSelected.date.end.visible && departure.timeToAdd == -1"
                          v-model="showEndDate"
                          :close-on-content-click="false"
                          :nudge-right="40"
                          lazy
                          transition="scale-transition"
                          offset-y
                          full-width
                          max-width="290px"
                          min-width="290px"
                        >
                          <template v-slot:activator="{ on }">
                            <v-text-field
                              v-model="endDateFormatted"
                              label="Fecha de Retorno"
                              hint="día/mes/año"
                              persistent-hint
                              prepend-icon="event"
                              readonly
                              v-on="on"
                              v-validate="departure.timeToAdd == -1 ? 'required' : ''"
                              name="Fecha de Retorno"
                              :error-messages="errors.collect('Fecha de Retorno')"
                            ></v-text-field>
                          </template>
                          <v-date-picker v-model="departure.return" no-title @input="showEndDate = false" :min="$moment(departure.departure).startOf('day').add('days', 1).format('YYYY-MM-DD')" locale="es-bo"></v-date-picker>
                        </v-menu>
                      </v-card-actions>
                    </v-card>
                  </v-flex>
                </v-layout>
                <v-card v-show="reasonSelected.time.start.visible || reasonSelected.time.end.visible" class="text-xs-center mt-5" height="158px">
                  <v-toolbar-title>Hora</v-toolbar-title>
                  <v-card-actions>
                    <v-layout wrap>
                      <v-flex xs6>
                        <v-layout wrap>
                          <v-flex xs6>
                            <v-text-field
                              v-model="departure.time.start.hours"
                              step="1"
                              label="Salida"
                              hint="(24 hrs.)"
                              prepend-icon="alarm"
                              type="number"
                              name="Hora de salida"
                              v-validate="limitHour"
                              :min="departure.period == 1 ? '8' : '14'"
                              :max="departure.period == 1 ? '12' : '18'"
                              class="right-input pl-1"
                              :readonly="!reasonSelected.time.start.editable && departure.record != 3"
                            ></v-text-field>
                          </v-flex>
                          <v-flex xs1 class="mt-3 pt-1">
                            <span class="title font-weight-black">:</span>
                          </v-flex>
                          <v-flex xs5>
                            <v-text-field
                              v-model="departure.time.start.minutes"
                              step="1"
                              type="number"
                              name="Hora de salida"
                              v-validate="step == 2 ? 'required|min_value:0|max_value:59' : ''"
                              min="0"
                              max="59"
                              class="pr-4"
                              :disabled="!departure.time.start.hours"
                              :readonly="!reasonSelected.time.start.editable && departure.record != 3"
                            ></v-text-field>
                          </v-flex>
                        </v-layout>
                      </v-flex>
                      <v-flex xs6>
                        <v-layout wrap>
                          <v-flex xs6>
                            <v-text-field
                              v-model="departure.time.end.hours"
                              step="1"
                              label="Retorno"
                              hint="(24 hrs.)"
                              prepend-icon="alarm"
                              type="number"
                              name="Hora de retorno"
                              v-validate="limitHour"
                              :min="departure.period == 1 ? '8' : '14'"
                              :max="departure.period == 1 ? '12' : '18'"
                              class="right-input pl-1"
                              :readonly="(!reasonSelected.time.end.editable && departure.record != 3) || departure.timeToAdd > 0"
                            ></v-text-field>
                          </v-flex>
                          <v-flex xs1 class="mt-3 pt-1">
                            <span class="title font-weight-black">:</span>
                          </v-flex>
                          <v-flex xs5>
                            <v-text-field
                              v-model="departure.time.end.minutes"
                              step="1"
                              type="number"
                              name="Hora de retorno"
                              v-validate="step == 2 ? `required|min_value:0|max_value:59` : ''"
                              min="0"
                              max="59"
                              :disabled="!departure.time.end.hours"
                              :readonly="(!reasonSelected.time.end.editable && departure.record != 3) || departure.timeToAdd > 0"
                            ></v-text-field>
                          </v-flex>
                        </v-layout>
                      </v-flex>
                    </v-layout>
                  </v-card-actions>
                  <v-layout wrap class="caption red--text">
                    <v-flex xs6 v-if="errors.has('Hora de salida')">{{ errors.first('Hora de salida') }}</v-flex>
                    <v-flex xs5 offset-xs1 v-if="errors.has('Hora de retorno')">{{ errors.first('Hora de retorno') }}</v-flex>
                  </v-layout>
                </v-card>
              </v-flex>
            </v-layout>
          </v-stepper-content>
        </v-stepper-items>
      </v-stepper>

      <div class="text-xs-right mt-3">
        <v-btn color="primary" v-if="step > 1" @click.native="step -= 1; clearForm()" :disabled="loading">
          Volver
        </v-btn>
        <v-btn color="error" v-if="step < 2 && !error.value" @click.native="nextStep" :disabled="loading">
          Siguiente
        </v-btn>
        <v-btn color="error" v-if="step == 2 && !error.value" @click.native="makeRequest" :disabled="loading">
          Guardar
        </v-btn>
      </div>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: 'Form',
  data() {
    return {
      loading: true,
      dialog: false,
      step: 1,
      groups: [],
      reasons: [],
      birthDate: null,
      showStartDate: false,
      showEndDate: false,
      reasonSelected: {
        description_needed: null,
        name: null,
        date: {
          start: {
            editable: true,
            visible: true,
            default: this.$store.getters.dateNow
          },
          end: {
            editable: false,
            visible: false,
            default: this.$store.getters.dateNow
          }
        },
        time: {
          start: {
            editable: false,
            visible: false,
          },
          end: {
            editable: false,
            visible: false,
          }
        }
      },
      periods: [
        {
          text: 'Mañana',
          value: 1
        }, {
          text: 'Tarde',
          value: 2
        }
      ],
      records: [
        {
          text: 'Sin marcado al ingreso',
          value: 1
        }, {
          text: 'Sin marcado a la salida',
          value: 2
        }, {
          text: 'Con ambos marcados',
          value: 3
        }
      ],
      departure: {
        group: null,
        departure_reason_id: null,
        description: null,
        timeToAdd: 0,
        days: 0,
        departure: this.$store.getters.dateNow,
        return: null,
        time: {
          start: {
            hours: null,
            minutes: null
          },
          end: {
            hours: null,
            minutes: null
          }
        },
        period: null,
        record: null
      },
      error: {
        text: null,
        value: false
      }
    }
  },
  mounted() {
    this.getDepartureGroups()
  },
  computed: {
    limitHour() {
      let min, max
      if (this.step == 2) {
        if (this.departure.record == 3 || this.departure.timeToAdd >= 8 || this.departure.timeToAdd == -1) {
          return 'required'
        } else {
          if (this.departure.period == 1) {
            min = 8
            max = 12
          } else {
            min = 14
            max = 18
          }
        }
      } else {
        return ''
      }
      return `required|min_value:${min}|max_value:${max}`
    },
    groupDescription() {
      let group = this.groups.find(o => o.id == this.departure.group)
      if(group) return group.description
    },
    reasonDescription() {
      let reason = this.reasons.find(o => o.id == this.departure.departure_reason_id)
      if(reason) return reason.description
    },
    startDateFormatted () {
      if (!this.departure.departure) return null
      return this.formatDate(this.departure.departure)
    },
    endDateFormatted () {
      if (!this.departure.return) return null
      return this.formatDate(this.departure.return)
    }
  },
  watch: {
    'departure.timeToAdd' (val) {
      if (this.departure.timeToAdd == -1) {
        this.departure.time.start.hours = 8
        this.departure.time.start.minutes = 0
        this.departure.time.end.hours = 18
        this.departure.time.end.minutes = 30
      } else {
        this.departure.return = null
        this.changeTime()
      }
    },
    'departure.period' (val) {
      this.changeTime()
    },
    'departure.record' (val) {
      this.changeTime()
    },
    async 'departure.time.start.hours'(val) {
      if (val != null) {
        if (this.departure.time.start.minutes == null) {
          if (val == 14) {
            this.departure.time.start.minutes = '30'
          } else {
            this.departure.time.start.minutes = '00'
          }
        }
        this.departure.time.start = await this.formatTime(this.departure.time.start)
      }
      this.addMinutes()
    },
    async 'departure.time.start.minutes'(val) {
      this.departure.time.start = await this.formatTime(this.departure.time.start)
      await this.$validator.validateAll()
      this.addMinutes()
    },
    async 'departure.time.end.hours'(val) {
      if (val != null) {
        if (this.departure.time.end.minutes == null) {
          if (val == 18) {
            this.departure.time.end.minutes = '30'
          } else {
            this.departure.time.end.minutes = '00'
          }
        }
        this.departure.time.end = await this.formatTime(this.departure.time.end)
      }
    },
    async 'departure.time.end.minutes'(val) {
      this.departure.time.end = await this.formatTime(this.departure.time.end)
      await this.$validator.validateAll()
    },
    'departure.group'(val) {
      this.getDepartureReasons(val)
      this.departure.description = null
      this.departure.departure_reason_id = null
      this.reasonSelected = {
        description_needed: null,
        name: null,
        date: {
          start: {
            editable: true,
            visible: true,
            default: this.$store.getters.dateNow
          },
          end: {
            editable: false,
            visible: false,
            default: this.$store.getters.dateNow
          }
        },
        time: {
          start: {
            editable: false,
            visible: false,
          },
          end: {
            editable: false,
            visible: false,
          }
        }
      }
    },
    async 'departure.departure_reason_id'(val) {
      try {
        if (val != null) {
          this.loading = true
          this.error = {
            text: null,
            value: false
          }
          this.reasonSelected = this.reasons.find(o => o.id == val)
          this.reasonSelected.date = {
            start: {
              editable: true,
              visible: true,
              default: this.$store.getters.dateNow
            },
            end: {
              editable: false,
              visible: false,
              default: this.$store.getters.dateNow
            }
          }
          this.reasonSelected.time = {
            start: {
              editable: false,
              visible: false
            },
            end: {
              editable: false,
              visible: false
            }
          }
          this.reasonSelected.period = true
          this.reasonSelected.records = [
            {
              text: 'Sin marcado al ingreso',
              value: 1
            }, {
              text: 'Sin marcado a la salida',
              value: 2
            }, {
              text: 'Con ambos marcados',
              value: 3
            }
          ]
          this.reasonSelected.options = null
          this.reasonSelected.timeRemaining = 0
          let remainingDepartures
          if (this.reasonSelected.reset) {
            remainingDepartures = await this.getRemainingDepartures(val)
            this.reasonSelected.options = remainingDepartures.remaining_departures[this.reasonSelected.reset].options
            this.reasonSelected.timeRemaining = remainingDepartures.remaining_departures[this.reasonSelected.reset].time_remaining
            this.reasonSelected.recordsRemaining = remainingDepartures.remaining_departures[this.reasonSelected.reset].remaining_records
          }

          if (this.reasonSelected.timeRemaining > 0 || this.reasonSelected.reset == null) {
            if (['Permiso por horas'].includes(this.reasonSelected.name)) {
              this.reasonSelected.time = {
                start: {
                  editable: false,
                  visible: true
                },
                end: {
                  editable: false,
                  visible: true
                }
              }
            }
            if (['Cumpleaños'].includes(this.reasonSelected.name)) {
              this.reasonSelected.records = null
            }
            if (['Licencia con goce de haberes', 'Mamografía/Papanicolao', 'Examen de próstata', 'Examen de colón'].includes(this.reasonSelected.name)) {
              this.reasonSelected.records = null
            }
            if (['Licencia sin goce de haberes', 'Viaje', 'Baja médica'].includes(this.reasonSelected.name)) {
              this.reasonSelected.options = [
                {
                  text: 'Media jornada',
                  value: 4
                }, {
                  text: 'Una jornada',
                  value: 8
                }, {
                  text: 'Más de una jornada',
                  value: -1
                }
              ]

              this.reasonSelected.records = null
              this.reasonSelected.date = {
                start: {
                  editable: true,
                  visible: true,
                  default: this.$store.getters.dateNow
                },
                end: {
                  editable: true,
                  visible: true,
                  default: this.$store.getters.dateNow
                }
              }
            }
            if (['Regularización de marcado'].includes(this.reasonSelected.name)) {
              this.departure.timeToAdd = 1
              this.reasonSelected.timeRemaining = this.reasonSelected.recordsRemaining
              this.reasonSelected.options = null
              this.reasonSelected.records = this.reasonSelected.records.filter(o => o.value != 3)
            }
            if (['Diligencia'].includes(this.reasonSelected.name)) {
              this.reasonSelected.options = null
              this.reasonSelected.time = {
                start: {
                  editable: true,
                  visible: true
                },
                end: {
                  editable: true,
                  visible: true
                }
              }
            }
          }

          let message
          if (this.reasonSelected.timeRemaining == 0 && this.reasonSelected.reset) {
            if (this.reasonSelected.reset == 'annually') message = 'No le quedán permisos disponibles para este mes'
            if (this.reasonSelected.reset == 'monthly') message = 'No le quedán licencias disponibles para el año en curso'
            if (this.reasonSelected.name == 'Cumpleaños') {
              let birthDate = remainingDepartures.birth_date
              birthDate = this.birthDate.birth_date
              let dateNow = this.$moment(this.$store.getters.dateNow)
              let startDate = this.$moment(birthDate).year(dateNow.year()).subtract(8, 'days')
              let endDate = this.$moment(birthDate).year(dateNow.year()).add(8, 'days')
              if (!dateNow.isBetween(startDate, endDate)) message = 'No le quedán licencias disponibles para el año en curso'
            }
            this.error = {
              text: message,
              value: true
            }
            this.step = 1
          }
          if (this.reasonSelected.name == 'Cumpleaños') {
            let dateNow = this.$moment(this.$store.getters.dateNow)
            let birthDate = this.$moment(remainingDepartures.birth_date).year(dateNow.year())
            let startDate = birthDate.clone().subtract(8, 'days')
            let endDate = birthDate.clone().add(8, 'days')
            if (!dateNow.isBetween(startDate, endDate)) {
              message = 'No puede solicitar esta licencia aún'
              this.error = {
                text: message,
                value: true
              }
              this.step = 1
            } else if (birthDate.weekday() == 5 || birthDate.weekday() == 6) {
              message = `No puede solicitar esta licencia en ${birthDate.format('dddd')}`
              this.error = {
                text: message,
                value: true
              }
              this.step = 1
            } else {
              this.departure.departure = birthDate.toISOString()
              this.reasonSelected.date = {
                start: {
                  editable: false,
                  visible: true,
                  default: birthDate
                },
                end: {
                  editable: false,
                  visible: false,
                  default: birthDate
                }
              }
            }
          }
          this.loading = false
        }
      } catch (e) {
        console.log(e)
      }
    }
  },
  methods: {
    async makeRequest() {
      let valid = await this.$validator.validateAll()
      let departureDate
      let returnDate = this.$moment(this.departure.return)
      if (valid && !this.loading) {
        departureDate = this.$moment(this.departure.departure).hour(this.departure.time.start.hours).minutes(this.departure.time.start.minutes)
        if (!this.departure.return) returnDate = departureDate.clone()
        returnDate.hour(this.departure.time.end.hours).minutes(this.departure.time.end.minutes)
        console.log({
          departure_reason_id: this.departure.departure_reason_id,
          description: this.departure.description,
          employee_id: this.$store.getters.id,
          departure: departureDate.format('LLL'),
          return: returnDate.format('LLL')
        })
      }
    },
    closeDialog() {
      this.dialog = false
      this.step = 1
      this.clearForm()
    },
    clearForm() {
      this.reasonSelected = {
        description: null,
        name: null,
        date: {
          start: {
            editable: false,
            visible: false
          },
          end: {
            editable: false,
            visible: false
          }
        },
        time: {
          start: {
            editable: false,
            visible: false
          },
          end: {
            editable: false,
            visible: false
          }
        }
      }
      this.departure = {
        group: null,
        departure_reason_id: null,
        description: null,
        minutes: 0,
        hours: 0,
        days: 0,
        departure: this.$store.getters.dateNow,
        return: null,
        time: {
          start: {
            hours: null,
            minutes: null
          },
          end: {
            hours: null,
            minutes: null
          }
        },
        period: null,
        record: null
      }
    },
    addMinutes() {
      if (this.departure.record == 3 && this.departure.time.start.hours != null && this.departure.time.start.minutes != null) {
        let departureDate = this.$moment(this.departure.departure).hours(this.departure.time.start.hours).minutes(this.departure.time.start.minutes)
        let returnDate = departureDate.clone().add('minutes', this.departure.timeToAdd * 60)
        this.departure.time.end.hours = returnDate.hours()
        this.departure.time.end.minutes = returnDate.minutes()
      }
    },
    changeTime() {
      let departureDate, returnDate, timeUnit
      let time = this.departure.timeToAdd
      if (this.reasonSelected.reset == 'monthly') {
        timeUnit = 'minutes'
        time = this.departure.timeToAdd * 60
      } else {
        timeUnit = 'hours'
      }
      switch (this.departure.record) {
        case 3:
          this.departure.time.start.minutes = null
          this.departure.time.start.hours = null
          this.departure.time.end.minutes = null
          this.departure.time.end.hours = null
          break
        case 2:
          if (this.departure.period) {
            this.departure.time.end.hours = this.departure.period == 1 ? 12 : 18
            this.departure.time.end.minutes = this.departure.period == 1 ? 0 : 30
            returnDate = this.$moment(this.departure.departure).hours(this.departure.time.end.hours).minutes(this.departure.time.end.minutes)
            departureDate = returnDate.clone().subtract(timeUnit, time)
            this.departure.time.start.hours = departureDate.hours()
            this.departure.time.start.minutes = departureDate.minutes()
          }
          break
        default:
          if (this.departure.period) {
            this.departure.time.start.hours = this.departure.period == 1 ? 8 : 14
            this.departure.time.start.minutes = this.departure.period == 1 ? 0 : 30
            departureDate = this.$moment(this.departure.departure).hours(this.departure.time.start.hours).minutes(this.departure.time.start.minutes)
            returnDate = departureDate.clone().add(timeUnit, time)
            this.departure.time.end.hours = returnDate.hours()
            this.departure.time.end.minutes = returnDate.minutes()
          }
          if (this.departure.timeToAdd == 8 && timeUnit == 'hours') {
            this.departure.time.start.hours = 8
            this.departure.time.start.minutes = 0
            this.departure.time.end.hours = 18
            this.departure.time.end.minutes = 30
          }
      }
    },
    formatTime(time) {
      return new Promise((resolve, reject) => {
        if (time.hours != null && time.minutes != null) {
          let hours = time.hours.toString()
          hours = hours.padStart(2, '0')
          let minutes = time.minutes.toString()
          minutes = minutes.padStart(2, '0')
          return resolve({
            hours: hours.slice(hours.length-2, hours.length),
            minutes: minutes.slice(minutes.length-2, minutes.length)
          })
        } else {
          return resolve({
            hours: null,
            minutes: null
          })
        }
      })
    },
    formatDate(date) {
      return this.$moment(date).format("DD/MM/YYYY")
    },
    async getRemainingDepartures(reasonId) {
      try {
        let res = await axios.get(`employee/${this.$store.getters.id}`, {
          params: {
            departure_reason: reasonId
          }
        })
        return res.data
      } catch (e) {
        console.log(e)
      }
    },
    async nextStep() {
      try {
        let valid = await this.$validator.validateAll()
        if (valid && !this.loading) {
          this.step += 1
        }
      } catch (e) {
        console.log(e)
      }
    },
    async getDepartureGroups() {
      try {
        this.loading = true
        let res = await axios.get(`departure_group`)
        this.groups = res.data
        this.loading = false
      } catch (e) {
        console.log(e)
      }
    },
    async getDepartureReasons(groupId) {
      try {
        if (groupId != null) {
          this.loading = true
          let res = await axios.get(`departure_group/${groupId}`)
          this.reasons = res.data.departure_reasons
          this.loading = false
        }
      } catch (e) {
        console.log(e)
      }
    }
  }
}
</script>