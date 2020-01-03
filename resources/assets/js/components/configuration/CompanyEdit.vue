<template>
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
            @keyup.enter.prevent="updateCompany"
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
            @keyup.enter.prevent="updateCompany"
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
            @keyup.enter.prevent="updateCompany"
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
            @keyup.enter.prevent="updateCompany"
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
              @change="updateCompany"
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
</template>

<script>
export default {
  name: 'CompanyEdit',
  data() {
    return {
      loading: true,
      companies: [],
      headers: [
        { align: "center", sortable: false, text: "Nombre", class: ["ma-0", "pa-0"], value: "name", width: "25%"},
        { align: "center", sortable: false, text: "Acrónimo", class: ["ma-0", "pa-0"], value: "shortened", width: "10%"},
        { align: "center", sortable: false, text: "NIT", class: ["ma-0", "pa-0"], value: "shortened", width: "10%"},
        { align: "center", sortable: false, text: "Resolución de designación de Director", class: ["ma-0", "pa-0"], value: "directors_designation_number", width: "15%"},
        { align: "center", sortable: false, text: "Fecha de designación de Director", class: ["ma-0", "pa-0"], value: "directors_designation_date", width: "20%"},
      ]
    }
  },
  computed: {
    formatDate: function (value) {
      return this.$moment(value).format("DD/MM/YYYY")
    }
  },
  mounted() {
    this.getCompanies()
  },
  methods: {
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
    async updateCompany() {
      try {
        this.loading = true
        let valid = await this.$validator.validateAll()
        if (valid) {
          if (this.companies.length > 0) {
            let res = await axios.patch(`company/${this.companies[0].id}`, this.companies[0])
            this.toastr.success('Registro actualizado')
          }
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