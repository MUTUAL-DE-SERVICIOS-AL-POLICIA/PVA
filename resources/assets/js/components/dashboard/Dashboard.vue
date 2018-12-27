<template>
  <v-container fluid>
    <v-dialog
      v-model="phoneDialog"
      @keydown.esc="phoneDialog = false"
      max-width="900"
    >
      <v-card>
        <v-data-iterator
          :items="positionGroups"
          content-tag="v-layout"
          :rows-per-page-items="rowsPerPageItems"
          :pagination.sync="pagination"
          row
          wrap
        >
          <v-toolbar
            slot="header"
            class="mb-2"
            color="green darken-4"
            dark
            flat
          >
            <v-toolbar-title>NÚMEROS DE TELÉFONOS INTERNOS</v-toolbar-title>
          </v-toolbar>

          <v-flex
            slot="item"
            slot-scope="props"
            xs12
            sm6
            md4
            lg3
          >
            <v-card class="mb-3 mr-1 ml-1">
              <v-card-title class="blue-grey lighten-4 subheading font-weight-bold">{{ props.item.name }}</v-card-title>

              <v-list dense>
                <v-list-tile v-for="location in props.item.locations" :key="location.id">
                  <v-list-tile-content>{{ location.name }}</v-list-tile-content>
                  <v-list-tile-content class="pl-4 align-end">{{ location.phone_number }}</v-list-tile-content>
                </v-list-tile>
              </v-list>
            </v-card>
          </v-flex>
        </v-data-iterator>

        <v-card-actions>
          <v-spacer></v-spacer>

          <v-btn
            dark
            color="info"
            @click="phoneDialog = false"
          >
            Imprimir
          </v-btn>

          <v-btn
            dark
            color="error"
            @click="phoneDialog = false"
          >
            Cerrar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-card>
      <v-container fluid grid-list-md>
        <v-layout row wrap>
          <v-flex md12 lg8>
            <v-layout row wrap>
              <v-flex xs12 sm6 v-for="filter in filteredEmployees" :key="filter.icon" v-if="$store.getters.currentUser.roles.length > 0">
                <v-card :color="filter.color" dark height="190px" class="mb-2">
                  <v-layout row wrap>
                    <v-flex xs4 class="text-xs-center" mt-4>
                      <v-icon size="80">{{ filter.icon }}</v-icon>
                      <div class="text-xs-left mt-4 ml-3">
                        <vue-json-to-csv
                          v-if="filter.downloadable"
                          :json-data="filter.data.filter(o => { return o.active == true })"
                          :labels = "{
                            last_name: { title: 'APELLIDO PATERNO' },
                            mothers_last_name: { title: 'APELLIDO MATERNO' },
                            first_name: { title: 'PRIMER NOMBRE' },
                            second_name: { title: 'SEGUNDO NOMBRE' },
                            position: { title: 'CARGO' },
                            phone_number: { title: 'CELULAR'},
                            landline_number: { title: 'TELÉFONO'}
                          }"
                          :csv-title="`${filter.title}_${$store.getters.dateNow}`"
                        >
                          <v-tooltip right>
                            <v-btn flat icon slot="activator">
                              <v-icon>save</v-icon>
                            </v-btn>
                            <span>Descargar</span>
                          </v-tooltip>
                        </vue-json-to-csv>
                      </div>
                    </v-flex>
                    <v-flex xs8>
                      <v-card-text class="text-xs-center">
                        <div class="display-3 font-weight-thin">{{ filter.total.active }}</div>
                        <div class="display-1 font-weight-light">{{ filter.title }}</div>
                        <v-tooltip bottom v-if="'new' in filter && filter.new.length > 0">
                          <div slot="activator" class="subheading font-weight-light">Nuevos {{ $moment($store.getters.dateNow).format('MMMM') }}: {{ filter.new.length }}</div>
                          <div v-for="(item, index) in filter.new" :key="item.id">{{ `${++index}.- ${item.last_name} ${item.mothers_last_name} ${item.first_name} ${item.second_name}` }}</div>
                        </v-tooltip>
                        <div v-else-if="'new' in filter && filter.new.length == 0" slot="activator" class="subheading font-weight-light">Nuevos {{ $moment($store.getters.dateNow).format('MMMM') }}: {{ filter.new.length }}</div>
                        <v-tooltip bottom v-if="'old' in filter && filter.old.length > 0">
                          <div slot="activator" class="subheading font-weight-light">Bajas {{ $moment($store.getters.dateNow).format('MMMM') }}: {{ filter.old.length }}</div>
                          <div v-for="(item, index) in filter.old" :key="item.id">{{ `${++index}.- ${item.last_name} ${item.mothers_last_name} ${item.first_name} ${item.second_name}` }}</div>
                        </v-tooltip>
                        <div v-else-if="'old' in filter && filter.old.length == 0" slot="activator" class="subheading font-weight-light">Bajas {{ $moment($store.getters.dateNow).format('MMMM') }}: {{ filter.old.length }}</div>
                      </v-card-text>
                    </v-flex>
                  </v-layout>
                </v-card>
              </v-flex>
              <v-flex xs12 sm6 v-if="$store.getters.currentUser.roles.length > 0">
                <v-card dark v-if="$store.getters.currentUser.roles[0].name == 'admin' || $store.getters.currentUser.roles[0].name == 'rrhh'" height="190px" class="mb-2">
                  <v-layout row wrap>
                    <v-flex xs4 class="text-xs-center" mt-4>
                      <v-icon size="80">attach_money</v-icon>
                      <div class="text-xs-left ml-3">
                        <vue-json-to-csv
                            v-if="procedures.length > 0"
                            :json-data="procedures"
                            :labels = "{
                              month: { title: 'Nº' },
                              name: { title: 'MES' },
                              faults: { title: 'DESCUENTOS' }
                            }"
                            :csv-title="`fondo_social_a_${procedures[procedures.length - 1].name.toLowerCase()}_${$moment(this.$store.getters.dateNow).year()}`"
                          >
                          <v-tooltip right>
                            <v-btn flat icon slot="activator">
                              <v-icon>save</v-icon>
                            </v-btn>
                            <span>Descargar</span>
                          </v-tooltip>
                        </vue-json-to-csv>
                      </div>
                    </v-flex>
                    <v-flex xs8>
                      <v-card-text class="text-xs-center">
                        <div class="display-3 font-weight-thin">{{ procedures.length == 0 ? '0' : new Intl.NumberFormat('es-BO').format(procedures.map(item => item.faults).reduce((prev, next) => prev + next)) }}</div>
                        <div class="display-1 font-weight-light">Fondo Social {{ $moment(this.$store.getters.dateNow).year() }}</div>
                        <v-tooltip bottom>
                          <div slot="activator" class="subheading font-weight-light">Meses pagados: {{ procedures.length }}</div>
                          <table>
                            <tr v-for="item in procedures" :key="item.id">
                              <td>{{ item.month }}.-</td>
                              <td>{{ item.name }}</td>
                              <td class="text-xs-right">{{ item.faults }}</td>
                            </tr>
                          </table>
                        </v-tooltip>
                      </v-card-text>
                    </v-flex>
                  </v-layout>
                </v-card>
              </v-flex>
              <v-flex xs12 sm6>
                <v-card color="teal darken-4" dark @click="phoneDialog = true" style="cursor: pointer">
                  <v-layout row wrap>
                    <v-flex xs4 class="text-xs-center" mt-4>
                      <v-icon size="80">phone</v-icon>
                    </v-flex>
                    <v-flex xs8>
                      <v-card-text class="text-xs-center">
                        <div class="display-3 font-weight-thin">Teléfonos Internos</div>
                      </v-card-text>
                    </v-flex>
                  </v-layout>
                </v-card>
              </v-flex>
              <v-flex xs12 sm6 v-if="$store.getters.currentUser.roles.length == 0">
                <v-card color="blue darken-4" dark :to="{ name: 'departureIndex'}" style="cursor: pointer">
                  <v-layout row wrap>
                    <v-flex xs4 class="text-xs-center" mt-4>
                      <v-icon size="80">directions_run</v-icon>
                    </v-flex>
                    <v-flex xs8>
                      <v-card-text class="text-xs-center">
                        <div class="display-3 font-weight-thin">Salidas y Licencias</div>
                      </v-card-text>
                    </v-flex>
                  </v-layout>
                </v-card>
              </v-flex>
            </v-layout>
          </v-flex>
          <v-flex md12 lg4>
            <v-card color="red">
              <v-card dark color="blue-grey darken-2">
                <v-layout row wrap>
                  <v-flex xs4 class="text-xs-center" mt-4>
                    <v-icon size="80">cake</v-icon>
                  </v-flex>
                  <v-flex xs8>
                    <v-card-text class="text-xs-center">
                      <div class="display-3 font-weight-thin">{{ birthday.length }}</div>
                      <div class="display-1 font-weight-light">Cumpleañeros de {{ $moment($store.getters.dateNow).format('MMMM') }}</div>
                      <div class="title font-weight-light mt-2 mb-2" v-if="birthday.length > 0">Felicidades !!!!</div>
                    </v-card-text>
                  </v-flex>
                </v-layout>
                <v-layout row wrap v-for="(e, i) in birthday" :key="e.id">
                  <v-flex md6 lg2 class="text-xs-right">{{ ++i }}.</v-flex>
                  <v-flex md2 lg6 class="text-xs-left">{{ e.first_name }} {{ e.last_name }}</v-flex>
                  <v-flex md2 lg2 class="text-xs-right">Día {{ e.birth_date.split('/')[0] }}</v-flex>
                </v-layout>
                <div class="text-xs-left ml-3">
                  <vue-json-to-csv
                      :json-data="birthday"
                      :labels = "{
                        first_name: { title: 'PRIMER NOMBRE' },
                        second_name: { title: 'SEGUNDO NOMBRE' },
                        last_name: { title: 'APELLIDO PATERNO' },
                        mothers_last_name: { title: 'APELLIDO MATERNO' },
                        birthDate: { title: 'CUMPLEAÑOS' }
                      }"
                      :csv-title="`cumpleañeros_${$moment($store.getters.dateNow).format('M_Y')}`"
                    >
                    <v-tooltip right>
                      <v-btn flat icon slot="activator">
                        <v-icon>save</v-icon>
                      </v-btn>
                      <span>Descargar</span>
                    </v-tooltip>
                  </vue-json-to-csv>
                </div>
              </v-card>
            </v-card>
          </v-flex>
        </v-layout>
      </v-container>
    </v-card>
  </v-container>
</template>
<script>
import VueJsonToCsv from 'vue-json-to-csv'

export default {
  name: "dashboard",
  components: {
    VueJsonToCsv
  },
  data: () => ({
    rowsPerPageItems: [2, 4, 8],
    pagination: {
      rowsPerPage: 2
    },
    phoneDialog: false,
    employees: [],
    filteredEmployees: [],
    procedures: [],
    positionGroups: []
  }),
  computed: {
    birthday() {
      return this.employees.sort((a, b) => { if (a.birth_date < b.birth_date) return -1; if (a.birth_date > b.birth_date) return 1; return 0 }).filter(o => {
        o.birthDate = `Día ${ o.birth_date.split('/')[0] }`
        return (o.active == true && this.$moment(`${o.birth_date.split('/')[2]}-${o.birth_date.split('/')[1]}-${o.birth_date.split('/')[0]}`).month() == this.$moment(this.$store.getters.dateNow).month())
      })
    }
  },
  created() {
    this.getEmployees()
    this.getYearFaults()
    this.getLocations()
  },
  methods: {
    async getLocations() {
      try {
        let res = await axios.get(`/location`)
        this.positionGroups = res.data
      } catch (e) {
        console.log(e);
      }
    },
    async getYearFaults() {
      try {
        let res = await axios.get(`/payroll/faults/${this.$moment(this.$store.getters.dateNow).year()}`)
        this.procedures = res.data
      } catch (e) {
        console.log(e);
      }
    },
    printEmployeesData(data) {
      return data;
    },
    async getEmployees(active = this.active) {
      try {
        let res = await axios.get(`/employee`)
        this.employees = res.data
        if (this.employees.length > 0) {
          this.employees.forEach(e => {
            e.identity_card += ` ${e.city_identity_card.shortened}`
            e.birth_date = this.$moment(e.birth_date).format('L')
          })

          let types = ['eventuals', 'consultants', 'withoutContracts']
          types.forEach(type => {
            let obj = {}
            switch(type) {
              case 'eventuals':
                obj.data = this.employees.filter(o => {
                  return o.consultant == false
                })
                obj.title = 'Eventuales'
                obj.icon = 'person'
                obj.color = 'blue darken-4'
                obj.downloadable = true
                break
              case 'consultants':
                obj.data = this.employees.filter(o => {
                  return o.consultant == true
                })
                obj.title = 'Consultores'
                obj.icon = 'work'
                obj.color = 'deep-orange darken-2'
                obj.downloadable = true
                break
              case 'withoutContracts':
                obj.data = this.employees.filter(o => {
                  return o.consultant == null
                })
                obj.title = 'Sin Contratos'
                obj.icon = obj.data.length > 0 ? 'warning' : 'done'
                obj.color = obj.data.length > 0 ? 'red accent-4' : 'green darken-1'
                obj.downloadable = false
                break
            }

            if (type == 'withoutContracts') {
              obj.total = {
                active: obj.data.length
              }
            } else {
              obj.new = obj.data.filter(o => {
                if (o.created_at) {
                  return this.$moment(o.created_at.replace(" ", "T")).isSame(this.$moment(this.$store.getters.dateNow), 'month')
                }
              })
              obj.old = obj.data.filter(o => {
                return (o.active == false && o.updated_at && this.$moment(o.updated_at.replace(" ", "T")).isSame(this.$moment(this.$store.getters.dateNow), 'month'))
              })
              obj.total = {
                active: obj.data.filter(o => {
                  return o.active == true
                }).length,
                inactive: obj.data.filter(o => {
                  return o.active == false
                }).length,
              }
            }

            this.filteredEmployees.push(obj)
          })
        } else {
          this.filteredEmployees.push({
            data: [],
            title: 'Empleados registrados',
            icon: 'error_outline',
            color: 'red accent-4',
            downloadable: false,
            total: {
              active: 0
            }
          })
        }
      } catch (e) {
        console.log(e)
      }
    },
  }
};
</script>
