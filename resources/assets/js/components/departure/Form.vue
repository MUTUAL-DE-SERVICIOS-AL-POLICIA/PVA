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
        <v-toolbar-title class="white--text">Solicitud de Salida</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn icon dark @click.native="dialog = false; step = 1">
          <v-icon>close</v-icon>
        </v-btn>
      </v-toolbar>

      <v-stepper v-model="step">
        <v-stepper-header>
          <v-stepper-step :complete="step > 1" step="1">Razón</v-stepper-step>
          <v-divider></v-divider>
          <v-stepper-step :complete="step > 2" step="2">Opciones disponibles</v-stepper-step>
          <v-divider></v-divider>
          <v-stepper-step step="3">Name of step 3</v-stepper-step>
        </v-stepper-header>

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
                  v-show="departure.description_needed"
                  label="Detalle"
                  v-model="departure.description"
                  class="mt-4"
                  rows="3"
                  v-validate="departure.description_needed ? 'required' : ''"
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
              <v-flex xs6>
                <v-card class="text-xs-center">
                  <v-toolbar-title>Opciones</v-toolbar-title>
                  <v-card-actions>
                    <v-radio-group class="justify-center" column v-model="departure.minutes">
                      <v-radio v-for="option in remainingDepartures.monthly.options" :key="option.value" :label="`${option.text} hrs`" :value="option.value"></v-radio>
                    </v-radio-group>
                  </v-card-actions>
                </v-card>
                <v-card class="text-xs-center" v-show="departure.minutes > 0">
                  <v-toolbar-title>Fecha</v-toolbar-title>
                  <v-card-actions>
                    <v-menu
                      v-model="showStartDate"
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
                  </v-card-actions>
                </v-card>
              </v-flex>
              <v-flex xs6>
                <v-card v-show="departure.departure" class="text-xs-center">
                  <v-toolbar-title>Período</v-toolbar-title>
                  <v-card-actions>
                    <v-radio-group class="justify-center" column v-model="departure.period">
                      <v-radio v-for="period in periods" :key="period.value" :label="period.text" :value="period.value"></v-radio>
                    </v-radio-group>
                  </v-card-actions>
                </v-card>
                <v-card v-show="departure.period" class="text-xs-center">
                  <v-toolbar-title>Hora</v-toolbar-title>
                  <v-card-actions>

                  </v-card-actions>
                </v-card>
              </v-flex>
            </v-layout>
          </v-stepper-content>

          <v-stepper-content step="3">
            <v-card
              class="mb-5"
              color="grey lighten-3"
              height="400px"
            ></v-card>
          </v-stepper-content>
        </v-stepper-items>
      </v-stepper>

      <div class="text-xs-right mt-3">
        <v-btn color="primary" v-if="step > 1" @click.native="step -= 1">
          Volver
        </v-btn>
        <v-btn color="error" v-if="step < 3 && !error.value" @click.native="nextStep" :disabled="loading">
          Siguiente
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
      periods: [
        {
          text: 'Mañana',
          value: 1
        }, {
          text: 'Tarde',
          value: 2
        }
      ],
      remainingDepartures: {
        monthly: {
          time_remaining: 0,
          options: []
        },
        annually: {
          time_remaining: 0,
          options: []
        }
      },
      departure: {
        description_needed: false,
        group: null,
        departure_reason_id: null,
        description: null,
        minutes: 0,
        hours: 0,
        days: 0,
        departure: null,
        period: null
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
    }
  },
  watch: {
    'departure.group'(val) {
      this.getDepartureReasons(val)
      this.departure.description = null
      this.departure.departure_reason_id = null
      this.departure.description_needed = false
    },
    async 'departure.departure_reason_id'(val) {
      try {
        this.loading = true
        this.error = {
          text: null,
          value: false
        }
        this.departure.description_needed = this.reasons.find(o => o.id == val).description_needed
        this.departure.description = null
        let reason = this.reasons.find(o => o.id == val)
        switch(reason.name) {
          case 'Permiso por horas':
            this.remainingDepartures = await this.getRemainingDepartures()
            this.remainingDepartures = this.remainingDepartures.remaining_departures
            if (this.remainingDepartures.monthly.time_remaining == 0) {
              this.error = {
                text: 'No le quedán permisos disponibles para este mes',
                value: true
              }
              this.step = 1
            }
            break
          case 'Licencia con goce de haberes':
            this.remainingDepartures = await this.getRemainingDepartures()
            this.remainingDepartures = this.remainingDepartures.remaining_departures
            if (this.remainingDepartures.annually.time_remaining == 0) {
              this.error = {
                text: 'No le quedán licencias con goce de haberes disponibles el año en curso',
                value: true
              }
              this.step = 1
            }
            break
          case 'Cumpleaños':
            this.birthDate = await this.getRemainingDepartures()
            this.birthDate = this.birthDate.birth_date
            let dateNow = this.$moment(this.$store.getters.dateNow)
            let startDate = this.$moment(this.birthDate).year(dateNow.year()).subtract(8, 'days')
            let endDate = this.$moment(this.birthDate).year(dateNow.year()).add(8, 'days')
            if (!dateNow.isBetween(startDate, endDate)) {
              this.error = {
                text: 'Aún no puede solicitar tolerancia por su cumpleaños',
                value: true
              }
              this.step = 1
            }
            break
        }
        this.loading = false
      } catch (e) {
        console.log(e)
      }
    }
  },
  methods: {
    formatDate(date) {
      return this.$moment(date).format("DD/MM/YYYY")
    },
    async getRemainingDepartures() {
      try {
        let res = await axios.get(`employee/${this.$store.getters.id}`)
        return res.data
      } catch (e) {
        console.log(e)
      }
    },
    async nextStep() {
      try {
        let valid = await this.$validator.validateAll();
        if (valid) {
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
        this.loading = true
        let res = await axios.get(`departure_group/${groupId}`)
        this.reasons = res.data.departure_reasons
        this.loading = false
      } catch (e) {
        console.log(e)
      }
    }
  }
}
</script>



