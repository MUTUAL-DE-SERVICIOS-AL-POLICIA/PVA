<template>
  <v-container>
    <v-toolbar>
      <v-toolbar-title>{{ procedure.month.name }}</v-toolbar-title>
      <v-spacer></v-spacer>
      <div class="text-xs-center">
        <v-menu
        v-model="menuDate"
        >
          <v-text-field
            slot="activator"
            v-model="selectedDate"
            label="Fecha de Pago"
            prepend-icon="event"
            readonly
          ></v-text-field>
          <v-date-picker v-model="date" @input="menuDate = false" @change="updatePayDate(procedure.id, date)" locale="es-bo"></v-date-picker>
        </v-menu>
        <v-dialog
          v-model="dialogDelete"
          width="500"
          @keydown.esc="dialogDelete = false"
        >
          <v-btn
            slot="activator"
            color="error"
            dark
            v-if="$store.getters.currentUser.roles[0].name == 'admin'"
          >
            Eliminar Planilla
          </v-btn>
          <v-card>
            <v-card-text class="title">
              ¿Esta seguro que desea eliminar la planilla?
            </v-card-text>
            <v-divider></v-divider>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="success" small @click="dialogDelete = false"><v-icon small>check</v-icon> Cancelar</v-btn>
              <v-btn color="error" small @click="deleteProcedure"><v-icon small>close</v-icon> Eliminar</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
        <v-dialog
          v-model="dialog"
          width="500"
          @keydown.esc="dialog = false"
        >
          <v-btn
            slot="activator"
            color="error"
            dark
          >
            {{ message }} Planilla
          </v-btn>
          <v-card>
            <v-card-text class="title">
              ¿Esta seguro que desea {{ message }} la planilla?
            </v-card-text>
            <v-divider></v-divider>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="success" small @click="dialog = false"><v-icon small>check</v-icon> Cancelar</v-btn>
              <v-btn color="error" small @click="closeProcedure"><v-icon small>close</v-icon> {{ message }}</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </div>
      <v-flex xs2>
        <v-text-field
          v-model="search"
          append-icon="search"
          label="Buscar"
          single-line
          full-width
          clearable
        ></v-text-field>
      </v-flex>
      <PayrollAdd :contracts="contracts" :procedure="procedure" :bus="bus"/>
      <RemoveItem :bus="bus"/>
    </v-toolbar>
    <v-data-table
      :headers="headers"
      :items="payrolls"
      :search="search"
      disable-initial-sort
      expand
      :loading="loading"
      :rows-per-page-items="[10,20,30,{text:'TODO',value:-1}]"
    >
      <template slot="headerCell" slot-scope="props">
        <v-tooltip top v-if="props.header.tooltip">
          <span slot="activator">
            {{ props.header.text }}
          </span>
          <span>
            {{ props.header.tooltip }}
          </span>
        </v-tooltip>
        <span v-else>
          {{ props.header.text }}
        </span>
      </template>
      <template slot="items" slot-scope="props">
        <v-hover v-if="props.item.contract.employee" close-delay="0" open-delay="150">
          <tr
            slot-scope="{ hover }"
            :class="(props.item.contract.retirement_date != null) ? `warning elevation-${hover ? 15 : 0}` : `elevation-${hover ? 5 : 0}`"
          >
            <td>
              <v-tooltip right>
                <span slot="activator">
                  {{ `${props.item.contract.employee.last_name} ${props.item.contract.employee.mothers_last_name} ${props.item.contract.employee.first_name} ${(props.item.contract.employee.second_name) ? props.item.contract.employee.second_name : ''}` }}
                </span>
                <span>
                  <div>{{ `C.I: ${props.item.contract.employee.identity_card} ${props.item.contract.employee.city_identity_card.shortened}` }}</div>
                  <div>{{ `Cargo: ${props.item.contract.position.name}` }}</div>
                  <div>{{ `Haber: ${props.item.contract.position.charge.base_wage}` }}</div>
                  <div v-if="props.item.contract.contract_number">{{ `Contrato: ${props.item.contract.contract_number}` }}</div>
                </span>
              </v-tooltip>
            </td>
            <td class="text-md-center">
              {{ workedDays(props.item) }}
            </td>
            <td>
              <v-text-field
                v-validate="'required'"
                :error-messages="errors.collect('Días NO Trab.')"
                data-vv-name='Días NO Trab.'
                v-model="props.item.unworked_days"
                class="body-1"
                type="number"
                step="1"
                min="0"
                :max="Number(props.item.unworked_days) + Number(workedDays(props.item))"
                @keyup.enter.native="savePayroll(props.item)"
              ></v-text-field>
            </td>
            <td>
              <v-text-field
                v-validate="'required'"
                :error-messages="errors.collect('RC-IVA')"
                data-vv-name="RC-IVA"
                v-model="props.item.rc_iva"
                class="body-1"
                @keyup.enter.native="savePayroll(props.item)"
              ></v-text-field>
            </td>
            <td>
              <v-text-field
                v-validate="'required'"
                :error-messages="errors.collect('Descuentos')"
                data-vv-name="Descuentos"
                v-model="props.item.faults"
                class="body-1"
                @keyup.enter.native="savePayroll(props.item)"
              ></v-text-field>
            </td>
            <td>
              <v-text-field
                v-validate="'required'"
                :error-messages="errors.collect('Descuentos')"
                data-vv-name="Descuentos"
                v-model="props.item.previous_month_balance"
                class="body-1"
                @keyup.enter.native="savePayroll(props.item)"
              ></v-text-field>
            </td>
            <td class="text-md-center">
              <v-layout wrap>
                <v-flex xs6 pr-3>
                  <v-btn class="accent" @click="savePayroll(props.item)">
                    Guardar
                  </v-btn>
                </v-flex>
                <v-flex xs6 pl-3 v-if="$store.getters.currentUser.roles[0].name == 'admin'">
                  <v-btn class="error" @click="deletePayroll(props.item)">
                    Eliminar
                  </v-btn>
                </v-flex>
              </v-layout>
            </td>
            <td class="text-md-center">
              {{ total(props.item) }}
            </td>
            <td class="text-md-center">
              {{ totalDiscounts(props.item).toFixed(2) }}
            </td>
            <td class="text-md-center">
              {{ $moment(props.item.contract.start_date).format('DD/MM/YYYY') }}
            </td>
            <td class="text-md-center" v-if="props.item.contract.end_date == null">
              Indefinido
            </td>
            <td class="text-md-center" v-else-if="props.item.contract.retirement_date == null">
              {{ $moment(props.item.contract.end_date).format('DD/MM/YYYY') }}
            </td>
            <td class="text-md-center" v-else>
              <v-tooltip top>
                <span slot="activator">
                  {{ $moment(props.item.contract.retirement_date).format('DD/MM/YYYY') }}
                </span>
                <span>
                  Fecha Conclusión: {{ $moment(props.item.contract.end_date).format('DD/MM/YYYY') }}
                </span>
              </v-tooltip>
            </td>
          </tr>
        </v-hover>
      </template>
      <v-alert slot="no-results" :value="true" color="error">
        La búsqueda de "{{ search }}" no encontró resultados.
      </v-alert>
      <template slot="no-data">
        <v-container fluid fill-height>
          <v-layout align-center justify-center>
            <v-progress-circular
              :width="1"
              :size="50"
              color="primary"
              indeterminate
              class="pa-5 ma-5"
            ></v-progress-circular>
          </v-layout>
        </v-container>
      </template>
    </v-data-table>
  </v-container>
</template>

<script>
import Vue from "vue";
import PayrollAdd from "./PayrollAdd";
import RemoveItem from "../RemoveItem";

export default {
  name: "ProcedureEdit",
  components: {
    PayrollAdd,
    RemoveItem
  },
  data() {
    return {
      bus: new Vue(),
      loading: true,
      dialog: false,
      dialogDelete: false,
      procedure: {
        active: true,
        year: null,
        month: {
          name: null
        },
        employee_discount: {
          rc_iva: 0
        }
      },
      payrolls: [],
      contracts: [],
      search: "",
      menuDate: null,
      date: null,
      selectedDate: null
    };
  },
  async created() {
    await this.getProcedure();
    await this.getValidContracts();
    await this.getPayrolls();
  },
  mounted() {
    this.bus.$on("closeDialog", async () => {
      await this.getPayrolls();
    });
  },
  computed: {
    message() {
      if (this.procedure.active) {
        return "cerrar";
      } else {
        return "reabrir";
      }
    },
    headers() {
      return [
        {
          text: "Funcionario",
          value: "contract.employee.last_name"
        },
        {
          text: "Días Trab.",
          tooltip: "Días trabajados",
          align: "center",
          sortable: false,
          value: "contract.employee.mothers_last_name"
        },
        {
          text: "Días NO Trab.",
          tooltip: "Días NO trabajados",
          align: "center",
          sortable: false,
          value: "contract.employee.first_name"
        },
        {
          text: `RC-IVA ${this.procedure.employee_discount.rc_iva * 100}%`,
          align: "center",
          sortable: false,
          value: "contract.employee.identity_card"
        },
        {
          text: `Descuentos`,
          tooltip:
            "Descuentos por Atrasos, Abandonos, Faltas y Licencia S/G Haberes",
          align: "center",
          sortable: false
        },
        {
          text: `Saldo tributario`,
          tooltip:
            "Saldo tributario del mes anterior (Planilla tributaria. A-3)",
          align: "center",
          sortable: false
        },
        { text: `Acciones`, align: "center", value: "", sortable: false },
        {
          text: `Líquido pagable`,
          align: "center",
          value: "",
          sortable: false
        },
        {
          text: `Total descuentos`,
          tooltip: `Renta vejez ${this.procedure.employee_discount.elderly *
            100}%, Riesgo común ${this.procedure.employee_discount.common_risk *
            100}%, Comisión ${this.procedure.employee_discount.comission *
            100}%, Aporte solidario del asegurado ${this.procedure
            .employee_discount.solidary *
            100}%, Aporte Nacional solidario ${this.procedure.employee_discount
            .national *
            100}% y Descuentos por Atrasos, Abandonos, Faltas y Licencia S/G Haberes`,
          align: "center",
          value: "",
          sortable: false
        },
        {
          text: `Inicio contrato`,
          align: "center",
          value: "contract.start_date"
        },
        { text: `Fin contrato`, align: "center", value: "contract.end_date" }
      ];
    }
  },
  watch: {
    date(val) {
      this.selectedDate = this.$moment(this.date).format("DD/MM/YYYY");
    },
  },
  methods: {
    deletePayroll(item) {
      this.bus.$emit("openDialogRemove", `/payroll/${item.id}`);
    },
    async deleteProcedure() {
      try {
        let res = await axios.delete(
          `/payroll/drop/${this.procedure.id}`
        );
        this.toastr.warning(
          `Eliminados ${res.data.deleted} registros del mes de ${this.$moment()
            .month(res.data.procedure.month_id - 1)
            .format("MMMM")
            .toUpperCase()} de ${res.data.procedure.year}`
        );
        this.$router.push({
          name: "procedureIndex"
        });
      } catch (e) {
        console.log(e);
        this.toastr.error("Error al eliminar");
      }
    },
    async savePayroll(payroll) {
      try {
        await axios.patch(`/payroll/${payroll.id}`, {
          unworked_days: parseInt(payroll.unworked_days),
          rc_iva: Number(payroll.rc_iva),
          faults: Number(payroll.faults),
          previous_month_balance: Number(payroll.previous_month_balance)
        });
        this.toastr.success("Guardado");
      } catch (e) {
        console.log(e);
        this.toastr.error("Error al guardar");
      }
    },
    async getProcedure() {
      try {
        let res = await axios.get(
          `/procedure/${this.$route.params.id}/discounts`
        );
        this.procedure = res.data;
        if (res.data.pay_date){
          this.selectedDate = this.$moment(res.data.pay_date).format("DD/MM/YYYY");
        }
      } catch (e) {
        console.log(e);
      }
    },
    async getPayrolls() {
      try {
        let res = await axios.get(
          `/procedure/${this.$route.params.id}/payroll`
        );
        this.payrolls = res.data;
        this.loading = false;
        return Promise.resolve();
      } catch (e) {
        console.log(e);
      }
    },
    async getValidContracts() {
      try {
        let res = await axios.get(
          `/contract/valid/${this.procedure.id}`
        );
        this.contracts = res.data;
      } catch (e) {
        console.log(e);
      }
    },
    quotable(payroll) {
      return (
        Number(payroll.contract.position.charge.base_wage) /
        30 *
        this.workedDays(payroll)
      );
    },
    calculateDiscount(payroll, discount) {
      return this.quotable(payroll) * Number(discount);
    },
    calculateTotalDiscountLaw(payroll) {
      return (
        this.calculateDiscount(
          payroll,
          this.procedure.employee_discount.elderly
        ) +
        this.calculateDiscount(
          payroll,
          this.procedure.employee_discount.common_risk
        ) +
        this.calculateDiscount(
          payroll,
          this.procedure.employee_discount.comission
        ) +
        this.calculateDiscount(
          payroll,
          this.procedure.employee_discount.solidary
        ) +
        this.calculateDiscount(
          payroll,
          this.procedure.employee_discount.national
        )
      );
    },
    totalDiscounts(payroll) {
      return (
        this.calculateTotalDiscountLaw(payroll) +
        parseFloat(Number(payroll.faults) || 0)
      );
    },
    total(payroll) {
      return (this.quotable(payroll) - this.totalDiscounts(payroll)).toFixed(2);
    },
    workedDays(payroll) {
      let payrollDate = this.$moment(
        `${this.procedure.year}0${this.procedure.month.order}01`
      );

      let lastDayOfMonth = payrollDate.endOf("month").date();

      let dateStart = this.$moment(payroll.contract.start_date);

      let dateEnd = this.$moment(payroll.contract.end_date);

      let workedDays = 0;

      if (payroll.contract.retirement_date != null) {
        let dateRetirement = this.$moment(`${payroll.contract.retirement_date}T23:59:59.999999`);
        if (dateRetirement.year() == payrollDate.year() && dateRetirement.month() == payrollDate.month()) {
          dateEnd = dateRetirement;
        }
      }

      if (payroll.contract.end_date == null && payroll.contract.retirement_date == null && dateStart.year() <= payrollDate.year() && dateStart.month() < payrollDate.month()) {
        workedDays = 30;
      } else if (
        dateStart.year() == dateEnd.year() &&
        dateStart.month() == dateEnd.month()
      ) {
        if (
          dateEnd.date() == lastDayOfMonth &&
          (lastDayOfMonth < 30 || lastDayOfMonth > 30)
        ) {
          workedDays = 30 - dateStart.date();
        } else {
          workedDays = dateEnd.date() - dateStart.date();
        }
        workedDays += 1;
      } else if (
        dateStart.year() <= payrollDate.year() &&
        dateStart.month() == payrollDate.month()
      ) {
        workedDays = 30 + 1 - dateStart.date();
      } else if (
        dateEnd.year() >= payrollDate.year() &&
        dateEnd.month() == payrollDate.month()
      ) {
        workedDays = dateEnd.date();
      } else if (
        (dateStart.year() <= payrollDate.year() &&
          dateStart.month() < payrollDate.month()) ||
        (dateEnd.year() >= payrollDate.year() &&
          dateEnd.month() > payrollDate.month())
      ) {
        workedDays = 30;
      } else if (
        dateStart.year() < payrollDate.year() &&
        dateEnd.year() > payrollDate.year()
      ) {
        workedDays = 30;
      } else {
        workedDays = 0;
      }

      if (payroll.unworked_days > workedDays) {
        return 0;
      } else {
        return workedDays - payroll.unworked_days;
      }

      return 30;
    },
    async closeProcedure() {
      try {
        let res = await axios.patch(`/procedure/${this.procedure.id}`, {
          active: !this.procedure.active
        });
        this.toastr.warning(
          `Planilla de mes de ${res.data.month.name} cerrada`
        );
        this.$router.push({
          name: "procedureIndex"
        });
      } catch (e) {
        console.log(e);
      }
    },
    async updatePayDate(id, date) {
      let res = await axios.get('/api/v1/procedure/pay_date/'+ id +'/' + date);
    }
  }
};
</script>
