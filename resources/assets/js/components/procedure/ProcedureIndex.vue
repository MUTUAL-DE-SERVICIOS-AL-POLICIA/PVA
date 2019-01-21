<template>
  <v-container fluid>
    <v-toolbar>
      <v-toolbar-title>Planillas Eventuales</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-flex xs2>
        <v-select
          :disabled="!$store.getters.role == 'admin' || !$store.getters.role == 'rrhh'"
          :items="[1, 2]"
          label="# de Aguinaldos"
          class="mr-3"
          :flat="true"
          v-if="procedures.find((o) => { return (o.month_order == 11 && o.active == false) }) ? true : false"
          v-model="bonusYear.bonus"
          v-on:change="updateBonusYear"
        ></v-select>
      </v-flex>
      <v-flex xs2>
        <v-select
          :items="years"
          xs3 md3
          label="Gestión"
          v-model="yearSelected"
          @change="changeYear"
        ></v-select>
      </v-flex>
      <RemoveItem :bus="bus"/>
      <ProcedureAdd v-if="$store.getters.role == 'admin'" :bus="bus" type="eventual"/>
    </v-toolbar>
    <div v-if="loading">
      <Loading/>
    </div>
    <v-card v-else>
      <v-container
        fluid
        grid-list-md
      >
        <v-layout row wrap>
          <v-flex
            md6 lg4
            v-if="bonusYear.bonus > 0 && $store.getters.permissions.includes('create-procedure-eventual')"
          >
            <v-card color="info" height="100%" dark>
              <v-card-title>
                <v-flex xs6>
                  <div class="font-weight-light display-1">AGUINALDO</div>
                </v-flex>
                <v-flex xs6>
                  <v-text-field flat disabled></v-text-field>
                </v-flex>
              </v-card-title>
              <div>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-menu offset-y v-for="procedure in bonusYear.procedures" :key="procedure.id" class="mr-2 ml-1">
                    <v-btn slot="activator" color="error">
                      <span>{{ procedure.name }}</span>
                      <v-icon small>arrow_drop_down</v-icon>
                    </v-btn>
                    <v-card
                      style="max-height: 360px"
                      class="scroll-y"
                    >
                      <v-list
                        v-for="(item, index) in bonusPrintTypes"
                        v-bind:item="item"
                        v-bind:index="index"
                        v-bind:key="item.type"
                      >
                        <v-list-tile @click="bonusPrint(procedure.id, item.type)">
                          <v-list-tile-content>
                            {{ item.name }}
                          </v-list-tile-content>
                        </v-list-tile>
                      </v-list>
                      <v-list>
                        <v-list-tile @click="bonusProcedure = procedure; bonusDialog = true" v-if="$store.getters.role == 'rrhh' || $store.getters.role == 'admin'">
                          <v-list-tile-content>
                            <div class="blue--text">EDITAR</div>
                          </v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile @click="bus.$emit('openDialogRemove', `/bonus/${procedure.id}`)" v-if="$store.getters.role == 'admin'">
                          <v-list-tile-content>
                            <div class="red--text">ELIMINAR</div>
                          </v-list-tile-content>
                        </v-list-tile>
                      </v-list>
                    </v-card>
                  </v-menu>
                  <v-dialog
                    v-model="bonusDialog"
                    width="600"
                    @keydown.esc="closeEditBonus"
                  >
                    <v-btn color="error" slot="activator" v-if="bonusYear.bonus > bonusYear.procedures.length">
                      Registrar
                    </v-btn>
                    <v-card>
                      <v-toolbar dark color="secondary">
                        <v-toolbar-title class="white--text">{{ 'id' in bonusProcedure ? 'Editar Aguinaldo' : 'Registrar Aguinaldo' }}</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>
                          <v-flex xs7 pr-2>
                            <v-select
                              :items="bonusNames"
                              v-model="bonusProcedure.name"
                              label="Nombre"
                            ></v-select>
                          </v-flex>
                          <v-flex xs5 pl-2>
                            <v-text-field
                              v-validate="'min:0|max:99'"
                              v-model="bonusProcedure.split_percentage"
                              label="Porcentage de Descuento"
                              name="porcentage de descuento"
                              :error-messages="errors.collect('porcentage de descuento')"
                            ></v-text-field>
                          </v-flex>
                        </v-layout>
                        <v-layout row wrap>
                          <v-flex xs6 pr-2>
                            <v-text-field
                              v-validate="'min:0'"
                              v-model="bonusProcedure.lower_limit_wage"
                              label="Salario Límite Inferior"
                              name="salario límite inferior"
                              :error-messages="errors.collect('salario límite inferior')"
                            ></v-text-field>
                          </v-flex>
                          <v-flex xs6 pr-2>
                            <v-text-field
                              v-validate="'min:0'"
                              v-model="bonusProcedure.upper_limit_wage"
                              label="Salario Límite Superior"
                              name="salario límite superior"
                              :error-messages="errors.collect('salario límite superior')"
                            ></v-text-field>
                          </v-flex>
                        </v-layout>
                        <div class="text-md-center">
                          <div>Fecha de Pago</div>
                          <v-date-picker :min="$moment(this.$store.getters.dateNow).month(11).date(4).toISOString()" locale="es-bo" v-model="bonusProcedure.pay_date"></v-date-picker>
                        </div>
                      </v-card-text>
                      <v-divider></v-divider>
                      <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="error" @click.native="closeEditBonus"><v-icon>close</v-icon> Cancelar</v-btn>
                        <v-btn color="success" :disabled="this.errors.any()" @click.native="saveBonus"><v-icon>check</v-icon> Guardar</v-btn>
                      </v-card-actions>
                    </v-card>
                  </v-dialog>
                </v-card-actions>
              </div>
            </v-card>
          </v-flex>
          <v-flex
            v-for="procedure in procedures"
            :key="procedure.id"
            md6 lg4
          >
            <v-card :color="procedure.active ? 'warning' : 'green lighten-4'" height="100%">
              <v-card-title>
                <v-flex xs6>
                  <div class="font-weight-light display-1">{{ procedure.month_name || $moment().month(procedure.month_id-1).format('MMMM').toUpperCase() }}</div>
                </v-flex>
                <v-flex xs6>
                  <v-text-field
                    slot="activator"
                    :value="$moment(procedure.pay_date).format('DD/MM/YYYY')"
                    label="Fecha de Pago"
                    prepend-icon="event"
                    readonly
                    v-if="procedure.pay_date"
                  ></v-text-field>
                  <v-text-field
                    slot="activator"
                    label="Fecha de Pago"
                    prepend-icon="event"
                    disabled
                    v-else
                  ></v-text-field>
                </v-flex>
              </v-card-title>
              <div>
                <v-card-actions v-if="!procedure.new">
                  <v-spacer></v-spacer>
                  <v-btn icon v-if="procedure.active && $store.getters.permissions.includes('update-procedure-eventual')" :to="{ name: 'procedureEdit', params: { id: procedure.id }}" >
                    <v-tooltip top>
                      <v-icon slot="activator" :color="procedure.active ? 'info' : 'primary'">edit</v-icon>
                      <span>Editar</span>
                    </v-tooltip>
                  </v-btn>
                  <v-btn icon v-if="$store.getters.role == 'rrhh' || $store.getters.role == 'admin'">
                    <v-tooltip top>
                      <v-btn slot="activator" icon flat @click.prevent="print(`/ticket/print/${procedure.id}`)">
                        <v-icon :color="procedure.active ? 'info' : 'primary'">print</v-icon>
                      </v-btn>
                      <span>Imprimir boletas</span>
                    </v-tooltip>
                  </v-btn>
                  <v-btn icon @click="download(`/payroll/print/txt/${procedure.year}/${procedure.month_order}`)" v-if="$store.getters.role == 'rrhh' || $store.getters.role == 'admin'">
                    <v-tooltip top>
                      <v-icon slot="activator" :color="procedure.active ? 'info' : 'primary'">account_balance</v-icon>
                      <span>TXT Banco</span>
                    </v-tooltip>
                  </v-btn>
                  <v-btn icon @click="download(`/payroll/print/ovt/${procedure.year}/${procedure.month_order}?report_type=H&report_name=OVT&valid_contracts=0&with_account=0`)" v-if="$store.getters.role == 'rrhh' || $store.getters.role == 'admin'">
                    <v-tooltip top>
                      <v-icon slot="activator" :color="procedure.active ? 'info' : 'primary'">work</v-icon>
                      <span>CSV OVT</span>
                    </v-tooltip>
                  </v-btn>
                  <v-spacer></v-spacer>
                  <v-menu offset-y class="mr-2" v-if="$store.getters.role == 'financiera' || $store.getters.role == 'admin'">
                    <v-btn slot="activator" :color="procedure.active ? 'info' : 'primary'">
                      <span>AFP</span>
                      <v-icon small>arrow_drop_down</v-icon>
                    </v-btn>
                    <v-card
                      class="scroll-y"
                    >
                      <v-list
                        v-for="(item, index) in managementEntities"
                        v-bind:item="item"
                        v-bind:index="index"
                        v-bind:key="item.id"
                      >
                        <div>
                          <v-list-tile @click="xls(`/payroll/print/afp/${item.id}/${procedure.year}/${procedure.month_order}`)">
                            <span class="caption">{{ item.name }}</span>
                          </v-list-tile>
                        </div>
                      </v-list>
                    </v-card>
                  </v-menu>
                  <v-menu offset-y v-if="$store.getters.role == 'rrhh' || $store.getters.role == 'financiera' || $store.getters.role == 'admin'">
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
                          <v-list-tile @click="print(`/payroll/print/pdf/${procedure.year}/${procedure.month_order}?report_type=${item}&report_name=B-${index}&valid_contracts=1&with_account=1`)">
                            <span class="caption">B-{{ ++index }} ({{ item }}.)</span>
                          </v-list-tile>
                        </div>
                        <div v-else-if="item == 'P'">
                          <v-list-tile @click="print(`/payroll/print/pdf/${procedure.year}/${procedure.month_order}?report_type=P&report_name=B-2&valid_contracts=0&with_account=0&management_entity=0&position_group=0&employer_number=1`)">
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
                        <v-list-tile @click="print(`/payroll/print/pdf/${procedure.year}/${procedure.month_order}?report_type=${item}&report_name=A-${index}&valid_contracts=0&with_account=0`)">
                          <span class="caption">A-{{ ++index }} ({{ item }}.)</span>
                        </v-list-tile>
                      </v-list>
                      <v-list>
                        <v-list-tile @click="print(`/payroll/print/pdf/${procedure.year}/${procedure.month_order}?report_type=T&report_name=A-3&valid_contracts=0&with_account=0`)">
                          <span class="caption">A-3 (T.)</span>
                        </v-list-tile>
                        <v-list-tile @click="print(`/payroll/print/pdf/${procedure.year}/${procedure.month_order}?report_type=T&report_name=A-8&valid_contracts=0&with_account=0`)">
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
                          <v-list-tile @click="print(`/payroll/print/pdf/${procedure.year}/${procedure.month_order}?report_type=${t}&report_name=A-${parseInt(`${indexT}${indexM}`, 2) + 4}&valid_contracts=0&with_account=0&management_entity=${m.id}`)">
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
                              @click="print(`/payroll/print/pdf/${procedure.year}/${procedure.month_order}?report_type=${t}&report_name=${t}-${indexT+indexE+4}&valid_contracts=0&with_account=0&management_entity=0&position_group=0&employer_number=${e.id}`)"
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
                  <v-btn
                    color="info"
                    @click="storeProcedure"
                    v-if="$store.getters.permissions.includes('create-procedure-eventual')"
                  >
                    Registrar
                  </v-btn>
                </v-card-actions>
              </div>
            </v-card>
          </v-flex>
        </v-layout>
      </v-container>
    </v-card>
  </v-container>
</template>

<script>
import Vue from "vue"
import ProcedureAdd from "./ProcedureAdd"
import RemoveItem from "../RemoveItem"
import Loading from "../Loading"

export default {
  name: "ProcedureIndex",
  components: {
    ProcedureAdd,
    RemoveItem,
    Loading
  },
  data() {
    return {
      bus: new Vue(),
      loading: true,
      years: [],
      procedures: [],
      newProcedure: {
        new: true,
        year: this.$moment().year(),
        month_id: this.$moment().month() + 1,
        month: this.$moment().month(),
        active: true
      },
      yearSelected: null,
      templateTypes: ["H", "P"],
      managementEntities: [],
      employerNumbers: [],
      bonusYear: {
        bonus: 0,
        procedures: []
      },
      bonusDialog: false,
      bonusNames: ['AGUINALDO DE NAVIDAD', 'ESFUERZO POR BOLIVIA'],
      bonusPrintTypes: [
        {
          name: 'PLANILLA',
          type: 'pdf'
        }, {
          name: 'BANCO',
          type: 'txt'
        }, {
          name: 'OVT',
          type: 'csv'
        }, {
          name: 'BOLETAS DE PAGO',
          type: 'ticket'
        }
      ],
      bonusProcedure: {}
    };
  },
  mounted() {
    this.getYears()
    this.getManagementEntities()
    this.getEmployerNumbers()
    this.bus.$on("closeDialog", year => {
      this.getYears(year)
      this.changeYear()
    })
  },
  methods: {
    closeEditBonus() {
      this.bonusDialog = false
      this.clearBonusProcedure()
    },
    clearBonusProcedure() {
      this.bonusProcedure = {
        name: this.bonusYear.procedures ? this.bonusNames[this.bonusYear.procedures.length] : this.bonusNames[0],
        year: this.yearSelected,
        pay_date: this.$store.getters.dateNow
      }
    },
    async saveBonus() {
      try {
        if (this.bonusProcedure.lower_limit_wage == 0 || this.bonusProcedure.lower_limit_wage == "") {
          this.bonusProcedure.lower_limit_wage = null
        }
        if (this.bonusProcedure.upper_limit_wage == 0 || this.bonusProcedure.upper_limit_wage == "") {
          this.bonusProcedure.upper_limit_wage = null
        }
        if (this.bonusProcedure.split_percentage == 0 || this.bonusProcedure.split_percentage == "") {
          this.bonusProcedure.split_percentage = null
        }
        if ('id' in this.bonusProcedure) {
          let res = await axios.patch(`/bonus/${this.bonusProcedure.id}`, this.bonusProcedure)
          this.getBonusYear(this.bonusYear.year)
        } else {
          let res = await axios.post(`/bonus`, this.bonusProcedure)
          this.bonusYear.procedures.push(res.data)
        }
        this.clearBonusProcedure()
        this.toastr.success('Guardado correctamente')
        this.closeEditBonus();
      } catch (e) {
        console.log(e)
      }
    },
    bonusPrint(id, type) {
      let url = `/bonus/print/${id}?report_type=${type}`
      if (type == 'pdf' || type == 'ticket') {
        this.print(url)
      } else {
        this.download(url)
      }
    },
    async updateBonusYear() {
      try {
        let res = await axios.patch(`/bonus_procedure/${this.bonusYear.year}`, this.bonusYear)
        this.bonusYear = res.data
        this.toastr.success('Actualizado correctamente')
      } catch (e) {
        console.log(e)
        this.toastr.error(`Ya están registrados ${this.bonusYear.procedures.length} aguinaldos`)
        this.getBonusYear(this.bonusYear.year)
      }
    },
    async getLastProcedure() {
      try {
        let res = await axios.get(`/procedure/order/last`);
        if (res.data.id) {
          if (!res.data.active) {
            res = await axios.get(`/procedure/date/${res.data.id}`);
            let newDate = this.$moment(res.data.first_date).add(1, "months");
            this.newProcedure.year = newDate.year();
            res = await axios.get(`/month/order/${newDate.month() + 1}`);
            this.newProcedure.month_id = res.data.id;
            this.newProcedure.month = res.data.order - 1;
            if (this.newProcedure.year > this.$moment(this.$store.getters.dateNow).year() || (this.newProcedure.month == 0 && this.newProcedure.year == this.$moment(this.$store.getters.dateNow).year())) {
              this.years.unshift(this.newProcedure.year)
            }
          }
        }
        this.loading = false
        return Promise.resolve();
      } catch (e) {
        console.log(e);
        return Promise.reject();
      }
    },
    commaDivider(index, cities) {
      if (index + 1 < cities.length) {
        return ",";
      }
      return "";
    },
    async xls(url) {
      try {
        this.loading = true;
        let res = await axios({
          method: "GET",
          url: url,
          responseType: "arraybuffer"
        });
        const blob = new Blob([res.data], {
          type: res.headers["content-type"]
        });
        let link = document.createElement("a");
        link.href = window.URL.createObjectURL(blob);
        const contentDisposition = res.headers["content-disposition"];
        let fileName = `sueldos_${url.split('/').slice(-1)[0]}_${url.split('/').slice(-2)[0]}`;
        if (res.headers['content-type'].includes('sheet')) {
          fileName = `${fileName}.xlsx`
        } else if (res.headers['content-type'].includes('csv')) {
          fileName = `${fileName}.csv`
        }
        if (contentDisposition) {
          let fileNameMatch = contentDisposition.match(/filename="(.+)"/);
          if (!fileNameMatch) fileNameMatch = contentDisposition.match(/filename=(.+)/);
          if (fileNameMatch.length === 2) {
            fileName = fileNameMatch[1];
          }
        }
        link.download = fileName;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        this.loading = false;
      } catch (e) {
        this.loading = false;
        console.log(e);
      }
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
    async download(url) {
      try {
        this.loading = true;
        const res = await axios.get(url);
        const blob = new Blob([res.data], {
          type: res.headers["content-type"]
        });
        let link = document.createElement("a");
        link.href = window.URL.createObjectURL(blob);
        let fileName = `sueldos_${url.split('/').slice(-1)[0].split('?')[0]}_${url.split('/').slice(-2)[0]}`;
        const contentDisposition = res.headers["content-disposition"];
        if (res.headers['content-type'].includes('sheet')) {
          fileName = `${fileName}.xlsx`
        } else if (res.headers['content-type'].includes('csv')) {
          fileName = `${fileName}.csv`
        }
        if (contentDisposition) {
          const fileNameMatch = contentDisposition.match(/filename="(.+)"/);
          if (fileNameMatch.length === 2) {
            fileName = fileNameMatch[1];
          }
        }
        link.download = fileName;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        this.loading = false;
      } catch (e) {
        this.loading = false;
        console.log(e);
      }
    },
    async getEmployerNumbers() {
      try {
        let res = await axios.get(`/employer_number`);
        this.employerNumbers = res.data;
        this.changeYear();
      } catch (e) {
        console.log(e);
      }
    },
    async getYears(year) {
      try {
        let res = await axios.get(`/procedure/year/list`);
        this.years = res.data;
        this.yearSelected = year || Math.max(...this.years);
      } catch (e) {
        console.log(e);
      }
    },
    async getManagementEntities() {
      try {
        let res = await axios.get(`/management_entity`);
        this.managementEntities = res.data;
      } catch (e) {
        console.log(e);
      }
    },
    async getProcedures(year) {
      try {
        await this.getLastProcedure();
        let res = await axios.get(`/procedure/year/${year}`);
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
      if (this.years.length == 0) {
        this.years.unshift(this.newProcedure.year);
        this.yearSelected = this.newProcedure.year;
      }
      this.getProcedures(this.yearSelected);
      this.getBonusYear(this.yearSelected);
    },
    async getBonusYear(year) {
      try {
        let res = await axios.get(`bonus_procedure/${year}`)
        this.bonusYear = res.data
        this.bonusProcedure.year = year
        this.clearBonusProcedure()
      } catch (e) {
        console.log(e)
        this.bonusYear.year = year
        this.bonusProcedure.year = year
        this.clearBonusProcedure()
      }
    },
    async storeProcedure() {
      try {
        this.loading = true;
        let procedure = await axios.post(
          `/procedure`,
          this.newProcedure
        );
        procedure = procedure.data;
        let payrolls = await axios.post(
          `/procedure/${procedure.id}/payroll`
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
        this.loading = false;
      } catch (e) {
        this.loading = false;
        console.log(e);
      }
    }
  }
};
</script>