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
            <v-card :color="procedure.active ? 'warning' : 'green lighten-4'" height="100%">
              <v-card-title>
                <div class="font-weight-light display-1">{{ procedure.month_name || $moment().month(procedure.month_id-1).format('MMMM').toUpperCase() }}</div>
              </v-card-title>
              <v-progress-linear :indeterminate="true" v-if="loading"></v-progress-linear>
              <div v-else>
                <v-card-actions v-if="!procedure.new">
                  <v-spacer></v-spacer>
                  <v-btn icon v-if="procedure.active&&options.includes('edit')" :to="{ name: 'procedureEdit', params: { id: procedure.id }}" >
                    <v-tooltip top>
                      <v-icon slot="activator" :color="procedure.active ? 'info' : 'primary'">edit</v-icon>
                      <span>Editar</span>
                    </v-tooltip>
                  </v-btn>
                  <v-btn icon v-if="options.includes('ticket')">
                    <v-tooltip top>
                      <v-icon slot="activator" :color="procedure.active ? 'info' : 'primary'" @click="print(`/api/v1/ticket/print/${procedure.id}`)">print</v-icon>
                      <span>Imprimir boletas</span>
                    </v-tooltip>
                  </v-btn>
                  <v-btn icon :href="`/api/v1/payroll/print/txt/${procedure.year}/${procedure.month_order}`" v-if="options.includes('bank')">
                    <v-tooltip top>
                      <v-icon slot="activator" :color="procedure.active ? 'info' : 'primary'">account_balance</v-icon>
                      <span>TXT Banco</span>
                    </v-tooltip>
                  </v-btn>
                  <v-btn icon :href="`/api/v1/payroll/print/ovt/${procedure.year}/${procedure.month_order}?report_type=H&report_name=OVT&valid_contracts=0&with_account=0`" v-if="options.includes('ovt')">
                    <v-tooltip top>
                      <v-icon slot="activator" :color="procedure.active ? 'info' : 'primary'">work</v-icon>
                      <span>CSV OVT</span>
                    </v-tooltip>
                  </v-btn>
                  <v-spacer></v-spacer>
                  <v-menu offset-y v-if="options.includes('payroll')">
                    <v-btn slot="activator" :color="procedure.active ? 'info' : 'primary'">
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
                          <v-list-tile @click="print(`/api/v1/payroll/print/pdf/${procedure.year}/${procedure.month_order}?report_type=${item}&report_name=B-${index}&valid_contracts=1&with_account=1`)">
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
                        <v-list-tile @click="print(`/api/v1/payroll/print/pdf/${procedure.year}/${procedure.month_order}?report_type=T&report_name=A-8&valid_contracts=0&with_account=0`)">
                          <span class="caption">A-8 (T.)</span>
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
              </div>
              <v-card-actions v-else>
                <v-spacer></v-spacer>
                <v-btn
                  :disabled="dialog"
                  :loading="dialog"
                  color="info"
                  @click="storeProcedure"
                  v-if="options.includes('new')"
                >
                  Registrar
                </v-btn>
                <v-dialog
                  v-model="dialog"
                  hide-overlay
                  persistent
                  width="300"
                >
                  <v-card
                    color="primary"
                    dark
                  >
                    <v-card-text class="title">
                      Generando planillas...
                      <v-progress-linear
                        indeterminate
                        color="white"
                        class="mb-0"
                      ></v-progress-linear>
                    </v-card-text>
                  </v-card>
                </v-dialog>
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
      loading: false,
      dialog: false,
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
  created() {
    for (var i = 0; i < this.$store.getters.menuLeft.length; i++) {
      if (this.$store.getters.menuLeft[i].href == "procedureIndex") {
        this.options = this.$store.getters.menuLeft[i].options;
      }
    }
  },
  methods: {
    commaDivider(index, cities) {
      if (index + 1 < cities.length) {
        return ",";
      }
      return "";
    },
    async print(url) {
      try {
        this.loading = true;
        let res = await axios({
          method: "GET",
          url: url,
          responseType: "arraybuffer"
        });
        let blob = new Blob([res.data], {
          type: "application/pdf"
        });
        printJS(window.URL.createObjectURL(blob));
        this.loading = false;
      } catch (e) {
        this.loading = false;
        console.log(e);
      }
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
            }).length == 0 &&
            this.procedures.filter(obj => {
              return obj.active == true;
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
        this.dialog = true;
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
        this.dialog = false;
      } catch (e) {
        this.dialog = false;
        console.log(e);
      }
    }
  }
};
</script>