<template>
  <v-container fluid>
    <template v-if="!this.manteinanceMode">
      <v-toolbar>
        <v-toolbar-title>
          <v-select
            v-model="departureTypeSelected"
            :items="departureTypes"
            item-text="dislayName"
            item-value="value"
            class="subheading font-weight-medium"
            label="Solicitudes"
          ></v-select>
        </v-toolbar-title>
        <v-tooltip color="white" role="button" bottom>
          <v-icon slot="activator" class="ml-4">info</v-icon>
          <div>
            <v-alert :value="true" type="warning" class="black--text">PENDIENTE DE APROBACIÓN</v-alert>
            <v-alert :value="true" type="error">RECHAZADO</v-alert>
            <v-alert :value="true" type="info">SELECCIONADO</v-alert>
          </div>
        </v-tooltip>
        <v-spacer></v-spacer>
        <div v-if="$store.getters.user != 'admin' && $route.query.departureType == 'user'" class="ml-4">
          <v-chip
            v-if="!$store.getters.consultant" :color="remainingDepartures.monthly.time_remaining > 0 ? 'secondary' : 'red'" text-color="white"
            class="mr-3"
          >
            <v-avatar
              class="body-2 font-weight-black"
              :color="remainingDepartures.monthly.time_remaining > 0 ? 'primary' : 'error'"
            >{{ remainingDepartures.monthly.time_remaining / 60 }}</v-avatar>
            <div class="subheading font-weight-regular">Hrs/Mes</div>
          </v-chip>
          <v-chip
            v-if="!$store.getters.consultant" :color="remainingDepartures.annually.time_remaining > 0 ? 'accent' : 'red'" text-color="white"
            class="mr-3"
          >
            <v-avatar
              class="body-2 font-weight-black"
              :color="remainingDepartures.annually.time_remaining > 0 ? 'info' : 'error'"
            >{{ remainingDepartures.annually.time_remaining / 8 }}</v-avatar>
            <div class="subheading font-weight-regular">Días/Año</div>
          </v-chip>
        </div>
        <ReportPrint v-if="$route.query.departureType == 'all' && ($store.getters.role == 'rrhh' || $store.getters.role == 'admin')" url="departure/report/print"/>
        <v-divider
          class="mx-2"
          inset
          vertical
        ></v-divider>
        <v-flex xs2 v-show="$route.query.departureType == 'all'">
          <v-text-field
            v-model="search"
            append-icon="search"
            label="Buscar"
            single-line
            hide-details
            full-width
            clearable
          ></v-text-field>
        </v-flex>
        <v-btn color="info" @click="showDate = !showDate">
          {{ $moment(this.date).format('MMMM') }}
        </v-btn>
        <DepartureEdit class="ml-3" :bus="bus" v-show="$route.query.departureType == 'user'"></DepartureEdit>
        <RemoveItem :bus="bus"/>
      </v-toolbar>
      <div v-if="loading">
        <Loading/>
      </div>
      <v-data-table
        v-else
        :headers="headers"
        :items="departures"
        :loading="loading"
        :rows-per-page-items="[20,30,40,{text:'TODO',value:-1}]"
        :search="search"
        disable-initial-sort
        expand
        class="elevation-1"
      >
        <template slot="items" slot-scope="props">
          <tr :class="props.expanded ? 'info dark white--text' : (props.item.approved == null ? 'warning' : (props.item.approved == false ? 'error dark white--text' : ''))">
            <td class="text-xs-center bordered" @click="expand(props)" v-if="$route.query.departureType == 'all'">{{ `${props.item.last_name} ${props.item.mothers_last_name} ${props.item.first_name} ${props.item.second_name ? props.item.second_name : ''}` }}</td>
            <td class="text-xs-center bordered" @click="expand(props)">{{ departureType(props.item).group }}</td>
            <td class="text-xs-center bordered" @click="expand(props)">{{ departureType(props.item).reason }}</td>
            <td class="text-xs-center bordered" @click="expand(props)">{{ $moment(props.item.departure).format('L [a horas] HH:mm') }}</td>
            <td class="text-xs-center bordered" @click="expand(props)">{{ $moment(props.item.return).format('L [a horas] HH:mm') }}</td>
            <td class="text-xs-center bordered">
              <div v-if="$route.query.departureType == 'all' && ($store.getters.role == 'rrhh' || $store.getters.role == 'admin')">
                <v-tooltip top v-show="props.item.approved === null || props.item.approved === false">
                  <v-btn slot="activator" small icon color="success" @click.native="switchActive(props.item.id, true)">
                    <v-icon>check</v-icon>
                  </v-btn>
                  <span>Aprobar</span>
                </v-tooltip>
                <v-tooltip top v-show="props.item.approved === null || props.item.approved === true">
                  <v-btn slot="activator" small icon color="error" @click.native="switchActive(props.item.id, false)">
                    <v-icon>clear</v-icon>
                  </v-btn>
                  <span>Rechazar</span>
                </v-tooltip>
              </div>
              <div v-else>
                <v-tooltip top>
                  <v-btn slot="activator" flat icon :color="props.expanded ? 'danger' : ''" @click.native="print(props.item.id)">
                    <v-icon>print</v-icon>
                  </v-btn>
                  <span>Imprimir</span>
                </v-tooltip>
                <v-tooltip top v-if="props.item.approved === null && (props.item.description_needed || props.item.note)">
                  <v-btn slot="activator" flat icon :color="props.expanded ? 'danger' : 'info'" @click="bus.$emit('updateDeparture', props.item)">
                    <v-icon>edit</v-icon>
                  </v-btn>
                  <span>Editar</span>
                </v-tooltip>
                <v-tooltip top v-if="props.item.approved == null && $store.getters.role == 'admin'">
                  <v-btn slot="activator" flat icon :color="props.expanded ? 'danger' : 'red darken-3'" @click.native="removeItem(props.item)">
                    <v-icon>delete</v-icon>
                  </v-btn>
                  <span>Eliminar</span>
                </v-tooltip>
                <Transfer :departure="props.item" :color="props.expanded ? 'warning' : 'success'" v-if="departureType(props.item).group == 'COMISIÓN'"/>
              </div>
            </td>
          </tr>
        </template>
        <template slot="expand" slot-scope="props">
          <v-card flat class="grey lighten-3">
            <v-card-text class="bordered">
              <v-list class="grey lighten-3">
                <v-list-tile-content v-if="props.item.description"><p><strong>Detalle: </strong>{{ props.item.description }}</p></v-list-tile-content>
                <v-list-tile-content><p><strong>Puesto: </strong>{{ props.item.position }}</p></v-list-tile-content>
                <v-list-tile-content><p><strong>Unidad: </strong>{{ props.item.positionGroup }}</p></v-list-tile-content>
              </v-list>
            </v-card-text>
          </v-card>
        </template>
      </v-data-table>
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
        :max="($route.query.departureType == 'user' && !$store.getters.consultant) ? $moment($store.getters.dateNow).add(1, 'months').format() : $store.getters.dateNow"
      ></v-date-picker>
    </v-dialog>
  </v-container>
</template>

<script>
import Vue from 'vue'
import Loading from '../Loading'
import RemoveItem from "../RemoveItem"
import DepartureEdit from './DepartureEdit'
import ReportPrint from '../ReportPrint'
import ManteinanceDialog from "../ManteinanceDialog"
import Transfer from './Transfer'

export default {
  name: 'Departure',
  components: {
    Loading,
    RemoveItem,
    DepartureEdit,
    ReportPrint,
    Transfer,
    ManteinanceDialog,
  },
  data() {
    return {
      loading: true,
      bus: new Vue(),
      search: '',
      date: this.$store.getters.dateNow,
      showDate: false,
      departureTypeSelected: 'null',
      departureTypes: [
        {
          value: 'null',
          dislayName: 'PENDIENTES'
        }, {
          value: true,
          dislayName: 'APROBADAS'
        }, {
          value: false,
          dislayName: 'RECHAZADAS'
        }, {
          value: 'all',
          dislayName: 'TODAS'
        }
      ],
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
          value: 'mothers_last_name',
          align: 'center',
          sortable: false
        }, {
          text: 'Motivo',
          value: 'first_name',
          align: 'center',
          sortable: false
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
          value: 'second_name',
          align: 'center',
          sortable: false
        }
      ]
    }
  },
  computed: {
    manteinanceMode() {
      return JSON.parse(process.env.MIX_DEPARTURE_MANTEINANCE_MODE)
    },
    dateRange() {
      let range = {
        from: null,
        to: this.$moment(this.date)
      }
      if (this.$route.query.departureType == 'all') {
        range.from = range.to.clone().subtract(1, 'months').date(20).startOf('day').format()
        range.to = range.to.endOf('month').format()
      } else {
        if (this.$store.getters.consultant) {
          range.from = range.to.clone().date(1).startOf('day').format()
          range.to = range.to.endOf('month').format()
        } else {
          if (range.to.date() < 20) {
            range.from = range.to.clone().subtract(1, 'months').date(20).startOf('day').format()
            range.to = range.to.date(19).endOf('day').format()
          } else {
            range.from = range.to.clone().date(20).startOf('day').format()
            range.to = range.to.add(1, 'months').date(19).endOf('day').format()
          }
        }
      }
      return range
    }
  },
  beforeMount() {
    if (this.$route.query.departureType == 'user') {
      this.getRemainingDepartures()
      this.departureTypeSelected = 'all'
    } else {
      this.departureTypeSelected = 'null'
    }
  },
  mounted() {
    this.bus.$on('printDeparture', departureId => {
      this.print(departureId)
      this.getDeparture(departureId)
      this.getRemainingDepartures()
    })
    this.bus.$on('removed', departureId => {
      this.getRemainingDepartures()
      this.departures = this.departures.filter(o => o.id != departureId)
    })
    this.setHeaders()
    if (this.$route.query.departureType != 'all') {
      this.getRemainingDepartures()
    }
    this.getDepartureGroups()
    this.getDepartureReasons()
    this.getDepartures()
    this.bus.$on('updatePermissionList', () => {
      this.getDepartures()
    })
  },
  watch: {
    '$route.query.departureType'(newVal, oldVal) {
      if (newVal == 'user') {
        this.departureTypeSelected = 'all'
        this.getRemainingDepartures()
      } else {
        this.departureTypeSelected = 'null'
      }
      if (newVal != oldVal) {
        this.getDepartures(newVal)
      }
      this.setHeaders()
    },
    'departureTypeSelected'(newVal, oldVal) {
      this.search = ''
      if (newVal != oldVal) {
        this.getDepartures()
      }
    },
    date: function(newVal, oldVal) {
      if (newVal != oldVal) {
        this.getDepartures()
      }
      this.showDate = false
    }
  },
  methods: {
    setHeaders() {
      if (this.$route.query.departureType == 'all'){
        if (this.headers.findIndex(o => o.text == 'Nombres') == -1) {
          this.headers.unshift({
            text: 'Nombres',
            value: 'last_name',
            align: 'center',
            sortable: true
          })
        }
      } else {
        if (this.headers.findIndex(o => o.text == 'Nombres') != -1) {
          this.headers = this.headers.filter(el => {
            return el.text != 'Nombres';
          });
        }
      }
    },
    removeItem(item) {
      if(item.departure_reason_id==24){
        this.bus.$emit("openDialogRemove", `cancel_vacation_departure/${item.id}`);
      }else{
        this.bus.$emit("openDialogRemove", `departure/${item.id}`);
      }
      this.getRemainingDepartures()
    },
    async getLastContract(id) {
      try {
        let res = await axios.get(`employee/${id}/contract`)
        return res.data
      } catch (e) {
        console.log(e)
      }
    },
    async expand(props) {
      if (!props.expanded) {
        let contract = await this.getLastContract(props.item.employee_id)
        if (contract.hasOwnProperty('consultant_position_id')) {
          props.item.position = contract.consultant_position.name,
          props.item.positionGroup = contract.consultant_position.position_group.name
        } else {
          props.item.position = contract.position.name,
          props.item.positionGroup = contract.position.position_group.name
        }
      }
      props.expanded = !props.expanded
    },
    async print(id) {
      try {
        this.loading = true
        let res
          res = await axios({
          method: 'GET',
          url: `departure/print/${id}`,
          responseType: "arraybuffer"
          })
        let blob = new Blob([res.data], {
          type: "application/pdf"
        })
        printJS(window.URL.createObjectURL(blob))
        this.loading = false
      } catch (e) {
        console.log(e)
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
    async getDepartures(type = this.$route.query.departureType) {
      try {
        this.loading = true
        let range = this.dateRange
        let res = await axios.get(`departure`, {
          params: {
            from: range.from,
            to: range.to,
            employee_id: type == 'user' ? this.$store.getters.id : null,
            approved: this.departureTypeSelected
          }
        })
        if (res) this.departures = res.data
        this.loading = false
      } catch (e) {
        console.log(e)
        this.loading = false
      }
    },
    async switchActive(id, state) {
      try {
        let res = await axios.patch(`departure/${id}`, {
          approved: state
        })
        if (this.departureTypeSelected == 'all') {
          this.departures[this.departures.findIndex(o => o.id == id)].approved = res.data.approved
        } else {
          this.departures = this.departures.filter(o => o.id != id)
        }
        if (state === true) {
          this.toastr.success('Solicitud aprobada')
        } else {
          this.toastr.warning('Solicitud rechazada')
        }
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
</style>