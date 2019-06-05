<template>
  <v-container fluid>
    <template v-if="!this.manteinanceMode">
      <v-toolbar>
        <v-toolbar-title>
          {{ `Asistencia del ${$moment(limits.start).format('L')} al ${$moment(limits.end).format('L')}` }}
        </v-toolbar-title>
      </v-toolbar>
      <div v-if="loading">
        <Loading/>
      </div>
      <v-flex>
        <v-card>
          <v-calendar
            v-if="checks.length > 0"
            :start="limits.start"
            :end="limits.end"
            :now="$store.getters.dateNow"
            :short-months="false"
            locale="es-bo"
            type="custom-weekly"
            :weekdays="[1,2,3,4,5,6,0]"
          >
            <template v-slot:day="{ date }">
              <template v-for="event in checks.filter(o => o.date == date)">
                <div class="text-center my-event" :key="`${event.date}_${event.time}`">
                  <span class="white--text">{{ event.time }}</span>
                </div>
              </template>
            </template>
          </v-calendar>
          <template v-else>
            <v-card-text v-if="!loading">
              <h2 class="red--text">No se pudo encontrar el usuario en la base de datos</h2>
            </v-card-text>
          </template>
        </v-card>
      </v-flex>
    </template>
    <template v-else>
      <ManteinanceDialog positionGroup="la Unidad de Recursos Humanos"></ManteinanceDialog>
    </template>
  </v-container>
</template>

<script>
import Loading from '../Loading'
import ManteinanceDialog from "../ManteinanceDialog"

export default {
  name: 'AttendanceIndex',
  components: {
    Loading,
    ManteinanceDialog
  },
  data() {
    return {
      loading: true,
      checks: [],
      limits: {
        start: null,
        end: null
      }
    }
  },
  computed: {
    manteinanceMode() {
      return JSON.parse(process.env.MIX_ATTENDANCE_MANTEINANCE_MODE)
    }
  },
  beforeMount() {
    this.getChecks()
  },
  methods: {
    async getChecks() {
      try {
        let res = await axios.get(`employee/${this.$store.getters.id}/attendance`)
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
    }
  }
}
</script>

<style>
  .my-event {
    overflow: hidden;
    border-radius: 8px;
    background-color: steelblue;
    width: 50%;
    padding: 3px;
    margin-left: 25%;
    margin-bottom: 6px;
  }
</style>