<template>
  <v-container fluid>
    <v-toolbar>
      <v-toolbar-title>
        Permisos y Licencias
      </v-toolbar-title>
      <v-tooltip color="white" role="button" bottom>
        <v-icon slot="activator" class="ml-4">info</v-icon>
        <div>
          <v-alert :value="true" type="warning" class="black--text">NO APROBADO</v-alert>
          <v-alert :value="true" type="info">SELECCIONADO</v-alert>
        </div>
      </v-tooltip>
      <v-spacer></v-spacer>
      <v-divider
        class="mx-2"
        inset
        vertical
      ></v-divider>
      <v-chip
        :color="remainingDepartures.monthly.time_remaining > 0 ? 'secondary' : 'red'" text-color="white"
        class="mr-3"
      >
        <v-avatar
          class="body-2 font-weight-black"
          :color="remainingDepartures.monthly.time_remaining > 0 ? 'primary' : 'error'"
        >{{ remainingDepartures.monthly.time_remaining / 60 }}</v-avatar>
        <div class="subheading font-weight-regular">Hrs/Mes</div>
      </v-chip>
      <v-chip
        :color="remainingDepartures.annually.time_remaining > 0 ? 'accent' : 'red'" text-color="white"
        class="mr-3"
      >
        <v-avatar
          class="body-2 font-weight-black"
          :color="remainingDepartures.annually.time_remaining > 0 ? 'info' : 'error'"
        >{{ remainingDepartures.annually.time_remaining / 8 }}</v-avatar>
        <div class="subheading font-weight-regular">Días/Año</div>
      </v-chip>
      <DepartureForm></DepartureForm>
    </v-toolbar>
    <v-data-table
      :headers="headers"
      :items="departures"
      :rows-per-page-items="[10,20,30,{text:'TODO',value:-1}]"
      disable-initial-sort
      class="elevation-1"
    >
      <template slot="items" slot-scope="props">
        <tr :class="props.expanded ? 'info dark white--text' : (!props.item.approved ? 'warning' : '')">
          <td class="text-xs-center bordered" @click="props.expanded = !props.expanded"> {{ departureType(props.item).group }} </td>
          <td class="text-xs-center bordered" @click="props.expanded = !props.expanded"> {{ departureType(props.item).reason }} </td>
          <td class="text-xs-center bordered" @click="props.expanded = !props.expanded"> {{ $moment(props.item.departure).format('L [a horas] hh:mm') }} </td>
          <td class="text-xs-center bordered" @click="props.expanded = !props.expanded"> {{ $moment(props.item.return).format('L [a horas] hh:mm') }} </td>
        </tr>
      </template>
      <template slot="expand" slot-scope="props">
        <v-card flat class="grey lighten-3" v-if="props.item.description">
          <v-card-text class="bordered">
            <v-list class="grey lighten-3">
              <v-list-tile-content><p><strong>Detalle: </strong>{{ props.item.description }}</p></v-list-tile-content>
            </v-list>
          </v-card-text>
        </v-card>
      </template>
    </v-data-table>
  </v-container>
</template>

<script>
import DepartureForm from './Form'

export default {
  name: 'Departure',
  components: {
    DepartureForm
  },
  data() {
    return {
      loading: true,
      departures: [],
      departureReasons: [],
      departureGroups: [],
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
      headers: [
        {
          text: 'Tipo',
          value: '',
          align: 'center',
          sortable: false
        }, {
          text: 'Motivo',
          value: 'departure_reason_id',
          align: 'center',
          sortable: true
        }, {
          text: 'Desde',
          value: 'departure',
          align: 'center',
          sortable: true
        }, {
          text: 'Hasta',
          value: 'return',
          align: 'center',
          sortable: true
        }
      ]
    }
  },
  mounted() {
    this.getRemainingDepartures()
    this.getDepartureGroups()
    this.getDepartureReasons()
    this.getDepartures(this.$route.query.departureType)
  },
  watch: {
    '$route.query.departureType'(val) {
      this.getDepartures(val)
    }
  },
  methods: {
    async getRemainingDepartures() {
      try {
        let res = await axios.get(`employee/${this.$store.getters.id}`)
        this.remainingDepartures = res.data.remaining_departures
      } catch (e) {
        console.log(e)
      }
    },
    departureType(item) {
      if (this.departureReasons.length > 0 && this.departureGroups.length > 0) {
        let reason = this.departureReasons.find(o => o.id == item.departure_reason_id)
        return {
          reason: reason.name,
          group: this.departureGroups.find(o => o.id == reason.departure_group_id).name
        }
      }
      return {
        reason: '',
        group: ''
      }
    },
    async getDepartureGroups() {
      try {
        let res = await axios.get(`departure_group`)
        this.departureGroups = res.data
      } catch (e) {
        console.log(e)
      }
    },
    async getDepartureReasons() {
      try {
        let res = await axios.get(`departure_reason`)
        this.departureReasons = res.data
      } catch (e) {
        console.log(e)
      }
    },
    async getDepartures(type) {
      try {
        let res
        switch (type) {
          case 'all':
            res = await axios.get(`departure`)
            break;
          case 'user':
            res = await axios.get(`departure`, {
              params: {
                employee_id: this.$store.getters.id
              }
            })
            break;
        }
        if (res) this.departures = res.data
      } catch (e) {
        console.log(e)
      }
    }
  }
}
</script>

<style>
.bordered {
  border-bottom: 1px solid black;
}
</style>
