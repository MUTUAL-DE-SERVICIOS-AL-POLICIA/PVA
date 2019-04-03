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
      <v-btn color="error" v-if="$route.query.departureType == 'all' && ($store.getters.role == 'rrhh' || $store.getters.role == 'admin')">
        <v-icon>print</v-icon>
        <div class="pl-2">Reporte</div>
      </v-btn>
      <div v-if="$store.getters.user != 'admin' && $route.query.departureType == 'user'" class="ml-4">
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
        <DepartureEdit :bus="bus"></DepartureEdit>
      </div>
    </v-toolbar>
    <div v-if="loading">
      <Loading/>
    </div>
    <v-data-table
      v-else
      :headers="headers"
      :items="departures"
      :rows-per-page-items="[10,20,30,{text:'TODO',value:-1}]"
      disable-initial-sort
      class="elevation-1"
    >
      <template slot="items" slot-scope="props">
        <tr :class="props.expanded ? 'info dark white--text' : (!props.item.approved ? 'warning' : '')">
          <td class="text-xs-center bordered" @click="expand(props)">{{ departureType(props.item).group }}</td>
          <td class="text-xs-center bordered" @click="expand(props)">{{ departureType(props.item).reason }}</td>
          <td class="text-xs-center bordered" @click="expand(props)">{{ $moment(props.item.departure).format('L [a horas] hh:mm') }}</td>
          <td class="text-xs-center bordered" @click="expand(props)">{{ $moment(props.item.return).format('L [a horas] hh:mm') }}</td>
          <td class="text-xs-center bordered">
            <v-layout align-center justify-center v-if="$route.query.departureType == 'all' && ($store.getters.role == 'rrhh' || $store.getters.role == 'admin')">
              <v-switch
                v-model="props.item.approved"
                color="info"
                @click.native="switchActive(props.item)"
              ></v-switch>
            </v-layout>
            <div v-else>
              <v-btn slot="activator" flat icon :color="props.expanded ? 'danger' : 'info'" @click.native="print(props.item.id)">
                <v-icon>print</v-icon>
              </v-btn>
              <v-btn v-if="props.item.description_needed" slot="activator" flat icon :color="props.expanded ? 'danger' : 'info'" @click="bus.$emit('updateDeparture', props.item)">
                <v-icon>edit</v-icon>
              </v-btn>
            </div>
          </td>
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
import Vue from 'vue'
import Loading from '../Loading'
import DepartureEdit from './DepartureEdit'

export default {
  name: 'Departure',
  components: {
    Loading,
    DepartureEdit
  },
  data() {
    return {
      loading: true,
      bus: new Vue(),
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
        }, {
          text: 'Acciones',
          value: '',
          align: 'center',
          sortable: false
        }
      ]
    }
  },
  mounted() {
    this.bus.$on('printDeparture', departureId => {
      this.print(departureId)
      this.getDeparture(departureId)
      this.getRemainingDepartures()
    })
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
    expand(props) {
      if (props.item.description) {
        props.expanded = !props.expanded
      } else {
        props.expanded = false
      }
    },
    async print(id) {
      try {
        this.loading = true
        let res = await axios({
          method: 'GET',
          url: `departure/print/${id}`,
          responseType: "arraybuffer"
        });
        let blob = new Blob([res.data], {
          type: "application/pdf"
        });
        printJS(window.URL.createObjectURL(blob));
        this.loading = false
      } catch (e) {
        console.log(e);
        this.loading = false
      }
    },
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
        this.loading = true
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
        this.loading = false
      } catch (e) {
        console.log(e)
        this.loading = false
      }
    },
    async switchActive(departure) {
      try {
        let res = await axios.patch(`departure/${departure.id}`, {
          approved: !departure.approved
        })
        this.departures[this.departures.findIndex(o => o.id == departure.id)] = res.data
      } catch (e) {
        console.log(e)
      }
    },
    async getDeparture(id) {
      try {
        this.loading = true
        let index = this.departures.findIndex(o => o.id == id)
        let res = await axios.get(`departure/${id}`)
        if (res) {
          if (index == -1) {
            this.departures.unshift(res.data)
          } else {
            this.departures[index] = res.data
          }
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
.bordered {
  border-bottom: 1px solid black;
}
.centered-input input {
  text-align: center;
  margin-bottom: -5px;
  padding-top: 15px;
}
</style>