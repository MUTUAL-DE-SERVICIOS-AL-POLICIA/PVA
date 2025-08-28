<template>
  <v-dialog
    persistent
    v-model="dialog"
    max-width="1000px"
    @keydown.esc="closeDialog"
  >
    <v-card>
      <v-toolbar dark color="secondary">
        <v-btn
              icon
              color="secondary"
              @click="cancelForm()"
              class="ma-0 pa-0"
            >
              <v-icon>close</v-icon>
        </v-btn>
        <v-toolbar-title class="white--text">
          {{ formTitle }} - {{ fullName(employee) }}
        </v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn small color="primary" @click="casCreate">
          <v-icon left>add</v-icon> Nuevo registro CAS
        </v-btn>
      </v-toolbar>

      <!-- Tabla -->
      <v-data-table
        :headers="headers_employee_get_cas"
        :items="cas_employee"
        disable-initial-sort
        hide-actions
        class="ma-4"
      >
        <template v-slot:items="{ item }">
          <tr :class="item.active ? 'warning' : 'normal'" class="text-md-center">
            <td>{{ item.certification_number }}</td>
            <td>{{ formatDate(item.issue_date) }}</td>
            <td>{{ item.years }}</td>
            <td>{{ item.months }}</td>
            <td>{{ item.days }}</td>
            <td>{{ activeText(item.active) }}</td>
            <td>{{ item.created_at }}</td>
            <td>
              <v-icon 
                small 
                @click="editItem(item)" 
                color="blue"
                v-if="$store.getters.permissions.includes('edit-cas')"
                >
                  edit
              </v-icon>
            </td>
          </tr>
        </template>
      </v-data-table>

      <!-- Formulario creación/edición -->
      <v-card-text v-if="showForm">
        <v-container>
          <v-form ref="form">
            <v-layout wrap>
              <v-flex xs12 sm6>
                <v-text-field
                  v-model="selectedItem.certification_number"
                  label="Número de Certificación CAS"
                  dense
                  clearable
                  class="ml-3"
                />
              </v-flex>

              <v-flex xs12 sm6>
                <v-menu
                  v-model="datePicker.emission_date.display"
                  :close-on-content-click="false"
                  offset-y
                  full-width
                  max-width="290px"
                  min-width="290px"
                >
                  <v-text-field
                    slot="activator"
                    v-model="datePicker.emission_date.formattedDate"
                    label="Fecha de Emisión"
                    prepend-icon="event"
                    dense
                    clearable
                    class="ml-3"
                  />
                  <v-date-picker
                    v-model="selectedItem.emission_date"
                    no-title
                    @input="datePicker.emission_date.display = false"
                    first-day-of-week="1"
                  />
                </v-menu>
              </v-flex>
            </v-layout>

            <v-layout wrap>
              <v-flex xs12 sm4>
                <v-text-field
                  type="number"
                  v-model.number="selectedItem.years"
                  dense
                  label="Años"
                  clearable
                  min="0"
                  class="ml-3"
                />
              </v-flex>
              <v-flex xs12 sm4>
                <v-text-field
                  type="number"
                  v-model.number="selectedItem.months"
                  dense
                  label="Meses"
                  clearable
                  min="0"
                  class="ml-3"
                />
              </v-flex>
              <v-flex xs12 sm4>
                <v-text-field
                  type="number"
                  v-model.number="selectedItem.days"
                  dense
                  label="Días"
                  clearable
                  min="0"
                  class="ml-3"
                />
              </v-flex>
              <!-- <v-flex xs12>
                <v-checkbox v-model="selectedItem.for_vacation">
                  <template #append>Cuenta para vacación</template>
                </v-checkbox>
              </v-flex> -->
            </v-layout>
          </v-form>
        </v-container>
      </v-card-text>

      <!-- Botones -->
      <v-card-actions v-if="showForm">
        <v-spacer></v-spacer>
        <v-btn color="error" @click="cancelForm">
          <v-icon>close</v-icon> Cancelar
        </v-btn>
        <v-btn
          :color="isEdit ? 'warning' : 'success'"
          @click="saveForm"
        >
          <v-icon left>check</v-icon>
          {{ isEdit ? 'Actualizar' : 'Guardar' }}
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: "CasForm",
  props: ["item", "bus"],
  data() {
    return {
      dialog: false,
      showForm: false,
      isEdit: false,
      formTitle: "REGISTRO DE CAS",
      employee: {},
      cas_employee: [],
      datePicker: {
        emission_date: { formattedDate: null, display: false },
      },
      selectedItem: {},
      headers_employee_get_cas: [
        { text: "Nro. Certificación", value: "certification_number" },
        { text: "Fecha de Emisión", value: "issue_date" },
        { text: "Años", value: "years" },
        { text: "Meses", value: "months" },
        { text: "Días", value: "days" },
        { text: "Estado", value: "active" },
        { text: "Fecha de registro", value: "created_at", sortable: false },
        { text: "Acciones", value: "actions" },
      ],
    };
  },
  watch: {
    "selectedItem.emission_date"(value) {
      if (value) {
        this.datePicker.emission_date.formattedDate =
          this.$moment(value).format("L");
      }
    },
  },
  created() {
    this.bus.$on("openDialogCas", (item) => {
      this.employee = item;
      this.dialog = true;
      this.getCasEmployee();
    });
  },
  methods: {
    fullName(e) {
      return `${e.last_name || ""} ${e.mothers_last_name || ""} ${
        e.first_name || ""
      } ${e.second_name || ""}`.replace(/\s+/g, " ").trim().toUpperCase();
    },
    activeText(s) {
      return s ? "Activo" : "Inactivo";
    },
    formatDate(d) {
      if (!d) return "";
      const [y, m, day] = d.split("-");
      return `${day}/${m}/${y}`;
    },
    async getCasEmployee() {
      const res = await axios.get(`/getCasEmployee/${this.employee.id}`);
      this.cas_employee = res.data;
    },
    casCreate() {
      this.clearForm();
      this.isEdit = false;
      this.showForm = true;
    },
    editItem(item) {
      this.selectedItem = { ...item, emission_date: item.issue_date };
      this.isEdit = true;
      this.showForm = true;
    },
    cancelForm() {
      this.clearForm();
      this.showForm = false;
      this.dialog = false;
    },
    clearForm() {
      this.selectedItem = {
        years: null,
        months: null,
        days: null,
        certification_number: null,
        emission_date: null,
        affiliate_id: null,
        //for_vacation: false,
      };
      this.datePicker.emission_date.formattedDate = null;
    },
    async saveForm() {
      try {
        if (this.isEdit) {
          await axios.patch(`/cas_certification/${this.selectedItem.id}`, {
            ...this.selectedItem,
            issue_date: this.selectedItem.emission_date,
            employee_id: this.employee.id,
          });
          this.toastr.success("Registro actualizado correctamente");
        } else {
          await axios.post(`/cas_certification`, {
            ...this.selectedItem,
            issue_date: this.selectedItem.emission_date,
            employee_id: this.employee.id,
          });
          this.toastr.success("Registro creado correctamente");
        }
        this.getCasEmployee();
        this.cancelForm();
      } catch (e) {
        console.log(e);
        this.toastr.error("Ocurrió un error");
      }
    },
    closeDialog() {
      this.dialog = false;
      this.cancelForm();
    },
  },
};
</script>
