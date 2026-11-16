
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
            <div class="subheading grey--text text--darken-1 mb-2">
              RESULTADOS
            </div>

            <v-layout row wrap align-start>
              <v-flex
                xs12
                sm4
                class="text-xs-center py-3 result-col"
              >
                <div class="grey--text">Fecha de Inicio</div>
                <div class="display-1">
                  {{ formattedStartDate }}
                </div>
              </v-flex>

              <v-flex
                xs12
                sm4
                class="text-xs-center py-3 result-col"
              >
                <div class="grey--text">Plazo del Contrato</div>
                <div class="display-1">
                  {{ plazoText }}
                </div>
              </v-flex>

              <v-flex xs12 sm4 class="text-xs-center py-3">
                <div class="grey--text">Total Contrato</div>
                <div class="display-1">
                  {{ totalFormatted }} Bs.
                </div>
              </v-flex>
            </v-layout>
          </div>

          <v-alert v-else :value="true" type="error" outline>
            La Fecha Final del Contrato debe ser posterior a la Fecha de Inicio
            ({{ formattedStartDate }}).
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

      selectedCharge: {
        name: "",
        base_wage: ""
      },

      startDate: null,
      endDate: null,

      months: [],

      datePicker: {
        start: {
          formattedDate: null,
          display: false
        },
        end: {
          formattedDate: null,
          display: false,
          min: null
        }
      }
    };
  },

  computed: {
    // Fecha de inicio formateada
    formattedStartDate() {
      return this.startDate
        ? this.$moment(this.startDate).format("L")
        : "";
    },

    // Validar que la fecha final no sea anterior a la inicial
    isValidRange() {
      return (
        this.startDate &&
        this.endDate &&
        !this.$moment(this.endDate).isBefore(
          this.$moment(this.startDate),
          "day"
        )
      );
    },

    // Sumar todos los días comerciales
    totalCommercialDays() {
      return this.months.reduce(
        (sum, month) => sum + month.count,
        0
      );
    },

    // Cada 30 días equivalen a un mes
    completeMonthsCount() {
      return Math.floor(this.totalCommercialDays / 30);
    },

    // Días restantes después de completar los meses
    partialDaysTotal() {
      return this.totalCommercialDays % 30;
    },

    // Mostrar el plazo del contrato
    plazoText() {
      const parts = [];

      if (this.completeMonthsCount > 0) {
        parts.push(
          `${this.completeMonthsCount} mes${
            this.completeMonthsCount > 1 ? "es" : ""
          }`
        );
      }

      if (this.partialDaysTotal > 0) {
        parts.push(
          `${this.partialDaysTotal} día${
            this.partialDaysTotal !== 1 ? "s" : ""
          }`
        );
      }

      return parts.join(" y ") || "0 días";
    },

    // Calcular el costo total
    total() {
      return this.months.reduce(
        (sum, month) => sum + Number(month.salary),
        0
      );
    },

    // Formatear el total en bolivianos
    totalFormatted() {
      return this.total.toLocaleString("es-BO", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });
    }
  },

  watch: {
    // Observar cambios en la fecha inicial
    startDate(value) {
      if (value) {
        this.datePicker.start.formattedDate =
          this.$moment(value).format("L");

        this.datePicker.end.min = value;

        if (
          this.endDate &&
          this.$moment(this.endDate).isBefore(
            this.$moment(value),
            "day"
          )
        ) {
          this.endDate = null;
        }
      } else {
        this.datePicker.start.formattedDate = null;
        this.datePicker.end.min = null;
        this.endDate = null;
      }

      this.calculate();
    },

    // Observar cambios en la fecha final
    endDate(value) {
      this.datePicker.end.formattedDate = value
        ? this.$moment(value).format("L")
        : null;

      this.calculate();
    }
  },

  created() {
    this.getCharges();
  },

  methods: {
    // Obtener los niveles salariales
    async getCharges() {
      try {
        const res = await axios.get("/charge");

        this.charges = res.data.filter(e => e.active);
      } catch (e) {
        console.error(e);
      }
    },

    // Texto mostrado en el selector salarial
    chargeSelected: charge =>
      `${charge.base_wage} Bs. - ${charge.name}`,

    // Seleccionar nivel salarial
    onSelectCharge(id) {
      this.selectedCharge = id
        ? this.charges.find(o => o.id == id) ||
          { name: "", base_wage: "" }
        : { name: "", base_wage: "" };

      this.calculate();
    },

    // ============================================
    // CALCULO DEL CONTRATO CON MESES DE 30 DIAS
    // ============================================
    calculate() {
      this.months = [];

      const baseWage = Number(
        this.selectedCharge.base_wage
      );

      // Validar los datos necesarios
      if (
        !this.startDate ||
        !this.endDate ||
        !Number.isFinite(baseWage) ||
        baseWage <= 0
      ) {
        return;
      }

      // Convertir las fechas utilizando Moment
      const startDate = this.$moment(
        this.startDate,
        "YYYY-MM-DD",
        true
      );

      const endDate = this.$moment(
        this.endDate,
        "YYYY-MM-DD",
        true
      );

      // Validar fechas
      if (
        !startDate.isValid() ||
        !endDate.isValid() ||
        endDate.isBefore(startDate, "day")
      ) {
        return;
      }

      // El haber básico siempre se divide entre 30
      const dailyRate = baseWage / 30;

      // Comenzar desde el primer día del mes inicial
      const cursor = startDate.clone().startOf("month");

      // Recorrer todos los meses del contrato
      while (cursor.isSameOrBefore(endDate, "month")) {

        const isFirstMonth = cursor.isSame(
          startDate,
          "month"
        );

        const isLastMonth = cursor.isSame(
          endDate,
          "month"
        );

        // Último día real del mes
        const calendarLastDay = cursor.daysInMonth();

        // Día inicial del período de este mes
        const startDay = isFirstMonth
          ? startDate.date()
          : 1;

        // Día final del período de este mes
        const endDay = isLastMonth
          ? endDate.date()
          : calendarLastDay;

        /*
         * NORMALIZACION COMERCIAL:
         *
         * Todos los meses tienen 30 días.
         *
         * Si el día seleccionado coincide con el
         * último día real del mes, equivale al día 30.
         *
         * Si el mes tiene 31 días, el día 31 equivale
         * también al día comercial 30.
         */

        const commercialStartDay =
          startDay === calendarLastDay
            ? 30
            : Math.min(startDay, 30);

        const commercialEndDay =
          endDay === calendarLastDay
            ? 30
            : Math.min(endDay, 30);

        /*
         * CONTABILIZACION INCLUSIVA:
         *
         * Se cuenta el día de inicio y el día final.
         *
         * Por eso se suma +1.
         */

        const count = Math.max(
          0,
          commercialEndDay - commercialStartDay + 1
        );

        // Calcular el salario correspondiente
        if (count > 0) {

          const isFullMonth = count === 30;

          const salary = Number(
            (dailyRate * count).toFixed(2)
          );

          this.months.push({
            name: cursor.format("MMMM YYYY"),
            count: count,
            salary: salary,
            type: isFullMonth
              ? "complete"
              : "partial"
          });
        }

        // Pasar al siguiente mes
        cursor.add(1, "month");
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
