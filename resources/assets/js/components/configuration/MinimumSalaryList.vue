<template>
  <v-data-table
    :headers="headers_minimum_salary"
    :items="minimum_salary"
    :loading="loading"
    disable-initial-sort
    hide-actions
  >
    <template slot="items" slot-scope="props">
      <tr
        class="text-md-center"
        :class="props.item.active ? 'warning' : 'normal'"
      >
        <td class="text-md-center">
          <v-text-field
            class="centered-input"
            mask="####"
            type="number"
            v-model="props.item.year"
            @keyup.enter="updateMinimunSalary(props.item)"
          >
          </v-text-field>
        </td>
        <td>
          <v-text-field
            class="centered-input"
            type="number"
            v-model="props.item.value"
            @keyup.enter="updateMinimunSalary(props.item)"
          >
            <template #append><span class="pd">Bs.</span></template>
          </v-text-field>
        </td>
        <td>
          <v-checkbox
            v-model="props.item.active"
            @change="updateMinimunSalary(props.item)"
            ><template #append>
              {{ activeText(props.item.active) }}
            </template></v-checkbox
          >
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
</template>

<script>
export default {
  name: "MinimumSalary",
  data: () => ({
    loading: true,
    minimum_salary: [],
    headers_minimum_salary: [
      {
        align: "center",
        text: "Gestión",
        class: ["ma-0", "pa-0"],
        value: "year",
        width: "25%",
      },
      {
        align: "center",
        text: "Monto",
        class: ["ma-0", "pa-0"],
        value: "value",
        width: "25%",
      },
      {
        align: "center",
        text: "Estado",
        class: ["ma-0", "pa-0"],
        value: "active",
        width: "15%",
      },
    ],
  }),
  mounted() {
    this.getMinimumSalaries();
  },
  methods: {
    async getMinimumSalaries() {
      try {
        let res = await axios.get(`minimum_salary`);
        this.minimum_salary = res.data;
        this.loading = false;
      } catch (e) {
        console.log(e);
      }
    },
    async updateMinimunSalary(item) {
      try {
        let res = await axios.patch(`minimum_salary/${item.id}`, item);
        this.toastr.success("Salario mínimo actualizado");
      } catch (e) {
        console.log(e);
        console.log(item);
      }
    },
    activeText(state) {
      if (state) {
        return "Activo";
      } else {
        return "Inactivo";
      }
    },
  },
};
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
.pd {
  padding-right: 20px;
}
</style>
