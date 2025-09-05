<template>
  <v-dialog
    persistent
    v-model="dialog"
    max-width="90%"
  >
    <v-card>
      <!-- Toolbar -->
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
          <v-icon left>add</v-icon> Nuevo registro de asignación Vacación
        </v-btn>
      </v-toolbar>

      <!-- Tabla -->
      <v-data-table
        :headers="headers_employee_get_vacation_queue"
        :items="vacation_queue_employee"
        disable-initial-sort
        hide-actions
        class="ma-4"
      >
        <template v-slot:items="{ item }">
          <tr class="text-md-center">
            <td>{{ item.id }}</td>
            <td>{{ formatDateDisplay(item.start_date) }}</td>
            <td>{{ formatDateDisplay(item.end_date) }}</td>
            <td>{{ item.days }}</td>
            <td>{{ item.rest_days }}</td>
            <td>{{ formatDateDisplay(item.max_date) }}</td>
            <td>{{ item.created_at }}</td>
            <td>{{ item.is_valid ? 'Disponible' : 'Vencido' }}</td>
            <td>
              <v-icon 
                small 
                color="red"
                @click="dialogDelete = true; delete_item = item.id">
                  delete
              </v-icon>
            </td>
          </tr>
        </template>
      </v-data-table>

      <!-- Modal de eliminación -->
      <v-dialog
        v-model="dialogDelete"
        width="600"
        persistent
      >
        <v-card>
          <v-card-text class="title">
            ¿Está seguro que desea eliminar el registro? Si es así registre el motivo.
          </v-card-text>
          <v-text-field
            v-model="selectedItem.comment"
            label="Motivo de la eliminación"
            prepend-icon="note"
            dense
            clearable            
            class="ml-3"
            :rules="[
              v => !!v || 'El motivo es obligatorio',
              v => (v && v.length >= 5) || 'Debe tener al menos 5 caracteres'
            ]"
          />
          <v-divider></v-divider>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="success" small @click="dialogDelete = false; clearForm()">
              <v-icon small>check</v-icon> Cancelar
            </v-btn>
            <v-btn color="error" small @click="deleteItem(delete_item)">
              <v-icon small>close</v-icon> Eliminar
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>

      <!-- Formulario creación/edición -->
      <v-card-text v-if="showForm">
        <v-container>
          <v-form ref="form">
            <v-layout wrap>
              <!-- Start Date -->
              <v-flex xs12 sm6>
                <v-menu
                  v-model="datePicker.start_date.display"
                  :close-on-content-click="false"
                  offset-y
                  full-width
                  max-width="290px"
                  min-width="290px"
                >
                  <v-text-field
                    slot="activator"
                    v-model="selectedItem.start_date_input"
                    label="Periodo vacacional desde"
                    prepend-icon="event"
                    dense
                    clearable
                    class="ml-3"
                    placeholder="dd/mm/aaaa"
                    @blur="updateSelectedDate('start_date')"
                  />
                  <v-date-picker
                    v-model="selectedItem.start_date"
                    no-title
                    first-day-of-week="1"
                    @input="onDatePicked('start_date')"
                  />
                </v-menu>
              </v-flex>

              <!-- End Date -->
              <v-flex xs12 sm6>
                <v-menu
                  v-model="datePicker.end_date.display"
                  :close-on-content-click="false"
                  offset-y
                  full-width
                  max-width="290px"
                  min-width="290px"
                >
                  <v-text-field
                    slot="activator"
                    v-model="selectedItem.end_date_input"
                    label="Periodo vacacional hasta"
                    prepend-icon="event"
                    dense
                    clearable
                    class="ml-3"
                    placeholder="dd/mm/aaaa"
                    @blur="updateSelectedDate('end_date')"
                  />
                  <v-date-picker
                    v-model="selectedItem.end_date"
                    no-title
                    first-day-of-week="1"
                    @input="onDatePicked('end_date')"
                  />
                </v-menu>
              </v-flex>
            </v-layout>

            <v-layout wrap>
              <v-flex xs12 sm3>
                <v-text-field
                  type="number"
                  v-model.number="selectedItem.days"
                  dense
                  label="Días otorgados"
                  clearable
                  min="0"
                  class="ml-3"
                />
              </v-flex>
              <v-flex xs12 sm3>
                <v-text-field
                  type="number"
                  v-model.number="selectedItem.rest_days"
                  dense
                  label="Días restantes"
                  clearable
                  min="0"
                  class="ml-3"
                />
              </v-flex>

              <!-- Max Date -->
              <v-flex xs12 sm6>
                <v-menu
                  v-model="datePicker.max_date.display"
                  :close-on-content-click="false"
                  offset-y
                  full-width
                  max-width="290px"
                  min-width="290px"
                >
                  <v-text-field
                    slot="activator"
                    v-model="selectedItem.max_date_input"
                    label="Fecha de Vencimiento"
                    prepend-icon="event"
                    dense
                    clearable
                    class="ml-3"
                    placeholder="dd/mm/aaaa"
                    @blur="updateSelectedDate('max_date')"
                  />
                  <v-date-picker
                    v-model="selectedItem.max_date"
                    no-title
                    first-day-of-week="1"
                    @input="onDatePicked('max_date')"
                  />
                </v-menu>
              </v-flex>
            </v-layout>
            <v-layout wrap>
              <v-flex xs12>
                <v-checkbox
                  v-model="selectedItem.is_valid"
                  label="¿Vigente?"
                  class="ml-3"
                ></v-checkbox>
              </v-flex>
            </v-layout>
          </v-form>
        </v-container>
      </v-card-text>

      <!-- Botones -->
      <v-card-actions v-if="showForm">
        <v-spacer></v-spacer>
        <v-btn color="error" @click="cancelForm()">
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
      formTitle: "ASIGNACIÓN INDIVIDUAL DE VACACIONES",
      employee: {},
      vacation_queue_employee: [],
      datePicker: {
        start_date: { display: false },
        end_date: { display: false },
        max_date: { display: false }
      },
      selectedItem: {
        start_date: null,
        start_date_input: "",
        end_date: null,
        end_date_input: "",
        max_date: null,
        max_date_input: "",
        days: null,
        rest_days: null,
        comment: null
      },
      headers_employee_get_vacation_queue: [
        { text: "Nro", value: "id"},
        { text: "Periodo vacacional desde", value: "start_date" },
        { text: "Periodo vacacional hasta", value: "end_date" },
        { text: "Días asignados", value: "days" },
        { text: "Días restantes", value: "rest_days" },
        { text: "Fecha de vencimiento", value: "max_date" },
        { text: "Fecha de registro", value: "created_at" },
        { text: "Estado", value: "is_valid" },
        { text: "Acciones", value: "actions" }
      ],
      dialogDelete: false,
      delete_item: null,
    };
  },
  created() {
    this.bus.$on("openDialogVacationAssignment", (item) => {
      this.employee = item;
      this.dialog = true;
      this.getVacationQueueEmployee();
    });
  },
  methods: {
    fullName(e) {
      return `${e.last_name || ""} ${e.mothers_last_name || ""} ${
        e.first_name || ""
      } ${e.second_name || ""}`.replace(/\s+/g, " ").trim().toUpperCase();
    },
    formatDateDisplay(date) {
      if (!date) return "";
      const [y, m, d] = date.split("-");
      return `${d}/${m}/${y}`;
    },
    onDatePicked(field) {
      this.datePicker[field].display = false;
      this.selectedItem[`${field}_input`] = this.formatDateDisplay(this.selectedItem[field]);
    },
    updateSelectedDate(field) {
      const input = this.selectedItem[`${field}_input`];
      if (!input) {
        this.selectedItem[field] = null;
        return;
      }
      if (/^\d{4}-\d{2}-\d{2}$/.test(input)) {
        this.selectedItem[field] = input;
        return;
      }
      if (/^\d{2}\/\d{2}\/\d{4}$/.test(input)) {
        const [d, m, y] = input.split("/");
        this.selectedItem[field] = `${y}-${m}-${d}`;
        return;
      }
      this.selectedItem[field] = null;
    },
    async getVacationQueueEmployee() {
      const res = await axios.get(`/get_vacation_queue_employee/${this.employee.id}`);
      this.vacation_queue_employee = res.data;
    },
    casCreate() {
      this.clearForm();
      this.isEdit = false;
      this.showForm = true;
    },
    cancelForm() {
      this.clearForm()
      this.showForm = false;
      this.dialog = false;
      this.delete_item = null;
    },
    clearForm() {
      this.selectedItem = {
        start_date: null,
        start_date_input: "",
        end_date: null,
        end_date_input: "",
        max_date: null,
        max_date_input: "",
        days: null,
        rest_days: null,
        comment: null
      };
    },
    async saveForm() {
      try {
        await axios.post(`/vacation_queue`, {
          start_date: this.selectedItem.start_date,
          end_date: this.selectedItem.end_date,
          days: this.selectedItem.days,
          rest_days: this.selectedItem.rest_days,
          max_date: this.selectedItem.max_date,
          employee_id: this.employee.id,
          is_valid: this.selectedItem.is_valid || false
        });
        this.toastr.success("Registro creado correctamente");
        this.getVacationQueueEmployee();
        this.cancelForm();
      } catch (e) {
        console.log(e);
        this.toastr.error("Ocurrió un error");
      }
    },
    async deleteItem(item) {
      try {
        await axios.post(`vacation_queue/${item}/delete`, { 
          comment: this.selectedItem.comment
        });
        this.toastr.error("Registro eliminado correctamente");
        this.getVacationQueueEmployee();
        this.cancelForm();
        this.delete_item = null
      } catch (err) {
        console.error(err);
      }
    }
  }
};
</script>
