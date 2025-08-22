<template>
  <v-container fluid>
    <v-toolbar>
      <v-toolbar-title> Reportes </v-toolbar-title>
      <v-spacer></v-spacer>
    </v-toolbar>
    <card>
      <v-layout>
        <v-flex xs12 sm6 md6>
          <v-card class="mt-4 pa-4">

            <v-toolbar-title class="pb-4">
              <b>Seleccione un reporte</b>
            </v-toolbar-title>

            <!-- Sección del select -->
            <v-select
              v-model="report_selected"
              :items="reports_items"
              item-text="name"
              return-object
              label="Seleccione un reporte"
              outlined
              dense
            />

            <!-- Sección de criterios dinámicos -->
            <template v-if="report_selected && visible">
              <template v-if="report_selected.criterios.length > 0">
                <div class="pt-4">
                  <b>Seleccione criterio</b>
                </div>

                <div v-for="criterio in report_selected.criterios" :key="criterio">
                  <!-- Si el criterio es position_group_id -->
                  <v-select
                    v-if="criterio === 'position_group_id'"
                    v-model="report_inputs[criterio]"
                    :items="position_group"
                    item-text="name"
                    item-value="id"
                    label="Seleccione el Área"
                    outlined
                    dense
                  />

                  <!-- Si el criterio es fecha -->
                  <!-- <v-text-field
                    v-else-if="criterio === 'fecha'"
                    v-model="report_inputs[criterio]"
                    type="date"
                    label="Seleccione la fecha"
                    outlined
                    dense
                  /> -->
                </div>
              </template>

              <!-- Botón de descarga -->
              <v-btn
                color="primary"
                @click="downloadReport()"
                :loading="loading"
              >
                Descargar reporte
              </v-btn>
            </template>
          </v-card>
        </v-flex>
      </v-layout>
    </card>
  </v-container>
</template>

<script>
export default {
  name: "ReportNotification",
  data: () => ({
    current_date: null,
    position_group: null,
    loading: false,
    reports_items: [],
    report_selected: null,
    visible: false,
    report_inputs: {
      position_group_id: null,
    },
  }),
  created() {
    this.reports_items = [
      {
        id: 0,
        name: "REPORTE DE VACACIONES GENERAL",
        tab: 0,
        criterios: ["position_group_id"],
        service: "print_report_vacation",
        type: "pdf",
        permissions: "",
      },
      {
        id: 1,
        name: "REPORTE DE ASIGNACIONES DE VACACIONES ANULADAS",
        tab: 0,
        criterios: [],
        service: "print_cancelled_report",
        type: "pdf",
        permissions: "",
      },
    ];
  },
  mounted() {
    this.getPositionGroup();
  },
  watch: {
    report_selected: {
      deep: true,
      handler(val) {
        this.visible = true;
        this.report_inputs = {};
      },
    },
  },
  methods: {
    async getPositionGroup() {
      try {
        let response = await axios.get("position_group");
        this.position_group = response.data;
        this.position_group.unshift({ id: -1, name: "TODAS" });
      } catch (e) {
        console.log(e);
      }
    },

    async downloadReport() {
      if (!this.report_selected) return;

      try {
        this.loading = true;

        // servicio según el reporte seleccionado
        let new_url = this.report_selected.service;

        // construcción de parámetros
        let params = [];
        this.report_selected.criterios.forEach((criterio) => {
          if (this.report_inputs[criterio] != null && this.report_inputs[criterio] !== "") {
            params.push(`${criterio}=${this.report_inputs[criterio]}`);
          }
        });
        //une los parámetros en formato de query string
        if (params.length > 0) {
          new_url += `?${params.join("&")}`;//ejm print_report_vacation?position_group_id=5&user_id=10
        }

        let res = await axios({
          method: "GET",
          url: new_url,
          responseType: "arraybuffer",
        });

        let blob = new Blob([res.data], { 
          type: "application/pdf" 
        });
        printJS(window.URL.createObjectURL(blob));

      } catch (e) {
        console.log(e);
      } finally {
        this.loading = false;
      }
    },

    clearInputs() {
      this.report_selected = null;
      this.report_inputs = {};
    },
  },
};
</script>



