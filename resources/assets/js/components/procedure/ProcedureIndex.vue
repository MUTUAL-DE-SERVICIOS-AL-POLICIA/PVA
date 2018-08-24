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
            <v-card :color="procedure.active ? 'lime lighten-4' : 'green lighten-5'" height="100%">
              <v-card-title>
                <h3>{{ procedure.month_name || $moment().month(procedure.month_id-1).format('MMMM').toUpperCase() }}</h3>
              </v-card-title>
              <v-card-actions v-if="!procedure.new">
                <v-spacer></v-spacer>
                <v-btn icon v-if="procedure.active">
                  <v-tooltip top>
                    <router-link slot="activator" :to="{ name: 'procedureEdit', params: { id: procedure.id }}">
                      <v-icon color="primary">edit</v-icon>
                    </router-link>
                    <span>Editar</span>
                  </v-tooltip>
                </v-btn>
                <v-btn icon>
                  <v-tooltip top>
                    <v-icon slot="activator" color="primary" @click="print(procedure.id)">print</v-icon>
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
                  :items="payrolls"
                ></v-select>
              </v-card-actions>
              <v-card-actions v-else>
                <v-spacer></v-spacer>
                <v-btn color="primary" @click="storeProcedure()">
                  Registrar
                </v-btn>
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
      newProcedure: {
        new: true,
        year: this.$moment().year(),
        month_id: this.$moment().month() + 1,
        active: true
      },
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
        if (year == this.newProcedure.year) {
          if(this.procedures.filter(obj => {
            return this.newProcedure.month_id == obj.month_order
          }).length == 0) {
            this.procedures.unshift(this.newProcedure)
          }
        }
      } catch (e) {
        console.log(e);
      }
    },
    changeYear() {
      this.getProcedures(this.yearSelected);
    },
    async storeProcedure() {
      try {
        
        let procedure = await axios.post(`/api/v1/procedure`, this.newProcedure);
        procedure = procedure.data;
        let payrolls = await axios.post(`/api/v1/procedure/${procedure.id}/payroll`);
        payrolls = payrolls.data;
        this.getProcedures(this.newProcedure.year)
        this.toastr.success(`Generados ${payrolls.generated} registros para el mes de ${this.$moment().month(procedure.month_id-1).format('MMMM').toUpperCase()}`)
      } catch (e) {
        console.log(e);
      }
    },
    print (item) {
      printJS({printable:"api/v1/ticket/print/" + item, type:"pdf", showModal:true, modalMessage: "Generando documento por favor espere un momento."})    },
  }
};
</script>