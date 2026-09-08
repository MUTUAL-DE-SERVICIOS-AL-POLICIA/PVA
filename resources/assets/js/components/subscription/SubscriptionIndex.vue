<template>
  <v-container fluid>
    <v-toolbar>
      <v-toolbar-title>Calculo de Costo</v-toolbar-title>
    </v-toolbar>
    <v-card>
      <v-card-text>
        <v-alert :value="true" type="error" outline>
          Esta calculadora no almacena información en el servidor; los datos solo se
          usan para mostrar el resultado en pantalla.
        </v-alert>

       
        <v-layout row wrap class="mt-3">
          <v-flex xs12 sm3 class="px-2">
            <v-menu
              :close-on-content-click="false"
              v-model="datePicker.start.display"
              offset-y
              full-width
              max-width="290px"
              min-width="290px"
            >
              <v-text-field
                clearable
                slot="activator"
                v-model="datePicker.start.formattedDate"
                label="Fecha de Inicio"
                hint="Fecha en que comienza el contrato"
                persistent-hint
                prepend-icon="event"
                readonly
              ></v-text-field>
              <v-date-picker
                locale="es-bo"
                v-model="startDate"
                no-title
                @input="datePicker.start.display = false"
                first-day-of-week="1"
              ></v-date-picker>
            </v-menu>
          </v-flex>

          <v-flex xs12 sm3 class="px-2">
            <v-menu
              :close-on-content-click="false"
              v-model="datePicker.end.display"
              offset-y
              full-width
              max-width="290px"
              min-width="290px"
              :disabled="!startDate"
            >
              <v-text-field
                clearable
                slot="activator"
                v-model="datePicker.end.formattedDate"
                label="Fecha Final del Contrato"
                :disabled="!startDate"
                :hint="!startDate ? 'Primero selecciona la Fecha de Inicio' : ''"
                persistent-hint
                prepend-icon="event"
                readonly
              ></v-text-field>
              <v-date-picker
                locale="es-bo"
                :min="datePicker.end.min"
                v-model="endDate"
                no-title
                @input="datePicker.end.display = false"
                first-day-of-week="1"
              ></v-date-picker>
            </v-menu>
          </v-flex>

          <v-flex xs12 sm6 class="px-2">
            <v-autocomplete
              clearable
              v-model="charge_id"
              :items="charges"
              :item-text="chargeSelected"
              item-value="id"
              label="Nivel Salarial (Cuadro de Equivalencia)"
              hint="Determina el Haber Básico mensual"
              persistent-hint
              v-on:change="onSelectCharge"
            ></v-autocomplete>
          </v-flex>
        </v-layout>

    
        <template v-if="startDate && endDate && selectedCharge.base_wage">
          <v-divider class="my-4"></v-divider>

          <div v-if="isValidRange">
            <div class="subheading grey--text text--darken-1 mb-2">RESULTADOS</div>
            <v-layout row wrap align-start>
              <v-flex xs12 sm4 class="text-xs-center py-3 result-col">
                <div class="grey--text">Fecha de Inicio</div>
                <div class="display-1">{{ formattedStartDate }}</div>
              </v-flex>
              <v-flex xs12 sm4 class="text-xs-center py-3 result-col">
                <div class="grey--text">Plazo del Contrato</div>
                <div class="display-1">{{ plazoText }}</div>
              </v-flex>
              <v-flex xs12 sm4 class="text-xs-center py-3">
                <div class="grey--text">Total Contrato</div>
                <div class="display-1">{{ totalFormatted }} Bs.</div>
              </v-flex>
            </v-layout>
          </div>
          <v-alert v-else :value="true" type="error" outline>
            La Fecha Final del Contrato debe ser posterior a la Fecha de Inicio ({{ formattedStartDate }}).
          </v-alert>
        </template>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script>
export default {
  name: "SubscriptionIndex",
  data() {
    return {
      charges: [],
      charge_id: null,
      selectedCharge: { name: "", base_wage: "" },
      startDate: null,
      endDate: null,
      months: [],
      datePicker: {
        start: { formattedDate: null, display: false },
        end: { formattedDate: null, display: false, min: null }
      }
    };
  },
  computed: {
    formattedStartDate() {
      return this.startDate ? this.$moment(this.startDate).format("L") : "";
    },
    isValidRange() {
      return this.startDate && this.endDate && !this.$moment(this.endDate).isBefore(this.$moment(this.startDate));
    },
    
    completeMonthsCount() {
      return this.months.filter(o => o.type === "complete").length;
    },
  
    partialDaysTotal() {
      return this.months.filter(o => o.type === "partial").reduce((sum, o) => sum + o.count, 0);
    },
    plazoText() {
      let parts = [];
      if (this.completeMonthsCount > 0) {
        parts.push(`${this.completeMonthsCount} mes${this.completeMonthsCount > 1 ? "es" : ""}`);
      }
      if (this.partialDaysTotal > 0) {
        parts.push(`${this.partialDaysTotal} días`);
      }
      return parts.join(" y ") || "0 días";
    },
    total() {
      return this.months.reduce((total, o) => parseFloat(o.salary) + total, 0);
    },
    totalFormatted() {
      return this.total.toLocaleString("es-BO", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });
    }
  },
  watch: {
   
    startDate(value) {
      if (value) {
        this.datePicker.start.formattedDate = this.$moment(value).format("L");
        this.datePicker.end.min = value;
        if (this.endDate && this.$moment(this.endDate).isBefore(this.$moment(value))) {
          this.endDate = null;
          this.datePicker.end.formattedDate = null;
        }
      } else {
        this.datePicker.start.formattedDate = null;
        this.datePicker.end.min = null;
      }
      this.calculate();
    },
    endDate(value) {
      if (value) {
        this.datePicker.end.formattedDate = this.$moment(value).format("L");
      } else {
        this.datePicker.end.formattedDate = null;
      }
      this.calculate();
    }
  },
  created() {
    this.getCharges();
  },
  methods: {
    async getCharges() {
      try {
        let res = await axios.get("/charge");
       
        this.charges = res.data.filter(e => e.active);
      } catch (e) {
        console.log(e);
      }
    },
    chargeSelected: charge => `${charge.base_wage} Bs. - ${charge.name}`,
    onSelectCharge(id) {
      if (id) {
        this.selectedCharge = this.charges.find(o => o.id == id);
      } else {
        this.selectedCharge = { name: "", base_wage: "" };
      }
      this.calculate();
    },
   
    calculate() {
      this.months = [];

      if (!(this.startDate && this.endDate && this.selectedCharge.base_wage)) {
        return;
      }

      let startDate = this.$moment(this.startDate);
      let endDate = this.$moment(this.endDate);

      if (endDate.isBefore(startDate)) {
        return;
      }

      let dailyRate = this.selectedCharge.base_wage / 30;

      if (endDate.isSame(startDate, "month", "year")) {
        // Todo el rango cae dentro de un mismo mes.
        let count = Number(endDate.format("D")) - Number(startDate.format("D")) + 1;
        let lastDayOfMonth = Number(endDate.clone().endOf("month").format("D"));
        let isFullMonth = lastDayOfMonth == count && Number(startDate.format("D")) == 1;

        this.months.push({
          name: startDate.format("MMMM YYYY"),
          count: isFullMonth ? 30 : count,
          salary: (dailyRate * (isFullMonth ? 30 : count)).toFixed(2),
          type: isFullMonth ? "complete" : "partial"
        });
        return;
      }

    
      let firstMonthLastDay = Number(startDate.clone().endOf("month").format("D"));
      let count = firstMonthLastDay - Number(startDate.format("D")) + 1;
      this.months.push({
        name: startDate.format("MMMM YYYY"),
        count: count,
        salary: (dailyRate * count).toFixed(2),
        type: "partial"
      });

      let cursor = startDate.clone();

      while (!endDate.isSame(cursor, "month", "year")) {
        if (!endDate.isSame(cursor.add(1, "month"), "month", "year")) {
         
          this.months.push({
            name: cursor.format("MMMM YYYY"),
            count: 30,
            salary: Number(this.selectedCharge.base_wage).toFixed(2),
            type: "complete"
          });
        } else {
         
          let lastCount = Number(endDate.format("D"));
          let lastDayOfMonth = Number(endDate.clone().endOf("month").format("D"));
          let isFullMonth = lastCount == lastDayOfMonth;

          this.months.push({
            name: endDate.format("MMMM YYYY"),
            count: isFullMonth ? 30 : lastCount,
            salary: (dailyRate * (isFullMonth ? 30 : lastCount)).toFixed(2),
            type: isFullMonth ? "complete" : "partial"
          });
        }
      }
    }
  }
};
</script>

<style scoped>
@media (min-width: 600px) {
  .result-col {
    border-right: 1px solid rgba(0, 0, 0, 0.12);
  }
}
</style>

<style>
.v-select__selection {
  max-width: calc(100% - 12px);
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
</style>