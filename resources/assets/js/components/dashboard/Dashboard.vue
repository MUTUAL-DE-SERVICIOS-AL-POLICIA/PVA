<template>
  <v-container fluid>
    <v-card>
      <v-container fluid grid-list-md>
        <v-layout row wrap>
          <v-flex xs12 sm4>
            <v-card color="info" dark>
              <v-layout row wrap>
                <v-flex xs3 class="text-xs-center" mt-4>
                  <v-icon size="80">settings_backup_restore</v-icon>
                </v-flex>
                <v-flex xs9>
                  <v-card-text class="text-xs-center">
                    <div class="display-3 font-weight-thin">{{ eventuals.active.length }}</div>
                    <div class="display-1 font-weight-light">Eventuales</div>
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
    eventuals: {
      active: [],
      inactive: []
    },
    consultants: {
      active: [],
      inactive: []
    },
    withoutContracts: []
  }),
  created() {
    this.getEmployees()
  },
  methods: {
    async getEmployees(active = this.active) {
      try {
        let res = await axios.get(`/employee`)
        this.employees = res.data
        let eventuals = this.employees.filter(o => {
          return o.consultant == false
        })
        let consultants = this.employees.filter(o => {
          return o.consultant == true
        })
        this.withoutContracts = this.employees.filter(o => {
          return o.consultant == null
        })
        this.eventuals.active = eventuals.filter(o => {
          return o.active == true
        })
        this.eventuals.inactive = eventuals.filter(o => {
          return o.active == false
        })
        this.consultants.active = consultants.filter(o => {
          return o.active == true
        })
        this.consultants.inactive = consultants.filter(o => {
          return o.active == false
        })
      } catch (e) {
        console.log(e);
      }
    },
  }
};
</script>
