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
            <!-- Sub título  -->
            <!-- <v-toolbar-title>
              <b>Seleccione un reporte</b>
            </v-toolbar-title> -->
            <!-- Fin subtitulo -->

            <!-- Sección del select -->
            <v-select
              v-model="report_selected"
              :items="reports_items"
              item-text="name"
              return-object
              label="Seleccione un reporte"
              outlined
              dense
            >
            </v-select>

            <template v-if="report_selected && visible == true">
              <template
                v-if="report_selected.criterios.includes('position_group_id')"
              >
                <v-toolbar-title>
                  <b>Criterios de búsqueda</b>
                </v-toolbar-title>
                <v-progress-linear class="mb-5"></v-progress-linear>
              </template>

              <template>
                <v-select
                  v-model="report_inputs.position_group_id"
                  :items="position_group"
                  item-text="name"
                  item-value="id"
                  label="Seleccione el Área"
                  outlined
                  dense
                >
                </v-select>
              </template>

              <!-- Botón de descarga -->
              <v-btn
                color="primary"
                @click="downloadReport()"
                :loading="loading"
                >Descargar reporte
              </v-btn>
              <!-- Fin de botón de descarga -->
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
        criterios: ["position_group_id'"],
        service: "print_report_vacation",
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
      },
    },
  },
  methods: {
    async getPositionGroup() {
      try {
        let response = await axios.get("position_group");
        this.position_group = response.data;
        this.position_group.unshift({ id: -1, name: "TODAS" });
        console.log(response.data);
      } catch (e) {
        console.log(e);
      }
    },
    async downloadReport() {
      try {
        let new_url;
        if (this.report_inputs.position_group_id == -1) {
          new_url = `print_report_vacation`;
        } else {
          new_url = `print_report_vacation?position_group_id=${this.report_inputs.position_group_id}`;
        }
        this.loading = true;
        let res = await axios({
          method: "GET",
          url: new_url,
          responseType: "arraybuffer",
        });
        let blob = new Blob([res.data], {
          type: "application/pdf",
        });
        printJS(window.URL.createObjectURL(blob));
        this.loading = false;
      } catch (e) {
        console.log(e);
        this.loading = false;
      }
    },
    clearInputs() {
      this.report_selected = null;
      this.report_inputs.position_group_id = null;
    },
  },
};
</script>


