<template>
  <v-container fluid>
    <v-toolbar>
      <v-toolbar-title>
        <v-select
          v-model="materialSelected"
          :items="materials"
          item-text="description"
          item-value="id"
          class="subheading font-weight-medium"
        ></v-select>
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <v-divider
        class="mx-2"
        inset
        vertical
      ></v-divider>
      <v-flex xs2>
        <v-text-field
          v-model="search"
          append-icon="search"
          label="Buscar"
          single-line
          hide-details
          full-width
          clearable
        ></v-text-field>
      </v-flex>
    </v-toolbar>
    <v-data-table
      :headers="headers"
      :items="filteredSupplies"
      :search="search"
      :loading="loading"
      :rows-per-page-items="[10,20,30,{text:'TODO', value:-1}]"
      disable-initial-sort
    >
      <template slot="items" slot-scope="props">
        <tr>
          <td class="text-md-center">{{ props.item.description }}</td>
          <td class="text-md-center">{{ props.item.unit }}</td>
          <td class="text-md-center">{{ props.item.stock }}</td>
        </tr>
      </template>
      <v-alert slot="no-results" :value="true" color="error">
        La búsqueda de "{{ search }}" no encontró resultados.
      </v-alert>
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
  name: "SuppliesIndex",
  data: () => ({
    loading: true,
    search: '',
    materials: [{
      id: 0,
      description: 'TODOS'
    }],
    materialSelected: 0,
    supplies: [],
    headers: [
      { align: "center", text: "Descripción", class: ["ma-0", "pa-0"], value: "description" },
      { align: "center", text: "Unidad", class: ["ma-0", "pa-0"], value: "unit" },
      { align: "center", text: "Stock", class: ["ma-0", "pa-0"], value: "stock" },
    ]
  }),
  mounted() {
    this.getMaterials()
    this.getSupplies()
  },
  computed: {
    filteredSupplies() {
      if (this.materialSelected == -1) {
        return this.supplies.filter(o => {
          return o.request > 0
        })
      } else if (this.materialSelected == 0) {
        return this.supplies
      } else {
        return this.supplies.filter(o => {
          return o.material_id == this.materialSelected
        })
      }
    }
  },
  methods: {
    async getMaterials() {
      try {
        let res = await axios.get(`/material`)
        this.materials = this.materials.concat(res.data)
      } catch (e) {
        console.log(e)
      }
    },
    async getSupplies() {
      try {
        let res = await axios.get(`/subarticle`)
        let supplies = await res.data
        await supplies.forEach(supply => supply.request = 0)
        this.supplies = supplies
        this.loading = false
      } catch (e) {
        console.log(e)
      }
    }
  }
}
</script>