<template>
  <v-container>
    <v-toolbar>
      <v-toolbar-title>Planillas</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-flex xs2>
        <v-select
          :items="years"
          xs3 md3
          label="Gestión"
          v-model="yearSelected"
          @change="changeYear"
        ></v-select>
      </v-flex>
    </v-toolbar>
    <v-card>
        <v-container
          fluid
          grid-list-md
        >
          <v-layout row wrap>
            <v-flex
              v-for="procedure in procedures"
              :key="procedure.id"
              xs12 sm4
            >
              <v-card :color="procedure.active ? 'lime lighten-4' : 'green lighten-5'">
                <v-card-title>
                  <h3>{{ procedure.month_name }}</h3>
                </v-card-title>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn icon>
                    <v-tooltip top>
                      <v-icon slot="activator" color="primary">edit</v-icon>
                      <span>Editar</span>
                    </v-tooltip>
                  </v-btn>
                  <v-btn icon>
                    <v-tooltip top>
                      <v-icon slot="activator" color="primary">print</v-icon>
                      <span>Imprimir boletas</span>
                    </v-tooltip>
                  </v-btn>
                  <v-btn icon>
                    <v-tooltip top>
                      <v-icon slot="activator" color="primary">account_balance</v-icon>
                      <span>TXT Banco</span>
                    </v-tooltip>
                  </v-btn>
                  <v-btn icon>
                    <v-tooltip top>
                      <v-icon slot="activator" color="primary">work</v-icon>
                      <span>CSV OVT</span>
                    </v-tooltip>
                  </v-btn>
                  <v-spacer></v-spacer>
                  <v-select
                    label="Planillas"
                  ></v-select>
                </v-card-actions>
              </v-card>
            </v-flex>
          </v-layout>
        </v-container>
      </v-card>
  </v-container>
</template>

<script>
export default {
  name: "ProcedureIndex",
  data() {
    return {
      years: [],
      procedures: [],
      yearSelected: null,
      payrolls: [
        'B-1 (H)'
      ]
    };
  },
  async mounted() {
    try {
      await this.getYears();
    } catch (e) {
      console.log(e);
    }
  },
  methods: {
    async getYears() {
      try {
        let res = await axios.get(`/api/v1/procedure/year/list`);
        this.years = res.data;
        this.yearSelected = Math.max(...this.years);
      } catch (e) {
        console.log(e);
        return Promise.reject(e);
      }
    },
    async getProcedures(year) {
      try {
        let res = await axios.get(`/api/v1/procedure/year/${year}`);
        this.procedures = res.data;
      } catch (e) {
        console.log(e);
      }
    },
    changeYear() {
      this.getProcedures(this.yearSelected);
    }
  }
};
</script>