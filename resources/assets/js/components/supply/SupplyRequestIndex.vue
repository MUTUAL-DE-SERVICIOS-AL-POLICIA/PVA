<template>
  <v-container fluid>
    <v-toolbar>
      <v-toolbar-title>
        <v-select
          v-model="requestTypeSelected"
          :items="requestTypes"
          item-text="dislayName"
          item-value="type"
          class="subheading font-weight-medium"
          label="Pedidos"
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
      :items="filteredRequests"
      :search="search"
      :loading="loading"
      :rows-per-page-items="[10,20,30,{text:'TODO', value:-1}]"
      disable-initial-sort
      expand
    >
      <template slot="items" slot-scope="props">
        <tr :class="props.expanded ? 'expanded' : ''">
          <td class="text-md-center" @click="getSubarticles(props)">{{ props.item.nro_solicitud }}</td>
          <td class="text-md-center" @click="getSubarticles(props)">{{ $moment(props.item.created_at).format('L') }}</td>
          <td class="text-md-center" @click="getSubarticles(props)">{{ props.item.delivery_date ? $moment(props.item.delivery_date).format('L') : '-' }}</td>
          <td class="text-md-center" @click="getSubarticles(props)">{{ props.item.observacion }}</td>
          <td class="text-md-center" v-if="props.item.status == 'initiation'">
            <v-btn flat icon color="info" @click.native="printRequest(props.item.id)">
              <v-icon>print</v-icon>
            </v-btn>
          </td>
        </tr>
      </template>
      <template slot="expand" slot-scope="{ item }">
        <v-data-table
          :headers="subHeaders"
          :items="item.subarticles"
          disable-initial-sort
          hide-actions
          class="elevation-4"
        >
          <template slot="items" slot-scope="props">
            <tr class="sub-table">
              <td class="text-md-center">
                {{ props.item.description }}
              </td>
              <td class="text-md-center">
                {{ props.item.pivot.amount }}
              </td>
              <td class="text-md-center">
                {{ props.item.pivot.total_delivered }}
              </td>
            </tr>
          </template>
        </v-data-table>
      </template>
      <v-alert slot="no-results" :value="true" color="error">
        La búsqueda de "{{ search }}" no encontró resultados.
      </v-alert>
      <template slot="no-data" v-if="loading">
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
      <template slot="no-data">
        <v-container fluid fill-height>
          <v-layout align-center justify-center>
              La búsqueda no encontró resultados.
          </v-layout>
        </v-container>
      </template>
    </v-data-table>
  </v-container>
</template>

<script>
export default {
  name: 'SupplyRequestIndex',
  data: () => ({
    loading: true,
    search: '',
    requestTypes: [
      {
        type: 'all',
        dislayName: 'TODOS'
      }, {
        type: 'initiation',
        dislayName: 'NUEVOS'
      }, {
        type: 'delivered',
        dislayName: 'ENTREGADOS'
      }, {
        type: 'canceled',
        dislayName: 'CANCELADOS'
      }
    ],
    requestTypeSelected: 'initiation',
    requests: [],
    headers: [
      { align: "center", text: "Nro", class: ["ma-0", "pa-0"], value: "nro_solicitud" },
      { align: "center", text: "Fecha de Pedido", class: ["ma-0", "pa-0"], value: "created_at" },
      { align: "center", text: "Fecha de Entrega", class: ["ma-0", "pa-0"], value: "delivery_date" },
      { align: "center", text: "Observación", class: ["ma-0", "pa-0"], value: "observacion" }
    ],
    subHeaders: [
      { align: "center", sortable: false, text: "Artículo", class: ["ma-0", "pa-0"], value: "description" },
      { align: "center", sortable: false, text: "Pedido", class: ["ma-0", "pa-0"], value: "amount" },
      { align: "center", sortable: false, text: "Entregado", class: ["ma-0", "pa-0"], value: "total_delivered" }
    ],
  }),
  computed: {
    filteredRequests() {
      if (this.requestTypeSelected == 'all' || this.requestTypeSelected == 'initiation') {
        if (!this.headers.find(o => o.text == 'Acciones')) {
          this.headers.push({ align: "center", sortable: false, text: "Acciones", class: ["ma-0", "pa-0"], value: "" })
        }
      } else {
        this.headers = this.headers.filter(o => o.text != 'Acciones')
      }

      if (this.requestTypeSelected == 'all') {
        return this.requests
      } else {
        return this.requests.filter(o => {
          return o.status == this.requestTypeSelected
        })
      }
    }
  },
  mounted() {
    if (this.$route.params.id) this.printRequest(this.$route.params.id)
    this.getRequests()
  },
  methods: {
    async printRequest(id) {
      let res = await axios({
        method: "GET",
        url: `supply_request/print/${id}`,
        responseType: "arraybuffer"
      });
      let blob = new Blob([res.data], {
        type: "application/pdf"
      });
      printJS(window.URL.createObjectURL(blob));
    },
    async getRequests() {
      try {
        let res = await axios.get(`supply_user/${this.$store.getters.id}`)
        this.requests = await res.data
        this.loading = false
      } catch (e) {
        console.log(e)
      }
    },
    async getSubarticles(props) {
      try {
        let res = await axios.get(`supply_request/${props.item.id}`)
        if (res.data.subarticles.length > 0) {
          props.item.subarticles = res.data.subarticles
          props.expanded = !props.expanded
        } else {
          props.expanded = false
        }
      } catch (e) {
        console.log(e)
      }
    }
  }
}
</script>

<style>
.expanded {
  border-top: 1px solid black;
  background-color: #42B2A6;
}
.sub-table {
  border-bottom: 1px solid black;
}
</style>
