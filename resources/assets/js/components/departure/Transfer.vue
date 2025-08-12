<template>
  <v-dialog v-model="dialog" persistent width="1000px" @keydown.esc="close" scrollable>
    <template v-slot:activator="{ on }">
      <v-tooltip top>
        <v-btn slot="activator" v-on="on" flat icon :color="color">
          <v-icon>attach_money</v-icon>
        </v-btn>
        <span>Pasajes</span>
      </v-tooltip>
    </template>
    <template>
      <v-card>
        <v-toolbar dark color="secondary">
          <v-toolbar-title class="white--text">Solicitud de Pasajes - {{ departure.id }}</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-btn icon dark @click.native="close">
            <v-icon>close</v-icon>
          </v-btn>
        </v-toolbar>
        <v-card-text>
          <v-container fluid>
            <v-layout row wrap>
              <v-flex xs6>
                <v-text-field v-model="departure.destiny" label="Destino" v-validate="'required'"
                  :error-messages="errors.collect('Destino')" data-vv-name='Destino' class="pr-2" autofocus
                  outline></v-text-field>
              </v-flex>
              <v-flex xs6>
                <v-text-field :value="departure.description" label="Motivo" class="pl-2" readonly></v-text-field>
              </v-flex>
            </v-layout>
            <v-layout row wrap v-show="departure.destiny">
              <v-flex xs12>
                <v-data-table :headers="headers" :items="orderedTransfers" class="elevation-1" hide-actions
                  :rows-per-page-items="[{ text: '', value: -1 }]" :pagination.sync="pagination">
                  <template v-slot:items="props">
                    <td class="text-xs-center">{{ props.item.index + 1 }}</td>
                    <td><v-text-field v-validate="'required|min:3|max:255'"
                        :error-messages="errors.collect(`from${props.index}`)" :data-vv-name="`from${props.index}`"
                        data-vv-as="desde" v-model="props.item.from" clearable autofocus></v-text-field></td>
                    <td><v-text-field v-validate="'required|min:3|max:255'"
                        :error-messages="errors.collect(`to${props.index}`)" :data-vv-name="`to${props.index}`"
                        data-vv-as="hasta" v-model="props.item.to" clearable></v-text-field></td>
                    <td><v-text-field
                        v-validate="{ required: true, decimal: [1, '.'], min_value: [1, '.'], max_value: [200, '.'] }"
                        :error-messages="errors.collect(`cost${props.index}`)" :data-vv-name="`cost${props.index}`"
                        data-vv-as="importe" v-model="props.item.cost" type="number" step="0.5" min="1"
                        clearable></v-text-field></td>
                    <td class="text-xs-center">
                      <v-tooltip top>
                        <template v-slot:activator="{ on }">
                          <v-btn class="pa-0 ma-0" slot="activator" v-on="on" flat icon
                            :disabled="!props.item.from || !props.item.to || !props.item.cost || maxTransfers == transfers.length"
                            color="success" @click="addReturn(props.item.index)">
                            <v-icon>sync</v-icon>
                          </v-btn>
                        </template>
                        <span>Con retorno</span>
                      </v-tooltip>
                      <v-tooltip top>
                        <template v-slot:activator="{ on }">
                          <v-btn class="pa-0 ma-0" slot="activator" v-on="on" flat icon
                            :disabled="transfers.length <= 1 || props.index == 0" color="info"
                            @click="move('prev', props.item.index)">
                            <v-icon>arrow_drop_up</v-icon>
                          </v-btn>
                        </template>
                        <span>Mover arriba</span>
                      </v-tooltip>
                      <v-tooltip top>
                        <template v-slot:activator="{ on }">
                          <v-btn class="pa-0 ma-0" slot="activator" v-on="on" flat icon
                            :disabled="transfers.length <= 1 || props.index == (transfers.length - 1)" color="info"
                            @click="move('next', props.item.index)">
                            <v-icon>arrow_drop_down</v-icon>
                          </v-btn>
                        </template>
                        <span>Mover abajo</span>
                      </v-tooltip>
                      <v-tooltip top>
                        <template v-slot:activator="{ on }">
                          <v-btn class="pa-0 ma-0" slot="activator" v-on="on" flat icon color="error"
                            @click.native="dropTransfer(props.item.index)">
                            <v-icon>cancel</v-icon>
                          </v-btn>
                        </template>
                        <span>Eliminar</span>
                      </v-tooltip>
                    </td>
                  </template>
                  <template v-slot:footer v-if="transfers.length">
                    <td class="text-xs-center font-weight-bold" colspan="3">Total</td>
                    <td class="font-weight-bold subheading">{{ transfers.reduce((total, current) => {
                      if (current.cost)
                        return Number(Number(total) + Number(current.cost)).toFixed(1)
                    }, 0) }} Bs.</td>
                    <td></td>
                  </template>
                </v-data-table>
                <v-btn :disabled="maxTransfers == transfers.length" color="info" @click="addTransfer"><v-icon
                    class="mr-2">add</v-icon>
                  Agregar recorrido</v-btn>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="error" @click.native="close"><v-icon class="mr-2">close</v-icon> Cancelar</v-btn>
          <v-btn color="success" :disabled="this.errors.any()" @click.native="print"><v-icon class="mr-2">print</v-icon>
            Imprimir</v-btn>
        </v-card-actions>
      </v-card>
    </template>
  </v-dialog>
</template>

<script>
export default {
  name: 'transfer',
  props: {
    color: {
      type: String,
      required: true
    },
    departure: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      dialog: false,
      maxTransfers: 12,
      transfers: [],
      headers: [
        {
          text: "Nº",
          value: "index",
          align: "center",
          sortable: false,
          width: '1%'
        }, {
          text: "Desde",
          value: "from",
          align: "center",
          sortable: false,
          width: '30%'
        }, {
          text: "Hasta",
          value: "to",
          align: "center",
          sortable: false,
          width: '30%'
        }, {
          text: "Importe",
          value: "cost",
          align: "center",
          sortable: false,
          width: '15%'
        }, {
          text: "Acciones",
          value: "",
          align: "center",
          sortable: false,
          width: '24%'
        }
      ],
      pagination: {
        sortBy: 'index',
        descending: false
      }
    }
  },
  computed: {
    orderedTransfers() {
      return _.sortBy(this.transfers, 'index')
    }
  },
  watch: {
    'departure.destiny': {
      handler: function (val, oldVal) {
        if (val !== oldVal) {
          this.departure.destiny = val.toUpperCase()
        }
      },
      immediate: true
    },
    dialog(val) {
      if (val) {
        if (this.departure.id == this.$store.getters.departure.id) {
          this.transfers = this.$store.getters.departure.transfers
          this.departure.destiny = this.$store.getters.departure.destiny
        }
      }
    }
  },
  methods: {
    async print() {
      try {
        const payload = {
          requestId: this.departure.id,
          transfers: this.orderedTransfers,
        };
        this.loading = true
        let valid = await this.$validator.validateAll()
        if (valid) {
          let res = await axios({
            method: 'POST',
            url: `departure/${this.departure.id}/transfer`,
            responseType: 'arraybuffer',
            data: {
              destiny: this.departure.destiny,
              transfers: this.orderedTransfers
            }
          })
          let blob = new Blob([res.data], {
            type: "application/pdf"
          })
          printJS(window.URL.createObjectURL(blob))
          await axios2.post("/personal_transpor_tickets", payload);
          this.$store.commit('setDeparture', { ...this.departure, transfers: this.transfers })
          this.dialog = false
        }
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },
    move(order, index) {
      let fromIndex = index
      let toIndex
      if (order == 'prev') {
        toIndex = fromIndex - 1
      } else if (order == 'next') {
        toIndex = fromIndex + 1
      }
      fromIndex = this.transfers.findIndex(o => o.index == fromIndex)
      toIndex = this.transfers.findIndex(o => o.index == toIndex)
      let from = this.orderedTransfers[fromIndex]
      from.index = toIndex
      let to = this.orderedTransfers[toIndex]
      to.index = fromIndex
      this.transfers[fromIndex] = to
      this.transfers[toIndex] = from
    },
    addTransfer() {
      this.transfers.push({
        from: null,
        to: null,
        cost: null,
        index: this.transfers.length
      })
    },
    addReturn(index) {
      const transfer = this.transfers.find(o => o.index == index)
      this.transfers.push({
        from: transfer.to,
        to: transfer.from,
        cost: transfer.cost,
        index: this.transfers.length
      })
    },
    dropTransfer(index) {
      let transfers = this.orderedTransfers
      transfers = transfers.filter(o => o.index != index)
      transfers.forEach((transfer, i) => {
        transfer.index = i
      })
      this.transfers = transfers
    },
    close() {
      this.dialog = false
    }
  }
}
</script>