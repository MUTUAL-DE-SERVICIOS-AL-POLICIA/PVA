<template>
  <v-container>
    <v-toolbar>
      <v-toolbar-title>{{ procedure.month.name }}</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-flex xs2>
        <v-text-field
          v-model="search"
          append-icon="search"
          label="Buscar"
          single-line
          hide-details
          full-width
        ></v-text-field>
      </v-flex>
    </v-toolbar>
    <v-data-table
      :headers="headers"
      :items="payrolls"
      :search="search"
      disable-initial-sort
      expand
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
        <tr>
          <td>
            <v-tooltip right>
              <span slot="activator">
                {{ `${props.item.contract.employee.last_name} ${props.item.contract.employee.mothers_last_name} ${props.item.contract.employee.first_name}` }}
              </span>
              <span>
                <div>{{ `C.I: ${props.item.contract.employee.identity_card} ${props.item.contract.employee.city_identity_card.shortened}` }}</div>
                <div>{{ `Cargo: ${props.item.contract.position.name}` }}</div>
                <div>{{ `Haber: ${props.item.contract.position.charge.base_wage}` }}</div>
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
            ></v-text-field>
          </td>
          <td>
            <v-text-field
              v-validate="'required'"
              :error-messages="errors.collect('RC-IVA')"
              data-vv-name="RC-IVA"
              v-model="props.item.rc_iva"
              class="body-1"
            ></v-text-field>
          </td>
          <td>
            <v-text-field
              v-validate="'required'"
              :error-messages="errors.collect('Descuentos')"
              data-vv-name="Descuentos"
              v-model="props.item.faults"
              class="body-1"
            ></v-text-field>
          </td>
          <td>
            <v-text-field
              v-validate="'required'"
              :error-messages="errors.collect('Descuentos')"
              data-vv-name="Descuentos"
              v-model="props.item.previous_month_balance"
              class="body-1"
            ></v-text-field>
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
          <td class="text-md-center">
            {{ $moment(props.item.contract.end_date).format('DD/MM/YYYY') }}
          </td>
          <td class="text-md-center">
            <v-btn class="primary" @click="savePayroll(props.item)">
              Guardar
            </v-btn>
          </td>
        </tr>
      </template>
      <v-alert slot="no-results" :value="true" color="error">
        La búsqueda de "{{ search }}" no encontró resultados.
      </v-alert>
    </v-data-table>
  </v-container>
</template>

<script>
export default {
  name: "ProcedureEdit",
  data() {
    return {
      procedure: {
        year: null,
        month: {
          name: null
        },
        employee_discount: {
          rc_iva: 0
        }
      },
      payrolls: [],
      search: ""
    };
  },
  created() {
    this.getProcedure();
    this.getPayrolls();
  },
  computed: {
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
          text: `RC-IVA ${this.procedure.employee_discount.rc_iva}%`,
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
        { text: `Fin contrato`, align: "center", value: "contract.end_date" },
        { text: `Acciones`, align: "center", value: "", sortable: false }
      ];
    }
  },
  methods: {
    async savePayroll(payroll) {
      try {
        await axios.patch(`/api/v1/payroll/${payroll.id}`, {
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
          `/api/v1/procedure/${this.$route.params.id}/discounts`
        );
        this.procedure = res.data;
      } catch (e) {
        console.log(e);
      }
    },
    async getPayrolls() {
      try {
        let res = await axios.get(
          `/api/v1/procedure/${this.$route.params.id}/payroll`
        );
        this.payrolls = res.data;
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
      return this.quotable(payroll) * Number(discount) / 100;
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
      let payrollDate = {
        year: this.procedure.year,
        month: this.procedure.month.order
      };

      let lastDayOfMonth = new Date(
        payrollDate.year,
        payrollDate.month,
        0
      ).getDate();

      let dateStart = {
        day: new Date(payroll.contract.start_date).getDate() + 1,
        year: new Date(payroll.contract.start_date).getFullYear(),
        month: new Date(payroll.contract.start_date).getMonth() + 1
      };

      let dateEnd = {
        day: new Date(payroll.contract.end_date).getDate() + 1,
        year: new Date(payroll.contract.end_date).getFullYear(),
        month: new Date(payroll.contract.end_date).getMonth() + 1
      };

      let workedDays = 0;

      if (payroll.contract.end_date == null) {
        workedDays = 30;
      } else if (
        dateStart.year == dateEnd.year &&
        dateStart.month == dateEnd.month
      ) {
        if (
          dateEnd.day == lastDayOfMonth &&
          (lastDayOfMonth < 30 || lastDayOfMonth > 30)
        ) {
          workedDays = 30 - dateStart.day;
        } else {
          workedDays = dateEnd.day - dateStart.day;
        }
        workedDays += 1;
      } else if (
        dateStart.year <= payrollDate.year &&
        dateStart.month == payrollDate.month
      ) {
        workedDays = 30 + 1 - dateStart.day;
      } else if (
        dateEnd.year >= payrollDate.year &&
        dateEnd.month == payrollDate.month
      ) {
        workedDays = dateEnd.day;
      } else if (
        (dateStart.year <= payrollDate.year &&
          dateStart.month < payrollDate.month) ||
        (dateEnd.year >= payrollDate.year && dateEnd.month > payrollDate.month)
      ) {
        workedDays = 30;
      } else if (
        dateStart.year < payrollDate.year &&
        dateEnd.year > payrollDate.year
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
    }
  }
};
</script>