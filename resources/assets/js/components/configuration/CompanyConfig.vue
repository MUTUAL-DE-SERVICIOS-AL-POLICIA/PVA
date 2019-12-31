<template>
  <v-container fluid>
    <v-toolbar>
      <v-toolbar-title>
        Datos Institucionales
      </v-toolbar-title>
      <v-spacer></v-spacer>
    </v-toolbar>
    <v-data-table
      :headers="headers"
      :items="companies"
      :loading="loading"
      :hide-actions="true"
      disable-initial-sort
    >
      <template slot="items" slot-scope="props">
        <tr>
          <td class="text-md-center">
            <v-text-field
              v-validate="'required'"
              :error-messages="errors.collect('Nombre')"
              data-vv-name='Nombre'
              v-model="props.item.name"
              type="text"
              @keyup.enter.native="updateCompany(props.item)"
              class="ma-0 pa-0 centered-input"
            ></v-text-field>
          </td>
          <td class="text-md-center">
            <v-text-field
              v-validate="'required'"
              :error-messages="errors.collect('Acrónimo')"
              data-vv-name='Acrónimo'
              v-model="props.item.shortened"
              type="text"
              @keyup.enter.native="updateCompany(props.item)"
              class="ma-0 pa-0 centered-input"
            ></v-text-field>
          </td>
          <td class="text-md-center">
            <v-text-field
              v-validate="'required'"
              :error-messages="errors.collect('NIT')"
              data-vv-name='NIT'
              v-model="props.item.tax_number"
              type="number"
              @keyup.enter.native="updateCompany(props.item)"
              class="ma-0 pa-0 centered-input"
            ></v-text-field>
          </td>
          <td class="text-md-center">
            <v-text-field
              v-validate="'required'"
              :error-messages="errors.collect('Resolución de designación de Director')"
              data-vv-name='Resolución de designación de Director'
              v-model="props.item.directors_designation_number"
              type="number"
              @keyup.enter.native="updateCompany(props.item)"
              class="ma-0 pa-0 centered-input"
            ></v-text-field>
          </td>
          <td class="text-md-center">
            <v-menu
              v-model="props.item.showDateDialog"
              :close-on-content-click="false"
              :nudge-right="40"
              lazy
              transition="scale-transition"
              offset-y
              full-width
            >
              <v-text-field
                slot="activator"
                class="ma-0 pa-0 centered-input"
                :value="$moment(props.item.directors_designation_date).format('DD/MM/YYYY')"
                readonly
              >
              </v-text-field>
              <v-date-picker
                v-model="props.item.directors_designation_date"
                @input="props.item.showDateDialog = false"
                @change="updateCompany(props.item)"
                locale="es-bo"
                no-title
                first-day-of-week="1"
              ></v-date-picker>
            </v-menu>
          </td>
        </tr>
      </template>
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
    <v-toolbar>
      <v-toolbar-title>
        Números de Empleador
      </v-toolbar-title>
      <v-spacer></v-spacer>
    </v-toolbar>
    <v-data-iterator
      :rows-per-page-items="[9]"
      :total-items="9"
      :items="employerNumbers"
      item-key="id"
      content-tag="v-layout"
      :expand="false"
      row
      wrap
    >
      <template v-slot:item="props">
        <v-flex
          xs12
          sm6
          md4
          lg3
        >
          <v-card :class="props.item.cities.length == 0 ? 'danger' : ''">
            <v-card-title>
              <h4>
                <v-text-field
                  :value="props.item.number"
                  @change="updateEmployerNumber(props.item.id, $event)"
                ></v-text-field>
              </h4>
            </v-card-title>
            <v-switch
              v-model="props.expanded"
              :label="(props.expanded) ? 'Ocultar' : 'Ciudades asociadas'"
              class="pl-3 mt-0"
            ></v-switch>
            <v-divider></v-divider>
            <v-list v-if="props.expanded" dense class="mb-4 grey lighten-2">
              <template v-for="city in cities">
                <v-list-tile :key="city.id">
                  <v-btn @click="switchEmployerNumberCity(props.item.id, city.id)" round :color="hasCity(props.item.cities, city.id) ? 'success' : (!city.employer_number_id ? 'error' : '')" :dark="hasCity(props.item.cities, city.id)">{{ city.name }}</v-btn>
                </v-list-tile>
              </template>
            </v-list>
          </v-card>
        </v-flex>
      </template>
      <template v-slot:footer></template>
    </v-data-iterator>
  </v-container>
</template>

<script>
export default {
  name: 'CompanyConfig',
  data: () => ({
    loading: true,
    employerNumbers: [],
    cities: [],
    companies: [],
    headers: [
      { align: "center", sortable: false, text: "Nombre", class: ["ma-0", "pa-0"], value: "name", width: "25%"},
      { align: "center", sortable: false, text: "Acrónimo", class: ["ma-0", "pa-0"], value: "shortened", width: "10%"},
      { align: "center", sortable: false, text: "NIT", class: ["ma-0", "pa-0"], value: "shortened", width: "10%"},
      { align: "center", sortable: false, text: "Resolución de designación de Director", class: ["ma-0", "pa-0"], value: "directors_designation_number", width: "15%"},
      { align: "center", sortable: false, text: "Fecha de designación de Director", class: ["ma-0", "pa-0"], value: "directors_designation_date", width: "20%"},
    ]
  }),
  computed: {
    formatDate: function (value) {
      return this.$moment(value).format("DD/MM/YYYY");
    }
  },
  beforeMount() {
    this.getCities()
  },
  mounted() {
    this.getCompanies()
    this.getEmployerNumbers()
  },
  methods: {
    async updateEmployerNumber(employerNumberId, value) {
      let index = this.employerNumbers.findIndex(o => o.id == employerNumberId)
      if (this.employerNumbers[index].number != value) {
        try {
          this.loading = true
          let res = await axios.patch(`employer_number/${employerNumberId}`, {
            number: value
          })
          this.employerNumbers[index].number = value
          this.toastr.success('Actualizado correctamente')
        } catch (e) {
          console.log(e)
          this.toastr.error('Error al actualizar')
        } finally {
          this.loading = false
        }
      }
    },
    async switchEmployerNumberCity(employerNumberId, cityId) {
      let employerNumber = this.employerNumbers.find(o => o.id == employerNumberId)
      try {
        this.loading = true
        let res = await axios.patch(`city/${cityId}`, {
          employer_number_id: this.hasCity(employerNumber.cities, cityId) ? null : employerNumberId
        })
        this.getEmployerNumbers()
        this.getCities()
        this.toastr.success('Actualizado correctamente')
      } catch (e) {
        console.log(e)
        this.toastr.error('Error al actualizar')
      } finally {
        this.loading = false
      }
    },
    hasCity(citiesList, cityId) {
      return citiesList.map(o => o.id).includes(cityId)
    },
    async getEmployerNumbers() {
      try {
        this.loading = true
        let res = await axios.get(`employer_number`)
        this.employerNumbers = res.data
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },
    async getCities() {
      try {
        this.loading = true
        let res = await axios.get(`city`)
        this.cities = res.data
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },
    async getCompanies() {
      try {
        this.loading = true
        let res = await axios.get(`company`)
        this.companies = res.data
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },
    async updateCompany(val) {
      try {
        this.loading = true
        let valid = await this.$validator.validateAll();
        if (valid) {
          let res = await axios.patch(`company/${val.id}`, val)
          this.toastr.success('Registro actualizado')
        }
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    }
  }
}
</script>

<style>
.centered-input input {
  text-align: center;
  margin-bottom: -5px;
  padding-top: 15px;
}
</style>