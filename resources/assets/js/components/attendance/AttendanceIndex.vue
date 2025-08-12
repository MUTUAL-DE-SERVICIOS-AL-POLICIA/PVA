<template>
  <v-container fluid>
    <template v-if="!this.manteinanceMode">
      <v-toolbar v-if="!loading">
        <v-toolbar-title>
          {{ (limits.start && limits.end) ? `Asistencia del ${$moment(limits.start).format('L')} al ${$moment(limits.end).format('L')}` : `Asistencia del mes de ${$moment(date).format('MMMM')}` }}
        </v-toolbar-title>
        <v-tooltip color="white" role="button" bottom>
          <v-icon slot="activator" class="ml-4">info</v-icon>
          <div>
            <v-alert :value="true" type="success" color="green darken-2">INGRESO</v-alert>
            <v-alert :value="true" type="info" color="blue darken-2">SALIDA</v-alert>
            <v-alert :value="true" type="error" color="red darken-2">RETRASO</v-alert>
            <v-alert :value="true" type="warning" color="yellow darken-4">FUERA DE RANGO</v-alert>
          </div>
        </v-tooltip>
        <v-spacer></v-spacer>
        <AttendanceSync v-if="$store.getters.role == 'admin'"/>
        <AttendanceErase v-if="$store.getters.role == 'admin'"/>
        <ReportPrint v-if="['admin', 'rrhh'].includes($store.getters.role)" url="attendance/print"/>
        <v-btn color="primary" @click="showDate = !showDate">
          {{ $moment(this.date).format('MMMM') }}
        </v-btn>
      </v-toolbar>
      <div v-if="loading">
        <Loading/>
      </div>
      <v-flex v-else>
        <v-card class="pa-1" v-if="isAdmin">
          <v-container grid-list-md text-xs-center>
            <v-layout row wrap>
              <v-flex xs10>
                <v-autocomplete
                  :items="employees"
                  item-text="fullName"
                  label="Empleado"
                  v-model="selectedEmployee"
                  item-value="id"
                  clearable
                  prepend-icon="person"
                  :hint="selectedEmployeePosition"
                  persistent-hint
                  flat
                ></v-autocomplete>
              </v-flex>
              <v-flex xs2>
                <template class="justify-center layout" v-if="selectedEmployee">
                  <v-tooltip top>
                    <v-btn medium slot="activator" flat icon color="info" @click="getChecks(selectedEmployee, true)">
                      <v-icon>print</v-icon>
                    </v-btn>
                    <span>Imprimir asistencia del funcionario</span>
                  </v-tooltip>
                  <AttendanceAdd v-if="$store.getters.role == 'admin' && $route.query.add" :id="selectedEmployee" :limits="limits" :bus="bus"></AttendanceAdd>
                </template>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card>
        <Loading v-if="loading"></Loading>
        <v-card v-else-if="checks.length > 0">
          <v-calendar
            :start="limits.start"
            :end="limits.end"
            :now="$store.getters.dateNow"
            :short-months="false"
            locale="es-bo"
            type="custom-weekly"
            :weekdays="[1,2,3,4,5,6,0]"
          >
            <template v-slot:day="{ date }">
              <template v-for="event in departures.filter((o) => { return $moment(date).isBetween($moment(o.from.date).startOf('day'), $moment(o.to.date).endOf('day'), null, '[]') })">
                <div v-if="$moment(date, 'YYYY-MM-DD').isBusinessDay()" class="text-xs text-xs-center white--text grey darken-3 mb-2" :key="event.id">
                  <div>{{ event.reason }} [ {{ event.code }} ]</div>
                  <div>{{ event.from.time }} - {{ event.to.time }}</div>
                  <div>{{ verifyDepartureState(event.approved) }}</div>
                </div>
              </template>
              <template v-for="(event, index) in checks.filter(o => o.date == date)">
                <div class="text-xs-center event subheading grey darken-1 mt-3 mb-3" :key="index+event" v-if="index > 0 && event.shift != checks.filter(o => o.date == date)[index - 1].shift"></div>
                <div class="text-xs-center white--text event subheading" :class="`${event.color} darken-${event.shift}`" :key="index">
                  {{ event.time }}
                </div>
              </template>
            </template>
          </v-calendar>
        </v-card>
        <v-card v-else>
          <v-card-text v-if="!loading">
            <h2 class="red--text">No existen registros de asistencia</h2>
          </v-card-text>
        </v-card>
      </v-flex>
    </template>
    <template v-else>
      <ManteinanceDialog positionGroup="la Unidad de Recursos Humanos"></ManteinanceDialog>
    </template>

    <v-dialog
      v-model="showDate"
      width="460"
    >
      <v-date-picker
        v-model="date"
        :landscape="true"
        type="month"
        locale="es-BO"
        :max="maxDate"
      ></v-date-picker>
    </v-dialog>
  </v-container>
</template>

<script>
import Vue from "vue";
import Loading from '../Loading'
import ManteinanceDialog from "../ManteinanceDialog"
import AttendanceSync from "./AttendanceSync"
import AttendanceErase from "./AttendanceErase"
import AttendanceAdd from "./AttendanceAdd"
import ReportPrint from "../ReportPrint"

export default {
  name: 'AttendanceIndex',
  components: {
    Loading,
    ManteinanceDialog,
    AttendanceSync,
    AttendanceErase,
    AttendanceAdd,
    ReportPrint
  },
  data() {
    return {
      loading: true,
      checks: [],
      departures: [],
      limits: {
        start: null,
        end: null
      },
      bus: new Vue(),
      employees: [],
      selectedEmployee: null,
      selectedEmployeePosition: null,
      date: null,
      showDate: false
    }
  },
  watch: {
    selectedEmployee: function(val) {
      if (val != null) {
        if (typeof val === 'number') this.getChecks(val)
        if (this.selectedEmployee.hasOwnProperty('id')) this.selectedEmployee = this.selectedEmployee.id
        let employee = this.employees.find(o => o.id == this.selectedEmployee)
        if (employee) this.selectedEmployeePosition = employee.position || 'Nombre del empleado'
      }
    },
    date: function(val) {
      if (typeof this.selectedEmployee === 'object') {
        if (this.selectedEmployee === null) {
          this.selectedEmployee = this.$store.getters.id
        } else {
          this.selectedEmployee = this.selectedEmployee.id
        }
      } else {
        this.getChecks(this.selectedEmployee)
      }
      this.showDate = false
    },
    checks: function(val) {
      if (val.length == 0) {
        this.limits = {
          start: null,
          end: null
        }
      }
    }
  },
  computed: {
    manteinanceMode() {
      return JSON.parse(process.env.MIX_ATTENDANCE_MANTEINANCE_MODE)
    },
    isAdmin() {
      return this.$store.getters.role == 'admin' || this.$store.getters.role == 'rrhh'
    },
    maxDate() {
      let now = this.$moment(this.$store.getters.dateNow)
      if (now.date() <= 20) {
        return now.format()
      } else {
        now = now.add(1, 'months').date(1).format('YYYY-MM-DD')
        if (!this.date) {
          this.date = now
        }
        return now
      }
    }
  },
  mounted() {
    this.date = this.$store.getters.dateNow
    if (this.isAdmin) {
      this.getEmployees()
    } else {
      this.getChecks()
    }
    this.bus.$on("newCheck", (val) => {
      this.getChecks(this.selectedEmployee)
    })
  },
  methods: {
    verifyDepartureState(state) {
      if (state === null) {
        return 'PENDIENTE'
      } else if (state === true) {
        return 'APROBADO'
      } else if (state === false) {
        return 'RECHAZADO'
      }
    },
    async getDepartures(id) {
      try {
        this.loading = true
        let res = await axios.get(`employee/${id}/departure`, {
          params: {
            month: this.date
          }
        })
        this.departures = res.data
      } catch (e) {
        console.log(e)
        this.departures = []
      } finally {
        this.loading = false
      }
    },
    async getChecks(id = this.$store.getters.id, print = false) {
      try {
        this.loading = true
        let res = await axios.get(`employee/${id}/attendance`, {
          responseType: print ? 'arraybuffer' : 'json',
          params: {
            month: this.date,
            type: print ? 'pdf' : 'json',
            with_discounts: print
          }
        })
        if (print) {
          let blob = new Blob([res.data], {
            type: "application/pdf"
          });
          printJS(window.URL.createObjectURL(blob));
        } else {
          this.checks = res.data.checks
          this.limits = {
            start: res.data.from,
            end: res.data.to
          }
          this.getDepartures(id)
        }
      } catch (e) {
        console.log(e)
        this.checks = []
      } finally {
        this.loading = false
      }
    },
    async getEmployees() {
      try {
        let res = await axios.get(`employee`)
        this.employees = res.data.filter(o => o.active)
        this.employees.forEach(e => {
          e.fullName = `${e.last_name || ''} ${e.mothers_last_name || ''} ${e.first_name || ''} ${e.second_name || ''}`
        });
        this.loading = false
      } catch (e) {
        console.log(e)
        this.loading = false
      }
    }
  }
}
</script>

<style>
  .event {
    overflow: hidden;
    border-radius: 8px;
    width: 50%;
    padding: 3px;
    margin-left: 25%;
    margin-bottom: 6px;
  }
</style>