<template>
  <v-container fluid>
    <v-card>
      <v-container fluid grid-list-md>
        <v-layout row wrap>
          <v-flex xs12 sm4 v-for="filter in filteredEmployees" :key="filter.icon" v-if="$store.getters.currentUser.roles.length > 0">
            <v-card :color="filter.color" dark>
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
                        phone_number: { title: 'TELÉFONO'}
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
                      <div slot="activator" class="subheading font-weight-light">Nuevos este mes: {{ filter.new.length }}</div>
                      <div v-for="(item, index) in filter.new" :key="item.id">{{ `${++index}.- ${item.last_name} ${item.mothers_last_name} ${item.first_name} ${item.second_name}` }}</div>
                    </v-tooltip>
                    <div v-else-if="'new' in filter && filter.new.length == 0" slot="activator" class="subheading font-weight-light">Nuevos este mes: {{ filter.new.length }}</div>
                    <div v-if="'inactive' in filter.total" class="subheading font-weight-light">Inactivos: {{ filter.total.inactive }}</div>
                    <br v-else class="display-2">
                  </v-card-text>
                </v-flex>
              </v-layout>
            </v-card>
          </v-flex>
          <v-flex xs12 sm4 class="mt-3" v-if="$store.getters.currentUser.roles.length > 0">
            <v-card dark v-if="$store.getters.currentUser.roles[0].name == 'admin' || $store.getters.currentUser.roles[0].name == 'rrhh'">
              <v-layout row wrap>
                <v-flex xs4 class="text-xs-center" mt-4>
                  <v-icon size="80">attach_money</v-icon>
                  <div class="text-xs-left mt-4 ml-3">
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
                    <div class="display-1 font-weight-light">Fondo Social</div>
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
          <v-flex xs12 sm4 class="mt-3">
            <v-hover>
              <v-card dark color="blue-grey darken-2" slot-scope="{ hover }">
                <v-layout row wrap>
                  <v-flex xs4 class="text-xs-center" mt-4>
                    <v-icon size="80">cake</v-icon>
                    <div class="text-xs-left mt-4 ml-3">
                      <vue-json-to-csv
                          :json-data="birthday"
                          :labels = "{
                            first_name: { title: 'PRIMER NOMBRE' },
                            second_name: { title: 'SEGUNDO NOMBRE' },
                            last_name: { title: 'APELLIDO PATERNO' },
                            mothers_last_name: { title: 'APELLIDO MATERNO' }
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
                  </v-flex>
                  <v-flex xs8>
                    <v-card-text class="text-xs-center">
                      <div class="display-3 font-weight-thin">{{ birthday.length }}</div>
                      <div class="display-1 font-weight-light">Cumpleañeros del Mes</div>
                      <div class="title font-weight-light mb-2" v-if="hover">Felicidades !!!!</div>
                      <table v-if="hover">
                        <tr v-for="(e, i) in birthday" :key="e.id">
                          <td class="text-xs-rigth">{{ ++i }}.</td>
                          <td class="text-xs-left">{{ e.first_name }} {{ e.last_name }}</td>
                        </tr>
                      </table>
                    </v-card-text>
                  </v-flex>
                </v-layout>
              </v-card>
            </v-hover>
          </v-flex>
          <v-flex xs12 sm4 class="mt-3" v-if="$store.getters.currentUser.roles.length == 0">
            <v-card color="blue darken-4" dark :to="{ name: 'departureIndex'}">
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
    employees: [],
    filteredEmployees: [],
    procedures: []
  }),
  computed: {
    birthday() {
      return this.employees.filter(o => {
        return (o.active == true && this.$moment(`${o.birth_date.split('/')[2]}-${o.birth_date.split('/')[1]}-${o.birth_date.split('/')[0]}`).month() == this.$moment(this.$store.getters.dateNow).month())
      })
    }
  },
  created() {
    this.getEmployees()
    this.getYearFaults()
  },
  methods: {
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
