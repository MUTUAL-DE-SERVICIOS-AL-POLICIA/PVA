<template>
  <v-container fluid>
    <v-toolbar>
      <v-toolbar-title>
        Rango de Bono de Antiguedad
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <v-divider
        class="mx-2"
        inset
        vertical
      ></v-divider>
    </v-toolbar>
    <tr class="text-medium text-md-center">
      <td><strong>Rango de Bono de Antiguedad Segun CAS</strong></td>
    </tr>
    <tr>
      <td>
        <v-data-table
          :headers="headers_bonus"
          :items="calculated"
          :loading="loading"
          :rows-per-page-items="[{ text: 'TODO', value: -1 }, 10, 20, 30]"
          disable-initial-sort
        >
          <template slot="items" slot-scope="props">
            <tr>
              <td class="text-md-center">
                <v-text-field
                  class="centered-input"
                  mask="###"
                  type="number"
                  v-model="props.item.from"
                  @keyup.enter="updateBonus(props.item)"
                >
                <template #append>
                  Años
                </template>
              </v-text-field>
              </td>
              <td>
                <v-text-field
                  class="centered-input"
                  mask="###"
                  type="number"
                  v-model="props.item.to"
                  @keyup.enter="updateBonus(props.item)"
                >
                <template #append>
                  Años
                </template>
              </v-text-field>
              </td>
              <td>
                <v-text-field
                  class="centered-input"
                  type="number"
                  step="0.01"
                  v-model="props.item.percentage"
                  @keyup.enter="updateBonus(props.item)"
                >
                <template #append>
                  %
                </template>
              </v-text-field>
              </td>
              <td>
                <v-text-field
                  class="centered-input"
                  type="number"
                  step="0.01"
                  v-model="props.item.calculated"
                  readonly
                >
                <template #append>
                  Bs
                </template>
              </v-text-field>
              </td>
              <td>
                <v-checkbox
                  v-model="props.item.active"
                  @change="updateBonus(props.item)"
                ><template #append>
                  {{ activeText(props.item.active) }}
                </template></v-checkbox>
              </td>
            </tr>
          </template>
          <template slot="no-data">
            <v-container fluid fill-height>
              <v-layout align-center justify-center>
                <v-progress-circular
                  :width="1"
                  :size="100"
                  color="primary"
                  indeterminate
                  class="pa-5 ma-5"
                ></v-progress-circular>
              </v-layout>
            </v-container>
          </template>
        </v-data-table>
      </td>
    </tr>
  </v-container>
</template>

<script>
export default {
  name: 'DepartureConfig',
  data: () => ({
    loading: true,
    vacations: [],
    seniority_bonus: [],
    calculated: [],
    headers_bonus: [
      { align: "center", text: "Desde", class: ["ma-0", "pa-0"], value: "name", width: "10%" },
      { align: "center", text: "Hasta", class: ["ma-0", "pa-0"], value: "departure_group_id", width: "10%" },
      { align: "center", text: "Porcentaje", class: ["ma-0", "pa-0"], value: "note", width: "10%" },
      { align: "center", text: "Monto calculado", class: ["ma-0", "pa-0"], value: "note", width: "10%" },
      { align: "center", sortable: false, text: "Estado", class: ["ma-0", "pa-0"], value: "pay", width: "10%" },
    ]
  }),
  mounted() {
    this.getSeniorityBonus()
    this.getBonusCalculate()
  },
  methods: {
    async getBonusCalculate() {
      try {
        let res = await axios.get(`seniority_bonus_calculate`)
        this.calculated = res.data
        this.loading = false
      } catch (e) {
        console.log(e)
      }
    },
    async getSeniorityBonus() {
      try {
        let res = await axios.get(`seniority_bonus`)
        this.seniority_bonus = res.data
        this.loading = false
      } catch (e) {
        console.log(e)
      }
    },
    async updateBonus(item) {
      try {
        let res = await axios.patch(`seniority_bonus/${item.id}`, item)
        this.toastr.success('Registro actualizado')
      } catch (e) {
        console.log(e)
      }
    },
    activeText(state)
    {
      if(state){
        return "Activo"
      }
      else{
        return "Inactivo"
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
.text-medium {
  font-size: 25px;
}
</style>
