<template>
  <v-dialog persistent v-model="dialog" max-width="600px" @keydown.esc="close($moment().year())" scrollable class="pl-4">
    <v-tooltip slot="activator" top>
      <v-icon large slot="activator" dark color="primary">add_circle</v-icon>
      <span>Añadir planilla</span>
    </v-tooltip>
    <v-card>
      <v-toolbar dark color="secondary">
        <v-toolbar-title class="white--text">Nueva Planilla</v-toolbar-title>
      </v-toolbar>
      <v-card-text class="text-xs-center">
        <form>
          <v-layout>
            <v-flex xs12>
              <v-date-picker v-model="dateSelected" :landscape="true" type="month" locale="es-bo"></v-date-picker>
            </v-flex>
          </v-layout>
        </form>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-progress-linear :indeterminate="true" v-if="loading"></v-progress-linear>
        <div v-else>
          <v-btn color="error" @click.prevent="close($moment().year())"><v-icon>close</v-icon> Cancelar</v-btn>
          <v-btn color="success" @click.prevent="saveProcedure"><v-icon>check</v-icon> Generar</v-btn>
        </div>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: "ProcedureAdd",
  props: ["bus"],
  data() {
    return {
      loading: false,
      dialog: false,
      dateSelected: this.$moment().format("YYYY-MM")
    };
  },
  mounted() {
    this.bus.$on("openDialog", () => {
      this.dialog = true;
      this.dateSelected = this.$store.getters.dateNow;
    });
  },
  methods: {
    close(year) {
      this.dialog = false;
      this.bus.$emit("closeDialog", year);
    },
    async saveProcedure() {
      try {
        this.loading = true
        let res = await axios.get(
          `/api/v1/month/order/${Number(this.dateSelected.split("-")[1])}`
        );
        let newProcedure = {
          new: true,
          year: Number(this.dateSelected.split("-")[0]),
          month_id: res.data.id,
          active: true
        };
        let procedure = await axios.post(
          `/api/v1/procedure`,
          newProcedure
        );
        procedure = procedure.data;
        let payrolls = await axios.post(
          `/api/v1/procedure/${procedure.id}/payroll`
        );
        payrolls = payrolls.data;
        this.toastr.success(
          `Generados ${
            payrolls.generated
          } registros para el mes de ${this.$moment()
            .month(procedure.month_id - 1)
            .format("MMMM")
            .toUpperCase()}`
        );
        this.loading = false
        this.close(newProcedure.year);
      } catch (e) {
        this.loading = false
        console.log(e);
      }
    }
  }
};
</script>