<template>
  <v-container fluid>
    <v-card>
      <v-container fluid grid-list-md>
        <v-layout row wrap>
          <v-flex md12 lg8>
            <v-layout row wrap>
              <v-flex xs12 sm6>
                <v-card color="light-blue darken-1" dark :to="{ name: 'attendanceIndex' }" style="cursor: pointer" class="card-box">
                  <v-layout row wrap>
                    <v-flex xs4 class="text-xs-center" mt-4>
                      <v-icon size="80">fingerprint</v-icon>
                    </v-flex>
                    <v-flex xs7>
                      <v-card-text class="text-xs-center">
                        <div class="display-2 font-weight-thin">Registro de Asistencia</div>
                      </v-card-text>
                    </v-flex>
                  </v-layout>
                </v-card>
              </v-flex>
              <v-flex xs12 sm6 v-if="$store.getters.user != 'admin'">
                <v-card color="teal darken-4" dark :to="{ name: 'supplyRequestIndex', query: { requestType: 'user' }}" style="cursor: pointer" class="card-box">
                  <v-layout row wrap>
                    <v-flex xs4 class="text-xs-center" mt-4>
                      <v-icon size="80">add_shopping_cart</v-icon>
                    </v-flex>
                    <v-flex xs7>
                      <v-card-text class="text-xs-center">
                        <div class="display-2 font-weight-thin">Solicitud de Material</div>
                      </v-card-text>
                    </v-flex>
                  </v-layout>
                </v-card>
              </v-flex>
              <v-flex xs12 sm6 v-if="$store.getters.user != 'admin'">
                <v-card color="blue darken-4" dark :to="{ name: 'departureIndex', query: {departureType: 'user'} }" style="cursor: pointer" class="card-box">
                  <v-layout row wrap>
                    <v-flex xs4 class="text-xs-center" mt-4>
                      <v-icon size="80">directions_run</v-icon>
                    </v-flex>
                    <v-flex xs7>
                      <v-card-text class="text-xs-center">
                        <div class="display-2 font-weight-thin">Permisos y Licencias</div>
                      </v-card-text>
                    </v-flex>
                  </v-layout>
                </v-card>
              </v-flex>
              <v-flex xs12 sm6 v-if="$store.getters.role == 'admin' || $store.getters.role == 'rrhh'">
                <v-card dark class="mb-2 card-box">
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
                            :csv-title="`fondo_social_a_${procedures[procedures.length - 1].name.toLowerCase()}_${$moment($store.getters.dateNow).year()}`"
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
                    <v-flex xs7>
                      <v-card-text class="text-xs-center">
                        <div class="display-2 font-weight-thin">{{ procedures.length == 0 ? '0' : new Intl.NumberFormat('es-BO').format(procedures.map(item => item.faults).reduce((prev, next) => prev + next)) }}</div>
                        <div class="headline font-weight-light">Fondo Social {{ $moment($store.getters.dateNow).year() }}</div>
                        <v-tooltip bottom v-if="procedures.length > 0">
                          <div slot="activator" class="subheading font-weight-light">Meses pagados: {{ procedures.length }}</div>
                          <table>
                            <tr v-for="item in procedures" :key="item.id">
                              <td>{{ item.month }}.-</td>
                              <td>{{ item.name }}</td>
                              <td class="text-xs-right">{{ item.faults }}</td>
                            </tr>
                          </table>
                        </v-tooltip>
                        <div v-else class="subheading font-weight-light">Meses pagados: {{ procedures.length }}</div>
                      </v-card-text>
                    </v-flex>
                  </v-layout>
                </v-card>
              </v-flex>
              <template v-for="filter in filteredEmployees">
                <v-flex xs12 sm6 :key="filter.title" v-if="$store.getters.role == 'admin' || $store.getters.role == 'rrhh'">
                  <v-card :color="filter.color" dark class="mb-2 card-box">
                    <v-layout row wrap>
                      <v-flex xs4 class="text-xs-center" mt-4>
                        <v-icon size="80">{{ filter.icon }}</v-icon>
                        <div class="text-xs-left mt-2 ml-3">
                          <vue-json-to-csv
                            v-if="filter.downloadable"
                            :json-data="filter.data.filter(o => { return o.active == true })"
                            :labels = "{
                              last_name: { title: 'APELLIDO PATERNO' },
                              mothers_last_name: { title: 'APELLIDO MATERNO' },
                              first_name: { title: 'PRIMER NOMBRE' },
                              second_name: { title: 'SEGUNDO NOMBRE' },
                              position: { title: 'CARGO' },
                              gender: { title: 'GÉNERO' },
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
                      <v-flex xs7>
                        <v-card-text class="text-xs-center">
                          <div class="display-2 font-weight-thin">{{ filter.total.active }}</div>
                          <div class="headline font-weight-light">{{ filter.title }}</div>
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
              </template>
            </v-layout>
          </v-flex>
          <v-flex md12 lg4>
            <v-card color="red">
              <v-card dark color="deep-orange darken-2">
                <v-layout row wrap>
                  <v-flex xs4 class="text-xs-center" mt-4>
                    <v-icon size="80">cake</v-icon>
                  </v-flex>
                  <v-flex xs7>
                    <v-card-text class="text-xs-center">
                      <div class="display-2 font-weight-thin">{{ birthday.length }}</div>
                      <div class="headline font-weight-light">Cumpleañeros de {{ $moment($store.getters.dateNow).format('MMMM') }}</div>
                      <div class="subheading font-weight-light mt-2 mb-2" v-if="birthday.length > 0">Felicidades !!!!</div>
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
  data () {
    return {
      phoneHeaders: [
        {
          text: "Ubicación",
          value: "name",
          align: "center"
        }, {
          text: "Dirección",
          value: "position_group.name",
          align: "center"
        }, {
          text: "No. Interno",
          value: "phone_number",
          align: "center"
        },
      ],
      phoneSearch: "",
      employees: [],
      filteredEmployees: [],
      procedures: [],
      positionGroups: []
    }
  },
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
    if (this.$store.getters.role == 'rrhh' || this.$store.getters.role == 'admin') this.getYearFaults()
    this.getLocations()
  },
  methods: {
    async getLocations() {
      try {
        let res = await axios.get(`/location/list`)
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
                obj.color = 'teal lighten-1'
                obj.downloadable = true
                obj.role = null
                break
              case 'consultants':
                obj.data = this.employees.filter(o => {
                  return o.consultant == true
                })
                obj.title = 'Consultores'
                obj.icon = 'work'
                obj.color = 'blue-grey darken-1'
                obj.downloadable = true
                obj.role = null
                break
              case 'withoutContracts':
                obj.data = this.employees.filter(o => {
                  return o.consultant == null && o.active
                })
                obj.title = 'Sin Contratos'
                obj.icon = obj.data.length > 0 ? 'warning' : 'done'
                obj.color = obj.data.length > 0 ? 'red accent-4' : 'green darken-1'
                obj.downloadable = false
                obj.role = 'rrhh'
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

<style>
.card-box {
  height: 170px;
}
</style>
