<template>
  <v-container fluid>
    <v-toolbar>
      <v-toolbar-title>
        Configuración de Horarios
      </v-toolbar-title>
    </v-toolbar>
    <v-data-table
      :headers="headers"
      :items="schedules"
      :loading="loading"
      :rows-per-page-items="[{ text: 'TODO', value: -1 }, 10, 20, 30]"
      disable-initial-sort
    >
      <template slot="items" slot-scope="props">
        <tr>
          <td class="text-md-center">
            <v-time-picker
              v-model="props.item.start"
              :allowed-minutes="allowedMinutes"
              format="24hr"
            ></v-time-picker>
          </td>
          <td class="text-md-center">
            <v-time-picker
              v-model="props.item.end"
              :allowed-minutes="allowedMinutes"
              format="24hr"
            ></v-time-picker>
          </td>
          <td class="text-md-center">
            <v-time-picker
              v-model="props.item.start_limit"
              :allowed-minutes="allowedMinutes"
              format="24hr"
            ></v-time-picker>
          </td>
          <td class="text-md-center">
            <v-time-picker
              v-model="props.item.end_limit"
              :allowed-minutes="allowedMinutes"
              format="24hr"
            ></v-time-picker>
          </td>
          <td class="text-md-center">
            <table>
              <tr>
                <td class="pa-2" v-for="day in props.item.days" :key="`${props.item.id}.${day.weekday}`">
                  <v-checkbox v-model="day.value" :hint="day.name.substring(0, 3)" persistent-hint></v-checkbox>
                </td>
              </tr>
            </table>
          </td>
          <td>
            <v-btn color="success" @click="saveSchedule(props.item.id)"><v-icon>check</v-icon> Guardar</v-btn>
          </td>
        </tr>
      </template>
      <template slot="no-data">
        <v-container fluid fill-height>
          <v-layout align-center justify-center>
            <v-progress-circular
              :width="1"
              :size="50"
              color="primary"
              indeterminate
              class="pa-5 ma-5"
            ></v-progress-circular>
          </v-layout>
        </v-container>
      </template>
    </v-data-table>
  </v-container>
</template>

<script>
import { finished } from 'stream';
export default {
  name: 'AttendanceConfig',
  data: () => ({
    loading: false,
    schedules: [],
    headers: [
      { align: "center", sortable: false, text: "Ingreso", class: ["ma-0", "pa-0"], value: "start_hour" },
      { align: "center", sortable: false, text: "Salida", class: ["ma-0", "pa-0"], value: "end_hour" },
      { align: "center", sortable: false, text: "Límite mínimo", class: ["ma-0", "pa-0"], value: "start_hour_min_limit" },
      { align: "center", sortable: false, text: "Límite máximo", class: ["ma-0", "pa-0"], value: "end_hour_max_limit" },
      { align: "center", sortable: false, text: "Días laborales", class: ["ma-0", "pa-0"], value: "workdays" },
      { align: "center", sortable: false, text: "Acciones", class: ["ma-0", "pa-0"], value: "id" }
    ]
  }),
  mounted() {
    this.getJobSchedules()
  },
  methods: {
    async saveSchedule(id) {
      try {
        let schedule = this.schedules.find(o => o.id == id)
        let res = await axios.patch(`job_schedule/${id}?iso_format=true`, {
          start_hour: parseInt(schedule.start.split(':')[0]),
          start_minutes: parseInt(schedule.start.split(':')[1]),
          end_hour: parseInt(schedule.end.split(':')[0]),
          end_minutes: parseInt(schedule.end.split(':')[1]),
          start_hour_min_limit: parseInt(schedule.start_limit.split(':')[0]),
          start_minutes_min_limit: parseInt(schedule.start_limit.split(':')[1]),
          end_hour_min_limit: parseInt(schedule.end_limit.split(':')[0]),
          end_minutes_min_limit: parseInt(schedule.end_limit.split(':')[1]),
          workdays: schedule.days.filter(o => o.value).map(o => o.weekday)
        })
        this.toastr.success('Actualizado correctamente')
      } catch (e) {
        console.log(e)
        this.toastr.error('Error al actualizar')
      }
    },
    allowedMinutes(m) {
      return m % 30 === 0
    },
    async getJobSchedules() {
      try {
        this.loading = true
        let res = await axios.get(`job_schedule`, {
          params: {
            iso_format: true
          }
        })
        this.schedules = res.data

      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    }
  }
}
</script>