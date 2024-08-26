<template>
  <v-dialog
    persistent
    v-model="dialog"
    max-width="1000px"
    @keydown.esc="closeDialog"
  >
    <v-card>
      <v-toolbar dark color="secondary">
        <v-toolbar-title class="white--text"
          >{{ formTitle }} - {{ fullName(employee) }}</v-toolbar-title
        >
      </v-toolbar>

      <!-- <v-flex xs12 sm12 md12>
        <v-card>
          <v-card-title><strong>ÚLTIMO CAS ACTIVO:</strong></v-card-title>
          <v-card-text>
            <table class="v-datatable v-table">
              <thead>
                <div v-if="employee.get_cas.length > 0">
                  <div v-for="cas in employee.get_cas" :key="cas.id">
                    <template v-if="cas.active">
                      <tr>
                        <td><strong>Nro. Cert. CAS:</strong></td>
                        <td>{{ cas.certification_number }}</td>
                      </tr>
                      <tr>
                        <td><strong>Fecha de emisión:</strong></td>
                        <td>{{ cas.issue_date }}</td>
                      </tr>
                      <tr>
                        <td><strong>Años:</strong> {{ cas.years }}</td>
                        <td><strong>Meses:</strong> {{ cas.months }}</td>
                        <td><strong>Dias:</strong> {{ cas.days }}</td>
                      </tr>
                    </template>
                  </div>
                </div>
                <div v-else>
                  <strong class="red--text"
                    >-- No se cuenta con ningún registro --</strong
                  >
                </div>
              </thead>
            </table>
          </v-card-text>
        </v-card>
      </v-flex> -->
      <v-data-table
        :headers="headers_employee_get_cas"
        :items="cas_employee"
        disable-initial-sort
        hide-actions
        class="ma-4"
      >
        <template slot="items" slot-scope="props">
          <tr
            class="text-md-center"
            :class="props.item.active ? 'warning' : 'normal'"
          >
            <td class="text-md-center">
              <v-text-field
                class="centered-input"
                v-model="props.item.certification_number"
                @keyup.enter="updateCasCcertification(props.item)"
              >
              </v-text-field>
            </td>
            <td>
              <v-text-field
                class="centered-input"
                type="date"
                v-model="props.item.issue_date"
                @keyup.enter="updateCasCcertification(props.item)"
              >
              </v-text-field>
            </td>
            <td>
              <v-text-field
                class="centered-input"
                type="number"
                v-model="props.item.years"
                @keyup.enter="updateCasCcertification(props.item)"
              >
              </v-text-field>
            </td>
            <td>
              <v-text-field
                class="centered-input"
                type="number"
                v-model="props.item.months"
                @keyup.enter="updateCasCcertification(props.item)"
              >
              </v-text-field>
            </td>
            <td>
              <v-text-field
                class="centered-input"
                type="number"
                v-model="props.item.days"
                @keyup.enter="updateCasCcertification(props.item)"
              >
              </v-text-field>
            </td>
            <td>
              <v-checkbox v-model="props.item.active" readonly
                ><template #append>
                  {{ activeText(props.item.active) }}
                </template></v-checkbox
              >
            </td>
            <td>
              <v-checkbox
                v-model="props.item.for_vacation"
                @change="updateCasCcertification(props.item)"
                ><template #append>Cuenta para vacación
                </template></v-checkbox
              >
            </td>
          </tr>
        </template>
      </v-data-table>
      <v-spacer></v-spacer>
      <v-card-actions v-if="!showMore">
        <v-spacer></v-spacer>
        <v-btn color="error" @click.native="closeDialog"
          ><v-icon>close</v-icon> Cancelar</v-btn
        >
      </v-card-actions>
      <!--NUEVO REGISTRO--->
      <template v-if="!showMore">
        <v-layout align-center justify-center class="ma-4">
          <v-btn flat small @click="showMore = !showMore">
            Nuevo Regitro
            <v-icon dark right>arrow_drop_down</v-icon>
          </v-btn>
        </v-layout>
      </template>
      <template v-if="showMore">
        <v-toolbar class="ma-4">
          <v-toolbar-title> Nuevo registro CAS</v-toolbar-title>
          <v-spacer></v-spacer>
        </v-toolbar>
        <v-card-text>
          <v-container>
            <v-form ref="form">
              <v-layout wrap>
                <v-flex xs12 sm12 md6>
                  <v-text-field
                    v-model="selectedItem.certification_number"
                    label="Número de Certificacion CAS"
                    autocomplete="cc-number"
                    clearable
                  ></v-text-field>
                </v-flex>

                <v-flex xs12 sm12 md6>
                  <v-menu
                    :close-on-content-click="false"
                    v-model="datePicker.emission_date.display"
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
                      v-validate="'required'"
                      name="Fecha de emisión de CAS"
                      :error-messages="errors.collect('Fecha de inicio')"
                      clearable
                    ></v-text-field>
                    <v-date-picker
                      locale="es-bo"
                      v-model="selectedItem.emission_date"
                      no-title
                      @input="datePicker.emission_date.display = false"
                      first-day-of-week="1"
                    ></v-date-picker>
                  </v-menu>
                </v-flex>
              </v-layout>
              <v-layout wrap>
                <v-flex xs12 sm4 md4>
                  <v-text-field
                    v-validate="'numeric|max_value:99'"
                    :maxlength="2"
                    :error-messages="errors.collect('Años')"
                    data-vv-name="Años"
                    v-model.number="selectedItem.years"
                    label="Años"
                    autocomplete="cc-number"
                    clearable
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 sm4 md4>
                  <v-text-field
                    v-validate="'numeric|max_value:11'"
                    :maxlength="2"
                    :error-messages="errors.collect('Meses')"
                    data-vv-name="Meses"
                    v-model="selectedItem.months"
                    label="Meses"
                    autocomplete="cc-number"
                    clearable
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 sm4 md4>
                  <v-text-field
                    v-validate="'numeric|max_value:31'"
                    :maxlength="2"
                    :error-messages="errors.collect('Dias')"
                    data-vv-name="Dias"
                    v-model="selectedItem.days"
                    label="Dias"
                    autocomplete="cc-number"
                    clearable
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 sm4 md4>
                  <v-checkbox v-model="selectedItem.for_vacation"
                    ><template #append>
                      Cuenta para vacación
                    </template></v-checkbox
                  ></v-flex
                ></v-layout
              >
            </v-form>
          </v-container>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="error" @click.native="closeDialog"
            ><v-icon>close</v-icon> Cancelar</v-btn
          >
          <v-btn color="success" @click.native="saveCasCcertification()"
            ><v-icon>check</v-icon> Guardar</v-btn
          >
        </v-card-actions>
      </template>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: "CasForm",
  props: ["item", "bus"],
  data() {
    return {
      employee: {
        last_name: "",
        mothers_last_name: "",
        first_name: "",
        second_name: "",
        get_cas: [],
      },
      dialog: false,
      formTitle: "REGISTRO DE CAS",
      datePicker: {
        emission_date: {
          formattedDate: null,
          display: false,
        },
      },
      selectedItem: {
        years: null,
        months: null,
        days: null,
        certification_number: null,
        affiliate_id: null,
        for_vacation: false,
      },
      cas_employee: [],
      headers_employee_get_cas: [
        {
          align: "center",
          text: "Número de Certificació",
          class: ["ma-0", "pa-0"],
          value: "certification_number",
          width: "20%",
        },
        {
          align: "center",
          text: "Fecha de Emisión",
          class: ["ma-0", "pa-0"],
          value: "issue_date",
          width: "20%",
        },
        {
          align: "center",
          text: "Años",
          class: ["ma-0", "pa-0"],
          value: "years",
          width: "15%",
        },
        {
          align: "center",
          text: "Meses",
          class: ["ma-0", "pa-0"],
          value: "months",
          width: "15%",
        },
        {
          align: "center",
          text: "Dias",
          class: ["ma-0", "pa-0"],
          value: "days",
          width: "15%",
        },
        {
          align: "center",
          text: "Estado",
          class: ["ma-0", "pa-0"],
          value: "active",
          width: "10%",
        },
        {
          align: "center",
          text: "Considerado para Vac.",
          class: ["ma-0", "pa-0"],
          value: "for_vacation",
          width: "10%",
        },
      ],
      showMore: false,
    };
  },
  created() {
    this.bus.$on("openDialogCas", (item) => {
      if (item) {
        this.employee = item;
        console.log(item);
        this.formTitle = "REGISTRO DE CAS";
        this.dialog = true;
        this.getCasEmployee(item.id);
      }
    });
  },
  mounted() {},
  watch: {
    "selectedItem.emission_date": function (value) {
      if (value) {
        let date = this.$moment(value);
        this.datePicker.emission_date.formattedDate = date.format("L");
      }
    },
  },
  methods: {
    clearForm() {
      this.datePicker = {
        emission_date: {
          formattedDate: null,
          display: false,
        },
      };
      this.selectedItem = {
        years: null,
        months: null,
        days: null,
        certification_number: null,
        emission_date: null,
        affiliate_id: null,
        for_vacation: null,
      };
    },
    formatDate(date) {
      if (!date) return null;
      const [year, month, day] = date.split("-");
      return `${day}/${month}/${year}`;
    },
    closeDialog() {
      this.dialog = false;
      this.$validator.reset();
      this.bus.$emit("closeDialog");
      this.clearForm();
      this.showMore = false;
    },
    async getCasEmployee() {
      try {
        let res = await axios.get(`/getCasEmployee/${this.employee.id}`);
        this.cas_employee = res.data;
        console.log(res.data);
      } catch (e) {
        console.log(e);
      }
    },
    // async saveCas() {
    //   try {
    //     let res;
    //     res = await axios.post(`/cas_certification`, {
    //       years: this.selectedItem.years,
    //       months: this.selectedItem.months,
    //       days: this.selectedItem.days,
    //       issue_date: this.selectedItem.emission_date,
    //       certification_number: this.selectedItem.certification_number,
    //       employee_id: this.employee.id,
    //       for_vacation: this.selectedItem.for_vacation
    //     });
    //     console.log(res);
    //     this.closeDialog();
    //   } catch (e) {
    //     console.log(e);
    //   }
    // },
    fullName(employee) {
      let names = `${employee.last_name || ""} ${
        employee.mothers_last_name || ""
      } ${employee.surname_husband || ""} ${employee.first_name || ""} ${
        employee.second_name || ""
      } `;
      names = names.replace(/\s+/gi, " ").trim().toUpperCase();
      return names;
    },
    activeText(state) {
      if (state) {
        return "Activo";
      } else {
        return "Inactivo";
      }
    },
    async saveCasCcertification() {
      try {
        let res;
        res = await axios.post(`/cas_certification`, {
          years: this.selectedItem.years,
          months: this.selectedItem.months,
          days: this.selectedItem.days,
          issue_date: this.selectedItem.emission_date,
          certification_number: this.selectedItem.certification_number,
          employee_id: this.employee.id,
          for_vacation: this.selectedItem.for_vacation,
        });
        console.log(res);
        this.toastr.success("Se guardó correctamente")
        this.closeDialog();
      } catch (e) {
        console.log(e);
        this.toastr.error("Ocurrio un error")
      }
    },
    async updateCasCcertification(item) {
      try {
        let res;
        res = await axios.patch(`/cas_certification/${item.id}`, {
          years: item.years,
          months: item.months,
          days: item.days,
          issue_date: item.emission_date,
          certification_number: item.certification_number,
          employee_id: item.employee_id,
          for_vacation: item.for_vacation,
        });
        console.log(res);
        this.toastr.success("Se actualizó correctamente")
        this.closeDialog();
      } catch (e) {
        console.log(e);
        this.toastr.error("Ocurrio un error")
      }
    },
  },
};
</script>
