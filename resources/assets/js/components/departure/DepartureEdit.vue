<template>
  <v-dialog
    v-model="dialog"
    max-width="700px"
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
                <v-layout v-if="!updateDeparture">
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
                <v-text-field
                  v-show="reasonSelected.note"
                  label="Cite"
                  v-model="departure.cite"
                  class="mt-4"
                  rows="1"
                  v-validate="reasonSelected.note ? 'required' : ''"
                  name="Cite"
                  :error-messages="errors.collect('Cite')"
                  :disabled="!departure.departure_reason_id"
                ></v-text-field>
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

          <v-stepper-content step="2" v-show="!updateDeparture">
            <v-layout row justify-space-between>
              <v-flex grow v-show="reasonSelected.options">
                <v-card flat class="text-xs-center">
                  <v-toolbar-title>Opciones</v-toolbar-title>
                  <v-card-actions>
                    <v-radio-group
                      class="justify-center"
                      column
                      v-model="departure.timeToAdd"
                      v-validate="(step == 2 && Array.isArray(reasonSelected.options)) ? 'required' : ''"
                      name="Opciones"
                      :error-messages="errors.collect('Opciones')"
                    >
                      <v-radio
                        v-for="option in reasonSelected.options"
                        :key="option.value"
                        :label="option.text"
                        :value="option.value"
                      ></v-radio>
                    </v-radio-group>
                  </v-card-actions>
                </v-card>
              </v-flex>
              <v-flex xs-4 v-show="reasonSelected.period && departure.record < 3 && departure.timeToAdd > 0 && departure.timeToAdd < 8">
                <v-card flat class="text-xs-center">
                  <v-toolbar-title>Período</v-toolbar-title>
                  <v-card-actions>
                    <v-radio-group
                      class="justify-center"
                      column
                      v-model="departure.period"
                      v-validate="(step == 2 && reasonSelected.period && departure.record < 3 && departure.timeToAdd > 0 && departure.timeToAdd < 8) ? 'required' : ''"
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
              <v-flex grow v-show="reasonSelected.date.start.visible || reasonSelected.date.end.visible">
                <v-card flat class="text-xs-center">
                  <v-toolbar-title>Fecha</v-toolbar-title>
                  <v-card-actions>
                    <v-layout column>
                      <v-flex v-show="reasonSelected.date.start.visible">
                        <v-menu
                          v-model="showStartDate"
                          :close-on-content-click="false"
                          transition="scale-transition"
                          offset-y
                          max-width="290px"
                          min-width="290px"
                        >
                          <template v-slot:activator="{ on }">
                            <v-text-field
                              v-model="startDateFormatted"
                              label="Fecha de Salida"
                              hint="día/mes/año"
                              persistent-hint
                              prepend-icon="event"
                              v-on="on"
                              v-validate="(step == 2 && reasonSelected.date.start.editable) ? 'required' : ''"
                              name="Fecha de Salida"
                              :error-messages="errors.collect('Fecha de Salida')"
                            ></v-text-field>
                          </template>
                          <v-date-picker
                            v-model="departure.departure"
                            no-title
                            @input="showStartDate = false"
                            locale="es-bo"
                            first-day-of-week="1"
                          ></v-date-picker>
                        </v-menu>
                      </v-flex>
                      <v-flex mt-4 v-show="(reasonSelected.date.end.visible && departure.timeToAdd == -1) || reasonSelected.name == 'DOCENCIA, BECAS, CURSOS, SEMINARIOS, POSTGRADOS'">
                        <v-menu
                          v-model="showEndDate"
                          :close-on-content-click="false"
                          transition="scale-transition"
                          offset-y
                          full-width
                          max-width="290px"
                          min-width="290px"
                          :disabled="!reasonSelected.date.end.editable"
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
                              v-validate="step == 2 ? (departure.timeToAdd == -1 || reasonSelected.name == 'DOCENCIA, BECAS, CURSOS, SEMINARIOS, POSTGRADOS') ? 'required' : '' : ''"
                              name="Fecha de Retorno"
                              :error-messages="errors.collect('Fecha de Retorno')"
                            ></v-text-field>
                          </template>
                          <v-date-picker
                            v-model="departure.return"
                            no-title
                            @input="showEndDate = false"
                            :min="$moment(departure.departure).startOf('day').add('days', 1).format('YYYY-MM-DD')"
                            locale="es-bo"
                            first-day-of-week="1"
                          ></v-date-picker>
                        </v-menu>
                      </v-flex>
                    </v-layout>
                  </v-card-actions>
                </v-card>
              </v-flex>
            </v-layout>
            <v-layout mt-4>
              <v-flex xs-6 v-show="reasonSelected.records">
                <v-card flat class="text-xs-center">
                  <v-toolbar-title>Marcado</v-toolbar-title>
                  <v-card-actions>
                    <v-radio-group
                      class="justify-center"
                      column
                      v-model="departure.record"
                      v-validate="(step == 2 && reasonSelected.records) ? 'required' : ''"
                      name="Marcado"
                      :error-messages="errors.collect('Marcado')"
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
              <v-flex xs-6 v-show="reasonSelected.time.start.visible || reasonSelected.time.end.visible">
                <v-card flat class="text-xs-center">
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
                              name="Salida"
                              v-validate="limitHour"
                              :min="departure.period == 1 ? '8' : '14'"
                              :max="departure.period == 1 ? '12' : '18'"
                              class="right-input pl-1"
                              :readonly="!reasonSelected.time.start.editable && departure.record != 3"
                              :error-messages="errors.collect('Salida')"
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
                              name="Salida"
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
                              name="Retorno"
                              v-validate="limitHour"
                              :min="departure.period == 1 ? '8' : '14'"
                              :max="departure.period == 1 ? '12' : '18'"
                              class="right-input pl-1"
                              :readonly="(!reasonSelected.time.end.editable && departure.record != 3) || (departure.timeToAdd > 0 && reasonSelected.options != null)"
                              :error-messages="errors.collect('Retorno')"
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
                              name="Retorno"
                              v-validate="step == 2 ? `required|min_value:0|max_value:59` : ''"
                              min="0"
                              max="59"
                              :disabled="!departure.time.end.hours"
                              :readonly="(!reasonSelected.time.end.editable && departure.record != 3) || (departure.timeToAdd > 0 && reasonSelected.options != null)"
                            ></v-text-field>
                          </v-flex>
                        </v-layout>
                      </v-flex>
                    </v-layout>
                  </v-card-actions>
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
        <v-btn color="error" v-if="step < 2 && !error.value && !updateDeparture" @click.native="nextStep" :disabled="loading">
          Siguiente
        </v-btn>
        <v-btn color="error" v-if="(step == 2 && !error.value) || updateDeparture" @click.native="makeRequest" :disabled="loading">
          Imprimir
        </v-btn>
      </div>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: 'Form',
  props: ['bus'],
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
      updateDeparture: false,
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
          text: 'Ingreso',
          value: 1
        }, {
          text: 'Salida',
          value: 2
        }, {
          text: 'Definir hora',
          value: 3
        }
      ],
      departure: {
        group: null,
        departure_reason_id: null,
        description: null,
        cite: null,
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
      },
      contract: null
    }
  },
  mounted() {
    this.getDepartureGroups()
    this.bus.$on('updateDeparture', departure => {
      this.step = 1
      this.clearForm()
      this.updateDeparture = true
      this.getDepartureReason(departure.departure_reason_id)
      this.departure.departure_reason_id = departure.departure_reason_id
      this.departure.description = departure.description
      this.departure.cite = departure.cite
      this.departure.id = departure.id
      this.dialog = true
    })
    if (!this.departure.departure_reason_id) {
      if (this.$route.query.departureType == 'user') this.getContract()
    }
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
    'departure.cite'(val) {
      if (this.departure.cite) this.departure.cite = val.toUpperCase()
    },
    'departure.timeToAdd' (val) {
      if (this.departure.timeToAdd == -1) {
        this.departure.time.start.hours = 8
        this.departure.time.start.minutes = 30
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
      if (!this.updateDeparture) {
        this.departure.description = null
        this.departure.cite = null
      }
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
      this.error = {
        text: null,
        value: false
      }
    },
    'departure.departure'(val) {
      let inWeekend = !this.$moment(val).isBusinessDay()
      /*if (val && !this.reasonSelected.days) {
        if (inWeekend) {
          this.toastr.error('No se puede solicitar licencia en día Sábado ni Domingo')
          this.departure.departure = null
        }
      }*/
      if (val && this.reasonSelected.days) {
        if (this.reasonSelected.name != 'MATERNIDAD') {
          this.departure.return = this.$moment(val).startOf('day').businessAdd(this.reasonSelected.days - (inWeekend ? 0 : 1)).format('YYYY-MM-DD')
        } else {
          this.departure.return = this.$moment(val).startOf('day').add('days', this.reasonSelected.days - (inWeekend ? 0 : 1)).format('YYYY-MM-DD')
        }
      }
    },
    async 'departure.departure_reason_id'(val) {
      this.error = {
        text: null,
        value: false
      }
      if (!this.updateDeparture) {
        this.departure.description = null
        this.departure.cite = null
      }
      try {
        if (val != null && !this.updateDeparture) {
          this.loading = true
          this.error = {
            text: null,
            value: false
          }
          this.reasonSelected = this.reasons.find(o => o.id == val)
          this.reasonSelected.date = {
            start: {
              editable: true,
              visible: true
            },
            end: {
              editable: false,
              visible: false
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
              text: 'Ingreso',
              value: 1
            }, {
              text: 'Salida',
              value: 2
            }, {
              text: 'Definir hora',
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
            if (['PERMISO POR HORAS'].includes(this.reasonSelected.name)) {
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
            if (['CUMPLEAÑOS', 'JURADO ELECTORAL'].includes(this.reasonSelected.name)) {
              this.reasonSelected.records = null
              if (this.reasonSelected.name == 'JURADO ELECTORAL') {
                this.departure.description = 'Adjunto respaldo del Tribunal Supremo Electoral'
                this.reasonSelected.options = [
                  {
                    text: 'Una jornada',
                    value: 8
                  }
                ]
              } else {
                this.departure.description = 'Adjunto fotocopia de Cédula de Identidad'
              }
            }
            if (['CON GOCE DE HABERES', 'MAMOGRAFÍA/PAPANICOLAOU', 'EXAMEN DE PRÓSTATA', 'EXAMEN DE COLON'].includes(this.reasonSelected.name)) {
              this.reasonSelected.records = null
            }
            if (['SIN GOCE DE HABERES', 'VIAJE', 'BAJA MÉDICA'].includes(this.reasonSelected.name)) {
              if (this.reasonSelected.name == 'VIAJE') {
                this.departure.description = 'Adjunto memorándum de designación'
              }
              if (this.reasonSelected.name == 'BAJA MÉDICA') {
                this.departure.description = 'Adjunto documento de respaldo correspondiente'
              }
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
                  visible: true
                },
                end: {
                  editable: true,
                  visible: true
                }
              }
            }
            if (['REGULARIZACIÓN DE MARCADO'].includes(this.reasonSelected.name)) {
              this.departure.timeToAdd = 1
              this.reasonSelected.timeRemaining = this.reasonSelected.recordsRemaining
              this.reasonSelected.options = null
              this.reasonSelected.records = this.reasonSelected.records.filter(o => o.value != 3)
            }
            if (['TRABAJO', 'REUNIÓN', 'CURSO/TALLER', 'DOCENCIA, BECAS, CURSOS, SEMINARIOS, POSTGRADOS', 'CONSULTA MÉDICA', 'ACTIVIDAD CULTURAL O DEPORTIVA'].includes(this.reasonSelected.name)) {
              if (this.reasonSelected.name == 'ACTIVIDAD CULTURAL O DEPORTIVA') {
                this.departure.description = 'Adjunto documento de respaldo correspondiente'
              }
              if (this.reasonSelected.name == 'CONSULTA MÉDICA') {
                this.departure.description = 'Adjunto justificativo de la C.N.S.'
              }
              if (this.reasonSelected.name == 'DOCENCIA, BECAS, CURSOS, SEMINARIOS, POSTGRADOS') {
                this.reasonSelected.date = {
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
              this.departure.timeToAdd = 2
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
            if (['MATRIMONIO', 'NACIMIENTO DE HIJOS', 'MATERNIDAD', 'FALLECIMIENTO DE PADRES, ABUELOS, CONYUGE, HERMANOS O HIJOS - NIETOS O PARENTESCO ADOPTIVO', 'FALLECIMIENTO DE SUEGROS O CUÑADOS', ''].includes(this.reasonSelected.name)) {
              if (this.reasonSelected.name == 'MATERNIDAD') {
                this.departure.description = 'Adjunto documento de baja médica'
              }
              this.departure.timeToAdd = -1
              this.reasonSelected.records = null
              this.reasonSelected.period = false
              this.reasonSelected.date = {
                start: {
                  editable: true,
                  visible: true
                },
                end: {
                  editable: false,
                  visible: true
                }
              }
              this.departure.departure = null
            }
          }

          let message
          if (this.reasonSelected.timeRemaining == 0 && this.reasonSelected.reset) {
            if (this.reasonSelected.reset == 'monthly') message = 'No le quedán permisos disponibles para este mes'
            if (this.reasonSelected.reset == 'annually') message = 'No le quedán licencias disponibles para el año en curso'
            if (this.reasonSelected.name == 'CUMPLEAÑOS') {
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
          if (this.reasonSelected.name == 'CUMPLEAÑOS') {
            let dateNow = this.$moment(this.$store.getters.dateNow)
            let birthDate = this.$moment(remainingDepartures.birth_date).year(dateNow.year())
            let startDate = birthDate.clone().subtract(8, 'days')
            let endDate = birthDate.clone().add(8, 'days')
            
              this.departure.departure = birthDate.toISOString()
              this.reasonSelected.date = {
                start: {
                  editable: false,
                  visible: true
                },
                end: {
                  editable: false,
                  visible: false
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
    async getContract() {
      try {
        this.loading = true
        let res = await axios.get(`employee/${this.$store.getters.id}/contract`)
        this.contract = res.data
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },
    async makeRequest() {
      let res
      let valid = await this.$validator.validateAll()
      if (valid && !this.loading) {
        try {
          if (!this.updateDeparture) {
            let departureDate
            let returnDate = this.$moment(this.departure.return)
            departureDate = this.$moment(this.departure.departure).hour(this.departure.time.start.hours).minutes(this.departure.time.start.minutes)
            if (!this.departure.return) returnDate = departureDate.clone()
            returnDate.hour(this.departure.time.end.hours).minutes(this.departure.time.end.minutes)
            res = await axios.post(`departure`, {
              departure_reason_id: this.departure.departure_reason_id,
              description: this.departure.description,
              cite: this.departure.cite,
              employee_id: this.$store.getters.id,
              departure: departureDate.format(),
              return: returnDate.format()
            })
          } else {
            res = await axios.patch(`departure/${this.departure.id}`, {
              cite: this.departure.cite,
              description: this.departure.description
            })
          }
        }
          catch (e) {
          console.log(e)
        } finally {
          this.bus.$emit('printDeparture', res.data.id)
          this.closeDialog()
        }
      }
    },
    closeDialog() {
      this.clearForm()
      this.step = 1
      this.dialog = false
    },
    clearForm() {
      this.updateDeparture = false
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
        cite: null,
        minutes: 0,
        hours: 0,
        days: 0,
        departure: this.$store.getters.dateNow,
        return: null,
        time: {
          start: {
            hours: 8,
            minutes: 30
          },
          end: {
            hours: 18,
            minutes: 30
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
            this.departure.time.end.minutes = this.departure.period == 1 ? 30 : 30
            returnDate = this.$moment(this.departure.departure).hours(this.departure.time.end.hours).minutes(this.departure.time.end.minutes)
            departureDate = returnDate.clone().subtract(timeUnit, time)
            this.departure.time.start.hours = departureDate.hours()
            this.departure.time.start.minutes = departureDate.minutes()
          }
          break
        default:
          if (this.departure.period) {
            this.departure.time.start.hours = this.departure.period == 1 ? 8 : 14
            this.departure.time.start.minutes = this.departure.period == 1 ? 30 : 30
            departureDate = this.$moment(this.departure.departure).hours(this.departure.time.start.hours).minutes(this.departure.time.start.minutes)
            returnDate = departureDate.clone().add(timeUnit, time)
            this.departure.time.end.hours = returnDate.hours()
            this.departure.time.end.minutes = returnDate.minutes()
          }
          if (this.departure.timeToAdd == 8 && timeUnit == 'hours') {
            this.departure.time.start.hours = 8
            this.departure.time.start.minutes = 30
            this.departure.time.end.hours = 18
            this.departure.time.end.minutes = 30
          }
      }
    },
    formatTime(time) {
      if (this.departure.time.start.hours > 23 || this.departure.time.start.hours < 0 || this.departure.time.end.hours > 23 || this.departure.time.end.hours < 0 || this.departure.time.start.minutes > 59 || this.departure.time.start.minutes < 0 || this.departure.time.end.minutes > 59 || this.departure.time.end.minutes < 0) {
        this.error.value = true
        this.toastr.error('Formato de hora inválido')
      } else {
        this.error.value = false
      }
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
          if (this.reasonSelected.note) {
            let res = await axios.get(`departure/verify/cite`, {
              params: {
                cite: this.departure.cite
              }
            })
          }
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
    },
    async getDepartureReason(reasonId) {
      try {
        this.loading = true
        let res = await axios.get(`departure_reason/${reasonId}`)
        this.reasonSelected = res.data
        if (!res.data.hasOwnProperty('date')) {
          this.reasonSelected.date = {
            start: {
              editable: false,
              visible: false
            },
            end: {
              editable: false,
              visible: false
            }
          },
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
        }
        this.loading = false
      } catch (e) {
        console.log(e)
      }
    }
  }
}
</script>