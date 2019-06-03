<template>
  <v-container fluid>
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
              <div class="text-center my-event" :key="`${event.date} ${event.time}`">
                <span class="white--text">{{ event.time }}</span>
              </div>
            </template>
          </template>
        </v-calendar>
      </v-card>
    </v-flex>
  </v-container>
</template>

<script>
import Loading from '../Loading'

export default {
  name: 'AssistanceIndex',
  components: {
    Loading
  },
  data() {
    return {
      loading: true,
      checks: [],
      limits: {
        start: null,
        end: null,
        weeks: 1
      }
    }
  },
  beforeMount() {
    this.getChecks()
  },
  computed: {
    eventsMap () {
      const map = {}
      if (this.checks.length > 0) {
        this.checks.forEach(e => (map[e.date] = map[e.date] || []).push(e))
      }
      return map
    }
  },
  methods: {
    async getChecks() {
      try {
        let res = await axios.get(`employee/${this.$store.getters.id}/assistance`)
        this.checks = res.data.checks
        this.limits = {
          start: res.data.from,
          end: res.data.to,
          weeks: res.data.weeks
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