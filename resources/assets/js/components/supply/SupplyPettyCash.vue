<template>
  <v-container fluid>
    <v-toolbar>
      <v-toolbar-title>
        <v-select
          :items="['Todas las Solicitudes', 'En Revisión', 'Aceptadas', 'Cancelado']"
          v-model="employeeType"
          class="title font-weight-medium"
          label="Filtrar por Estado"
        ></v-select>
      </v-toolbar-title>

      <v-tooltip color="white" role="button" bottom>
        <v-icon slot="activator" class="ml-4">info</v-icon>
        <div>
          <v-alert :value="true" type="warning" class="black--text"
            >REALIZAR EL FORMULARIO 2</v-alert
          >
          <v-alert :value="true" type="error">FALTAN LA ASIGNACION DE LOS GRUPOS</v-alert>
          <v-alert :value="true" type="info">FINALIZADO</v-alert>
        </div>
      </v-tooltip>
      <v-spacer></v-spacer>

      <v-menu offset-y>
        <v-btn slot="activator" color="primary" dark>
          <template>
            <div>Solicitud de Recursos</div>
          </template>
        </v-btn>
        <v-list>
          <v-list-tile @click="openFormPettyCash">Solicitud de recursos</v-list-tile>
          <v-list-tile @click="openFormRepCash">Reembolsos de gastos</v-list-tile>
          <v-list-tile @click="openFormTransport">Gasto por transporte</v-list-tile>
        </v-list>
      </v-menu>
    </v-toolbar>

    <v-data-table
      :headers="mainHeaders"
      :items="items"
      item-value="id"
      class="elevation-1"
      hide-default-footer
    >
      <template v-slot:items="props">
        <tr>
          <td align="center">{{ props.item.number_note }}</td>
          <td align="center">{{ props.item.request_date }}</td>

          <td align="center">
            <div
              :class="['status-button', getStateClass(props.item.state, props.item)]"
              align="center"
            >
              {{ props.item.state }}
            </div>
          </td>

          <td align="center">
            <v-tooltip bottom v-if="props.item.type_cash_id === 3">
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  icon
                  v-bind="attrs"
                  v-on="on"
                  @click.native="print_form_transport(props.item)"
                >
                  <v-icon color="info">print</v-icon>
                </v-btn>
              </template>
              <span>Imprimir Formulario 1</span>
            </v-tooltip>

            <v-tooltip
              bottom
              v-if="props.item.state !== 'En Proceso' && props.item.type_cash_id !== 3"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  icon
                  v-bind="attrs"
                  v-on="on"
                  @click.native="print_form(props.item)"
                >
                  <v-icon color="info">print</v-icon>
                </v-btn>
              </template>
              <span>Imprimir Formulario 1</span>
            </v-tooltip>

            <v-tooltip
              bottom
              v-if="
                props.item.state === 'En Revision' &&
                props.item.request_date &&
                props.item.type_cash_id !== 3
              "
            >
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  icon
                  v-bind="attrs"
                  v-on="on"
                  @click.native="navigateNext(props.item)"
                >
                  <v-icon color="info">check</v-icon>
                </v-btn>
              </template>
              <span>Continuar con el Formulario 2</span>
            </v-tooltip>
          </td>
        </tr>

        <tr v-if="props.item.showProducts">
          <td colspan="5">
            <v-data-table
              :headers="detailsHeaders2"
              :items="props.item.products"
              class="elevarion-1"
              hide-default-footer
            >
              <template v-slot:items="productProps">
                <td align="left">{{ productProps.item.description }}</td>
                <td align="center">{{ productProps.item.pivot.amount_request }}</td>
                <td align="center">{{ productProps.item.pivot.costDetails }}</td>
                <td align="center">
                  {{
                    productProps.item.pivot.amount_request *
                    productProps.item.pivot.costDetails
                  }}
                </td>
              </template>
            </v-data-table>
          </td>
        </tr>
      </template>
    </v-data-table>

    <v-dialog v-model="dialogDetails" max-width="1400px">
      <v-card>
        <v-card-title>
          <span class="headline">DESCARGO - CAJA CHICA (Bolivianos)</span>
        </v-card-title>
        <v-form v-model="valid">
          <v-card-text>
            <v-data-table
              :headers="detailsHeaders"
              :items="selectedItem.products"
              hide-default-footer
            >
              <template v-slot:items="productProps">
                <td align="left">{{ productProps.item.description }}</td>
                <td align="center">{{ productProps.item.amount_request }}</td>
                <td align="center">{{ productProps.item.costDetail }}</td>
                <td>
                  <v-text-field
                    v-model="productProps.item.supplier"
                    label="Proveedor"
                    dense
                    hide-details
                    class="table-input"
                  />
                </td>
                <td>
                  <v-text-field
                    v-model="productProps.item.numer_invoice"
                    label="N° de Factura"
                    type="number"
                    dense
                    hide-details
                    class="table-input"
                  />
                </td>
                <td>
                  <v-text-field
                    v-model="productProps.item.amount"
                    label="Cantidad"
                    type="number"
                    dense
                    hide-details
                    class="table-input"
                    :rules="[(v) => v >= 0 || 'El precio no puede ser negativo']"
                    min="0"
                  />
                </td>
                <td>
                  <v-text-field
                    v-model="productProps.item.costUnit"
                    label="Precio Unitario"
                    type="number"
                    min="0"
                    dense
                    hide-details
                    class="table-input"
                  />
                </td>
                <td>
                  <v-text-field
                    :value="
                      Number(productProps.item.amount || 0) *
                      Number(productProps.item.costUnit || 0)
                    "
                    label="Total"
                    type="number"
                    dense
                    hide-details
                    class="table-input"
                    readonly
                  />
                </td>
              </template>
            </v-data-table>
            <v-layout class="mt-4" dense>
              <v-flex cols="12" md="6">
                <v-card outlined>
                  <v-card-title class="subtitle-2 font-weight-bold">
                    Montos
                  </v-card-title>
                  <v-divider></v-divider>
                  <v-card-text class="py-2 px-4">
                    <v-layout row wrap align-center justify-space-between>
                      <v-flex xs12 sm4 class="text-center">
                        <div class="kv-label">Precio referencial solicitado</div>
                        <v-chip small label class="ma-0 mt-2" color="grey lighten-3">
                          {{ formatMoney(approxCostNum) }}
                        </v-chip>
                      </v-flex>
                      <v-flex xs12 sm4 class="text-center">
                        <div class="kv-label">Costo total de productos</div>
                        <v-chip
                          small
                          label
                          class="ma-0 mt-2"
                          :color="exceedsApprox ? 'orange lighten-4' : 'green lighten-4'"
                        >
                          {{ formatMoney(productsTotal) }}
                        </v-chip>
                      </v-flex>
                      <v-flex xs12 sm4 class="text-center">
                        <div class="kv-label">Monto a devolver</div>
                        <v-chip
                          small
                          label
                          class="ma-0 mt-2"
                          :color="
                            exceedsApprox
                              ? 'red lighten-4'
                              : productsBalance === 0
                              ? 'grey lighten-4'
                              : 'green lighten-4'
                          "
                        >
                          {{ formatMoney(productsBalance) }}
                        </v-chip>
                      </v-flex>
                    </v-layout>
                  </v-card-text>
                </v-card>
              </v-flex>
            </v-layout>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="red" @click="dialogDetails = false">Cerrar</v-btn>
            <v-btn color="success" @click="submitDetails" :disabled="exceedsApprox"
              >Guardar</v-btn
            >
          </v-card-actions>
        </v-form>
      </v-card>
    </v-dialog>
    <form-request
      v-model="dialog"
      type="petty"
      @close="dialog = false"
      @save="updateRequests"
    />

    <form-request
      v-model="dialogRep"
      type="rep"
      @close="dialogRep = false"
      @save="updateRequests"
    />

    <form-request-transport
      v-model="dialogTransport"
      type="transport"
      @close="dialogTransport = false"
      @save="updateRequests"
    />
  </v-container>
</template>

<script>
import FormRequest from "./FormRequest.vue";
import FormRequestTransport from "./FormRequestTransport.vue";
export default {
  components: { FormRequest, FormRequestTransport },
  computed: {
    approxCostNum() {
      const n = Number(this.selectedItem.approximate_cost);
      return Number.isFinite(n) ? n : 0;
    },
    productsTotal() {
      const list = this.selectedItem.products || [];
      return list.reduce((acc, p) => {
        const qty = Number(p.amount) || 0;
        const unit = Number(p.costUnit) || 0;
        return acc + qty * unit;
      }, 0);
    },

    productsBalance() {
      return this.approxCostNum - this.productsTotal;
    },
    exceedsApprox() {
      return this.productsTotal > this.approxCostNum;
    },
  },
  data() {
    return {
      dialog: false,
      dialogRep: false,
      dialogTransport: false,
      dialogDetails: false,
      dialogConfirmDischarge: false,
      isChanging: false,
      selectedItem: {},
      employeeType: "Todas las Solicitudes",
      detailsVisible: null,
      valid: false,
      items: [],
      budgetGroups: [],
      deadlineTimerId: null,
      mainHeaders: [
        { text: "Nro Solicitud", value: "id", align: "center" },
        { text: "Fecha de entrega de recurso", value: "date", align: "center" },
        { text: "Estado", value: "status", align: "center" },

        { text: "Acciones", value: "actions", sortable: false, align: "center" },
      ],
      detailsHeaders: [
        { text: "Descripcion", value: "description", align: "center" },
        { text: "Cantidad Solicitadad", value: "amount_request", align: "center" },
        { text: "Costo Estimado", value: "costDetails", align: "center" },
        { text: "Proveedor", value: "supplier", align: "center" },
        { text: "N° de Factura", value: "numer_invoice", align: "center" },
        { text: "Cantidad", value: "cost_object", align: "center" },
        { text: "Precio Unitario", value: "id_group", align: "center" },
        { text: "Total", value: "total", align: "center" },
      ],
    };
  },
  mounted() {
    this.userId = localStorage.getItem("id");
    this.getProductsUser();
  },

  methods: {
    formatMoney(value) {
      try {
        return new Intl.NumberFormat("es-BO", {
          style: "currency",
          currency: "BOB",
          minimumFractionDigits: 2,
        }).format(Number(value) || 0);
      } catch {
        return `${Number(value || 0).toFixed(2)} Bs`;
      }
    },
    async getProductsUser() {
      try {
        const res = await axios2.get(`notePettyCash/${this.userId}`);
        this.items = res.data.map((item) => ({ ...item, showProducts: false }));
        this.items.forEach((i) => {
          i._overdueAlerted = false;
          i._soonAlerted = false;
        });
      } catch (error) {
        console.error(error);
      }
    },
    async submitDetails() {
      try {
        const invalidProduct = this.selectedItem.products.find(
          (product) =>
            !product.supplier ||
            !product.numer_invoice ||
            !product.amount ||
            !product.costUnit
        );

        if (invalidProduct) {
          this.toastr.error("Todos los campos deben ser llenados.");
          return;
        }
        const payload = {
          requestId: this.selectedItem.id,
          products: this.selectedItem.products.map((product) => ({
            description: product.description,
            supplier: product.supplier,
            numer_invoice: product.numer_invoice,
            amount: product.amount,
            costUnit: product.costUnit,
            total: product.amount * product.costUnit,
          })),
        };
        await axios2.post("/savePettyCashDetails", payload);
        this.dialogDetails = false;
        this.updateRequests();
      } catch (error) {
        console.error("Error al guardar detalles:", error);
      }
    },
    openFormPettyCash() {
      this.dialog = true;
    },
    openFormRepCash() {
      this.dialogRep = true;
    },
    openFormTransport() {
      this.dialogTransport = true;
    },
    updateRequests() {
      this.getProductsUser();
    },
    toggleDetails(item) {
      this.detailsVisible = this.detailsVisible === item.id ? null : item.id;
    },
    saveRequest(newRequest) {
      this.requests.push(newRequest);
      this.dialog = false;
    },
    getStateClass(state, item) {
      switch (state) {
        case "En Revision":
          return "state-en-revision";
        case "Aceptado":
          return "state-en-aceptado";
        case "Anulado":
          return "state-cancelado";
        case "Finalizado":
          return "state-finalizado";
        default:
          return "";
      }
    },
    toggleMaterials(item) {
      item.showProducts = !item.showProducts;
    },
    async print_form(item) {
      const res = await axios2({
        method: "GET",
        url: `/printPettCash/${item.id}`,
        responseType: "arraybuffer",
      });
      const blob = new Blob([res.data], { type: "application/pdf" });
      printJS(window.URL.createObjectURL(blob));
    },
    async print_form_transport(item) {
      const res = await axios2({
        method: "GET",
        url: `/print_Petty_Cash_Trasnport/${item.id}`,
        responseType: "arraybuffer",
      });
      const blob = new Blob([res.data], { type: "application/pdf" });
      printJS(window.URL.createObjectURL(blob));
    },
    async print_form_discharge(item) {
      if (item["comment_recived"]) {
        this.toastr.error("No se puede imprimir, pase con el encargado de Caja Chica");
        return;
      }
      const res = await axios2({
        method: "GET",
        url: `/printPettCashDischarge/${item.id}`,
        responseType: "arraybuffer",
      });
      const blob = new Blob([res.data], { type: "application/pdf" });
      printJS(window.URL.createObjectURL(blob));
    },
    async navigateNext(item) {
      console.log(item);
      this.selectedItem = item;
      this.dialogDetails = true;
    },
  },
};
</script>

<style scoped>
.status-button {
  display: inline-block;
  border-radius: 4px;
  padding: 4px 8px;
  font-weight: bold;
  color: white;
  text-align: center;
}

.state-en-revision {
  border: 2px solid #ffffff;
  background-color: #5cb0ca;
}

.state-en-aceptado {
  border: 2px solid #ffffff;
  background-color: #4eb355;
}

.state-cancelado {
  border: 2px solid #d32f2f;
  background-color: #d32f2f;
}

.state-finalizado {
  border: 2px solid #ffffff;
  background-color: #0b4474;
}

.kv-layout {
  min-height: 36px;
  padding-top: 0;
  padding-bottom: 0;
}
.kv-label {
  font-weight: 600;
}
.kv-value {
  font-variant-numeric: tabular-nums;
}
ol {
  margin-left: 1rem;
}
ol li {
  margin: 6px 0;
}
</style>
