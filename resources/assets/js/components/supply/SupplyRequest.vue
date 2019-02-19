<template>
  <v-dialog
    v-model="dialog"
    max-width="900px"
    @keydown.esc="closeDialog"
    persistent
    scrollable
  >

    <v-dialog
      v-model="dialogNullify"
      max-width="30%"
      persistent
    >
      <v-card>
        <v-card-text>
          <div class="title font-weight-regular">¿Está seguro de que desea anular la solicitud?</div>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="success" @click.native="dialogNullify = false">
            <v-icon small left>check</v-icon>
            Cancelar
          </v-btn>
          <v-btn color="error" @click.native="sendRequest('cancel')">
            <v-icon small left>close</v-icon>
            Anular
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-card>
      <v-toolbar dark color="secondary">
        <v-toolbar-title class="white--text">Solicitud de Material {{ requestNumber ? `Nº ${requestNumber}` : '' }}</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn color="error" v-if="$route.query.requestType == 'all'" @click.native="dialogNullify = true">
          Anular
        </v-btn>
        <v-btn icon dark @click.native="closeDialog">
          <v-icon>close</v-icon>
        </v-btn>
      </v-toolbar>

      <div>
        <v-stepper v-model="step">
          <v-stepper-items>
            <v-stepper-content
              v-for="n in steps.length"
              :key="`${n}-content`"
              :step="n"
              class="mt-0 pt-0 mb-0 pb-0"
            >
              <v-layout v-if="steps.find(o => { return o.n==n }).title == 'Materiales'">
                <v-flex xs8>
                  <v-select
                    v-model="materialSelected"
                    :items="materials"
                    item-text="description"
                    item-value="id"
                    class="subheading font-weight-medium"
                  ></v-select>
                </v-flex>
                <v-spacer></v-spacer>
                <v-flex xs1>
                  <v-divider class="mx-2" inset vertical></v-divider>
                </v-flex>
                <v-flex xs3>
                  <v-text-field
                    v-model="search"
                    append-icon="search"
                    label="Buscar"
                    single-line
                    hide-details
                    full-width
                  ></v-text-field>
                </v-flex>
              </v-layout>

              <div>
                <v-data-table
                  :headers="headers"
                  :items="step == 2 ? supplyRequest : filteredSupplies"
                  class="elevation-1"
                  :rows-per-page-items="steps.find(o => { return o.n==n }).title == 'Entrega de Material' ? [maxRequest] : [6,20,{text:'TODO', value:-1}]"
                  disable-initial-sort
                  :hide-actions="deliveryForm"
                  :search="search"
                >
                  <template slot="items" slot-scope="props">
                    <td>{{ props.item.description }}</td>
                    <td class="text-xs-center">{{ props.item.unit }}</td>
                    <td class="text-xs-center" v-if="steps.find(o => { return o.n==n }).title == 'Entrega de Material'">{{ props.item.stock }}</td>
                    <td class="text-xs-center" v-if="steps.find(o => { return o.n==n }).title == 'Materiales' || step > 1">
                      <v-text-field
                        class="ma-0 pa-0 centered-input"
                        v-model="props.item.amount"
                        type="number"
                        step="1"
                        min="0"
                        :max="Number(props.item.amount) + Number(remaining(props.item))"
                        @keyup.enter.native="makeRequest(props.item)"
                        @blur="makeRequest(props.item)"
                        :disabled="(supplyRequest.length >= maxRequest || remaining(props.item) == 0) && !itemInRequest(props.item.id)"
                      ></v-text-field>
                    </td>
                    <td v-else class="text-xs-center">{{ props.item.amount }}</td>
                    <td class="text-xs-center" v-if="steps.find(o => { return o.n==n }).title == 'Entrega de Material'">
                      <v-text-field
                        class="ma-0 pa-0 centered-input body-1"
                        v-model="props.item.total_delivered"
                        step="1"
                        type="number"
                        :name="props.item.code"
                        v-validate="'required|min_value:0|max_value:59'"
                        :error-messages="errors.collect(`props.item.code`)"
                        min="0"
                        :max="props.item.stock < props.item.amount ? props.item.stock : props.item.amount"
                      ></v-text-field>
                    </td>
                  </template>
                </v-data-table>
              </div>

              <div class="text-xs-right mt-3" v-if="steps.find(o => { return o.n==n }).title == 'Entrega de Material'">
                <v-btn color="error" @click.native="sendRequest('delivery')">
                  Entregar
                </v-btn>
              </div>
              <div class="text-xs-right mt-3" v-else>
                <v-btn color="primary" v-if="step > 1" @click.native="step-=1">
                  Volver
                </v-btn>
                <v-btn color="error" v-if="step < steps.length" @click.native="nextStep">
                  Siguiente
                </v-btn>
                <v-btn color="error" v-if="step == steps.length && supplyRequest.length > 0" @click.native="sendRequest('request')">
                  Imprimir Pedido
                </v-btn>
              </div>
            </v-stepper-content>
          </v-stepper-items>
        </v-stepper>
      </div>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: 'SupplyRequest',
  props: ['bus'],
  data() {
    return {
      dialog: false,
      dialogNullify: false,
      maxRequest: 15,
      search: '',
      supplyRequest: [],
      materialSelected: 0,
      materials: [{
        id: 0,
        description: 'TODOS'
      }],
      subarticles: [],
      steps: [
        { n: 1,title: 'Materiales' },
        { n: 2, title: 'Pedido'}
      ],
      requestNumber: '',
      requestId: '',
      step: 1,
      headers: [
        { text: 'Material', value: 'description', align: 'center', sortable: false },
        { text: 'Unidad', value: 'unit', align: 'center', sortable: false },
        { text: 'Pedido', value: 'amount', align: 'center', sortable: false }
      ]
    }
  },
  watch: {
    supplyRequest: function (val) {
      if (val.length >= this.maxRequest) this.toastr.warning('El número máximo de pedidos es 15')
    }
  },
  computed: {
    deliveryForm() {
      return (this.headers.map(o => o.text).indexOf('Entregado') > -1)
    },
    filteredSupplies() {
      if (this.materialSelected == -1) {
        return this.subarticles.filter(o => {
          return o.request > 0
        })
      } else if (this.materialSelected == 0) {
        return this.subarticles
      } else {
        return this.subarticles.filter(o => {
          return o.material_id == this.materialSelected
        })
      }
    },
  },
  mounted() {
    this.getMaterials()
    this.bus.$on('openDialogSupply', data => {
      let steps
      switch (data.formType) {
        case 'delivery':
          if (this.headers.map(o => o.text).indexOf('Entregado') == -1) {
            this.headers.push({ text: 'Entregado', value: 'total_delivered', align: 'center', sortable: false })
          }
          if (this.headers.map(o => o.text).indexOf('Stock') == -1) {
            this.headers.splice(2, 0, { text: 'Stock', value: 'stock', align: 'center', sortable: false })
          }
          data.supplyRequest.subarticles.forEach((subarticle) => {
            subarticle.amount = subarticle.pivot.amount
            subarticle.total_delivered = subarticle.pivot.amount
          })
          this.requestNumber = data.supplyRequest.nro_solicitud
          this.requestId = data.supplyRequest.id
          this.step = 1
          steps = [
            {
              n: 1,
              title: 'Entrega de Material'
            }
          ]
          this.steps = steps
          break
        case 'request':
          this.headers = this.headers.filter(o => { return (o.text != 'Stock' && o.text != 'Entregado') })
          steps = [
            {
              n: 1,
              title: 'Materiales'
            }, {
              n: 2,
              title: 'Pedido'
            }
          ]
          this.steps = steps
          break
      }

      this.subarticles = data.supplyRequest.subarticles
      this.dialog = true
    })
  },
  methods: {
    nextStep() {
      if (this.supplyRequest.length > 0) {
        this.step += 1
        this.search = ''
      } else {
        this.toastr.warning('Debe seleccionar algún material')
      }
    },
    closeDialog() {
      this.step = 1
      this.search = ''
      this.requestNumber = ''
      this.requestId = ''
      this.supplyRequest = []
      this.subarticles = []
      this.dialogNullify = false
      this.dialog = false
    },
    async getMaterials() {
      try {
        let res = await axios.get(`/material`)
        this.materials = this.materials.concat(res.data)
      } catch (e) {
        console.log(e)
      }
    },
    itemInRequest(id) {
      return this.supplyRequest.find(o => o.id == id) ? true : false
    },
    remaining(item) {
      return item.stock - item.amount
    },
    makeRequest(item) {
      if (item.amount > 0) {
        let requestId = this.supplyRequest.findIndex(o => o.id == item.id)
        if (requestId == -1) {
          this.supplyRequest.push(item)
        } else {
          this.supplyRequest[requestId].amount = item.amount
        }
      } else {
        this.supplyRequest = this.supplyRequest.filter(o => o.id != item.id)
      }
      this.toastr.success(`Pedidos: ${this.supplyRequest.length} artículos`)
    },
    async sendRequest(type) {
      try {
        let res
        let subarticles
        switch (type) {
          case 'delivery':
            subarticles = this.subarticles.filter(o => { return o.total_delivered > 0 })
            if (subarticles.length > 0) {
              subarticles.forEach(subarticle => {
                subarticle.pivot.total_delivered = subarticle.total_delivered
                subarticle.pivot.amount_delivered = subarticle.total_delivered
              })
              res = await axios.patch(`supply_request/${this.requestId}`, {
                subarticles: subarticles,
                status: 'delivered'
              })
              this.bus.$emit("setRequestState", {
                id: res.data.id,
                request: {
                  delivery_date: res.data.delivery_date,
                  status: res.data.status
                }
              })
            } else {
              this.toastr.error('No se ha entregado ningún artículo')
            }
            break
          case 'request':
            res = await axios.post(`supply_request`, {
              employee: {
                id: this.$store.getters.id
              },
              supplyRequest: this.supplyRequest
            })
            this.toastr.success(`Solicitud realizada correctamente. Solicitud Número: ${res.data.nro_solicitud}`)
            this.bus.$emit('addRequest', res.data)
            break
          case 'cancel':
            subarticles = this.subarticles.filter(o => { return o.total_delivered > 0 })
            if (subarticles.length > 0) {
              subarticles.forEach(subarticle => {
                subarticle.pivot.total_delivered = 0
                subarticle.pivot.amount_delivered = 0
              })
            }
            res = await axios.patch(`supply_request/${this.requestId}`, {
              subarticles: subarticles,
              status: 'canceled'
            })
            this.bus.$emit("setRequestState", {
              id: res.data.id,
              request: {
                delivery_date: res.data.delivery_date,
                status: res.data.status
              }
            })
            break
        }
        this.dialog = false
        this.dialogNullify = false
        this.bus.$emit('printRequest', {
          id: res.data.id,
          type: type
        })
        this.closeDialog
      } catch (e) {
        console.log(e)
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
