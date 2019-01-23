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
          :label="requestButton.title"
        ></v-select>
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn v-if="showRequest && requestButton.state" @click.native="sendRequest()" class="danger white--text ml-0">
        <div class="font-weight-regular subheading pa-2">ENVIAR PEDIDO</div>
      </v-btn>
      <v-btn v-if="showRequest" @click.native="switchRequest()" class="primary white--text ml-0">
        <div class="font-weight-regular subheading pa-2">{{ requestButton.text }}</div>
      </v-btn>
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
          <td class="text-md-center">{{ remaining(props.item) }}</td>
          <td class="text-md-center">{{ props.item.unit }}</td>
          <td>
            <v-text-field
              class="ma-0 pa-0 centered-input"
              v-model="props.item.request"
              type="number"
              step="1"
              min="0"
              :max="Number(props.item.request) + Number(remaining(props.item))"
              @keyup.enter.native="makeRequest(props.item)"
              @change="makeRequest(props.item)"
              @blur="makeRequest(props.item)"
            ></v-text-field>
          </td>
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
  name: "SupplyIndex",
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
      { align: "center", text: "Stock", class: ["ma-0", "pa-0"], value: "stock" },
      { align: "center", text: "Unidad", class: ["ma-0", "pa-0"], value: "unit" },
      { align: "center", text: "Pedido", class: ["ma-0", "pa-0"], value: "" },
    ],
    supplyRequest: [],
    requestButton: {
      state: false,
      text: 'VER PEDIDO',
      title: "Artículos de almacén"
    }
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
        this.requestButton.state = false
        return this.supplies
      } else {
        this.requestButton.state = false
        return this.supplies.filter(o => {
          return o.material_id == this.materialSelected
        })
      }
    },
    showRequest() {
      if (this.supplies.filter(o => o.request > 0).length > 0) {
        if (this.requestButton.state) {
          this.requestButton.title = "Pedidos de almacén"
          this.requestButton.text = 'VER ARTÍCULOS'
        } else {
          this.requestButton.title = "Artículos de almacén"
          this.requestButton.text = 'VER PEDIDO'
        }
        return true
      } else {
        this.materialSelected = 0
        return false
      }
    }
  },
  methods: {
    async sendRequest() {
      try {
        let res = await axios.post(`subarticle`, {
          employee: {
            id: this.$store.getters.id
          },
          supplyRequest: this.supplyRequest
        })
      } catch (e) {
        console.log(e)
      }
    },
    switchRequest() {
      if (this.requestButton.state) {
        this.materialSelected = 0
      } else {
        this.materialSelected = -1
      }
      this.requestButton.state = !this.requestButton.state
      this.search = ''
    },
    remaining(item) {
      return item.stock - item.request
    },
    makeRequest(item) {
      if (item.request > 0) {
        let requestId = this.supplyRequest.findIndex(o => o.id == item.id)
        if (requestId == -1) {
          this.supplyRequest.push(item)
        } else {
          this.supplyRequest[requestId].request = item.request
        }
      } else {
        this.supplyRequest = this.supplyRequest.filter(o => o.id != item.id)
      }
    },
    async printRequest() {
      try {
        let res = await axios.post('/request',this.supplyRequest);
      } catch (e) {
        console.log(e);
      }
    },
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

<style>
.centered-input input {
  text-align: center
}
</style>
