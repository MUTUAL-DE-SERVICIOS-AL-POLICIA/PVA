<template>
  <v-container fluid>
    <v-toolbar>
      <v-toolbar-title> Escala de Vacaciones </v-toolbar-title>
      <v-spacer></v-spacer>
    </v-toolbar>

    <v-data-table
      :headers="headers"
      :items="vacations"
      :loading="loading"
      disable-initial-sort
      hide-actions
    >
      <template slot="items" slot-scope="props">
        <tr>
          <td class="text-md-center">
            <v-text-field
              class="centered-input"
              mask="###"
              type="number"
              v-model="props.item.from"
              @keyup.enter="updateVacation(props.item)"
            >
              <template #append> <span class="pd">Años trabajados</span> </template>
            </v-text-field>
          </td>
          <td>
            <v-text-field
              class="centered-input"
              mask="###"
              type="number"
              v-model="props.item.to"
              @keyup.enter="updateVacation(props.item)"
            >
              <template #append> <span class="pd">Años trabajados</span> </template>
            </v-text-field>
          </td>
          <td>
            <v-text-field
              class="centered-input"
              mask="###"
              type="number"
              v-model="props.item.days"
              @keyup.enter="updateVacation(props.item)"
              ><template #append> <span class="pd">Dias</span> </template>
            </v-text-field>
          </td>
          <td class="text-md-center">
            <v-checkbox
              v-model="props.item.active"
              @change="updateVacation(props.item)"
              ><template #append>
                {{ activeText(props.item.active) }}
              </template>
            </v-checkbox>
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
  </v-container>
</template>

<script>
export default {
  name: 'VacationConfig',
  data: () => ({
    loading: true,
    vacations: [],
    seniority_bonus: [],
    calculated: [],
    headers: [
      {
        align: "center",
        text: "Desde",
        class: ["ma-0", "pa-0"],
        value: "name",
        width: "25%",
      },
      {
        align: "center",
        text: "Hasta",
        class: ["ma-0", "pa-0"],
        value: "departure_group_id",
        width: "25%",
      },
      {
        align: "center",
        text: "Dias Asignados",
        class: ["ma-0", "pa-0"],
        value: "note",
        width: "25%",
      },
      {
        align: "center",
        sortable: false,
        text: "Estado",
        class: ["ma-0", "pa-0"],
        value: "pay",
        width: "10%",
      }
    ]
  }),
  mounted() {
    this.getVacations()
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
    async getVacations() {
      try {
        let res = await axios.get(`vacations`)
        this.vacations = res.data
        this.loading = false
      } catch (e) {
        console.log(e)
      }
    },
    async updateVacation(item) {
      try {
        let res = await axios.patch(`vacations/${item.id}`, item)
        this.toastr.success("Registro actualizado")
      } catch (e) {
        console.log(e)
      }
    },
    activeText(state) {
      if (state) {
        return "Activo"
      } else {
        return "Inactivo"
      }
    },
  },
}
</script>

<style>
.centered-input input {
  text-align: center;
  margin-bottom: -5px;
  padding-top: 15px;
}
.text-medium {
  font-size: 35px;
}
.pd {
  padding-right:20px
}
</style>
