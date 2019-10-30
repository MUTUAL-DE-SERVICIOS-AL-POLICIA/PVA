<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="close" scrollable>

    <v-card>
      <v-card-text>
        <form ref="form">
          <v-text-field
            v-model="formData.name"
            label="Nombre"
            required
          ></v-text-field>
          <v-text-field
            v-model="formData.contact"
            label="Contacto"
            required
          ></v-text-field>
          <v-text-field
            v-model="formData.nit"
            label="Nit"
            required
          ></v-text-field>
          <v-text-field
            v-model="formData.phone"
            label="Telefono"
            required
          ></v-text-field>
          <v-text-field
            v-model="formData.mobile"
            label="Celular"
            required
          ></v-text-field>


<!--
          <v-layout wrap>
            <v-flex xs12 sm6 md6>
              <v-layout wrap>
                <v-flex xs5 sm5 md5 pr-2>
                  <v-text-field
                    v-validate="'required'"
                    :error-messages="errors.collect('Carnet de Identidad')"
                    data-vv-name="Carnet de Identidad"
                    v-model="edit.identity_card"
                    label="C.I."
                  ></v-text-field>
                </v-flex>
                <v-spacer></v-spacer>

              </v-layout>

            </v-flex>

          </v-layout>
-->
        </form>
      </v-card-text>



      <v-card-actions v-if="dialog2Status == 'add'">
        <v-spacer></v-spacer>
        <v-btn color="error" @click.native="close"><v-icon>close</v-icon> Cancelar</v-btn>
        <v-btn color="success" :disabled="this.errors.any()" @click.native="saveProvider"><v-icon>check</v-icon> Guardar</v-btn>
      </v-card-actions>
      <v-card-actions v-else="dialog2Status == 'update'">
        <v-spacer></v-spacer>
        <v-btn color="error" @click.native="close"><v-icon>close</v-icon> Cancelar</v-btn>
        <v-btn color="success" :disabled="this.errors.any()" @click.native="updateProvider"><v-icon>check</v-icon> Modificar</v-btn>
      </v-card-actions>

    </v-card>
  </v-dialog>
</template>





<script>
export default {
  name: 'SupplyRequest',
  props: ['bus'],
  data() {
    return {
      name2:'',
      cities:'',
      genders:'',
      formData:{},
      edit: {},
      dialog: false,
      dialog2: false,
      dialog2Status:'add',
      dialogNullify: false,
      maxRequest: 14,
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
      request: {
        id: null,
        number: null,
        employee: null
      },
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
      if (val.length >= this.maxRequest) this.toastr.warning(`El número máximo de pedidos es ${maxRequest}`)
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
    //this.getMaterials()
    this.bus.$on('openDialogSupply', data => {

      //res = axios.post(`/provider`, this.formData);
      //this.formData.name = "hola estas";
      //this.subarticles = data.supplyRequest.subarticles
      this.dialog2Status = "add";
      this.formData = {};
      this.dialog = true
    });

    this.bus.$on('editDialogSupply', data => {

      console.log(data);
      this.editDialogSupplyAll(data);

    });



  },
  methods: {
    async editDialogSupplyAll(item) {
      let res;
      /*
      res = axios.post(`/provider`, this.formData);
      this.formData.name = "hola estas";
      this.subarticles = data.supplyRequest.subarticles
      this.dialog = true
      */
      this.dialog2Status = "update";
      res = await axios.get(`/provider/${item.id}`, this.formData);
      //console.log(res.data);
      this.formData = res.data[0];
      this.dialog = true




      //this.formData.name = item.name;
      //this.subarticles = data.supplyRequest.subarticles
      //this.dialog = true


    },
    async saveProvider() {
      let res;
      res = await axios.post(`/provider`, this.formData);

      this.close();

      this.toastr.success('Agregado correctamente');
      this.bus.$emit('addRequest', res.data);
    },
    async updateProvider() {
      let res;

      res = await axios.patch(`/provider/${this.formData.id}`, this.formData);
      this.toastr.success('Actualizado correctamente')

      this.dialog = false;


      this.bus.$emit('datatableUpdate', res.data);

      //this.close();
      //this.toastr.success('Agregado correctamente');
      //this.bus.$emit('addRequest', res.data);
    },
    close() {
      this.dialog = false;
    },
    maxDelivered(item) {
      if (item.stock < item.amount) {
        return item.stock
      } else {
        return item.amount
      }
    },
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
      this.request = {
        id: null,
        number: null,
        employee: null
      }
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
        if (await this.$validator.validateAll()) {
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
                res = await axios.patch(`supply_request/${this.request.id}`, {
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
              res = await axios.patch(`supply_request/${this.request.id}`, {
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
        }
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
