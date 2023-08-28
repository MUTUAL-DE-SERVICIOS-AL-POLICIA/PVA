<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="closeDialog">
    <v-card>
      <v-toolbar dark color="secondary">
        <v-toolbar-title class="white--text">{{ formTitle }} - {{fullName(employee)}}</v-toolbar-title>
      </v-toolbar>
      <v-card-text>
        <v-container grid-list-md layout>
          <v-layout wrap>
            <v-flex xs12 sm6 md6>
              <v-form ref="form">
                <v-text-field
                  v-model="selectedItem.certification_number"
                  label="Número de Certificacion CAS"
                  autocomplete='cc-number'
                  clearable
                ></v-text-field>
                <v-layout wrap>
                  <v-flex>
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
                <v-layout>
                <v-flex xs12 sm4 md4>
                <v-text-field
                  v-validate="'numeric|max_value:99'"
                  :maxlength="2"
                  :error-messages="errors.collect('Años')"
                  data-vv-name="Años"
                  v-model.number="selectedItem.years"
                  label="Años"
                  autocomplete='cc-number'
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
                  autocomplete='cc-number'
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
                  autocomplete='cc-number'
                  clearable
                ></v-text-field>
                </v-flex>
                </v-layout>
              </v-form>
            </v-flex>
            <v-flex xs12 sm6 md6>
              <v-card>
                <v-card-title><strong>ÚLTIMO CAS ACTIVO:</strong></v-card-title>
                <v-card-text>
                  <table class="v-datatable v-table">
                    <thead>
                      <div v-if="employee.get_cas.length > 0">
                        <div v-for="cas in employee.get_cas" :key="cas.id">
                          <template v-if="cas.active">
                            <tr><td><strong>Nro. Cert. CAS:</strong></td><td>{{ cas.certification_number }}</td></tr>
                            <tr><td><strong>Fecha de emisión:</strong></td><td>{{ cas.issue_date }}</td></tr>
                            <tr><td><strong>Años:</strong> {{ cas.years }}</td>
                                <td><strong>Meses:</strong> {{ cas.months }}</td>
                                <td><strong>Dias:</strong> {{ cas.days }}</td></tr>
                          </template>
                        </div>
                      </div>
                      <div v-else><strong class="red--text">-- No se cuenta con ningún registro --</strong></div>
                    </thead>
                  </table>
                </v-card-text>
              </v-card>
            </v-flex>
          </v-layout>
        </v-container>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="error" @click.native="closeDialog"><v-icon>close</v-icon> Cancelar</v-btn>
        <v-btn color="success" @click.native="saveCas()"><v-icon>check</v-icon> Guardar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import { log } from 'util';
export default {
  name: "CasForm",
  props: ["item", "bus"],
  data() {
    return {
      employee: {
          last_name: '',
          mothers_last_name: '',
          first_name: '',
          second_name: '',
          get_cas:[]
      },
      dialog: false,
      formTitle: "REGISTRO DE CAS",
      datePicker: {
          emission_date: {
            formattedDate: null,
            display: false
          }
      },
      selectedItem: {
        years:null,
        months:null,
        days:null,
        certification_number:null,
        affiliate_id:null
      },
    }
  },
  created() {
    //this.getEmployees()
  },
  mounted() {
    this.bus.$on("openDialogCas", item => {
      if (item) {
        this.employee = item
        this.formTitle = 'REGISTRO DE CAS'
        this.dialog = true

      }
    })
  },
  watch: {
    'selectedItem.emission_date': function(value) {
      if (value) {
        let date = this.$moment(value)
        this.datePicker.emission_date.formattedDate = date.format('L')
      }
    }
  },
  methods: {
    clearForm() {
      this.datePicker = {
       emission_date: {
         formattedDate: null,
         display: false
       }
      }
      this.selectedItem = {
        years:null,
        months:null,
        days:null,
        certification_number:null,
        emission_date:null,
        affiliate_id:null
      }
    },
    formatDate (date) {
      if (!date) return null
      const [year, month, day] = date.split('-')
      return `${day}/${month}/${year}`
    },
    closeDialog() {
      this.dialog = false
      this.$validator.reset()
      this.bus.$emit("closeDialog")
      this.clearForm()
    },
    // async getEmployees() {
    //   try {
    //     let res = await axios.get("/employee")
    //     this.employees = res.data
    //   } catch (e) {
    //     console.log(e)
    //   }
    // },
    async saveCas(){
      try {
        let res
        res = await axios.post(`/cas_certification`,{
          years:this.selectedItem.years,
          months:this.selectedItem.months,
          days:this.selectedItem.days,
          issue_date:this.selectedItem.emission_date,
          certification_number:this.selectedItem.certification_number,
          employee_id:this.employee.id
        }
      )
      console.log(res)
      this.closeDialog()
      } catch (e) {
        console.log(e)
      }
    },
    fullName(employee) {
      let names = `${employee.last_name || ""} ${employee.mothers_last_name ||
        ""} ${employee.surname_husband || ""} ${employee.first_name ||
        ""} ${employee.second_name || ""} `;
      names = names
        .replace(/\s+/gi, " ")
        .trim()
        .toUpperCase();
      return names;
    },
  }
}
</script>
