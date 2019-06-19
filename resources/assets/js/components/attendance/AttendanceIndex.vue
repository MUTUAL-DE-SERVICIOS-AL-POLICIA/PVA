<template>
  <v-container fluid>
    <template v-if="!this.manteinanceMode">
      <v-toolbar>
        <v-toolbar-title>
          {{ `Asistencia del ${$moment(limits.start).format('L')} al ${$moment(limits.end).format('L')}` }}
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
        <AttendanceSync v-if="isAdmin"/>
        <AttendanceErase v-if="isAdmin"/>
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
              <v-flex xs11>
                <v-autocomplete
                  :items="employees"
                  item-text="fullName"
                  label="Empleado"
                  v-model="selectedEmployee"
                  item-value="id"
                  clearable
                  prepend-icon="person"
                  hint="Nombre del empleado"
                  persistent-hint
                ></v-autocomplete>
              </v-flex>
              <v-flex xs1>
                <AttendanceAdd v-if="isAdmin" :id="selectedEmployee" :limits="limits" :bus="bus"></AttendanceAdd>
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
            <h2 class="red--text">No hay registros de asistencia</h2>
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
        :max="$store.getters.dateNow"
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

export default {
  name: 'AttendanceIndex',
  components: {
    Loading,
    ManteinanceDialog,
    AttendanceSync,
    AttendanceErase,
    AttendanceAdd
  },
  data() {
    return {
      loading: true,
      checks: [],
      limits: {
        start: null,
        end: null
      },
      bus: new Vue(),
      employees: [],
      selectedEmployee: null,
      date: this.$store.getters.dateNow,
      showDate: false
    }
  },
  watch: {
    selectedEmployee: function(val) {
      if (typeof val === 'number') this.getChecks(val)
      if (this.selectedEmployee.hasOwnProperty('id')) this.selectedEmployee = this.selectedEmployee.id
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
    }
  },
  computed: {
    manteinanceMode() {
      return JSON.parse(process.env.MIX_ATTENDANCE_MANTEINANCE_MODE)
    },
    isAdmin() {
      return this.$store.getters.role == 'admin'
    }
  },
  mounted() {
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
    async getChecks(id = this.$store.getters.id) {
      try {
        this.loading = true
        let res = await axios.get(`employee/${id}/attendance`, {
          params: {
            month: this.date
          }
        })
        this.checks = res.data.checks
        this.limits = {
          start: res.data.from,
          end: res.data.to
        }
        this.loading = false
      } catch (e) {
        console.log(e)
        this.loading = false
      }
    },
    async getEmployees() {
      try {
        let res = await axios.get(`employee`)
        this.employees = res.data.filter(o => o.active)
        this.employees.forEach(e => {
          e.fullName = `${e.first_name || ''} ${e.second_name || ''} ${e.last_name || ''} ${e.mothers_last_name || ''}`
        });
        this.selectedEmployee = this.employees.find(o => o.id == this.$store.getters.id)
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