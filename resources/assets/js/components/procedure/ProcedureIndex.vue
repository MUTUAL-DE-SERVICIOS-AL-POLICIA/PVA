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
                    <v-icon slot="activator" color="primary" @click="printTicket(procedure.id)">print</v-icon>
                    <span>Imprimir boletas</span>
                  </v-tooltip>
                </v-btn>
                <v-btn icon :href="`/api/v1/payroll/print/txt/${procedure.year}/${procedure.month_order}`">
                  <v-tooltip top>
                    <v-icon slot="activator" color="primary">account_balance</v-icon>
                    <span>TXT Banco</span>
                  </v-tooltip>
                </v-btn>
                <v-btn icon :href="`/api/v1/payroll/print/ovt/${procedure.year}/${procedure.month_order}?report_type=H&report_name=OVT&valid_contracts=0&with_account=0`">
                  <v-tooltip top>
                    <v-icon slot="activator" color="primary">work</v-icon>
                    <span>CSV OVT</span>
                  </v-tooltip>
                </v-btn>
                <v-spacer></v-spacer>
                <v-menu offset-y>
                  <v-btn slot="activator" color="primary">
                    <span>Planillas</span>
                    <v-icon small>arrow_drop_down</v-icon>
                  </v-btn>
                  <v-card
                    style="max-height: 360px"
                    class="scroll-y"
                  >
                    <v-list
                      v-for="(item, index) in templateTypes"
                      v-bind:item="item"
                      v-bind:index="index"
                      v-bind:key="item.id"
                    >
                      <div v-if="item == 'H'">
                        <v-list-tile @click="print(`/api/v1/payroll/print/pdf/${procedure.year}/${procedure.month_order}?report_type=${item}&report_name=B-${++index}&valid_contracts=1&with_account=1`)">
                          <span class="caption">B-{{ ++index }} ({{ item }}.)</span>
                        </v-list-tile>
                      </div>
                      <div v-else-if="item == 'P'">
                        <v-list-tile @click="print(`/api/v1/payroll/print/pdf/${procedure.year}/${procedure.month_order}?report_type=P&report_name=B-2&valid_contracts=0&with_account=0&management_entity=0&position_group=0&employer_number=1`)">
                          <span class="caption">B-{{ ++index }} ({{ item }}.)</span>
                        </v-list-tile>
                      </div>
                    </v-list>
                    <v-list
                      v-for="(item, index) in templateTypes"
                      v-bind:item="item"
                      v-bind:index="index"
                      v-bind:key="item.id"
                    >
                      <v-list-tile @click="print(`/api/v1/payroll/print/pdf/${procedure.year}/${procedure.month_order}?report_type=${item}&report_name=A-${index}&valid_contracts=0&with_account=0`)">
                        <span class="caption">A-{{ ++index }} ({{ item }}.)</span>
                      </v-list-tile>
                    </v-list>
                    <v-list>
                      <v-list-tile @click="print(`/api/v1/payroll/print/pdf/${procedure.year}/${procedure.month_order}?report_type=T&report_name=A-3&valid_contracts=0&with_account=0`)">
                        <span class="caption">A-3 (T.)</span>
                      </v-list-tile>
                    </v-list>
                    <v-list
                      v-for="(t, indexT) in templateTypes"
                      v-bind:item="t"
                      v-bind:index="indexT"
                      v-bind:key="t.id"
                    >
                      <div
                        v-for="(m, indexM) in managementEntities"
                        v-bind:item="m"
                        v-bind:index="indexM"
                        v-bind:key="m.id"
                      >
                        <v-list-tile @click="print(`/api/v1/payroll/print/pdf/${procedure.year}/${procedure.month_order}?report_type=${t}&report_name=A-${parseInt(`${indexT}${indexM}`, 2) + 4}&valid_contracts=0&with_account=0&management_entity=${m.id}`)">
                          <span class="caption">A-{{ parseInt(`${indexT}${indexM}`, 2) + 4 }} ({{ t }}. AFP {{ m.name }})</span>
                        </v-list-tile>
                      </div>
                    </v-list>
                    <v-list
                      v-for="(t, indexT) in templateTypes"
                      v-bind:item="t"
                      v-bind:index="indexT"
                      v-bind:key="t.id"
                    >
                      <div
                        v-for="(e, indexE) in employerNumbers"
                        v-bind:item="e"
                        v-bind:index="indexE"
                        v-bind:key="e.id"
                      >
                        <v-tooltip left>
                          <v-list-tile
                            slot="activator"
                            v-if="e.cities.length > 0"
                            @click="print(`/api/v1/payroll/print/pdf/${procedure.year}/${procedure.month_order}?report_type=${t}&report_name=${t}-${indexT+indexE+4}&valid_contracts=0&with_account=0&management_entity=0&position_group=0&employer_number=${e.id}`)"
                          >
                            <span class="caption">{{ t }}-{{ e.id }} ({{ t }}.</span>
                            <span
                              v-for="(c, indexC) in e.cities"
                              v-bind:item="c"
                              v-bind:index="indexC"
                              v-bind:key="c.id"
                              class="caption"
                            >&nbsp;{{ c.name }}{{ commaDivider(indexC, e.cities) }}</span>
                            <span class="caption">)</span>
                          </v-list-tile>
                          <span>#Empleador: {{ e.number }}</span>
                        </v-tooltip>
                      </div>
                    </v-list>
                  </v-card>
                </v-menu>
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
      templateTypes: ["H", "P"],
      managementEntities: [],
      employerNumbers: []
    };
  },
  mounted() {
    this.getYears();
    this.getManagementEntities();
    this.getEmployerNumbers();
  },
  methods: {
    commaDivider(index, cities) {
      if (index + 1 < cities.length) {
        return ",";
      }
      return "";
    },
    print(url) {
      printJS({
        printable: url,
        type: "pdf",
        showModal: true,
        modalMessage: "Generando documento por favor espere un momento."
      });
    },
    async getEmployerNumbers() {
      try {
        let res = await axios.get(`/api/v1/employer_number`);
        this.employerNumbers = res.data;
      } catch (e) {
        console.log(e);
      }
    },
    async getYears() {
      try {
        let res = await axios.get(`/api/v1/procedure/year/list`);
        this.years = res.data;
        this.yearSelected = Math.max(...this.years);
      } catch (e) {
        console.log(e);
      }
    },
    async getManagementEntities() {
      try {
        let res = await axios.get(`/api/v1/management_entity`);
        this.managementEntities = res.data;
      } catch (e) {
        console.log(e);
      }
    },
    async getProcedures(year) {
      try {
        let res = await axios.get(`/api/v1/procedure/year/${year}`);
        this.procedures = res.data;
        if (year == this.newProcedure.year) {
          if (
            this.procedures.filter(obj => {
              return this.newProcedure.month_id == obj.month_order;
            }).length == 0
          ) {
            this.procedures.unshift(this.newProcedure);
          }
        }
      } catch (e) {
        console.log(e);
      }
    },
    changeYear() {
      if (this.years.length > 0) {
        this.getProcedures(this.yearSelected);
      } else {
        this.years.unshift(this.newProcedure.year);
        this.yearSelected = this.newProcedure.year;
      }
    },
    async storeProcedure() {
      try {
        let procedure = await axios.post(
          `/api/v1/procedure`,
          this.newProcedure
        );
        procedure = procedure.data;
        let payrolls = await axios.post(
          `/api/v1/procedure/${procedure.id}/payroll`
        );
        payrolls = payrolls.data;
        this.getProcedures(this.newProcedure.year);
        this.toastr.success(
          `Generados ${
            payrolls.generated
          } registros para el mes de ${this.$moment()
            .month(procedure.month_id - 1)
            .format("MMMM")
            .toUpperCase()}`
        );
      } catch (e) {
        console.log(e);
      }
    },
    print(url) {
      printJS({
        printable: url,
        type: "pdf",
        showModal: true,
        modalMessage: "Generando documento por favor espere un momento."
      });
    },
    printTicket(item) {
      printJS({
        printable: "api/v1/ticket/print/" + item,
        type: "pdf",
        showModal: true,
        modalMessage: "Generando documento por favor espere un momento."
      });
    }
  }
};
</script>