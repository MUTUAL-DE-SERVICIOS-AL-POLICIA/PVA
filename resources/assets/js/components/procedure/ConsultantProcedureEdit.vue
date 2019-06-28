<template>
  <v-container fluid>
    <v-toolbar>
      <v-toolbar-title>CONSULTORES {{ procedure.month.name }}</v-toolbar-title>
      <v-tooltip color="white" role="button" bottom>
        <v-icon slot="activator" class="ml-4">info</v-icon>
        <div>
          <v-alert :value="true" type="warning" class="black--text">RETIROS DE ESTE MES</v-alert>
          <v-alert :value="true" type="info" color="light-green lighten-3" class="black--text">REGISTROS EDITADOS</v-alert>
        </div>
      </v-tooltip>
      <v-spacer></v-spacer>
      <v-menu
      v-model="menuDate"
      :close-on-content-click="false"
      :nudge-right="40"
      lazy
      transition="scale-transition"
      offset-y
      full-width>
        <v-text-field
          class="pl-2 pr-2 ml-1 mr-1"
          slot="activator"
          v-model="selectedDate"
          label="Fecha de Pago"
          prepend-icon="event"
          readonly
        ></v-text-field>
        <v-date-picker
          v-model="date"
          @input="menuDate = false"
          @change="updatePayDate(procedure.id, date)"
          locale="es-bo"
          :min="minDatePay"
          :max="maxDatePay"
          no-title
          first-day-of-week="1"
        ></v-date-picker>
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
          v-if="$store.getters.role == 'admin'"
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
      <v-flex xs2>
        <v-text-field
          class="pl-2 pr-2 ml-1 mr-1"
          v-model="search"
          append-icon="search"
          label="Buscar"
          single-line
          full-width
          clearable
        ></v-text-field>
      </v-flex>
      <ConsultantPayrollAdd :contracts="contracts" :procedure="procedure" :bus="bus"/>
      <RemoveItem :bus="bus"/>
    </v-toolbar>
    <v-data-table
      :headers="headers"
      :items="payrolls"
      :search="search"
      disable-initial-sort
      expand
      :loading="loading"
      :rows-per-page-items="[{text:'TODO',value:-1},10,20,30]"
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
        <v-hover v-if="props.item.consultant_contract.employee" close-delay="0" open-delay="150">
          <tr
            slot-scope="{ hover }"
            :class="(props.item.consultant_contract.retirement_date != null) ? `warning elevation-${hover ? 15 : 0}` : ((props.item.created_at != props.item.updated_at) ? `light-green lighten-3 elevation-${hover ? 15 : 0}` : `elevation-${hover ? 5 : 0}`)"
          >
            <td>
              <v-tooltip right>
                <span slot="activator">
                  {{ `${props.item.consultant_contract.employee.last_name} ${props.item.consultant_contract.employee.mothers_last_name} ${props.item.consultant_contract.employee.first_name} ${(props.item.consultant_contract.employee.second_name) ? props.item.consultant_contract.employee.second_name : ''}` }}
                </span>
                <span>
                  <div>{{ `C.I: ${props.item.consultant_contract.employee.identity_card} ${props.item.consultant_contract.employee.city_identity_card.shortened}` }}</div>
                  <div>{{ `Cargo: ${props.item.consultant_contract.consultant_position.name}` }}</div>
                  <div>{{ `Haber: ${props.item.consultant_contract.consultant_position.charge.base_wage}` }}</div>
                  <div v-if="props.item.consultant_contract.contract_number">{{ `Contrato: ${props.item.consultant_contract.contract_number}` }}</div>
                </span>
              </v-tooltip>
            </td>
            <td class="text-md-center font-weight-medium">
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
                :error-messages="errors.collect('Descuentos')"
                data-vv-name="Descuentos"
                v-model="props.item.faults"
                class="body-1"
                @keyup.enter.native="savePayroll(props.item)"
              ></v-text-field>
            </td>
            <td class="text-md-center">
              <v-layout wrap>
                <v-flex xs6>
                  <v-tooltip top>
                    <v-btn slot="activator" flat icon color="primary" @click="savePayroll(props.item)">
                      <v-icon>check</v-icon>
                    </v-btn>
                    <span>Validar</span>
                  </v-tooltip>
                </v-flex>
                <v-flex xs6 v-if="$store.getters.role == 'admin'">
                  <v-tooltip top>
                    <v-btn slot="activator" flat icon color="error" @click="deletePayroll(props.item)">
                      <v-icon>delete</v-icon>
                    </v-btn>
                    <span>Eliminar</span>
                  </v-tooltip>
                </v-flex>
              </v-layout>
            </td>
            <td class="text-md-center font-weight-bold">
              {{ total(props.item) }}
            </td>
            <td class="text-md-center">
              {{ $moment(props.item.consultant_contract.start_date).format('DD/MM/YYYY') }}
            </td>
            <td class="text-md-center" v-if="props.item.consultant_contract.end_date == null">
              Indefinido
            </td>
            <td class="text-md-center" v-else-if="props.item.consultant_contract.retirement_date == null">
              {{ $moment(props.item.consultant_contract.end_date).format('DD/MM/YYYY') }}
            </td>
            <td class="text-md-center" v-else>
              <v-tooltip top>
                <span slot="activator">
                  {{ $moment(props.item.consultant_contract.retirement_date).format('DD/MM/YYYY') }}
                </span>
                <span>
                  Fecha Conclusión: {{ $moment(props.item.consultant_contract.end_date).format('DD/MM/YYYY') }}
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
import ConsultantPayrollAdd from "./ConsultantPayrollAdd";
import RemoveItem from "../RemoveItem";

export default {
  name: "ConsultantProcedureEdit",
  components: {
    ConsultantPayrollAdd,
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
        }
      },
      payrolls: [],
      contracts: [],
      search: "",
      menuDate: null,
      date: null,
      selectedDate: null,
      minDatePay: null,
      maxDatePay: null
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
          text: `Descuentos`,
          tooltip:
            "Descuentos por Atrasos, Abandonos, Faltas y Licencia S/G Haberes",
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
      this.bus.$emit("openDialogRemove", `/consultant_payroll/${item.id}`);
    },
    async deleteProcedure() {
      try {
        let res = await axios.delete(
          `/consultant_payroll/drop/${this.procedure.id}`
        );
        this.toastr.warning(
          `Eliminados ${res.data.deleted} registros del mes de ${this.$moment()
            .month(res.data.procedure.month_id - 1)
            .format("MMMM")
            .toUpperCase()} de ${res.data.procedure.year}`
        );
        this.$router.push({
          name: "consultantProcedureIndex"
        });
      } catch (e) {
        console.log(e);
        this.toastr.error("Error al eliminar");
      }
    },
    async savePayroll(payroll) {
      try {
        await axios.patch(`/consultant_payroll/${payroll.id}`, {
          unworked_days: parseInt(payroll.unworked_days),
          faults: Number(payroll.faults)
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
          `/consultant_procedure/${this.$route.params.id}`
        );
        this.procedure = res.data;
        this.minDatePay = this.$moment().year(this.procedure.year).month(this.procedure.month.order - 1).date(20).toISOString().split('T')[0]
        this.maxDatePay = this.$moment().year(this.procedure.year).month(this.procedure.month.order).date(20).toISOString().split('T')[0]
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
          `/consultant_procedure/${this.$route.params.id}/payroll`
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
          `/consultant_contract/valid/${this.procedure.id}`
        );
        this.contracts = res.data;
      } catch (e) {
        console.log(e);
      }
    },
    quotable(payroll) {
      return (
        Number(payroll.consultant_contract.consultant_position.charge.base_wage) /
        30 *
        this.workedDays(payroll)
      );
    },
    total(payroll) {
      return (this.quotable(payroll) - parseFloat(Number(payroll.faults)).toFixed(2)).toFixed(2)
    },
    workedDays(payroll) {
      let payrollDate = this.$moment(
        `${this.procedure.year}${this.procedure.month.order.toString().padStart(2, '0')}01`
      );

      let lastDayOfMonth = payrollDate.endOf("month").date();

      let dateStart = this.$moment(payroll.consultant_contract.start_date);

      let dateEnd = (payroll.consultant_contract.end_date == null && payroll.consultant_contract.retirement_date == null) ? this.$moment(`${this.procedure.year}${this.procedure.month.order.toString().padStart(2, '0')}01`).endOf("month") : this.$moment(payroll.consultant_contract.end_date);

      let workedDays = 0;

      if (payroll.consultant_contract.retirement_date != null) {
        let dateRetirement = this.$moment(`${payroll.consultant_contract.retirement_date}T23:59:59.999999`);
        if (dateRetirement.year() == payrollDate.year() && dateRetirement.month() == payrollDate.month()) {
          dateEnd = dateRetirement;
        }
      }

      if (payroll.consultant_contract.end_date == null && payroll.consultant_contract.retirement_date == null && dateStart.year() <= payrollDate.year() && dateStart.month() < payrollDate.month()) {
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
        workedDays = dateEnd.date() > 30 ? 30 : dateEnd.date();
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
        let res = await axios.patch(`/consultant_procedure/${this.procedure.id}`, {
          active: !this.procedure.active
        });
        this.toastr.warning(
          `Planilla de mes de ${res.data.month.name} cerrada`
        );
        this.$router.push({
          name: "consultantProcedureIndex"
        });
      } catch (e) {
        console.log(e);
      }
    },
    async updatePayDate(id, date) {
      try{
        await axios.patch(`/consultant_procedure/${id}`, {
          pay_date: date
        });
        this.toastr.success(
          `Registrado correctamente`
        );
      } catch(e) {
        console.log(e);
      }
    }
  }
};
</script>
