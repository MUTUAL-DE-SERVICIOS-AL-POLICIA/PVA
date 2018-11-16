<template>
  <v-container fluid>
    <v-card>
      <v-container fluid grid-list-md>
        <v-layout row wrap>
          <v-flex xs12 sm4 v-for="filter in filteredEmployees" :key="filter.icon">
            <v-card :color="filter.color" dark>
              <v-layout row wrap>
                <v-flex xs4 class="text-xs-center" mt-4>
                  <v-icon size="80">{{ filter.icon }}</v-icon>
                  <div class="text-xs-left mt-4 ml-3">
                    <v-icon>save</v-icon>
                  </div>
                </v-flex>
                <v-flex xs8>
                  <v-card-text class="text-xs-center">
                    <div class="display-3 font-weight-thin">{{ filter.total.active }}</div>
                    <div class="display-1 font-weight-light">{{ filter.title }}</div>
                    <div v-if="'inactive' in filter.total" class="subheading font-weight-light">Inactivos: {{ filter.total.inactive }}</div>
                    <br v-else class="subheading font-weight-light">
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
export default {
  name: "dashboard",
  data: () => ({
    employees: [],
    filteredEmployees: []
  }),
  created() {
    this.getEmployees()
  },
  methods: {
    async getEmployees(active = this.active) {
      try {
        let res = await axios.get(`/employee`)
        this.employees = res.data

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
              break
            case 'consultants':
              obj.data = this.employees.filter(o => {
                return o.consultant == true
              })
              obj.title = 'Consultores'
              obj.icon = 'work'
              obj.color = 'deep-orange darken-2'
              break
            case 'withoutContracts':
              obj.data = this.employees.filter(o => {
                return o.consultant == null
              })
              obj.title = 'Sin Contratos'
              obj.icon = obj.data.length > 0 ? 'warning' : 'done'
              obj.color = obj.data.length > 0 ? 'red accent-4' : 'green darken-1'
              break
          }

          if (type == 'withoutContracts') {
            obj.total = {
              active: obj.data.length
            }
          } else {
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
      } catch (e) {
        console.log(e);
      }
    },
  }
};
</script>
